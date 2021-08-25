<?php
/**
 * Используется для создания заявок на подключение из вида
 */
namespace app\models;

use Yii;
use yii\base\Model;
use yii\behaviors\TimestampBehavior;
use yii\data\ActiveDataProvider;

class Client extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return 'client';
    }


    public function rules()
    {
        return [

            [['id','inn','edpo','tel','adres',
                'name','e_mail'], 'safe'],
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

