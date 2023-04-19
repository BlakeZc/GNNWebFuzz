<?php

namespace App {
    echo "123";
    require_once(__DIR__ . "/../vendor/autoload.php");
    use MyCLabs\Enum\Enum;
    class FileScore extends Enum {
        public const SCORES = [
            "1" => 1.1,
            "2" => 2.2,
        ];
    }
}