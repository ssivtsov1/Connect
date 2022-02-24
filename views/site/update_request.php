<?php
//namespace app\models;
use yii\helpers\Html;
//use yii\widgets\ActiveForm;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\bootstrap\Modal;
use app\models\spr_res;
use app\models\status_con;
$role = Yii::$app->user->identity->role;
$arr1 = ['- Виберіть мету приєднання *-','нове приєднання','зміна технічних параметрів'];
$arr3 = [0 => '- Виберіть дільницю *-',1 => 'Дніпро',2 => 'Жовті Води',3 => 'Кривий Ріг',4 => 'Павлоград'];
//debug($model->id);
//debug($role);
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

    $('.scrolldown').click(function() {
        // переместиться в нижнюю часть страницы

        $("html, body").animate({
            scrollTop:2000
        },400);
    })

    $('.scrollup').click(function() {
        // переместиться в верхнюю часть страницы

        $("html, body").animate({
            scrollTop:0
        },1000);
    })

    // при прокрутке окна (window)
    $(window).scroll(function() {
        // если пользователь прокрутил страницу более чем на 200px
        if ($(this).scrollTop()>200) {
            // то сделать кнопку scrollup видимой
            $('.scrollup').fadeIn();
        }
        // иначе скрыть кнопку scrollup
        else {
            $('.scrollup').fadeOut();
        }
    });


    localStorage.setItem("person", 0);
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
       $('#createproposal-tel').val(rez);
       $('#request-tel').val(rez);
   }

</script>

<br>
<?php $form = ActiveForm::begin([
    'options' => ['enctype' => 'multipart/form-data'],
    'enableAjaxValidation' => false,]); ?>

<div class="scrolldown">
    <!-- Иконка fa-chevron-up (Font Awesome) -->
    <i class="fa fa-chevron-down"></i>
    <!--            <i class="glyphicon glyphicon-eject"></i>-->
</div>

<div class="row">
    <div class="col-lg-6" id="docs">
        <? if($mode==1): ?>
        <p class="text-warning-doc">Документи (у форматі pdf):</p>
        <?= $form->field($model, 'doc1')->fileInput(); ?>
        <? if(is_doc($doc_v,1)): ?>

        <?= Html::a('...',['site/doc_request'], [
            'data' => [
                'method' => 'post',
                'params' => [
                    'doc' => 1,
                    'id' => $model->id,
                ],
            ],'class' => 'btn btn-info']); ?>
        <? endif; ?>

        <?= $form->field($model, 'doc2')->fileInput(); ?>

        <? if(is_doc($doc_v,2)): ?>

                <?= Html::a('...',['site/doc_request'], [
                    'data' => [
                        'method' => 'post',
                        'params' => [
                            'doc' => 2,
                            'id' => $model->id,
                        ],
                    ],'class' => 'btn btn-info']); ?>
            <? endif; ?>

        <?= $form->field($model, 'doc3')->fileInput(); ?>
        <? if(is_doc($doc_v,3)): ?>

                <?= Html::a('...',['site/doc_request'], [
                    'data' => [
                        'method' => 'post',
                        'params' => [
                            'doc' => 3,
                            'id' => $model->id,
                        ],
                    ],'class' => 'btn btn-info']); ?>

        <? endif; ?>

        <?= $form->field($model, 'doc4')->fileInput(); ?>
        <? if(is_doc($doc_v,4)): ?>

                <?= Html::a('...',['site/doc_request'], [
                    'data' => [
                        'method' => 'post',
                        'params' => [
                            'doc' => 4,
                            'id' => $model->id,
                        ],
                    ],'class' => 'btn btn-info']); ?>

        <? endif; ?>

        <?= $form->field($model, 'doc5')->fileInput(); ?>
        <? if(is_doc($doc_v,5)): ?>

                <?= Html::a('...',['site/doc_request'], [
                    'data' => [
                        'method' => 'post',
                        'params' => [
                            'doc' => 5,
                            'id' => $model->id,
                        ],
                    ],'class' => 'btn btn-info']); ?>

        <? endif; ?>
        <? endif; ?>

     <?php if(1==2):?>
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
     <?php endif; ?>
    </div>


    <div class="col-lg-4">


    <?php
    if ($mode == 1) {
        $tu = str_pad($model->id, 6, "0", STR_PAD_LEFT);
        $t = str_replace("-", "", str_replace(" ", "", $model->tel));
        $p = сreate_a_password($tu, $t);
    }

////    debug($p);
//    echo '<br>';
//    debug($t);
//    echo '<br>';
//    debug($tu);

        // Установка статусов в соответствии с доступами
//        switch($role) {
//        case 3: // Полный доступ
//            echo $form->field($model, 'status')->dropDownList(ArrayHelper::map(status_con::find()->all(), 'id', 'status'));
//            break;
//        }
    ?>
     <?= $form->field($model, 'mark')->label('Мета приєднання')  -> dropDownList ( $arr1 ) ?>
     <?php if($mode==1):?>
             <?=$form->field($model, 'id_tu') ?>
        <?php if($role<>5):?>
                <?= Html::a('Створити ID ТУ',['site/create_tu'], [
                    'data' => [
                        'method' => 'post',
                        'params' => [
                            'id' => $model->id,
                            'id_tu' => $model->id_tu,
                        ],
                    ],'class' => 'btn btn-info']); ?>
         <?php endif; ?>

             <?=$form->field($model, 'opl')->
                    dropDownList([
            '0' => 'Не оплачено',
            '1' => 'Оплачено']); ?>
        <?php endif; ?>

        <?= $form->field($model, 'date_opl')->widget(\yii\jui\DatePicker::classname(), [
            'language' => 'uk',
        ]) ?>

        <?= $form->field($model, 'rem')->label('Дільниця')  -> dropDownList ( $arr3 ) ?>

<!--        --><?//=$form->field($model, 'new_doc')->
//            dropDownList([
//    '1' => 'Нові',
//    '2' => 'Зміна проекту']); ?>
            
<!--     --><?//= $form->field($model, 'contract')->textInput() ?>
<!--        --><?//= $form->field($model, 'date_contract')->
//        widget(\yii\jui\DatePicker::classname(), [
//            'language' => 'uk'
//        ]) ?>
     <?php if($mode==1):?>
    <?= $form->field($model, 'edrpo')->textInput(
        ['onblur' => '$.get("' . Url::to('/Connect/web/site/getklient_edrpo?edrpo=') .
            '"+$(this).val(),
                    function(data) {
//                     alert(data.nazv); 
                    if(localStorage.getItem("person")!=2) {
                    if(data.nazv=="")
                    { //alert(11);  
                     localStorage.setItem("person", 0);
                      $(".nazv_kl").hide();
                      $(".btn-primary").removeClass("disabled");
                      $("#createproposal-nazv").text("");
                      $("#request-nazv").text("");
                      $("#createproposal-adres").text("");
                      $("#request-adres").text("");
                      $("#createproposal-tel").val("");
                      $("#request-tel").text("");
                      $("#createproposal-email").val("");
                      $("#request-email").text("");
                      $("#createproposal-inn").val("");
//                      $("#request-edrpo").text("");
                    }
                    else
                    { 
                      localStorage.setItem("person", 1);
                      $("#createproposal-nazv").text(data.nazv);
                      $("#request-nazv").text(data.nazv);
                      $("#createproposal-adres").text(data.adres);
                       $("#createproposal-adres1").text(data.adres);
                       $("#request-adres1").text(data.adres);
                      $("#createproposal-tel").val(data.tel);
                       $("#request-tel").val(data.tel);
//                      $("#createproposal-edrpo").val(data.edrpo);
                        $("#request-inn").val(data.inn);
                      $("#createproposal-email").val(data.email);
                       $("#request-email").val(data.email);
                    }   
                }
                });',
            'disabled' => true]) ?>
     <?endif;?>

        <?php if($mode==0):?>
            <?= $form->field($model, 'edrpo')->textInput(
                ['onblur' => '$.get("' . Url::to('/Connect/web/site/getklient_edrpo?edrpo=') .
                    '"+$(this).val(),
                    function(data) {
//                     alert(data.nazv); 
                    if(localStorage.getItem("person")!=2) {
                    if(data.nazv=="")
                    { //alert(11);  
                     localStorage.setItem("person", 0);
                      $(".nazv_kl").hide();
                      $(".btn-primary").removeClass("disabled");
                      $("#createproposal-nazv").text("");
                      $("#request-nazv").text("");
                      $("#createproposal-adres").text("");
                      $("#request-adres").text("");
                      $("#createproposal-tel").val("");
                      $("#request-tel").text("");
                      $("#createproposal-email").val("");
                      $("#request-email").text("");
                      $("#createproposal-inn").val("");
//                      $("#request-edrpo").text("");
                    }
                    else
                    { 
                      localStorage.setItem("person", 1);
                      $("#createproposal-nazv").text(data.nazv);
                      $("#request-nazv").text(data.nazv);
                      $("#createproposal-adres").text(data.adres);
                       $("#createproposal-adres1").text(data.adres);
                       $("#request-adres1").text(data.adres);
                      $("#createproposal-tel").val(data.tel);
                       $("#request-tel").val(data.tel);
//                      $("#createproposal-edrpo").val(data.edrpo);
                        $("#request-inn").val(data.inn);
                      $("#createproposal-email").val(data.email);
                       $("#request-email").val(data.email);
                    }   
                }
                });',
                  ]) ?>
        <?endif;?>

        <?php if($mode==1):?>
        <?= $form->field($model, 'inn')->textInput(
            ['onblur' => '$.get("' . Url::to('/Connect/web/site/getklient?inn=') .
                '"+$(this).val(),
                    function(data) {
//                     alert(data.nazv); 
                    if(localStorage.getItem("person")==0) {
                    if(data.nazv=="")
                    { //alert(11);  
                      $(".nazv_kl").hide();
                      $(".btn-primary").removeClass("disabled");
                      $("#createproposal-nazv").text("");
                      $("#request-nazv").text("");
                      $("#createproposal-adres").text("");
                      $("#request-adres").text("");
                      $("#createproposal-tel").val("");
                      $("#request-tel").text("");
                      $("#createproposal-email").val("");
                      $("#request-email").text("");
                      $("#createproposal-edrpo").val("");
                      $("#request-edrpo").text("");
                    }
                    else
                    { 
                     localStorage.setItem("person", 2);
                      $("#createproposal-nazv").text(data.nazv);
                      $("#request-nazv").text(data.nazv);
                      $("#createproposal-adres").text(data.adres);
                       $("#createproposal-adres1").text(data.adres);
                       $("#request-adres1").text(data.adres);
                      $("#createproposal-tel").val(data.tel);
                       $("#request-tel").val(data.tel);
                      $("#createproposal-edrpo").val(data.edrpo);
                        $("#request-edrpo").val(data.edrpo);
                      $("#createproposal-email").val(data.email);
                       $("#request-email").val(data.email);
                    }   
                }
                });',
                'disabled' => true]) ?>
        <?endif;?>

        <?php if($mode==0):?>
            <?= $form->field($model, 'inn')->textInput(
                ['onblur' => '$.get("' . Url::to('/Connect/web/site/getklient?inn=') .
                    '"+$(this).val(),
                    function(data) {
//                     alert(data.tel); 
                    if(localStorage.getItem("person")==0) {
                    if(data.nazv=="")
                    { //alert(11);  
                      $(".nazv_kl").hide();
                      $(".btn-primary").removeClass("disabled");
                      $("#createproposal-nazv").text("");
                      $("#request-nazv").text("");
                      $("#createproposal-adres").text("");
                      $("#request-adres").text("");
                      $("#createproposal-tel_con").val("");
                      $("#request-tel_con").text("");
                      $("#createproposal-email").val("");
                      $("#request-email").text("");
                      $("#createproposal-edrpo").val("");
                      $("#request-edrpo").text("");
                    }
                    else
                    { 
                     localStorage.setItem("person", 2);
                      $("#createproposal-nazv").text(data.nazv);
                      $("#request-nazv").text(data.nazv);
                      $("#createproposal-adres").text(data.adres);
                       $("#createproposal-adres1").text(data.adres);
                       $("#request-adres1").text(data.adres);
                      $("#createproposal-tel_con").val(data.tel);
                       $("#request-tel_con").val(data.tel);
                      $("#createproposal-edrpo").val(data.edrpo);
                        $("#request-edrpo").val(data.edrpo);
                      $("#createproposal-email").val(data.email);
                       $("#request-email").val(data.email);
                    }   
                }
                });',
                    ]) ?>
        <?endif;?>

        <?php if($mode==1): ?>
            <?= $form->field($model, 'new_cl')
            ->checkbox([
            'labelOptions' => [
            'style' => 'padding-left:20px;'
            ],
            'disabled' => false
            ]);  ?>
        <?php endif; ?>

    <?= $form->field($model, 'nazv')->textarea() ?>

<!--    --><?//= $form->field($model, 'tel',
//            ['inputTemplate' => '<div class="input-group"><span class="input-group-addon">'
//            . '<span class="glyphicon glyphicon-phone"></span></span>{input}</div>'] )->textInput() ?>

        <?= $form->field($model, 'tel_con',['inputTemplate' => '<div class="input-group"><span class="input-group-addon">'
            . '<span class="glyphicon glyphicon-phone"></span></span>{input}</div>'])->textInput(
            ['maxlength' => true,'onBlur' => 'norm_tel($(this).val())']) ?>
        
     <?php if($mode==0):?>
         <?= $form->field($model, 'adres')->textarea() ?>
     <?php endif; ?>
        <?php if($mode==1):?>
            <?= $form->field($model, 'adres')->textarea() ?>
        <?php endif; ?>
        <?= $form->field($model, 'email',
            ['inputTemplate' => '<div class="input-group"><span class="input-group-addon">'
            . '<span class="glyphicon glyphicon-envelope"></span></span>{input}</div>'])->textInput() ?>
    
     
   
    <?= $form->field($model, 'adres_con')->textarea(['onDblClick' => 'rmenu($(this).val(),"#viewschet-adres")']) ?>
           <div class='rmenu' id='rmenu-viewschet-adres'></div>

        <?php
           echo $form->field($model, 'status')->dropDownList(ArrayHelper::map(status_con::find()->all(), 'id', 'status'));
         ?>

<!--    --><?//= $form->field($model, 'date_z')->widget(\yii\jui\DatePicker::classname(), [
//            'language' => 'uk'
//        ]) ?>
        <?php if($mode==1):?>
        <?= $form->field($model, 'date1')->widget(\yii\jui\DatePicker::classname(), [
            'language' => 'uk'
        ]) ?>

        <?= $form->field($model, 'date2')->widget(\yii\jui\DatePicker::classname(), [
            'language' => 'uk'
        ]) ?>

        <?= $form->field($model, 'date3')->widget(\yii\jui\DatePicker::classname(), [
            'language' => 'uk'
        ]) ?>

        <?= $form->field($model, 'date4')->widget(\yii\jui\DatePicker::classname(), [
            'language' => 'uk'
        ]) ?>

        <?= $form->field($model, 'date5')->widget(\yii\jui\DatePicker::classname(), [
            'language' => 'uk'
        ]) ?>

        <?= $form->field($model, 'date6')->widget(\yii\jui\DatePicker::classname(), [
            'language' => 'uk'
        ]) ?>

        <?= $form->field($model, 'date7')->widget(\yii\jui\DatePicker::classname(), [
            'language' => 'uk'
        ]) ?>
        <?php endif; ?>

        <!--    --><?//= $form->field($model, 'date'
//                    )->textInput() ?>
<!---->
<!--    --><?//= $form->field($model, 'time')->textInput() ?>

        <div class="scrollup">
            <!-- Иконка fa-chevron-up (Font Awesome) -->
            <i class="fa fa-chevron-up"></i>
            <!--            <i class="glyphicon glyphicon-eject"></i>-->
        </div>

        <? if($mode==1 && $role<>5): ?>
        <?=$form->field($model, 'id_msg') ?>

        <?= Html::a('Створити ID повідомлення', ['site/create_msg'], [
                    'data' => [
                        'method' => 'post',
                        'params' => [
                            'id' => $model->id,
                            'id_msg' => $model->id_msg,
                            'id_tu' => $model->id_tu,
                        ],
                    ], 'class' => 'btn btn-info']); ?>

        <br>
        <br>
        <? endif; ?>


        <? if($role<>5): ?>
         <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'ОК' : 'OK', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <? endif; ?>

        <? if($mode==1 && $role<>5): ?>

            <?    Modal::begin([
            'header' => '<h3>Вхід в особистий кабінет</h3>',
                                                                'toggleButton' => [
                                                                'label' => 'Створити пароль',
                                                                'tag' => 'button',
                                                                'class' => 'btn btn-success',
                                                                ]
                                                                ]);
                                                                $tu=str_pad($model->id, 6, "0", STR_PAD_LEFT);
                                                                $t=str_replace("-","",str_replace(" ", "", $model->tel));
                                                                $p = сreate_a_password($tu,$t);
//                                                                echo $p;
                                                                ?>

            <table width="200px" class="table table-bordered table-hover table-condensed ">
                <th width="70px">Логін</th>
                <th width="129px">Пароль</th>
                <tr>
                    <td><?= $t ?></td>
                    <td><?= $p ?></td>
                </tr>
            </table>
            <?php
            Modal::end();


            endif; ?>

    <?php ActiveForm::end(); ?>
    </div>
</div>


