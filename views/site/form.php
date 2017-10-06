<style>
h1 {
    margin-bottom: 40px;
}
#form-container {
    padding-top: 200px;
    width: 600px;
    margin: auto;
}
#message {
    margin-top: 20px;
}
.progress {
    display: none;
}
.upload-button {
    float: right;
}
</style>

<div id="form-container">

<h1>Конвертер PDF в HTML слайдер</h1>

<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

echo yii\bootstrap\Button::widget([
    'label' => 'Выберите файл',
    'options' => ['class' => 'select-file btn-lg btn-success', 'style' => 'margin-right: 50px;'],
]);

$form = ActiveForm::begin([
    'options' => [
        'enctype' => 'multipart/form-data',
        'style' => 'display: inline-block;'
    ]
]);

echo $form->field($model, 'file')->fileInput([
        'style' => 'display: none',
        'accept' => '.pdf',
        'fieldConfig' => [
            'template' => "{beginWrapper}{input}{endWrapper}",
        ]
])->label(false);


?>

<?php ActiveForm::end(); ?>

<?= yii\bootstrap\Button::widget([
        'label' => 'Конвертировать',
        'options' => ['class' => 'btn-lg btn-primary upload-button', 'disabled' => 'disabled']
]); ?>

<div id="message"></div>

<div class="progress">
    <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
</div>
</div>