<?php
// Ввод данных для входа в личный кабинет

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

use yii\bootstrap\Modal;
$this->title = 'Вхід в особовий кабінет';
?>        

<div class="site-login">

    <h3><?= Html::encode($this->title) ?></h3>
   
    <div class="row">
        <div class="col-lg-3">
            <?php $form = ActiveForm::begin(['id' => 'inputdata_cabinet',
                'options' => [
                    'class' => 'form-horizontal col-lg-25',
                    'enctype' => 'multipart/form-data'
                    
                ]]); ?>
                       
             <?= $form->field($model, 'contract')->textInput() ?>
             <?= $form->field($model, 'date_contract')->
                widget(\yii\jui\DatePicker::classname(), [
              'language' => 'uk'
                ]) ?>
            
            <?php if(Yii::$app->session->hasFlash('error')): ?> 
            <div class="alert alert-danger">
                <?php echo Yii::$app->session->getFlash('error'); ?> 
            </div>        
            <?php endif; ?>  
            
            <div class="form-group">
                <?= Html::submitButton('OK', ['class' => 'btn btn-primary']); ?>

            </div>
            <?php
                ActiveForm::end();
            ?>
        </div>
    </div>
</div>

 
 
 



