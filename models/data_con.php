<?php
// Данные по стоимости подключения
namespace app\models;

use Yii;
use yii\base\Model;
use yii\behaviors\TimestampBehavior;
use yii\data\ActiveDataProvider;


class Data_con extends \yii\db\ActiveRecord
{
    public $cost;
    
    public static function tableName()
    {
        return 'data_con';
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'u_a1' => '1 фаз. 0.4 кВ',
            'u_a3' => '3 фаз. 0.4 кВ',
            'u_b1' => '1 фаз. 10 кВ',
            'u_b3' => '3 фаз. 10 кВ',
            'u_c1' => '1 фаз. 35 кВ',
            'u_c3' => '3 фаз. 35 кВ',
            'u_d1' => '1 фаз. 110 кВ',
            'u_d3' => '3 фаз. 110 кВ',
            'town' => 'Місто/село',
            'rank' => 'Категорія',
            'power_stage' => 'Потужність',
            
        ];
    }

    public function rules()
    {
        return [
            [['id', 'u_a1','u_b1','u_c1','u_d1','town','rank',
                'u_a3','u_b3','u_c3','u_d3'], 'required'],
        ];
    }
    
     //   Метод, необходимый для поиска
    public function search($params)
    {
        $query = data_con::find()->orderBy(['power_stage' => SORT_ASC]);
                //->orderBy(['rank' => SORT_DESC])->orderBy(['town' => SORT_DESC]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,'pagination' => [
                'pageSize' => 20,],
        ]);
        if (!($this->load($params) && $this->validate())) {

            return $dataProvider;
        }

       // $query->andFilterWhere(['like', 'status', $this->status]);

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
