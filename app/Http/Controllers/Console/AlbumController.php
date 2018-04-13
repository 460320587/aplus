<?php

namespace Someline\Http\Controllers\Console;

use Someline\Http\Controllers\BaseController;
use Someline\Models\Foundation\Album;

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

    //分配专辑页
    public function getAlbumAssign()
    {
        return view('console.albums.assign');
    }

    public function getAlbumSingleAssign($album_id)
    {
        return view('console.albums.assign', compact('album_id'));
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

    public function getAlbumAudios($album_id)
    {
        /** @var Album $album */
        $album = Album::findOrFail($album_id);
        $canUpload = $album->isStatus(Album::STATUS_PUBLISHING);
        if ($canUpload) {
            $user = $this->getAuthUser();
            $publisher = $user->hasRole('publisher');
            if ($publisher) {
                $canUpload = $album->related_user_id == $user->getUserId();
            }
        }
        return view('console.albums.audios', compact('canUpload', 'album_id'));
    }

    public function getAlbumAudiosUpload($album_id)
    {
        return view('console.albums.audios_upload', compact('album_id'));
    }


}