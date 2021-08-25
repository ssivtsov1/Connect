<?php
// Справочник статусов заявки
namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class Status_con extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return 'spr_status';
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'status' => 'Назва статусу',
        ];
    }

    public function rules()
    {
        return [

            [['status', 'id'], 'required'],

        ];
    }

    //   Метод, необходимый для поиска
    public function search($params)
    {
        $query = status_con::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,'pagination' => [
                'pageSize' => 15,],
        ]);
        if (!($this->load($params) && $this->validate())) {

            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'status', $this->status]);

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
