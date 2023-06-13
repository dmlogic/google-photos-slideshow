<?php

namespace DmLogic\GooglePhotoSlideshow;

use Illuminate\Contracts\Support\Arrayable;
use DmLogic\GooglePhotoSlideshow\Models\Photo;

class SlideshowImage implements Arrayable
{
    private Photo $model;

    public function __construct(Photo $model)
    {
        $this->model = $model;
    }

    public function toArray()
    {
        return [
            'album_title' => $this->model->title,
            'date_taken' => $this->dateTaken(),
            'album_id' => $this->model->album_id,
            'image_id' => $this->model->id,
            'url' => $this->url(),
            'next_image' => $this->model->next_image,
        ];
    }

    private function url(): string
    {
        return '/photos/'.$this->model->google_id.'.jpg';
    }

    private function dateTaken(): string
    {
        return $this->model->created_at->format('l, jS F Y');
    }
}
