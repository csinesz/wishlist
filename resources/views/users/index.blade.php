@extends('layouts.gui')

@section('content')

<section class="container">
    <div class="col-12">
        <a href="{{route('users.create')}}">
        <button class="btn btn-sm btn-outline-success">@lang('gui.user_add')</button>
        </a>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">@lang('gui.user_username')</th>
                    <th scope="col">@lang('gui.user_name')</th>
                    <th scope="col">@lang('gui.user_last_modify')</th>
                    <th scope="col"></th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr @if(auth()->id() == $user->i_user) style="font-weight: bold" @endif>
                        <td>{{$user->i_user}}</td>
                        <td><a style="color: black; text-decoration: none"  href="{{route('users.edit', $user->i_user)}}">{{$user->username}}</a></td>
                        <td>{{$user->name}}</td>
                        <td style="white-space:nowrap">
                            {{$user->updated_at}}
                        </td>
                        <td>
                            @if($user->hasRole('admin'))
                                <i alt="admin" class="fas fa-shield-alt"></i>
                            @else
                                <i alt="user" class="fas fa-user"></i>
                            @endif
                        </td>
                        <td>
                            @if($user->active)
                                <i class="fas fa-check" style="color: green"></i>
                            @else
                                <i class="fas fa-lock" style="color: red"></i>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</section>

@endsection
