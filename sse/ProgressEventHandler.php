<?php

namespace app\sse;

use odannyc\Yii2SSE\SSEBase;
use app\converters\PdfConverter;

/**
 * Возвращает на front-end информацию о конвертировании
 *
 * Class ProgressEventHandler
 * @package app\sse
 */
class ProgressEventHandler extends SSEBase
{
    public function check()
    {
        return true;
    }

    public function update()
    {
        $pdfConverter = new PdfConverter();

        return json_encode([
            'percent' => $pdfConverter->progressPercent(),
            'converted' => $pdfConverter->isConverted()
        ]);
    }
}