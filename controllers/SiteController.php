<?php

namespace app\controllers;

use app\models\UploadForm;
use Yii;
use yii\web\Controller;
use yii\web\UploadedFile;

/**
 * Контроллер главной страницы (формы загрузки)
 *
 * Class SiteController
 * @package app\controllers
 */
class SiteController extends Controller
{
    /**
     * Главная страница. Форма загрузки.
     *
     * @return string|\yii\web\Response
     */
    public function actionIndex()
    {
        $model = new UploadForm();

        // ожидаем получить PDF-файл
        if (Yii::$app->request->isPost) {
            $model->file = UploadedFile::getInstance($model, 'file');

            $response = [
                'success' => false
            ];

            try {
                // пытаемся сохранить файл на сервер
                // перед сохранением происходит валидация
                $fileId = $model->upload();

                $response['success'] = true;
                $response['fileId'] = $fileId;

            } catch (\yii\base\Exception $exception) {
                $response['message'] = $exception->getMessage();
            }

            return $this->asJson($response);
        }

        return $this->render('form', [
            'model' => $model
        ]);
    }
}
