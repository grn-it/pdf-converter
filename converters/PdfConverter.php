<?php

namespace app\converters;

use Yii;
use app\models\File;
use yii\base\Model;
use yii\helpers\FileHelper;
use app\models\Image;
use app\helpers\DateHelper;

/**
 * Конвертирует PDF-файл в изображения
 *
 * Class PdfConverter
 * @package app\converters
 */
class PdfConverter extends Model
{
    const SESSION_PROGRESS_PERCENT = 'converting.progress.percent';
    const SESSION_PROGRESS_CONVERTED = 'converting.progress.converted';

    /**
     * @param $fileId
     */
    public function convert($fileId)
    {
        // в сессии будет храниться информация о процессе конвертации - проценты, флаг выполненной конвертации
        $session = Yii::$app->session;

        $session->set(self::SESSION_PROGRESS_PERCENT, 0);
        $session->set(self::SESSION_PROGRESS_CONVERTED, false);
        $session->close();

        $file = File::findOne($fileId);

        $imageSavePath = '/images/' . $fileId . '/';

        FileHelper::createDirectory(Yii::getAlias('@webroot') . $imageSavePath);

        for ($page = 1; $page <= $file->pages; $page++) {
            $imageSavePathFile  = Yii::getAlias('@webroot') . $imageSavePath . $page . '.jpg';
            $imageWebPath       = $imageSavePath . $page . '.jpg';

            // конвертируем из PDF-файла в изображения постранично
            exec('convert -density 100 ' . $file->path . '[' . ($page - 1) . '] ' . $imageSavePathFile);

            // сохранение изображения
            $image = new Image();
            $image->file_id = $fileId;
            $image->path = $imageSavePathFile;
            $image->webpath = $imageWebPath;
            $image->save();

            // процент выполнения
            $percent = 100 * $page / $file->pages;

            $session->set(self::SESSION_PROGRESS_PERCENT, $percent);
            $session->close();
        }

        $session->set(self::SESSION_PROGRESS_CONVERTED, true);
        $session->close();

        $file->expired_at = DateHelper::getExpiredDate();
        $file->save();
    }

    /**
     * Возвращает процент конвертации
     *
     * @return mixed
     */
    public function progressPercent()
    {
        return Yii::$app->session->get(self::SESSION_PROGRESS_PERCENT);
    }

    /**
     * PDF-файл сконвертирован?
     *
     * @return mixed
     */
    public function isConverted()
    {
        return Yii::$app->session->get(self::SESSION_PROGRESS_CONVERTED);
    }
}