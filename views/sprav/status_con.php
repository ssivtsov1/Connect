<?php


use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\grid\GridView;
use yii\grid\ActionColumn;
use yii\grid\CheckboxColumn;
use yii\grid\SerialColumn;

$this->title = 'Статуси замовлень на приєднання';
$this->params['breadcrumbs'][] = $this->title;

?>
<?= Html::a('Добавити', ['createstatus_con'], ['class' => 'btn btn-success']) ?>
<div class="site-spr">
    <h3><?= Html::encode($this->title) ?></h3>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'tableOptions' => ['class' => 'table table-striped table-bordered table-condensed'],
        'summary' => false,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            
            'id',
            'status',

             [
                /**
                 * Указываем класс колонки
                 */
                'class' => 'yii\grid\ActionColumn',
                 'header' => 'Кн. <br /> Управ.',
                 'contentOptions'=>['style'=>'white-space: normal;'] ,
                 'buttons'=>[
                  'delete'=>function ($url, $model) {
                        $customurl=Yii::$app->getUrlManager()->createUrl(['/sprav/delete','id'=>$model['id'],'mod'=>'status_con']); //$model->id для AR
                        return \yii\helpers\Html::a( '<span class="glyphicon glyphicon-remove-circle"></span>', $customurl,
                                                ['title' => Yii::t('yii', 'Видалити'),
                                                    'data' => [
                                                        'confirm' => 'Ви впевнені, що хочете видалити цей запис ?',

                                                    ],'data-pjax' => '0']);
                  },
                  
                  'update'=>function ($url, $model) {
                        $customurl=Yii::$app->getUrlManager()->createUrl(['/sprav/update','id'=>$model['id'],'mod'=>'status_con']); //$model->id для AR
                        return \yii\helpers\Html::a( '<span class="glyphicon glyphicon-pencil"></span>', $customurl,
                                                ['title' => Yii::t('yii', 'Редагувати'), 'data-pjax' => '0']);
                  }
                ],
                 
                /**
                 * Определяем набор кнопочек. По умолчанию {view} {update} {delete}
                 */
                'template' => '{update} {delete}',
                 'controller' => '/sprav',
            ],
        ],
    ]); ?>


    
</div>


