<?php namespace Someline\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Someline\Component\File\SomelineFileService;
use Someline\Models\File\SomelineFile;
use Someline\MP3File;

class FileController extends BaseController
{

    public function postFile(Request $request)
    {
        $somelineFileService = new SomelineFileService();
        $file = $request->file('file');

        $somelineFile = null;
        try {
            /** @var SomelineFile $somelineFile */
            $somelineFile = $somelineFileService->handleUploadedFile($file);
        } catch (Exception $e) {
            return response($e->getMessage(), 422);
        }

        if (!$somelineFile) {
            return response('Failed to save uploaded file.', 422);
        }

        $clientOriginalName = $file->getClientOriginalName();
        $data = [
            'client_original_name' => $clientOriginalName,
            'display_client_original_name' => str_replace_last('.mp3', '', strtolower($clientOriginalName)),
        ];

        if ('mp3' == strtolower($file->getClientOriginalExtension())) {
            $path = $somelineFile->getFileStoragePath();
            $mp3file = new MP3File($path);
            $info = $mp3file->getInfo();
            $data['bitrate'] = !empty($info['Bitrate']) ? $info['Bitrate'] : null;
            $data['duration'] = $mp3file->getDuration();
            $data['duration_text'] = MP3File::formatTime($data['duration']);
        }

        return response([
            'data' => array_merge($somelineFile->toSimpleArray(), $data)
        ]);
    }

}