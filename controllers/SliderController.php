<?php

namespace app\controllers;

use app\models\File;
use Yii;
use yii\web\Controller;
use app\models\Image;
use app\assets\AppAsset;
use yii\bootstrap\BootstrapPluginAsset;
use app\helpers\SliderHelper;

/**
 * Контроллер слайдера (просмотр страниц, скачивание архива)
 *
 * Class SliderController
 * @package app\controllers
 */
class SliderController extends Controller
{
    /**
     * Проверяем, что срок "жизни" (30 минут) слайдера не истёк
     *
     * @param \yii\base\Action $action
     * @return bool
     */
    public function beforeAction($action)
    {
        $fileId = Yii::$app->getRequest()->get('fileId');

        if ((new File())->isExpired($fileId)) {
            echo $this->render('expired');

            return false;
        }

        return parent::beforeAction($action);
    }

    /**
     * Страница слайдера. Показывает сконвертированные изображения
     *
     * @param $fileId
     * @param bool $downloadButton
     * @return string
     */
    public function actionIndex($fileId, $downloadButton = true)
    {
        return $this->render('slider', [
            'images' => Image::getAll($fileId),
            'fileId' => $fileId,
            'downloadButton' => $downloadButton
        ]);
    }

    /**
     * Скачивает локальную версию слайдера (zip-архив)
     *
     * @param $fileId
     * @return $this
     */
    public function actionDownload($fileId)
    {
        // регистрируем Asset'ы для того, чтобы можно было получить ссылки на JS/CSS-файлы
        AppAsset::register($this->getView());
        BootstrapPluginAsset::register($this->getView());

        // генерируем zip-архив
        $zipPath = SliderHelper::generateArchive($fileId, $this->getView()->assetBundles, $this->actionIndex($fileId, false));

        return Yii::$app->getResponse()->sendFile($zipPath);
    }




}