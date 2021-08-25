<?php
use yii\helpers\Html;

$this->title = 'Заявка на підключення';
?>
<div class="site-about">
    <p>
    <?php if($is==0): ?>
         <h4><?= Html::encode("Заявку на підключення сформовано. Дякуємо за звернення до ПрАТ «ПЕЕМ «ЦЕК».") ?></h4>
          <h4><?= Html::encode("Чекайте на зворотній зв’язок з оператором.") ?></h4>
    <?php endif; ?>
    <?php if($is==1): ?>
        <h3><?= Html::encode("Така заявка вже є") ?></h3>
    <?php endif; ?>
    <?php if($is==3): ?>
        <h3><?= Html::encode("Заявку № $nazv перераховано.") ?></h3>
    <?php endif; ?>

    <?php if($is==2): ?>
        <h4><?= Html::encode("Ваша заявка в черзі на обробку. Якнайшвидше з Вами з’єднається оператор.") ?></h4>
        <h4><?= Html::encode("Чекайте на зворотній зв’язок з оператором.") ?></h4>
    <?php endif; ?>
    </p>

    <?php if($is==-1): ?>
        <?= Html::a("З’єднатись з оператором",['relat?sch='.$nazv], ['class' => 'btn btn-primary']); ?>

       
  

    <?php endif; ?>
    <code><?//= __FILE__ ?></code>
</div>
