<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\InputDataForm;
use app\models\Calc;
use app\models\spr_res;
use app\models\vspr_res_koord;
use app\models\spr_res_koord;
use app\models\data_con;
use app\models\spr_uslug;
use app\models\spr_transp;
use app\models\sprtransp;
use app\models\searchklient;
use app\models\klient;
use app\models\status_con;
use app\models\requerstsearch;

class SpravController extends Controller
{
   public $spr='0';
    
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

// Справочник услуг
    public function actionSprav_uslug()
    {
        $model = new spr_uslug();
        $model = $model::find()->all();
        $dataProvider = new ActiveDataProvider([
            'query' => spr_uslug::find(),
        ]);
        $dataProvider->pagination->route = '/sprav/sprav_uslug';
        $dataProvider->sort->route = '/sprav/sprav_uslug';

        return $this->render('sprav_uslug', [
            'model' => $model,'dataProvider' => $dataProvider
        ]);
    }
    // Справочник РЭСов
    public function actionSprav_res()
    {
        $model = new spr_res();
        $model = $model::find()->all();
        $dataProvider = new ActiveDataProvider([
         'query' => spr_res::find(),
        ]); 
        $dataProvider->pagination->route = '/sprav/sprav_res';
        $dataProvider->sort->route = '/sprav/sprav_res';
        
            return $this->render('sprav_res', [
                'model' => $model,'dataProvider' => $dataProvider
            ]);
    }
    
    // Справочник ответственных лиц по РЄСам
    public function actionSprav_spr_res_koord()
    {
        $model = new vspr_res_koord();
        $model = $model::find()->all();
        $dataProvider = new ActiveDataProvider([
         'query' => vspr_res_koord::find(),
        ]); 
        $dataProvider->pagination->route = '/sprav/sprav_spr_res_koord';
        $dataProvider->sort->route = '/sprav/sprav_spr_res_koord';
        
            return $this->render('sprav_spr_res_koord', [
                'model' => $model,'dataProvider' => $dataProvider
            ]);
    }
    
    // Справочник ставок на присоединение
    public function actionSprav_data_con()
    {
        $model = new data_con();
        $searchModel = new data_con();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $model = $model::find()->orderBy(['power_stage' => SORT_ASC])
                ->orderBy(['rank' => SORT_DESC])->orderBy(['town' => SORT_DESC])->all();
       
            return $this->render('sprav_data_con', [
                'model' => $searchModel,'dataProvider' => $dataProvider,'searchModel' => $searchModel,
            ]);
    }

    // Справочник транспорта
    public function actionSprav_transp()
    {
        $searchModel = new spr_transp();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            return $this->render('sprav_transp', [
                'model' => $searchModel,'dataProvider' => $dataProvider,'searchModel' => $searchModel,
            ]);
    }

    // Справочник статусов заявки
    public function actionStatus_con()
    {
        $searchModel = new Status_con();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('status_con', [
            'model' => $searchModel,'dataProvider' => $dataProvider,'searchModel' => $searchModel,
        ]);
    }

    // Справочник контрагентов
    public function actionSprav_klient()
    {
        $searchModel = new searchklient();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->setSort([
            'attributes' => [
                'inn',
                'okpo',
                'regsvid',
                'nazv',
                'addr',
                'priz_nds',
                'id',
                'person'
            ]
        ]);

        return $this->render('sprav_klient', [
            'model' => $searchModel,'dataProvider' => $dataProvider,'searchModel' => $searchModel,
        ]);
    }
    
//    Удаление записей из справочника
    public function actionDelete($id,$mod)
    {   // $id  id записи
        // $mod - название модели
        if($mod=='spr_res')
        $model = spr_res::findOne($id);
        if($mod=='sprtransp')
        $model = sprtransp::findOne($id);
        if($mod=='spr_data_con')
        $model = data_con::findOne($id);
        if($mod=='sprklient')
            $model = klient::findOne($id);
        if($mod=='status_con')
            $model = status_con::findOne($id);
        if($mod=='spr_res_koord')
            $model = spr_res_koord::findOne($id);
        
        $model->delete();
        
        if($mod=='spr_res')
        return $this->redirect(['sprav/sprav_res']);
        if($mod=='sprtransp')
        return $this->redirect(['sprav/sprav_transp']);
        if($mod=='spr_data_con')
        return $this->redirect(['sprav/sprav_data_con']);
        if($mod=='sprklient')
            return $this->redirect(['sprav/sprav_klient']);
        if($mod=='status_con')
            return $this->redirect(['sprav/status_con']);
        if($mod=='spr_res_koord')
            return $this->redirect(['sprav/sprav_spr_res_koord']);

    }

//    Обновление записей из справочника
    public function actionUpdate($id,$mod)
    {
        // $id  id записи
        // $mod - название модели
        if($mod=='spr_res')
        $model = spr_res::findOne($id);
        if($mod=='sprtransp')
        $model = sprtransp::findOne($id);
        if($mod=='spr_data_con')
        $model = data_con::findOne($id);
        if($mod=='sprklient')
            $model = klient::findOne($id);
        if($mod=='status_con')
            $model = status_con::findOne($id);
        if($mod=='spr_res_koord')
            $model = spr_res_koord::findOne($id);

        if ($model->load(Yii::$app->request->post()))
        {  
            
            if(!$model->save())
            {  $model->validate();
               print_r($model->getErrors());
               return;
                var_dump($model);return;}

            if($mod=='spr_res')
                return $this->redirect(['sprav/sprav_res']);
            if($mod=='sprtransp')
                return $this->redirect(['sprav/sprav_transp']);
            if($mod=='spr_data_con')
                return $this->redirect(['sprav/sprav_data_con']);
            if($mod=='sprklient')
                return $this->redirect(['sprav/sprav_klient']);
            if($mod=='status_con')
                return $this->redirect(['sprav/status_con']);
            if($mod=='spr_res_koord')
                return $this->redirect(['sprav/sprav_spr_res_koord']);
            
        } else {
            if($mod=='spr_res')
            return $this->render('update_res', [
                'model' => $model,

            ]);
            if($mod=='spr_data_con')
            return $this->render('update_data_con', [
                'model' => $model,

            ]);
            if($mod=='sprtransp')
            return $this->render('update_transp', [
                'model' => $model,

            ]);
            if($mod=='sprklient')
                return $this->render('update_klient', [
                    'model' => $model,

                ]);

            if($mod=='status_con')
                return $this->render('update_status_con', [
                    'model' => $model,

                ]);
            if($mod=='spr_res_koord')
                return $this->render('update_spr_res_koord', [
                    'model' => $model,

                ]);
        }
    }
//    Срабатывает при нажатии кнопки добавления РЭСа
     public function actionCreateres()
    {
        
        $model = new spr_res();
       
        if ($model->load(Yii::$app->request->post()))
        {  
                       
            if($model->save(false)) //var_dump($model->getErrors());
               return $this->redirect(['sprav/sprav_res']);
           
        } else {
           
            return $this->render('update_res', [
                'model' => $model]);
        }
    }

    //    Срабатывает при нажатии кнопки добавления статуса заявки
    public function actionCreatestatus_con()
    {
        $model = new status_con();

        if ($model->load(Yii::$app->request->post()))
        {
            if($model->save(false)) //var_dump($model->getErrors());
                return $this->redirect(['sprav/status_con']);
        } else {

            return $this->render('update_status_con', [
                'model' => $model]);
        }
    }

    //    Срабатывает при нажатии кнопки добавления в справ. транспорта
    public function actionCreatetransp()
    {
        
        $model = new sprtransp();
       
        if ($model->load(Yii::$app->request->post()))
        {  
            if($model->save(false))
               return $this->redirect(['sprav/sprav_transp']);
        } else {
           
            return $this->render('update_transp', [
                'model' => $model]);
        }
    }
    
    //    Срабатывает при нажатии кнопки добавления в справ. отв. лиц
    public function actionCreatekoord()
    {
        
        $model = new spr_res_koord();
       
        if ($model->load(Yii::$app->request->post()))
        {  
            if($model->save(false))
               return $this->redirect(['sprav/sprav_spr_res_koord']);
        } else {
           
            return $this->render('update_spr_res_koord', [
                'model' => $model]);
        }
    }

    //    Срабатывает при нажатии кнопки добавления в справ. работ
    public function actionCreatedata_con()
    {
        $model = new spr_work();
        if ($model->load(Yii::$app->request->post()))
        {  
            if($model->save(false))
               return $this->redirect(['sprav/sprav_data_con']);
           
        } else {
           
            return $this->render('update_data_con', [
                'model' => $model]);
        }
    }

    //    Срабатывает при нажатии кнопки добавления в справ. контрагентов
    public function actionCreateklient()
    {
        $model = new klient();
        if ($model->load(Yii::$app->request->post()))
        {
            if($model->save(false))
                return $this->redirect(['sprav/sprav_klient']);
        } else {
            return $this->render('update_klient', [
                'model' => $model]);
        }
    }

}
