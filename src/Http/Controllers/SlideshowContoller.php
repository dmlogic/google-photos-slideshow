<?php

namespace DmLogic\GooglePhotoSlideshow\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use DmLogic\GooglePhotoSlideshow\Models\Album;
use DmLogic\GooglePhotoSlideshow\Models\Photo;
use DmLogic\GooglePhotoSlideshow\SlideshowImage;

class SlideshowContoller extends Controller
{
    public function random(): ?JsonResponse
    {
        return $this->respond(Photo::random());
    }

    public function single($photoId): ?JsonResponse
    {
        return $this->respond(Photo::single($photoId));
    }

    public function albumImage($albumId, $photoId = null): ?JsonResponse
    {
        return $this->respond(Album::image($albumId, $photoId), 'album');
    }

    private function respond(?SlideshowImage $image, $mode = 'photo'): ?JsonResponse
    {
        if (!$image) {
            return null;
        }
        return response()->json($image->toArray() + ['mode' => $mode]);
    }
}
