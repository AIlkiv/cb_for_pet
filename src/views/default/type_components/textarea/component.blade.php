<div class='form-group {{$header_group_class}} {{ ($errors->first($name))?"has-error":"" }}' id='form-group-{{$name}}' style="{{@$form['style']}}">
    <label class='control-label col-sm-2'>{{$form['label']}}
        @if(!empty($required))
            <span class='text-danger' title='{!! trans('crudbooster.this_field_is_required') !!}'>*</span>
        @endif
    </label>
    <div class="{{$col_width?:'col-sm-10'}}">
        <textarea name="{{$form['name']}}" id="{{$name}}"
                  {{$required}} {{$readonly}} {!!$placeholder!!} {{$disabled}} {{(!empty($validation['max']))?"maxlength=".$validation['max']:""}} class='form-control'
                  rows='5'>{{ $value}}</textarea>
        <div class="text-danger">{!! $errors->first($name)?"<i class='fa fa-info-circle'></i> ".$errors->first($name):"" !!}</div>
        <p class='help-block'>{{ @$form['help'] }}</p>
    </div>
</div>