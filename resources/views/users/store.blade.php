@extends('layouts.gui')

@section('content')
    <section class="container mx-auto" style="max-width: 500px">
        <div class="card">
            <div class="card-header">
            @if(isset($user))
                @lang('gui.user_update')
                <i id="deleteButton" class="far float-end fa-trash-alt" style="color: red; cursor: pointer;margin-top: 4px"></i>

                <form name="delete_form" action="{{route('users.destroy', $user)}}" method="POST" id="delete_form">
                    @csrf
                    @method('DELETE')
                </form>
            @else
                @lang('gui.user_new')
            @endif
            </div>
            <div class="card-body">
                <form name="user_form" action="@if(isset($user)){{route('users.update', $user)}}@else{{route('users.store')}}@endif" method="POST" id="user_form" >
                    @csrf
                    @if(isset($user))
                        @method('PUT')
                    @endif
                    <div class="form-floating mb-3">
                        <input required type="text" class="form-control {{$errors->has('name')?'is-invalid':''}}" name="name" id="name" placeholder="@lang('gui.user_name')" value="@if(isset($user)){{$user->name}}@else{{old('name')}}@endif">
                        <label for="name">
                            @lang('gui.user_name')
                        </label>
                        @include('includes.form_feedback', ['type' => 'name'])
                    </div>

                    <div class="form-floating mb-3">
                        <input required type="text" class="form-control {{$errors->has('username')?'is-invalid':''}} " name="username" id="username" placeholder="@lang('gui.user_username')" value="@if(isset($user)){{$user->username}}@else{{old('username')}}@endif">
                        <label for="username">
                            @lang('gui.user_username')
                        </label>
                        @include('includes.form_feedback', ['type' => 'username'])
                    </div>

                    <div class="form-floating mb-3">
                        <input type="password" class="form-control {{$errors->has('password')?'is-invalid':''}}" name="password" id="password" placeholder="@lang('gui.user_password')">
                        <label for="user_name">
                            @lang('gui.user_password')
                        </label>
                        <div style="font-size: 8pt">@lang('gui.user_password_note')</div>
                        @include('includes.form_feedback', ['type' => 'password'])
                    </div>

                    <div class="form-floating mb-3">
                        <input type="password" class="form-control {{$errors->has('password')?'is-invalid':''}}" name="password_confirmation" id="password_confirmation" placeholder="@lang('gui.user_password_re')">
                        <label for="password_confirmation">
                            @lang('gui.user_password_re')
                        </label>
                    </div>

                    <br>

                    <h6>@lang('gui.user_is_admin')</h6>

                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="role" id="role_user" value="user" @if((isset($user) && $user->isUser()) || !isset($user)) checked @endif>
                      <label class="form-check-label" for="role_user">
                        @lang('gui.user_role_user')
                      </label>
                    </div>

                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="role" id="role_admin" value="admin" @if((isset($user) && $user->isAdmin())) checked @endif>
                      <label class="form-check-label" for="role_admin">
                        @lang('gui.user_role_admin')
                      </label>
                    </div>
                    @include('includes.form_feedback', ['type' => 'role'])

                    <br>
                    <h6>@lang('gui.user_status')</h6>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="status" id="status_active" value="active" @if((isset($user) && $user->active) || !isset($user)) checked @endif>
                      <label class="form-check-label" for="status_active">
                        @lang('gui.user_active')
                      </label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="status" id="status_inactive" value="inactive" @if((isset($user) && !$user->active)) checked @endif>
                      <label class="form-check-label" for="status_inactive">
                        @lang('gui.user_inactive')
                      </label>
                    </div>
                    @include('includes.form_feedback', ['type' => 'status'])


                    @if(isset($user))
                        <button type="submit" class="btn btn-sm btn-warning mt-4">@lang('gui.update_action')</button>
                    @else
                        <button type="submit" class="btn btn-sm btn-success mt-4">@lang('gui.add_action')</button>
                    @endif
                    <div id="error" class="is-invalid"></div>
                    @include('includes.form_feedback', ['type' => 'error'])
                </form>
            </div>
        </div>
    </section>
@endsection

@section('js_extend')
    <script>
        @if (isset($user))
            $('#user_form').submit(function(e) {
                @include('includes.alerts.confirmation_update')
            });
        @endif

        $('#delete_form').submit(function(e) {
            @include('includes.alerts.confirmation_delete')
        });

        $('#deleteButton').click(function(e) {
            $('#delete_form').submit();
        });

    </script>

@endsection
