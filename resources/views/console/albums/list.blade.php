@extends('console.layout.frame')

@section('content')

    <div class="bg-light lter b-b wrapper-md">
        @role('admin')
        <a href="{{ url('console/albums/new') }}" class="btn btn-sm btn-info pull-right"><i class="fa fa-plus"></i> 添加专辑</a>
        @endrole
        <h1 class="m-n font-thin h3">专辑列表</h1>
    </div>
    <div class="wrapper-md">
        <sl-album-list></sl-album-list>
    </div>

@endsection