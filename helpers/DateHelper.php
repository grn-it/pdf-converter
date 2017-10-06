<?php

namespace app\helpers;

class DateHelper
{
    const EXPIRED = '+30 minutes';
    const DATE_FORMAT = 'Y-m-d H:i:s';

    /**
     * Возвращает дату истечения срока "жизни" слайдера
     *
     * @return false|string
     */
    public static function getExpiredDate()
    {
        return date(self::DATE_FORMAT, strtotime(self::EXPIRED));
    }

    /**
     * Возвращает текущую дату
     *
     * @return false|string
     */
    public static function getNow()
    {
        return date(self::DATE_FORMAT);
    }
}