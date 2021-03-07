<div class="resources container-fluid">
    <div class="row">
        <div class="col-6 col-md-2 offset-md-1">
            <div class="resources-holder" title="{{number_format(floor($planetaryResources->rate_fe), 0, ',', '.')}}/h">
                <span class="resources-title title-line">Eisen</span>
                <span class="js-ress-calc resources-amount sub-line" data-rate="{{floor($planetaryResources->rate_fe)}}" data-stored-max="{{$planetaryStorage->fe}}" data-stored="{{$planetaryResources->fe}}">{{number_format(floor($planetaryResources->fe), 0, ',', '.')}}</span>
            </div>
        </div>
        <div class="col-6 col-md-2">
            <div class="resources-holder" title="{{number_format(floor($planetaryResources->rate_lut), 0, ',', '.')}}/h">
                <span class="resources-title title-line">Lutinum</span>
                <span class="js-ress-calc resources-amount sub-line" data-rate="{{floor($planetaryResources->rate_lut)}}" data-stored-max="{{$planetaryStorage->lut}}" data-stored="{{$planetaryResources->lut}}">{{number_format(floor($planetaryResources->lut), 0, ',', '.')}}</span>
            </div>
        </div>
        <div class="col-12 col-md-2">
            <div class="resources-holder" title="{{number_format(floor($planetaryResources->rate_cry), 0, ',', '.')}}/h">
                <span class="resources-title title-line">Kristalle</span>
                <span class="js-ress-calc resources-amount sub-line" data-rate="{{floor($planetaryResources->rate_cry)}}" data-stored-max="{{$planetaryStorage->cry}}" data-stored="{{$planetaryResources->cry}}">{{number_format(floor($planetaryResources->cry), 0, ',', '.')}}</span>
            </div>
        </div>
        <div class="col-6 col-md-2">
            <div class="resources-holder" title="{{number_format(floor($planetaryResources->rate_h2o), 0, ',', '.')}}/h">
                <span class="resources-title title-line">Wasser</span>
                <span class="js-ress-calc resources-amount sub-line" data-rate="{{floor($planetaryResources->rate_h2o)}}" data-stored-max="{{$planetaryStorage->h2o}}" data-stored="{{$planetaryResources->h2o}}">{{number_format(floor($planetaryResources->h2o), 0, ',', '.')}}</span>
            </div>
        </div>
        <div class="col-6 col-md-2">
            <div class="resources-holder" title="{{number_format(floor($planetaryResources->rate_h2), 0, ',', '.')}}/h">
                <span class="resources-title title-line">Wasserstoff</span>
                <span class="js-ress-calc resources-amount sub-line" data-rate="{{floor($planetaryResources->rate_h2)}}" data-stored-max="{{$planetaryStorage->h2}}" data-stored="{{$planetaryResources->h2}}">{{number_format(floor($planetaryResources->h2), 0, ',', '.')}}</span>
            </div>
        </div>
    </div>
</div>
