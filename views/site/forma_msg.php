<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii \ helpers \ ArrayHelper;

$model->id = $id;

?>

<div class = 'test col-xs-3' >
    <h4>Створення ідентифікатора повідомлення</h4>
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>

    <?= $form->field($model, 'id')->hiddenInput()->label(false, ['style'=>'display:none']) ?>
    <?= $form->field($model, 'date3')->label('Дата видачі повідомлення')-> widget(\yii\jui\DatePicker::classname(), ['language' => 'uk']) ?>

    <?= Html::submitButton('ОК',['class' => 'btn btn-success']) ?>

    <?php ActiveForm::end() ?>
</div>
