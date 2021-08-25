<?php
// Данные по стоимости подключения не стандартного присоединения
namespace app\models;

use Yii;
use yii\base\Model;
use yii\behaviors\TimestampBehavior;
use yii\data\ActiveDataProvider;


class Data_non_std extends \yii\db\ActiveRecord
{
    public $cost;
    public $cost_line;
    public $line;
    
    public static function tableName()
    {
        return 'vw_data';
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'voltage' => 'Напруга',
            'vid_e' => 'Вид',
            'town' => 'Місто/село',
            'category' => 'Категорія',
        ];
    }

    public function rules()
    {
        return [
            [['id', 'price','town','category',
                'voltage','vid_e','rem','cost','cost_line',
                'line'], 'safe'],
        ];
    }
    
     //   Метод, необходимый для поиска
    public function search($params)
    {
        $query = data_non_std::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,'pagination' => [
                'pageSize' => 20,],
        ]);
        if (!($this->load($params) && $this->validate())) {

            return $dataProvider;
        }
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
