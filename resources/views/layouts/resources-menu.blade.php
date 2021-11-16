<div class="resources">
    <div class="resources-holder" title="{{number_format(floor($planetaryResources['rate_fe']), 0, ',', '.')}}/h">
        <span class="resources-title title-line">Eisen</span>
        <span class="js-ress-calc resources-amount sub-line" data-rate="{{floor($planetaryResources['rate_fe'])}}" data-stored-max="{{$planetaryStorage['max_fe']}}" data-stored="{{$planetaryResources['fe']}}">{{number_format(floor($planetaryResources['fe']), 0, ',', '.')}}</span>
    </div>
    <div class="resources-holder" title="{{number_format(floor($planetaryResources['rate_lut']), 0, ',', '.')}}/h">
        <span class="resources-title title-line">Lutinum</span>
        <span class="js-ress-calc resources-amount sub-line" data-rate="{{floor($planetaryResources['rate_lut'])}}" data-stored-max="{{$planetaryStorage['max_lut']}}" data-stored="{{$planetaryResources['lut']}}">{{number_format(floor($planetaryResources['lut']), 0, ',', '.')}}</span>
    </div>
    <div class="resources-holder" title="{{number_format(floor($planetaryResources['rate_cry']), 0, ',', '.')}}/h">
        <span class="resources-title title-line">Kristalle</span>
        <span class="js-ress-calc resources-amount sub-line" data-rate="{{floor($planetaryResources['rate_cry'])}}" data-stored-max="{{$planetaryStorage['max_cry']}}" data-stored="{{$planetaryResources['cry']}}">{{number_format(floor($planetaryResources['cry']), 0, ',', '.')}}</span>
    </div>
    <div class="resources-holder" title="{{number_format(floor($planetaryResources['rate_h2o']), 0, ',', '.')}}/h">
        <span class="resources-title title-line">Wasser</span>
        <span class="js-ress-calc resources-amount sub-line" data-rate="{{floor($planetaryResources['rate_h2o'])}}" data-stored-max="{{$planetaryStorage['max_h2o']}}" data-stored="{{$planetaryResources['h2o']}}">{{number_format(floor($planetaryResources['h2o']), 0, ',', '.')}}</span>
    </div>
    <div class="resources-holder" title="{{number_format(floor($planetaryResources['rate_h2']), 0, ',', '.')}}/h">
        <span class="resources-title title-line">Wasserstoff</span>
        <span class="js-ress-calc resources-amount sub-line" data-rate="{{floor($planetaryResources['rate_h2'])}}" data-stored-max="{{$planetaryStorage['max_h2']}}" data-stored="{{$planetaryResources['h2']}}">{{number_format(floor($planetaryResources['h2']), 0, ',', '.')}}</span>
    </div>
</div>
