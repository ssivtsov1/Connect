<?php
/*Ввод основных данных для рассчета*/

namespace app\models;

use Yii;
use yii\base\Model;

class InputDataForm extends Model
{
    public $res;               // Название РЭСа
    public $energy;            // Вид потребления
    public $place;             // Местоположение точки присоединения (для нестандартного присоединения)
    public $type_line;         // Тип лінії електропередавання  (для нестандартного присоединения)
    public $dist=0;              // Длинна линии (расстояние) (для нестандартного присоединения)
    public $id;
    public $potrebitel;        // ИНН потребителя 
    public $inn;               // Индивидуальный налоговый номер 
    public $addr;
    public $addr_work;         // Адрес работ (вводится для поиска на карте)
    public $nazv = '';         // Название потребителя 
    public $work;              // Вид работы
    public $usluga;            // Вид услуги 
    public $kol = 1;           // Кол-во калькуляционных единиц
    public $koord = '';
    //public $distance = 0;      // Расстояние до объекта туда и назад
    public $poezdka = 1;       // Количество выездов бригады
    public $time_work = 1;     // Время работы в часах (для транспортных услуг)
    public $time_prostoy = 1;  // Время простоя в часах (для транспортных услуг)
    public $adr_potr = '';     // Адрес с карты
    public $geo = '';          // Координаты с карты
    public $region;            // Область
    public $refresh = 0;       // Признак перерасчета заявки
    public $transp_cek = 1;    // Признак использования транспорта ЦЕК
    
    public $town = 1;          // Признак населенного пункта 
    public $power;             // Мощность  
    public $q_phase;           // Кол-во фаз
    public $reliability;       // Категория надежности
    public $voltage;           // Уровень напряжения
    public $type_connect;           // Вид соединения (стандартное/нестандартное)
    public $search_town;

    public $town1;
    public $id_street;
    public $id_t;
    public $street;
    public $house;
    public $korp;


    private $_user;

    public function attributeLabels()
    {
        return [
            'res' => 'РЕМ для послуги підключення:',
            'energy' => 'Вид підключення',
            'place' => 'Розташування точки приєднання',
            'type_line' => 'Тип лінії електропередавання',
            'dist' => 'Відстань, м',
            'potrebitel' => 'Споживач ІНН:',
            'usluga' => 'Напрямок роботи (послуги):',
            'work' => 'Найменування роботи (послуги):',
            'kol' => 'Кількість калькуляційних одиниць:',
            //'distance' => 'Відстань від бази до місця проведення робіт (в обидві сторони),км:',
            'koord' => '',
            'poezdka' => 'Кількість виїздів бригади:',
            'time_work' => 'Кількість годин роботи (тільки для транспортних послуг):',
            'time_prostoy' => 'Кількість годин простою (тільки для транспортних послуг):',
            'nazv' => 'Споживач назва: ',
            'addr' => 'Адреса споживача: ',
            'addr_work' => 'Адреса виконання робіт (для пошуку на карті) - Пишіть українською мовою (вихід з поля - Tab) ',
            'region' => 'Область:',
            
            
            'voltage' => 'Напруга в точці приєднання:',
            'reliability' => 'Категорія надійності:',
            'q_phase' => 'Кількість фаз приєднання:',
            'power' => 'Потужність, замовлена до приєднання,кВт:',
            'town' => 'Місцерозположення:',
            'type_connect' => "З'єднання:",
            'search_town' => 'Населений пункт:',

            'house' => 'Будинок',
            'korp' => 'Корпус',
            'id_t' => '',
            'id_street' => '',
            'town1' => 'Населений пункт',
            'street' => 'Вулиця',
        
            ];
    }

    public function rules()
    {
        return [
            [['work', 'kol', 'distance','poezdka'], 'required'],
            ['power', 'compare', 'compareValue' => 950, 'operator' => '<', 'type' => 'number',
                'message' => "Для підключення білше 950кВт проводиться не стандартне приєднання - вартість уточнюйте у оператора."],
            ['potrebitel','safe'],
            ['res', 'default', 'value'=>'Дніпропетровський РЕМ'],
            ['potrebitel','string','length'=>[10,10],'tooShort'=>'ІНН повинно бути 10 значним',
                'tooLong'=>'ІНН повинно бути 10 значним'],
            ['time_work', 'safe'],
            ['energy', 'safe'],
            ['adr_potr', 'safe'],
            ['geo', 'safe'],
            ['refresh', 'safe'],
            ['type_line', 'safe'],
            ['place', 'safe'],
            ['dist', 'safe'],
            ['type_connect', 'safe'],
            ['region', 'safe'],
            ['time_prostoy', 'safe'],
            ['voltage', 'safe'],
            ['town', 'required','message' => "Населений пункт"],
            ['power', 'required','message' => "Введіть потужність"],
            
            ['q_phase', 'safe'],
            ['reliability', 'safe'],
            [['house','korp','street',
                'id_t','town1','id_street'], 'safe']
        ];
    }

}
