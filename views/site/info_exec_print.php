<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
$this->title = "Інформаційне повідомлення";
$this->params['breadcrumbs'][] = $this->title;
?>
<!--<div class="site-about">-->
    <div class=<?= $style_title ?> >
         <h3><?= Html::encode($this->title) ?></h3>
    </div>
    <div class="contract_center">
    <span class="span_single">
        <?= Html::encode("Інформаційне повідомлення для надання вашим підрозділом послуги
                        фізичній особі (або сторонній організації)") ?>

    </span>
    </div>

<br>
<br>
<span class="contract_center_text_bold" >
    <?= Html::encode("Споживач послуги (контрагент):");?>
</span>
<br>
<span class="contract_center_text" >
    <?= Html::encode($model->nazv);?>
</span>
<br>

<hr class="inf_line_main">

<span class="contract_center_text_bold" >
    <?= Html::encode("Назва послуги:");?>
</span>
<br>
<span class="contract_center_text" >
    <?= Html::encode($model->usluga);?>
</span>
<br>

<hr class="inf_line_main">

<br>
<span class="contract_center_text_bold" >
    <?= Html::encode("Вартість послуги:");?>
</span>
<br>
<span class="contract_center_text" >
    <?= Html::encode($model->summa).' грн.';?>
</span>
<br>
<hr class="inf_line_main">
<br>
<span class="contract_center_text_bold" >
    <?= Html::encode("Адреса виконання робіт:");?>
</span>
<br>
<span class="contract_center_text" >
    <?= Html::encode($model->adres);?>
</span>
<br>
<hr class="inf_line_main">
<br>
<span class="contract_center_text_bold" >
    <?= Html::encode("Бажана дата виконання роботи:");?>
</span>
<br>
<span class="contract_center_text" >
    <?php 
    if(!empty($model->date_z)) 
        echo Html::encode(changeDateFormat($model->date_z, 'd.m.Y'));
    ?>
</span>
<br>
<hr class="inf_line_main">
<br>
<span class="contract_center_text_bold" >
    <?= Html::encode("Телефон споживача:");?>
</span>
<br>
<span class="contract_center_text" >
    <?= Html::encode($model->tel);?>
</span>
<br>
<br>
<br>
<br>

    <code><?//= __FILE__ ?></code>

<!--</div>-->
