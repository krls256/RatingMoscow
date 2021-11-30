<?php

namespace app\Utilities;

class DomainUtils
{
    public static function isMainDomain() {
        $domain = $_SERVER['SERVER_NAME'];
        return  $domain === "rating-remont.moscow" || $domain === "rating-moscow.local";
    }

    public static function isSubDomain() {
        return !self::isMainDomain();
    }
}