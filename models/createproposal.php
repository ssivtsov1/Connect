<?php
/**
 * Используется для создания заявок на подключение из вида
 */
namespace app\models;

use Yii;
use yii\base\Model;
use yii\behaviors\TimestampBehavior;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;

class Createproposal extends \yii\db\ActiveRecord
{
    public $Director;
    public $parrent_nazv;
    public $mail;
    public $adres1;
    public $plat_yesno = 'ні';
    public $view = 0; // Признак просмотра данных в личном кабиненте
    public $doc1;
    public $doc2;
    public $doc3;
    public $doc4;
    public $doc5;
    public $new_cl; // Признак создания нового пользователя электроенергией в справочнике

    public static function tableName()
    {
        return 'vw_request'; //Это вид
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'inn' => 'ІНН:',
            'schet' => 'Заявка:',
            'opl' => 'Оплата:',
            'date_opl' => 'Дата оплати:',
            'edrpo' => 'ЄДРПОУ:',
            'regsvid' => '№ рег. посвідч.',
            'nazv' => 'Замовник:',
            'adres' => 'Адреса:',
            'adres1' => 'Адреса:',
            'adres_con' => 'Адреса виконання робіт:',
            'res' => 'Територіальний підрозділ:',
            'comment' => 'Коментар споживача:',
            'tel_con' => 'Телефон:',
            'date' => 'Дата заявки:',
            'date_f' => 'Дата заявки:',
            'email' => 'Адреса ел. почти:',
            'time' => 'Час:',
            'status' => 'Статус замовлення:',
            'nazv_status' => 'Статус замовлення:',
            'date_z' => 'Дата виконання:',
            'contract' => '№ договору:',
            'date_contract' => 'Дата договору:',
            'new_doc' => 'Признак документів:',
            'message' => 'Повідомлення для споживача:',
            'id_tu' => 'Ідентифікатор ТУ:',
            'nomer' => '№ з.п.',
            'id_msg' => 'Ідентифікатор повідомлення:',
            'new_cl' => 'Новий користувач електроенергією',
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
        return [
            [['id','inn','schet','opl','date','adres1','id_tu',
                'okpo','nazv','adres_con','tel_con','id_unique','view',
                'doc1','doc2','doc3','doc4','doc5','mark','date1','date2','date3','date4',
                'date5','date6','date7','res','rem','nomer',
                'email','adres','status','nazv_status','new_inf','message','new_cl',
                'comment','time','date_z','date_opl','contract','date_contract'], 'safe'],
            ['date_z','date', 'format' => 'Y-m-d'],
//            [['tel','status','adres_con','nazv'],'required','message'=>'Поле обов’язкове'],
            [['doc1'],'file','skipOnEmpty' => true,'extensions'=>'pdf'],
            [['doc2'],'file','skipOnEmpty' => true,'extensions'=>'pdf'],
            [['doc3'],'file','skipOnEmpty' => true,'extensions'=>'pdf'],
            [['doc4'],'file','skipOnEmpty' => true,'extensions'=>'pdf'],
            [['doc5'],'file','skipOnEmpty' => true,'extensions'=>'pdf'],
        ];
    }

    public function search($params,$role,$year_p)
    {
        /*
         * role - № отдела (как-бы идентификатор пользователя)
         * year_p - год, который выбирается в меню (Рік)
         */
        switch($role) {
            case 3: // Полный доступ
                $query = createproposal::find()->where('year(date)=:year',[':year' => $year_p]);
                break;
            case 4: // Полный доступ
                $query = createproposal::find()->where('year(date)=:year',[':year' => $year_p]);
                break;
            case 5: // Полный доступ
                $query = createproposal::find()->where('year(date)=:year',[':year' => $year_p]);
                break;
            case 11: // Dnepr
                $query = createproposal::find()->
                where('department=:department',[':department' => 'Дніпропетровські РЕМ'])->
                orwhere('rem=:rem',[':rem' => 1])->andwhere('year(date)=:year',[':year' => $year_p]);
                break;
            case 12: // zvdrem
                $query = createproposal::find()->where('department=:department',[':department' => 'Жовтоводські РЕМ'])->
                 orwhere('rem=:rem',[':rem' => 2])->andwhere('year(date)=:year',[':year' => $year_p]);
                break;
            case 13: // vgrem
                $query = createproposal::find()->where('department=:department',[':department' => 'Вільногірські РЕМ'])->
                orwhere('rem=:rem',[':rem' => 2])->andwhere('year(date)=:year',[':year' => $year_p]);
                break;
            case 14: // pvrem
                $query = createproposal::find()->where('department=:department',[':department' => 'Павлоградські РЕМ'])->
                orwhere('rem=:rem',[':rem' => 4])->andwhere('year(date)=:year',[':year' => $year_p]);
                break;
            case 15: // krgrem
                $query = createproposal::find()->where('department=:department',[':department' => 'Криворізькі РЕМ'])->
                orwhere('rem=:rem',[':rem' => 3])->andwhere('year(date)=:year',[':year' => $year_p]);
                break;
            case 16: // aprem
                $query = createproposal::find()->where('department=:department',[':department' => 'Дільниця Апостоловська'])
                    ->orwhere('rem=:rem',[':rem' => 3])->andwhere('year(date)=:year',[':year' => $year_p]);
                break;
            case 17: // gvrem
                $query = createproposal::find()->where('department=:department',[':department' => 'Дільниця Гвардійська'])
                    ->orwhere('rem=:rem',[':rem' => 4])->andwhere('year(date)=:year',[':year' => $year_p]);
                break;
            case 18: // inrem
                $query = createproposal::find()->where('department=:department',[':department' => 'Дільниця Інгулецька'])
                    ->orwhere('rem=:rem',[':rem' => 3])->andwhere('year(date)=:year',[':year' => $year_p]);
                break;

        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder'=> ['status'=>SORT_ASC,'date'=>SORT_DESC,'time'=>SORT_DESC]]
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }
        $query->andFilterWhere(['like', 'adres_con', $this->adres_con]);
        $query->andFilterWhere(['like', 'inn', $this->inn]);
        $query->andFilterWhere(['like', 'nazv', $this->nazv]);
        $query->andFilterWhere(['like', 'tel_con', $this->tel]);
        $query->andFilterWhere(['like', 'id_tu', $this->id_tu]);
        $query->andFilterWhere(['=', 'nomer', $this->nomer]);
        
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

