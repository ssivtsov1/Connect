<?php


use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

use yii\grid\GridView;
use yii\grid\ActionColumn;
use yii\grid\CheckboxColumn;
use yii\grid\SerialColumn;
use yii\helpers\Url;

$this->title = 'Перегляд замовлень на підключення';
//$this->params['breadcrumbs'][] = $this->title;
//echo Yii::$app->user->identity->role;
?>

<div class="site-spr1">
    <h3><?= Html::encode($this->title) ?></h3>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'emptyText' => 'Нічого не знайдено',
        'summary' => false,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                /**
                 * Указываем класс колонки
                 */
            'class' => \yii\grid\ActionColumn::class,
            'buttons'=>[

                'update'=>function ($url, $model) {
                    $customurl=Yii::$app->getUrlManager()->createUrl(['/site/upd','id'=>$model['id'],'mod'=>'schet']); //$model->id для AR
                    return \yii\helpers\Html::a( '<span class="glyphicon glyphicon-pencil"></span>', $customurl,
                        ['title' => Yii::t('yii', 'Редагувати'), 'data-pjax' => '0']);
                }
            ],
            /**
             * Определяем набор кнопочек. По умолчанию {view} {update} {delete}
             */
            'template' => '{update}',
        ],
                    
            
           // 'id',
            ['attribute' =>'id',
                'value' => function ($model){
                    $q = $model->status;
                    switch($q){
                        case 1:
                            return "<span class='text-info'> $model->id </span>";
                       
                        case 11:
                            return "<span class='text-success fontbld'> $model->id </span>";
                        
                        default:
                            return $model->id;}
                },
                'format' => 'raw'
            ],
//            'okpo',
//            'inn',
            ['attribute' =>'nazv_status',
                'value' => function ($model){
                    $q = $model->status;
                    switch($q){
                         case 1:
                         return "<span class='text-info'> $model->nazv_status </span>";
                         
                         case 11:
                         return "<span class='text-success fontbld'> $model->nazv_status </span>";
                         
                         default:
                    return $model->nazv_status;}
                },
                'format' => 'raw'
            ],
            
            //'nazv',
                ['attribute' =>'nazv',
                'value' => function ($model){
                    $q = $model->status;
                    switch($q){
                         case 1:
                         return "<span class='text-info'> $model->nazv </span>";
                         
                         case 11:
                        return "<span class='text-success fontbld'> $model->nazv </span>";
                        
                         default:
                    return $model->nazv;}
                },
                'format' => 'raw'
            ],

            ['attribute' =>'tel',
                'value' => function ($model){
                    $q = $model->status;
                    switch($q){
                        case 1:
                            return "<span class='text-info'> $model->tel </span>";
                       
                        case 11:
                            return "<span class='text-success fontbld'> $model->tel </span>";
                        
                        default:
                            return $model->tel;}
                },
                'format' => 'raw'
            ],
          
            ['attribute' =>'inn',
                'value' => function ($model){
                    $q = $model->status;
                    switch($q){
                        case 1:
                            return "<span class='text-info'> $model->inn </span>";
                        
                        case 11:
                            return "<span class='text-success fontbld'> $model->inn </span>";
                        
                        default:
                            return $model->inn;}
                },
                'format' => 'raw'
            ],
            
            ['attribute' =>'opl',
                'value' => function ($model){
                    $q = $model->opl;
                    switch($q){
                        case 1:
                            return "Оплачено";
                        case 0:
                            return "Не оплачено";
                        }
                },
                'format' => 'raw'
            ],
            
                        [
                'attribute' => 'date_z',
                'label' => 'Дата <br />виконання:',
                'format' =>  ['date', 'php:d.m.Y'],
                'encodeLabel' => false,
                'value' => function ($model){
                    $q = $model->status;
                    switch($q){
                        case 1:
                            return "<span class='text-info'> $model->date_z </span>";
                        
                        case 11:
                            return "<span class='text-success fontbld'> $model->date_z </span>";
                        
                        default:
                            return $model->date_z;}
                },
                'format' => 'raw'
            ],
                        
                        
            [
                'attribute' => 'date',
                'label' => 'Дата <br />заявки:',
                'format' =>  ['date', 'php:d.m.Y'],
                'encodeLabel' => false,
                'value' => function ($model){
                    $q = $model->status;
                    switch($q){
                        case 1:
                            return "<span class='text-info'> $model->date </span>";
                        
                        case 11:
                            return "<span class='text-success fontbld'> $model->date </span>";
                        
                        default:
                            return $model->date;}
                },
                'format' => 'raw'
            ],
            
            ['attribute' =>'time',
                'value' => function ($model){
                    $q = $model->status;
                    switch($q){
                        case 1:
                            return "<span class='text-info'> $model->time </span>";
                        
                        case 11:
                            return "<span class='text-success fontbld'> $model->time </span>";
                        
                        default:
                            return $model->time;}
                },
                'format' => 'raw'
            ],

           
            
        ],
    ]); ?>

   

</div>



