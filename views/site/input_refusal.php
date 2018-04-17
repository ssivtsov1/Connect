<?php
/**
 * Created by PhpStorm.
 * User: ssivtsov
 * Date: 21.06.2017
 * Time: 9:43
 */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Дякуємо за звернення до ПрАТ «ПЕЕМ «ЦЕК».Будь ласка, вкажіть причину відмови.';
//$model->sel = '1';
?>

<div class="site-login">

    <h4><?= Html::encode($this->title) ?></h4>

    <div class="row">
        <div class="col-lg-3">
            <?php $form = ActiveForm::begin(['id' => 'inputdata',
                'options' => [
                    'class' => 'form-horizontal col-lg-25',
                    'enctype' => 'multipart/form-data',
                    'fieldConfig' => ['errorOptions' => ['encode' => false, 'class' => 'help-block']
                    
                ]]]); ?>

            <?= $form->field($model, 'sel')->radioList(['1' => 'Не влаштовує вартість послуги', '2' => 'Інше'],
                ['onchange' => 'f_cause($(this).find("input:checked").val());']) ?>

            <?= $form->field($model, 'cause')->textarea(['rows' => 3, 'cols' => 25,
                'onDblClick' => 'rmenu($(this).val(),"#input_refusal-cause")']) ?>
            <div class='rmenu' id='rmenu-input_refusal-cause'></div>
            <div class="form-group">
                <?= Html::submitButton('OK', ['class' => 'btn btn-primary']); ?>
            </div>

            <?php

            ActiveForm::end(); ?>
        </div>
    </div>
</div>

<script>
    function f_cause(p){
        if(p==2)
            $(".field-input_refusal-cause").show();
        else
            $(".field-input_refusal-cause").hide();
    }


    function rmenu(p){
        var y,i,c,nc='',phrase = '';

        y = p.length;
        for(i=0;i<y;i++)
        {
            c = p.substr(i,1);
            switch(c) {
                case 'q':  nc = 'й';
                            break;
                case 'w':  nc = 'ц';
                            break;
                case 'e':  nc = 'у';
                             break;
                case 'r':  nc = 'к';
                    break;
                case 't':  nc = 'е';
                    break;

                case 'y':  nc = 'н';
                    break;
                case 'u':  nc = 'г';
                    break;
                case 'i':  nc = 'ш';
                    break;
                case 'o':  nc = 'щ';
                    break;
                case 'p':  nc = 'з';
                    break;

                case '[':  nc = 'х';
                    break;
                case ']':  nc = 'ъ';
                    break;
                case 'a':  nc = 'ф';
                    break;
                case 's':  nc = 'ы';
                    break;
                case 'd':  nc = 'в';
                    break;

                case 'f':  nc = 'а';
                    break;
                case 'g':  nc = 'п';
                    break;
                case 'h':  nc = 'р';
                    break;
                case 'j':  nc = 'о';
                    break;
                case 'k':  nc = 'л';
                    break;

                case 'l':  nc = 'д';
                    break;
                case ';':  nc = 'ж';
                    break;
                case "'":  nc = 'э';
                    break;
                case 'z':  nc = 'я';
                    break;
                case 'x':  nc = 'ч';
                    break;

                case 'c':  nc = 'с';
                    break;
                case 'v':  nc = 'м';
                    break;
                case "b":  nc = 'и';
                    break;
                case 'n':  nc = 'т';
                    break;
                case 'm':  nc = 'ь';
                    break;
                case ',':  nc = 'б';
                    break;
                case '.':  nc = 'ю';
                    break;

                default:
                     nc = '';
                     break;
            }
            phrase = phrase + nc;
        }

        //alert(this.val);
//        $(this).val()
        //return phrase;
    }


</script>
    





