<?php

namespace app\models;

use Yii;

/**
 * Модель изображения
 *
 * @property integer $id
 * @property integer $file_id
 * @property string $path
 * @property string $webpath
 *
 * @property File $file
 */
class Image extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'image';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['file_id'], 'integer'],
            [['path', 'webpath'], 'string', 'max' => 500],
            [['file_id'], 'exist', 'skipOnError' => true, 'targetClass' => File::className(), 'targetAttribute' => ['file_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'file_id' => 'File ID',
            'path' => 'Path',
            'webpath' => 'Webpath',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFile()
    {
        return $this->hasOne(File::className(), ['id' => 'file_id']);
    }

    /**
     *
     *
     * @param $fileId
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getAll($fileId)
    {
        $images = self::find()->select('webpath')->where([
            'file_id' => $fileId
        ])->asArray()->all();

        $images = array_column($images, 'webpath');

        $images = array_map(function ($image) {
            return '<img src="' . $image . '"/>';
        }, $images);

        return $images;
    }
}
