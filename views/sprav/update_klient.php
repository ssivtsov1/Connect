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

    <?= $form->field($model, 'inn') ?>
    <?= $form->field($model, 'priz_nds')->checkbox([
        'onchange' => 'showfields(this.checked);',
        'label' => 'Платник ПДВ',
        'labelOptions' => [
            'style' => 'padding-left:20px;'
        ],
        'disabled' => false
    ]); ?>
    <?= $form->field($model, 'okpo') ?>
    <?= $form->field($model, 'regsvid') ?>
    <?= $form->field($model, 'nazv')->textarea(['rows' => 3, 'cols' => 25]) ?>
    <?= $form->field($model, 'addr')->textarea(['rows' => 3, 'cols' => 25]) ?>
    <?= $form->field($model, 'tel') ?>
    <?= $form->field($model, 'email') ?>
    
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'ОК' : 'OK', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<script>
    function showfields(p){
        if(p==1) {
            $('.field-klient-okpo').show();
            $('.field-klient-regsvid').show();
        }
        else {
            $('.field-klient-okpo').hide();
            $('.field-klient-regsvid').hide();
        }
    }
</script>
