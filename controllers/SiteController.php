<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use app\models\ContactForm;
use app\models\InputDataForm;
use app\models\Calc;
use app\models\spr_res;
use app\models\spr_res_koord;
use app\models\vspr_res_koord;
use app\models\data_con;
use app\models\spr_work;
use app\models\spr_costwork;
use app\models\spr_transp;
use app\models\klient;
use app\models\spr_towns;
use app\models\schet;
use app\models\viewproposal;
use app\models\requestsearch;
use app\models\tofile;
use app\models\forExcel;
use app\models\info;
use app\models\docs;
use app\models\User;
use app\models\loginform;
use app\models\potrebitel;
use app\models\proposal;
use app\models\inputdata_cabinet;
use kartik\mpdf\Pdf;
//use mpdf\mpdf;
use yii\web\UploadedFile;

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
                ],
            ],
        ];
    }

    
    public function beforeAction($action)
    {
        if ($this->action->id == 'proposal')
        {
           // $this->enableCsrfValidation = false;
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
        $model = new InputDataForm();
        if ($model->load(Yii::$app->request->post()))
        {

            return $this->redirect(['calc','town' => $model->town,
                'power' => $model->power,'voltage' => $model->voltage,
                'q_phase' => $model->q_phase,'reliability' => $model->reliability]);
            }
         else {
            return $this->render('inputdata', [
                'model' => $model,
            ]);
        }
    }

    // Расчет стоимости подключения (происходит при нажатии на кн. OK)
    public function actionCalc($town,$power,$voltage,$q_phase,$reliability)
    {
        if($town==2) $town=0;
        if($power<=16) $power_stage=1;
        if($power>16 && $power<=50) $power_stage=2;
        if($power>50 && $power<=160) $power_stage=3;
        $rank=4-$reliability;
        if($q_phase==2) $q_phase=3;
        if($voltage==1) $v='a';
        if($voltage==2) $v='b';
        if($voltage==3) $v='c';
        if($voltage==4) $v='d';
        $u='u_'.$v.$q_phase;
        
        $sql = 'SELECT '.$u.' as cost FROM data_con WHERE town=:town and power_stage=:power_stage
                 and rank=:rank';

        $model = data_con::findBySql($sql,[':town'=>$town,
            ':power_stage'=>$power_stage,':rank'=>$rank])->all();
        $cost1 = $model[0]->cost*$power;
        $cost = number_format($cost1,0,' ',' ');
        $cost_all = number_format($cost1*1.2,0,' ',' ');
        $cost_nds = number_format($cost1*0.2,0,' ',' ');
            
        
        return $this->render('resultCalc', ['model' => $model,
            'cost' => $cost, 'cost_all' => $cost_all,
            'cost_nds' => $cost_nds,'town' => $town,
            'power' => $power,'voltage' => $voltage,
            'reliability' => $reliability,'q_phase' => $q_phase]);
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
        {   $contract = $model->contract; 
            $date_contract = $model->date_contract;
            
            $sql = 'select * from vw_proposal where contract='.'"'.$contract.'"'.
                    ' and date_contract='.'"'.$date_contract.'"';
             $info = Viewproposal::findBySql($sql)->one();       
             if(empty($info->contract)){
                Yii::$app->session->setFlash('error','Неправильний № договору або дата'); 
                return $this->refresh ();}
             else    
             {return $this->redirect(['info_cabinet',
             'contract' => $contract,'date_contract' => $date_contract]);}
             }
         else {
            return $this->render('inputdata_cabinet', [
                'model' => $model,
            ]);
        }
    }
    
    // Отображение личного кабинета
    public function actionInfo_cabinet($contract,$date_contract)
    {
        $sql = 'select * from vw_proposal where contract='.'"'.$contract.'"'.
                    ' and date_contract='.'"'.$date_contract.'"';
             $info = Viewproposal::findBySql($sql)->one();       
        
                 
             
        return $this->render('info_cabinet', ['info' => $info]);
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
            $mail_cek = 'opr@cek.dp.ua';
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
            $sql = 'select min(id) as id,district,town from spr_towns where town like '.'"'.$name1.'%"'.
                    ' and length('.'"'.$name1.'")>3'.' group by district,town order by town,district';
             $cur = spr_towns::findBySql($sql)->all();

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
            $sql = 'select min(id) as id,street from spr_towns where street like '.'"%'.$name1.'%"'.
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

    //    Просмотр документов
    public function actionDoc(){
        date_default_timezone_set('Europe/Kiev');
        $doc = Yii::$app->request->post('doc');
        $id = Yii::$app->request->post('id');
//        echo $doc;
//        echo ' ';
//        echo '/'.$id;
//        return;
        
        $sql = 'select a.*from docs a'
            . ' where a.id_doc=:search_doc and a.id_unique=:search_id';
        $model = docs::findBySql($sql,[':search_doc'=>$doc,':search_id'=>$id])->one();
        
        $f="store/".$model->file_path;
        
        $file = Yii::getAlias($f);
        return Yii::$app->response->sendFile($file);
        

    }    
       
// Добавление новых пользователей
    public function actionAddAdmin() {
        $model = User::find()->where(['username' => 'buh1'])->one();
        if (empty($model)) {
            $user = new User();
            $user->username = 'buh1';
            $user->email = 'buh1@ukr.net';
            $user->setPassword('afynfpbz');
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
}
