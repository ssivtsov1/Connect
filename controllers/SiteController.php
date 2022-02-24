<?php

namespace app\controllers;

use app\models\rep_nkre;
use app\models\report_nkre;
use app\models\Spr_brig;
use app\models\Spr_res;
use app\models\Spr_res_koord;
use app\models\Spr_uslug;
use app\models\Spr_work;
use app\models\Sprtransp;
use app\models\Status_sch;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use app\models\ContactForm;
use app\models\Forma_tu;
use app\models\InputDataForm;
use app\models\data_con;
use app\models\data_non_std;
use app\models\klient;
use app\models\spr_towns;
use app\models\schet;
use app\models\client;
use app\models\viewproposal;
use app\models\createproposal;
use app\models\requestsearch;
use app\models\tofile;
use app\models\forExcel;
use app\models\info;
use app\models\gis_tp;
use app\models\docs;
use app\models\User;
use app\models\loginform;
use app\models\potrebitel;
use app\models\proposal;
use app\models\request;
use app\models\inputdata_cabinet;
use kartik\mpdf\Pdf;
use yii\web\UploadedFile;
use yii\helpers\Url;

class SiteController extends Controller
{  /**
 * 
 * @return type
 *
 */

    //public $defaultAction = 'index';

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
//                    'logout' => ['info_cabinet'],
                ],
            ],
        ];
    }

    public function beforeAction($action)
    {
//       debug($this->action->id);
//       return;
        if ($this->action->id == 'upd')
        {
            $this->enableCsrfValidation = false;
        }

        return parent::beforeAction($action);
    }
    

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    //  Происходит при запуске сайта
    public function actionIndex()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->redirect(['site/more']);
        }
        if(strpos(Yii::$app->request->url,'/cek')==0)
            return $this->redirect(['site/more']);
        $model = new loginform();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->redirect(['site/more']);
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    //  Происходит после ввода пароля
    public function actionMore()
    {
        $flag=1;
        $role=0;
        if(!isset(Yii::$app->user->identity->role))
        {      $flag=0;}
        else{
            $role=Yii::$app->user->identity->role;
        }
        $adr='192.168.55.1';
        $adr1='localhost';
        $url = Url::base('');
        $flag_cek=0;
        if(find_str($url,$adr)<>-1) $flag_cek=1;
        if(find_str($url,$adr1)<>-1) $flag_cek=1;

        if($role<>0)
            return $this->redirect(['createproposal']);
        else {
            if($flag_cek==1)
                 return $this->redirect(['cnt_con']);
            else
                 return $this->redirect(['cabinet']);
        }

        $model = new InputDataForm();
        if ($model->load(Yii::$app->request->post()))
        {

           $model->power=str_replace(',','.',$model->power);
           // ($model->dist<=300 && $model->power<=50)
           if($model->type_connect==1)
            return $this->redirect(['calc','town' => $model->town,
                'power' => $model->power,'voltage' => $model->voltage,
                'q_phase' => $model->q_phase,'reliability' => $model->reliability]);
           else
              // Не стандартное присоединение
               return $this->redirect(['calc_nostd','town' => $model->town,
                   'power' => $model->power,'voltage' => $model->voltage,
                   'res' => $model->res,'energy' => $model->energy,
                   'place' => $model->place,'type_line' => $model->type_line,
                   'dist' => $model->dist,'reliability' => $model->reliability]);
            }
         else {
            return $this->render('inputdata', [
                'model' => $model,
            ]);
        }
    }

    public function actionCnt_con()
    {

        $model = new InputDataForm();
        if ($model->load(Yii::$app->request->post()))
        {

            $model->power=str_replace(',','.',$model->power);
            // ($model->dist<=300 && $model->power<=50)
            if($model->type_connect==1)
                return $this->redirect(['calc','town' => $model->town,
                    'power' => $model->power,'voltage' => $model->voltage,
                    'q_phase' => $model->q_phase,'reliability' => $model->reliability]);
            else
                // Не стандартное присоединение
                return $this->redirect(['calc_nostd','town' => $model->town,
                    'power' => $model->power,'voltage' => $model->voltage,
                    'res' => $model->res,'energy' => $model->energy,
                    'place' => $model->place,'type_line' => $model->type_line,
                    'dist' => $model->dist,'reliability' => $model->reliability]);
        }
        else {
            return $this->render('inputdata', [
                'model' => $model,
            ]);
        }
    }

    // Расчет стоимости подключения (происходит при нажатии на кн. OK если стандартное присоединение)
    public function actionCalc($town,$power,$voltage,$q_phase,$reliability)
    {
        $power=str_replace(',','.',$power);
        if($power<=50) {
            if ($town == 2) $town = 0;
            if ($power <= 16) $power_stage = 1;
            if ($power > 16 && $power <= 50) $power_stage = 2;
            $rank = 4 - $reliability;
            if ($q_phase == 2) $q_phase = 3;
            if ($voltage == 1) $v = 'a';
            if ($voltage == 2) $v = 'b';
            if ($voltage == 3) $v = 'c';
            if ($voltage == 4) $v = 'd';
            $u = 'u_' . $v . $q_phase;

            $sql = 'SELECT ' . $u . ' as cost FROM data_con WHERE town=:town and power_stage=:power_stage
                 and rank=:rank';

            $model = data_con::findBySql($sql, [':town' => $town,
                ':power_stage' => $power_stage, ':rank' => $rank])->all();
//            debug($model);
//            return;
            $cost1 = $model[0]->cost * $power;
            $cost = number_format($cost1, 2, ',', ' ');
            $cost_all = number_format($cost1 * 1.2, 2, ',', ' ');
            $cost_nds = number_format($cost1 * 0.2, 2, ',', ' ');

            return $this->render('resultCalc', ['model' => $model,
                'cost' => $cost, 'cost_all' => $cost_all,
                'cost_nds' => $cost_nds, 'town' => $town,
                'power' => $power, 'voltage' => $voltage,
                'reliability' => $reliability, 'q_phase' => $q_phase]);
        }
    }

    // Расчет стоимости подключения (происходит при нажатии на кн. OK если не стандартное присоединение)
    public function actionCalc_nostd($town,$power,$voltage,$res,$energy,$place,
                                     $type_line,$dist,$reliability)
    {

            $rank = 4 - $reliability;
            if(empty($dist)) $dist=0;
            if ($town == 2) $town=0;
            $place--;
            $sql = 'SELECT a.price as cost,b.price as cost_line,a.nazv,
                 a.energy,c.line FROM vw_data a 
                 left join data_line b on a.voltage=b.voltage
                 left join spr_line c on b.id_line=c.id
                 WHERE a.town=:town and a.vid_e=:vid_e
                 and a.category=:rank and a.rem=:res and b.place=:place and b.id_line=:type_line';

            $model = data_non_std::findBySql($sql, [':town' => $town,':vid_e' => $energy,
                 ':rank' => $rank,':res' => $res,':place' => $place,':type_line' => $type_line])->all();

//            debug($sql);
//            return;

            $cost1 = $model[0]->cost * $power;
            $cost2 = $model[0]->cost_line * $dist;
            $cost = number_format($cost1, 0, ' ', ' ');
            $cost_line = number_format($cost2, 0, ' ', ' ');
            $cost_all = number_format($cost1 * 1.2, 0, ' ', ' ');
            $cost_nds = number_format($cost1 * 0.2, 0, ' ', ' ');
            $cost_all_line = number_format($cost2 * 1.2, 0, ' ', ' ');
            $cost_nds_line = number_format($cost2 * 0.2, 0, ' ', ' ');

            $cost_total_all = number_format($cost1 * 1.2+$cost2 * 1.2, 0, ' ', ' ');
            $cost_total_nds = number_format($cost1 * 0.2+$cost2 * 0.2, 0, ' ', ' ');
            $cost_total = number_format($cost1+$cost2, 0, ' ', ' ');

            return $this->render('resultCalc_nostd', ['model' => $model,
                'cost' => $cost, 'cost_all' => $cost_all, 'cost_all_line' => $cost_all_line,
                'cost_nds_line' => $cost_nds_line,'cost_nds' => $cost_nds, 'town' => $town,
                'power' => $power, 'voltage' => $voltage,'cost_total_nds' => $cost_total_nds,
                'cost_total' => $cost_total,'cost_total_all' => $cost_total_all,
                'reliability' => $reliability, 'cost_line' => $cost_line,
                'dist' => $dist,'place' => $place
                ]);

    }
    
    //  Происходит при переключении версии сайта (Обычная и для слабовидящих)
    public function actionSwitch()
    {
        if (Yii::$app->session->has('switch')) {
            if(Yii::$app->session->get('switch')==1) 
                Yii::$app->session->set('switch', 0);
            else
                Yii::$app->session->set('switch', 1);
        }
        else  
        {
            $session = Yii::$app->session;
            $session->open();
            $session->set('switch', 1);
        }
        
        //return $this->goBack();
        if (\Yii::$app->request->referrer) {
            return $this->redirect(Yii::$app->request->referrer);
         } 
         else 
             {
            return $this->goBack();
         }
              
    }

    //  Происходит при входе в личный кабинет
    public function actionCabinet()
    {
        $model = new InputData_Cabinet();
        if ($model->load(Yii::$app->request->post()))
        {   $contract = $model->login;
            $date_contract = $model->password;
            $fs=substr($date_contract,0,1);
            $psw = substr($date_contract,-$fs);
            $q=6-$fs;
            $tel_i=substr($date_contract,1,$q);
            $tel=str_pad("9", $q, "9", STR_PAD_LEFT) - $tel_i ;
            $tel = str_pad($tel, $q, '0', STR_PAD_LEFT);
//            debug($tel);
//            return;
            
            $sql = 'select * from vw_proposal where contract='.'"'.$contract.'"'.
                    ' and date_contract='.'"'.$date_contract.'"';
            $sql = 'select * from vw_request where replace(replace(tel," ",""),"-","")='.'"'.$contract.'"'.
                ' and id='.$psw." and substr(replace(replace(tel,' ',''),'-',''),-$q,$q)=".'"'.$tel.'"';

//            debug($sql);
//            return;

             $info = createproposal::findBySql($sql)->one();
             if(empty($info->tel)){
                Yii::$app->session->setFlash('error','Неправильний логін або пароль');
                return $this->refresh ();}
             else    
             {
//                 Yii::$app->user->setState('date_contract', $date_contract);
                 return $this->redirect(['info_cabinet',
                'contract' => $contract,'date_contract' => $date_contract]);

//                 return $this->redirect(['info_cabinet',
//                     'model' => $model]);
             }
             }
         else {
            return $this->render('inputdata_cabinet', [
                'model' => $model,
            ]);
        }
    }

    //  Происходит при нажатии кнопки Створити ID ТУ
    public function actionCreate_tu()
    {
        $model = new Forma_tu();
        $id = Yii::$app->request->post('id');
        $id_tu = Yii::$app->request->post('id_tu');


        if ($model->load(Yii::$app->request->post()))
        {   $type_el = $model->name1;
             $type_c = $model->name2;
             $date_tu = $model->date1;
             if(empty($date_tu)) $date_tu='000000';
             else {
                 $date_tu = substr($date_tu,8,2).substr($date_tu,5,2).substr($date_tu,2,2);
             }

             $date_edit = $model->date2;
            if(empty($date_edit)) {
                $date_edit='000000';
                $change_flag='1';
            }
            else {
                $change_flag = '2';
                $date_edit = substr($date_edit,8,2).substr($date_edit,5,2).substr($date_edit,2,2);
            }

            $place_osr = '10';  // Константа для ЦЭК
            if($model->rem==1) $place_osr = '01';
            if($model->rem==2) $place_osr = '02';
            if($model->rem==3) $place_osr = '03';
            if($model->rem==4) $place_osr = '04';

             $n_osr = '31';  // Константа для ЦЭК


            $sql = 'select * from request where id='.$model->id;


            $info = request::findBySql($sql)->one();
//            debug($info);
//            return;
            $id_new = $info['nomer'];
//            $id_new = $info['id'];
            // Генерация ТУ
            $tu = 'ТУ'.str_pad($id_new, 6, "0", STR_PAD_LEFT).$date_tu.$type_el.$n_osr.
                $place_osr.$type_c.$date_edit.$change_flag;

            $info->id_tu = $tu;
            if(!$info->save(false))
            {  var_dump($info);return;}

            return $this->redirect(['site/upd1','id'=>$model->id,'mod'=>'schet']);
        }
        else {
            return $this->render('forma_tu', [
                'model' => $model,'id' => $id,'id_tu' => $id_tu
            ]);
        }
    }

    // Установки для программы
    public function actionSetprog($year)
    { // аргумент year - год, который выбирается в меню (Рік)
      if(1==2) {  // Сейчас отключено
          // Заполнение таблицы setprog
          for ($role = 4; $role < 6; $role++) {
              for ($year = 2021; $year < 2024; $year++) {
                  if ($year == 2022) $active = 1; else $active = 0;  // Установка текущего года как активного
                  $sql = "INSERT INTO setprog(year,active,role) select $year,$active,$role";
                  Yii::$app->db->createCommand($sql)->execute();
              }
          }
      }
//      return;

        if(!isset(Yii::$app->user->identity->role))
        {      $flag=0;}
        else{
            $role=Yii::$app->user->identity->role;
            $department=Yii::$app->user->identity->department;
        }

            $sql = "select * from setprog where year=$year and role=$role";
            $set_p = request::findBySql($sql)->one();
            $year_p = $set_p['year'];

            // Делаем активным выбранный год в меню Рік
            $sql = "UPDATE setprog set active=0 where role=$role";
            Yii::$app->db->createCommand($sql)->execute();
            $sql = "UPDATE setprog set active=1 where role=$role and year=$year_p";
            Yii::$app->db->createCommand($sql)->execute();
            return $this->goHome();
        }

    //  Происходит при нажатии кнопки Створити ID повідомлення
    public function actionCreate_msg()
    {
        $model = new Forma_tu();
        $id = Yii::$app->request->post('id');
        $id_msg = Yii::$app->request->post('id_msg');
        $id_tu = Yii::$app->request->post('id_tu');

   //        debug($id);
//        debug($id_tu);
//        return;

        if ($model->load(Yii::$app->request->post()))
        {
            $date_msg = $model->date3;
            if(empty($date_msg)) {
                $date_edit='000000';
                $change_flag='1';
            }
            else {
                $change_flag = '2';
                $date_edit = substr($date_msg,8,2).substr($date_msg,5,2).substr($date_msg,2,2);
            }

            // Генерация сообщения


            $sql = 'select * from request where id='.$model->id;

            $info = request::findBySql($sql)->one();
            if(empty($info->id_tu)) {
                $model = new info();
                $model->title = 'Створення скасовано';
                $model->info1 = "Спочатку потрібно створити ідентифікатор ТУ.";
                $model->style1 = "d15";
                $model->style2 = "info-text";
                $model->style_title = "d9";

                return $this->render('msg', [
                    'model' => $model]);
            }
            $id_tu = str_replace('ТУ', 'ПВ', $info->id_tu);
            $msg = mb_substr($id_tu,0,19,"UTF-8") . $date_edit;
//            debug($info);
//            return;

            $info->id_msg = $msg;
            if(!$info->save(false))
            {  var_dump($info);return;}

            return $this->redirect(['site/upd1','id'=>$model->id,'mod'=>'schet']);
        }
        else {
            return $this->render('forma_msg', [
                'model' => $model,'id' => $id,'id_msg' => $id_msg,
                'id_tu' => $id_tu
            ]);
        }
    }

    // Отображение личного кабинета
    public function actionInfo_cabinet($contract,$date_contract)
    {
        $fs=substr($date_contract,0,1);
        $psw = substr($date_contract,-$fs);

        $q=6-$fs;
        $tel_i=substr($date_contract,1,$q);
        $tel=str_pad("9", $q, "9", STR_PAD_LEFT) - $tel_i ;
        $tel = str_pad($tel, $q, '0', STR_PAD_LEFT);
//        $date_contract = Yii::$app->user->getState('date_contract');
//        $sql = 'select * from vw_proposal where contract='.'"'.$contract.'"'.
//                    ' and date_contract='.'"'.$date_contract.'"';
        $sql = 'select * from vw_request where replace(replace(tel," ",""),"-","")='.'"'.$contract.'"'.
            ' and id='.$psw." and substr(replace(replace(tel,' ',''),'-',''),-$q,$q)=".'"'.$tel.'"';
//        debug($sql);
//        return;
        $info = createproposal::findBySql($sql)->one();

        $doc_v = docs::find()->where('id_request=:id',[':id'=>$info->id])->all();
        return $this->render('info_cabinet', ['info' => $info,'doc_v'=>$doc_v]);
    }
    
    // Установка признака просмотра информации в личном кабинете
    public function actionCh($view,$u )
    {
        $sql = 'select * from vw_proposal where id_unique='.$u;
        $info = Viewproposal::findBySql($sql)->one(); 
        if($info->view==0){
            $info->view=1;
            $info->save(false);
        }
             
        return $this->render('info_cabinet', ['info' => $info]);
    }
    
     // Подача заявки на подключение
    public function actionProposal()
    {
        $model = new Klient();
        $ar_doc=[];
        $i=0;
        if ($model->load(Yii::$app->request->post()))
        {    
            $rand = rand();
            if($model->variant2==1){
               
                $model->doc1 = UploadedFile::getInstance($model,'doc1');;
                if($model->doc1) {
                    $model->upload('doc1',$rand);
                    $ar_doc[$i]=1;
                }
                $model->doc2 = UploadedFile::getInstance($model,'doc2');;
                if($model->doc2) {
                    $model->upload('doc2',$rand);
                    $i++;
                    $ar_doc[$i]=2;
                }
                $model->doc3 = UploadedFile::getInstance($model,'doc3');;
                if($model->doc3) {
                    $model->upload('doc3',$rand);
                    $i++;
                    $ar_doc[$i]=3;
                }
                $model->doc4 = UploadedFile::getInstance($model,'doc4');;
                if($model->doc4) {
                    $model->upload('doc4',$rand);
                    $i++;
                    $ar_doc[$i]=4;
                }
                $model->doc5 = UploadedFile::getInstance($model,'doc5');;
                if($model->doc5) {
                    $model->upload('doc5',$rand);
                    $i++;
                    $ar_doc[$i]=5;
                }
                
            }
            
            if($model->variant2==2){ 
            
                $model->doc1 = UploadedFile::getInstance($model,'doc1');;
                if($model->doc1) {
                    $model->upload('doc1',$rand);
                    $i++;
                    $ar_doc[$i]=1;
                }
                $model->doc2 = UploadedFile::getInstance($model,'doc2');;
                if($model->doc2) {
                    $model->upload('doc2',$rand);
                    $i++;
                    $ar_doc[$i]=2;
                }
                $model->doc3 = UploadedFile::getInstance($model,'doc3');;
                if($model->doc3) {
                    $model->upload('doc3',$rand);
                    $i++;
                    $ar_doc[$i]=3;
                }
                $model->doc4 = UploadedFile::getInstance($model,'doc4');;
                if($model->doc4) {
                    $model->upload('doc4',$rand);
                    $i++;
                    $ar_doc[$i]=4;
                }
                $model->doc5 = UploadedFile::getInstance($model,'doc5');;
                if($model->doc5) {
                    $model->upload('doc5',$rand);
                    $i++;
                    $ar_doc[$i]=5;
                }
                $model->doc6 = UploadedFile::getInstance($model,'doc6');;
                if($model->doc6) {
                    $model->upload('doc6',$rand);
                    $i++;
                    $ar_doc[$i]=6;
                }
            }
            
            if($model->variant2==3){
            
                $model->doc1 = UploadedFile::getInstance($model,'doc1');;
                if($model->doc1) {
                    $model->upload('doc1',$rand);
                    $i++;
                    $ar_doc[$i]=1;
                }
                $model->doc2 = UploadedFile::getInstance($model,'doc2');;
                if($model->doc2) {
                    $model->upload('doc2',$rand);
                    $i++;
                    $ar_doc[$i]=2;
                }
                $model->doc3 = UploadedFile::getInstance($model,'doc3');;
                if($model->doc3) {
                    $model->upload('doc3',$rand);
                    $i++;
                    $ar_doc[$i]=3;
                }
                $model->doc7 = UploadedFile::getInstance($model,'doc7');;
                if($model->doc7) {
                    $model->upload('doc7',$rand);
                    $i++;
                    $ar_doc[$i]=7;
                }
            }
            
            if($model->variant2==4){
            
                $model->doc1 = UploadedFile::getInstance($model,'doc1');;
                if($model->doc1) {
                    $model->upload('doc1',$rand);
                    $i++;
                    $ar_doc[$i]=1;
                }
                $model->doc2 = UploadedFile::getInstance($model,'doc2');;
                if($model->doc2) {
                    $model->upload('doc2',$rand);
                    $i++;
                    $ar_doc[$i]=2;
                }
                $model->doc3 = UploadedFile::getInstance($model,'doc3');;
                if($model->doc3) {
                    $model->upload('doc3',$rand);
                    $i++;
                    $ar_doc[$i]=3;
                }
                $model->doc6 = UploadedFile::getInstance($model,'doc6');;
                if($model->doc6) {
                    $model->upload('doc6',$rand);
                    $i++;
                    $ar_doc[$i]=6;
                }
            }
            
            if($model->variant2==5){
            
                $model->doc1 = UploadedFile::getInstance($model,'doc1');;
                if($model->doc1) {
                    $model->upload('doc1',$rand);
                    $i++;
                    $ar_doc[$i]=1;
                }
                $model->doc2 = UploadedFile::getInstance($model,'doc2');;
                if($model->doc2) {
                    $model->upload('doc2',$rand);
                    $i++;
                    $ar_doc[$i]=2;
                }
                $model->doc3 = UploadedFile::getInstance($model,'doc3');;
                if($model->doc3) {
                    $model->upload('doc3',$rand);
                    $i++;
                    $ar_doc[$i]=3;
                }
                $model->doc4 = UploadedFile::getInstance($model,'doc4');;
                if($model->doc4) {
                    $model->upload('doc4',$rand);
                    $i++;
                    $ar_doc[$i]=4;
                }
                $model->doc7 = UploadedFile::getInstance($model,'doc7');;
                if($model->doc7) {
                    $model->upload('doc7',$rand);
                    $i++;
                    $ar_doc[$i]=7;
                }
                $model->doc6 = UploadedFile::getInstance($model,'doc6');;
                if($model->doc6) {
                    $model->upload('doc6',$rand);
                    $i++;
                    $ar_doc[$i]=6;
                }
            }
            
            if(empty($model->doc1))
            {
                Yii::$app->session->setFlash('Error',"Файл не вибраний");
                return $this->refresh();
            }
            
            // Запись данных в базу
             $item_id1 = '';
             $item_id1 = $model->inn;

             $prop=new Proposal();
             $prop->inn=$model->inn;
             $prop->comment=$model->comment;
             $prop->adres=$model->search_town.' '.$model->search_street.' '.$model->adr_flat;
             $prop->type_doc=$model->variant2;
             $prop->new_doc=$model->variant1;
             $prop->status = 1;
             $prop->id_unique = $rand;
             $prop->save();

             $doc=new Docs();
             //$doc->id_unique = $rand;
             if($model->variant2==1){
                 $doc->id_doc = 1;
                 
                 $doc->file_path = $doc->id_doc.'_'.$rand.'-'.$model->doc1->name;
                 $doc->item_id = $item_id1;
                 $doc->id_unique = $rand;
                 $doc->save();
                 
                 $doc=new Docs();
                 $doc->id_doc = 2;
                 $doc->file_path = $doc->id_doc.'_'.$rand.'-'.$model->doc2->name;
                 $doc->id_unique = $rand;
                 $doc->item_id = $item_id1;
                 $doc->save();
                 $doc=new Docs();
                 $doc->id_doc = 3;
                 $doc->file_path = $doc->id_doc.'_'.$rand.'-'.$model->doc3->name;
                 $doc->id_unique = $rand;
                 $doc->item_id = $item_id1;
                 $doc->save();
                 $doc=new Docs();
                 $doc->id_doc = 4;
                 $doc->file_path = $doc->id_doc.'_'.$rand.'-'.$model->doc4->name;
                 $doc->id_unique = $rand;
                 $doc->item_id = $item_id1;
                 $doc->save();
                 $doc=new Docs();
                 $doc->id_doc = 5;
                 $doc->file_path = $doc->id_doc.'_'.$rand.'-'.$model->doc5->name;
                 $doc->id_unique = $rand;
                 $doc->item_id = $item_id1;
                 $doc->save();
             }
             if($model->variant2==2){
                 $doc->id_doc = 1;
                 $doc->file_path = $doc->id_doc.'_'.$rand.'-'.$model->doc1->name;
                 $doc->id_unique = $rand;
                 $doc->item_id = $item_id1;
                 $doc->save();
                 $doc=new Docs();
                 $doc->id_doc = 2;
                 $doc->file_path = $doc->id_doc.'_'.$rand.'-'.$model->doc2->name;
                 $doc->id_unique = $rand;
                 $doc->item_id = $item_id1;
                 $doc->save();
                 $doc=new Docs();
                 $doc->id_doc = 3;
                 $doc->file_path = $doc->id_doc.'_'.$rand.'-'.$model->doc3->name;
                 $doc->id_unique = $rand;
                 $doc->item_id = $item_id1;
                 $doc->save();
                 $doc=new Docs();
                 $doc->id_doc = 4;
                 $doc->file_path = $doc->id_doc.'_'.$rand.'-'.$model->doc4->name;
                 $doc->id_unique = $rand;
                 $doc->item_id = $item_id1;
                 $doc->save();
                 $doc=new Docs();
                 $doc->id_doc = 5;
                 $doc->file_path = $doc->id_doc.'_'.$rand.'-'.$model->doc5->name;
                 $doc->id_unique = $rand;
                 $doc->item_id = $item_id1;
                 $doc->save();
                 $doc=new Docs();
                 $doc->id_doc = 6;
                 $doc->file_path = $doc->id_doc.'_'.$rand.'-'.$model->doc6->name;
                 $doc->id_unique = $rand;
                 $doc->item_id = $item_id1;
                 $doc->save();
             }
              if($model->variant2==3){
                 $doc->id_doc = 1;
                  $doc->file_path = $doc->id_doc.'_'.$rand.'-'.$model->doc1->name;
                 
                 $doc->item_id = $item_id1;
                 $doc->id_unique = $rand;
                 $doc->save();
                 
                 $doc=new Docs();
                 $doc->id_doc = 2;
                 $doc->file_path = $doc->id_doc.'_'.$rand.'-'.$model->doc2->name;
                 $doc->item_id = $item_id1;
                 $doc->id_unique = $rand;
                 $doc->save();
                 
                 $doc=new Docs();
                 $doc->id_doc = 3;
                 $doc->file_path = $doc->id_doc.'_'.$rand.'-'.$model->doc3->name;
                 $doc->item_id = $item_id1;
                 $doc->id_unique = $rand;
                 $doc->save();
                 $doc=new Docs();
                 $doc->id_doc = 7;
                 $doc->file_path = $doc->id_doc.'_'.$rand.'-'.$model->doc7->name;
                 $doc->id_unique = $rand;
                 $doc->item_id = $item_id1;
                 $doc->save();
             }
              if($model->variant2==4){
                 $doc->id_doc = 1;
                 $doc->file_path = $doc->id_doc.'_'.$rand.'-'.$model->doc1->name;
                 $doc->id_unique = $rand;
                 $doc->item_id = $item_id1;
                 $doc->save();
                 $doc=new Docs();
                 $doc->id_doc = 2;
                 $doc->file_path = $doc->id_doc.'_'.$rand.'-'.$model->doc2->name;
                 $doc->id_unique = $rand;            
                 $doc->item_id = $item_id1;
                 $doc->save();
                 
                 $doc=new Docs();
                 $doc->id_doc = 3;
                 $doc->file_path = $doc->id_doc.'_'.$rand.'-'.$model->doc3->name;
                 $doc->item_id = $item_id1;
                 $doc->id_unique = $rand;
                 $doc->save();
                 
                 $doc=new Docs();
                 $doc->id_doc = 6;
                 $doc->file_path = $doc->id_doc.'_'.$rand.'-'.$model->doc6->name;
                 $doc->item_id = $item_id1;
                 $doc->id_unique = $rand;
                 $doc->save();
             }
              if($model->variant2==5){
                
                 $doc->id_doc = 1;
                  $doc->file_path = $doc->id_doc.'_'.$rand.'-'.$model->doc1->name;
                 $doc->item_id = $item_id1;
                 $doc->save();
                 $doc=new Docs();
                 
                 $doc->id_doc = 2;
                 $doc->file_path = $doc->id_doc.'_'.$rand.'-'.$model->doc2->name;
                 $doc->item_id = $item_id1;
                 $doc->save();
                 $doc=new Docs();
                 
                 $doc->id_doc = 3;
                 $doc->file_path = $doc->id_doc.'_'.$rand.'-'.$model->doc3->name;
                 $doc->item_id = $item_id1;
                 $doc->save();
                 $doc=new Docs();
                 
                 $doc->id_doc = 7;
                 $doc->file_path = $doc->id_doc.'_'.$rand.'-'.$model->doc7->name;
                 $doc->item_id = $item_id1;
                 $doc->save();
                 $doc=new Docs();
                 
                 $doc->id_doc = 6;
                 $doc->file_path = $doc->id_doc.'_'.$rand.'-'.$model->doc6->name;
                 $doc->item_id = $item_id1;
                 $doc->save();
                 $doc=new Docs();
                  $doc->id_doc = 4;
                 $doc->file_path = $doc->id_doc.'_'.$rand.'-'.$model->doc4->name;
                 $doc->item_id = $item_id1;
                 $doc->save();
             }
            $model->id_unique = $rand; 
            $model->save(false);
            $is=0;
            // Отправка письма на отдел ОПР о появлении новой заявки
            $mail_cek = 'zamovlennya@cek.dp.ua';
             Yii::$app->mailer->compose()
                ->setFrom('usluga@cek.dp.ua')
                ->setTo($mail_cek)
                ->setSubject('Нова заявка на підключення в сервісі особовий кабінет підключення')
                ->setHtmlBody('<b>Увага! У Вас з`явилась нова заявка на підключення з'
                        . ' особового кабінета підключення.'
                        . '<a target="_blank" href="https://cek.dp.ua/Connect/cek"> Перейти в адміністрування особовим кабінетом.</a> ')
                ->send();
            return $this->render('prop_ok', [
            'is' => $is
        ]);
        }
        else {

            return $this->render('inputregistr', [
                'model' => $model,
            ]);
        }
    }
    
    // Подгрузка населенных пунктов - происходит при наборе первых букв
    public function actionGet_search_town($name)
    {

        Yii::$app->response->format = Response::FORMAT_JSON;
                
        $name1 = mb_strtolower($name,"UTF-8");
        $name2 = mb_strtoupper($name,"UTF-8");
        if (Yii::$app->request->isAjax) {
            $sql = 'select min(id) as id,district,town from spr_towns where town like '.'"%'.$name1.'%"'.
                    ' and length('.'"'.$name1.'")>2'.' group by district,town order by town,district';
             $cur = spr_towns::findBySql($sql)->all();
//             var_dump($sql);

            return ['success' => true, 'cur' => $cur];

        }
    }

    // Определяем населенные пункты, найденные после ввода поискового адреса,
    // необходимо для поиска на карте по введенному адресу
    public function actionGetloc($loc,$key,$address) {
        Yii::$app->response->format = Response::FORMAT_JSON;
        if (Yii::$app->request->isAjax) {
            $address = str_replace(' ', '+', $address);
            $loc = $loc . '&key='.$key.'&address='.$address;
            $loc = $loc . '&language=ru&region=UA';
            $ch = curl_init($loc);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $output = curl_exec($ch);
            $s = curl_error ($ch);
            curl_close($ch);
            $output = json_decode($output,true);
            return ['success' => true, 'output' => $output];
        }
    }

    // Определение расстояния до ближайшей подстанции от заданных гео-координат
    public function actionGeo_dist($lat,$lng)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        if (Yii::$app->request->isAjax) {
//            $sql = "select tpid,address,ST_Distance( gpoint::geography, ST_GeomFromEWKT('SRID=4326;POINT($lng $lat)')::geography ) as dist,
//            substr(substr(ST_AsText(gpoint),10),1,33) as koord
//            from tp where
//            ST_Distance( gpoint::geography, ST_GeomFromEWKT('SRID=4326;POINT($lng $lat)')::geography )=
//            (select min(ST_Distance( gpoint::geography, ST_GeomFromEWKT('SRID=4326;POINT($lng $lat)')::geography )) as dist from tp)";
//            $sql = "select q.obj,q.id,q.techplace,q.dist,q.koord,p.address as tp_name,p.dist as tp_dist,p.koord as korrd_tp from
//            (select 'Опора' as obj,epillarid as id,techplace,ST_Distance( gpoint::geography, ST_GeomFromEWKT('SRID=4326;POINT($lng $lat)')::geography ) as dist,
//            substr(substr(ST_AsText(gpoint),7),1,33) as koord from epillar
//            where
//            ST_Distance( gpoint::geography, ST_GeomFromEWKT('SRID=4326;POINT($lng $lat)')::geography )=
//                (select min(ST_Distance( gpoint::geography, ST_GeomFromEWKT('SRID=4326;POINT($lng $lat)')::geography )) as dist from epillar)
//
//            union
//             select 'Підстанція' as obj,tpid as id,address,ST_Distance( gpoint::geography, ST_GeomFromEWKT('SRID=4326;POINT($lng $lat)')::geography ) as dist,
//                        substr(substr(ST_AsText(gpoint),10),1,33) as koord
//                        from tp where
//                        ST_Distance( gpoint::geography, ST_GeomFromEWKT('SRID=4326;POINT($lng $lat)')::geography )=
//                            (select min(ST_Distance( gpoint::geography, ST_GeomFromEWKT('SRID=4326;POINT($lng $lat)')::geography )) as dist from tp)
//            ) as q
//            left join
//                        (select 'Підстанція' as obj,tpid as id,address,ST_Distance( gpoint::geography, ST_GeomFromEWKT('SRID=4326;POINT($lng $lat)')::geography ) as dist,
//                        substr(substr(ST_AsText(gpoint),10),1,33) as koord
//                        from tp where
//                        ST_Distance( gpoint::geography, ST_GeomFromEWKT('SRID=4326;POINT($lng $lat)')::geography )=
//                            (select min(ST_Distance( gpoint::geography, ST_GeomFromEWKT('SRID=4326;POINT($lng $lat)')::geography )) as dist from tp)) p
//              on 1=1 and q.obj='Опора'
//            order by dist
//            limit 1";

            $sql = "select q.obj,q.id,q.lname,q.techplace,q.dist,q.koord,p.address as tp_name,p.dist as tp_dist from
            (select 'Опора' as obj,lineid as id ,lname,techplace,ST_Distance( gline::geography, ST_GeomFromEWKT('SRID=4326;POINT($lng $lat)')::geography ) as dist,
            substr(substr(ST_AsText(gline),7),1,33) as koord from eline
            where 
            ST_Distance( gline::geography, ST_GeomFromEWKT('SRID=4326;POINT($lng $lat)')::geography )=
                (select min(ST_Distance( gline::geography, ST_GeomFromEWKT('SRID=4326;POINT($lng $lat)')::geography )) as dist from eline)
            
            union
             select 'Підстанція' as obj,tpid as id,'' as lname,address,ST_Distance( gpoint::geography, ST_GeomFromEWKT('SRID=4326;POINT($lng $lat)')::geography ) as dist,
                        substr(substr(ST_AsText(gpoint),10),1,33) as koord
                        from tp where 
                        ST_Distance( gpoint::geography, ST_GeomFromEWKT('SRID=4326;POINT($lng $lat)')::geography )=
                            (select min(ST_Distance( gpoint::geography, ST_GeomFromEWKT('SRID=4326;POINT($lng $lat)')::geography )) as dist from tp)
            ) as q
            left join
                        (select 'Підстанція' as obj,tpid as id,address,ST_Distance( gpoint::geography, ST_GeomFromEWKT('SRID=4326;POINT($lng $lat)')::geography ) as dist,
                        substr(substr(ST_AsText(gpoint),10),1,33) as koord
                        from tp where 
                        ST_Distance( gpoint::geography, ST_GeomFromEWKT('SRID=4326;POINT($lng $lat)')::geography )=
                            (select min(ST_Distance( gpoint::geography, ST_GeomFromEWKT('SRID=4326;POINT($lng $lat)')::geography )) as dist from tp)) p
              on 1=1 and q.obj='Опора'      
            order by dist
            limit 1";

            $cur = gis_tp::findBySql($sql)->asArray()->all();
            //var_dump($cur);

            return ['success' => true, 'cur' => $cur];

        }
    }


    // Подгрузка улиц - происходит при наборе первых букв
    public function actionGet_search_street($name,$str)
    {

        Yii::$app->response->format = Response::FORMAT_JSON;
                
        $name1 = mb_strtolower($name,"UTF-8");
        $name2 = mb_strtoupper($name,"UTF-8");
        $n = strpos($str, ',');
        $town=substr($str,0,$n);
        $n1 = strpos($str, 'р-н');
        $district=trim(substr($str,$n+1,($n1-$n-1)));
        if (Yii::$app->request->isAjax) {
            $sql = 'select min(id) as id,street from spr_towns where street like '.'"% '.$name1.'%"'.
                    ' and length('.'"'.$name1.'")>3'.' and town='.'"'.$town.'"'.
                    ' and district='.'"'.$district.'"'.
                    ' group by street order by street';
             $cur = spr_towns::findBySql($sql)->all();

            return ['success' => true, 'cur' => $cur];

        }
    }

    // Формирование инф. сообщения для исполнителя
    public function actionInfo_exec()
    {
        $sch = Yii::$app->request->post('sch');
        $mail = Yii::$app->request->post('mail');
        $sql = 'select a.*,b.Director,b.parrent_nazv,b.mail from vschet a,spr_res b'
            . ' where a.res=b.nazv and schet=:search';
        $model = viewschet::findBySql($sql,[':search'=>"$sch"])->one();
        return $this->render('info_exec',['model' => $model,'style_title' => 'd9','mail' => $mail]);
    }

    // Просмотр заявок на подключение
    public function actionViewproposal($item='')
    {
        $searchModel = new viewproposal();
       
        $flag=1;
        $role=0;
        if(!isset(Yii::$app->user->identity->role))
        {      $flag=0;}
        else{
            $role=Yii::$app->user->identity->role;
        }

        switch($role) {
             case 3: // Полный доступ
                $data = $searchModel::find()->orderBy(['status' => SORT_ASC])->all();
                break;
             case 2:  // финансовый отдел
                $data = $searchModel::find()->where('status=:status',[':status' => 2])->
                orderBy(['status' => SORT_ASC])->all();
                break;
             case 1:  // бухгалтерия
                $data = $searchModel::find()->where('status=:status',[':status' => 5])->
                orderBy(['status' => SORT_ASC])->all();
                break;
        }

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$role);

       
        if (Yii::$app->request->get('item') == 'Excel' )
        {
            $newQuery = clone $dataProvider->query;
            $models = $newQuery->orderby(['date' => SORT_DESC])->all();
            $kind=1;
            $k1 = 'Інформація по рахункам';
//             Сброс в Excel
            if($kind==1){
                \moonland\phpexcel\Excel::widget([
                    'models' => $models,
                   
                    'mode' => 'export', //default value as 'export'
                    'format' => 'Excel2007',
                    'hap' => $k1,    //cтрока шапки таблицы
                    'data_model' => 1,
                    'columns' => ['status_sch','inn','nazv','addr','tel','schet','contract',
                        'usluga','summa','summa_beznds','summa_work','summa_delivery','summa_transport','res','date'],
                    'headers' => ['status_sch' => 'Cтатус заявки','inn' => 'ІНН','nazv' => 'Споживач','addr'=> 'Адрес','tel' => 'Телефон',
                        'schet' => 'Рахунок','contract' => '№ договору', 'usluga' => 'Послуга','summa' => 'Сума,грн.:','summa_beznds' => 'Сума без ПДВ,грн.:',
                        'summa_work' => 'Вартість робіт,грн.:','summa_delivery' => 'Доставка бригади,грн.:',
                        'summa_transport' => 'Транспорт всього,грн.:',
                        'res' => 'Виконавча служба:','date' => 'Дата'],
                ]);}
            return;
        }

        return $this->render('viewproposal', [
            'model' => $searchModel,'dataProvider' => $dataProvider,'searchModel' => $searchModel,
        ]);
    }

    // Просмотр отчета НКРЕ
    public function actionReport_nkre()
    {
        $searchModel = new Report_nkre();

        $flag=1;
        $role=0;
        if(!isset(Yii::$app->user->identity->role))
        {      $flag=0;}
        else{
            $role=Yii::$app->user->identity->role;
        }

//        switch($role) {
//            case 3: // Полный доступ
//                $data = $searchModel::find()->orderBy(['status' => SORT_ASC])->all();
//                break;
//            case 2:  // финансовый отдел
//                $data = $searchModel::find()->where('status=:status',[':status' => 2])->
//                orderBy(['status' => SORT_ASC])->all();
//                break;
//            case 1:  // бухгалтерия
//                $data = $searchModel::find()->where('status=:status',[':status' => 5])->
//                orderBy(['status' => SORT_ASC])->all();
//                break;
//        }

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination = false;

        return $this->render('report_nkre', [
            'model' => $searchModel,'dataProvider' => $dataProvider,'searchModel' => $searchModel,
        ]);
    }

    // Создание заявок на подключение
    public function actionCreateproposal($item='')
    {
        $searchModel = new createproposal();

        $flag=1;
        $role=0;
        // Узнаем идентификатор пользователя
        if(!isset(Yii::$app->user->identity->role))
        {      $flag=0;}
        else{
            $role=Yii::$app->user->identity->role;  // идентификатор пользователя
        }

        $sql = "select * from setprog where active=1 and role=$role";
        $set_p = request::findBySql($sql)->one();
        $year_p = $set_p['year']; // год, который выбирается в меню (Рік)

        switch($role) {
            case 3: // Полный доступ
                $data = $searchModel::find()->where('year(date)=:year',[':year' => $year_p])
                    ->orderBy(['date' => SORT_DESC,'time'=>SORT_DESC])->all();
                break;
            case 11:  // ДНРЕМ
                $data = $searchModel::find()->where('role=:role',[':role' => 11])->
                orwhere('rem=:rem',[':rem' => 1])->andwhere('year(date)=:year',[':year' => $year_p])->
                orderBy(['date' => SORT_DESC,'time'=>SORT_DESC])->all();
                break;
            case 12:  // ЖВРем
                $data = $searchModel::find()->where('role=:role',[':role' => 12])->
                orwhere('rem=:rem',[':rem' => 2])->andwhere('year(date)=:year',[':year' => $year_p])->
                orderBy(['date' => SORT_DESC,'time'=>SORT_DESC])->all();
                break;
            case 14:  // Павлоград
                $data = $searchModel::find()->where('role=:role',[':role' => 14])->
                orwhere('rem=:rem',[':rem' => 4])->andwhere('year(date)=:year',[':year' => $year_p])->
                orderBy(['date' => SORT_DESC,'time'=>SORT_DESC])->all();
                break;
            case 13:  // Вг
                $data = $searchModel::find()->where('role=:role',[':role' => 13])->
                orwhere('rem=:rem',[':rem' => 2])->andwhere('year(date)=:year',[':year' => $year_p])->
                orderBy(['date' => SORT_DESC,'time'=>SORT_DESC])->all();
                break;
            case 15:  // Крг Рем
                $data = $searchModel::find()->where('role=:role',[':role' => 15])->
                orwhere('rem=:rem',[':rem' => 3])->andwhere('year(date)=:year',[':year' => $year_p])->
                orderBy(['date' => SORT_DESC,'time'=>SORT_DESC])->all();
                break;
            case 16:  // Ап
                $data = $searchModel::find()->where('role=:role',[':role' => 16])->
                orwhere('rem=:rem',[':rem' => 3])->andwhere('year(date)=:year',[':year' => $year_p])->
                orderBy(['date' => SORT_DESC,'time'=>SORT_DESC])->all();
                break;
            case 17:  // Гвардійске
                $data = $searchModel::find()->where('role=:role',[':role' => 17])->
                orwhere('rem=:rem',[':rem' => 4])->andwhere('year(date)=:year',[':year' => $year_p])->
                orderBy(['date' => SORT_DESC,'time'=>SORT_DESC])->all();
                break;
            case 18:  // Інгулець
                $data = $searchModel::find()->where('role=:role',[':role' => 18])->
                orwhere('rem=:rem',[':rem' => 3])->andwhere('year(date)=:year',[':year' => $year_p])->
                orderBy(['date' => SORT_DESC,'time'=>SORT_DESC])->all();
                break;
            case 4:
                // Полный доступ
                $data = $searchModel::find()->where('year(date)=:year',[':year' => $year_p])->
                orderBy(['date' => SORT_DESC,'time'=>SORT_DESC])->all();
                break;
            case 5:
                // Полный доступ
                $data = $searchModel::find()->where('year(date)=:year',[':year' => $year_p])->
                orderBy(['date' => SORT_DESC,'time'=>SORT_DESC])->all();
                break;
        }

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$role,$year_p);
        $dataProvider->pagination = false;

        if (Yii::$app->request->get('item') == 'Excel' )
        {
            $newQuery = clone $dataProvider->query;
            $models = $newQuery->orderby(['id' => SORT_DESC])->all();
            $kind=1;
            $k1 = 'Інформація по заявкам';
//             Сброс в Excel
            if($kind==1){
                \moonland\phpexcel\Excel::widget([
                    'models' => $models,

                    'mode' => 'export', //default value as 'export'
                    'format' => 'Excel2007',
                    'hap' => $k1,    //cтрока шапки таблицы
                    'data_model' => 1,
                    'columns' => ['id','id_tu','nazv_status','nazv','adres_con','tel','opl',
                        'date_z','department','date_f','time'],
                    'headers' => ['id' => 'ID','id_tu' => 'Ідентифікатор ТУ','nazv_status' => 'Статус замовлення',
                        'nazv' => 'Замовник','adres_con' => 'Адреса виконання робіт','tel' => 'Телефон',
                        'opl' => 'Оплата','date_z' => 'Дата виконання',
                        'department' => 'Користувач', 'date_f' => 'Дата заявки','time' => 'Час'],
                ]);}
            return;
        }

        return $this->render('createproposal', [
            'model' => $searchModel,'dataProvider' => $dataProvider,'searchModel' => $searchModel,
        ]);
    }

     // Запись данных в файл
    public function actionTofile()
    {
    $model = new tofile();

    if (Yii::$app->request->isPost) {
      $file = \yii\web\UploadedFile::getInstance($model, 'file');
    var_dump($file);
    return;
    $file->saveAs(\Yii::$app->basePath . $file);
    }
    return $this->render('tofile', ['model' => $model]);
    }  
    
    public function actionDownload($f)
    {
        $file = Yii::getAlias($f);
        return Yii::$app->response->sendFile($file);
    }

//    Страница о программе
    public function actionAbout()
    {
        $model = new info();
        $model->title = 'Про програму';
        $model->info1 = "Ця програма здійснює розрахунок робіт відповідно вибраному виду роботи, а також транспортні витрати.";
        $model->style1 = "d15";
        $model->style2 = "info-text";
        $model->style_title = "d9";

        return $this->render('about', [
            'model' => $model]);
    }

    //    Сброс в Excel результатов рассчета
    public function actionExcel($kind,$nazv,$rabota,$delivery,$transp,$all,$nds,$all_nds)
    {
        $k1='Результат розрахунку для послуги: '.$nazv;
        $param = 0;
        $model = new forExcel();
        $model->nazv = $nazv;
        $model->rabota = $rabota;
        $model->delivery = $delivery;
        $model->transp = $transp;
        $model->all = $all;
        $model->nds = $nds;
        $model->all_nds = $all_nds;
        if ($kind == 1) {
            \moonland\phpexcel\Excel::widget([
                'models' => $model,
                'mode' => 'export', //default value as 'export'
                'format' => 'Excel2007',
                'hap' => $k1, //cтрока шапки таблицы'hap' => $k1,
                'data_model' => $param,
                'columns' => ['nazv', 'rabota', 'delivery', 'transp', 'all', 'nds', 'all_nds',
                ],
            ]);
        }
        if ($kind == 2) {
            \moonland\phpexcel\Excel::widget([
                'models' => $model,
                'mode' => 'export', //default value as 'export'
                'format' => 'Excel2007',
                'hap' => $k1, //cтрока шапки таблицы'hap' => $k1,
                'data_model' => $param,
                'columns' => ['nazv', 'all', 'nds', 'all_nds',
                ],
            ]);
        }
        return;
    }

//    Обновление статуса заявки на подключение
    public function actionUpd($id,$mod)
    {
        // $id  id записи
        // $mod - название модели
        if($mod=='schet')
            $model = viewproposal::find()->where('id=:id',[':id'=>$id])->one();
            $nazv = $model->schet;
            $inn = $model->inn;
            $mail = $model->email;
                       
            $message_old=$model->message;
            $contract_old=$model->contract;
            $date_contract_old=$model->date_contract;
            $status_old=$model->status;
            $adres_old=$model->adres;
            $date_z_old=$model->date_z;
            $opl_old=$model->opl;
            
            if(!empty($model->date))
                $model->date = date("d.m.Y", strtotime($model->date));
            
            if(!empty($model->date_z))
                $model->date_z = date("d.m.Y", strtotime($model->date_z));
        
        if ($model->load(Yii::$app->request->post()))
        {
            
             $message = $model->message;
            // Выявление изменения данных
            $priz_upd=0;
            $priz_contract=0;
            $priz_status=0;
            $upd=[];
            $i=0;
            if($message_old!=$model->message) {
                $priz_upd=1;
                $upd[$i]='message';
                $i++;
            }    
            if($contract_old!=$model->contract) {
                $priz_upd=1;
                $priz_contract=1;
                $upd[$i]='contract';
                $i++;
            }    
            if($date_contract_old!=$model->date_contract) {
                $priz_upd=1;
                $priz_contract=1;
                $upd[$i]='date_contract';
                $i++;
            }    
            if($adres_old!=$model->adres) {
                $priz_upd=1;
                $upd[$i]='adres';
                $i++;
            }    
            if($status_old!=$model->status) {
                $priz_upd=1;
                $priz_status=1;
                $upd[$i]='status';
                $i++;
            }    
            if(date("Y-m-d", strtotime($date_z_old))!=date("Y-m-d", strtotime($model->date_z))) {
                $priz_upd=1;
                $upd[$i]='date_z';
                $i++;
            }
            if($opl_old!=$model->opl) {
                $priz_upd=1;
                $upd[$i]='opl';
                $i++;
            }
            
            $model1 = proposal::find()->where('id=:id',[':id'=>$id])->one();
            $model1->status = $model->status;
            $model1->adres = $model->adres;
            if(!empty($model->date_z))
                $model1->date_z = date("Y-m-d", strtotime($model->date_z));
            if(!empty($model->date_opl))
                $model1->date_opl = date("Y-m-d", strtotime($model->date_opl));
            if(!empty($model->date_exec))
                $model1->date_exec = date("Y-m-d", strtotime($model->date_exec));
            $model1->comment = $model->comment;
            $model1->contract = $model->contract;
            $model1->date_contract = $model->date_contract;
            $model1->adres = $model->adres;
            $model1->opl = $model->opl;
            $model1->message = $message;
            if($priz_upd==1){
                // Записываем в таблицу proposal.new_inf имена полей, которые изменились через запятую
                if(empty($model1->new_inf)){
                    $model1->new_inf = implode(",",$upd);
                }    
                else {
                    $model1->new_inf = $model1->new_inf.','.implode(",",$upd);
                }
                
            }            
            if(!$model1->save(false))
            {  var_dump($model1);return;}

            // Отправка письма потребителю при изменении информации
            if($priz_upd==1 && $priz_contract==0){
                 
                if($priz_status==0)
                Yii::$app->mailer->compose()
                ->setFrom('usluga@cek.dp.ua')
                ->setTo($mail)
                ->setSubject('Нове повідомлення в особовому кабінеті підключення від ПрАТ «ПЕЕМ «ЦЕК»')
                ->setHtmlBody('<b>Увага! У Вас з`явилось нове повідомлення'
                        . ' в особовому кабінеті підключення від ПрАТ «ПЕЕМ «ЦЕК».'
                        . '<a target="_blank" href="https://cek.dp.ua/Connect/web/cabinet/"> Перейти в особовий кабінет.</a> ')
                ->send();
                if($priz_status==1)
                Yii::$app->mailer->compose()
                ->setFrom('usluga@cek.dp.ua')
                ->setTo($mail)
                ->setSubject('Нове повідомлення в особовому кабінеті підключення від ПрАТ «ПЕЕМ «ЦЕК»')
                ->setHtmlBody('<b>Увага! У Вас з`явилось нове повідомлення'
                        . ' в особовому кабінеті підключення від ПрАТ «ПЕЕМ «ЦЕК». Змінився статус підключення.'
                        . '<a target="_blank" href="https://cek.dp.ua/Connect/web/cabinet/"> Перейти в особовий кабінет.</a> ')
                ->send();
            }
            
            if($priz_contract==1){
                 // Если присвоен новый № договора
                Yii::$app->mailer->compose()
                ->setFrom('usluga@cek.dp.ua')
                ->setTo($mail)
                ->setSubject('Нове повідомлення в особовому кабінеті підключення від ПрАТ «ПЕЕМ «ЦЕК»')
                ->setHtmlBody('<b>Увага! Вам присвоєно номер договору '.$model->contract.' від '.
                        date("d.m.Y", strtotime($model->date_contract)).'.'
                        . ' Це є дані для входу в особовий кабінет підключення від ПрАТ «ПЕЕМ «ЦЕК».')
                ->send();
            }
            
            if($mod=='schet')
                return $this->redirect(['site/viewproposal']);

        } else {
            if($mod=='schet')
                return $this->render('update_proposal', [
                    'model' => $model,'nazv' => $nazv,'mail'=> $mail
                ]);
        }
    }

    //    Редактирование заказов на подключение
    public function actionUpd1($id,$mod)
    {
        // $id  id записи
        // $mod - название модели
        $ar_doc=[];
        $i=0;
        $rand = rand();

        if($mod=='schet')
            $model = createproposal::find()->where('id=:id',[':id'=>$id])->one();

        $doc_v = docs::find()->where('id_request=:id',[':id'=>$id])->all();

//        debug($doc_v);
//        return;

        $model->doc1 = UploadedFile::getInstance($model,'doc1');
        $u1 = $model->doc1;
        $model->doc2 = UploadedFile::getInstance($model,'doc2');
        $u2 = $model->doc2;
        $model->doc3 = UploadedFile::getInstance($model,'doc3');
        $u3 = $model->doc3;
        $model->doc4 = UploadedFile::getInstance($model,'doc4');
        $u4 = $model->doc4;
        $model->doc5 = UploadedFile::getInstance($model,'doc5');
        $u5 = $model->doc5;

//        debug($model->doc1);
//        return;
        if($model->doc1) {
            $model->upload('doc1',$rand);
            $ar_doc[$i]=1;
        }
        if($model->doc2) {
            $model->upload('doc2',$rand);
            $i++;
            $ar_doc[$i]=1;
        }
        if($model->doc3) {
            $model->upload('doc3',$rand);
            $i++;
            $ar_doc[$i]=1;
        }
        if($model->doc4) {
            $model->upload('doc4',$rand);
            $i++;
            $ar_doc[$i]=1;
        }
        if($model->doc5) {
            $model->upload('doc5',$rand);
            $i++;
            $ar_doc[$i]=1;
        }
//        if(empty($model->doc1))
//        {
//            Yii::$app->session->setFlash('Error',"Файл не вибраний");
//            return $this->refresh();
//        }

        $nazv = $model->schet;
        $inn = $model->inn;
        $mail = $model->email;
        $edrpo = $model->edrpo;

        $message_old=$model->message;
        $contract_old=$model->contract;
        $date_contract_old=$model->date_contract;
        $status_old=$model->status;
        $adres_old=$model->adres;
        $date_z_old=$model->date_z;
        $opl_old=$model->opl;

        if(!empty($model->date))
            $model->date = date("d.m.Y", strtotime($model->date));

        if(!empty($model->date_z))
            $model->date_z = date("d.m.Y", strtotime($model->date_z));

        if ($model->load(Yii::$app->request->post()))
        {

            $message = $model->message;
            // Выявление изменения данных
            $priz_upd=0;
            $priz_contract=0;
            $priz_status=0;
            $upd=[];
            $i=0;
            if($message_old!=$model->message) {
                $priz_upd=1;
                $upd[$i]='message';
                $i++;
            }
            if($contract_old!=$model->contract) {
                $priz_upd=1;
                $priz_contract=1;
                $upd[$i]='contract';
                $i++;
            }
            if($date_contract_old!=$model->date_contract) {
                $priz_upd=1;
                $priz_contract=1;
                $upd[$i]='date_contract';
                $i++;
            }
            if($adres_old!=$model->adres) {
                $priz_upd=1;
                $upd[$i]='adres';
                $i++;
            }
            if($status_old!=$model->status) {
                $priz_upd=1;
                $priz_status=1;
                $upd[$i]='status';
                $i++;
            }
            if(date("Y-m-d", strtotime($date_z_old))!=date("Y-m-d", strtotime($model->date_z))) {
                $priz_upd=1;
                $upd[$i]='date_z';
                $i++;
            }
            if($opl_old!=$model->opl) {
                $priz_upd=1;
                $upd[$i]='opl';
                $i++;
            }

            $model1 = request::find()->where('id=:id',[':id'=>$id])->one();

            $model1->status = $model->status;
            if(!empty($model->date_z))
                $model1->date_z = date("Y-m-d", strtotime($model->date_z));
            if(!empty($model->date1))
                $model1->date1 = date("Y-m-d", strtotime($model->date1));
            if(!empty($model->date2))
                $model1->date2 = date("Y-m-d", strtotime($model->date2));
            if(!empty($model->date3))
                $model1->date3 = date("Y-m-d", strtotime($model->date3));
            if(!empty($model->date4))
                $model1->date4 = date("Y-m-d", strtotime($model->date4));
            if(!empty($model->date5))
                $model1->date5 = date("Y-m-d", strtotime($model->date5));
            if(!empty($model->date6))
                $model1->date6 = date("Y-m-d", strtotime($model->date6));
            if(!empty($model->date7))
                $model1->date7 = date("Y-m-d", strtotime($model->date7));
            if(!empty($model->date_opl))
                $model1->date_opl = date("Y-m-d", strtotime($model->date_opl));
            else
                $model1->date_opl = '';
            if(!empty($model->date_exec))
                $model1->date_exec = date("Y-m-d", strtotime($model->date_exec));
            $model1->comment = $model->comment;
            $model1->contract = $model->contract;
            $model1->date_contract = $model->date_contract;
            $model1->adres = $model->adres_con;
            $model1->tel_con = $model->tel_con;
            $model1->opl = $model->opl;
            $model1->mark = $model->mark;
            $model1->message = $message;
            $model1->rem = $model->rem;
            $model1->id_tu = $model->id_tu;

            // Сохранение клиентских данных
            if(empty($model->new_cl)) {
                $client = client::find()->where('id=:id',[':id'=>$model1->id_client])
                   ->one();
                if(!empty($model->inn)) {
                    $sql = "select * from client where trim(inn)=$inn";
                }
                if(!empty($model->edrpo)) {
                    $sql = "select * from client where trim(edrpo)=$edrpo";
                }
             }
            else {
                $client = new client();
                if(!empty($model->inn)) {
                    $sql = "select max(id) as id from client where trim(inn)=$inn";
                }
                if(!empty($model->edrpo)) {
                    $sql = "select max(id) as id from client where trim(edrpo)=$edrpo";
                }
            }
            $client->inn=$model->inn;
            $client->edrpo=$model->edrpo;
            $client->name=$model->nazv;
            $client->adres=$model->adres;
            $client->tel=$model->tel_con;
            $client->e_mail=$model->email;

            if(empty($model->inn) && empty($model->edrpo)) {
                $model = new info();
                $model->title = 'Створення скасовано';
                $model->info1 = "потрібно ввести ІПН або ЄДРПОУ.";
                $model->style1 = "d15";
                $model->style2 = "info-text";
                $model->style_title = "d9";

                return $this->render('msg', [
                    'model' => $model]);
            }

            if(!$client->save(false))
            {
                var_dump($client);
                return;
            }
            $client = client::findBySql($sql)->one();
            $model1->id_client = $client->id;

            if(!$model1->save(false))
            {
                var_dump($model1);
                return;
            }

//  Сохранение документов
            $item_id1 = '';
            $item_id1 = $client->inn;

//            debug($u1);
//            return;

            if(isset($u1->name)) {
                $sql = 'select a.*from docs a'
                    . ' where a.id_doc=:search_doc and a.id_request=:search_id';
                $docs_d = docs::findBySql($sql, [':search_doc' => 1, ':search_id' => $id])->one();
                if (!empty($docs_d)) $docs_d->delete();

                $doc = new Docs();
                $doc->id_doc = 1;

                $doc->file_path = $doc->id_doc . '_' . $rand . '-' . $u1->name;
                $doc->item_id = $item_id1;
                $doc->id_request = $id;
                $doc->save();
            }

            if(isset($u2->name)) {
                $sql = 'select a.*from docs a'
                    . ' where a.id_doc=:search_doc and a.id_request=:search_id';
                $docs_d = docs::findBySql($sql, [':search_doc' => 2, ':search_id' => $id])->one();
                if (!empty($docs_d)) $docs_d->delete();

                $doc = new Docs();
                $doc->id_doc = 2;

                $doc->file_path = $doc->id_doc . '_' . $rand . '-' . $u2->name;
                $doc->item_id = $item_id1;
                $doc->id_request = $id;
                $doc->save();
            }

            if(isset($u3->name)) {
                $sql = 'select a.*from docs a'
                    . ' where a.id_doc=:search_doc and a.id_request=:search_id';
                $docs_d = docs::findBySql($sql, [':search_doc' => 3, ':search_id' => $id])->one();
                if (!empty($docs_d)) $docs_d->delete();

                $doc = new Docs();
                $doc->id_doc = 3;

                $doc->file_path = $doc->id_doc . '_' . $rand . '-' . $u3->name;
                $doc->item_id = $item_id1;
                $doc->id_request = $id;
                $doc->save();
            }

            if(isset($u4->name)) {
                $sql = 'select a.*from docs a'
                    . ' where a.id_doc=:search_doc and a.id_request=:search_id';
                $docs_d = docs::findBySql($sql, [':search_doc' => 4, ':search_id' => $id])->one();
                if (!empty($docs_d)) $docs_d->delete();

                $doc = new Docs();
                $doc->id_doc = 4;

                $doc->file_path = $doc->id_doc . '_' . $rand . '-' . $u4->name;
                $doc->item_id = $item_id1;
                $doc->id_request = $id;
                $doc->save();
            }

            if(isset($u5->name)) {
                $sql = 'select a.*from docs a'
                    . ' where a.id_doc=:search_doc and a.id_request=:search_id';
                $docs_d = docs::findBySql($sql, [':search_doc' => 5, ':search_id' => $id])->one();
                if (!empty($docs_d)) $docs_d->delete();

                $doc = new Docs();
                $doc->id_doc = 5;

                $doc->file_path = $doc->id_doc . '_' . $rand . '-' . $u5->name;
                $doc->item_id = $item_id1;
                $doc->id_request = $id;
                $doc->save();
            }

            if($mod=='schet')
                return $this->redirect(['site/createproposal']);

        } else {
            if($mod=='schet')
                return $this->render('update_request', [
                    'model' => $model,'nazv' => $nazv,'mail'=> $mail,'mode'=>1,'doc_v' => $doc_v
                ]);
        }
    }

    //    Просмотр документов
    public function actionDoc(){
        date_default_timezone_set('Europe/Kiev');
        $doc = Yii::$app->request->post('doc');
        $id = Yii::$app->request->post('id');
//        echo $doc;
//        echo ' ';
//        echo '/'.$id;
//        return;
        
        $sql = 'select a.* from docs a'
            . ' where a.id_doc=:search_doc and a.id_unique=:search_id';
        $model = docs::findBySql($sql,[':search_doc'=>$doc,':search_id'=>$id])->one();

//        debug($doc);
//        debug($id);
//        debug($sql);
//        return;

        $f="store/".$model->file_path;
        $f=str_replace('.PDF','.pdf',$f);
        
        $file = Yii::getAlias($f);
        return Yii::$app->response->sendFile($file);
        

    }

    //    Просмотр документов
    public function actionDoc_request(){
        date_default_timezone_set('Europe/Kiev');
        $doc = Yii::$app->request->post('doc');
        $id = Yii::$app->request->post('id');
//        echo $doc;
//        echo '<br>';
//        echo $id;
//        return;

        $sql = 'select a.* from docs a'
            . ' where a.id_doc=:search_doc and a.id_request=:search_id';
        $model = docs::findBySql($sql,[':search_doc'=>$doc,':search_id'=>$id])->one();
//        debug($doc);
//        debug($id);
//        debug($sql);
//        return;

        $f="store/".$model->file_path;
        $f=str_replace('.PDF','.pdf',$f);
        $file = Yii::getAlias($f);
        return Yii::$app->response->sendFile($file);
    }

    //    Срабатывает при нажатии кнопки добавления заявки
    public function actionCreaterequest()
    {
        $model = new request();
        $role=0;
        if(!isset(Yii::$app->user->identity->role))
        {      $flag=0;}
        else{
            $role=Yii::$app->user->identity->role;
        }

        if ($model->load(Yii::$app->request->post()))
        {
//            $model1 = new request();
//            debug($model);
//            return;
            $z = "select max(nomer) as nomer
                      from request
                      where year(date)=year(now())";

            $req = request::findBySql($z)->one();
//            debug($req);
//            return;

            // В Новом году нумерация начинается сначала (с 1)
            if(empty($req['nomer']))
                $nomer=1;
            else
                $nomer=$req['nomer']+1;

            $model->date = date("Y-m-d");
            $model->time = date("H:i:s");
            $model->nomer = $nomer;
            if(!empty($model->date_opl))
                $model->date_opl = date("Y-m-d", strtotime($model->date_opl));
            if(!empty($model->date_exec))
                $model->date_exec = date("Y-m-d", strtotime($model->date_exec));
            if(!empty($model->date_z))
                $model->date_z = date("Y-m-d", strtotime($model->date_z));
            $model->comment = $model->comment;
            $model->contract = $model->contract;
            $model->date_contract = $model->date_contract;
            $adres_cl = $model->adres;
            $model->adres = $model->adres_con;
            $model->user = $role;
            $inn = $model->inn;
            $edrpo = $model->edrpo;
            $nazv = $model->nazv;
            $adres = $model->adres1;
            $tel = $model->tel_con;
            $email = $model->email;
//            debug($adres);
//            return;
            // Сохранение клиентских данных
            if(!empty($model->inn)) {
                $sql = "select max(id) as id from client where trim(inn)=$inn";
            }
            if(!empty($model->edrpo)) {
                $sql = "select max(id) as id from client where trim(edrpo)=$edrpo";
            }
            if(empty($model->inn) && empty($model->edrpo)) {
                $model = new info();
                $model->title = 'Створення скасовано';
                $model->info1 = "потрібно ввести ІПН або ЄДРПОУ.";
                $model->style1 = "d15";
                $model->style2 = "info-text";
                $model->style_title = "d9";

                return $this->render('msg', [
                    'model' => $model]);
            }
//            debug($client);
//            debug($model->inn);
//            $client->inn=$model->inn;
//            return;

            if(empty($client)) $client = new client();
            $client->inn=$model->inn;
            $client->edrpo=$model->edrpo;
            $client->name=$model->nazv;
            $client->adres=$adres_cl;
            $client->tel=$model->tel_con;
            $client->e_mail=$model->email;

            if(!$client->save(false))
            {
                var_dump($client);
                return;
            }
            $client = client::findBySql($sql)->one();
            $model->id_client = $client->id;

            if($model->save(false))
                return $this->redirect(['site/createproposal']);
        } else {

            return $this->render('update_request', [
                'model' => $model,'mode'=>0]);
        }
    }

    // Подгрузка клиентских данных - происходит при вводе ИНН
    public function actionGetklient($inn) {
        Yii::$app->response->format = Response::FORMAT_JSON;
       if(empty($inn)){
           $nazv = '';
           $adres = '';
           $email = '';
           $tel = '';
           $edrpo = '';
           return ['success' => true, 'nazv' => $nazv,'adres' => $adres,
               'email' => $email,'tel' => $tel,'edrpo' => $edrpo,
           ];
       }
        if (Yii::$app->request->isAjax) {
            $iklient = client::find()->
                where('trim(inn)=:inn',[':inn' => trim($inn)])->orderBy([
                'id' => SORT_DESC //specify sort order ASC for ascending DESC for descending
            ])->all();
            //var_dump($iklient);
            if(!isset($iklient[0]->name)) {
                $nazv = '';
                $adres = '';
                $email = '';
                $tel = '';
                $edrpo = '';
            }
            else {
                $nazv = $iklient[0]->name;
                $adres = $iklient[0]->adres;
                $email = $iklient[0]->e_mail;
                $tel = $iklient[0]->tel;
                $edrpo = $iklient[0]->edrpo;
            }
            return ['success' => true, 'nazv' => $nazv,'adres' => $adres,
                'email' => $email,'tel' => $tel,'edrpo' => $edrpo,
            ];

        }
        return ['oh no' => 'you are not allowed :('];
    }

    // Подгрузка клиентских данных - происходит при вводе ОКПО
    public function actionGetklient_edrpo($edrpo) {
        Yii::$app->response->format = Response::FORMAT_JSON;
        if (Yii::$app->request->isAjax) {
            $iklient = client::find()->
            where('trim(edrpo)=:edrpo',[':edrpo' => trim($edrpo)])->andwhere('length(edrpo)>0')->all();

            if(!isset($iklient[0]->name)) {
                $nazv = '';
                $adres = '';
                $email = '';
                $tel = '';
                $inn = '';
            }
            else {
                $nazv = $iklient[0]->name;
                $adres = $iklient[0]->adres;
                $email = $iklient[0]->e_mail;
                $tel = $iklient[0]->tel;
                $inn = $iklient[0]->inn;
            }
            return ['success' => true, 'nazv' => $nazv,'adres' => $adres,
                'email' => $email,'tel' => $tel,'inn' => $inn,
            ];

        }
        return ['oh no' => 'you are not allowed :('];
    }

    //    Обновление записей из справочника
    public function actionUpdate_repnkre($id,$mod)
    {
        // $id  id записи
        // $mod - название модели
        if($mod=='update_repnkre')
            $model = Rep_nkre::findOne($id);

        if ($model->load(Yii::$app->request->post()))
        {
            $model->status=1;
            $date_con=$model->date_con;
            if(substr($date_con,2,1)<>'.'){
                if(!empty($date_con)) {
                    $date_con = changeDateFormat($date_con, 'd.m.Y');
                    $model->date_con = $date_con;
                }
            }
            $date_com_dog_d=$model->date_com_dog_d;
            if(substr($date_com_dog_d,2,1)<>'.'){
                if(!empty($date_com_dog_d)) {
                    $date_com_dog_d = changeDateFormat($date_com_dog_d, 'd.m.Y');
                    $model->date_com_dog_d = $date_com_dog_d;
                }
            }
            $date_com_dog_p=$model->date_com_dog_p;
            if(substr($date_com_dog_p,2,1)<>'.'){
                if(!empty($date_com_dog_p)) {
                    $date_com_dog_p = changeDateFormat($date_com_dog_p, 'd.m.Y');
                    $model->date_com_dog_p = $date_com_dog_p;
                }
            }
            if(!$model->save())
            {  $model->validate();
                print_r($model->getErrors());
                return;
                var_dump($model);return;
            }

            if($mod=='update_repnkre')
                return $this->redirect(['site/report_nkre']);

        }
        else
        {
            if($mod=='update_repnkre')
                return $this->render('update_nkre', [
                    'model' => $model,
                ]);
        }
    }

    //    Импорт данных отчета для НКРЕ
    public function actionImport_rep_nkre()
    {
        define("FIRST_STRING", 10);  // Константа начала вывода строк в таблице - конец шапки таблицы Excel
        $data = Rep_nkre::find()->where(['status'=>1])->asArray()->all();  // Массив измененных записей в отчете
        $sql='select max(num_pp) as maxnum from rep_nkre';
        $data_max = Rep_nkre::findBySql($sql)->asArray()->all();
        $max_string=$data_max[0]['maxnum'];  // Кол-во строк в отчете

        $kol=count($data);
        // Если нет данных для импорта - выводим сообщение: "Даних для імпорту немає"
        if($kol==0){
            $model = new info();
            $model->title = 'Увага!';
            $model->info1 = "Даних для імпорту немає";
            $model->style1 = "d15";
            $model->style2 = "info-text";
            $model->style_title = "d9";

            return $this->render('about_u', [
                'model' => $model]);
        }

        $pExcel = \PHPExcel_IOFactory::load('rep_nkre.xlsx');
        $objWriter = \PHPExcel_IOFactory::createWriter($pExcel, 'Excel2007');
        // Очищаем первую ячейку (надпись "Змінено") - по всем строкам отчета
        for($i=(FIRST_STRING+1);$i<($max_string+FIRST_STRING+1);$i++) {
            $pExcel->getActiveSheet()->setCellValue('A'.$i,'');
        }
        // Заполняем определенные ячейки
        foreach ($data as $v) {
            $num_pp=$v['num_pp'];
            $row=FIRST_STRING+$num_pp;
            $date_con=$v['date_con'];
            $distance=$v['distance'];
            $date_com_expl=$v['date_com_expl'];
            $date_com_dog_d=$v['date_com_dog_d'];
            $date_com_dog_p=$v['date_com_dog_p'];
            $pExcel->getActiveSheet()->setCellValue('X'.$row,$distance);
            $pExcel->getActiveSheet()->setCellValue('BL'.$row,$date_con);
            $pExcel->getActiveSheet()->setCellValue('DT'.$row,$date_com_expl);
            $pExcel->getActiveSheet()->setCellValue('DU'.$row,$date_com_dog_d);
            $pExcel->getActiveSheet()->setCellValue('DV'.$row,$date_com_dog_p);
//            $pExcel->getActiveSheet()->getStyle('B11')
//                ->getFill()->getStartColor()->setRGB('FFFF0000');
            $pExcel->getActiveSheet()->setCellValue('A'.$row,'Змінено');
            $objWriter->save('rep_nkre.xlsx');
        }
        // Обнуляем все статусы, так как все строки уже импортированы
        $z='UPDATE rep_nkre set status=0';
        Yii::$app->db_pg_budget->createCommand($z)->execute();

//        Выводим информационное сообщение о том сколько импортировано строк в таблицу Excel
        $model = new info();
        $model->title = 'Увага!';
        $model->info1 = "Дані записано. Кількість змінених рядків ".$kol;
        $model->style1 = "d15";
        $model->style2 = "info-text";
        $model->style_title = "d9";

        return $this->render('about_u', [
            'model' => $model]);
    }


// Добавление новых пользователей
    public function actionAddAdmin() {
        $model = User::find()->where(['username' => 'boss'])->one();
        if (empty($model)) {
            $user = new User();
            $user->username = 'boss';
            $user->email = 'boss@ukr.net';
            $user->setPassword('yfxfkmybr');
            $user->generateAuthKey();
            if ($user->save()) {
                echo 'good';
            }
        }
    }

// Выход пользователя
    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }

    public function actionLogin()
    {
        return $this->goHome();
    }
}
