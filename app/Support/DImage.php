<?php
function DImage($reference_id, $width = null, $height = null) {
    if ($width != null && $height != null) {
        return '/image/' . $reference_id . '/' . $width . '/' . $height;
    } else if ($width == null && $height == null) {
        return '/image/' . $reference_id;
    }
}