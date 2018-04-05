@extends('console.layout.frame')

@section('content')

    <div class="bg-light lter b-b wrapper-md">
        <a href="{{ url('console/users') }}" class="btn btn-sm btn-default pull-right">返回</a>
        <h1 class="m-n font-thin h3">查看用户</h1>
    </div>
    <div class="wrapper-md">
        <sl-user-detail item-id="{{ $user_id }}"></sl-user-detail>
    </div>

@endsection