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
            return response('Failed to save: ' . $e->getMessage(), 422);
        }

        if (!$somelineFile) {
            return response('Failed to save uploaded file.', 422);
        }

        $data = [
            'client_original_name' => $file->getClientOriginalName(),
        ];

        if('mp3' == $file->getClientOriginalExtension()) {
            $path = $somelineFile->getFileStoragePath();
            $mp3file = new MP3File($path);
            $data['duration'] = $mp3file->getDurationInFormatTime();
        }

        return response([
            'data' => array_merge($somelineFile->toSimpleArray(), $data)
        ]);
    }

}