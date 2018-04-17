<?php
// Справочник ответственных лиц в РЭСах (используется для редактирования)
namespace app\models;

use Yii;
use yii\base\Model;
use yii\behaviors\TimestampBehavior;
use yii\data\ActiveDataProvider;
use yii\data\SqlDataProvider;

class Spr_res_koord extends \yii\db\ActiveRecord
{
   
    public static function tableName()
    {

        return 'spr_res_koord';
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_res' => 'РЕМ',
            'name_koord' => 'П.І.Б. відповідальної особи',
            'tel_mobile' => 'Номер тел.(моб.)',
            'tel_town' => 'Номер тел.(міський)',
            'tel' => 'Телефон внутрішній',
            'tel_dop' => 'Додатковий тел.',
            'email' => 'Адреса пошти',
        ];
    }

    public function rules()
    {
        return [

            [['id','id_res','tel','name_koord',
              'tel_mobile','tel_town','tel_dop','email','type_usl'], 'safe'],
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


