<?php

namespace Someline\Http\Controllers\Console;

use Someline\Http\Controllers\BaseController;

class AlbumController extends BaseController
{

    //专辑列表页
    public function getAlbumList()
    {
        return view('console.albums.list');
    }

    //新建专辑页
    public function getAlbumNew()
    {
        return view('console.albums.new');
    }

    //专辑分类
    public function getAlbumCategory()
    {
        return view('console.albums.category');
    }

    //专辑详情页
    public function getAlbumDetail($album_id)
    {
        //compact 创建一个包含变量名和它们的值的数组：
        return view('console.albums.detail', compact('album_id'));
    }

    //专辑编辑页
    public function getAlbumEdit($album_id)
    {
        return view('console.albums.edit', compact('album_id'));
    }

}