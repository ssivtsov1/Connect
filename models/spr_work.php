<?php
// Справочник видов работ (вспомагательная модель)
namespace app\models;

use Yii;
use yii\base\Model;
use yii\behaviors\TimestampBehavior;
use yii\data\ActiveDataProvider;


class Spr_work extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return 'costwork';
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'work' => 'Вид роботи',
            'usluga' => 'Послуга',
            'brig' => 'Склад бригади',
            'time_transp' => 'Час простою',
            'type_transp' => 'Тип трансп.',
            'stavka_grn' => 'Тарифна ставка',
            'cast_1' => 'квіт-трав; верес-лист',
            'cast_2' => 'берез',
            'cast_3' => 'берез-трав; верес-лист',
            'cast_4' => 'черв-серп',
            'cast_5' => 'грудень',
            'cast_6' => 'січ-лют',
            'kod_uslug' => 'Код посл.',
            'transp_cek' => 'Транспорт ЦЕК',
           
        ];
    }
    
    public function rules()
    {
        return [

            [['work', 'usluga','cast_1','cast_2','cast_3',
                'cast_4','cast_5','cast_6','stavka_grn',
                'time_transp','type_transp','brig','transp_cek'], 'safe'],

        ];
    }

   
     public function search($params)
    {
        $query = spr_work::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

       // $query->andFilterWhere([
        //    'work' => $this->work,
       // ]);

        $query->andFilterWhere(['like', 'work', $this->work]);
        $query->andFilterWhere(['like', 'usluga', $this->usluga]);
        $query->andFilterWhere(['=', 'stavka_grn', $this->stavka_grn]);
        $query->andFilterWhere(['=', 'time_transp', $this->time_transp]);
        $query->andFilterWhere(['=', 'type_transp', $this->type_transp]);
        $query->andFilterWhere(['like', 'brig', $this->brig]);
        $query->andFilterWhere(['=', 'cast_1', $this->cast_1]);
        $query->andFilterWhere(['=', 'cast_2', $this->cast_2]);
        $query->andFilterWhere(['=', 'cast_3', $this->cast_3]);
        $query->andFilterWhere(['=', 'cast_4', $this->cast_4]);
        $query->andFilterWhere(['=', 'cast_5', $this->cast_5]); 
        $query->andFilterWhere(['=', 'cast_6', $this->cast_6]); 

        return $dataProvider;
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

