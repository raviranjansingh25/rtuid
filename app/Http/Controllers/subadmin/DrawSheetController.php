<?php

namespace App\Http\Controllers\subadmin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\subadmin\Concerns\HandlesSubadminPermissions;
use Illuminate\Http\Request;
use App\Models\Tournament;
use App\Models\ApplyTournament;
use App\Http\Controllers\admin\DrawSheetController as AdminDrawSheetController;
use DataTables;

class DrawSheetController extends Controller
{
    use HandlesSubadminPermissions;

    public function index()
    {
        $denied = $this->denySubadminUnless($this->subadminCan('can_draw_sheet'));
        if ($denied) {
            return $denied;
        }

        return view('subadmin.drawsheet.view')->with($this->subadminViewData([
            'title' => 'View Draw Sheet',
            'page_title' => 'View Draw Sheet',
        ]));
    }

    public function indexname($id)
    {
        $denied = $this->denySubadminUnless($this->subadminCan('can_draw_sheet'));
        if ($denied) {
            return $denied;
        }

        $admin = $this->subadmin();
        if (!$this->canAccessAllDistricts($admin)) {
            $decrypted_id = get_decrypted_value($id, true);
            $tournament = Tournament::find($decrypted_id);
            if (!$tournament || !$this->districtDrawSheetAllowed($tournament, $admin)) {
                return redirect()->route('subadmin_draw_sheet')->withErrors('This draw sheet is not available for your district yet.');
            }
        }

        return view('subadmin.drawsheet.viewname')->with($this->subadminViewData([
            'title' => 'View Tournament',
            'page_title' => 'View Tournament',
            'id' => $id,
        ]));
    }

    public function draw_sheet($id)
    {
        $denied = $this->denySubadminUnless($this->subadminCan('can_draw_sheet'));
        if ($denied) {
            return $denied;
        }

        $admin = $this->subadmin();
        if (!$this->canAccessAllDistricts($admin)) {
            $decrypted_id = get_decrypted_value($id, true);
            $tournament = Tournament::find($decrypted_id);
            if (!$tournament || !$this->districtDrawSheetAllowed($tournament, $admin)) {
                return redirect()->route('subadmin_draw_sheet')->withErrors('This draw sheet is not available for your district yet.');
            }
        }

        return app(AdminDrawSheetController::class)->draw_sheet($id);
    }

    public function anydata(Request $request)
    {
        $admin = $this->subadmin();
        if (!$this->subadminCan('can_draw_sheet', $admin)) {
            return response()->json(['data' => []]);
        }

        $query = Tournament::orderBy('id', 'DESC')
            ->where('status', '<', 3)
            ->where(function ($query) use ($request) {
                if (!empty($request['title'])) {
                    $query->where('title', 'LIKE', '%' . $request['title'] . '%');
                }
                if (!empty($request['status'])) {
                    $query->where('status', $request['status']);
                }
            });

        if (!$this->canAccessAllDistricts($admin)) {
            $this->applyDistrictDrawSheetFilter($query, $admin);
        }

        $anydata = $query->groupBy('title')->get();

        return Datatables::of($anydata)
            ->addColumn('action', function ($anydata) {
                $encrypted_id = get_encrypted_value($anydata->id, true);
                return '<a href="' . url('/subadmin/draw-sheet-name/' . $encrypted_id) . '"><i class="mdi mdi-eye text-info" title="View"></i></a>&nbsp;&nbsp;';
            })
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);
    }

    public function anydataname(Request $request, $id)
    {
        $admin = $this->subadmin();
        if (!$this->subadminCan('can_draw_sheet', $admin)) {
            return response()->json(['data' => []]);
        }

        $decrypted_id = get_decrypted_value($id, true);
        $data = Tournament::find($decrypted_id);

        $query = Tournament::where('title', $data->title)
            ->with('get_event', 'get_waight_cat', 'get_category')
            ->orderBy('id', 'DESC')
            ->where('status', '<', 3)
            ->where(function ($query) use ($request) {
                if (!empty($request['title'])) {
                    $query->where('title', 'LIKE', '%' . $request['title'] . '%');
                }
                if (!empty($request['status'])) {
                    $query->where('status', $request['status']);
                }
            });

        if (!$this->canAccessAllDistricts($admin)) {
            $this->applyDistrictDrawSheetFilter($query, $admin);
        }

        $anydata = $query->get();

        return Datatables::of($anydata)
            ->addColumn('category', function ($anydata) {
                return $anydata['get_category']->title ?? 'N/A';
            })
            ->addColumn('gender_name', function ($anydata) {
                if ($anydata->gender == 1) {
                    return 'Male';
                }
                if ($anydata->gender == 2) {
                    return 'Female';
                }
                return 'Other';
            })
            ->addColumn('event', function ($anydata) {
                return $anydata['get_event']->title ?? 'N/A';
            })
            ->addColumn('get_waight_cat', function ($anydata) {
                return isset($anydata['get_waight_cat']->title) ? $anydata['get_waight_cat']->title : 'N/A';
            })
            ->addColumn('player_count', function ($anydata) {
                return ApplyTournament::where('turnament_id', $anydata->id)->count();
            })
            ->addColumn('action', function ($anydata) {
                $encrypted_id = get_encrypted_value($anydata->id, true);
                return '<a href="' . url('/subadmin/draw-sheet/view/' . $encrypted_id) . '"><i class="mdi mdi-eye text-info" title="View"></i></a>&nbsp;&nbsp;';
            })
            ->rawColumns(['action', 'event', 'get_waight_cat', 'category', 'player_count'])
            ->addIndexColumn()
            ->make(true);
    }

    private function applyDistrictDrawSheetFilter($query, $admin)
    {
        return $query->where(function ($q) use ($admin) {
            $q->where('draw_sheet_verified', 1)
                ->orWhere(function ($q2) use ($admin) {
                    $q2->where('is_district_tournament', 1)
                        ->where('district_id', $admin->district)
                        ->where('district_draw_sheet_open', 1);
                });
        });
    }

    private function districtDrawSheetAllowed(Tournament $tournament, $admin): bool
    {
        if ((int) $tournament->draw_sheet_verified === 1) {
            return Tournament::where('title', $tournament->title)->where('draw_sheet_verified', 1)->exists();
        }

        return (int) $tournament->is_district_tournament === 1
            && (string) $tournament->district_id === (string) $admin->district
            && (int) $tournament->district_draw_sheet_open === 1;
    }
}
