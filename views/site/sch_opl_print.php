<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
$this->title = "Рахунок для оплати";
$this->params['breadcrumbs'][] = $this->title;
$rr='26007000030100';
$mfo='322313';
$okpo='31793056';
?>
<!--<div class="site-about">-->
    <div class=<?= $style_title ?> >
         <h3><?= Html::encode($this->title) ?></h3>
    </div>

<table width="600px" class="table table-bordered ">

    <tr>
        <th width="250px">
            <div class="opl_left">
            <span class="span_single">Повідомлення про оплату за послугу по рахунку №
                <?php
                echo $model->schet;
                ?>
                </span>
                <br>
                <br>
                Одержувач:
                <br>
                <?= Html::encode("ПрАТ «ПЕЕМ «ЦЕК»") ?>
                <br>
                <?= Html::encode("р/р: $rr МФО: $mfo") ?>

                <br>
                <?= Html::encode("ЕГРПОУ: $okpo") ?>

                <br>
                <br>
                <?= Html::encode("Платник:") ?>

                <br>
                <?= Html::encode($model->nazv) ?>

                <br>
                <?= Html::encode($model->addr) ?>
                <br>
                <br>
                <br>
            <span class="span_single">
                <?= Html::encode("Сплачено:") ?>

            </span> <span class="span_ramka"> <?= Html::encode($model->summa.' грн.') ?> </span>
                <br>
                <br>
                <br>
                <?= Html::encode("Підпис") ?>

                <br>
                <br>
            </div>
        </th>
        <th width="350px" class="th_r">
            <div class="opl_left">
                <span class="span_single"><?= Html::encode("Рахунок за послугу №") ?>
                    <?= Html::encode($model->schet. ' від '. date('d.m.Y')) ?>
                    <?= Html::encode(' по договору '. $model->contract) ?>
                </span>
                <br>
                <br>
                <?= Html::encode("Платник:") ?>
                <br>
                <?= Html::encode($model->nazv) ?>
                <br>
                <?= Html::encode($model->addr) ?>
                <br>
                <br>
                <?= Html::encode("Послуга:") ?>

                <br>
                <?= Html::encode($model->usluga) ?>
                <br>
                <br>
                <br>
                <span class="span_single">
                    Всього до сплати:
                </span> <span class="span_ramka">
                    <?= Html::encode($model->summa.' грн.') ?>
                </span>
                <br>
                <br>
                <br>
                <br>
                <br>
                <br>
                <?= Html::encode("Телефон для довідок:    0 800 300 015 (безкоштовно цілодобово)") ?>
                 <br>
                <div class="single_red">
                    <?= Html::encode("Рахунок дійсний протягом однієї доби !") ?>
                </div>
                <br>
                <br>
                <br>

            </div>
        </th>
    </tr>


</table>
    <code><?//= __FILE__ ?></code>

