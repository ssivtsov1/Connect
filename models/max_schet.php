<?php
// Модель для хранения последнего номера заявки
namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;

class Max_schet extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return 'max_schet';
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'value' => 'макс. значення номера заявки',
        ];
    }

    public function rules()
    {
        date_default_timezone_set('Europe/Kiev');
        return [
            [['id','value'], 'safe'],
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

