<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;
use app\models\File;
use yii\helpers\FileHelper;
use app\validators\PdfValidator;

/**
 * Модель формы загрузки файла
 *
 * Class UploadForm
 * @package app\models
 */
class UploadForm extends Model
{
    /** @var UploadedFile */
    public $file;

    public function rules()
    {
        return [
            ['file', 'file', 'extensions' => 'pdf']
        ];
    }

    /**
     * Сохранение файла на серер
     *
     * @return bool|mixed
     * @throws \yii\base\Exception
     */
    public function upload()
    {
        // если PDF-файл валидный (первый этап валидации)
        if ($this->validate()) {
            // сохраняем в базу данных для получения идентификатора этого файла
            $file = new File();
            $file->save();
            $fileId = $file->getPrimaryKey();

            $filePath = Yii::getAlias('@webroot') . '/uploads/pdf/' . $fileId . '.pdf';

            // сохраняем на сервере
            $this->file->saveAs($filePath);

            // валидация (ориентация, количество страниц, размер файла)
            $pdfValidator = new PdfValidator();

            if (!$pdfValidator->validate($filePath, $error)) {
                unlink($filePath);

                throw new \yii\base\Exception($error);
            }

            $file->path = $filePath;
            $file->pages = $pdfValidator->pages;
            $file->save();

            return $fileId;
        }

        return false;
    }
}