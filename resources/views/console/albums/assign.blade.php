@extends('console.layout.frame')

@section('content')

    <div class="bg-light lter b-b wrapper-md">
        <a href="{{ url('console/albums') }}" class="btn btn-sm btn-default pull-right">返回</a>
        <h1 class="m-n font-thin h3">分配专辑</h1>
    </div>
    <div class="wrapper-md">
        <sl-album-assign></sl-album-assign>
    </div>

@endsection