<?php
// Справочник РЭСов
namespace app\models;

use Yii;
use yii\base\Model;
use yii\behaviors\TimestampBehavior;


class Spr_res extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return 'spr_res';
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nazv' => 'Назва РЕМ',

        ];
    }

    public function rules()
    {
        return [

            [['nazv', 'id'], 'required'],

        ];
    }

    public function getId()
    {
        return $this->getPrimaryKey();
    }

    public static function getDb()
    {
        return Yii::$app->get('db');
    }

}
