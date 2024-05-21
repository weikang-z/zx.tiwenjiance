<?php
$cdnurl = function_exists('config') ? config('view_replace_str.__CDN__') : '';
$publicurl = function_exists('config') ? (config('view_replace_str.__PUBLIC__') ?: '/') : '/';
$debug = true;



?>

<?= $message ?>
    