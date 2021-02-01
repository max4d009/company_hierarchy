<?php

namespace App\Enum;


/**
 * Class FileContentTypeEnum
 * @package App\Enum
 */
class FileContentTypeEnum extends AbstractEnum
{

    const UPLOADS_FULL_LIST_INT = 0;
    const UPLOADS_GROUP_BY_USER_INT = 1;

    const UPLOADS_FULL_LIST_NAME = 'Полный список';
    const UPLOADS_GROUP_BY_USER = 'По пользователям';

    public static function getCodeList()
    {
        return [
            self::UPLOADS_FULL_LIST_INT => 0,
            self::UPLOADS_GROUP_BY_USER_INT => 1
        ];
    }


    public static function getNameFileList()
    {
        return [
            self::UPLOADS_FULL_LIST_INT => 'Полный список выгрузок',
            self::UPLOADS_GROUP_BY_USER_INT => 'По пользователям'
        ];
    }

    public static function getCode($const)
    {
        return self::getCodeList()[$const];
    }



    public static function getNameFileBy($const)
    {
        return self::getNameFileList()[$const];
    }
}
