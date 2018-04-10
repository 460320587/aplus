@extends('angulr.auth.auth_base')

@section('content')
    <div class="wrapper text-center">
        <strong>账号登陆</strong>
    </div>
    <form name="form" class="form-horizontal form-validation" role="form" method="POST" action="{{ url('/login') }}">
        {!! csrf_field() !!}
        @if (!empty($errors))
            <div class="text-danger wrapper text-center">
                {{$errors->first()}}
            </div>
        @endif
        <div class="list-group list-group-sm">
            <div class="list-group-item">
                <input type="text" placeholder="账号" class="form-control no-border" name="username"
                       value="{{ old('username') }}" required autofocus>
            </div>
            <div class="list-group-item">
                <input type="hidden" name="grant_type" value="password"/>
                <input type="password" placeholder="密码" class="form-control no-border" name="password"
                       required>
            </div>
        </div>
        <button type="submit" class="btn btn-lg btn-primary btn-block">
            登录
        </button>
        {{--<div class="text-center m-t m-b"><a href="{{ url('/password/reset') }}">Forgot password?</a></div>--}}

        <div class="wrapper"></div>
        {{--<div class="line line-dashed"></div>--}}
        {{--<p class="text-center">--}}
        {{--<small>Do not have an account?</small>--}}
        {{--</p>--}}
        {{--<a href="{{ url('/register') }}" class="btn btn-lg btn-default btn-block">Create an username</a>--}}
    </form>
@endsection