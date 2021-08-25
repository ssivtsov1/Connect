<?php
// Справочник транспорта (используется для редактирования)
namespace app\models;

use Yii;
use yii\base\Model;

class Sprtransp extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return 'transport';
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'transport' => 'Транспорт',
            'nomer' => 'Номер',
            'locale' => 'Розположення',
            'prostoy' => 'Вартість простою',
            'proezd' => 'Вартість проїзду',
        ];
    }

    public function rules()
    {
        return [
            [['transport','nomer','proezd','prostoy','locale'], 'safe'],
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


