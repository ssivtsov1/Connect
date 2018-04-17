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
       
    function set_dist(p){   
        if(p==0)
           $('.dst').val(0);
    }
    // Показывает или прячет фазы или уровни напряжения
    // в зависимости от указанной мощности
    function proc_power(p){
        var q,qr;
        q = $("select[id=inputdataform-voltage] option").size();
        qr = $("select[id=inputdataform-reliability] option").size();
        
        if(p>16){
            if(q==4)
                $("#inputdataform-voltage :last").remove();
            if(qr==2)
                $("#inputdataform-reliability").append( $('<option value="3">1 категорія</option>'));
        }
        else
        {
            if(q==3)
               $("#inputdataform-voltage").append( $('<option value="4">110(154) кВ</option>'));
            if(qr==3)
               $("#inputdataform-reliability :last").remove();
        }  
        
        $("#inputdataform-voltage").change();
    }
    
    // Показывает или прячет фазы 
    // в зависимости от указанной мощности и выбранного напряжения
    function proc_voltage(p){
        var power,q,sw=0;
        power = $("#inputdataform-power").val(); 
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

 
 
 



