<?php
/**
 * Используется для просмотра заявок на подключение из вида
 */
namespace app\models;

use Yii;
use yii\base\Model;
use yii\behaviors\TimestampBehavior;
use yii\data\ActiveDataProvider;

class Viewproposal extends \yii\db\ActiveRecord
{
    public $Director;
    public $parrent_nazv;
    public $mail;
    public $plat_yesno = 'ні';
    public $view = 0; // Признак просмотра данных в личном кабиненте

    public static function tableName()
    {
        return 'vw_proposal'; //Это вид
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'inn' => 'ІНН:',
            'schet' => 'Заявка:',
            'opl' => 'Оплата:',
            'okpo' => 'ЄДРПОУ:',
            'regsvid' => '№ рег. посвідч.',
            'nazv' => 'Споживач:',
            'addr' => 'Адреса:',
            'adres' => 'Адреса виконання робіт:',
            'res' => 'Територіальний підрозділ:',
            'comment' => 'Коментар споживача:',
            'tel' => 'Телефон:',
            'date' => 'Дата заявки:',
            'email' => 'Адреса ел. почти:',
            'time' => 'Час:',
            'status' => 'Статус замовлення:',
            'nazv_status' => 'Статус замовлення:',
            'date_z' => 'Дата виконання:',
            'contract' => '№ договору:',
            'date_contract' => 'Дата договору:',
            'new_doc' => 'Признак документів:',
            'message' => 'Повідомлення для споживача:',
            
        ];
    }

    public function rules()
    {
        return [

            [['id','inn','schet','opl','date',
                'okpo','nazv','addr','tel','id_unique','view',
                'email','adres','status','nazv_status','new_inf','message',
                'comment','time','date_z','date_opl','contract','date_contract'], 'safe'],
//            ['date_z', 'compare',
//                'compareValue' => date('Y-m-d'), 'operator' => '>=',
//                'type' => 'string','message' => "Введено минулу дату"],
            ['date_z','date', 'format' => 'Y-m-d'],
        ];
    }

    public function search($params,$role)
    {
        switch($role) {
            case 3: // Полный доступ
                $query = viewproposal::find();
                break;
           
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder'=> ['status'=>SORT_ASC,'date'=>SORT_DESC,'time'=>SORT_DESC]]
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }
        $query->andFilterWhere(['like', 'nazv_status', $this->nazv_status]);
        $query->andFilterWhere(['like', 'inn', $this->inn]);
        $query->andFilterWhere(['like', 'nazv', $this->nazv]);
        $query->andFilterWhere(['like', 'addr', $this->addr]);
        $query->andFilterWhere(['like', 'tel', $this->tel]);
        $query->andFilterWhere(['like', 'okpo', $this->okpo]);
        $query->andFilterWhere(['like', 'contract', $this->contract]);
        
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

