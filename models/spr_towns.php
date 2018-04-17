<?php
/**
 Справочник населенных пунктов
 */
namespace app\models;

use Yii;
use yii\base\Model;
use yii\behaviors\TimestampBehavior;
use yii\data\ActiveDataProvider;
use yii\data\SqlDataProvider;


class Spr_towns extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'spr_towns';
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'town' => 'Населений пункт',
            'street' => 'Вулиця',
            'district' => 'Район',
        ];
    }

    public function rules()
    {
        return [
            [['id','town','street','district'], 'safe'],
            ];
    }

//   Метод, необходимый для поиска
     public function search($params)
    {
        $query = refusal::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
             'pageSize' => 15,],
            'sort'=> ['defaultOrder' => ['date'=>SORT_DESC]],
        ]);
        if (!($this->load($params) && $this->validate())) {
           
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'usluga', $this->transport]);
        $query->andFilterWhere(['like', 'work', $this->nomer]);

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


