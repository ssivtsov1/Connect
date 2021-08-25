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
    public $login;
    public $password;
    
     public function attributeLabels()
    {
        return [
            'contract' => '№ договору:',
            'date_contract' => 'Дата договору:',
            'login' => 'Логін:',
            'password' => 'Пароль:',
        ];
    }

    public function rules()
    {
        return [
            [['login' ], 'required','message' => "Введіть логін"],
            [['password' ], 'required','message' => "Введіть пароль"],
           [['contract', 'login'],'safe']
        ];
    }
}
