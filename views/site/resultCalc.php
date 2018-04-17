<?php
// Вывод результата рассчета стоимости подключения
 
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\Response;
use app\models\forExcel;

use yii\bootstrap\Modal;

?>


<div class="site-login">
    <h4><?= Html::encode("Результат розрахунку вартості підключення:") ?></h4>
    <br>
    
        <table width="600px" class="table table-bordered table-hover table-condensed ">
            <thead>
            <tr>
                <th width="150px">грн/1кВт.</th> 
                <th width="150px">Сума, грн.</th> 
                <th width="150px">Сума ПДВ, грн.</th>
                <th width="150px">Сума з ПДВ, грн.</th>
            </tr>
            </thead>
            <tbody>

            <tr>
                <td><?= $model[0]->cost ?></td>
                <td><?= $cost ?></td>
                <td><?= $cost_nds ?></td>
                <td><?= $cost_all ?></td>
            </tr>
            
            </tbody>
        </table>
</div>


<?php
Modal::begin([
'header' => '<h3>Початкові параметри розрахунку</h3>',
'toggleButton' => [
'label' => 'Початкові параметри',
'tag' => 'button',
'class' => 'btn btn-success',
]
]);
if($town=1) 
        $town = 'місто, смт';
else
        $town = 'село';
if($voltage==1) $v='0,4 кВ (220/380 B)';
if($voltage==2) $v='10(6) кВ ';
if($voltage==3) $v='35(27) кВ';
if($voltage==4) $v='110(154) кВ';
?>
   
        <table width="600px" class="table table-bordered table-hover table-condensed ">
            <thead>
            <tr>
                <th width="150px">Місцевість</th> 
                <th width="150px">Потужність, кВт</th> 
                <th width="150px">Категорія надійності</th>
                <th width="150px">Напруга</th>
                <th width="150px">Кількість фаз</th>
            </tr>
            </thead>
            <tbody>

            <tr>
                <td><?= $town ?></td>
                <td><?= $power ?></td>
                <td><?= 4-$reliability ?></td>
                <td><?= $v ?></td>
                <td><?= $q_phase ?></td>
            </tr>
            
            </tbody>
        </table>
<?php
    Modal::end();
?>
</br>
</br>
</br>
</br>



