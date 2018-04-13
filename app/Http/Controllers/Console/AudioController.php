<?php

namespace Someline\Http\Controllers\Console;

use Someline\Http\Controllers\BaseController;
use Someline\Models\Foundation\Audio;

class AudioController extends BaseController
{

    //专辑列表页
    public function getAudioList()
    {
        return view('console.audios.list');
    }

    //新建专辑页
    public function getAudioNew()
    {
        return view('console.audios.new');
    }

    //专辑分类
    public function getAudioCategory()
    {
        return view('console.audios.category');
    }

    //专辑详情页
    public function getAudioDetail($audio_id)
    {
        //compact 创建一个包含变量名和它们的值的数组：
        return view('console.audios.detail', compact('audio_id'));
    }

    public function getAudioRandomReview()
    {
        $user = $this->getAuthUser();
        $audio = Audio::where('pool', Audio::POOL_REVIEW)
            ->where('status', Audio::STATUS_NOT_REVIEWED)
            ->orderBy('failed_times', 'desc')
            ->orderBy('created_at', 'asc')
            ->first();
        if ($audio) {
            return redirect('console/audios/' . $audio->audio_id . '/review');
        } else {
            return redirect('console/audios/review_landing');
        }
    }

    public function getAudioReviewLanding()
    {
        return view('console.audios.review_landing');
    }

    //审核声音页
    public function getAudioReview($audio_id)
    {
        return view('console.audios.review', compact('audio_id'));
    }

    //专辑编辑页
    public function getAudioEdit($audio_id)
    {
        return view('console.audios.edit', compact('audio_id'));
    }

}