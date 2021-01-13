<div id="{{$type}}Validation" class="invalid-feedback">
    @if($errors->has($type))
        <ul>
            @foreach($errors->get($type) as $error)
                <li>{{$error}}</li>
            @endforeach
        </ul>
    @endif
</div>
