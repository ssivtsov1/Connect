<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<div class="user-form">

    <?php $form = ActiveForm::begin([
        'options' => ['enctype' => 'multipart/form-data'],
        'enableAjaxValidation' => false,]); ?>
  
    <?= $form->field($model, 'status')->textInput(['onDblClick' => 'rmenu($(this).val(),"#status_sch-nazv")']) ?>
      <div class='rmenu' id='rmenu-status_sch-nazv'></div>  
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'ОК' : 'OK', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
