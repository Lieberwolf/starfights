<div class="container">
    <div class="row">
        <div class="col-12 col-md-8 offset-md-2">
            <div class="row techtree">
                @foreach($data as $entry)
                    <div class="col-12 item-row">
                        <div class="row">
                            <div class="col-6 item-title">
                                @if($isBuilding)
                                    <span>{{$entry->building_name}}</span>
                                @endif
                                @if($isResearch)
                                    <span>{{$entry->research_name}}</span>
                                @endif
                                @if($isShips)
                                    <span>{{$entry->ship_name}}</span>
                                @endif
                                @if($isDefense)
                                    <span>{{$entry->defense_name}}</span>
                                @endif
                            </div>
                            <div class="col-6 item-list">
                                <ul>
                                    @foreach($entry->building_requirements as $key => $req_level)
                                        @if($req_level > 0)
                                            @foreach($infrastructure as $compare)
                                                @if($key == $compare->building_name)
                                                    @if($compare->level >= $req_level)
                                                        <li style="color: green;">{{$key}} (Stufe: {{$req_level}})</li>
                                                    @else
                                                        <li style="color: red;">{{$key}} (Stufe: {{$req_level}})</li>
                                                    @endif
                                                @endif
                                            @endforeach
                                        @endif
                                    @endforeach
                                    @foreach($entry->research_requirements as $key => $req_level)
                                        @if($req_level > 0)
                                            @foreach($knowledge as $compare)
                                                @if($key == $compare->research_name)
                                                    @if($compare->level >= $req_level)
                                                        <li style="color: green;">{{$key}} (Stufe: {{$req_level}})</li>
                                                    @else
                                                        <li style="color: red;">{{$key}} (Stufe: {{$req_level}})</li>
                                                    @endif
                                                @endif
                                            @endforeach
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
