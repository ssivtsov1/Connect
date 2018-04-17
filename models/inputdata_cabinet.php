<?php
/**
 * Модель для ввода данных для входа в 
 * личный кабинет.
 */

namespace app\models;

use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;


class Inputdata_Cabinet extends Model
{
    public $contract; 
    public $date_contract;
    
     public function attributeLabels()
    {
        return [
            'contract' => '№ договору:',
            'date_contract' => 'Дата договору:',
        ];
    }

    public function rules()
    {
        return [
            [['contract' ], 'required','message' => "Введіть № договору"],
            [['date_contract' ], 'required','message' => "Введіть дату договору"],
            
        ];
    }
}
