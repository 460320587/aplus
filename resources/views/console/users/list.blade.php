@extends('console.layout.frame')

@section('content')

    <div class="bg-light lter b-b wrapper-md">
        <a href="{{ url('console/users/new') }}" class="btn btn-sm btn-info pull-right"><i class="fa fa-plus"></i> 添加用户</a>
        <h1 class="m-n font-thin h3">用户列表</h1>
    </div>
    <div class="wrapper-md">
        <sl-user-list></sl-user-list>
    </div>

@endsection