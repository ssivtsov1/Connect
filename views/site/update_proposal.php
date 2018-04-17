<?php
//namespace app\models;
use yii\helpers\Html;
//use yii\widgets\ActiveForm;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use app\models\spr_res;
use app\models\status_con;
$role = Yii::$app->user->identity->role;
?>
<script>
   window.onload=function(){
    $(document).click(function(e){

	  if ($(e.target).closest("#recode-menu").length) return;

	   $("#rmenu").hide();

	  e.stopPropagation();

	  });
               
          
   }        

window.addEventListener('load', function(){
          var t=$(".type_doc").text();
          if(t=="Звернення керівника, юридичної особи"){
              $("#tr_doc6").hide();
              $("#tr_doc7").hide();
          }
          if(t=="Звернення фізичної особи"){
              $("#tr_doc6").hide();
              $("#tr_doc4").hide();
              $("#tr_doc5").hide();
          }
          if(t=="Звернення представника юридичної особи"){
              $("#tr_doc7").hide();
          }
          if(t=="Звернення представника фізичної особи"){
              $("#tr_doc7").hide();
              $("#tr_doc4").hide();
              $("#tr_doc5").hide()
          }
          if(t=="Звернення фізичної особи підприємця"){
              $("#tr_doc5").hide();
          }
         });
</script>

<br>
<div class="row">
    <div class="col-lg-6" id="docs">
    <p><ins>Документи</ins></p>   
    <?php if($model->type_doc==1):?>
        <p class="type_doc">Звернення керівника, юридичної особи</p>
    <?php endif; ?> 
    <?php if($model->type_doc==2):?>
        <p class="type_doc">Звернення представника юридичної особи</p>
    <?php endif; ?>
    <?php if($model->type_doc==3):?>
        <p class="type_doc">Звернення фізичної особи</p>
    <?php endif; ?>
    <?php if($model->type_doc==4):?>
        <p class="type_doc">Звернення представника фізичної особи</p>
    <?php endif; ?>
    <?php if($model->type_doc==5):?>
        <p class="type_doc">Звернення фізичної особи підприємця</p>
    <?php endif; ?>
      
    <table class="table table-striped">
        <tr id="tr_doc1">
        <td>
            Заява про приєднання (з ЕЦП)
        </td>
        <td>
            <?= Html::a('...',['site/doc'], [
            'data' => [
                'method' => 'post',
                'params' => [
                    'doc' => 1,
                    'id' => $model->id_unique,
                    
                ],
            ],'class' => 'btn btn-info']); ?>
      
        </td>
        </tr>
        <tr id="tr_doc2">
        <td>
            Копії ситуаційного плану та викопіювання з топографо-геодезичного плану в масштабі 1:2000
            із зазначенням місця розташування об'єкта(об'єктів) замовника, земельної ділянки замовника або
            прогнозованої точки приєднання
        </td>
        <td>
            <?= Html::a('...',['site/doc'], [
            'data' => [
                'method' => 'post',
                'params' => [
                    'doc' => 2,
                    'id' => $model->id_unique,
                ],
            ],'class' => 'btn btn-info']); ?>
      
        </td>
        </tr>
        <tr id="tr_doc3">
        <td>
            Документ, який підтверджує право власності чи користування земельною ділянкою
        </td>
        <td>
            <?= Html::a('...',['site/doc'], [
            'data' => [
                'method' => 'post',
                'params' => [
                    'doc' => 3,
                    'id' => $model->id_unique,
                ],
            ],'class' => 'btn btn-info']); ?>
      
        </td>
        </tr>
        <tr id="tr_doc4">
        <td>
            Виписка, витяг, довідка із ЄДРПОУ
        </td>
        <td>
            <?= Html::a('...',['site/doc'], [
            'data' => [
                'method' => 'post',
                'params' => [
                    'doc' => 4,
                    'id' => $model->id_unique,
                ],
            ],'class' => 'btn btn-info']); ?>
      
        </td>
        </tr>
        <tr id="tr_doc5">
        <td>
            Статутний документ
        </td>
        <td>
            <?= Html::a('...',['site/doc'], [
            'data' => [
                'method' => 'post',
                'params' => [
                    'doc' => 5,
                    'id' => $model->id_unique,
                ],
            ],'class' => 'btn btn-info']); ?>
      
        </td>
        </tr>
        <tr id="tr_doc6">
        <td>
            Належним чином оформлена довіреність чи інший документ на право укладати договори особі,
            яка уповноважена підписувати договори 
        </td>
        <td>
            <?= Html::a('...',['site/doc'], [
            'data' => [
                'method' => 'post',
                'params' => [
                    'doc' => 6,
                    'id' => $model->id_unique,
                ],
            ],'class' => 'btn btn-info']); ?>
      
        </td>
        </tr>
        <tr id="tr_doc7">
        <td>
            Паспорт
        </td>
        <td>
            <?= Html::a('...',['site/doc'], [
            'data' => [
                'method' => 'post',
                'params' => [
                    'doc' => 7,
                    'id' => $model->id_unique,
                ],
            ],'class' => 'btn btn-info']); ?>
      
        </td>
        </tr>
    </table>
    </div>    
    <div class="col-lg-4">

    <?php $form = ActiveForm::begin([
        'options' => ['enctype' => 'multipart/form-data'],
        'enableAjaxValidation' => false,]); ?>

    <?php
        if($model->person==1):?>
            <p>Фізична особа</p>
        <?php endif; ?>   
        <?php if($model->person==2):?>
            <p>Юридична особа</p>
        <?php endif; ?>   
        <?php    
        // Установка статусов в соответствии с доступами
        switch($role) {
        case 3: // Полный доступ
            echo $form->field($model, 'status')->dropDownList(ArrayHelper::map(status_con::find()->all(), 'id', 'status'));
            break;
        }
    ?>
        
     <?=$form->field($model, 'opl')->
            dropDownList([
    '0' => 'Не оплачено',
    '1' => 'Оплачено']); ?>
        <?=$form->field($model, 'new_doc')->
            dropDownList([
    '1' => 'Нові',
    '2' => 'Зміна проекту']); ?>
            
     <?= $form->field($model, 'contract')->textInput() ?>
        <?= $form->field($model, 'date_contract')->
        widget(\yii\jui\DatePicker::classname(), [
            'language' => 'uk'
        ]) ?>
            
    <?= $form->field($model, 'okpo')->textInput() ?>
    <?= $form->field($model, 'inn')->textInput() ?>
    <?= $form->field($model, 'nazv')->textarea() ?>

    <?= $form->field($model, 'tel',
            ['inputTemplate' => '<div class="input-group"><span class="input-group-addon">'
            . '<span class="glyphicon glyphicon-phone"></span></span>{input}</div>'] )->textInput() ?>
        
        
    <?= $form->field($model, 'addr')->textarea() ?>
    <?= $form->field($model, 'email',
            ['inputTemplate' => '<div class="input-group"><span class="input-group-addon">'
            . '<span class="glyphicon glyphicon-envelope"></span></span>{input}</div>'])->textInput() ?>
    
     
   
    <?= $form->field($model, 'adres')->textarea(['onDblClick' => 'rmenu($(this).val(),"#viewschet-adres")']) ?>
           <div class='rmenu' id='rmenu-viewschet-adres'></div>
 
        
    <?= $form->field($model, 'date_z')->widget(\yii\jui\DatePicker::classname(), [
            'language' => 'uk'
        ]) ?>   
           
    <?= $form->field($model, 'date'
                    )->textInput() ?>

    <?= $form->field($model, 'time')->textInput() ?>
    <?= $form->field($model, 'comment')->textarea() ?>  
    <?= $form->field($model, 'message')->textarea(['rows' => 3, 'cols' => 25]) ?>  
   
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'ОК' : 'OK', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
    </div>
</div>


