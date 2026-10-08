@extends($layout ?? 'admin.layout.layout')
@section('content')

    
    <style>
       

        .tournament-container {
           
            backdrop-filter: blur(10px);
            border-radius: 20px;
            /*box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);*/
            /*padding: 30px;*/
            min-width: 1400px;
            margin: 0 auto;
        }

        .tournament-title {
            text-align: left;
            font-size: 1.5rem;
            font-weight: 700;
            /*background: linear-gradient(45deg, #667eea, #764ba2);*/
            /*-webkit-background-clip: text;*/
            /*-webkit-text-fill-color: transparent;*/
            margin-bottom: 40px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        /* .main-content {
            display: flex;
            gap: 30px;
            align-items: flex-start;
            width: 90%;
            margin: 0 auto;
        } */

        .team-pool {
            flex: 0 0 250px;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .pool-title {
            font-size: 1.3rem;
            font-weight: 600;
            color: #4a5568;
            margin-bottom: 15px;
            text-align: center;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 10px;
        }

        .team-item {
            background: linear-gradient(90deg, #f8fafc 0%, #ffffff 100%);
            border-radius: 8px;
            padding: 12px 15px;
            margin-bottom: 8px;
            cursor: move;
            transition: all 0.2s ease;
            border: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .team-item:hover {
            background: linear-gradient(90deg, #e2e8f0 0%, #f1f5f9 100%);
            transform: translateX(5px);
        }

        .team-icon {
            width: 8px;
            height: 8px;
            background: #667eea;
            border-radius: 50%;
        }

        .bracket-container {
            flex: 1;
            position: relative;
        }

        .bracket {
            display: flex;
            /* justify-content: space-around; */
            align-items: center;
            gap: 80px;
            position: relative;
        }

        .round {
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
        }

        .round-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: #4a5568;
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .match {
            background: white;
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            margin-bottom: 73px;
            transition: box-shadow 0.3s cubic-bezier(0.4, 0, 0.2, 1), border-color 0.3s;
            /* border: 2px solid transparent; */
            min-width: 200px;
            position: relative;
            /* overflow: hidden; */
        }
        
        .match2 {
            background: white;
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            margin-bottom: 5px;
            transition: box-shadow 0.3s cubic-bezier(0.4, 0, 0.2, 1), border-color 0.3s;
            /* border: 2px solid transparent; */
            min-width: 200px;
            position: relative;
            /* overflow: hidden; */
        }
        
        .match2:hover {
            /* transform: translateY(-5px); */
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
            border-color: #667eea;
        }

        .match:hover {
            /* transform: translateY(-5px); */
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
            border-color: #667eea;
        }

        .team {
            padding: 1px 5px;
            border-bottom: 1px solid #e2e8f0;
            cursor: move;
            transition: all 0.2s ease;
            position: relative;
            background: linear-gradient(90deg, #f8fafc 0%, #ffffff 100%);
            height: 35px;
            max-width: 200px; /* अपनी container width के हिसाब से set करें */
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            vertical-align: middle;
        }

        .team:last-child {
            border-bottom: none;
        }

        .team:hover {
            background: linear-gradient(90deg, #e2e8f0 0%, #f1f5f9 100%);
            transform: translateX(5px);
        }

        
        
        .team.winner {
           background: linear-gradient(90deg, #d4edda 0%, #c3e6cb 100%);
            padding-left: 8px;
        }
        
        .success_class{
            border-left: 6px solid blue;
            padding-left: 8px;
        }
        
        .team.looser {
            background: linear-gradient(90deg, #f8d7da 0%, #f5c6cb 100%);
            padding-left: 8px;
        }
        
        .danger_class{
            border-left: 6px solid red;
            padding-left: 8px;
        }

        .team.empty {
            color: #a0aec0;
            font-style: italic;
            background: #f7fafc;
            cursor: default;
        }

        .team.empty:hover {
            background: #f7fafc;
            transform: none;
        }

        .team.drag-over {
            background: linear-gradient(90deg, #fff3cd 0%, #ffeaa7 100%);
            border-left: 4px solid #f39c12;
        }

        .team.ui-draggable-dragging {
            transform: rotate(5deg) !important;
            z-index: 1000;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }

        /* Arrow styling */
        .arrow {
            position: absolute;
            right: -22px;
            top: 50%;
            transform: translateY(-50%);
            padding: 0 4px;
            width: auto;
            border-bottom: none;
            background: #fff;
            z-index: 3;
            line-height: 1;
        }
        .arrow span {
            font-size: 12px;
            color: #774ba1;
            font-weight: 600;
        }
        .js-bracket-line {
            position: absolute;
            background: #774ba1;
            z-index: 1;
            pointer-events: none;
        }

        /* .arrow::after {
            content: '';
            position: absolute;
            right: -8px;
            top: -4px;
            width: 0;
            height: 0;
            border-left: 10px solid #764ba2;
            border-top: 5px solid transparent;
            border-bottom: 5px solid transparent;
        } */

        .round:last-child .arrow {
            display: none;
        }


        .champion {
            display: inline-block;
            background: linear-gradient(135deg, #ffd700 0%, #ffed4e 100%);
            color: #744210;
            padding: 20px 40px;
            border-radius: 50px;
            font-size: 1.5rem;
            font-weight: 700;
            box-shadow: 0 10px 30px rgba(255, 215, 0, 0.4);
            border: 3px solid #e6c200;
            min-width: 200px;
            transition: all 0.3s ease;
        }

        /* .champion:hover {
            transform: scale(1.05);
            box-shadow: 0 15px 40px rgba(255, 215, 0, 0.6);
        } */

        .champion.empty {
            background: #f8f9fa;
            color: #6c757d;
            border-color: #dee2e6;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .lists-section {
            margin-top: 40px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }

        .list-container {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        .list-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #4a5568;
            margin-bottom: 15px;
            text-align: center;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 8px;
        }

        .list-item {
            background: #f8fafc;
            border-radius: 8px;
            padding: 10px 15px;
            margin-bottom: 8px;
            border-left: 4px solid #667eea;
            transition: all 0.2s ease;
        }

        /* .list-item:hover {
            background: #e2e8f0;
            transform: translateX(3px);
        } */

        .list-item.winner {
            background: linear-gradient(90deg, #d4edda 0%, #c3e6cb 100%);
            border-left-color: #28a745;
            font-weight: 600;
            color: #155724;
        }



        .list-item.eliminated {
            background: #f8d7da;
            border-left-color: #dc3545;
            color: #721c24;
            opacity: 0.7;
        }

        .reset-btn {
           
            background: linear-gradient(135deg, #ff6b6b, #ee5a24);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 25px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(238, 90, 36, 0.3);
        }
        .reset-btn_div {
    position: absolute;
    top: 118px;
    right: 41px;
    z-index: 999;
}

        .reset-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(238, 90, 36, 0.4);
        }

        /* Animations */
        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }
        }

        .match.highlight {
            animation: pulse 0.6s ease-in-out;
            border-color: #667eea;
        }

        /* Responsive Design */
        @media (max-width: 1200px) {
            .tournament-container {
                min-width: auto;
            }

            .main-content {
                flex-direction: column;
            }

            .team-pool {
                flex: none;
                width: 100%;
                margin-bottom: 20px;
            }

            .bracket {
                gap: 40px;
            }
        }

        @media (max-width: 768px) {
            .tournament-container {
                padding: 20px;
            }

            .bracket {
                flex-direction: column;
                gap: 30px;
            }

            .tournament-title {
                font-size: 2rem;
            }

            .lists-section {
                grid-template-columns: 1fr;
            }
        }

        .quarterfinal_team,
        .round2_team,
        .semifinal_team {
            position: relative;
        }

        /* arrow css */


        .champion-container {
            text-align: center;
            margin-top: 16px;
            position: relative;
            border: none !important;
            box-shadow: none !important;
            background: transparent !important;
            min-width: 0 !important;
            width: auto;
        }
        .champion-container .champion {
            border: none;
            box-shadow: none;
            background: transparent;
            min-width: 0;
            padding: 4px 0;
        }
        .podium-wrap {
            display: flex;
            justify-content: flex-end;
            margin: 8px 24px 0 0;
        }
        .podium {
            width: 260px;
            border-collapse: collapse;
            margin: 0;
        }
        .podium td {
            border: 1px solid #c8c8c8;
            padding: 6px 8px;
            font-size: 13px;
            text-align: left;
            background: #fff;
            color: #222;
            height: 26px;
        }
        .podium .podium-place {
            width: 48px;
            font-weight: 500;
        }
        .match_data {
                margin-bottom: 16px;
            }
        .quarterfinal_team_semi .match_data:last-child {
                margin-bottom: 0;
            }
        .quarterfinal_team_semi {
                margin-bottom: 28px;
            }
        .semifinal_team > .match {
                margin-bottom: 16px;
            }
        .semifinal_team > .match:last-child {
                margin-bottom: 0;
            }
        .quarterfinal_team_round2 > .match,
        .round2_team > .match {
                margin-bottom: 16px;
            }
        .quarterfinal_team_round2 > .match:last-child,
        .round2_team > .match:last-child {
                margin-bottom: 0;
            }
        .quarterfinal_team_round2,
        .round2_team {
                margin-bottom: 18px;
            }

        .champion-arrow {
            position: absolute;
            top: -30px;
            left: 50%;
            transform: translateX(-50%);
            width: 2px;
            height: 20px;
            background: linear-gradient(180deg, #667eea, #764ba2);
        }

        .champion-arrow::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: -4px;
            width: 0;
            height: 0;
            border-top: 10px solid #764ba2;
            border-left: 5px solid transparent;
            border-right: 5px solid transparent;
        }

        /* CSS join lines disabled — JS draws exact center-to-center connectors */
        .quarterfinal_team:after,
        .round2_team:after,
        .quarterfinal_team_semi:after,
        .semifinal_team:after,
        .quarterfinal_team:before,
        .round2_team:before,
        .quarterfinal_team_semi:before,
        .semifinal_team:before,
        .quarterfinal_team_round2:after,
        .quarterfinal_team_round2:before {
            content: none !important;
            display: none !important;
            border: none !important;
        }
        
        .dist{
            margin-top: -2px;
            font-size: 11px;
        }
        
        /* Table Styling */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 10px 15px;
            text-align: center;
            border: 1px solid #ddd;
            font-size: 1rem;
        }

        th {
            background-color: #f4f4f4;
            font-weight: bold;
        }

        /* Gold, Silver, and Bronze Styling */
        .gold {
            background: linear-gradient(135deg, #ffd700 0%, #ffed4e 100%);
            color: #744210;
        }

        .silver {
            background: linear-gradient(135deg, #c0c0c0 0%, #d3d3d3 100%);
            color: #6c757d;
        }

        .bronze {
            background: linear-gradient(135deg, #cd7f32 0%, #f0c59d 100%);
            color: #5c4033;
        }

        .other {
            background-color: #f1f5f9;
        }
        
        .sheet-page {
    page-break-after: always;  /* ✅ Force separate page */
    margin-bottom: 20px;
    background: #fff;
    padding: 20px;
}
        
        
        .sheet-xl .match,
        .sheet-xl .match2,
        .sheet-lg .match,
        .sheet-lg .match2,
        .sheet-md .match,
        .sheet-md .match2,
        .sheet-sm .match,
        .sheet-sm .match2,
        .sheet-xs .match,
        .sheet-xs .match2 {
            border-radius: 0;
            box-shadow: none;
            border: 1px solid #cfcfcf;
        }

        .sheet-xl .match, .sheet-xl .match2 { min-width: 520px; }
        .sheet-xl .team { height: auto; min-height: 68px; max-width: none; font-size: 20px; padding: 10px 14px; }
        .sheet-xl .dist { font-size: 16px; }
        .sheet-xl strong { font-size: 22px; }

        .sheet-lg .match, .sheet-lg .match2 { min-width: 420px; }
        .sheet-lg .team { height: auto; min-height: 56px; max-width: none; font-size: 18px; padding: 8px 12px; }
        .sheet-lg .dist { font-size: 14px; }
        .sheet-lg strong { font-size: 19px; }

        .sheet-md .match, .sheet-md .match2 { min-width: 340px; }
        .sheet-md .team { height: auto; min-height: 48px; max-width: none; font-size: 16px; padding: 6px 10px; }
        .sheet-md .dist { font-size: 13px; }
        .sheet-md strong { font-size: 17px; }

        .sheet-sm .match, .sheet-sm .match2 { min-width: 280px; }
        .sheet-sm .team { height: auto; min-height: 40px; max-width: none; font-size: 14px; padding: 4px 8px; }
        .sheet-sm .dist { font-size: 12px; }
        .sheet-sm strong { font-size: 15px; }

        .sheet-xs .match, .sheet-xs .match2 { min-width: 220px; }
        .sheet-xs .team { height: auto; min-height: 32px; max-width: none; font-size: 12px; padding: 2px 6px; }
        .sheet-xs .dist { font-size: 10px; }
        .sheet-xs strong { font-size: 13px; }
        .sheet-xs .bracket { gap: 64px; }

        @page {
            size: A4 landscape;
            margin: 8mm;
        }
        @media print {
            .reset-btn_div { display: none !important; }
            .main-content, .page-content, .container-fluid, .tournament-container {
                min-width: 0 !important;
                width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
            }
        }
                    
                    
    </style>
    
<div class="main-content">
    <div class="page-content">
        
        <div class="container-fluid">
        <div class="reset-btn_div">
            <button class="reset-btn" onclick="resetBracketWithOtp()">Reset Bracket</button>
            <!--<button class="reset-btn" onclick="resetBracket()">Reset Bracket</button>-->
            <button class="reset-btn" onclick="downloadPDF()">Download PDF</button>
         </div>
    <div class="tournament-container" id="contentToPrint">
         

        <div class="" >
            <!-- Team Pool -->
            

            <!-- Bracket -->
            <div class="bracket-container">
               
                @foreach($allSheets as $sheetNo => $matches)
                @php
                    $sheetMatchCount = count($matches['round2Matches'] ?? [])
                        + count($matches['round1Matches'] ?? [])
                        + count($matches['quarterfinalMatches'] ?? [])
                        + count($matches['semifinalMatches'] ?? [])
                        + count($matches['finalMatches'] ?? []);
                    if ($sheetMatchCount <= 2) {
                        $sheetSize = 'sheet-xl';
                    } elseif ($sheetMatchCount <= 5) {
                        $sheetSize = 'sheet-lg';
                    } elseif ($sheetMatchCount <= 9) {
                        $sheetSize = 'sheet-md';
                    } elseif ($sheetMatchCount <= 16) {
                        $sheetSize = 'sheet-sm';
                    } else {
                        $sheetSize = 'sheet-xs';
                    }
                @endphp
                <div class="sheet-page {{ $sheetSize }}">
                @if($sheetNo == 1)
                <h1 class="tournament-title"><img src="{{url($setting->header_logo)}}" height="50px"> {{$turnament->title}}</h1>
                <h2 class="tournament-title">🏆 {{$turnament['get_category']->title}} {{ $turnament->gender == 1 ? 'Male' : 'Female' }} {{$turnament['get_waight_cat']->title}}</h2>
                @endif
                <h2 class="text-center">Sheet {{ $sheetNo }}</h2>

    <div class="bracket-container">
        <div class="bracket">

            <!-- Round 2 -->
            @if(!empty($matches['round2Matches']))
                @php
                    // Attach only to next-round matches that have Upcoming blanks
                    $round2Matches = groupFeedersByBlanks(
                        $matches['round2Matches'],
                        $matches['round1Matches'] ?? []
                    );
                    if (empty($round2Matches)) {
                        $tmp = [];
                        foreach (array_chunk($matches['round2Matches'], 2) as $i => $g) {
                            $tmp[] = ['target_index' => $i, 'matches' => $g];
                        }
                        $round2Matches = $tmp;
                    }
                @endphp

                <div class="round round-early">
                    <h3 class="round-title">Round 1</h3>
                   @php $arrowCounter = 1; @endphp
                    @foreach($round2Matches as $group1)
                        @php
                            $feederMatches = $group1['matches'] ?? $group1;
                            $feedTarget = $group1['target_index'] ?? '';
                            $round1_single = count($feederMatches) === 1 ? 'round2_single' : '';
                        @endphp
                        <div class="round2_team {{ $round1_single }}" data-feed-target="{{ $feedTarget }}">
                            @foreach($feederMatches as $key=>$match)
                           
                            
                                <div class="match match2" data-match="5" data-sheet={{$sheetNo}}>
                                    <div class="team {{ empty($match[0]['user_id']) ? 'empty' : '' }} {{$match[0]['status_class']}} success_class" 
                                        data-team="{{ $match[0]['user_id'] ?? '' }}" 
                                        data-group="{{ $match[0]['group_set'] ?? '' }}" 
                                        data-match_group="{{ $match[0]['match_group'] ?? '' }}">
                                        <strong class="fw-semibold">{{ $match[0]['user_name'] ?? 'Upcoming' }}</strong><br>  
                                        @if(!empty($match[0]['code']) || !empty($match[0]['district']))
                                            <div class="dist">
                                                {{ $match[0]['code'] ?? '' }} 
                                                {{ !empty($match[0]['code']) && !empty($match[0]['district']) ? '-' : '' }} 
                                                {{ $match[0]['district'] ?? '' }}
                                            </div>
                                        @endif
                                         
                                    </div>
                                    <div class="team {{ empty($match[1]['user_id']) ? 'empty' : '' }} {{$match[1]['status_class']}} danger_class"
                                        data-team="{{ $match[1]['user_id'] ?? '' }}" 
                                        data-group="{{ $match[1]['group_set'] ?? '' }}" 
                                        data-match_group="{{ $match[0]['match_group'] ?? '' }}">
                                        <strong class="fw-semibold">{{ $match[1]['user_name'] ?? 'Upcoming' }}</strong><br>  
                                        @if(!empty($match[1]['code']) || !empty($match[0]['district']))
                                            <div class="dist">
                                                {{ $match[1]['code'] ?? '' }} 
                                                {{ !empty($match[1]['code']) && !empty($match[1]['district']) ? '-' : '' }} 
                                                {{ $match[1]['district'] ?? '' }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="arrow"><span>{{ $arrowCounter }}</span></div>
                                </div>
                            @endforeach
                        </div>
                        @php $arrowCounter++; @endphp
                    @endforeach
                </div>
            @endif

            <!-- Round 1 -->
            @if(!empty($matches['round1Matches']))
                @php
                    // Round 2 → only QF matches that have Upcoming (skip fully filled QF)
                    $round1Matches = groupFeedersByBlanks(
                        $matches['round1Matches'],
                        $matches['quarterfinalMatches'] ?? []
                    );
                    if (empty($round1Matches)) {
                        $tmp = [];
                        foreach (array_chunk($matches['round1Matches'], 2) as $i => $g) {
                            $tmp[] = ['target_index' => $i, 'matches' => $g];
                        }
                        $round1Matches = $tmp;
                    }
                @endphp

                <div class="round round-early">
                        
                    <h3 class="round-title">Round 2</h3>
                    @foreach($round1Matches as $group)
                    @php
                        $feederMatches = $group['matches'] ?? $group;
                        $feedTarget = $group['target_index'] ?? '';
                        $round2_single = count($feederMatches) === 1 ? 'round2_single' : '';
                    @endphp
                   
                        <div class="quarterfinal_team quarterfinal_team_round2 {{$round2_single}}" data-feed-target="{{ $feedTarget }}">
                            @foreach($feederMatches as $match)
                                <div class="match" data-match="4" data-sheet={{$sheetNo}}>
                                    <div class="team {{ empty($match[0]['user_id']) ? 'empty' : '' }} {{$match[0]['status_class']}} success_class" 
                                        data-team="{{ $match[0]['user_id'] ?? '' }}" 
                                        data-group="{{ $match[0]['group_set'] ?? '' }}" 
                                        data-match_group="{{ $match[0]['match_group'] ?? '' }}">
                                        <strong class="fw-semibold">{{ $match[0]['user_name'] ?? 'Upcoming' }}</strong><br>  
                                        @if(!empty($match[0]['code']) || !empty($match[0]['district']))
                                            <div class="dist">
                                                {{ $match[0]['code'] ?? '' }} 
                                                {{ !empty($match[0]['code']) && !empty($match[0]['district']) ? '-' : '' }} 
                                                {{ $match[0]['district'] ?? '' }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="team {{ empty($match[1]['user_id']) ? 'empty' : '' }} {{$match[1]['status_class']}} danger_class" 
                                        data-team="{{ $match[1]['user_id'] ?? '' }}" 
                                        data-group="{{ $match[1]['group_set'] ?? '' }}" 
                                        data-match_group="{{ $match[0]['match_group'] ?? '' }}">
                                        <strong class="fw-semibold">{{ $match[1]['user_name'] ?? 'Upcoming' }}</strong><br>  
                                        @if(!empty($match[1]['code']) || !empty($match[0]['district']))
                                            <div class="dist">
                                                {{ $match[1]['code'] ?? '' }} 
                                                {{ !empty($match[1]['code']) && !empty($match[1]['district']) ? '-' : '' }} 
                                                {{ $match[1]['district'] ?? '' }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="arrow"><span>1</span></div>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            @endif

            <!-- Quarterfinals -->
            @if(!empty($matches['quarterfinalMatches']))
                <!--<div class="round" style="margin-top: 192px;">-->
                    @php
                        // Bye structure (e.g. 6 players): 2 QF → 2 SF blanks → one arrow each
                        // Full bracket (8 players): 4 QF → 2 SF → pair arrows into each SF
                        $sfCount = count($matches['semifinalMatches'] ?? []);
                        $qfChunk = ($sfCount > 0 && count($matches['quarterfinalMatches']) === $sfCount) ? 1 : 2;
                        $groupedMatches = array_chunk($matches['quarterfinalMatches'], $qfChunk);
                    @endphp
                    <div class="round">
                    <h3 class="round-title">Quarterfinals</h3>
                    
                    @foreach($groupedMatches as $qkey=>$group)
                        @php 
                        if(count($group) === 1){
                            $quarterfinal_single = 'quarterfinal_single';
                        }else{
                            $quarterfinal_single = '';
                        }
                        @endphp
                        <div class="quarterfinal_team quarterfinal_team_semi {{$quarterfinal_single}}">
                           
                            @foreach($group as $qucount=>$match)
                            
                                
                                <div class="match match_data" data-match="3" data-sheet={{$sheetNo}}>
                                    <div class="team {{ empty($match[0]['user_id']) ? 'empty' : '' }} {{$match[0]['status_class']}} success_class" 
                                        data-team="{{ $match[0]['user_id'] ?? '' }}" 
                                        data-group="{{ $match[0]['group_set'] ?? '' }}" 
                                        data-match_group="{{ $match[0]['match_group'] ?? '' }}">
                                        <strong class="fw-semibold">{{ $match[0]['user_name'] ?? 'Upcomming' }}</strong><br>
                                        @if(!empty($match[0]['code']) || !empty($match[0]['district']))
                                            <div class="dist">
                                                {{ $match[0]['code'] ?? '' }} 
                                                {{ !empty($match[0]['code']) && !empty($match[0]['district']) ? '-' : '' }} 
                                                {{ $match[0]['district'] ?? '' }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="team {{ empty($match[1]['user_id']) ? 'empty' : '' }} {{$match[1]['status_class']}} danger_class" 
                                        data-team="{{ $match[1]['user_id'] ?? '' }}" 
                                        data-group="{{ $match[1]['group_set'] ?? '' }}" 
                                        data-match_group="{{ $match[1]['match_group'] ?? '' }}">
                                        <strong class="fw-semibold">{{ $match[1]['user_name'] ?? 'Upcomming' }}</strong><br>
                                        @if(!empty($match[1]['code']) || !empty($match[0]['district']))
                                            <div class="dist">
                                                {{ $match[1]['code'] ?? '' }} 
                                                {{ !empty($match[1]['code']) && !empty($match[1]['district']) ? '-' : '' }} 
                                                {{ $match[1]['district'] ?? '' }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="arrow"><span>1</span></div>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            @endif

            <!-- Semifinals -->
            @if(!empty($matches['semifinalMatches']))
                <div class="round">
                    <h3 class="round-title">Semifinals</h3>
                    <div class="semifinal_team {{ count($matches['semifinalMatches']) == 1 ? 'semifinal_single' : '' }}">
                     <div class="match" data-match="2" data-sheet={{$sheetNo}}>
                        <!--<div class="match" style="margin-bottom: 485px;" data-match="2" data-sheet={{$sheetNo}}>-->
                            <div class="team {{ empty($matches['semifinalMatches'][0][0]['user_id']) ? 'empty' : '' }} {{$matches['semifinalMatches'][0][0]['status_class']}} success_class" 
                                data-team="{{ $matches['semifinalMatches'][0][0]['user_id'] ?? '' }}" 
                                data-group="{{ $matches['semifinalMatches'][0][0]['group_set'] ?? '' }}" 
                                data-match_group="{{ $matches['semifinalMatches'][0][0]['match_group'] ?? '' }}">
                                <strong class="fw-semibold">{{ $matches['semifinalMatches'][0][0]['user_name'] ?? 'Upcomming' }}</strong><br>
                                @if(!empty($matches['semifinalMatches'][0][0]['code']) || !empty($matches['semifinalMatches'][0][0]['district']))
                                    <div class="dist">
                                        {{ $matches['semifinalMatches'][0][0]['code'] ?? '' }} 
                                        {{ !empty($matches['semifinalMatches'][0][0]['code']) && !empty($matches['semifinalMatches'][0][0]['district']) ? '-' : '' }} 
                                        {{ $matches['semifinalMatches'][0][0]['district'] ?? '' }}
                                    </div>
                                @endif
                            </div>
                            <div class="team {{ empty($matches['semifinalMatches'][0][1]['user_id']) ? 'empty' : '' }} {{$matches['semifinalMatches'][0][1]['status_class']}} danger_class" 
                                data-team="{{ $matches['semifinalMatches'][0][1]['user_id'] ?? '' }}" 
                                data-group="{{ $matches['semifinalMatches'][0][1]['group_set'] ?? '' }}" 
                                data-match_group="{{ $matches['semifinalMatches'][0][1]['match_group'] ?? '' }}">
                                <strong class="fw-semibold">{{ $matches['semifinalMatches'][0][1]['user_name'] ?? 'Upcomming' }}</strong><br>
                               @if(!empty($matches['semifinalMatches'][0][1]['code']) || !empty($matches['semifinalMatches'][0][1]['district']))
                                    <div class="dist">
                                        {{ $matches['semifinalMatches'][0][1]['code'] ?? '' }} 
                                        {{ !empty($matches['semifinalMatches'][0][1]['code']) && !empty($matches['semifinalMatches'][0][1]['district']) ? '-' : '' }} 
                                        {{ $matches['semifinalMatches'][0][1]['district'] ?? '' }}
                                    </div>
                                @endif
                            </div>
                            <div class="arrow"><span>1</span></div>
                        </div>
                        @if(count($matches['semifinalMatches']) > 1)
                            <div class="match" data-match="2" data-sheet={{$sheetNo}}>
                                <div class="team {{ empty($matches['semifinalMatches'][1][0]['user_id']) ? 'empty' : '' }} {{$matches['semifinalMatches'][1][0]['status_class']}} success_class" 
                                    data-team="{{ $matches['semifinalMatches'][1][0]['user_id'] ?? '' }}"  
                                    data-group="{{ $matches['semifinalMatches'][1][0]['group_set'] ?? '' }}"  
                                    data-match_group="{{ $matches['semifinalMatches'][1][0]['match_group'] ?? '' }}">
                                    <strong class="fw-semibold">{{ $matches['semifinalMatches'][1][0]['user_name'] ?? 'Upcomming' }}</strong><br>
                                    @if(!empty($matches['semifinalMatches'][1][0]['code']) || !empty($matches['semifinalMatches'][1][0]['district']))
                                        <div class="dist">
                                            {{ $matches['semifinalMatches'][1][0]['code'] ?? '' }} 
                                            {{ !empty($matches['semifinalMatches'][1][0]['code']) && !empty($matches['semifinalMatches'][1][0]['district']) ? '-' : '' }} 
                                            {{ $matches['semifinalMatches'][1][0]['district'] ?? '' }}
                                        </div>
                                    @endif
                                </div>
                                <div class="team {{ empty($matches['semifinalMatches'][1][1]['user_id']) ? 'empty' : '' }} {{$matches['semifinalMatches'][1][1]['status_class']}} danger_class" 
                                    data-team="{{ $matches['semifinalMatches'][1][1]['user_id'] ?? '' }}"  
                                    data-group="{{ $matches['semifinalMatches'][1][1]['group_set'] ?? '' }}" 
                                    data-match_group="{{ $matches['semifinalMatches'][1][1]['match_group'] ?? '' }}">
                                    <strong class="fw-semibold">{{ $matches['semifinalMatches'][1][1]['user_name'] ?? 'Upcomming' }}</strong><br>
                                    @if(!empty($matches['semifinalMatches'][1][1]['code']) || !empty($matches['semifinalMatches'][1][1]['district']))
                                        <div class="dist">
                                            {{ $matches['semifinalMatches'][1][1]['code'] ?? '' }} 
                                            {{ !empty($matches['semifinalMatches'][1][1]['code']) && !empty($matches['semifinalMatches'][1][1]['district']) ? '-' : '' }} 
                                            {{ $matches['semifinalMatches'][1][1]['district'] ?? '' }}
                                        </div>
                                    @endif
                                </div>
                                <div class="arrow"><span>1</span></div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif


            
            <!-- Finals -->
            @if(!empty($matches['finalMatches']))
                <div class="round finals-round">
                    <h3 class="round-title">Finals</h3>
                    <div class="match" data-match="1" data-sheet={{$sheetNo}}>
                        <div class="team {{ empty($matches['finalMatches'][0][0]['user_id']) ? 'empty' : '' }} {{$matches['finalMatches'][0][0]['status_class']}} success_class" 
                            data-team="{{ $matches['finalMatches'][0][0]['user_id'] ?? '' }}" 
                            data-group="{{ $matches['finalMatches'][0][0]['group_set'] ?? '' }}" 
                            data-match_group="{{ $matches['finalMatches'][0][0]['match_group'] ?? '' }}">
                            <strong class="fw-semibold">{{ $matches['finalMatches'][0][0]['user_name'] ?? 'Upcomming' }}</strong><br>
                            @if(!empty($matches['finalMatches'][0][0]['code']) || !empty($matches['finalMatches'][0][0]['district']))
                                <div class="dist">
                                    {{ $matches['finalMatches'][0][0]['code'] ?? '' }} 
                                    {{ !empty($matches['finalMatches'][0][0]['code']) && !empty($matches['finalMatches'][0][0]['district']) ? '-' : '' }} 
                                    {{ $matches['finalMatches'][0][0]['district'] ?? '' }}
                                </div>
                            @endif
                        </div>
                        <div class="team {{ empty($matches['finalMatches'][0][1]['user_id']) ? 'empty' : '' }} {{empty($matches['finalMatches'][0][1]['status_class']) ? 'empty' : ''}} danger_class" 
                            data-team="{{ $matches['finalMatches'][0][1]['user_id'] ?? '' }}" 
                            data-group="{{ $matches['finalMatches'][0][1]['group_set'] ?? '' }}" 
                            data-match_group="{{ $matches['finalMatches'][0][1]['match_group'] ?? '' }}">
                            <strong class="fw-semibold">{{ $matches['finalMatches'][0][1]['user_name'] ?? 'Upcomming' }}</strong><br>
                            @if(!empty($matches['finalMatches'][0][1]['code']) || !empty($matches['finalMatches'][0][1]['district']))
                                <div class="dist">
                                    {{ $matches['finalMatches'][0][1]['code'] ?? '' }} 
                                    {{ !empty($matches['finalMatches'][0][1]['code']) && !empty($matches['finalMatches'][0][1]['district']) ? '-' : '' }} 
                                    {{ $matches['finalMatches'][0][1]['district'] ?? '' }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
            
            

        </div>

        <!-- Champion -->
        
        <div class="champion-container match" data-match="6" data-sheet={{$sheetNo}} class="champion-container" style="background:inherit; box-shadow:inherit;">
            <div class="champion-arrow"></div>
            <h3 class="round-title">@if(count($allSheets)>=2) Winner @else🏆 Champion @endif</h3>
            <div class="champion empty team" id="champion" data-team="{{ $matches['winner'][0][0]['user_id'] ?? '' }}" 
                data-group="{{ $matches['winner'][0][0]['group_set'] ?? '' }}" 
                data-match_group="{{ $matches['winner'][0][0]['match_group'] ?? '' }}" style="height:inherit; max-width:inherit;">{{ $matches['winner'][0][0]['user_name'] ?? 'Drag champion here' }} </div>
        </div>
        
        @if($loop->last)
            @if(count($allSheets) >= 2)
                <div class="round" style="margin-top: 81px;">
                    <h3 class="round-title">Both Winner Finals</h3>
                    <div class="match" data-match="1" data-sheet={{$sheetNo}}>
                        <div class="team {{ empty($allSheets[1]['winner'][0][0]['user_id']) ? 'empty' : '' }} {{$allSheets[1]['winner'][0][0]['status_class']}} success_class" 
                            data-team="{{ $allSheets[1]['winner'][0][0]['user_id'] ?? '' }}" 
                            data-group="{{ $allSheets[1]['winner'][0][0]['group_set'] ?? '' }}" 
                            data-match_group="{{ $allSheets[1]['winner'][0][0]['match_group'] ?? '' }}">
                            <strong class="fw-semibold">{{ $allSheets[1]['winner'][0][0]['user_name'] ?? 'Upcomming' }}</strong><br>
                            @if(!empty($allSheets[1]['winner'][0][0]['code']) || !empty($allSheets[1]['winner'][0][0]['district']))
                                <div class="dist">
                                    {{ $allSheets[1]['winner'][0][0]['code'] ?? '' }} 
                                    {{ !empty($allSheets[1]['finalMatches'][0][0]['code']) && !empty($allSheets[1]['finalMatches'][0][0]['district']) ? '-' : '' }} 
                                    {{ $allSheets[1]['finalMatches'][0][0]['district'] ?? '' }}
                                </div>
                            @endif
                        </div>
                        <div class="team {{ empty($allSheets[2]['winner'][0][0]['user_id']) ? 'empty' : '' }} {{$allSheets[2]['winner'][0][0]['status_class']}} danger_class" 
                            data-team="{{ $allSheets[2]['winner'][0][0]['user_id'] ?? '' }}" 
                            data-group="{{ $allSheets[2]['winner'][0][0]['group_set'] ?? '' }}" 
                            data-match_group="{{ $allSheets[2]['winner'][0][0]['match_group'] ?? '' }}">
                            <strong class="fw-semibold">{{ $allSheets[2]['winner'][0][0]['user_name'] ?? 'Upcomming' }}</strong><br>
                            @if(!empty($allSheets[2]['winner'][0][0]['code']) || !empty($allSheets[2]['winner'][0][0]['district']))
                                <div class="dist">
                                    {{ $allSheets[2]['winner'][0][0]['code'] ?? '' }} 
                                    {{ !empty($allSheets[2]['winner'][0][0]['code']) && !empty($allSheets[2]['winner'][0][0]['district']) ? '-' : '' }} 
                                    {{ $allSheets[2]['winner'][0][0]['district'] ?? '' }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <div class="champion-container match" data-match="7" data-sheet="1" class="champion-container" style="background:inherit; box-shadow:inherit;">
                    <div class="champion-arrow" style="height: 96px;top: -110px;"></div>
                    <h3 class="round-title">🏆 Champion</h3>
                    <div class="champion empty team" id="champion" data-team="{{ $allSheets[1]['champian'][0][0]['user_id'] ?? '' }}" 
                        data-group="{{ $allSheets[1]['champian'][0][0]['group_set'] ?? '' }}" 
                        data-match_group="{{ $allSheets[1]['champian'][0][0]['match_group'] ?? '' }}" style="height:inherit; max-width:inherit;">{{ $allSheets[1]['champian'][0][0]['user_name'] ?? 'Drag champion here' }} </div>
                </div>
            @endif
                
            <div class="podium-wrap">
                    @php
                        $medals = [
                            ['label' => '1st', 'data' => $standings['gold'] ?? null],
                            ['label' => '2nd', 'data' => $standings['silver'] ?? null],
                            ['label' => '3rd', 'data' => $standings['bronze'] ?? null],
                            ['label' => '3rd', 'data' => $standings['bronze1'] ?? null],
                        ];
                    @endphp
                    <table class="podium">
                        <tbody>
                            @foreach ($medals as $medal)
                                <tr>
                                    <td class="podium-place">{{ $medal['label'] }}</td>
                                    <td>{{ $medal['data']->user_name ?? '' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
    @endif
    </div>
    
        </div>
    @endforeach
                
                


            </div>
        </div>
        <!-- OTP Modal -->
        <div class="modal fade" id="otpModal" tabindex="-1" aria-labelledby="otpModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-3 shadow">
              <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="otpModalLabel">Enter OTP</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body text-center">
                <p class="mb-3">We have sent an OTP to your registered email/phone.</p>
                <input type="text" id="otpInput" class="form-control text-center mb-3" placeholder="Enter OTP" maxlength="6">
                <button type="button" class="btn btn-primary w-100" onclick="verifyOtp()">Submit</button>
              </div>
            </div>
          </div>
        </div>


        <!-- Tournament Lists -->
       
    </div>
</div>
</div>
</div>

@push('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Scale the overlay based on container width
    function scaleOverlay() {
        const container = document.querySelector('.form-container');
        const overlay = document.querySelector('.form-overlay');

        if (!container || !overlay) return;

        const containerWidth = container.offsetWidth;
        const scale = containerWidth / 600; // base is 600px
        overlay.style.transform = `scale(${scale})`;
    }

    window.addEventListener('load', scaleOverlay);
    window.addEventListener('resize', scaleOverlay);

   async function downloadPDF() {
    if (typeof alignDrawSheet === "function") alignDrawSheet();
    const { jsPDF } = window.jspdf;
    const sheets = document.querySelectorAll("#contentToPrint .sheet-page");

    const pageW = 841.89;
    const pageH = 595.28;
    const margin = 18;
    const maxW = pageW - margin * 2;
    const maxH = pageH - margin * 2;
    let pdf = null;

    for (let i = 0; i < sheets.length; i++) {
        const canvas = await html2canvas(sheets[i], {
            scale: 2,
            backgroundColor: "#ffffff",
            useCORS: true
        });

        const ratio = maxW / canvas.width;
        const sliceH = maxH / ratio;
        let offsetY = 0;

        while (offsetY < canvas.height - 0.5) {
            const pieceH = Math.min(sliceH, canvas.height - offsetY);
            const piece = document.createElement("canvas");
            piece.width = canvas.width;
            piece.height = Math.max(1, Math.ceil(pieceH));
            const ctx = piece.getContext("2d");
            ctx.fillStyle = "#ffffff";
            ctx.fillRect(0, 0, piece.width, piece.height);
            ctx.drawImage(canvas, 0, offsetY, canvas.width, pieceH, 0, 0, canvas.width, pieceH);

            if (!pdf) {
                pdf = new jsPDF({ orientation: "l", unit: "pt", format: "a4" });
            } else {
                pdf.addPage("a4", "l");
            }
            pdf.addImage(piece.toDataURL("image/jpeg", 0.95), "JPEG", margin, margin, maxW, pieceH * ratio);
            offsetY += pieceH;
        }
    }

    if (pdf) {
        pdf.save("tournament_bracket.pdf");
    }
}



</script>
<script>
function alignDrawSheet() {
    document.querySelectorAll(".sheet-page .bracket").forEach(function (bracket) {
        var groups = Array.from(bracket.querySelectorAll(".quarterfinal_team_semi"));
        var semiTeam = bracket.querySelector(".semifinal_team");
        var semiRound = semiTeam ? semiTeam.parentElement : null;
        var semiMatches = semiTeam ? Array.from(semiTeam.children).filter(function (el) {
            return el.classList.contains("match");
        }) : [];
        var finalRound = bracket.querySelector(".finals-round");
        var finalMatch = finalRound ? finalRound.querySelector(".match") : null;
        var round2Groups = Array.from(bracket.querySelectorAll(".quarterfinal_team_round2"));
        var round1Groups = Array.from(bracket.querySelectorAll(".round2_team"));

        bracket.style.position = "relative";
        bracket.style.alignItems = "flex-start";
        bracket.querySelectorAll(".match, .quarterfinal_team, .round2_team, .round, .semifinal_team").forEach(function (el) {
            el.style.top = "0px";
            el.style.marginTop = "0px";
        });
        bracket.querySelectorAll(".js-bracket-line").forEach(function (el) {
            el.remove();
        });

        function centerY(el) {
            var rect = el.getBoundingClientRect();
            return rect.top + rect.height / 2;
        }

        function groupMatches(group) {
            return Array.from(group.children).filter(function (el) {
                return el.classList.contains("match");
            });
        }

        function groupMid(group) {
            var matches = groupMatches(group);
            if (!matches.length) return centerY(group);
            return matches.reduce(function (sum, match) {
                return sum + centerY(match);
            }, 0) / matches.length;
        }

        function addMargin(el, delta) {
            if (!el || Math.abs(delta) < 0.5) return;
            el.style.marginTop = ((parseFloat(el.style.marginTop) || 0) + delta) + "px";
        }

        function alignTargetsToSources(sourceMids, targets) {
            var count = Math.min(sourceMids.length, targets.length);
            for (var i = 0; i < count; i++) {
                addMargin(targets[i], sourceMids[i] - centerY(targets[i]));
            }
        }

        function feedTargetIndex(group, fallback) {
            var raw = group.getAttribute("data-feed-target");
            if (raw !== null && raw !== "" && !isNaN(parseInt(raw, 10))) {
                return parseInt(raw, 10);
            }
            return fallback;
        }

        function resolveFeedTargets(sourceGroups, targetMatches) {
            return sourceGroups.map(function (group, i) {
                var idx = feedTargetIndex(group, i);
                return targetMatches[idx] || targetMatches[i] || null;
            }).filter(Boolean);
        }

        function alignColumn(sourceGroups, targetMatches) {
            if (!sourceGroups.length || !targetMatches.length) return;
            var gap = 18;
            for (var i = 1; i < sourceGroups.length; i++) {
                var previous = sourceGroups[i - 1].getBoundingClientRect();
                var current = sourceGroups[i].getBoundingClientRect();
                if (current.top < previous.bottom + gap) {
                    addMargin(sourceGroups[i], (previous.bottom + gap) - current.top);
                }
            }
            var pairedTargets = resolveFeedTargets(sourceGroups, targetMatches);
            var mids = sourceGroups.map(groupMid);
            if (pairedTargets[0]) {
                var targetColumn = pairedTargets[0].closest(".round");
                if (targetColumn) addMargin(targetColumn, mids[0] - centerY(pairedTargets[0]));
            }
            for (var n = 0; n < sourceGroups.length; n++) {
                var target = pairedTargets[n];
                if (!target) continue;
                addMargin(target, groupMid(sourceGroups[n]) - centerY(target));
            }
        }

        var qfMatches = Array.from(bracket.querySelectorAll(".quarterfinal_team_semi > .match"));
        var round2Matches = Array.from(bracket.querySelectorAll(".quarterfinal_team_round2 > .match"));
        // Round 2 groups → QF matches with Upcoming (via data-feed-target)
        alignColumn(round2Groups, qfMatches);
        alignColumn(round1Groups, round2Matches);

        if (semiRound && semiMatches.length && groups.length) {
            var qfMids = groups.map(groupMid);
            addMargin(semiRound, qfMids[0] - centerY(semiMatches[0]));
            alignTargetsToSources(qfMids, semiMatches);
        }

        if (finalRound && finalMatch && semiMatches.length) {
            var semiMid = semiMatches.reduce(function (sum, match) {
                return sum + centerY(match);
            }, 0) / semiMatches.length;
            addMargin(finalRound, semiMid - centerY(finalMatch));
        }

        // Second pass after layout settles
        if (semiRound && semiMatches.length && groups.length) {
            alignTargetsToSources(groups.map(groupMid), semiMatches);
        }
        if (finalRound && finalMatch && semiMatches.length) {
            var semiMid2 = semiMatches.reduce(function (sum, match) {
                return sum + centerY(match);
            }, 0) / semiMatches.length;
            addMargin(finalRound, semiMid2 - centerY(finalMatch));
        }

        // Draw exact connectors from match center → next match center
        var root = bracket.getBoundingClientRect();

        function pointRight(match) {
            var r = match.getBoundingClientRect();
            return {
                x: r.right - root.left,
                y: r.top + r.height / 2 - root.top
            };
        }

        function pointLeft(match) {
            var r = match.getBoundingClientRect();
            return {
                x: r.left - root.left,
                y: r.top + r.height / 2 - root.top
            };
        }

        function addLine(x1, y1, x2, y2) {
            var el = document.createElement("div");
            el.className = "js-bracket-line";
            if (Math.abs(y1 - y2) < 0.5) {
                el.style.left = Math.min(x1, x2) + "px";
                el.style.top = (y1 - 1) + "px";
                el.style.width = Math.max(1, Math.abs(x2 - x1)) + "px";
                el.style.height = "2px";
            } else {
                el.style.left = (x1 - 1) + "px";
                el.style.top = Math.min(y1, y2) + "px";
                el.style.width = "2px";
                el.style.height = Math.max(1, Math.abs(y2 - y1)) + "px";
            }
            bracket.appendChild(el);
        }

        function connectPair(sources, target) {
            if (!target || !sources.length) return;
            var t = pointLeft(target);
            if (sources.length === 1) {
                var s = pointRight(sources[0]);
                var midX = (s.x + t.x) / 2;
                addLine(s.x, s.y, midX, s.y);
                if (Math.abs(s.y - t.y) > 0.5) addLine(midX, s.y, midX, t.y);
                addLine(midX, t.y, t.x, t.y);
                return;
            }
            var pts = sources.map(pointRight);
            var joinX = Math.min.apply(null, pts.map(function (p) { return p.x; })) + 28;
            var midY = t.y;
            pts.forEach(function (p) {
                addLine(p.x, p.y, joinX, p.y);
            });
            var minY = Math.min.apply(null, pts.map(function (p) { return p.y; }));
            var maxY = Math.max.apply(null, pts.map(function (p) { return p.y; }));
            addLine(joinX, minY, joinX, maxY);
            addLine(joinX, midY, t.x, midY);
        }

        // Early rounds → next matches (attach to Upcoming targets, not filled ones)
        if (round1Groups.length && round2Matches.length) {
            round1Groups.forEach(function (group, i) {
                var target = round2Matches[feedTargetIndex(group, i)] || null;
                connectPair(groupMatches(group), target);
            });
        }
        if (round2Groups.length && qfMatches.length) {
            round2Groups.forEach(function (group, i) {
                var target = qfMatches[feedTargetIndex(group, i)] || null;
                connectPair(groupMatches(group), target);
            });
        }

        // QF → SF (1→1 when bye blanks, or paired into SF)
        if (groups.length && semiMatches.length) {
            groups.forEach(function (group, i) {
                connectPair(groupMatches(group), semiMatches[i] || null);
            });
        }

        // SF → Final
        if (semiMatches.length && finalMatch) {
            connectPair(semiMatches, finalMatch);
        }
    });
}

document.addEventListener("DOMContentLoaded", function () {
    let counter = 1;
    document.querySelectorAll(".arrow span").forEach(span => {
        span.textContent = counter++;
    });
    alignDrawSheet();
});
window.addEventListener("load", alignDrawSheet);
window.addEventListener("resize", alignDrawSheet);
</script>
    <script>
        $(document).ready(function () {
            // Make teams draggable (both from pool and matches)
            $('.team:not(.empty), .team-item').draggable({
                helper: 'clone',
                revert: 'invalid',
                zIndex: 1000,
                opacity: 0.8,
                start: function (event, ui) {
                    $(this).addClass('ui-draggable-dragging');
                    ui.helper.addClass('ui-draggable-dragging');
                },
                stop: function (event, ui) {
                    $(this).removeClass('ui-draggable-dragging');
                }
            });

            // Make empty team slots and champion droppable
            $('.team, #champion').droppable({
                accept: '.team:not(.empty), .team-item',
                hoverClass: 'drag-over',
                drop: function (event, ui) {
                    const draggedTeamName = ui.draggable.text().trim();
                    const draggedUserId   = ui.draggable.attr("data-team"); // user_id
                    const $dropped        = $(this);

                    // 🔹 Match aur Group info nikalna (parent match div se)
                    const $match   = $dropped.closest(".match");
                    const matchId  = $match.data("match");     // match number (1,2,3…)
                    const sheetId  = $match.data("sheet");
                    const groupSet = $dropped.data("group");
                    const match_group = $dropped.data("match_group");
                    

                    // 🔹 Slot identify karo (top = player1_id, bottom = player2_id)
                    const slot = $dropped.is(":first-child") ? "player1_id" : "player2_id";

                    // UI Update
                    $dropped.text(draggedTeamName)
                        .removeClass("empty drag-over")
                        .attr("data-team", draggedUserId);

                    // 🔹 Backend Update (AJAX)
                    $.ajax({
                        url: "{{ route('update.match.slot') }}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            tournament_id: "{{ $tournament_id }}",
                            match_id: matchId,    // DB ka "match"
                            sheet_id: sheetId,
                            group_set: groupSet,  // DB ka "group_set"
                            slot: slot,           // player1_id / player2_id
                            user_id: draggedUserId,
                            match_group: match_group,
                        },
                        success: function (res) {
                            location.reload();
                        },
                        error: function (err) {
                            console.error("❌ Error updating match:", err);
                        }
                    });

                    // Highlight animation
                    $dropped.closest(".match, .champion-container")
                        .find(".match, .champion")
                        .addClass("highlight");
                    setTimeout(() => { $(".highlight").removeClass("highlight"); }, 600);

                    // Winner mark
                    if (ui.draggable.hasClass("team")) {
                        ui.draggable.addClass("winner");
                    }

                    // Make dropped draggable again
                    $dropped.draggable({
                        helper: "clone",
                        revert: "invalid",
                        zIndex: 1000,
                        opacity: 0.8,
                        start: function (event, ui) {
                            $(this).addClass("ui-draggable-dragging");
                            ui.helper.addClass("ui-draggable-dragging");
                        },
                        stop: function (event, ui) {
                            $(this).removeClass("ui-draggable-dragging");
                        }
                    });

                    // Champion styling
                    if ($dropped.is("#champion")) {
                        $dropped.removeClass("empty");
                    }

                    // Update lists
                    updateLists();
                }


            });
        });

        

        function resetBracket() {
            $.ajax({
                
                url: "{{ route('reset.bracket') }}",   // 🔹 Route banani hogi
                type: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content') // 🔹 CSRF token Laravel ke liye
                },
                data: {
                    _token: "{{ csrf_token() }}",
                    tournament_id: "{{ $tournament_id }}",
                },
                beforeSend: function() {
                    console.log("Resetting bracket...");
                },
                success: function(response) {
                    location.reload();
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Something went wrong while resetting.'
                    });
                }
            });
        }

    </script>
    <script>
function resetBracketWithOtp() {
    $.ajax({
        url: "{{ route('send.otp') }}",
        type: "POST",
        data: {
            _token: "{{ csrf_token() }}",
            tournament_id: "{{ $tournament_id }}",
        },
        success: function(res) {
            if (res.success) {
                Swal.fire({
                    title: 'Enter OTP',
                    text: 'We have sent an OTP to your phone.',
                    input: 'text',
                    inputAttributes: {
                        maxlength: 6,
                        inputmode: 'numeric',
                        pattern: '\\d{6}'
                    },
                    showCancelButton: true, // 🔹 Remove Cancel button
                    confirmButtonText: 'Submit',
                    cancelButtonText: 'Cancel',
                    allowOutsideClick: false, // 🔹 Can't click outside
                    allowEscapeKey: false,    // 🔹 Can't press ESC
                    inputValidator: (value) => {
                        if (!value) return 'Please enter OTP!';
                        if (value.length !== 6) return 'OTP must be 6 digits!';
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        const otp = result.value;
                        // 🔹 Verify OTP & reset bracket
                        $.ajax({
                            url: "{{ route('verify.otp.and.reset') }}",
                            type: "POST",
                            data: {
                                _token: "{{ csrf_token() }}",
                                tournament_id: "{{ $tournament_id }}",
                                otp: otp
                            },
                            success: function(res2) {
                                if (res2.success) {
                                    resetBracket();
                                    Swal.fire("Success", "Bracket has been reset!", "success");
                                } else {
                                    Swal.fire("Error", res2.message, "error");
                                }
                            },
                            error: function(xhr) {
                                console.error(xhr.responseText);
                                Swal.fire("Error", "OTP verification failed.", "error");
                            }
                        });
                    }
                });
            } else {
                Swal.fire("Error", res.message, "error");
            }
        },
        error: function(xhr) {
            console.error(xhr.responseText);
            Swal.fire("Error", "Could not send OTP.", "error");
        }
    });
}

</script>

    @endpush
@endsection