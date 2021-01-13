        @if(Session::has('success') && Session::get('success') == 'delete')
            @include('includes.alerts.success_delete')
        @endif

        @if(Session::has('success') && Session::get('success') == 'create')
            @include('includes.alerts.success_create')
        @endif

        @if(Session::has('success') && Session::get('success') == 'update')
            @include('includes.alerts.success_update')
        @endif

        @if(Session::has('error') && Session::get('error') == 'delete')
            @include('includes.alerts.error_delete')
        @endif

        @if(Session::has('error') && Session::get('error') == 'create')
            @include('includes.alerts.error_create')
        @endif

        @if(Session::has('error') && Session::get('error') == 'update')
            @include('includes.alerts.error_update')
        @endif

        @if(Session::has('error') && Session::get('error') == 'system')
            @include('includes.alerts.error_system')
        @endif
