<?php
// Заявки пользователей
namespace app\models;
use Yii;
use yii\base\Model;
use yii\behaviors\TimestampBehavior;
use yii\data\ActiveDataProvider;


class Proposal extends \yii\db\ActiveRecord
{
   
    public static function tableName()
    {
        return 'proposal';
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'inn' => 'ІНН:',
            'schet' => 'Заявка:',
            'opl' => 'Призн.Опл.:',
            'adres' => 'Адреса робіт:',
            'type_doc' => 'Подача документів:',
            'comment' => 'Коментарій споживача:',
            'time' => 'Час створення',
            'date_z' => 'Дата виконання послуги',
            'date' => 'Дата подачі заявки:',
            'time' => 'Час подачі заявки:',
            'status' => 'Статус заявки:',
            'contract' => '№ договору:',
            'date_contract' => 'Дата договору:',
            'message' => 'Повідомлення для споживача:',
        ];
    }

    public function rules()
    {
        date_default_timezone_set('Europe/Kiev');
        return [

            [['inn','schet','id','opl','date','adres',
              'time','comment','date_z','status','message','new_inf',
                'contract','date_contract','type_doc','id_unique'], 'safe'],
            [['date'], 'default', 'value' => date('Y-m-d')],
            [['time'], 'default', 'value' => date('H:i')],
        ];
    }

     public function search($params)
    {
        $query = proposal::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'inn', $this->inn]);
        $query->andFilterWhere(['like', 'schet', $this->schet]);
        

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

