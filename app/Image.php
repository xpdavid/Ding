<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use IImage;

class Image extends Model
{
    public $saveTo = 'images';

    /**
     * define the fillable field
     *
     * @var array
     */
    protected $fillable = [
        'reference_id',
        'path',
        'width',
        'height'
    ];

    protected $table = 'images';

    /**
     *
     *
     * @param $reference_id
     * @param $width
     * @param $height
     * @return null
     */
    public static function findSimilarOrCreate($reference_id, $width, $height) {
        $images = Image::where('reference_id', $reference_id)->get();
        if ($images->count() == 0) {
            return null;
        } else {
            $original_image = Image::findOrFail($reference_id);
            // try to find similar image size
            $candidates = $images->filter(function($image) use ($width,$height) {
                return $image->isSimilar($width, $height);
            });

            if ($candidates->count() > 0) {
                // we have find similar image size
                $image = $candidates->first();
                $img = IImage::make(base_path($image->path));
                return $img;
            } else {
                // we don't have desired image size, let use create one and store it.
                $img = IImage::make(base_path($original_image->path))->fit($width, $height);
                // get path and name
                $path_name = explode('.', $original_image->path)[0];
                $extension = explode('.', $original_image->path)[1];
                // generate file name
                $file_name = $path_name . '_' . $width . 'x' . $height . '.' . $extension;
                $img->save(base_path($file_name));
                // store it in the database
                $newImg = Image::create([
                    'reference_id' => $reference_id,
                    'path' => $file_name,
                    'width' => $width,
                    'height' => $height
                ]);
                $newImg->save();
                return $img;
            }

        }
    }

    public function isSimilar($width, $height) {
        $width_diff = abs($this->width - $width);
        $width_diff_ratio = $width_diff / $this->width;

        $height_diff = abs($this->height - $height);
        $height_diff_ratio = $height_diff / $this->height;

        return ($width_diff_ratio < 0.3) && ($height_diff_ratio < 0.3);
    }
}
