<?php
// Заявки пользователей
namespace app\models;
use Yii;
use yii\base\Model;
use yii\behaviors\TimestampBehavior;
use yii\data\ActiveDataProvider;


class Request extends \yii\db\ActiveRecord
{
    public $edrpo;
    public $inn;
    public $nazv;
    public $tel;
    public $email;
    public $adres1;
    public $adres_con;
    public $doc1;
    public $doc2;
    public $doc3;
    public $doc4;
    public $doc5;

   
    public static function tableName()
    {
        return 'request';
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_tu' => 'Ідентифікатор ТУ:',
            'inn' => 'ІНН:',
            'edrpo' => 'ЕДРПОУ:',
            'tel' => 'Телефон:',
            'nazv' => 'Замовник:',
            'schet' => 'Заявка:',
            'opl' => 'Призн.Опл.:',
            'adres1' => 'Адреса:',
            'adres_con' => 'Адреса підключення:',
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
            'doc1' => 'Заява про приєднання',
            'doc2' => "Технічні умови",
            'doc3' => 'Розрахунок вартості плати на приєднання',
            'doc4' => 'Рахунок',
            'doc5' => 'Повідомлення про надання послуги на приєднання',
            'mark' => 'Мета приєднання',
            'date1' => "Дата закінчення проєктування та здійснення заходів щодо відведення земельних ділянок для розміщення відповідних об'єктів електроенергетики",
            'date2' => "Дата завершення проведення експертизи та погодження проектної документації з іншими заінтересованими сторонами",
            'date3' => 'Дата отримання дозволу на виконання будівельно-монтажних робіт',
            'date4' => 'Дата початку проведення тендерних процедур з метою придбання обладнання та матеріалів для виконання  будівельно-монтажних робіт',
            'date5' => 'Дата завершення проведення тендерних процедур з метою придбання обладнання та матеріалів для виконання  будівельно-монтажних робіт',
            'date6' => 'Дата завершення виконання будівельно-монтажних робіт, пусконалагоджувальних та випробувальних робіт',
            'date7' => "Дата підключення електроустановок (об'єкта) замовника до електричних мереж",
        ];
    }

    public function rules()
    {
        date_default_timezone_set('Europe/Kiev');
        return [
            [['inn','schet','id','opl','date','adres1','adres','adres_con',
              'time','comment','date_z','status','message','new_inf',
                'doc1','doc2','doc3','doc4','doc5','mark','date1','date2','date3','date4',
                'date5','date6','date7',
                'contract','date_contract','type_doc','id_unique','id_tu','nazv','tel','edrpo','email','res','rem'], 'safe'],
            [['tel','status','adres_con','nazv'],'required','message'=>'Поле обов’язкове'],
            [['date'], 'default', 'value' => date('Y-m-d')],
            [['time'], 'default', 'value' => date('H:i')],
            [['doc1'],'file','skipOnEmpty' => true,'extensions'=>'pdf'],
            [['doc2'],'file','skipOnEmpty' => true,'extensions'=>'pdf'],
            [['doc3'],'file','skipOnEmpty' => true,'extensions'=>'pdf'],
            [['doc4'],'file','skipOnEmpty' => true,'extensions'=>'pdf'],
            [['doc5'],'file','skipOnEmpty' => true,'extensions'=>'pdf'],
        ];
    }

     public function search($params)
    {
        $query = request::find();

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

    public function upload($d,$rand)
    {

//         if($this->validate()){
        $n=substr($d,3);
        $path = "store/".$n.'_'.$rand.'-'.$this->$d->basename.'.'.$this->$d->extension;
        $this->$d->saveas($path);
        //$this->photo = $path;
        //$this->attachImage($path);
        //@unlink($path);
        return true;
//        }
//        else
//            return false;
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

