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
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
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
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
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
            right: -38px;
            top: 35%;
            transform: translateY(-50%);
            padding-left: 15px;
            width: 37px;
            /*height: 2px;*/
            /*background: linear-gradient(90deg, #667eea, #764ba2);*/
            border-bottom: 2px solid #774ba1; /* normal border ko transparent rakho */
            
            z-index: 1;
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
            margin-top: 40px;
            position: relative;
        }
        .match_data {
                margin-bottom: 213px;
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

        .quarterfinal_team:after {
            content: '';
            position: absolute;
            right: -38px;
            height: 50%;
            border-right: solid 2px #774ba1;
            top: 12%;
        }
        
        .round2_team:after {
            content: '';
            position: absolute;
            right: -38px;
            height: 50%;
            border-right: solid 2px #774ba1;
            top: 24%;
        }

        .quarterfinal_team_semi:after {
            content: '';
            position: absolute;
            right: -38px;
            height: 50%;
            border-right: solid 2px #774ba1;
            top: 6%;
        }


        .quarterfinal_team:before {
            content: '';
            position: absolute;
            border-top: solid 2px #774ba1;
            right: -140px;
            width: 104px;
            top: 34.5%;
            height: 10px;
            bottom: 0;
        }
        
        .round2_team:before {
            content: '';
            position: absolute;
            border-top: solid 2px #774ba1;
            right: -140px;
            width: 104px;
            top: 42.5%;
            height: 10px;
            bottom: 0;
        }

        .quarterfinal_team_semi:before {
            content: '';
            position: absolute;
            border-top: solid 2px #774ba1;
            right: -140px;
            width: 104px;
            top: 33.3%;
            height: 10px;
            bottom: 0;
        }

        .semifinal_team:after {
            content: '';
            position: absolute;
            right: -39px;
            height: 79.8%;
            border-right: solid 2px #774ba1;
            top: 4.9%;
        }
        
        .dist{
            margin-top: -2px;
            font-size: 11px;
        }

        .semifinal_team:before {
            content: '';
            position: absolute;
            border-top: solid 2px #774ba1;
            right: -140px;
            width: 104px;
            top: 44.5%;
            height: 10px;
            bottom: 0;
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
        
        
    /*    .round2_single:after {*/
    /*            height: 39%;*/
                /*top: 23%;*/
            
    /*    }*/
    /*    .round2_single:before {*/
            /*top: 60%;*/
    /*    }*/
        
    /*    .quarterfinal_single:after {*/
    /*            height: 5%;*/
    /*top: 12%;*/
            
    /*    }*/
    /*    .quarterfinal_single:before {*/
    /*        top: 17%;*/
    /*    }*/
                    
                    
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
                <div class="sheet-page">
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
                    $count = 16 - count($matches['round2Matches']);
                    $round2Matches = array_chunk($matches['round2Matches'], 2);
                @endphp

                <div class="round" style="margin-top: -{{$count*5}}%;">
                    <h3 class="round-title">Round 1</h3>
                   @php $arrowCounter = 1; @endphp
                    @foreach($round2Matches as $group1)
                    
                        <div class="round2_team">
                            @foreach($group1 as $key=>$match)
                           
                            
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
                    $count = 8 - count($matches['round1Matches']);
                    $round1Matches = array_chunk($matches['round1Matches'], 2);
                @endphp

                @if(!empty($matches['round2Matches']))
                    <div class="round" style="margin-top: 67px;">
                @else 
                    <div class="round" style="margin-top: -{{$count*8.5}}%;">
                @endif
                        
                    <h3 class="round-title">Round 2</h3>
                    @foreach($round1Matches as $group)
                    @php 
                    if(count($group) === 1){
                    $round2_single = 'round2_single';
                    }else{
                    $round2_single = '';
                    }
                        
                        @endphp
                   
                        <div class="quarterfinal_team quarterfinal_team_round2 {{$round2_single}}">
                            @foreach($group as $match)
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
                        $groupedMatches = array_chunk($matches['quarterfinalMatches'], 2);   
                        
                    @endphp
                    @if(count($groupedMatches) ==2 )
                        <div class="round" style="margin-top: 192px;">
                    @else 
                        <div class="round" style="margin-top: -360px;">
                    @endif
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
                @if(count($matches['semifinalMatches']) == 1)
                    <style>
                     .semifinal_team:after {
                        height: 0%;
                        
                    }
            
            
                   .semifinal_team:before {

                        top: 23.8%;
                    
                    }
                    </style>
                    @endif
                <div class="round" style="margin-top:81px;">
                    <h3 class="round-title">Semifinals</h3>
                    <div class="semifinal_team">
                         @if(count($matches['semifinalMatches']) == 1)
                    <style>
                     .semifinal_team:after {
                        height: 0%;
                        
                    }
            
            
                   .semifinal_team:before {

                        top: 23.8%;
                    
                    }
                    
                    </style>
                     <div class="match" data-match="2" data-sheet={{$sheetNo}}>
                    @else 
                    <div class="match" style="margin-bottom: 485px;" data-match="2" data-sheet={{$sheetNo}}>
                    @endif
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
                <div class="round" style="margin-top: 81px;">
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
                
            <div class="">
                    <h2 class="tournament-title">Tournament Standings</h2>
                    @php 
                        $medals = [
                            ['label' => 'Gold', 'class' => 'gold', 'data' => $standings['gold'] ?? null],
                            ['label' => 'Silver', 'class' => 'silver', 'data' => $standings['silver'] ?? null],
                            ['label' => 'Bronze', 'class' => 'bronze', 'data' => $standings['bronze'] ?? null],
                            ['label' => 'Bronze', 'class' => 'bronze', 'data' => $standings['bronze1'] ?? null],
                        ];
                    @endphp
                    <!-- Tournament Table -->
                    <div style="width: 50%;">
                    <table>
                        <thead>
                            <tr>
                                <th>Rank</th>
                                <th>Player</th>
                                <th>Code</th>
                                <th>Medal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($medals as $index => $medal)
                                <tr class="{{ $medal['class'] }}">
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $medal['data']->user_name ?? '' }}</td>
                                    <td>{{ $medal['data']->code ?? '' }}</td>
                                    <td>{{ $medal['label'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    </div>
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
    const { jsPDF } = window.jspdf;
    const sheets = document.querySelectorAll("#contentToPrint .sheet-page");

    let pdf = null;

    for (let i = 0; i < sheets.length; i++) {
        const sheet = sheets[i];

        // 1. High-resolution render (bina kisi CSS change ke)
        const canvas = await html2canvas(sheet, {
            scale: 2.5, // Crisp text
            backgroundColor: "#ffffff",
            useCORS: true
        });

        // Canvas size ko PDF points me convert karo
        const imgWidthPt = canvas.width * 0.5;
        const imgHeightPt = canvas.height * 0.5;
        const imgData = canvas.toDataURL("image/jpeg", 0.98);

        // 2. Exact content size ka PDF Page banao
        if (i === 0) {
            pdf = new jsPDF({
                orientation: imgWidthPt > imgHeightPt ? "l" : "p",
                unit: "pt",
                format: [imgWidthPt, imgHeightPt] // Exact content size
            });
        } else {
            pdf.addPage([imgWidthPt, imgHeightPt], imgWidthPt > imgHeightPt ? "l" : "p");
        }

        // 3. Image zero margin ke sath fit karo
        pdf.addImage(imgData, "JPEG", 0, 0, imgWidthPt, imgHeightPt);
    }

    if (pdf) {
        pdf.save("tournament_bracket.pdf");
    }
}



</script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    let counter = 1;
    document.querySelectorAll(".arrow span").forEach(span => {
        span.textContent = counter++;
    });
});
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