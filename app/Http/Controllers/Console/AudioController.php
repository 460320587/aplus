<?php

namespace Someline\Http\Controllers\Console;

use Illuminate\Http\Request;
use Someline\Http\Controllers\BaseController;
use Someline\Models\Foundation\Audio;

class AudioController extends BaseController
{

    //专辑列表页
    public function getAudioList(Request $request)
    {
        $album_id = $request->get('album_id');
        return view('console.audios.list', compact('album_id'));
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
        $reviewingAudios = [];
        do {

            $audio = Audio::where('pool', Audio::POOL_REVIEW)
                ->where('status', Audio::STATUS_NOT_REVIEWED)
                ->whereNotIn('audio_id', $reviewingAudios)
                ->orderBy('failed_times', 'desc')
                ->orderBy('created_at', 'asc')
                ->first();
            if ($audio) {
                $audio_id = $audio->audio_id;
                if (!empty($audio_id)) {
                    $currentReviewing = \Cache::get('reviewing_audio.' . $audio_id);
                    if ($currentReviewing) {
                        $reviewingAudios[] = $audio_id;
                    } else {
                        return redirect('console/audios/' . $audio->audio_id . '/review');
                    }
                }
            } else {
                return redirect('console/audios/review_landing');
            }

        } while (true);
    }

    public function getAudioReviewLanding()
    {
        return view('console.audios.review_landing');
    }

    //审核声音页
    public function getAudioReview($audio_id)
    {
        $audio = Audio::find($audio_id);
        if (!$audio) {
            abort('404');
        }
        $current_user = auth_user();

        if ($audio->status != Audio::STATUS_NOT_REVIEWED && !$current_user->isRoleAdmin()) {
            abort('403');
        }

        $currentReviewing = \Cache::get('reviewing_audio.' . $audio_id);
        if ($currentReviewing != null && $currentReviewing != $this->getAuthUserId()) {
            abort('403');
        }
        \Cache::put('reviewing_audio.' . $audio_id, $this->getAuthUserId(), 60);
        return view('console.audios.review', compact('audio_id'));
    }

    //专辑编辑页
    public function getAudioEdit($audio_id)
    {
        return view('console.audios.edit', compact('audio_id'));
    }

}