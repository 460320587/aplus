
@extends('console.layout.frame')

@section('content')

    <div class="bg-light lter b-b wrapper-md">
        <h1 class="m-n font-thin h3">Someline UI</h1>
    </div>
    <div class="wrapper-md">
        <sl-someline-form-example></sl-someline-form-example>
    </div>

@endsection

@push('stylesheets')
<link rel="stylesheet" href="{{ url('bower_components/wangEditor/dist/css/wangEditor.min.css') }}" type="text/css"/>
@endpush

@push('pre_scripts')
<script src="{{ url('bower_components/wangEditor/dist/js/wangEditor.min.js') }}"></script>
@endpush