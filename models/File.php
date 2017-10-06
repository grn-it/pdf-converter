<?php

namespace app\models;

use app\helpers\DateHelper;

/**
 * Модель файла (слайдера)
 *
 * @property integer $id
 * @property string $path
 * @property integer $pages
 * @property string $expired_at
 */
class File extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'file';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pages'], 'integer'],
            [['expired_at'], 'safe'],
            [['path'], 'string', 'max' => 500],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'path' => 'Path',
            'pages' => 'Pages',
            'expired_at' => 'Expired At',
        ];
    }

    /**
     * Срок "жизни" слайдера истёк?
     *
     * @param $fileId
     * @return bool
     */
    public function isExpired($fileId)
    {
        if (self::find()->select('id')->where([
            'id' => $fileId,
        ])->andWhere([
            '>', 'expired_at', DateHelper::getNow()
        ])
            ->one()) {
            return false;
        }

        return true;
    }
}
