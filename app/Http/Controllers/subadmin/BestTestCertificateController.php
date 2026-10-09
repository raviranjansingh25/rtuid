<?php

namespace App\Http\Controllers\subadmin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\subadmin\Concerns\HandlesSubadminPermissions;
use App\Models\BestTestAthlete;
use App\Models\BestTestBatch;
use App\Models\User;
use App\Services\BestTestCertificateRenderer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use DataTables;

class BestTestCertificateController extends Controller
{
    use HandlesSubadminPermissions;

    public function index()
    {
        return view('subadmin.best_test.select')->with($this->subadminViewData([
            'title' => 'Best Test Certificate',
            'page_title' => 'Best Test Certificate',
        ]));
    }

    public function athleteData(Request $request)
    {
        $admin = $this->subadmin();

        // Same query / columns as /subadmin/user (no belt checks on list)
        $anydata = User::where('status', '!=', 3)
            ->where('role', 1)
            ->where(function ($query) use ($request) {
                if (!empty($request['title'])) {
                    $query->where('name', 'LIKE', '%' . $request['title'] . '%');
                }
                if (!empty($request['status'])) {
                    $query->where('status', $request['status']);
                }
            });
        $this->applyDistrictScope($anydata, $admin, $request['district'] ?? null);
        $anydata = $anydata->orderBy('id', 'DESC')->get();

        $allDistrictMode = $this->canAccessAllDistricts($admin);
        $singleDistrictMode = $this->isSingleDistrictSubadmin($admin);

        $datatable = Datatables::of($anydata)
            ->addColumn('checkbox', function ($row) {
                return '<input type="checkbox" class="form-check-input athlete-check" value="' . $row->id . '">';
            })
            ->addColumn('name', function ($anydata) {
                return isset($anydata->name) ? trim($anydata->name . ' ' . ($anydata->last_name ?? '')) : ($anydata->last_name ?? '');
            })
            ->addColumn('phone', function ($anydata) {
                return isset($anydata->country_code) ? '+' . $anydata->country_code . ' ' . $anydata->mobile : $anydata->mobile;
            })
            ->addColumn('status', function ($anydata) use ($allDistrictMode, $singleDistrictMode) {
                if ($anydata->status == 1) {
                    $text = 'Approved';
                    $color = 'success';
                } elseif ($anydata->status == 2) {
                    $text = 'Pending';
                    $color = 'primary';
                } elseif ($anydata->status == 4) {
                    $text = 'Reject';
                    $color = 'danger';
                } else {
                    $text = 'Unknown';
                    $color = 'secondary';
                }

                if ($allDistrictMode || $singleDistrictMode) {
                    return '<span class="btn btn-' . $color . ' btn-sm">' . $text . '</span>';
                }

                return '<div class="btn-group"><button class="btn btn-' . $color . ' dropdown-toggle btn-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">' . $text . '</button></div>';
            });

        if ($allDistrictMode) {
            $datatable->addColumn('coach_act', function ($anydata) {
                if ($anydata->coach_act == 1) {
                    $text = 'Pending';
                    $color = 'danger';
                } elseif ($anydata->coach_act == 2) {
                    $text = 'Approved';
                    $color = 'primary';
                } elseif ($anydata->coach_act == 3) {
                    $text = 'Updated';
                    $color = 'success';
                } else {
                    $text = 'N/A';
                    $color = 'secondary';
                }

                return '<span class="btn btn-' . $color . ' btn-sm">' . $text . '</span>';
            });
        }

        if ($singleDistrictMode) {
            $datatable->addColumn('subadmin_verification', function ($anydata) {
                if (!empty($anydata->subadmin_verified)) {
                    return '<span class="badge bg-success">Verified</span>';
                }

                return '<span class="badge bg-warning text-dark">Pending Verification</span>';
            });
        }

        return $datatable
            ->rawColumns(['checkbox', 'status', 'name', 'phone', 'coach_act', 'subadmin_verification'])
            ->addIndexColumn()
            ->make(true);
    }

    public function prepareApply(Request $request)
    {
        $ids = $request->input('athlete_ids', []);
        if (!is_array($ids) || count($ids) < 1) {
            return redirect()->route('subadmin_best_test')->withErrors('Please select at least one athlete.');
        }

        $admin = $this->subadmin();
        $query = User::whereIn('id', $ids)->where('role', 1)->where('status', '!=', 3);
        $this->applyDistrictScope($query, $admin);
        $athletes = $query->get();

        if ($athletes->isEmpty()) {
            return redirect()->route('subadmin_best_test')->withErrors('No valid athletes selected.');
        }

        session(['best_test_selected_ids' => $athletes->pluck('id')->values()->all()]);

        return redirect()->route('subadmin_best_test_apply_form');
    }

    public function applyForm()
    {
        $ids = session('best_test_selected_ids', []);
        if (empty($ids)) {
            return redirect()->route('subadmin_best_test')->withErrors('Please select athletes first.');
        }

        $admin = $this->subadmin();
        $query = User::whereIn('id', $ids)->where('role', 1)->where('status', '!=', 3);
        $this->applyDistrictScope($query, $admin);
        $athletes = $query->get();

        if ($athletes->isEmpty()) {
            return redirect()->route('subadmin_best_test')->withErrors('Please select athletes first.');
        }

        $athleteBeltOptions = [];
        foreach ($athletes as $athlete) {
            $athleteBeltOptions[$athlete->id] = BestTestAthlete::availableBeltOptionsForUser((int) $athlete->id);
        }

        return view('subadmin.best_test.apply')->with($this->subadminViewData([
            'title' => 'Best Test Certificate - Apply',
            'page_title' => 'Exam Details & Belt',
            'athletes' => $athletes,
            'athleteBeltOptions' => $athleteBeltOptions,
        ]));
    }

    public function applySave(Request $request)
    {
        $request->validate([
            'exam_date' => 'required|date',
            'place' => 'required|string|max:255',
            'belts' => 'required|array',
            'belts.*' => 'required|string',
        ]);

        $admin = $this->subadmin();
        $ids = array_keys($request->input('belts', []));
        $query = User::whereIn('id', $ids)->where('role', 1)->where('status', '!=', 3);
        $this->applyDistrictScope($query, $admin);
        $athletes = $query->get();

        if ($athletes->isEmpty()) {
            return back()->withErrors('No valid athletes found.')->withInput();
        }

        DB::beginTransaction();
        try {
            $batch = BestTestBatch::create([
                'created_by' => $admin->id ?? null,
                'district' => $athletes->first()->district ?? ($admin->district ?? null),
                'exam_date' => $request->exam_date,
                'place' => $request->place,
                'status' => 'applied',
            ]);

            foreach ($athletes as $athlete) {
                $belt = $request->input('belts.' . $athlete->id);
                $allowed = BestTestAthlete::availableBeltOptionsForUser((int) $athlete->id);

                if (empty($allowed)) {
                    throw new \Exception(
                        trim(($athlete->name ?? '') . ' ' . ($athlete->last_name ?? '')) .
                        ' already has the highest belt. No further belt available.'
                    );
                }

                if (empty($belt) || empty($allowed[$belt])) {
                    throw new \Exception(
                        'Invalid / lower belt selected for ' .
                        trim(($athlete->name ?? '') . ' ' . ($athlete->last_name ?? '')) .
                        '. Only higher belts are allowed.'
                    );
                }

                BestTestAthlete::create([
                    'batch_id' => $batch->id,
                    'user_id' => $athlete->id,
                    'belt_type' => $belt,
                    'status' => 'applied',
                    'certificate_ready' => 0,
                ]);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors($e->getMessage())->withInput();
        }

        session()->forget('best_test_selected_ids');

        return redirect()->route('subadmin_best_test_list')
            ->withSuccess('Application submitted. Waiting for State Admin to enter grades.');
    }

    public function list()
    {
        return view('subadmin.best_test.list')->with($this->subadminViewData([
            'title' => 'Best Test Applications',
            'page_title' => 'Best Test Applications',
        ]));
    }

    public function listData(Request $request)
    {
        $admin = $this->subadmin();
        $query = BestTestBatch::withCount('athletes')->orderBy('id', 'DESC');
        $this->applyDistrictScopeOnColumn($query, 'district', $admin);

        if (!empty($request['status'])) {
            $query->where('status', $request['status']);
        }

        return Datatables::of($query->get())
            ->addColumn('exam_date', function ($row) {
                return $row->exam_date ? $row->exam_date->format('d-m-Y') : '-';
            })
            ->addColumn('status_badge', function ($row) {
                if ($row->status === 'graded') {
                    return '<span class="badge bg-success">Graded / Certificate Ready</span>';
                }
                return '<span class="badge bg-warning text-dark">Pending State Grade</span>';
            })
            ->addColumn('action', function ($row) {
                $html = '<a href="' . route('subadmin_best_test_view', $row->id) . '" class="btn btn-sm btn-info">View</a>';
                if ($row->status === 'graded') {
                    $html .= ' <a href="' . route('subadmin_best_test_certificates', $row->id) . '" class="btn btn-sm btn-success">Certificates</a>';
                }
                return $html;
            })
            ->rawColumns(['status_badge', 'action'])
            ->addIndexColumn()
            ->make(true);
    }

    public function viewBatch($id)
    {
        $batch = $this->findScopedBatch($id);

        return view('subadmin.best_test.view')->with($this->subadminViewData([
            'title' => 'Application #' . $batch->id,
            'page_title' => 'Best Test Application',
            'batch' => $batch,
        ]));
    }

    public function certificates($id)
    {
        $batch = $this->findScopedBatch($id);

        if ($batch->status !== 'graded') {
            return redirect()->route('subadmin_best_test_view', $id)
                ->withErrors('Certificates available after State Admin grades the batch.');
        }

        return view('subadmin.best_test.certificates')->with($this->subadminViewData([
            'title' => 'Certificates - Batch #' . $batch->id,
            'page_title' => 'Best Test Certificates',
            'batch' => $batch,
        ]));
    }

    public function download($batchId, $athleteId)
    {
        $batch = $this->findScopedBatch($batchId);
        if ($batch->status !== 'graded') {
            return redirect()->route('subadmin_best_test_view', $batchId)
                ->withErrors('Certificates available after State Admin grades the batch.');
        }

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

    protected function findScopedBatch($id)
    {
        $admin = $this->subadmin();
        $query = BestTestBatch::with(['athletes.user'])->where('id', $id);
        $this->applyDistrictScopeOnColumn($query, 'district', $admin);

        return $query->firstOrFail();
    }
}
