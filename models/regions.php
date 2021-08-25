<?php
/*Справочник областей
 */
namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;

class Regions extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return 'regions';
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'obl' => 'Область',
        ];
    }

    public function rules()
    {
        return [
            [['id','obl'], 'safe'],
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

