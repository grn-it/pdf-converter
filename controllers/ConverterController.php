<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\converters\PdfConverter;
use app\sse\ProgressEventHandler;

/**
 * Контроллер PDF-конвертера
 *
 * Class ConverterController
 * @package app\controllers
 */
class ConverterController extends Controller
{
    /**
     * Конвертирует PDF-файл в JPG-изображения
     *
     * @param $fileId
     */
    public function actionConvert($fileId)
    {
        (new PdfConverter())->convert($fileId);
    }

    /**
     * Возвращает прогресс конвертации в процентах
     */
    public function actionProgress()
    {
        $sse = Yii::$app->sse;
        $sse->addEventListener('progress', new ProgressEventHandler());
        $sse->start();
    }
}