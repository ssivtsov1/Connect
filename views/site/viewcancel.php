<?php


use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

use yii\grid\GridView;
use yii\grid\ActionColumn;
use yii\grid\CheckboxColumn;
use yii\grid\SerialColumn;

$this->title = 'Перегляд відмов';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-spr">
    <h3><?= Html::encode($this->title) ?></h3>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'emptyText' => 'Нічого не знайдено',
        'summary' => false,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'id',
            'work',
            'adr_work',
            'res_id',
            'summa',
            'cause',
            [
                'attribute' => 'date',
                'format' =>  ['date', 'php:d.m.Y'],
            ],
            'time'

        ],
    ]); ?>


</div>



