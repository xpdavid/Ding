<?php
function truncateText($text, $length) {
    if(strlen($text) > $length) {
        $text = preg_replace("/^(.{1,$length})(\s.*|$)/s", '\\1...', $text);
    }
    return $text;
}