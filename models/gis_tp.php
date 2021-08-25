<?php
// Справочник видов работ (вспомагательная модель)
namespace app\models;

use Yii;
use yii\base\Model;
use yii\behaviors\TimestampBehavior;
use yii\data\ActiveDataProvider;


class Gis_tp extends \yii\db\ActiveRecord
{

    public $dist;
    public $koord;

    public static function tableName()
    {
        return 'tp';
    }


    public function rules()
    {
        return [

            [['address', 'dist', 'tpid', 'koord'],'safe'],

        ];
    }


    public function getId()
    {
        return $this->getPrimaryKey();
    }

    public static function getDb()
    {
        return Yii::$app->get('db_gis');
    }
    
}

