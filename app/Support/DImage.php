<?php
/**
 * Get image
 *
 * @param $reference_id
 * @param null $width
 * @param null $height
 * @return string
 */
function DImage($reference_id, $width = null, $height = null) {
    if ($width != null && $height != null) {
        return '/image/' . $reference_id . '/' . $width . '/' . $height;
    } else if ($width == null && $height == null) {
        return '/image/' . $reference_id;
    }
}


/**
 * Get auto get image
 *
 * @param $query
 * @param $width
 * @param $height
 * @return string
 */
function autoImage($query, $width, $height) {
    return '/image/auto/' . urlencode($query) . '/' . $width . '/' . $height;
}