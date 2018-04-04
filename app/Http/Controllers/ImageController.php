<?php namespace Someline\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Someline\Http\Controllers\BaseController;
use Someline\Image\Controllers\SomelineImageController;
use Someline\Models\Image\SomelineImage;
use Someline\Image\SomelineImageService;

class ImageController extends BaseController
{

    public function postImage(Request $request)
    {
        $somelineImageService = new SomelineImageService();
        $file = $request->file('image');

        $somelineImage = null;
        try {
            /** @var SomelineImage $somelineImage */
            $somelineImage = $somelineImageService->handleUploadedFile($file);
        } catch (Exception $e) {
            return response('Failed to save: ' . $e->getMessage(), 422);
        }

        if (!$somelineImage) {
            return response('Failed to save uploaded image.', 422);
        }

        return response([
            'data' => $somelineImage->toSimpleArray()
        ]);
    }

    public function postWangEditorImage(Request $request)
    {
        $somelineImageService = new SomelineImageService();
        $file = $request->file('image');

        $err_msg = '{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to store image."}, "id" : "id"}';
        if (empty($file)) {
            return $err_msg;
        }

        $somelineImage = null;
        try {
            /** @var SomelineImage $somelineImage */
            $somelineImage = $somelineImageService->handleUploadedFile($file);
        } catch (Exception $e) {
            return $err_msg;
//            return 'Failed to save: ' . $e->getMessage();
        }

        if (!$somelineImage) {
            return $err_msg;
//            return 'Failed to save uploaded image.';
        }

        $somelineImageId = $somelineImage->getSomelineImageId();
        return $somelineImage->getImageUrl();
    }

    public function showOriginalImage($image_name)
    {
        return SomelineImageController::showImage('original', $image_name);
    }

    public function showTypeImage($type, $image_name)
    {
        return SomelineImageController::showImage($type, $image_name);
    }

}