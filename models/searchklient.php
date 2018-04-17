<?php
/**
 * Created by PhpStorm.
 * User: ssivtsov
 * Date: 21.06.2017
 * Time: 9:49
 */
namespace app\models;

use Yii;
use yii\base\Model;
use yii\behaviors\TimestampBehavior;
use yii\data\ActiveDataProvider;


class searchklient extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return 'klient';
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'inn' => 'ІНН:',
            'okpo' => 'ОКПО:',
            'regsvid' => '№ рег. посвідч.',
            'nazv' => 'Назва:',
            'addr' => 'Адреса:',
            'tel' => 'Телефон:',
            'priz_nds' => 'Платник ПДВ:',
            'date_reg' => 'Дата реєстрації:',
            'reg' => 'Признак реєстрації:',
            'person' => 'Особа:'
        ];
    }


    public function rules()
    {
        return [
            [['id','tel','okpo','regsvid','inn','nazv','addr','priz_nds','person'], 'safe'],
                ];
    }

     public function search($params)
    {
        $query = klient::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'nazv', $this->nazv]);
        $query->andFilterWhere(['like', 'addr', $this->addr]);
        $query->andFilterWhere(['like', 'tel', $this->tel]);
        $query->andFilterWhere(['like', 'inn', $this->inn]);
        $query->andFilterWhere(['like', 'okpo', $this->okpo]);
        $query->andFilterWhere(['like', 'regsvid', $this->regsvid]);
        $query->andFilterWhere(['=', 'priz_nds', $this->priz_nds]);
        $query->andFilterWhere(['=', 'person', $this->person]);

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

