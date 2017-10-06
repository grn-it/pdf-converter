<?php

namespace app\controllers\api;

use Yii;
use yii\rest\ActiveController;
use app\models\Image;
use yii\web\Response;

/**
 * REST контроллер
 *
 * Class ImageController
 * @package app\controllers\api
 */
class ImageController extends ActiveController
{
    public $modelClass = 'app\models\Image';

    /**
     * Возвращает список ссылок на изображения для конкретного слайдера
     *
     * @param $id
     * @return array|\yii\db\ActiveRecord[]
     */
    public function actionView($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        return Image::find()
            ->select(['CONCAT("' . Yii::getAlias('@site') . '", webpath) as webpath'])
            ->where([
                'file_id' => $id
            ])
            ->all();
    }

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['view']);

        return $actions;
    }
}