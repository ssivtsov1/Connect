<?php
//namespace console\controllers;
namespace app\controllers;
 
use Yii;
use yii\helpers\Url;
use yii\web\Response;
use yii\web\Controller;
//use yii\console\Controller;
use app\models\schet;
 
/**
 * Test controller
 */
class CronController extends Controller {
 
    public function actionIndex() {
        echo "Yes, cron service is running.";
    }
 
    public function actionCron_schet() {
        
       $filename =  'cron_schet';
       
       if (file_exists($filename))
            unlink($filename);
      
        $f = fopen('cron_schet.dat','r');
        $s = fgets($f);
        
       // echo $s;
        $model = new schet();
        $sql = 'select max(cast(id as unsigned)) as id from schet';
        $sch = schet::findBySql($sql)->one();
        $id = $sch->id;
       
        //echo $id;
        if($id>$s)
        {
            //echo " З’явилась нова заявка №$id";
            fclose($f);
            $f = fopen('cron_schet.dat','w');
            fputs($f,$id);
            fclose($f);
            
            //exec("mpg123 zvukovye-effekty-korotkie-fanfary.mp3");
            return 1;
//            $model = new info();
//            $model->title = "З’явилась нова заявка №$id";
//            $model->info1 = "";
//            $model->style1 = "d15";
//            $model->style_title = "d9";
//            return $this->render('info', [
//                'model' => $model]);
            //return $this->render('sound');


        }
        else
            //$this->refresh();
             fclose($f);
            return 0;
            //return $this->redirect(['/site/viewschet']);
            //return 0;

       
    
   }
}
