<?php

namespace app\validators;

use yii\validators\Validator;

class PdfValidator extends Validator
{
    const MAX_PAGES = 20;
    const MAX_FILESIZE = 50; // МБ

    public $pages;

    /**
     * Ориентация должна быть портретная
     *
     * @param $filePath
     * @return bool
     */
    public function isPortraitOrientation($filePath)
    {
        $result = exec('identify -format "%wx%h" ' . $filePath . '[0]');

        list($width, $height) = explode('x', $result);

        // если ширина меньше высоты, значит ориентация портретная
        if ($width < $height) {
            return true;
        }
    }

    /**
     * Количество страниц должно быть меньше 20
     *
     * @param $filePath
     * @return bool
     */
    public function isCountOfPageLowerMax($filePath)
    {
        exec('cpdf -info ' . $filePath, $output);

        $pages = null;

        foreach ($output as $line) {
            if (preg_match('/Pages: (?P<pages>\d+)/', $line, $matches)) {
                $pages = (int)$matches['pages'];
            }
        }

        $this->pages = $pages;

        if ($pages && $pages <= self::MAX_PAGES) {
            return true;
        }
    }

    /**
     * Размер файла должен быть меньше 50 МБ
     *
     * @param $filePath
     * @return bool
     */
    public function isFileSizeLowerMax($filePath)
    {
        if (filesize($filePath) <= self::MAX_FILESIZE * 1024 * 1024) {
            return true;
        }
    }

    /**
     * Валидация
     *
     * @param mixed $value
     * @param null $error
     * @return bool
     */
    public function validate($value, &$error = null)
    {
        if (!$this->isPortraitOrientation($value)) {
            $error = 'Ориентация PDF-файла должны быть портретная';
            return false;
        }

        if (!$this->isCountOfPageLowerMax($value)) {
            $error = 'Количество страниц в PDF-файле должно быть меньше ' . self::MAX_PAGES;
            return false;
        }

        if (!$this->isFileSizeLowerMax($value)) {
            $error = 'Размер файла должен быть меньше ' . self::MAX_FILESIZE . ' Мегабайт';
            return false;
        }

        return true;
    }
}