<?php

namespace app\helpers;

use Yii;

/**
 * Помощник слайдера
 *
 * Class SliderHelper
 * @package app\helpers
 */
class SliderHelper
{
    /**
     * Генерирует zip-архив слайдера
     *
     * @param $fileId
     * @param $assetBundles
     * @param $viewIndex
     * @return string
     */
    public static function generateArchive($fileId, $assetBundles, $viewIndex)
    {
        // путь куда будет сохранен zip-архив
        $savePath = Yii::getAlias('@webroot') . '/downloads/' . $fileId;
        $sliderPath = $savePath . '/slider.zip';

        // если zip-архив уже сгенерирован,
        // то возвращаем путь на него
        if (file_exists($sliderPath)) {
            return $sliderPath;
        }

        yii\helpers\FileHelper::createDirectory($savePath);

        // получаем ссылки на JS/CSS-файлы, для того чтобы потом положить эти файлы в архив
        $assets = array_map(function ($assetBundle) {
            $files = [];

            if ($assetBundle->baseUrl) {
                foreach (['css', 'js'] as $type) {
                    if ($type) {
                        foreach ($assetBundle->$type as $item) {
                            $files[] = $assetBundle->baseUrl . '/' . $item;
                        }
                    }
                }
                return [
                    'baseUrl' => $assetBundle->baseUrl,
                    'files' => $files
                ];
            }
        }, $assetBundles);

        // копируем JS/CSS-файлы которые будут добавлены в zip-архив
        foreach ($assets as $asset) {
            if (!$asset['baseUrl']) {
                continue;
            }

            foreach ($asset['files'] as $file) {
                yii\helpers\FileHelper::createDirectory(dirname($savePath . '/static/' . $file));
                copy(Yii::getAlias('@webroot') . $file, $savePath . '/static/' . $file);
            }
        }

        // путь куда будут сохранены изображения
        $savePathImages = $savePath . '/static/images/';
        yii\helpers\FileHelper::createDirectory($savePathImages);

        // копируем изображения которые будут добавлены в zip-архив
        \app\helpers\FileHelper::recurse_copy(Yii::getAlias('@webroot') . '/images/' . $fileId, $savePathImages . $fileId);

        // рендеринг файла index.html (страница слайдера)
        $view = $viewIndex;
        $view = str_replace('/assets', 'assets', $view);
        $view = str_replace('/images', 'images', $view);
        file_put_contents($savePath . '/static/index.html', $view);

        // генерируем zip-архив
        \app\helpers\FileHelper::zip($savePath . '/static', $savePath . '/slider.zip');

        // возвращаем путь к zip-архиву
        return $savePath . '/slider.zip';
    }
}