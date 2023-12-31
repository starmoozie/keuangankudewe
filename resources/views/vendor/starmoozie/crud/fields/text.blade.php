<!-- text input -->

@include('crud::fields.inc.wrapper_start')
    <label>{!! $field['label'] !!}</label>
    @include('crud::fields.inc.translatable_icon')

    @if(isset($field['prefix']) || isset($field['suffix'])) <div class="input-group"> @endif
        @if(isset($field['prefix'])) <div class="input-group-prepend"><span class="input-group-text">{!! $field['prefix'] !!}</span></div> @endif
        <input
            data-init-function="bpFieldInitText"
            type="text"
            name="{{ $field['name'] }}"
            value="{{ old_empty_or_null($field['name'], '') ??  $field['value'] ?? $field['default'] ?? '' }}"
            @include('crud::fields.inc.attributes')
        >
        @if(isset($field['suffix'])) <div class="input-group-append"><span class="input-group-text">{!! $field['suffix'] !!}</span></div> @endif
    @if(isset($field['prefix']) || isset($field['suffix'])) </div> @endif

    {{-- HINT --}}
    @if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif
@include('crud::fields.inc.wrapper_end')

@if(isset($field['masking']) && isset($field['masking']['format']))
    @push('crud_fields_scripts')
        @loadOnce('packages/masking/jquery.mask.js')
        <script>
            $(document).ready(function() {
                var id = "{{ $field['name'] }}";
                var format = "{{ $field['masking']['format'] }}"
                $(`[name=${id}]`).mask(format, {reverse: true});
            })
        </script>
    @endpush
@endif
