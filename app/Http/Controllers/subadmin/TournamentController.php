<?php

namespace App\Http\Controllers\subadmin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\subadmin\Concerns\HandlesSubadminPermissions;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\EventCategory;
use App\Models\Tournament;
use App\Models\Category;
use App\Models\WeightCategory;
use App\Models\ApplyTournament;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use DataTables;
use Validator;

class TournamentController extends Controller
{
    use HandlesSubadminPermissions;

    private function denyUnlessCanCreate()
    {
        $admin = $this->subadmin();
        if (!$this->isSingleDistrictSubadmin($admin) || empty($admin->can_create_tournament)) {
            return redirect()->route('subadmin_dashboard')->withErrors('You do not have permission to create district tournaments.');
        }

        return null;
    }

    private function applyDistrictFlags(Tournament $data, Request $request, $admin)
    {
        $data->district_id = $admin->district;
        $data->created_by_subadmin = $admin->id;
        $data->is_district_tournament = 1;
        $data->district_apply_open = (int) $request->input('district_apply_open', 0);
        $data->district_draw_sheet_open = (int) $request->input('district_draw_sheet_open', 0);
        $data->coach_apply_weight_open = (int) $request->input('coach_apply_weight_open', 0);
        $data->athlete_apply_weight_open = (int) $request->input('athlete_apply_weight_open', 0);
    }

    public function add(Request $request, $id = null)
    {
        if ($denied = $this->denyUnlessCanCreate()) {
            return $denied;
        }

        $decrypted_id = get_decrypted_value($id, true);
        $getdata = Tournament::find($decrypted_id);
        $event = Event::where('status', 1)->get();
        $category = Category::where('status', 1)->get();

        if ($id != '') {
            $saveurl = url('subadmin/tournament/save/' . $id);
            $button = 'Update';
            $page_title = 'Update District Tournament';
        } else {
            $saveurl = url('subadmin/tournament/save');
            $button = 'Add';
            $page_title = 'Add District Tournament';
        }

        return view('subadmin.tournament.add')->with($this->subadminViewData([
            'getdata'    => $getdata,
            'saveurl'    => $saveurl,
            'button'     => $button,
            'title'      => $page_title,
            'event'      => $event,
            'category'   => $category,
        ]));
    }

    public function edit(Request $request, $id)
    {
        if ($denied = $this->denyUnlessCanCreate()) {
            return $denied;
        }

        $admin = $this->subadmin();
        $decrypted_id = get_decrypted_value($id, true);
        $getdata = Tournament::find($decrypted_id);

        if (!$getdata || (string) $getdata->district_id !== (string) $admin->district) {
            return redirect()->route('subadmin_tournament')->withErrors('Tournament not found.');
        }

        return view('subadmin.tournament.edit')->with($this->subadminViewData([
            'getdata'    => $getdata,
            'saveurl'    => url('subadmin/tournament_edit/save/' . $id),
            'title'      => 'Update District Tournament',
        ]));
    }

    public function editsave(Request $request, $id = null)
    {
        if ($denied = $this->denyUnlessCanCreate()) {
            return $denied;
        }

        $admin = $this->subadmin();
        $decrypted_id = get_decrypted_value($id, true);
        $base = Tournament::find($decrypted_id);

        if (!$base || (string) $base->district_id !== (string) $admin->district) {
            return redirect()->route('subadmin_tournament')->withErrors('Tournament not found.');
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        DB::beginTransaction();
        try {
            $currentDate = Carbon::now()->format('Y-m-d');
            $rows = Tournament::where('title', $base->title)
                ->where('district_id', $admin->district)
                ->where('is_district_tournament', 1)
                ->get();

            foreach ($rows as $row) {
                $row->title = $request['title'];
                $row->start_date = $request['start_date'];
                $row->end_date = $request['end_date'];
                $row->district_apply_open = (int) $request->input('district_apply_open', 0);
                $row->district_draw_sheet_open = (int) $request->input('district_draw_sheet_open', 0);
                $row->coach_apply_weight_open = (int) $request->input('coach_apply_weight_open', 0);
                $row->athlete_apply_weight_open = (int) $request->input('athlete_apply_weight_open', 0);
                $row->save();

                if ($row->end_date >= $currentDate) {
                    ApplyTournament::where('turnament_id', $row->id)->update(['final' => 0]);
                }
            }
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()->withErrors($e->getMessage());
        }
        DB::commit();

        return redirect()->route('subadmin_tournament')->withSuccess('District Tournament Updated Successfully.');
    }

    public function save(Request $request, $id = null)
    {
        if ($denied = $this->denyUnlessCanCreate()) {
            return $denied;
        }

        $admin = $this->subadmin();
        $validator = Validator::make($request->all(), [
            'title' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        DB::beginTransaction();
        try {
            foreach ($request['gender'] as $gender) {
                foreach ($request['event'] as $event) {
                    if ($event == 2) {
                        $event_cat = EventCategory::where('status', 1)->get();
                        foreach ($event_cat as $eve) {
                            $data = new Tournament;
                            $data->title = $request['title'];
                            $data->event = $event;
                            $data->gender = $gender;
                            $data->category = $request['category'][0] ?? null;
                            $data->event_category = $eve->id;
                            $data->start_date = $request['start_date'];
                            $data->end_date = $request['end_date'];
                            $this->applyDistrictFlags($data, $request, $admin);
                            $data->save();
                        }
                    } else {
                        $weight = WeightCategory::whereIn('category', $request['category'])->where('gender', $gender)->get();
                        foreach ($weight as $waight_cat) {
                            $data = new Tournament;
                            $data->title = $request['title'];
                            $data->event = $event;
                            $data->gender = $gender;
                            $data->category = $waight_cat->category;
                            $data->weight_category = $waight_cat->id;
                            $data->start_date = $request['start_date'];
                            $data->end_date = $request['end_date'];
                            $this->applyDistrictFlags($data, $request, $admin);
                            $data->save();
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()->withErrors($e->getMessage());
        }
        DB::commit();

        return redirect()->route('subadmin_tournament')->withSuccess('District Tournament Added Successfully.');
    }

    public function index()
    {
        if ($denied = $this->denyUnlessCanCreate()) {
            return $denied;
        }

        return view('subadmin.tournament.view')->with($this->subadminViewData([
            'title' => 'District Tournaments',
            'page_title' => 'District Tournaments',
        ]));
    }

    public function anydata(Request $request)
    {
        $admin = $this->subadmin();
        if (!$this->isSingleDistrictSubadmin($admin) || empty($admin->can_create_tournament)) {
            return response()->json(['data' => []]);
        }

        $query = Tournament::orderBy('id', 'DESC')
            ->where('status', '<', 3)
            ->where('is_district_tournament', 1)
            ->where('district_id', $admin->district)
            ->where(function ($query) use ($request) {
                if (!empty($request['title'])) {
                    $query->where('title', 'LIKE', '%' . $request['title'] . '%');
                }
                if (!empty($request['status'])) {
                    $query->where('status', $request['status']);
                }
            });

        return Datatables::of($query->groupBy('title')->get())
            ->addColumn('flags', function ($anydata) {
                $parts = [];
                if (!empty($anydata->district_apply_open)) {
                    $parts[] = '<span class="badge bg-primary">Apply ON</span>';
                }
                if (!empty($anydata->district_draw_sheet_open)) {
                    $parts[] = '<span class="badge bg-info">Draw ON</span>';
                }
                if (!empty($anydata->coach_apply_weight_open)) {
                    $parts[] = '<span class="badge bg-success">Coach Weight ON</span>';
                }
                if (!empty($anydata->athlete_apply_weight_open)) {
                    $parts[] = '<span class="badge bg-warning text-dark">Athlete Weight ON</span>';
                }

                return $parts ? implode(' ', $parts) : '<span class="badge bg-secondary">All OFF</span>';
            })
            ->addColumn('action', function ($anydata) {
                $encrypted_id = get_encrypted_value($anydata->id, true);
                return '<a href="' . url('/subadmin/tournament/edit/' . $encrypted_id) . '"><i class="fas fa-edit" title="Edit"></i></a>';
            })
            ->rawColumns(['action', 'flags'])
            ->addIndexColumn()
            ->make(true);
    }
}
