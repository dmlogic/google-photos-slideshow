<?php

namespace DmLogic\GooglePhotoSlideshow\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use DmLogic\GooglePhotoSlideshow\SlideshowImage;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Album extends Model
{
    protected $table = 'albums';
    protected $guarded = [];

    public static function image($albumId, $startFrom): ?SlideshowImage
    {
        $startFrom = $startFrom ?? 1;
        $raw = DB::select('SELECT a.id as album_id,
                        p.id,
                        p.google_id,
                        a.title,
                        p.created_at
                    FROM photos p
                    JOIN albums a ON a.id = p.album_id
                    WHERE a.id = ?
                        AND p.id >= ?
                    ORDER BY p.id asc
                    LIMIT 2', [$albumId, $startFrom]);

        if (!$raw) {
            return null;
        }
        $photo = new Photo((array) $raw[0]);
        if(isset($raw[1])) {
            $photo->next_image = $raw[1]->id;
        }

        return new SlideshowImage($photo);
    }

    public function photos(): HasMany
    {
        return $this->hasMany(Photo::class);
    }
}
