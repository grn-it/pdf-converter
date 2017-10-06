<style>
.carousel-control {
    font-size: 100px;
    bottom: inherit;
}
.download-button-container {
    padding: 20px;
    text-align: center;
}
.carousel-inner > .item > img {
    display: inline-block;
}
</style>

<div class="download-button-container">
    <?php
    if ($downloadButton) {
        echo \yii\helpers\Html::a('Скачать', ['/slider/download/' . $fileId], ['class'=>'btn btn-lg btn-success']);
    }
    ?>
</div>

<div style="text-align: center;">
    <?= yii\bootstrap\Carousel::widget([
        'items' => $images,
        'options' => [
            'data-interval' => 'false'
        ]
    ]); ?>
</div>
