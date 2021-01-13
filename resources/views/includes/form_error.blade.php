@if ($errors->any())
    <div class="alert alert-danger mb-2 mt-3" style="margin: 0 auto;">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
