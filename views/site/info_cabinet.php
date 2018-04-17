<?php
//namespace app\models;
use yii\helpers\Html;
//use yii\widgets\ActiveForm;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use app\models\spr_res;
use app\models\proposal;
use app\models\status_con;
$this->params['breadcrumbs'][] = 'Особовий кабінет';
$priz_status=0;
$priz_opl=0;
$priz_date_z=0;
$a_new = explode(',',$info->new_inf);
foreach($a_new as $v){
    if($v=='status') $priz_status=1;
    if($v=='opl') $priz_opl=1;
    if($v=='date_z') $priz_date_z=1;;
}
$sql = 'select * from proposal where id_unique='.$info->id_unique;
        $data = proposal::findBySql($sql)->one(); 
        
        $data->new_inf = '';
        $data->message = '';
        $data->save(false);
        
?>
<script>
    
   
var priz_status = <?php echo $priz_status;?>;
var priz_opl = <?php echo $priz_opl;?>;
var priz_date_z = <?php echo $priz_date_z;?>;

 
   window.onload=function(){
    $(document).click(function(e){

	  if ($(e.target).closest("#recode-menu").length) return;

	   $("#rmenu").hide();

	  e.stopPropagation();

	  });
               
          
   }        

window.addEventListener('load', function(){
          var t=$(".type_doc").text();
          if(t=="Подані як: Звернення керівника, юридичної особи"){
              $("#tr_doc6").hide();
              $("#tr_doc7").hide();
          }
          if(t=="Подані як: Звернення фізичної особи"){
              $("#tr_doc6").hide();
              $("#tr_doc4").hide();
              $("#tr_doc5").hide();
          }
          if(t=="Подані як: Звернення представника юридичної особи"){
              $("#tr_doc7").hide();
          }
          if(t=="Подані як: Звернення представника фізичної особи"){
              $("#tr_doc7").hide();
              $("#tr_doc4").hide();
              $("#tr_doc5").hide()
          }
          if(t=="Подані як: Звернення фізичної особи підприємця"){
              $("#tr_doc5").hide();
          }

          if(priz_status==1) {
              $("#viewproposal-nazv_status").css('color','blue');
          }  
          if(priz_opl==1) {
              $("#viewproposal-opl").css('color','blue');
          }   
          if(priz_date_z==1) {
              $("#viewproposal-date_z").css('color','blue');
          }    
         });
</script>

<br>
<div class="row icab_row">
    <div class="col-lg-6" id="docs">
    <p><ins>Мої документи</ins></p>   
    <?php if($info->type_doc==1):?>
        <p class="type_doc">Подані як: Звернення керівника, юридичної особи</p>
    <?php endif; ?> 
    <?php if($info->type_doc==2):?>
        <p class="type_doc">Подані як: Звернення представника юридичної особи</p>
    <?php endif; ?>
    <?php if($info->type_doc==3):?>
        <p class="type_doc">Подані як: Звернення фізичної особи</p>
    <?php endif; ?>
    <?php if($info->type_doc==4):?>
        <p class="type_doc">Подані як: Звернення представника фізичної особи</p>
    <?php endif; ?>
    <?php if($info->type_doc==5):?>
        <p class="type_doc">Подані як: Звернення фізичної особи підприємця</p>
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
                    'id' => $info->id_unique,
                    
                ],
            ],'class' => 'btn btn-info btn-doc']); ?>
      
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
                    'id' => $info->id_unique,
                ],
            ],'class' => 'btn btn-info btn-doc']); ?>
      
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
                    'id' => $info->id_unique,
                ],
            ],'class' => 'btn btn-info btn-doc']); ?>
      
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
                    'id' => $info->id_unique,
                ],
            ],'class' => 'btn btn-info btn-doc']); ?>
      
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
                    'id' => $info->id_unique,
                ],
            ],'class' => 'btn btn-info btn-doc']); ?>
      
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
                    'id' => $info->id_unique,
                ],
            ],'class' => 'btn btn-info btn-doc']); ?>
      
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
                    'id' => $info->id_unique,
                ],
            ],'class' => 'btn btn-info btn-doc']); ?>
      
        </td>
        </tr>
    </table>
        <?php if(!empty($info->message)): ?>
        <div class="alert alert-warning">
            Увага повідомлення!
            <?php echo $info->message; ?>
        </div>
        <?php endif; ?>
    </div>    
     
    
    <div class="col-lg-4 i_cab">

    <?php $form = ActiveForm::begin([
        'options' => ['enctype' => 'multipart/form-data'],
        'enableAjaxValidation' => false,]); ?>

    <?php
        if($info->person==1):?>
            <p>Фізична особа</p>
        <?php endif; ?>   
        <?php if($info->person==2):?>
            <p>Юридична особа</p>
        <?php endif; ?>   
        <?php    
       
            echo $form->field($info, 'nazv_status')->textarea();
          
    ?>
      <?= $form->field($info, 'date_z')->widget(\yii\jui\DatePicker::classname(), [
            'language' => 'uk'
        ]) ?>     
            
     <?=$form->field($info, 'opl')->
            dropDownList([
    '0' => 'Не оплачено',
    '1' => 'Оплачено'],['disabled'=>'disabled']); ?>
        <?=$form->field($info, 'new_doc')->
            dropDownList([
    '1' => 'Нові',
    '2' => 'Зміна проекту'],['disabled'=>'disabled']); ?>
            
     <?= $form->field($info, 'contract')->textInput() ?>
        <?= $form->field($info, 'date_contract')->
        widget(\yii\jui\DatePicker::classname(), [
            'language' => 'uk'
        ]) ?>
            
    <?= $form->field($info, 'nazv')->textarea() ?>

    <?= $form->field($info, 'tel',
            ['inputTemplate' => '<div class="input-group"><span class="input-group-addon">'
            . '<span class="glyphicon glyphicon-phone"></span></span>{input}</div>'] )->textInput() ?>
        
     
    <?= $form->field($info, 'adres')->textarea(['onDblClick' => 'rmenu($(this).val(),"#viewschet-adres")']) ?>
           <div class='rmenu' id='rmenu-viewschet-adres'></div>
 
    
    <div class="form-group">
       <?= Html::submitButton($info->isNewRecord ? 'ОК' : 'OK', ['class' => $info->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>         
        
    </div>

    <?php ActiveForm::end(); ?>
    </div>
</div>


