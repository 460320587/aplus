@extends('console.layout.frame')

@section('content')

    <div class="bg-light lter b-b wrapper-md">
        {{--<a href="{{ url('console/audios/new') }}" class="btn btn-sm btn-info pull-right"><i class="fa fa-plus"></i> 添加声音</a>--}}
        <h1 class="m-n font-thin h3">声音列表</h1>
    </div>
    <div class="wrapper-md">
        <sl-audio-list
                @if($album_id) album-id="{{ $album_id }}" @endif
        ></sl-audio-list>
    </div>

@endsection