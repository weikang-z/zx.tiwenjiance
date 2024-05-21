<?php
namespace addons\shorturl\library;

use addons\shorturl\library\hashids\Hashids;

class Hash {

    var $hashids;

    function __construct($salt, $length, $alphabet) {
        $this->hashids = new Hashids($salt, $length, $alphabet);
    }

    function encode($id) {
        return $this->hashids->encode($id);
    }

    function decode($hash) {
        $id = $this->hashids->decode($hash);
        return $id ? $id[0] : false;
    }
}