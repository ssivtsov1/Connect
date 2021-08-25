<?php
// Справочник видов работ
namespace app\models;

use Yii;
use yii\base\Model;
use yii\behaviors\TimestampBehavior;


class Spr_costwork extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return 'costwork';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    public function rules()
    {
        return [
            [['work', 'id'], 'required'],
        ];
    }

    public function getId()
    {
        return $this->getPrimaryKey();
    }
    
    public static function getDb()
    {
        if (isset(Yii::$app->user->identity->role))
            return Yii::$app->get('db');
        else
            return Yii::$app->get('db');
    }
}
