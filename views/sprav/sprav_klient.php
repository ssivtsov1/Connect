<?php


use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

use yii\grid\GridView;
use yii\grid\ActionColumn;
use yii\grid\CheckboxColumn;
use yii\grid\SerialColumn;

$this->title = 'Довідник контрагентів';
$this->params['breadcrumbs'][] = $this->title;
?>
<?= Html::a('Добавити', ['createklient'], ['class' => 'btn btn-success']) ?>
<div class="site-spr">
    <h3><?= Html::encode($this->title) ?></h3>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'summary' => false,
        'emptyText' => 'Нічого не знайдено',
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'id',
            'inn',
            'nazv',
            'addr',
            ['attribute' =>'priz_nds',
                //'format' => ['decimal', 0],
                'value' => function ($model){
                    if($model->priz_nds == 0)
                        return 'ні';
                    else
                        return 'так';

                },
            ],
            ['attribute' =>'person',
                //'format' => ['decimal', 0],
                'value' => function ($model){
                    if($model->person == 1)
                        return 'Фіз.';
                    if($model->person == 2)
                        return 'Юр.';

                },
            ],
            'okpo',
            'regsvid',
            'tel',
            'email',
            [
                'attribute' => 'date_reg',
                'format' =>  ['date', 'php:d.m.Y'],

            ],
                        
             [
                /**
                 * Указываем класс колонки
                 */
                'class' => \yii\grid\ActionColumn::class,
                 'buttons'=>[
                     'delete'=>function ($url, $model) {
                         $customurl=Yii::$app->getUrlManager()->createUrl(['/sprav/delete','id'=>$model['id'],'mod'=>'sprklient']); //$model->id для AR
                         return \yii\helpers\Html::a( '<span class="glyphicon glyphicon-remove-circle"></span>', $customurl,
                             ['title' => Yii::t('yii', 'Видалити'),'data' => [
                                 'confirm' => 'Ви впевнені, що хочете видалити цей запис ?',
                             ], 'data-pjax' => '0']);
                     },

                     'update'=>function ($url, $model) {
                         $customurl=Yii::$app->getUrlManager()->createUrl(['/sprav/update','id'=>$model['id'],'mod'=>'sprklient']); //$model->id для AR
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



