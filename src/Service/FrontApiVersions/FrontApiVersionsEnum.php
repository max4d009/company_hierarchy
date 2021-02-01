<?php

namespace App\Service\FrontApiVersions;


class FrontApiVersionsEnum
{

    const V1 = 'v1';
    const V2 = 'v2';


    /**
     * @return array
     */
    public static function getVersionList()
    {
        return [
            self::V1,
            self::V2
        ];
    }
}

