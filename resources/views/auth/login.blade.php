@extends('layouts.gui')

@section('css_extend')
    <style>

        .loginForm {
          width: 100%;
          max-width: 330px;
          padding: 15px;
          margin: auto;
         margin-top: 50px;
        }

        .loginForm .checkbox {
          font-weight: 400;
        }

        .loginForm .form-control {
          position: relative;
          box-sizing: border-box;
          height: auto;
          padding: 10px;
          font-size: 16px;
        }

        .loginForm .form-control:focus {
          z-index: 2;
        }

        .loginForm input[type="text"] {
          margin-bottom: -1px;
          border-bottom-right-radius: 0;
          border-bottom-left-radius: 0;
        }

        .loginForm input[type="password"] {
          margin-bottom: 10px;
          border-top-left-radius: 0;
          border-top-right-radius: 0;
        }
    </style>

@endsection

@section('content')

    <div class="loginForm">
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <input type="text" name="username" class="form-control  @error('username') is-invalid @enderror" value="{{ old('username') }}" required autocomplete="username" autofocus placeholder="Username">
            <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="Password" required autocomplete="current-password">

            <div class="checkbox mb-3">
              <label>
                  <input checked type="checkbox" name="remember"> Emlékezz rám
              </label>
            </div>
            <button class="w-100 btn btn-lg btn-primary" type="submit">Bejelentkezés</button>
            @if ($errors->any())
                <div class="alert alert-danger mt-2" style="margin: 0 auto;">
                    @foreach ($errors->all() as $error)
                        {{ $error }}
                    @endforeach
                </div>
            @endif


        </form>
    </div>

@endsection
