<?php
//namespace app\models;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use app\models\spr_res;

?>

<div class="user-form">



    <?php $form = ActiveForm::begin([
        'options' => ['enctype' => 'multipart/form-data'],
        'enableAjaxValidation' => false,]); ?>



    <?= $form->field($model, 'distance')->textInput() ?>
    <?= $form->field($model, 'date_con')->
        widget(\yii\jui\DatePicker::classname(), [
        'language' => 'uk'
    ]) ?>
    <?= $form->field($model, 'date_com_expl')->
    widget(\yii\jui\DatePicker::classname(), [
        'language' => 'uk'
    ]) ?>
    <?= $form->field($model, 'date_com_dog_d')->
    widget(\yii\jui\DatePicker::classname(), [
        'language' => 'uk'
    ]) ?>
    <?= $form->field($model, 'date_com_dog_p')->
    widget(\yii\jui\DatePicker::classname(), [
        'language' => 'uk'
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'ОК' : 'OK', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>




