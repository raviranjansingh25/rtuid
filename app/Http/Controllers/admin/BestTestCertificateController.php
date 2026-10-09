<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\BestTestAthlete;
use App\Models\BestTestBatch;
use App\Models\Tags;
use App\Services\BestTestCertificateRenderer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use DataTables;

class BestTestCertificateController extends Controller
{
    public function index()
    {
        return view('admin.best_test.list')->with([
            'title' => 'Best Test Certificate',
            'page_title' => 'Best Test Applications (Grade)',
            'districts' => Tags::where('status', 1)->get(),
        ]);
    }

    public function listData(Request $request)
    {
        $query = BestTestBatch::withCount('athletes')->with('districtTag')->orderBy('id', 'DESC');

        if (!empty($request['status'])) {
            $query->where('status', $request['status']);
        }
        if (!empty($request['district']) && $request['district'] !== 'all') {
            $query->where('district', $request['district']);
        }

        return Datatables::of($query->get())
            ->addColumn('district_name', function ($row) {
                return e($row->districtTag->title ?? ($row->district ?? '-'));
            })
            ->addColumn('exam_date', function ($row) {
                return $row->exam_date ? $row->exam_date->format('d-m-Y') : '-';
            })
            ->addColumn('status_badge', function ($row) {
                if ($row->status === 'graded') {
                    return '<span class="badge bg-success">Graded / Certificate Ready</span>';
                }
                return '<span class="badge bg-warning text-dark">Pending Grade</span>';
            })
            ->addColumn('action', function ($row) {
                $html = '<a href="' . route('admin_best_test_grade', $row->id) . '" class="btn btn-sm btn-primary">Grade</a>';
                if ($row->status === 'graded') {
                    $html .= ' <a href="' . route('admin_best_test_certificates', $row->id) . '" class="btn btn-sm btn-success">Certificates</a>';
                }
                return $html;
            })
            ->rawColumns(['status_badge', 'action'])
            ->addIndexColumn()
            ->make(true);
    }

    public function grade($id)
    {
        $batch = BestTestBatch::with(['athletes.user', 'districtTag'])->findOrFail($id);

        return view('admin.best_test.grade')->with([
            'title' => 'Enter Grades - Batch #' . $batch->id,
            'page_title' => 'Best Test Grade Entry',
            'batch' => $batch,
        ]);
    }

    public function gradeSave(Request $request, $id)
    {
        $batch = BestTestBatch::with('athletes')->findOrFail($id);

        $request->validate([
            'grades' => 'required|array',
            'grades.*' => 'nullable|string|max:50',
        ]);

        DB::beginTransaction();
        try {
            foreach ($batch->athletes as $athlete) {
                $grade = trim((string) $request->input('grades.' . $athlete->id, ''));
                $athlete->grade = $grade !== '' ? $grade : null;
                $athlete->status = 'graded';
                $athlete->certificate_ready = 1;
                $athlete->save();
            }

            $batch->status = 'graded';
            $batch->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors($e->getMessage())->withInput();
        }

        return redirect()->route('admin_best_test_certificates', $batch->id)
            ->withSuccess('Grades submitted. Certificates are ready.');
    }

    public function certificates($id)
    {
        $batch = BestTestBatch::with(['athletes.user'])->findOrFail($id);

        if ($batch->status !== 'graded') {
            return redirect()->route('admin_best_test_grade', $id)
                ->withErrors('Please submit grades first.');
        }

        return view('admin.best_test.certificates')->with([
            'title' => 'Certificates - Batch #' . $batch->id,
            'page_title' => 'Best Test Certificates',
            'batch' => $batch,
        ]);
    }

    public function download($batchId, $athleteId)
    {
        $batch = BestTestBatch::findOrFail($batchId);
        $row = BestTestAthlete::with('user')
            ->where('batch_id', $batch->id)
            ->where('id', $athleteId)
            ->where('certificate_ready', 1)
            ->firstOrFail();

        $binary = BestTestCertificateRenderer::renderJpg($batch, $row);
        $filename = 'color-belt-certificate-' . $row->id . '.jpg';

        return response($binary, 200, [
            'Content-Type' => 'image/jpeg',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}
