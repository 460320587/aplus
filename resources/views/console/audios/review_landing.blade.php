@extends('console.layout.frame')

@section('content')

    <div class="bg-light lter b-b wrapper-md">
        <h1 class="m-n font-thin h3">审核</h1>
    </div>
    <div class="wrapper-md">
        {{--<h3>当前还未进行审核 或 没有声音需要审核了。</h3>--}}
        <h3>当前审核库内剩余{{$audio_count}}条声音需要审核</h3>
    </div>

@endsection