<?php
namespace app\models;
use Yii;
use yii\base\Model;
use yii\behaviors\TimestampBehavior;
use yii\data\ActiveDataProvider;
use yii\helpers;

class Klient extends \yii\db\ActiveRecord
{
    public $id_t;
    public $adr_work;
    public $comment;
    public $adr_town;
    public $adr_district;
    public $adr_street;
    public $adr_flat;
    public $search_town;
    public $search_street;
    public $id_town;
    public $id_street;
    public $variant1;
    public $variant2;
    public $doc1;
    public $doc2;
    public $doc3;
    public $doc4;
    public $doc5;
    public $doc6;
    public $doc7;
    public $verifyCode;
       
         
    public static function tableName()
    {
        return 'klient';
    }

    public function attributeLabels()
    {
        return [
            'id' => '',
            'id_t' => '',
            'inn' => 'Індивідуальний податковий №:',
            'okpo' => 'ЄДРПОУ:',
            'regsvid' => '№ регістраційного посвідчення',
            'nazv' => 'Прізвище, ім’я та по батькові:',
            'contact_person' => 'П.І.Б. контактної особи:',
            'fio_dir' => 'Посада та П.І.Б. уповноваженої особи:',
            'addr' => 'Адреса проживання:',
            'tel' => 'Контактний телефон:',
            'priz_nds' => 'Платник ПДВ:',
            'person' => '',
            'date_reg' => 'Дата реєстрації:',
            'reg' => 'Признак реєстрації',
            'email' => 'E-Mail:',
            'adr_work' => 'Адреса виконання робіт:',
            'comment' => 'Коментар замовника:',
            'adr_district' => 'Район області:',
            'adr_town' => 'Населений пункт:',
            'adr_street' => 'Вулиця:',
            'adr_flat' => 'Будинок та квартира:',
            'adres_post' => 'Адреса для листування:',
            'search_town' => 'Населений пункт:',
            'search_street' => 'Вулиця:',
            'id_street' => '',
            'variant1' => '',
            'variant2' => '',
            'doc1' => 'Заява про приєднання (з ЕЦП)',
            'doc2' => "Копії ситуаційного плану та викопіювання з топографо-геодезичного плану в масштабі 1:2000"
            . " із зазначенням місця розташування об'єкта(об'єктів) замовника, земельної ділянки замовника або"
            . " прогнозованої точки приєднання",
            'doc3' => 'Документ , який підтверджує право власності чи користування земельною ділянкою',
            'doc4' => 'Виписка , витяг, довідка із ЄДРПОУ',
            'doc5' => 'Статутний документ',
            'doc6' => 'Належним чином оформлена довіреність чи інший документ на право укладати договори особі,'
            . ' яка уповноважена підписувати договори (за потреби)',
            'doc7' => 'Паспорт',
            'verifyCode' => 'Введіть код для відправки',
            
        ];
    }

    public function rules()
    {
       
        return [

            [['verifyCode','search_town','search_street','adr_flat',
                'inn', 'nazv','addr','email','doc1','doc2','doc3'],'required','message'=>'Поле обов’язкове'],
            [['tel','priz_nds','okpo','regsvid','reg','adr_district',
              'adr_town','adr_street','adr_flat','adres_post',
              'person','date_reg','email','fio_dir','contact_person',
                'id_town','id_street','variant1','variant2','search_town','search_street',
                'nazv','email','inn','addr','id','id_t'], 'safe'],
            [['adr_work','comment','id_unique'], 'safe'],
            ['inn','string','length'=>[10,10],'tooShort'=>'ІНН повинно бути 10 значним',
                'tooLong'=>'ІНН повинно бути 10 значним'],
            [['date_reg'], 'default', 'value' => date('Y-m-d')],
            [['reg'], 'default', 'value' => 1],
            [['person'], 'default', 'value' => 1],
            [['priz_nds'], 'default', 'value' => 0],
            // Условная валидация полей doc4-doc7 в зависимости от выбора подачи документов- радиокнопка variant2
            ['doc7', 'required', 'when' => function ($model) {
                return ($model->variant2 == 3 || $model->variant2 == 5) ;
            }, 'whenClient' => 'function (attribute, value) {
                return ($("#klient-variant2").find("input:checked").val()==3 || $("#klient-variant2").find("input:checked").val()==5);
            }', 'message' => 'Поле обов’язкове'], 
                    
            ['doc6', 'required', 'when' => function ($model) {
                return ($model->variant2 == 4 || $model->variant2 == 2 || $model->variant2 == 5) ;
            }, 'whenClient' => 'function (attribute, value) {
                return ($("#klient-variant2").find("input:checked").val()==4 
                || $("#klient-variant2").find("input:checked").val()==2 || $("#klient-variant2").find("input:checked").val()==5);
            }', 'message' => 'Поле обов’язкове'],  
                    
            ['doc5', 'required', 'when' => function ($model) {
                return ($model->variant2 < 3) ;
            }, 'whenClient' => 'function (attribute, value) {
                return $("#klient-variant2").find("input:checked").val()<3; 
                
            }', 'message' => 'Поле обов’язкове'],        
            
            ['doc4', 'required', 'when' => function ($model) {
                return ($model->variant2 == 1 || $model->variant2 == 2 || $model->variant2 == 5) ;
            }, 'whenClient' => 'function (attribute, value) {
                return ($("#klient-variant2").find("input:checked").val()==1 
                || $("#klient-variant2").find("input:checked").val()==2 || $("#klient-variant2").find("input:checked").val()==5);
            }', 'message' => 'Поле обов’язкове'],   
                    
            ['email', 'email','message'=>'Не корректний адрес почти'],
            [['doc1'],'file','skipOnEmpty' => true,'extensions'=>'pdf'],
            [['doc2'],'file','skipOnEmpty' => true,'extensions'=>'pdf'],
            [['doc3'],'file','skipOnEmpty' => true,'extensions'=>'pdf'],
            [['doc4'],'file','skipOnEmpty' => true,'extensions'=>'pdf'],
            [['doc5'],'file','skipOnEmpty' => true,'extensions'=>'pdf'],
            [['doc6'],'file','skipOnEmpty' => true,'extensions'=>'pdf'],
            [['doc7'],'file','skipOnEmpty' => true,'extensions'=>'pdf'],
            ['verifyCode', 'captcha']    
            ];
     
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
        if (isset(Yii::$app->user->identity->role))
            return Yii::$app->get('db');
        else
            return Yii::$app->get('db');

    }

}

