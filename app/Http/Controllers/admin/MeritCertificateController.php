<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Coach;
use App\Models\MeritCertificate;
use App\Models\MeritCertificateBatch;
use App\Models\Referee;
use App\Models\Tags;
use App\Services\MeritCertificateRenderer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;
use DataTables;

class MeritCertificateController extends Controller
{
    public function index()
    {
        return view('admin.merit_certificate.select')->with([
            'title' => 'Coach / Referee Certificate',
            'page_title' => 'Select Coach or Referee',
            'districts' => Tags::where('status', 1)->get(),
        ]);
    }

    public function recipientData(Request $request)
    {
        $type = $request->input('recipient_type', 'coach');
        if (!in_array($type, ['coach', 'referee'], true)) {
            $type = 'coach';
        }

        if ($type === 'referee') {
            $query = Referee::where('status', '!=', 3);
        } else {
            $query = Coach::where('status', '!=', 3);
        }

        if (!empty($request['title'])) {
            $query->where('name', 'LIKE', '%' . $request['title'] . '%');
        }
        if (!empty($request['status'])) {
            $query->where('status', $request['status']);
        }
        if (!empty($request['district']) && $request['district'] !== 'all') {
            $query->where('district', $request['district']);
        }

        $rows = $query->orderBy('id', 'DESC')->get();

        return Datatables::of($rows)
            ->addColumn('checkbox', function ($row) {
                return '<input type="checkbox" class="form-check-input recipient-check" value="' . $row->id . '">';
            })
            ->addColumn('district_name', function ($row) {
                $tag = Tags::find($row->district);
                return e($tag->title ?? ($row->district ?? '-'));
            })
            ->addColumn('phone_col', function ($row) {
                if (!empty($row->country_code)) {
                    return e('+' . $row->country_code . ' ' . $row->phone);
                }
                return e($row->phone ?? '-');
            })
            ->addColumn('status_badge', function ($row) {
                if ((int) $row->status === 1) {
                    return '<span class="badge bg-success">Active</span>';
                }
                if ((int) $row->status === 2) {
                    return '<span class="badge bg-warning text-dark">Inactive</span>';
                }
                return '<span class="badge bg-secondary">Unknown</span>';
            })
            ->rawColumns(['checkbox', 'status_badge'])
            ->addIndexColumn()
            ->make(true);
    }

    public function prepare(Request $request)
    {
        $type = $request->input('recipient_type', 'coach');
        if (!in_array($type, ['coach', 'referee'], true)) {
            return redirect()->route('admin_merit_certificate')->withErrors('Invalid recipient type.');
        }

        $ids = $request->input('recipient_ids', []);
        if (!is_array($ids) || count($ids) < 1) {
            return redirect()->route('admin_merit_certificate')->withErrors('Please select at least one ' . $type . '.');
        }

        $model = $type === 'referee' ? Referee::class : Coach::class;
        $people = $model::whereIn('id', $ids)->where('status', '!=', 3)->get();
        if ($people->isEmpty()) {
            return redirect()->route('admin_merit_certificate')->withErrors('No valid recipients selected.');
        }

        session([
            'merit_recipient_type' => $type,
            'merit_recipient_ids' => $people->pluck('id')->values()->all(),
        ]);

        return redirect()->route('admin_merit_certificate_form');
    }

    public function form()
    {
        $type = session('merit_recipient_type');
        $ids = session('merit_recipient_ids', []);
        if (!$type || empty($ids)) {
            return redirect()->route('admin_merit_certificate')->withErrors('Please select recipients first.');
        }

        $model = $type === 'referee' ? Referee::class : Coach::class;
        $people = $model::whereIn('id', $ids)->where('status', '!=', 3)->get();
        if ($people->isEmpty()) {
            return redirect()->route('admin_merit_certificate')->withErrors('Please select recipients first.');
        }

        return view('admin.merit_certificate.apply')->with([
            'title' => 'Generate Certificate',
            'page_title' => 'Certificate Details',
            'recipient_type' => $type,
            'people' => $people,
            'default_template' => $type === 'referee' ? 2 : 1,
        ]);
    }

    public function save(Request $request)
    {
        $request->validate([
            'recipient_type' => 'required|in:coach,referee',
            'template' => 'required|in:1,2',
            'course_title' => 'required|string|max:255',
            'place' => 'nullable|string|max:255',
            'date_from' => 'nullable|string|max:255',
            'date_on' => 'nullable|string|max:255',
            'issue_date' => 'required|date',
            'recipient_ids' => 'required|array|min:1',
            'recipient_ids.*' => 'integer',
        ]);

        $type = $request->recipient_type;
        $template = (int) $request->template;
        if ($template === 1 && empty($request->place) && empty($request->date_from)) {
            return back()->withErrors('For Template 1, please fill Place and/or From date.')->withInput();
        }
        if ($template === 2 && empty($request->date_on)) {
            return back()->withErrors('For Template 2, please fill the Regulation / On date.')->withInput();
        }

        $model = $type === 'referee' ? Referee::class : Coach::class;
        $people = $model::whereIn('id', $request->recipient_ids)->where('status', '!=', 3)->get();
        if ($people->isEmpty()) {
            return back()->withErrors('No valid recipients found.')->withInput();
        }

        $admin = Auth::guard('admin')->user();

        DB::beginTransaction();
        try {
            $batch = MeritCertificateBatch::create([
                'created_by' => $admin->id ?? null,
                'recipient_type' => $type,
                'template' => $template,
                'course_title' => $request->course_title,
                'place' => $request->place,
                'date_from' => $request->date_from,
                'date_on' => $request->date_on,
                'issue_date' => $request->issue_date,
                'status' => 'issued',
            ]);

            $prefix = $type === 'referee' ? 'RT/RF' : 'RT/CO';
            foreach ($people as $person) {
                $cert = MeritCertificate::create([
                    'batch_id' => $batch->id,
                    'recipient_id' => $person->id,
                    'serial_no' => null,
                ]);
                $cert->serial_no = $prefix . '/' . date('Y') . '/' . str_pad((string) $cert->id, 4, '0', STR_PAD_LEFT);
                $cert->save();
            }

            DB::commit();
            session()->forget(['merit_recipient_type', 'merit_recipient_ids']);

            return redirect()
                ->route('admin_merit_certificate_view', $batch->id)
                ->with('success', 'Certificates generated successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withErrors($e->getMessage())->withInput();
        }
    }

    public function list()
    {
        return view('admin.merit_certificate.list')->with([
            'title' => 'Issued Certificates',
            'page_title' => 'Coach / Referee Certificates',
        ]);
    }

    public function listData(Request $request)
    {
        $query = MeritCertificateBatch::withCount('certificates')->orderBy('id', 'DESC');

        if (!empty($request['recipient_type'])) {
            $query->where('recipient_type', $request['recipient_type']);
        }

        return Datatables::of($query->get())
            ->addColumn('type_label', function ($row) {
                return ucfirst($row->recipient_type);
            })
            ->addColumn('issue_date_fmt', function ($row) {
                return $row->issue_date ? $row->issue_date->format('d-m-Y') : '-';
            })
            ->addColumn('action', function ($row) {
                return '<a href="' . route('admin_merit_certificate_view', $row->id) . '" class="btn btn-sm btn-info">View</a> '
                    . '<a href="' . route('admin_merit_certificate_certificates', $row->id) . '" class="btn btn-sm btn-success">Certificates</a>';
            })
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);
    }

    public function viewBatch($id)
    {
        $batch = MeritCertificateBatch::with('certificates')->findOrFail($id);
        $people = $this->peopleForBatch($batch);

        return view('admin.merit_certificate.view')->with([
            'title' => 'Certificate Batch #' . $batch->id,
            'page_title' => 'Certificate Batch',
            'batch' => $batch,
            'people' => $people,
        ]);
    }

    public function certificates($id)
    {
        $batch = MeritCertificateBatch::with('certificates')->findOrFail($id);
        $people = $this->peopleForBatch($batch);

        return view('admin.merit_certificate.certificates')->with([
            'title' => 'Certificates - Batch #' . $batch->id,
            'page_title' => 'Certificates',
            'batch' => $batch,
            'people' => $people,
        ]);
    }

    public function download($batchId, $certId)
    {
        $batch = MeritCertificateBatch::findOrFail($batchId);
        $row = MeritCertificate::where('batch_id', $batch->id)->where('id', $certId)->firstOrFail();

        $binary = MeritCertificateRenderer::renderJpg($batch, $row);
        $filename = 'merit-certificate-' . ($row->serial_no ?: $row->id) . '.jpg';
        $filename = preg_replace('/[^A-Za-z0-9_\-\.]/', '-', $filename);

        return response($binary, 200, [
            'Content-Type' => 'image/jpeg',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    protected function peopleForBatch(MeritCertificateBatch $batch)
    {
        $ids = $batch->certificates->pluck('recipient_id')->all();
        if ($batch->recipient_type === 'referee') {
            return Referee::whereIn('id', $ids)->get()->keyBy('id');
        }

        return Coach::whereIn('id', $ids)->get()->keyBy('id');
    }
}
