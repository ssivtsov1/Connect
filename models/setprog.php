<?php
/**
 * Используется для создания заявок на подключение из вида
 */
namespace app\models;

use Yii;
use yii\base\Model;
use yii\behaviors\TimestampBehavior;
use yii\data\ActiveDataProvider;

class Setprog extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return 'setprog';
    }


    public function rules()
    {
        return [

            [['year'], 'safe'],
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

