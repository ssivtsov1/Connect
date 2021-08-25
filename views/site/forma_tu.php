<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii \ helpers \ ArrayHelper;

$arr1 = ['- Виберіть тип електроустановки *-','споживання','генерація'];
$arr2 = ['- Виберіть тип приєднання *-', 'стандартне приєднання', 'нестандартне приєднання "під ключ"',
    'нестандартне приєднання зпроєктуванням лінійної частини замовником',
    "нестандартне приєднання суб'єкту господарювання",
   "нестандартне приєднання тимчасових (сезонних) об'єктів",
    "нестандартне приєднання з ОСП (багатосторонній договір)"];
$arr3 = ['- Виберіть дільницю *-','Дніпро','Жовті Води','Кривий Ріг','Павлоград'];
$model->id = $id;

?>

<div class = 'test col-xs-3' >
    <h4>Створення ідентифікатора технічних умов</h4>
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>

    <?= $form->field($model, 'id')->hiddenInput()->label(false, ['style'=>'display:none']) ?>

    <?= $form->field($model, 'name1')->label('Тип електроустановки')  -> dropDownList ( $arr1 ) ?>

    <?= $form->field($model, 'name2')->label('Тип приєднання')  -> dropDownList ( $arr2 ) ?>

    <?= $form->field($model, 'rem')->label('Дільниця')  -> dropDownList ( $arr3 ) ?>

    <?= $form->field($model, 'date1')->label('Дата видачі ТУ')-> widget(\yii\jui\DatePicker::classname(), ['language' => 'uk']) ?>

    <?= $form->field($model, 'date2')->label('Дата внесення змін')-> widget(\yii\jui\DatePicker::classname(), ['language' => 'uk']) ?>

    <?= Html::submitButton('ОК',['class' => 'btn btn-success']) ?>

    <?php ActiveForm::end() ?>
</div>
