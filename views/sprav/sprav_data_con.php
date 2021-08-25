<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\grid\GridView;
use yii\grid\ActionColumn;
use yii\grid\CheckboxColumn;
use yii\grid\SerialColumn;

$this->title = 'Довідник ставок на приєднання';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-spr">
    <h3><?= Html::encode($this->title) ?></h3>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'summary' => false,
        'emptyText' => 'Нічого не знайдено',
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'rank',
            ['attribute' =>'power_stage',
                'value' => function ($model){
                    $q = $model->power_stage;
                    switch($q){
                        case 1:
                            return "до 16кВт";
                        case 2:
                            return "від 16 до 50кВт";
                        case 3:
                            return "від 50 до 160кВт";    
                        }
                },
                'format' => 'raw'
            ],
            ['attribute' =>'town',
                'value' => function ($model){
                    $q = $model->town;
                    switch($q){
                        case 1:
                            return "Місто";
                        case 0:
                            return "село/смт.";
                        }
                },
                'format' => 'raw'
            ],
            'u_a1',
            'u_a3',
            'u_b1',
            'u_b3',
            ['attribute' =>'u_c1',
                'value' => function ($model){
                    $q = $model->u_c1;
                    switch($q){
                        case -1:
                            return "";
                       
                        }
                },
                'format' => 'raw'
            ],
            'u_c3',
            ['attribute' =>'u_d1',
                'value' => function ($model){
                    $q = $model->u_d1;
                    switch($q){
                        case -1:
                            return "";
                        default:
                            return $q; 
                        }
                },
                'format' => 'raw'
            ],
            ['attribute' =>'u_d3',
                'value' => function ($model){
                    $q = $model->u_d3;
                    switch($q){
                        case -1:
                            return "";
                        default:
                            return $q;
                        }
                },
                'format' => 'raw'
            ],
             [
                /**
                 * Указываем класс колонки
                 */
                'class' => \yii\grid\ActionColumn::class,
                 'buttons'=>[
                  'delete'=>function ($url, $model) {
                        $customurl=Yii::$app->getUrlManager()->createUrl(['/sprav/delete','id'=>$model['id'],'mod'=>'spr_data_con']); //$model->id для AR
                        return \yii\helpers\Html::a( '<span class="glyphicon glyphicon-remove-circle"></span>', $customurl,
                                                ['title' => Yii::t('yii', 'Видалити'),'data' => [
                                                    'confirm' => 'Ви впевнені, що хочете видалити цей запис ?',
                                                ], 'data-pjax' => '0']);
                  },
                  
                  'update'=>function ($url, $model) {
                        $customurl=Yii::$app->getUrlManager()->createUrl(['/sprav/update','id'=>$model['id'],'mod'=>'spr_data_con']); //$model->id для AR
                        return \yii\helpers\Html::a( '<span class="glyphicon glyphicon-pencil"></span>', $customurl,
                                                ['title' => Yii::t('yii', 'Редагувати'), 'data-pjax' => '0']);
                  }
                ],
                /**
                 * Определяем набор кнопочек. По умолчанию {view} {update} {delete}
                 */
                'template' => '{update} {delete}',
            ],
        ],
    ]); ?>


    
</div>



