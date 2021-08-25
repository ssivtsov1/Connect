<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

$this->title = 'Заявка на підключення';
$this->params['breadcrumbs'][] = $this->title;
$model->person=1;
$model->variant1=1;
$model->variant2=1;
?>

<script>
    window.onload=function(){
        localStorage.setItem("person", 1);
        localStorage.setItem("plat_nds",false);
       // $("#klient-variant2").change();
    }
    
</script>
<div class="site-login">

    <h4><?= Html::encode($this->title) ?></h4>

<!--    <p>Введіть реквізіти:</p>-->

    <div class="row row_reg">
        <div class="col-lg-6">
            <?php $form = ActiveForm::begin(['id' => 'inputdata',
                'options' => [
                    'class' => 'form-horizontal col-lg-25',
                    'enctype' => 'multipart/form-data',
                    //'enableClientValidation' => false,
                    'fieldConfig' => ['errorOptions' => ['encode' => false, 'class' => 'help-block']
                    
                ]]]); ?>

            <?= $form->field($model, 'person')->radioList(['1' => 'Фізична особа', '2' => 'Юридична особа'],
                ['onchange' => 'showfields_person($(this).find("input:checked").val());']) ?>

            <?= $form->field($model, 'inn')->textInput() ?>
            <span class="nazv_kl"></span>

            <?= $form->field($model, 'okpo')->textInput(['maxlength' => true,'onBlur' => 'f_okpo($(this).val())']) ?>
            <?= $form->field($model, 'regsvid') ?>
            <?= $form->field($model, 'nazv')->textarea(['rows' => 1, 'cols' => 25,
                'onDblClick' => 'rmenu($(this).val(),"#klient-nazv")']) ?>
             
            
            <div class='rmenu' id='rmenu-klient-nazv'></div>
            
            <?= $form->field($model, 'addr')->textarea(['rows' => 1, 'cols' => 25,
                'onDblClick' => 'rmenu($(this).val(),"#klient-addr")']) ?>
             <div class='rmenu' id='rmenu-klient-addr'></div>  
             
            <?= $form->field($model, 'adres_post')->textarea(['rows' => 1, 'cols' => 25,
                'onDblClick' => 'rmenu($(this).val(),"#klient-adres_post")']) ?>
              <div class='rmenu' id='rmenu-klient-adres_post'></div>
             
            <?= $form->field($model, 'tel')->textInput(
                ['maxlength' => true,'onBlur' => 'norm_tel($(this).val())']) ?>
            <?= $form->field($model, 'email') ?>

            <p class="text-warning">Адреса виконання робіт:</p>
            
                        
            <?= $form->field($model, 'search_town')->textInput(
                ['autocomplete' => 'off','maxlength' => true,'onkeyup' => '$.get("' . Url::to('/Connect/web/site/get_search_town?name=') .
                    '"+$(this).val(),
                   function(data) {
                         $("#klient-id_t").empty();
                         
                         
                         
                         for(var ii = -1; ii<data.cur.length; ii++) {
                         if(ii==-1) {var q1=" ";var q2=" ";var n = 20000;
                            $("#klient-id_t").append("<option onClick="+String.fromCharCode(34)+"sel_town($(this).text(),"+n+");"
                            +String.fromCharCode(34)+" value="+n+">"+q1+"  "+q2+
                            "</option>");
                         }
                         else {
                         var q1 = data.cur[ii].town;
                         var q2 = (data.cur[ii].district)+" р-н";
//                         alert(q2);
                         var n = data.cur[ii].id;
//                         alert(q1);
//                         alert(n);
                        // if(q1==null && ii<>0) continue;
//                         var q1 = q.substr(6);
//                         var n = q.substr(0,6);
                            
                        // alert("<option value="+n+">"+q1+q2+"</option>");
                         $("#klient-id_t").append("<option onClick="+String.fromCharCode(34)+"sel_town($(this).text(),"+n+");"
                         +String.fromCharCode(34)+" value="+n+">"+q1+",  "+q2+
                         "</option>");
                         //$("#klient-id_t").append("<option value="+n+">"+"<span>"+q1+"</span>+"<span>"+q2+"</span></option>");
                         $("#klient-id_t").attr("size", ii+2);
                         //$("#klient-id_t").focus();
                         $("#klient-id_t").show();
                         $(".field-klient-id_t").show();
                         
                        }} 
                        if(data.cur.length==0) $("#klient-id_t").hide();
                  });'
                ]) ?>
            
             <?=$form->field($model, 'id_t')->
            dropDownList(['maxlength' => true,"onchange"=>"sel_town1(this,event)"]) ?>
                        
             <?= $form->field($model, 'search_street')->textInput(
                ['autocomplete' => 'off','maxlength' => true,'onkeyup' => '$.get("' . Url::to('/Connect/web/site/get_search_street?name=') .
                    '"+$(this).val()+"&str="+$("#klient-search_town").val(),
                   function(data) {
                         $("#klient-id_street").empty();
                         for(var ii = 0; ii<data.cur.length; ii++) {
                         var q1 = data.cur[ii].street;
                         var n = data.cur[ii].id;
                         var q2 = $("#klient-search_town").val();
                         //alert(q2);
//                         alert(n);
                         if(q1==null) continue;
                            
                         $("#klient-id_street").append("<option onClick="+String.fromCharCode(34)+
                         "sel_street($(this).text(),"+n+");"
                         +String.fromCharCode(34)+" value="+n+">"+q1+"</option>");
                         
//                         alert("<option onClick="+String.fromCharCode(34)+
//                         "sel_street($(this).text(),"+n+");"
//                         +String.fromCharCode(34)+" value="+n+">"+q1+"</option>");
                         
                         
                         $("#klient-id_street").attr("size", ii+2);
                         $("#klient-id_street").show();
                         $(".field-klient-id_street").show();
                        } 
                        if(data.cur.length==0) $("#klient-id_street").hide();
                  });'
                ]) ?>
            
             <?=$form->field($model, 'id_street')->
            dropDownList([]) ?>
            
            <?= $form->field($model, 'adr_flat') ?> 
            
            <p class="text-warning">Варіант подання замовлення заяви етап 1:</p>
            <?= $form->field($model, 'variant1')->radioList(['1' => "Для об'єктів , які приєднуються до електричних мереж уперше",
                '2' => "При зміні технічних параметрів об'єкта"],
                ['onchange' => 'sel_variant1($(this).find("input:checked").val());']) ?>
            
            <p class="text-warning">Варіант подання замовлення заяви етап 2:</p>
            <?= $form->field($model, 'variant2')->radioList(['1' => "Звернення керівника, юридичної особи",
                '2' => "Звернення представника юридичної особи",
                '3' => "Звернення фізичної особи",
                '4' => "Звернення представника фізичної особи",
                '5' => "Звернення фізичної особи підприємця"],
                ['onchange' => 'sel_variant2($(this).find("input:checked").val());']) ?>
            
            <p class="text-warning-doc">Документи (у форматі pdf):</p>
            <?= $form->field($model, 'doc1')->fileInput(); ?>
            <?= $form->field($model, 'doc2')->fileInput(); ?>
            <?= $form->field($model, 'doc3')->fileInput(); ?>
            <?= $form->field($model, 'doc4')->fileInput(); ?>
            <?= $form->field($model, 'doc5')->fileInput(); ?>
            <?= $form->field($model, 'doc6')->fileInput(); ?>
            <?= $form->field($model, 'doc7')->fileInput(); ?>
            
             <?php if(Yii::$app->session->hasFlash('success')):?>
                <span class="label label-success" ><?php echo Yii::$app->session->getFlash('success'); ?></span>
            <?php endif; ?>

            <?php if(Yii::$app->session->hasFlash('Error')):?>
                <span class="label label-danger" ><?php echo Yii::$app->session->getFlash('Error'); ?></span>

            <?php endif; ?>
            <?php if(!Yii::$app->session->hasFlash('Error')):?>
                <?php echo ' '; ?>

            <?php endif; ?>
            
            <?= $form->field($model, 'comment')->textarea(['rows' => 2, 'cols' => 25,
                'onDblClick' => 'rmenu($(this).val(),"#klient-comment")']) ?>
             <div class='rmenu' id='rmenu-klient-comment'></div>
            <p class="text-warning">Увага! Перевірте правильність заповнення даних.</p>
            
             <?= $form->field($model, 'verifyCode')->widget(\yii\captcha\Captcha::classname()) ?>

            <div class="form-group">
                <?= Html::submitButton('OK', ['class' => 'btn btn-primary']); ?>

            </div>
            <?php

            ActiveForm::end(); ?>
        </div>
    </div>
</div>

<script>
    function f_inn(p){
        $("#klient-inn").val(p);
    }
    function showfields(p){
        //alert(p);
        var tip = localStorage.getItem("person");
        localStorage.setItem("plat_nds",p);
        if(tip==1) {
            if (p == 1) {
                // Физ. лицо плат НДС
                //alert('aaa');
                $('.field-klient-okpo').show();
                $('.field-klient-regsvid').hide();
                $('.control-label[for=klient-nazv]').text("Прізвище, ім’я та по батькові:");
            }
            else {
                // Физ. лицо не плат НДС
                $('.field-klient-okpo').hide();
                $('.field-klient-regsvid').hide();
                $('.control-label[for=klient-nazv]').text("Прізвище, ім’я та по батькові:");
            }
        }
        if(tip==2) {
            if (p == 1) {
               // Юр. лицо плат НДС
                //alert('aaa');
                $('.field-klient-okpo').show();
                $('.field-klient-regsvid').show();
                $('.field-klient-inn').show();
                $('.control-label[for=klient-nazv]').text("Повна назва юридичної особи:");
            }

            else {
                // Юр. лицо не плат НДС
                $('.field-klient-okpo').show();
                $('.field-klient-regsvid').show();
                $('.field-klient-inn').show();
                $('.control-label[for=klient-nazv]').text("Повна назва юридичної особи:");
            }
        }
    }

// Срабатывает при изменении значения радиокнопок
    function showfields_person(p){
        
        var nds = true;
        //alert(nds);
           
           //alert($("#klient-variant2").find("input:checked").val());

        if(nds==true) {
//            Плательщик НДС
            if (p == 1) {
               // alert('Плательщик НДС физ');
                localStorage.setItem("person", 1);
                $('.field-klient-okpo').hide();
                $('.field-klient-regsvid').hide();
                $('.field-klient-inn').show();
                $('.field-klient-fio_dir').hide();
                $('.field-klient-contact_person').hide();
                $('.control-label[for=klient-addr]').text("Адреса проживання:");
                $('.control-label[for=klient-nazv]').text("Прізвище, ім’я та по батькові:");
                $('.control-label[for=klient-inn]').text("Індивід. податковий №:");
                //$('.control-label[for=klient-nazv]').text("Назва:");
            }
            else {
//                alert('Плательщик НДС юр');
                localStorage.setItem("person", 2);
                $('.field-klient-okpo').show();
                $('.field-klient-fio_dir').show();
                $('.field-klient-contact_person').show();
                $('.field-klient-regsvid').hide();
                $('.field-klient-inn').hide();
                $('.control-label[for=klient-addr]').text("Юридична адреса:");
                $('.control-label[for=klient-nazv]').text("Повна назва юридичної особи:");
                //$('.control-label[for=klient-inn]').text("ЄДРПОУ:");
            }
        }

        if(nds=='false') {
//          не плательщик НДС
            if (p == 1) {
//                alert('не Плательщик НДС физ');
                localStorage.setItem("person", 1);
                $('.field-klient-okpo').hide();
                $('.field-klient-regsvid').hide();
                $('.field-klient-fio_dir').hide();
                $('.field-klient-contact_person').hide();
                $('.field-klient-inn').hide();
                $('.control-label[for=klient-addr]').text("Адреса проживання:");
                $('.control-label[for=klient-nazv]').text("Прізвище, ім’я та по батькові:");
                $('.control-label[for=klient-inn]').text("Індивід. податковий №:");
                //$('.control-label[for=klient-nazv]').text("Назва:");
            }
            else {
//                alert('не Плательщик НДС юр');
                localStorage.setItem("person", 2);
                $('.field-klient-okpo').show();
                $('.field-klient-regsvid').hide();
                $('.field-klient-fio_dir').show();
                $('.field-klient-contact_person').show();
                $('.field-klient-inn').hide();
                $('.control-label[for=klient-addr]').text("Юридична адреса:");
                $('.control-label[for=klient-nazv]').text("Повна назва юридичної особи:");
                //$('.control-label[for=klient-inn]').text("ЄДРПОУ:");
            }
        }
    }
    
    function sel_town(elem,id) {
        localStorage.setItem("id_town", id);
        $("#klient-search_town").val(elem);
        $(".field-klient-id_t").hide();
        $("#klient-id_t").hide();
        $("#klient-search_street").val('');
        
    }
    
    function sel_street(elem,town) {
        //alert(town);
        $("#klient-search_street").val(elem);
        $(".field-klient-id_street").hide();
        $("#klient-id_street").hide();
        
    }

    function sel_town1(elem,event) {
        alert(event.keyCode);
        if(event.keyCode==13) {
            $("#klient-search_town").val(elem);
            $("#klient-id_t").hide();
        }
    }
    
    function sel_variant2(p) {
        $(".field-klient-doc1").show();
        $(".field-klient-doc2").show();
        $(".field-klient-doc3").show();
        $(".text-warning-doc").show();
        if (p == 1) {
            $(".field-klient-doc4").show();
            $(".field-klient-doc5").show();
            $(".field-klient-doc6").hide();
            $(".field-klient-doc7").hide();
        }
        if (p == 2) {
            $(".field-klient-doc4").show();
            $(".field-klient-doc5").show();
            $(".field-klient-doc6").show();
            $(".field-klient-doc7").hide();
        }
        if (p == 3) {
            $(".field-klient-doc4").hide();
            $(".field-klient-doc5").hide();
            $(".field-klient-doc6").hide();
            $(".field-klient-doc7").show();
        }
        if (p == 4) {
            $(".field-klient-doc4").hide();
            $(".field-klient-doc5").hide();
            $(".field-klient-doc6").show();
            $(".field-klient-doc7").hide();
        }
        if (p == 5) {
            $(".field-klient-doc4").show();
            $(".field-klient-doc5").hide();
            $(".field-klient-doc6").show();
            $(".field-klient-doc7").show();
        }
    }
    
    function f_okpo(p){
        $('#klient-inn').val(p);
    }
    
    function norm_tel(p){
        var y,i,c,tel = '',kod,op,flag=0,rez='';
        y = p.length;

        for(i=0;i<y;i++)
        {
            c = p.substr(i,1);
            kod=p.charCodeAt(i);
            if(kod>47 && kod<58) tel+=c;
        }
        op = tel.substr(0,3);
        y = tel.length;
        if(y<10) {
            return 1;
        }
            switch(op) {
                case '050':  flag = 1;
                    break;
                case '096':  flag = 1;
                    break;
                case '097':  flag = 1;
                    break;
                case '098':  flag = 1;
                    break;
                case '099':  flag = 1;
                    break;

                case '091':  flag = 1;
                    break;
                case '063':  flag = 1;
                    break;
                case '073':  flag = 1;
                    break;
                case '067':  flag = 1;
                    break;
                case '066':  flag = 1;
                    break;

                case '093':  flag = 1;
                    break;
                case '095':  flag = 1;
                    break;
                case '039':  flag = 1;
                    break;
                case '068':  flag = 1;
                    break;
                case '092':  flag = 1;
                    break;
                case '094':  flag = 1;
                    break;
            }

            var add = tel.substr(3,3);
            rez+=add+'-';
            add = tel.substr(6,2);
            rez+=add+'-';
            add = tel.substr(8);
            rez+=add;

        if(flag) {
            rez = op+' '+rez;
        }
        else{
            rez = '('+op+')'+' '+rez;
        }
        $('#klient-tel').val(rez);
    }

</script>
    





