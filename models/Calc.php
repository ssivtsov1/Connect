<?php
// Используется для рассчетов стоимости работ 
namespace app\models;

use Yii;
use yii\db\ActiveRecord;


class Calc extends ActiveRecord
{
       
//  Формирование строки SQL запроса для расчета стоимости подключения
    public static function Calc($town,$power,$voltage,$q_phase,$reliability)
    {   if($town==2) $town=0;
        if($power<=16) $power_stage=1;
        if($power>16 && $power<=50) $power_stage=2;
        if($power>50 && $power<=160) $power_stage=3;
        $reliability=4-$reliability;
        if($q_phase==2) $q_phase=3;
        if($voltage==1) $v='a';
        if($voltage==2) $v='b';
        if($voltage==3) $v='c';
        if($voltage==4) $v='d';
        $u='u_'.$v.$q_phase;
        
        $sql = 'SELECT '.$u.' FROM data_con WHERE town=:'.$town.' and power_stage=:'.$power_stage.
                ' and rank=:'.$reliability;
        return $sql;
    }
    

    public static function getDb()
    {
        return Yii::$app->get('db');
    }

}
