@extends('console.layout.frame')

@section('content')

    <div class="bg-light lter b-b wrapper-md">
        <h1 class="m-n font-thin h3">专辑分类</h1>
    </div>
    <div class="wrapper-md">
        <sl-album-category
                category_type="{{ \Someline\Models\Category\SomelineCategory::TYPE_ALBUM }}"
        ></sl-album-category>
    </div>

@endsection