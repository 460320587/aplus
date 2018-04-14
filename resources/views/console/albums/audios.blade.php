@extends('console.layout.frame')

@section('content')

    <div class="bg-light lter b-b wrapper-md">
        <a href="{{ url('console/albums') }}" class="btn btn-sm btn-default pull-right">返回</a>
        @if($canUpload)
            <a href="{{ url('console/albums/'.$album_id.'/audios/upload') }}"
               class="btn btn-sm btn-success pull-right m-r"><i class="fa fa-cloud-upload"></i> &nbsp;上传声音</a>
        @endif
        <h1 class="m-n font-thin h3">专辑声音</h1>
    </div>
    <div class="wrapper-md">
        <sl-audio-editor item-id="{{ $album_id }}"></sl-audio-editor>
    </div>

@endsection

@push('stylesheets')
    <link rel="stylesheet" href="{{ url('bower_components/wangEditor/dist/css/wangEditor.min.css') }}" type="text/css"/>
@endpush

@push('pre_scripts')
    <script src="{{ url('bower_components/wangEditor/dist/js/wangEditor.min.js') }}"></script>
@endpush