<div class="col-12 notification" style="margin-bottom: 1px;">
    <a href="#" data-toggle="collapse" data-target="#warningCollapse" aria-expanded="false" aria-controls="warningCollapse" style="color: #000;font-weight: bold;">{{__('settings.warning')}}</a>
    <div class="collapse" id="warningCollapse">
        <div class="row">
            <div class="col-12 col-sm-6">
                {{$warning->level}}%
            </div>
            <div class="col-12 col-sm-6">
                {{__('settings.warningDate', ['date' => $warning->date])}}
            </div>
        </div>
    </div>
</div>
