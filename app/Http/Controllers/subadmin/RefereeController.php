<?php

namespace App\Http\Controllers\subadmin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\subadmin\Concerns\HandlesSubadminPermissions;
use Illuminate\Http\Request;
use App\Models\Referee;
use DataTables;

class RefereeController extends Controller
{
    use HandlesSubadminPermissions;

    public function index()
    {
        $denied = $this->denySubadminUnless(!empty($this->subadmin()->can_referee));
        if ($denied) {
            return $denied;
        }

        return view('subadmin.referee.view')->with($this->subadminViewData([
            'title' => 'View Referee',
            'page_title' => 'View Referee',
        ]));
    }

    public function anydata(Request $request)
    {
        $admin = $this->subadmin();
        if (empty($admin->can_referee)) {
            return response()->json(['data' => []]);
        }

        $query = Referee::orderBy('id', 'DESC')
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
        $readOnlyStatus = $allDistrictMode || $this->isSingleDistrictSubadmin($admin);

        return Datatables::of($query->get())
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
            })
            ->rawColumns(['status', 'image'])
            ->addIndexColumn()
            ->make(true);
    }
}
