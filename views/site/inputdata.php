<?php
// Ввод основных данных для рассчета стоимости работ и услуг

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

use yii\bootstrap\Modal;
$this->title = 'Розрахунок вартості приєднання';
$this->params['breadcrumbs'][] = 'Розрахунок вартості приєднання';
    
?>        
 <script>
             
      window.addEventListener('load', function(){

          $('.field-inputdataform-res').hide();
          $('.field-inputdataform-energy').hide();
          $('.field-inputdataform-place').hide();
          $('.field-inputdataform-type_line').hide();

          $('.field-inputdataform-dist').hide();

          $('.field-inputdataform-town1').hide();
          $('.field-inputdataform-street').hide();
          $('.field-inputdataform-flat').hide();
          $('.field-inputdataform-korp').hide();
          $('.field-inputdataform-house').hide();
          $("#inputdataform-id_street").hide();
          $(".field-inputdataform-id_t").hide();

          $('.find_res').hide();

          $("#inputdataform-region").val(3);
        
        $('.field-inputdataform-transp_cek').hide();
        localStorage.setItem("lat1", '');
        localStorage.setItem("lng1", '');
        localStorage.setItem("geo_res", '');
        localStorage.setItem("geo_lat", '');
        localStorage.setItem("geo_lng", '');
        localStorage.setItem("geo_lat_sd", '');
        localStorage.setItem("geo_lng_sd", '');
        localStorage.setItem("geo_lat_sz", '');
        localStorage.setItem("geo_lng_sz", '');
        localStorage.setItem("id_res", '');
        localStorage.setItem("usluga", '');
        //localStorage.setItem("work", '');
        localStorage.setItem("town_sz", '');
        localStorage.setItem("town_sd", '');
        var geo,y1,p1,lat,lon;
        geo = $("#inputdataform-geo").val();
        localStorage.setItem("geo_marker", '');
        localStorage.setItem("geo_k", '');
        if(geo!='') {
            y1 = geo.length;
            p1 = geo.indexOf(',') - 1
            lat = geo.substring(0, p1);
            lon = geo.substring(p1 + 2);
            localStorage.setItem("geo_lat", lat);
            localStorage.setItem("geo_lng", lon);
            localStorage.setItem("geo_lat_save", lat);
            localStorage.setItem("geo_lng_save", lon);
            localStorage.setItem("geo_marker", '('+geo+')');
            localStorage.setItem("geo_k", geo);
            $("#inputdataform-res").change();
            //initMap();
        }

        var p,u = $("#inputdataform-potrebitel").val();
        p = u.length;
        if(p!=0)
        $("#inputdataform-potrebitel").blur();
    });

   </script>
   
   <script>


       function sel_town(elem,id) {
           localStorage.setItem("id_town", id);
           //alert(elem);
           $("#inputdataform-town1").val(elem);
           $(".field-inputdataform-id_t").hide();
           $("#inputdataform-id_t").hide();
           $("#inputdataform-street").val('');

       }

       function sel_street(elem,town) {
           //alert(town);
           $("#inputdataform-street").val(elem);
           $(".field-inputdataform-id_street").hide();
           $("#inputdataform-id_street").hide();

       }

       function sel_town1(elem,event) {
           alert(event.keyCode);
           if(event.keyCode==13) {
               $("#inputdataform-town1").val(elem);
               $("#inputdataform-id_t").hide();
           }

       }



    function set_dist(p){
        if(p==0)
           $('.dst').val(0);
    }
    // Показывает или прячет фазы или уровни напряжения
    // в зависимости от указанной мощности
    function proc_power(p){
        var q,qr,w;
        p = p.replace(",", ".");
        q = $("select[id=inputdataform-voltage] option").size();
        qr = $("select[id=inputdataform-reliability] option").size();
        w=$("#inputdataform-type_connect").val();
        // alert(w);
        if(w==1){
             if(q==4) {
                $("#inputdataform-voltage :last").remove();
                $("#inputdataform-voltage :eq(2)").remove();
             }
            if(qr==2)
                $("#inputdataform-reliability").append( $('<option value="3">1 категорія</option>'));
            $(".field-inputdataform-reliability").show();
            $(".field-inputdataform-q_phase").show();
            $('.field-inputdataform-res').hide();
            $('.field-inputdataform-energy').hide();
            $('.field-inputdataform-place').hide();
            $('.field-inputdataform-type_line').hide();
            // $('.field-inputdataform-dist').hide();
            $('.find_res').hide();

            // $('.field-inputdataform-town1').hide();
            // $('.field-inputdataform-street').hide();
            // $('.field-inputdataform-flat').hide();
            // $('.field-inputdataform-korp').hide();
            // $('.field-inputdataform-house').hide();
            // $("#inputdataform-id_street").hide();
            // $(".field-inputdataform-id_t").hide();
            $(".field-inputdataform-place").hide();
            $(".field-inputdataform-type_line").hide();
        }
        else
        {
            $("#inputdataform-type_connect").val(2);
            if(q==2) {
            $("#inputdataform-voltage").append($('<option value="3">35(27) кВ</option>'));
            $("#inputdataform-voltage").append($('<option value="4">110(154) кВ</option>'));
        }
            // if(qr==3)
            //    $("#inputdataform-reliability :last").remove();
            // $(".field-inputdataform-reliability").hide();
            $(".field-inputdataform-q_phase").hide();
            $('.field-inputdataform-res').show();
            $('.field-inputdataform-energy').show();
            $('.field-inputdataform-place').show();
            $('.field-inputdataform-type_line').show();
            $('.field-inputdataform-dist').show();
            $('.find_res').show();
            $('.field-inputdataform-town1').show();
            $('.field-inputdataform-street').show();
            $('.field-inputdataform-house').show();
            $('.field-inputdataform-flat').show();
            $('.field-inputdataform-korp').show();
            $("#inputdataform-id_street").show();
            $(".field-inputdataform-id_t").show();
        }

        if(p<=16){
            //if(qr==3)
                $("#inputdataform-reliability :last").remove();
        }

        
        $("#inputdataform-voltage").change();
        $("#inputdataform-house").blur();
    }

       // Показывает или прячет фазы или уровни напряжения
       // в зависимости от выбранного соединения
       function proc_c(w) {
           var q, qr, p;

           if(w==1) p=10;
           else p=100;
           // alert(p);
           q = $("select[id=inputdataform-voltage] option").size();
           qr = $("select[id=inputdataform-reliability] option").size();
           //alert(q);
           if(p<=50){
               if(q==4) {
                   $("#inputdataform-voltage :last").remove();
                   $("#inputdataform-voltage :eq(2)").remove();
               }
               if(qr==2)
                   $("#inputdataform-reliability").append( $('<option value="3">1 категорія</option>'));
               $(".field-inputdataform-reliability").show();
               $(".field-inputdataform-q_phase").show();
               $('.field-inputdataform-res').hide();
               $('.field-inputdataform-energy').hide();
               $('.field-inputdataform-place').hide();
               $('.field-inputdataform-type_line').hide();

               $('.field-inputdataform-dist').hide();
               $('.find_res').hide();

               $('.field-inputdataform-town1').hide();
               $('.field-inputdataform-street').hide();
               $('.field-inputdataform-flat').hide();
               $('.field-inputdataform-korp').hide();
               $('.field-inputdataform-house').hide();
               $("#inputdataform-id_street").hide();
               $(".field-inputdataform-id_t").hide();
               $(".field-inputdataform-place").hide();
               $(".field-inputdataform-type_line").hide();
               var pw=$("#inputdataform-power").val();
               if (pw>50) $("#inputdataform-power").val('');
           }
           else
           {
               $("#inputdataform-type_connect").val(2);
               if(q==2) {
                   $("#inputdataform-voltage").append($('<option value="3">35(27) кВ</option>'));
                   $("#inputdataform-voltage").append($('<option value="4">110(154) кВ</option>'));
               }
               // if(qr==3)
               //    $("#inputdataform-reliability :last").remove();
               // $(".field-inputdataform-reliability").hide();
               $(".field-inputdataform-q_phase").hide();
               $('.field-inputdataform-res').show();
               $('.field-inputdataform-energy').show();
               $('.field-inputdataform-place').show();
               $('.field-inputdataform-type_line').show();
               $('.field-inputdataform-dist').show();
               $('.find_res').show();
               $('.field-inputdataform-town1').show();
               $('.field-inputdataform-street').show();
               $('.field-inputdataform-house').show();
               $('.field-inputdataform-flat').show();
               $('.field-inputdataform-korp').show();
               $("#inputdataform-id_street").show();
               $(".field-inputdataform-id_t").show();
           }

           if(p<=16){
               //if(qr==3)
               $("#inputdataform-reliability :last").remove();
           }


           $("#inputdataform-voltage").change();
           $("#inputdataform-house").blur();
       }


       // Показывает или прячет фазы
    // в зависимости от указанной мощности и выбранного напряжения
    function proc_voltage(p){
        var power,q,sw=0;
        // alert(p);
        power = $("#inputdataform-power").val();
        power = power.replace(",", ".");
        q = $("select[id=inputdataform-q_phase] option").size();
        if(p==3 && power>16)
            sw=1;
        if((p==3 || p==4) && power<=16)
            sw=2;
                
        if(sw==1){
            if(q==2)
            $("#inputdataform-q_phase :first").remove();
        }
        else
        {
            if(sw==2){
                if(q==2)
                $("#inputdataform-q_phase :first").remove();
            }
            else {
                if(q==1)
                $("#inputdataform-q_phase").prepend( $('<option value="1">Однофазне приєднання</option>'));
            }
        }    
    }

       
    function find_on_map(addr){

        localStorage.setItem("addr_work", addr);
                var addr_work = addr;
                var region = $("#inputdataform-region option:selected").text();
                if(typeof(addr_work)!='undefuned' && addr_work!=''){
                    addr_work = addr_work+region+' область';
                    var addr_request = 'https://maps.googleapis.com/maps/api/geocode/json?'+
                            'components=country:UA'+'&key='+'AIzaSyDSyQ_ATqeReytiFrTiqQAS9FyIIwuHQS4'+
                            '&address='+addr_work;
                  
                    $.getJSON('/Connect/web/site/getloc?loc='+addr_request, function(data) {

                        var lat1 = data.output.results[0].geometry.location.lat;
                        var lng1 = data.output.results[0].geometry.location.lng;
                        localStorage.setItem("lat1",lat1);
                        localStorage.setItem("lng1",lng1);
                         //var location = {lat: alat, lng: alng};

                    });

                }
       
        setTimeout(function () {
                        initMap();
                    }, 1700); // время в мс
                    

    }
    //$(document).ready(function(){
   
</script>

<div class="site-login">

    <h3><?= Html::encode($this->title) ?></h3>
    
    <div class="row calc-connect">
        <div class="col-lg-4 calc-form">
            <?php $form = ActiveForm::begin(['id' => 'inputdata',
                'options' => [
                    'class' => 'form-horizontal col-lg-25',
                    'enctype' => 'multipart/form-data'
                    
                ]]); ?>

            <?=$form->field($model, 'type_connect')->dropDownList([
                '1' => 'Стандартне',
                '2' => 'Нестандартне',
                ],['onChange' => 'proc_c($(this).val())']); ?>

             <?=$form->field($model, 'town')->dropDownList([
                        '1' => 'Місто, смт',
                        '2' => 'Село',
                        ]); ?>

                       
             <?= $form->field($model, 'power')->textInput(
                ['maxlength' => true,'onBlur' => 'proc_power($(this).val())']) ?>


            <?=$form->field($model, 'reliability')->dropDownList([
                        '1' => '3 категорія',
                        '2' => '2 категорія',
                        '3' => '1 категорія',
                        ]); ?>
            
            <?=$form->field($model, 'voltage')->dropDownList([
                        '1' => '0,4 кВ (220/380 B)',
                        '2' => '10(6) кВ',
                        '3' => '35(27) кВ',
                        '4' => '110(154) кВ' ,
                        ],['onChange' => 'proc_voltage($(this).val())']); ?>
            
            <?=$form->field($model, 'q_phase')->dropDownList([
                        '1' => 'Однофазне приєднання',
                        '2' => 'Трифазне приєднання',
                       
                        ]); ?>

            <?=$form->field($model, 'energy')->dropDownList([
                '1' => 'Споживання',
                '2' => 'Генерація',

            ]); ?>




            <?= $form->field($model, 'town1')->textInput(
                ['autocomplete' => 'off','maxlength' => true,'onkeyup' => '$.get("' . Url::to('/Connect/web/site/get_search_town?name=') .
                    '"+$(this).val(),
                   function(data) {
                         $("#inputdataform-id_t").empty();
                         
                         
                          // alert(1);
                         
                         for(var ii = -1; ii<data.cur.length; ii++) {
                         if(ii==-1) {var q1=" ";var q2=" ";var n = 20000;
                            $("#inputdataform-id_t").append("<option onClick="+String.fromCharCode(34)+"sel_town($(this).text(),"+n+");"
                            +String.fromCharCode(34)+" value="+n+">"+q1+"  "+q2+
                            "</option>");
                         }
                         else {
                         var q1 = data.cur[ii].town;
                         var q2 = (data.cur[ii].district)+" р-н";
                         var n = data.cur[ii].id;
 
                         $("#inputdataform-id_t").append("<option onClick="+String.fromCharCode(34)+"sel_town($(this).text(),"+n+");"
                         +String.fromCharCode(34)+" value="+n+">"+q1+", "+q2+
                         "</option>");
                         $("#inputdataform-id_t").attr("size", ii+2);
                        
                         $("#inputdataform-id_t").show();
                         $(".field-inputdataform-id_t").show();
                         
                        }} 
                        if(data.cur.length==0) $("#inputdataform-id_t").hide();
                  });'
                ]) ?>

            <?=$form->field($model, 'id_t')->
            dropDownList(['maxlength' => true,"onchange"=>"sel_town1(this,event)"]) ?>

            <?= $form->field($model, 'street')->textInput(
                ['autocomplete' => 'off','maxlength' => true,'onkeyup' => '$.get("' . Url::to('/Connect/web/site/get_search_street?name=') .
                    '"+$(this).val()+"&str="+$("#inputdataform-town1").val(),
                   function(data) {
                         $("#inputdataform-id_street").empty();
                         for(var ii = 0; ii<data.cur.length; ii++) {
                         var q1 = data.cur[ii].street;
                         var n = data.cur[ii].id;
                         var q2 = $("#inputdataform-town1").val();
                         //alert(q2);
//                         alert(n);
                         if(q1==null) continue;
                            
                         $("#inputdataform-id_street").append("<option onClick="+String.fromCharCode(34)+
                         "sel_street($(this).text(),"+n+");"
                         +String.fromCharCode(34)+" value="+n+">"+q1+"</option>");
                         
//                         alert("<option onClick="+String.fromCharCode(34)+
//                         "sel_street($(this).text(),"+n+");"
//                         +String.fromCharCode(34)+" value="+n+">"+q1+"</option>");
                         
                         
                         $("#inputdataform-id_street").attr("size", ii+2);
                         $("#inputdataform-id_street").show();
                         $(".field-inputdataform-id_street").show();
                        } 
                        if(data.cur.length==0) $("#inputdataform-id_street").hide();
                  });'
                ]) ?>

            <?=$form->field($model, 'id_street')->
            dropDownList([]) ?>

            <?= $form->field($model, 'house')->textInput(
                ['autocomplete' => 'off','maxlength' => true,'onblur' => '$.get("' . Url::to('/Connect/web/site/getloc?loc=') .
                    '"+"https://maps.googleapis.com/maps/api/geocode/json?"+
            "components=country:UA"+"&key="+"AIzaSyDSyQ_ATqeReytiFrTiqQAS9FyIIwuHQS4"+
            "&address="+" Дніпровська область м. "+$("#inputdataform-town1").val().replace(",","")+" "+$("#inputdataform-street").val()+" "+$(this).val(),
                   function(data) {
                        var lat1 = data.output.results[0].geometry.location.lat;
                        var lng1 = data.output.results[0].geometry.location.lng;
//                        alert(lat1);
//                        alert(lng1);
//                        alert($("#inputdataform-house").val());
                         if ($("#inputdataform-house").val()=="") {
                            $(".tp_connect").text("");
                            return;
                         }
                            
                         $.ajax({
                            url: "/Connect/web/site/geo_dist",
                        
                            data: {lat: lat1, lng: lng1},
                            type: "GET",
                            success: function(res){
                               
                                $("#inputdataform-dist").val(Math.round(res.cur[0].dist).toFixed(0));
                               
                                if (res.cur[0].tp_name==""){
                                if($("#inputdataform-power").val()<=50 && Math.round(res.cur[0].dist).toFixed(0)<=300) {
                                    $(".tp_connect").text(res.cur[0].obj+": "+res.cur[0].techplace+". Стандартне приєднання."+
                                    " "+res.cur[0].lname);
                                    
                                     $(".field-inputdataform-place").hide();
                                     $(".field-inputdataform-type_line").hide();
                                     $(".field-inputdataform-res").hide();
                                     $(".find_res").hide();
                                }    
                                else {
                                    $(".tp_connect").text(res.cur[0].obj+": "+res.cur[0].techplace+". Не стандартне приєднання."+
                                    " "+res.cur[0].lname);
                                    
                                        
                                     $(".field-inputdataform-place").show();
                                     $(".field-inputdataform-type_line").show();
                                     $(".field-inputdataform-res").show();
                                     $(".find_res").show();   
                                    
                                  }  
                                }    
                                else
                                {
                                if($("#inputdataform-power").val()<=50 && Math.round(res.cur[0].dist).toFixed(0)<=300) 
                                 {   $(".tp_connect").text(res.cur[0].obj+": "+res.cur[0].techplace+
                                    ". Підстанція "+res.cur[0].tp_name+", відстань: "+
                                        Math.round(res.cur[0].tp_dist).toFixed(0)+"м. Стандартне приєднання."+
                                        " "+res.cur[0].lname);
                                        
                                     $(".field-inputdataform-place").hide();
                                     $(".field-inputdataform-type_line").hide();
                                     $(".field-inputdataform-res").hide();
                                     $(".find_res").hide();
                                     //alert(1);
                                        
                                 }       
                                else
                                  {
                                    $(".tp_connect").text(res.cur[0].obj+": "+res.cur[0].techplace+
                                    ". Підстанція "+res.cur[0].tp_name+", відстань: "+
                                        Math.round(res.cur[0].tp_dist).toFixed(0)+"м. Не стандартне приєднання."+
                                        " "+res.cur[0].lname); 
                                        
                                     $(".field-inputdataform-place").show();
                                     $(".field-inputdataform-type_line").show();
                                     $(".field-inputdataform-res").show();
                                     $(".find_res").show();   
                                      //alert(2);
                                   }
                                }
                               
                                
                                     
                               
                            },
                            error: function (data) {
                                console.log("Error", res);
                            },
                
                        });
                   });'
                   ]) ?>


<!--            --><?//= $form->field($model, 'korp')->textInput(
//                ['autocomplete' => 'off','maxlength' => true,'onblur' => '$.get("' . Url::to('/Connect/web/site/getloc?loc=') .
//                    '"+"https://maps.googleapis.com/maps/api/geocode/json?"+
//            "components=country:UA"+"&key="+"AIzaSyDSyQ_ATqeReytiFrTiqQAS9FyIIwuHQS4"+
//            "&address="+" Дніпровська область м. "+$("#inputdataform-town1").val().replace(",","")+" "+$("#inputdataform-street").val()
//            +" "+$(#inputdataform-korp).val()+" "+$(this).val(),
//                   function(data) {
//                        var lat1 = data.output.results[0].geometry.location.lat;
//                        var lng1 = data.output.results[0].geometry.location.lng;
////                        alert(lat1);
////                        alert(lng1);
//                            if ($("#inputdataform-house").val()=="") {
//                                $(".tp_connect").text("");
//                            return;
//                         }
//                         $.ajax({
//                            url: "/Connect/web/site/geo_dist",
//
//                            data: {lat: lat1, lng: lng1},
//                            type: "GET",
//                            success: function(res){
//
//                                $("#inputdataform-dist").val(Math.round(res.cur[0].dist).toFixed(0));
//
//                             if (res.cur[0].tp_name==""){
//                                if($("#inputdataform-power").val()<=50 && Math.round(res.cur[0].dist).toFixed(0)<=300)
//                                    $(".tp_connect").text(res.cur[0].obj+": "+res.cur[0].techplace+". Стандартне приєднання."+
//                                    " "+res.cur[0].lname);
//                                else
//                                    $(".tp_connect").text(res.cur[0].obj+": "+res.cur[0].techplace+". Не стандартне приєднання."+
//                                    " "+res.cur[0].lname);
//                                }
//                                else
//                                {
//                                if($("#inputdataform-power").val()<=50 && Math.round(res.cur[0].dist).toFixed(0)<=300)
//                                    $(".tp_connect").text(res.cur[0].obj+": "+res.cur[0].techplace+
//                                    ". Підстанція "+res.cur[0].tp_name+", відстань: "+
//                                        Math.round(res.cur[0].tp_dist).toFixed(0)+"м. Стандартне приєднання."+
//                                        " "+res.cur[0].lname);
//                                else
//                                    $(".tp_connect").text(res.cur[0].obj+": "+res.cur[0].techplace+
//                                    ". Підстанція "+res.cur[0].tp_name+", відстань: "+
//                                        Math.round(res.cur[0].tp_dist).toFixed(0)+"м. Не стандартне приєднання."+
//                                        " "+res.cur[0].lname);
//                                }
//
//
//                                  if($("#inputdataform-power").val()<=50 && Math.round(res.cur[0].dist).toFixed(0)<=300)
//                                 {
//                                     $(".field-inputdataform-place").hide();
//                                     $(".field-inputdataform-type_line").hide();
//                                     $(".field-inputdataform-res").hide();
//                                     $(".find_res").hide();
//                                 }
//                                 else
//                                 {
//
//                                     $(".field-inputdataform-place").show();
//                                     $(".field-inputdataform-type_line").show();
//                                     $(".field-inputdataform-res").show();
//                                     $(".find_res").show();
//                                 }
//
//
//
//                            },
//                            error: function (data) {
//                                console.log("Error", res);
//                            },
//
//                        });
//                   });'
//                ]) ?>


            <?= $form->field($model, 'dist')->textInput(); ?>
            <div class="tp_connect"></div>

            <?=$form->field($model, 'type_line')->dropDownList([
                '1' => 'Повітряна лінія',
                '2' => 'Кабельна лінія ',

            ]); ?>

            <?=$form->field($model, 'place')->dropDownList([
                '1' => 'На межі земельної ділянки',
                '2' => 'На земельній ділянці',

            ]); ?>

            <? echo $form->field($model, 'res')->dropDownList(
                ArrayHelper::map(app\models\spr_res::findbysql(
                    "select id,nazv
                    from spr_res ")->all(), 'id', 'nazv')) ?>

            <div class="find_res">
                <?
                Modal::begin([
                    'header' => '<h3>Інформація для визначення РЕМ</h3>',
                    'toggleButton' => [
                        'label' => 'Примітка для визначення РЕМ',
                        'tag' => 'button',
                        'class' => 'btn btn-success',
                    ]
                ]);
                ?>
                <?= Html::encode('Увага! Для жителів смт. Гвардійське потрібно вибрати Павлоградські РЕМ, для тих хто 
            проживає в м.Інгулець та в м.Апостолово - Криворізькі РЕМ. В інших випадках РЕМ можна визначити ') ?>
                <a href="http://cek.dp.ua/cekservice" target="_blank">тут.</a>
                <?php
                Modal::end();
                ?>
            </div>

            <div class="form-group">
                <?= Html::submitButton('OK', ['class' => 'btn btn-primary']); ?>
<!--                --><?//= Html::a('OK', ['/CalcWork/web'], ['class' => 'btn btn-success']) ?>
                
                
                   

            </div>

            <?php

                ActiveForm::end();
            ?>
            
        </div>
    </div>
</div>


<script



        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDSyQ_ATqeReytiFrTiqQAS9FyIIwuHQS4&callback=initMap&language=ru&region=UA"
        async defer>



</script>

 



