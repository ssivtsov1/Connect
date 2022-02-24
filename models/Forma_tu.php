<?php

namespace app\models;

use yii\base\Model;
use yii\web\UploadedFile;



class Forma_tu extends Model
{
    public $name1;
    public $name2;
    public $date1;
    public $date2;
    public $date3;
    public $id;
    public $id_tu;
    public $rem;


    public function attributeLabels()
    {
        return [
            'name1' => 'Тип електроустановки',
            'name2' => 'Тип приєднання',
            'date1' => 'Дата видачі ТУ',
            'date2' => 'Дата внесення змін',
            'date3' => 'Дата видачі повідомлення',
        ];
    }

    public function rules()
    {
        return [
            [['name1', 'name2', 'date1', 'date2', 'date3', 'id', 'rem', 'id_tu', 'rem'
            ], 'safe'],
            [[ 'name1' ], 'compare', 'compareValue' => 0, 'operator' => '!=','message' => "Введіть тип електроустановки" ],
            [[ 'name2' ], 'compare', 'compareValue' => 0, 'operator' => '!=','message' => "Введіть тип приєднання" ],
            [[ 'rem' ], 'compare', 'compareValue' => 0, 'operator' => '!=','message' => "Введіть дільницю" ],
        ];
}
}