<?php

namespace App\Http\Controllers\subadmin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\subadmin\Concerns\HandlesSubadminPermissions;
use Illuminate\Http\Request;
use App\Models\Coach;
use DataTables;

class CoachController extends Controller
{
    use HandlesSubadminPermissions;

    public function index()
    {
        $denied = $this->denySubadminUnless(!empty($this->subadmin()->can_coach));
        if ($denied) {
            return $denied;
        }

        return view('subadmin.coach.view')->with($this->subadminViewData([
            'title' => 'View Coach',
            'page_title' => 'View Coach',
        ]));
    }

    public function anydata(Request $request)
    {
        $admin = $this->subadmin();
        if (empty($admin->can_coach)) {
            return response()->json(['data' => []]);
        }

        $singleDistrictMode = $this->isSingleDistrictSubadmin($admin);

        $query = Coach::orderBy('id', 'DESC')
            ->where('status', '<', 3)
            ->where(function ($query) use ($request) {
                if (!empty($request['title'])) {
                    $query->where('name', 'LIKE', '%' . $request['title'] . '%');
                }
                if (!empty($request['status'])) {
                    $query->where('status', $request['status']);
                }
            });

        $this->applyDistrictScopeOnColumn($query, 'district', $admin, $request['district'] ?? null);

        $allDistrictMode = $this->canAccessAllDistricts($admin);
        $readOnlyStatus = $allDistrictMode || $singleDistrictMode;

        $datatable = Datatables::of($query->get())
            ->addColumn('image', function ($anydata) {
                $img = (!empty($anydata['image']) && file_exists($anydata['image']))
                    ? url($anydata['image'])
                    : url('public/noimage.png');
                return '<img style="border-radius: 50%;" alt="image" src=' . $img . ' width="35" height="35px">';
            })
            ->addColumn('status', function ($anydata) use ($readOnlyStatus) {
                if ($anydata->status == 1) {
                    if ($readOnlyStatus) {
                        return '<span class="btn btn-success btn-rounded btn-sm">Active</span>';
                    }
                    $status = 2;
                    return '<span onclick="changeStatus(' . $anydata->id . ',' . $status . ')" class="btn btn-success btn-rounded btn-sm waves-effect waves-light">Active</span>';
                }

                if ($readOnlyStatus) {
                    return '<span class="btn btn-danger btn-rounded btn-sm">Deactive</span>';
                }

                $status = 1;
                return '<span onclick="changeStatus(' . $anydata->id . ',' . $status . ')" class="btn btn-danger btn-rounded btn-sm waves-effect waves-light">Deactive</span>';
            });

        if ($singleDistrictMode) {
            $datatable->addColumn('stop_apply', function ($anydata) {
                $checked = !empty($anydata->stop_apply_weight) ? 'checked' : '';
                return '<div class="form-check form-switch">
                    <input class="form-check-input stop-apply-toggle" type="checkbox" data-id="' . $anydata->id . '" ' . $checked . '>
                    <label class="form-check-label">' . (!empty($anydata->stop_apply_weight) ? 'Stopped' : 'Allowed') . '</label>
                </div>';
            });
        }

        return $datatable
            ->rawColumns(['status', 'image', 'stop_apply'])
            ->addIndexColumn()
            ->make(true);
    }

    public function toggleStopApply(Request $request)
    {
        $admin = $this->subadmin();
        if (!$this->isSingleDistrictSubadmin($admin) || empty($admin->can_coach)) {
            return response()->json(['status' => 'error', 'message' => 'Permission denied.'], 403);
        }

        $coach = Coach::find($request->coach_id);
        if (!$coach || (string) $coach->district !== (string) $admin->district) {
            return response()->json(['status' => 'error', 'message' => 'Coach not found.'], 404);
        }

        $coach->stop_apply_weight = $request->boolean('stop') ? 1 : 0;
        $coach->save();

        return response()->json([
            'status' => 'success',
            'message' => $coach->stop_apply_weight ? 'Coach tournament apply stopped.' : 'Coach tournament apply allowed.',
        ]);
    }
}
