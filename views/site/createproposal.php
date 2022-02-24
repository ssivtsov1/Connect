<?php


use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

use yii\grid\GridView;
use yii\grid\ActionColumn;
use yii\grid\CheckboxColumn;
use yii\grid\SerialColumn;
use yii\helpers\Url;

$this->title = 'Створення замовлень на підключення';

//$this->params['breadcrumbs'][] = $this->title;
//echo Yii::$app->user->identity->role;
?>

<div class="site-spr1">
    <h3><?= Html::encode($this->title) ?></h3>

    <?= Html::a('Додати', ['createrequest'], ['class' => 'btn btn-success']);?>
    &nbsp
    <?
    echo Html::a('Експорт в Excel', ['createproposal?'.
        'item=Excel'],
    ['class' => 'btn btn-info excel_btn'
    ]);
    ?>
    <br>
    <br>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'emptyText' => 'Нічого не знайдено',
        'summary' => false,
        'columns' => [
//            ['class' => 'yii\grid\SerialColumn'],
            [
                /**
                 * Указываем класс колонки
                 */
            'class' => \yii\grid\ActionColumn::class,
            'buttons'=>[

                'update'=>function ($url, $model) {
                    $customurl=Yii::$app->getUrlManager()->createUrl(['/site/upd1','id'=>$model['id'],'mod'=>'schet']); //$model->id для AR
                    return \yii\helpers\Html::a( '<span class="glyphicon glyphicon-pencil"></span>', $customurl,
                        ['title' => Yii::t('yii', 'Редагувати'), 'data-pjax' => '0']);
                }
            ],
            /**
             * Определяем набор кнопочек. По умолчанию {view} {update} {delete}
             */
            'template' => '{update}',
        ],

            ['attribute' =>'nomer',
                'value' => function ($model){
                    $q = $model->status;
                    switch($q){
                        case 1:
                            return  $model->nomer ;

                        case 11:
                            return "<span class='text-success fontbld'> $model->nomer </span>";

                        default:
                            return $model->nomer;}
                },
                'format' => 'raw'
            ],
//            'okpo',
//            'inn',

            ['attribute' =>'id_tu',
                'value' => function ($model){
                    $q = $model->status;
                    switch($q){
                        case 1:
                            return  $model->id_tu ;

                        case 11:
                            return "<span class='text-success fontbld'> $model->id_tu </span>";

                        default:
                            return $model->id_tu;}
                },
                'format' => 'raw'
            ],

            ['attribute' =>'nazv_status',
                'value' => function ($model){
                    $q = $model->status;
                    switch($q){
                         case 1:
                         return $model->nazv_status ;
                         
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
                         return  $model->nazv ;
                         
                         case 11:
                        return "<span class='text-success fontbld'> $model->nazv </span>";
                        
                         default:
                    return $model->nazv;}
                },
                'format' => 'raw'
            ],

            ['attribute' =>'adres_con',
                'value' => function ($model){
                    $q = $model->status;
                    switch($q){
                        case 1:
                            return $model->adres_con ;

                        case 11:
                            return "<span class='text-success fontbld'> $model->adres_con </span>";

                        default:
                            return $model->adres_con;}
                },
                'format' => 'raw'
            ],

            ['attribute' =>'tel_con',
                'value' => function ($model){
                    $q = $model->status;
                    switch($q){
                        case 1:
                            return  $model->tel_con ;
                       
                        case 11:
                            return "<span class='text-success fontbld'> $model->tel_con </span>";
                        
                        default:
                            return $model->tel_con;}
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
                            return $model->date_z ;
                        
                        case 11:
                            return "<span class='text-success fontbld'> $model->date_z </span>";
                        
                        default:
                            return $model->date_z;}
                },
                'format' => 'raw'
            ],
            [
                'attribute' => 'department',
                'label' => 'Користувач:',
                'format' =>  ['date', 'php:d.m.Y'],
                'encodeLabel' => false,
                'value' => function ($model){
                    $q = $model->status;
                    switch($q){
                        case 1:
                            return $model->department ;
                        case 11:
                            return "<span class='text-success fontbld'> $model->department </span>";

                        default:
                            return $model->department;}
                },
                'format' => 'raw'
            ],
                        
            [
                'attribute' => 'date_f',
                'label' => 'Дата <br />заявки:',
                'format' =>  ['date', 'php:d.m.Y'],
                'encodeLabel' => false,
                'value' => function ($model){
                    $q = $model->status;
                    switch($q){
                        case 1:
                            return $model->date_f ;
                        
                        case 11:
                            return "<span class='text-success fontbld'> $model->date_f </span>";
                        
                        default:
                            return $model->date_f;}
                },
                'format' => 'raw'
            ],
            
            ['attribute' =>'time',
                'value' => function ($model){
                    $q = $model->status;
                    switch($q){
                        case 1:
                            return  $model->time ;
                        
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



