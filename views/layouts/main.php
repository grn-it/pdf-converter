<?php

/* @var $this \yii\web\View */

use app\assets\AppAsset;
use app\assets\UploadAsset;

if (Yii::$app->controller->id == 'site' && Yii::$app->controller->action->id == 'index') {
    UploadAsset::register($this);
}

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Конвертер PDF в HTML слайдер</title>
    <?php $this->head() ?>
</head>

<body>
<?php $this->beginBody() ?>
    <?= $content ?>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
