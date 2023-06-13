<?php

namespace DmLogic\GooglePhotoSlideshow\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use DmLogic\GooglePhotoSlideshow\SlideshowImage;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Photo extends Model
{
    protected $table = 'photos';

    protected $guarded = [];

    public static function random(): ?SlideshowImage
    {
        $raw = DB::select('SELECT a.id as album_id,
                        p.id,
                        p.google_id,
                        a.title,
                        p.created_at
                    FROM photos p
                    JOIN albums a ON a.id = p.album_id
                    ORDER BY RANDOM()
                    LIMIT 1');

        if (!$raw) {
            return null;
        }
        return new SlideshowImage(new static ((array) $raw[0]));
    }

    public static function single($uid)
    {
        $raw = DB::select('SELECT a.id as album_id,
                        p.id,
                        p.google_id,
                        a.title,
                        p.created_at
                    FROM photos p
                    JOIN albums a ON a.id = p.album_id
                    WHERE p.id = ?', [$uid]);
        if (!$raw) {
            return null;
        }
        return new SlideshowImage(new static ((array) $raw[0]));
    }

    public function album(): BelongsTo
    {
        return $this->belongsTo(Album::class);
    }
}
