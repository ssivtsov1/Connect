<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\grid\GridView;
use yii\grid\ActionColumn;
use yii\grid\SerialColumn;
use yii\helpers\ArrayHelper;

$this->title = "Звіт НКРЕ";
//$this->params['breadcrumbs'][] = $this->title;
?>
    <div class="site-spr">

        <h4><?php

            echo Html::encode($this->title);
            ?></h4>

        <?
        //    debug(Yii::$app->user->identity->role);
        if(Yii::$app->user->identity->role==3) {
            echo Html::a('Імпорт даних', ['site/import_rep_nkre'],
                ['class' => 'btn btn-info excel_btn']);

            if(Yii::$app->user->identity->role==3) {
                echo Html::a('Завантажити файл', ['site/load_rep_nkre'],
                    ['class' => 'btn btn-info excel_btn']);

            echo '<br>';
            echo '<br>';
        }
        ?>


        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'summary' => false,
            'emptyText' => 'Нічого не знайдено',
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                [
                    /**
                     * Указываем класс колонки
                     */
                    'class' => \yii\grid\ActionColumn::class,
                    'buttons'=>[

                        'update'=>function ($url, $model) {
                            $customurl=Yii::$app->getUrlManager()->
                            createUrl(['/site/update_repnkre','id'=>$model['id'],'mod'=>'update_repnkre']);

                                return \yii\helpers\Html::a( '<span class="glyphicon glyphicon-pencil"></span>', $customurl,
                                    ['title' => Yii::t('yii', 'Редагувати'), 'data-pjax' => '0']);}

                    ],
                    'template' => '{update}',
                ],
                'num_pp',
                  ['attribute' =>'nazv_status',
                'label' => 'Статус',
                'encodeLabel' => false,
                'filter'=>array('Не змінено' => 'Не змінено',
                    'Змінено' => 'Змінено',
                ),
                'format' => 'raw'],
                ['attribute' =>'nazv_rem',
                    'label' => 'РЕМ',
                    'encodeLabel' => false,
                    'filter'=>array('ДнРЕМ' => 'ДнРЕМ',
                        'ЖвРЕМ' => 'ЖвРЕМ',
                        'ПвРЕМ' => 'ПвРЕМ',
                        'КрРЕМ' => 'КрРЕМ',
                        'Вг.діл.ЖвРЕМ' => 'Вг.діл.ЖвРЕМ',
                        'Гв.діл.ПвРЕМ' => 'Гв.діл.ПвРЕМ',
                    ),
                    'format' => 'raw'],
                'fio','adres','nazv_locate','n_doc',
                'date_doc','nazv_src','nazv_typeust','category','power',
                'power_z','voltage','date_expl','nazv_point_con','nazv_scheme',
                'date_dog','date_tu','num_tu','distance','date_con',
                'date_com_expl','date_com_dog_d','date_com_dog_p'
              
            ],
        ]);?>


    </div>

<?php
