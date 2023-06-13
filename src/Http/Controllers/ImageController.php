<?php

namespace DmLogic\GooglePhotoSlideshow\Http\Controllers;

use Illuminate\Routing\Controller;
use Intervention\Image\Facades\Image;

class ImageController extends Controller
{
    public function __invoke($url)
    {
        $path = config('photos.storage_path') . '/' . $url;
        if(!file_exists($path)) {
            abort(404);
        }
        return $this->generateImage($path)->response();
    }

    private function generateImage($path)
    {
        $image = Image::make($path);
        $image->resize(config('photos.width'), config('photos.height'), function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });
        return $image;
    }
}
