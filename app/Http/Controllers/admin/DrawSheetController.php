<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\User;
use App\Models\Tournament;
use App\Models\Setting;
use App\Models\DrawSheet;
use App\Models\WeightCategory;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\ApplyTournament;
use Illuminate\Support\Str;
use DataTables;
use Validator;
use Session;
use File;
use Intervention\Image\Facades\Image;

class DrawSheetController extends Controller
{
    public function index()
    {
        $data = array(
            'title' => 'View Tournament',
            'page_title' => 'View Tournament',
        );
        return view('admin.drawsheet.view')->with($data);
    }

    
    public function draw_sheet($id)
    {
        $decrypted_id = get_decrypted_value($id, true);
        $layout = request()->is('subadmin/*') ? 'subadmin.layout.layout' : 'admin.layout.layout';

        $players = ApplyTournament::where('turnament_id', $decrypted_id)
            ->pluck('name', 'id')
            ->toArray();
        $turnament = Tournament::with('get_category','get_event','get_event_cat','get_waight_cat')->find($decrypted_id);
        // p($turnament);    
        if(count($players)<1){
            return view('admin.drawsheet.no_player', [
                'title' => 'Draw Sheet',
                'layout' => $layout,
            ]);
        }
        // p(count($players));

        // check if already exists
        $data = DrawSheet::where('tournament_id', $decrypted_id)->get();
        
        $sheet2 = DrawSheet::where('tournament_id', $decrypted_id)->where('sheet',2)->count();
            
            if($sheet2 >= 1){
                $gold = DrawSheet::where('tournament_id', $decrypted_id)
                ->where('match', 7)
                ->first();

                $silver = DrawSheet::where('tournament_id', $decrypted_id)
                                ->where('match', 6)
                                ->when($gold, fn($q) => $q->where('user_id', '!=', $gold->user_id))
                                ->first();
                
                $bronze = DrawSheet::where('tournament_id', $decrypted_id)
                                ->where('match', 1)
                                ->when($gold, fn($q) => $q->where('user_id', '!=', $gold->user_id))
                                ->when($silver, fn($q) => $q->where('user_id', '!=', $silver->user_id))
                                ->first();
                
                $bronze1 = DrawSheet::where('tournament_id', $decrypted_id)
                                ->where('match', 1)
                                ->when($gold, fn($q) => $q->where('user_id', '!=', $gold->user_id))
                                ->when($silver, fn($q) => $q->where('user_id', '!=', $silver->user_id))
                                ->latest('id') // or any timestamp column
                                ->first();
                
            }else{
                
                    $gold = DrawSheet::where('tournament_id', $decrypted_id)
                   ->whereIn('match', [1, 6])
                    ->where('status', 2)
                    ->first();
                    
    
                    $silver = DrawSheet::where('tournament_id', $decrypted_id)
                                    ->where('match', 1)
                                    ->where('status', 3)
                                    ->first();
                    
                    $bronze = DrawSheet::where('tournament_id', $decrypted_id)
                                    ->where('match', 2)
                                    
                                    ->when($gold, fn($q) => $q->where('user_id', '!=', $gold->user_id))
                                    ->when($silver, fn($q) => $q->where('user_id', '!=', $silver->user_id))
                                    ->first();
                    
                    $bronze1 = DrawSheet::where('tournament_id', $decrypted_id)
                                    ->where('match', 2)
                                    
                                    ->when($gold, fn($q) => $q->where('user_id', '!=', $gold->user_id))
                                    ->when($silver, fn($q) => $q->where('user_id', '!=', $silver->user_id))
                                    ->latest('id') // or any timestamp column
                                    ->first();
                }
            
        
            $Standings = [
                    'gold'       => $gold,
                    'silver'       => $silver,
                    'bronze' => $bronze,
                    'bronze1'    => $bronze1,
                    
                ];
                // p($Standings);
        if ($data->count() > 0) {
            // 🔹 Pehle sheet ke hisaab se group karo
            $sheets = DrawSheet::where('tournament_id', $decrypted_id)
                ->orderBy('match')
                ->orderBy('group_set')
                ->orderBy('sheet','asc')
                ->get()
                ->groupBy('sheet');  // <-- sheet wise grouping
        
            $allSheets = [];
        
            foreach ($sheets as $sheetNo => $drawSheets) {
                $groupedByMatch = $drawSheets->groupBy('match');
        
                $allSheets[$sheetNo] = [
                    'round2Matches'       => groupMatches($groupedByMatch, 5),
                    'round1Matches'       => groupMatches($groupedByMatch, 4),
                    'quarterfinalMatches' => groupMatches($groupedByMatch, 3),
                    'semifinalMatches'    => groupMatches($groupedByMatch, 2),
                    'finalMatches'        => groupMatches($groupedByMatch, 1),
                    'winner'              => groupMatches($groupedByMatch, 6),
                    'champian'            => groupMatches($groupedByMatch, 7),
                ];
            }
        
            return view('admin.drawsheet.draw_sheet', [
                'allSheets'     => $allSheets,
                'tournament_id' => $decrypted_id,
                'title'         => 'Draw Sheet',
                'turnament'     => $turnament,
                'standings' => $Standings,
                'layout'        => $layout,
            ]);
        }


        $totalPlayers = count($players);
        
        
        // 👉 Split players into chunks (32 max per sheet)
        $chunks = array_chunk($players, 32, true);
        if (count($players) > 32) {
        $chunks = splitIntoEqualParts($players, 2);
        } else {
            $chunks = [$players];
        }
        
       

        foreach ($chunks as $sheetIndex => $sheetPlayers) {
            $sheetNumber = $sheetIndex + 1;
            $count = count($sheetPlayers);
           
            if ($count == 1) {
                // p($sheetPlayers);
                // 🔹 Direct winner
                foreach ($sheetPlayers as $userId => $userName) {
                    DrawSheet::create([
                        'tournament_id' => $decrypted_id,
                        'user_id'       => $userId,
                        'user_name'     => $userName,
                        'sheet'         => $sheetNumber,
                        'match'         => 6, // winner
                        'match_group'   => 1,
                        'group_set'     => $sheetNumber,
                        'round_no'      => 1,
                        'status'        => 2,
                    ]);
                }

            }elseif ($count == 2) {
                
                // 🔹 Direct Final
                foreach ($sheetPlayers as $userId => $userName) {
                    DrawSheet::create([
                        'tournament_id' => $decrypted_id,
                        'user_id'       => $userId,
                        'user_name'     => $userName,
                        'sheet'         => $sheetNumber,
                        'match'         => 1, // final
                        'match_group'   => 1,
                        'group_set'     => 1,
                        'round_no'      => 1,
                        'status'        => 'pending'
                    ]);
                }

            } elseif ($count <= 4 && $count > 2) {

            
                $semifinalMatches = array_chunk($sheetPlayers, 2, true);
            
                $groupCounter = 1;
            
                foreach ($semifinalMatches as $matchIndex => $matchPlayers) {
            
                    foreach ($matchPlayers as $userId => $userName) {
            
                        DrawSheet::create([
                            'tournament_id' => $decrypted_id,
                            'user_id'       => $userId,
                            'user_name'     => $userName,
                            'sheet'         => $sheetNumber,
                            'match'         => 2, // semifinal
                            'match_group'   => $matchIndex + 1,
                            'group_set'     => $groupCounter,
                            'round_no'      => 2,
                            'status'        => 'pending'
                        ]);
            
                        $groupCounter++;
                    }
            
                    // agar odd player ho to blank opponent add karo
                    if (count($matchPlayers) == 1) {
            
                        DrawSheet::create([
                            'tournament_id' => $decrypted_id,
                            'user_id'       => null,
                            'user_name'     => null,
                            'sheet'         => $sheetNumber,
                            'match'         => 2,
                            'match_group'   => $matchIndex + 1,
                            'group_set'     => $groupCounter,
                            'round_no'      => 2,
                            'status'        => 'pending'
                        ]);
            
                        $groupCounter++;
                    }
                }
            
                // Final placeholder
                for ($i = 1; $i <= 2; $i++) {
            
                    DrawSheet::create([
                        'tournament_id' => $decrypted_id,
                        'user_id'       => null,
                        'user_name'     => null,
                        'sheet'         => $sheetNumber,
                        'match'         => 1, // final
                        'match_group'   => 1,
                        'group_set'     => $i,
                        'round_no'      => 1,
                        'status'        => 'pending'
                    ]);
                }
            } elseif ($count <= 8) {
                $semifinalSlots = 4;
                $quarterPlayers = max(0, $count - $semifinalSlots);
                $quarter = array_slice($sheetPlayers, 0, $quarterPlayers, true);
                $semifinalPlayers = array_slice($sheetPlayers, $quarterPlayers, null, true);
                
                // Semifinal insert
                $semifinalMatches = array_chunk($semifinalPlayers, 2, true);
                $groupCounter1 = 1;
                foreach ($semifinalMatches as $matchIndex => $matchPlayers) {
                    foreach ($matchPlayers as $userId => $userName) {
                        DrawSheet::create([
                            'tournament_id' => $decrypted_id,
                            'user_id'       => $userId,
                            'user_name'     => $userName,
                            'sheet'         => $sheetNumber,
                            'match'         => 2, // semifinal
                            'match_group'   =>  $matchIndex + 1,
                            'group_set'     => $groupCounter1,
                            'round_no'      => 3,
                            'status'        => 'pending'
                        ]);

                        $groupCounter1++;
                    }
                }

                $groupCounter = 1;
                foreach ($quarter as $userId => $userName) {
                    // Real Player
                    DrawSheet::create([
                        'tournament_id' => $decrypted_id,
                        'user_id'       => $userId,
                        'user_name'     => $userName,
                        'sheet'         => $sheetNumber,
                        'match'         => 3, // round-1
                        'match_group'   => $groupCounter,
                        'group_set'     => 1,
                        'round_no'      => 4,
                        'status'        => 'pending'
                    ]);
                
                    // Blank Opponent
                    DrawSheet::create([
                        'tournament_id' => $decrypted_id,
                        'user_id'       => null,
                        'user_name'     => null,
                        'sheet'         => $sheetNumber,
                        'match'         => 3, // round-1
                        'match_group'   => $groupCounter,
                        'group_set'     => 2,
                        'round_no'      => 4,
                        'status'        => 'pending'
                    ]);
                
                    $groupCounter++;
                }
    
                // Final placeholder
                for ($i = 1; $i <= 2; $i++) {
                    DrawSheet::create([
                        'tournament_id' => $decrypted_id,
                        'user_id'       => null,
                        'user_name'     => null,
                        'sheet'         => $sheetNumber,
                        'match'         => 1, // final
                        'match_group'   => 1,
                        'group_set'     => $i,
                        'round_no'      => 1,
                        'status'        => 'pending'
                    ]);
                }

            } elseif ($count <= 16) {
                
                // 🔹 Round-1 + Quarterfinal
                $quarterfinalSlots = 8;
                $round1Players = max(0, $count - $quarterfinalSlots);
                $round1 = array_slice($sheetPlayers, 0, $round1Players, true);
                $quarterfinalPlayers = array_slice($sheetPlayers, $round1Players, null, true);

                // Round-1 insert
                $groupCounter = 1;
                foreach ($round1 as $userId => $userName) {
                    // Real Player
                    DrawSheet::create([
                        'tournament_id' => $decrypted_id,
                        'user_id'       => $userId,
                        'user_name'     => $userName,
                        'sheet'         => $sheetNumber,
                        'match'         => 4, // round-1
                        'match_group'   => $groupCounter,
                        'group_set'     => 1,
                        'round_no'      => 4,
                        'status'        => 'pending'
                    ]);
                
                    // Blank Opponent
                    DrawSheet::create([
                        'tournament_id' => $decrypted_id,
                        'user_id'       => null,
                        'user_name'     => null,
                        'sheet'         => $sheetNumber,
                        'match'         => 4, // round-1
                        'match_group'   => $groupCounter,
                        'group_set'     => 2,
                        'round_no'      => 4,
                        'status'        => 'pending'
                    ]);
                
                    $groupCounter++;
                }

                // Quarterfinal insert
                $quarterfinalMatches = array_chunk($quarterfinalPlayers, 2, true);
                $groupCounter1 = 1;
                foreach ($quarterfinalMatches as $matchIndex => $matchPlayers) {
                    foreach ($matchPlayers as $userId => $userName) {
                        DrawSheet::create([
                            'tournament_id' => $decrypted_id,
                            'user_id'       => $userId,
                            'user_name'     => $userName,
                            'sheet'         => $sheetNumber,
                            'match'         => 3, // quarterfinal
                            'match_group'   =>  $matchIndex + 1,
                            'group_set'     => $groupCounter1,
                            'round_no'      => 3,
                            'status'        => 'pending'
                        ]);

                        $groupCounter1++;
                    }
                }

                for ($i = 1; $i <= 4; $i++) {
                    DrawSheet::create([
                        'tournament_id' => $decrypted_id,
                        'user_id'       => null,
                        'user_name'     => null,
                        'sheet'         => $sheetNumber,
                        'match'         => 2, // semifinal
                        'match_group'   => ceil($i / 2),
                        'group_set'     => $i,
                        'round_no'      => 2,
                        'status'        => 'pending'
                    ]);
                }
    
                // Final placeholder
                for ($i = 1; $i <= 2; $i++) {
                    DrawSheet::create([
                        'tournament_id' => $decrypted_id,
                        'user_id'       => null,
                        'user_name'     => null,
                        'sheet'         => $sheetNumber,
                        'match'         => 1, // final
                        'match_group'   => 1,
                        'group_set'     => $i,
                        'round_no'      => 1,
                        'status'        => 'pending'
                    ]);
                }

            } else {
                // 🔹 17–32 → Round-2 + Round-1 + Quarterfinal
                $quarterfinalSlots = 16;
                $round1Players = max(0, $count - $quarterfinalSlots);
                $round1 = array_slice($sheetPlayers, 0, $round1Players, true);
                $quarterfinalPlayers = array_slice($sheetPlayers, $round1Players, null, true);

                // Round-1 insert
                $groupCounter = 1;
                foreach ($round1 as $userId => $userName) {
                    // Real Player
                    DrawSheet::create([
                        'tournament_id' => $decrypted_id,
                        'user_id'       => $userId,
                        'user_name'     => $userName,
                        'sheet'         => $sheetNumber,
                        'match'         => 5, // round-1
                        'match_group'   => $groupCounter,
                        'group_set'     => 1,
                        'round_no'      => 4,
                        'status'        => 'pending'
                    ]);
                
                    // Blank Opponent
                    DrawSheet::create([
                        'tournament_id' => $decrypted_id,
                        'user_id'       => null,
                        'user_name'     => null,
                        'sheet'         => $sheetNumber,
                        'match'         => 5, // round-1
                        'match_group'   => $groupCounter,
                        'group_set'     => 2,
                        'round_no'      => 4,
                        'status'        => 'pending'
                    ]);
                
                    $groupCounter++;
                }

                // Quarterfinal insert
                $quarterfinalMatches = array_chunk($quarterfinalPlayers, 2, true);
                $groupCounter1 = 1;
                foreach ($quarterfinalMatches as $matchIndex => $matchPlayers) {
                    foreach ($matchPlayers as $userId => $userName) {
                        DrawSheet::create([
                            'tournament_id' => $decrypted_id,
                            'user_id'       => $userId,
                            'user_name'     => $userName,
                            'sheet'         => $sheetNumber,
                            'match'         => 4, // quarterfinal
                            'match_group'   =>  $matchIndex + 1,
                            'group_set'     => $groupCounter1,
                            'round_no'      => 3,
                            'status'        => 'pending'
                        ]);

                        $groupCounter1++;
                    }
                }

                // Quarterfinal insert
                for ($i = 1; $i <= 8; $i++) {
                    DrawSheet::create([
                        'tournament_id' => $decrypted_id,
                        'user_id'       => null,
                        'user_name'     => null,
                        'sheet'         => $sheetNumber,
                        'match'         => 3, // semifinal
                        'match_group'   => ceil($i / 2),
                        'group_set'     => $i,
                        'round_no'      => 2,
                        'status'        => 'pending'
                    ]);
                }

                for ($i = 1; $i <= 4; $i++) {
                    DrawSheet::create([
                        'tournament_id' => $decrypted_id,
                        'user_id'       => null,
                        'user_name'     => null,
                        'sheet'         => $sheetNumber,
                        'match'         => 2, // semifinal
                        'match_group'   => ceil($i / 2),
                        'group_set'     => $i,
                        'round_no'      => 2,
                        'status'        => 'pending'
                    ]);
                }
    
                // Final placeholder
                for ($i = 1; $i <= 2; $i++) {
                    DrawSheet::create([
                        'tournament_id' => $decrypted_id,
                        'user_id'       => null,
                        'user_name'     => null,
                        'sheet'         => $sheetNumber,
                        'match'         => 1, // final
                        'match_group'   => 1,
                        'group_set'     => $i,
                        'round_no'      => 1,
                        'status'        => 'pending'
                    ]);
                }
            }
            
            DrawSheet::create([
                'tournament_id' => $decrypted_id,
                'user_id'       => null,
                'user_name'     => null,
                'sheet'         => $sheetNumber,
                'match'         => 6, // winner
                'match_group'   => 1,
                'group_set'     => $sheetNumber,
                'round_no'      => 1,
                'status'        => 'pending'
            ]);
        }
        
            if(count($chunks) >= 2){
                DrawSheet::create([
                    'tournament_id' => $decrypted_id,
                    'user_id'       => null,
                    'user_name'     => null,
                    'sheet'         => 1,
                    'match'         => 7, // Champian
                    'match_group'   => 1,
                    'group_set'     => 1,
                    'round_no'      => 1,
                    'status'        => 'pending'
                ]);
            }

            $sheets = DrawSheet::where('tournament_id', $decrypted_id)
                ->orderBy('match')
                ->orderBy('group_set')
                ->get()
                ->groupBy('sheet');  // <-- sheet wise grouping
        
            $allSheets = [];
        
            foreach ($sheets as $sheetNo => $drawSheets) {
                $groupedByMatch = $drawSheets->groupBy('match');
        
                $allSheets[$sheetNo] = [
                    'round2Matches'       => groupMatches($groupedByMatch, 5),
                    'round1Matches'       => groupMatches($groupedByMatch, 4),
                    'quarterfinalMatches' => groupMatches($groupedByMatch, 3),
                    'semifinalMatches'    => groupMatches($groupedByMatch, 2),
                    'finalMatches'        => groupMatches($groupedByMatch, 1),
                    'winner'              => groupMatches($groupedByMatch, 6),
                    'champian'            => groupMatches($groupedByMatch, 7),
                ];
            }
            
            return view('admin.drawsheet.draw_sheet', [
                'allSheets'     => $allSheets,
                'tournament_id' => $decrypted_id,
                'title'         => 'Draw Sheet',
                'turnament'     => $turnament,
                'layout'        => $layout,
            ]);
    }


    public function updateMatchSlot(Request $request)
    {
        try {
            // Validate input
            
            $request->validate([
                'tournament_id' => 'required|integer',
                'match_id'      => 'required|integer',   // yeh "match" field ya unique match identifier hoga
                'group_set'     => 'required|integer',   // kis group_set ka slot update karna hai
                'slot'          => 'required|string|in:player1_id,player2_id',
                'user_id'       => 'nullable|integer',
                'match_group'   => 'required'
            ]);
    
    
           $draw = DrawSheet::where('tournament_id', $request->tournament_id)
             ->where('user_id', $request->user_id)
             ->first();
             
            $exchange = DrawSheet::where('tournament_id', $request->tournament_id)->where('match',$request['match_id'])->where('match_group',$request['match_group'])->where('sheet',$request['sheet_id'])->where('group_set',$request['group_set'])->first();
            
            if(!empty($exchange->user_id) && !empty($exchange->user_name)){
                $draw->user_id = $exchange->user_id;
                $draw->user_name = $exchange->user_name;
                $draw->save();
                
                $exchange->user_id = $draw->user_id;
                $exchange->user_name = $draw->user_name;
                $exchange->save();
            }else{
                if($draw->match < $request->match_id && $request->match_id != 6){
                    $draw->user_id = null;
                    $draw->user_name = null;
                    $draw->save();
                }else{
                    if($request->match_id == 6){
                         $winner = DrawSheet::where('tournament_id', $request->tournament_id)->where('match',1)->where('user_id',$draw->user_id)->first();
                        $winner->status = 2;
                        $winner->save();
            
                        $loss = DrawSheet::where('tournament_id', $request->tournament_id)->where('match',1)->where('user_id','!=',$draw->user_id)->where('match_group',$winner->match_group)->where('sheet',$winner->sheet)->first();
                        $loss->status = 3;
                        $loss->save();
                    }else{
                        $winner = DrawSheet::where('tournament_id', $request->tournament_id)->where('match',$request->match_id+1)->where('user_id',$draw->user_id)->first();
                        $winner->status = 2;
                        $winner->save();
            
                        $loss = DrawSheet::where('tournament_id', $request->tournament_id)->where('match',$request->match_id+1)->where('user_id','!=',$draw->user_id)->where('match_group',$winner->match_group)->where('sheet',$winner->sheet)->first();
                        $loss->status = 3;
                        $loss->save();
                    }
                    
                }
            }
            
                        
    
            // Match find karo
            $match = DrawSheet::where('tournament_id', $request->tournament_id)
                        ->where('match', $request->match_id)
                        ->where('sheet', $request->sheet_id)
                        ->where('group_set', $request->group_set)
                        ->where('match_group',$request->match_group)
                        ->first();
    
            if (!$match) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Match not found'
                ], 404);
            }
            // p($match);
            $player = ApplyTournament::find($request->user_id);
    
            // Update slot (player1_id / player2_id)
            $match->user_id = $request->user_id;
            $match->user_name = $player->name;
            $match->save();
    
            return response()->json([
                'status'  => 'success',
                'message' => 'Match updated successfully',
                'data'    => [
                    'match_id'  => $match->id,
                    'slot'      => $request->slot,
                    'user_id'   => $request->user_id
                ]
            ], 200);
    
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Validation failed',
                'errors'  => $e->errors()
            ], 422);
    
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Something went wrong',
                'error'   => $e->getMessage()
            ], 500);
        }
    }


    public function resetBracket(Request $request)
    {
        // 🔹 DrawSheet empty karna
        $draw = DrawSheet::where('tournament_id', $request->tournament_id)
             ->delete();
    
        return response()->json(['message' => 'Bracket reset successfully!']);
    }



    
    
    
    public function indexname($id)
    {
        $data = array(
            'title' => 'View Tournament',
            'page_title' => 'View Tournament',
            'id' => $id
        );
        return view('admin.drawsheet.viewname')->with($data);
    }
    
    public function anydata(Request $request)
    {
        $anydata = [];
        $user = auth()->guard('admin')->user();
        if($user->id != 1){
            
            $setting = Setting::first();
           $event = !empty($setting->event) ? explode(',', $setting->event) : [];
            
        }else{
            $event = [];
        }
        
        $anydata = Tournament::orderBy('id', 'DESC')->where('status', '<', 3)->where(function ($query) use ($request,$event) {

            if (!empty($request['title'])) {
                $query->where('title', 'LIKE', '%' . $request['title'] . '%');
            }

            if (!empty($request['status'])) {
                $query->where('status', $request['status']);
            }
            if (!empty($event)) {
               
                $query->whereIn('id', $event);
            }
        })->groupBy('title')->get();

        return Datatables::of($anydata)
        
             

           

            ->addColumn('action', function ($anydata) {
                $file_name = "category";
                $encrypted_id = get_encrypted_value($anydata->id, true);
                $action = '<a href="' . url('/admin/draw-sheet-name/' . $encrypted_id) . '"><i class="mdi mdi-eye text-info" title="View"></i></a>&nbsp;&nbsp;  
                        
                        ';
                return $action;
            })
            ->rawColumns(['status', 'action'])
            ->addIndexColumn()->make(true);
    }

    public function anydataname(Request $request,$id)
    {
        $decrypted_id  = get_decrypted_value($id, true);
        $data = Tournament::find($decrypted_id);
        $anydata = [];
        $anydata = Tournament::where('title',$data->title)->with('get_event','get_waight_cat','get_category')->orderBy('id', 'DESC')->where('status', '<', 3)->where(function ($query) use ($request) {

            if (!empty($request['title'])) {
                $query->where('title', 'LIKE', '%' . $request['title'] . '%');
            }

            if (!empty($request['status'])) {
                $query->where('status', $request['status']);
            }
        })->get();
        // p($anydata);

        return Datatables::of($anydata)
        
             ->addColumn('category', function ($anydata) {
                return $anydata['get_category']->title;
            })
            
            ->addColumn('gender_name', function ($anydata) {
                if($anydata->gender == 1){
                    $gen = 'Male';
                }elseif($anydata->gender == 2){
                    $gen = 'Female';
                }else{
                    $gen = 'Other';
                }
                return $gen;
            })

            ->addColumn('event', function ($anydata) {
                return $anydata['get_event']->title;
            })
            
            
            
            ->addColumn('get_waight_cat', function ($anydata) {
                return isset($anydata['get_waight_cat']->title)?$anydata['get_waight_cat']->title:'N/A';
            })
            
            ->addColumn('player_count', function ($anydata){
                return ApplyTournament::where('turnament_id', $anydata->id)->count();
            
            })

            

            ->addColumn('action', function ($anydata) {
                $file_name = "category";
                $encrypted_id = get_encrypted_value($anydata->id, true);
                $action = '<a href="' . url('/admin/draw-sheet/view/' . $encrypted_id) . '"><i class="mdi mdi-eye text-info" title="View"></i></a> &nbsp;&nbsp;  
                        
                        ';
                return $action;
            })
            ->rawColumns(['status', 'action', 'image', 'event', 'get_waight_cat', 'category', 'player_count'])
            ->addIndexColumn()->make(true);
    }
    
    public function sendOtp(Request $request)
    {
        $otp = rand(100000, 999999);

        // Session me save kar do
        Session::put('bracket_otp', $otp);

        // Example: Mail ke through bhejna
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => "http://2factor.in/API/V1/71f9a670-f39e-11ec-9c12-0200cd936042/SMS/9214711000/".$otp,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "GET",
          CURLOPT_POSTFIELDS => "",
          CURLOPT_HTTPHEADER => array(
            "content-type: application/x-www-form-urlencoded"
          ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
          echo "cURL Error #:" . $err;
        } else {
           $response;
        }

        return response()->json(['success' => true, 'message' => 'OTP sent successfully!']);
    }

    // 2. Verify OTP & Reset
    public function verifyOtpAndReset(Request $request)
    {
        $otp = $request->otp;
        $savedOtp = Session::get('bracket_otp');

        if ($otp != $savedOtp) {
            return response()->json(['success' => false, 'message' => 'Invalid OTP']);
        }

        // OTP expire kar do
        Session::forget('bracket_otp');

        return response()->json(['success' => true, 'message' => 'Bracket reset successfully']);
    }

    public function verifyDrawSheetForSubadmin(Request $request)
    {
        $otp = $request->otp;
        $savedOtp = Session::get('bracket_otp');

        if ($otp != $savedOtp) {
            return response()->json(['success' => false, 'message' => 'Invalid OTP']);
        }

        if (!empty($request->tournament_id)) {
            $decrypted_id = get_decrypted_value($request->tournament_id, true);
            $tournament = Tournament::find($decrypted_id);
            if ($tournament) {
                Tournament::where('title', $tournament->title)->update(['draw_sheet_verified' => 1]);
            }
        }

        Session::forget('bracket_otp');

        return response()->json(['success' => true, 'message' => 'Draw sheet verified for subadmin successfully']);
    }
}
