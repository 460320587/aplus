@extends('console.layout.frame')

@section('content')

    <div class="bg-light lter b-b wrapper-md">
        <a href="{{ url('/console/audios/review_landing') }}" class="btn btn-sm btn-default pull-right">返回</a>
        <h1 class="m-n font-thin h3">审核声音(目前还有{{$audio_count}}条声音需要审核)</h1>
    </div>
    <div class="wrapper-md">
        <sl-audio-review item-id="{{ $audio_id }}"></sl-audio-review>
    </div>

@endsection