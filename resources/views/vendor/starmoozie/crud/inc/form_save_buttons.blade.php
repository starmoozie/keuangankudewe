@if(isset($saveAction['active']) && !is_null($saveAction['active']['value']))
    <div id="saveActions" class="form-group">

        <input type="hidden" name="_save_action" value="{{ $saveAction['active']['value'] }}">
        @if(!empty($saveAction['options']))
            <div class="btn-group" role="group">
        @endif

        <button type="submit" class="btn btn-outline-primary btn-sm shadow-sm">
            <span class="la la-save" role="presentation" aria-hidden="true"></span> &nbsp;
            <span data-value="{{ $saveAction['active']['value'] }}">{{ $saveAction['active']['label'] }}</span>
        </button>

        <div class="btn-group" role="group">
            @if(!empty($saveAction['options']))
                <button id="btnGroupDrop1" type="button" class="btn btn-outline-primary shadow-sm btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="caret"></span><span class="sr-only">&#x25BC;</span></button>
                <div class="dropdown-menu shadow-sm" aria-labelledby="btnGroupDrop1">
                    @foreach( $saveAction['options'] as $value => $label)
                    <a class="dropdown-item" href="javascript:void(0);" data-value="{{ $value }}">{{ $label }}</a>
                    @endforeach
                </div>
            @endif
        </div>

        @if(!empty($saveAction['options']))
            </div>
        @endif

        @if(!$crud->hasOperationSetting('showCancelButton') || $crud->getOperationSetting('showCancelButton') == true)
            <a href="{{ $crud->hasAccess('list') ? url($crud->route) : url()->previous() }}" class="btn btn-default"><span class="la la-ban"></span> &nbsp;{{ trans('starmoozie::crud.cancel') }}</a>
        @endif

    </div>
@endif

