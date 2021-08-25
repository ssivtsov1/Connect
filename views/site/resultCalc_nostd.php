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
                <th width="150px">Складова частина</th>
                <th width="150px">грн/1кВт.</th>
                <th width="150px">Сума, грн.</th> 
                <th width="150px">Сума ПДВ, грн.</th>
                <th width="150px">Сума з ПДВ, грн.</th>
            </tr>
            </thead>
            <tbody>

            <tr>
                <td><?= Html::encode('Підключення') ?></td>
                <td><?= $model[0]->cost ?></td>
                <td><?= $cost ?></td>
                <td><?= $cost_nds ?></td>
                <td><?= $cost_all ?></td>
            </tr>

            </tbody>
        </table>

    <table width="600px" class="table table-bordered table-hover table-condensed ">
        <thead>
        <tr>
            <th width="150px">Складова частина</th>
            <th width="150px">грн/1 м.</th>
            <th width="150px">Сума, грн.</th>
            <th width="150px">Сума ПДВ, грн.</th>
            <th width="150px">Сума з ПДВ, грн.</th>
        </tr>
        </thead>
        <tbody>

        <tr>
            <td><?= Html::encode('Лінійна частина') ?></td>
            <td><?= $model[0]->cost_line ?></td>
            <td><?= $cost_line ?></td>
            <td><?= $cost_nds_line ?></td>
            <td><?= $cost_all_line ?></td>
        </tr>


        </tbody>
    </table>

    <table width="600px" class="table table-bordered table-hover table-condensed ">
        <thead>
        <tr>
            <th width="150px"></th>
            <th width="150px"> </th>
            <th width="150px">Сума, грн.</th>
            <th width="150px">Сума ПДВ, грн.</th>
            <th width="150px">Сума з ПДВ, грн.</th>
        </tr>
        </thead>

        <tr>
            <td><?= Html::encode('Всього') ?></td>
            <td><?= Html::encode(' ') ?></td>
            <td><?= $cost_total ?></td>
            <td><?= $cost_total_nds ?></td>
            <td><?= $cost_total_all ?></td>
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
if($place==0) $place_='На межі земельної ділянки';
if($place==1) $place_='На земельній ділянці';
?>
   
        <table class="table table-bordered table-hover table-condensed ">
            <thead>
            <tr>
                <th width="100px">Місцевість</th>
                <th width="100px">Потужність, кВт</th>
                <th width="100px">Категорія надійності</th>
                <th width="100px">Напруга</th>
                <th width="100px">РЕМ</th>
                <th width="100px">Вид підключення</th>
                <th width="100px">Тип лінії</th>
                <th width="100px">Розташування точки приєднання</th>
                <th width="100px">Відстань, м</th>
            </tr>
            </thead>
            <tbody>

            <tr>
                <td><?= $town ?></td>
                <td><?= $power ?></td>
                <td><?= 4-$reliability ?></td>
                <td><?= $v ?></td>
                <td><?= $model[0]->nazv ?></td>
                <td><?= $model[0]->energy ?></td>
                <td><?= $model[0]->line ?></td>
                <td><?= $place_ ?></td>
                <td><?= $dist ?></td>
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



