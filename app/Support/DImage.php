<?php
function DImage($reference_id, $width, $height) {
    return '/image/' . $reference_id . '/' . $width . '/' . $height;
}