@extends('console.layout.frame')

@section('content')

    <div class="bg-light lter b-b wrapper-md">
        <a href="{{ url('console/audios') }}" class="btn btn-sm btn-default pull-right">返回</a>
        <h1 class="m-n font-thin h3">查看声音</h1>
    </div>
    <div class="wrapper-md">
        <sl-audio-detail item-id="{{ $audio_id }}"></sl-audio-detail>
    </div>

@endsection