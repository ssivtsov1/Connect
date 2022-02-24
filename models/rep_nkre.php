<?php
// Заявки пользователей
namespace app\models;
use Yii;
use yii\base\Model;
use yii\behaviors\TimestampBehavior;
use yii\data\ActiveDataProvider;


class Rep_nkre extends \yii\db\ActiveRecord
{
   
    public static function tableName()
    {
        return 'rep_nkre';
    }

    public function attributeLabels()
    {
        return [
            'num_pp' => '№',
            'fio' => 'П.І.Б.',
            'loc' => 'Розташування:',
            'nazv_locate' => 'Розташування:',
            'n_doc' => '№ заяви.:',
            'adres' => 'Місцезнаходження:',
            'date_doc' => 'Дата заяви:',
            'src' => 'Походження:',
            'nazv_src' => 'Походження:',
            'type_ust' => 'Тип',
            'nazv_typeust' => 'Тип',
            'category' => 'Категорія',
            'power' => 'Потужність:',
            'power_z' => 'Потужність замовл:',
            'voltage' => 'Напруга:',
            'date_expl' => 'Дата введення :',
            'point_con' => 'Точка приєдн.',
            'nazv_point_con' => 'Точка приєдн.',
            'scheme' => 'Живлення:',
            'nazv_scheme' => 'Живлення:',
            'return_z' => 'Причина повернення заяви:',
            'date_dog' => 'Дата договору:',
            'date_tu' => 'Дата ТУ:',
            'num_tu' => '№ ТУ:',
            'distance' => 'Відстань:',
            'date_con' => 'Дата підключення:',
            'date_com_expl' => 'Дата прийняття в експл.:',
            'date_com_dog_d' => 'Дата укл. договору (розп.):',
            'date_com_dog_p' => 'Дата укл. договору (постач.):',
            'rem' => 'РЕМ:',
            'nazv_rem' => 'РЕМ:',
        ];
    }

    public function rules()
    {
        date_default_timezone_set('Europe/Kiev');
        return [

            [['id','num_pp','fio','adres','loc','n_doc','nazv_rem','nazv_locate','nazv_point_con',
              'rem','date_doc','src','type_ust','category','power','nazv_src','nazv_scheme',
                'power_z','voltage','date_expl','point_con','scheme','nazv_typeust',
                'return_z','date_dog','date_tu','num_tu','distance','date_con',
                'date_com_expl','date_com_dog_d','date_com_dog_p','status'], 'safe'],

        ];
    }


    public function getId()
    {
        return $this->getPrimaryKey();
    }

    public static function getDb()
    {
        return Yii::$app->get('db_pg_budget');
    }

}

