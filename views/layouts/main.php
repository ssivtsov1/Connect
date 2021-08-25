<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use app\assets\AppAsset_eye;
use yii\web\Request;
use app\models\schet;
use app\models\max_schet;
use yii\helpers\Url;

/* @var $this \yii\web\View */
/* @var $content string */
$session = Yii::$app->session;
$session->open();
$switch=$session->get('switch');
$this->title = 'Приєднання до електромереж';
        
if(isset($switch)){
   
    if($switch==0)
        AppAsset::register($this);
    else
        AppAsset_eye::register($this);
}
else {
    AppAsset::register($this);
}
?>


<script>
    window.addEventListener('load', function(){
              var ua = navigator.userAgent;
              if (ua.search(/Firefox/) == -1) {
              //alert(navigator.userAgent);    
              $(".rh1").css('dicplay','none');
              $(".rh2").css('dicplay','none');
              $(".rh3").css('dicplay','none');
              $(".rh1").css('padding-left','17.1%');
              $(".rh2").css('padding-left','17.1%');
              $(".rh3").css('padding-left','17.1%');
              $(".rh1").css('dicplay','inline-block');
              $(".rh2").css('dicplay','inline-block');
              $(".rh3").css('dicplay','inline-block');}
          });          
          
</script>
    

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css"
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php
    $flag=1;
    $flag_cek=0;
    $url = Url::base('');
    $adr='192.168.55.1';
    $adr1='localhost';
    if(find_str($url,$adr)<>-1) $flag_cek=1;
    if(find_str($url,$adr1)<>-1) $flag_cek=1;
    $role=0;
    $department = '';

    if(!isset(Yii::$app->user->identity->role))
    {      $flag=0;}
    else{
    $role=Yii::$app->user->identity->role;
    $department=Yii::$app->user->identity->department;

    }

    $this->head() ?>
</head>
<body>

<?php $this->beginBody() ?>
    <div class="wrap">
        <?php


//        debug(Yii::$app->user->identity->role);
//        debug($flag);

            NavBar::begin([
                'brandLabel' => 'Приєднання до електромереж',
                'brandUrl' => Yii::$app->homeUrl,
                'options' => [
                    'class' => 'navbar-inverse navbar-fixed-top',
                ],
            ]);

        if($flag==1 || $flag_cek==1){
            if($role<>0)
            {echo Nav::widget([
                'options' => ['class' => 'navbar-nav navbar-right'],
                'items' => [
                    ['label' => 'Вхід', 'url' => str_replace('/web','',Url::toRoute('site/cek')), 'linkOptions' => ['data-method' => 'post']],
                    ['label' => 'Головна', 'url' => ['/site/createproposal']],

                    ['label' => 'Довідники', 'url' => ['/site/index'],
                        'options' => ['id' => 'down_menu'],			
                     'items' => [
                                ['label' => 'Довідник ставок на приєднання', 'url' => ['/sprav/sprav_data_con']],
                                ['label' => 'Довідник статусів на приєднання', 'url' => ['/sprav/status_con']],
                        ]],
                    ['label' => 'Адміністрування', 'url' => ['/site/index'],
                        'options' => ['id' => 'down_menu'],
                        'items' => [
                            ['label' => 'Перегляд заявок', 'url' => ['/site/viewproposal']],
                            ['label' => 'Створення заявок', 'url' => ['/site/createproposal']],
                            
                        ]],
                    ['label' => 'Розрахунок вартості', 'url' => ['/site/cnt_con']],
//                    ['label' => 'Про программу', 'url' => ['/site/about']],
                    ['label' => 'Особистий кабінет', 'url' => ['/site/cabinet']],
                    ['label' => 'Вийти', 'url' => ['/site/logout'], 'linkOptions' => ['data-method' => 'post']],

                ],
            ]);}
        else
            {echo Nav::widget([
                'options' => ['class' => 'navbar-nav navbar-right'],
                'items' => [
                    ['label' => 'Вхід', 'url' => str_replace('/web','',Url::toRoute('site/cek')), 'linkOptions' => ['data-method' => 'post']],
                    ['label' => 'Головна', 'url' => ['/site/index']],
                    ['label' => 'Адміністрування', 'url' => ['/site/index'],
                        'options' => ['id' => 'down_menu'],
                        'items' => [
                            ['label' => 'Перегляд заявок', 'url' => ['/site/viewschet']],
                           
                        ]],
                    ['label' => 'Розрахунок вартості', 'url' => ['/site/cnt_con']],
//                    ['label' => 'Про программу', 'url' => ['/site/about']],
                    ['label' => 'Особистий кабінет', 'url' => ['/site/cabinet']],
                    ['label' => 'Вийти', 'url' => ['/site/logout'], 'linkOptions' => ['data-method' => 'post']],



                ],
            ]);
        }}
        else
        {echo Nav::widget([
                'options' => ['class' => 'navbar-nav navbar-right'],
                'items' => [
                    ['label' => 'Головна', 'url' => ['/site/index']],
                    ['label' => 'Розрахунок вартості', 'url' => ['/site/cnt_con']],
                    ['label' => 'Подати заявку', 'url' => ['/site/proposal']],
                    ['label' => 'Особистий кабінет', 'url' => ['/site/cabinet']],
                    ['label' => 'Контакти', 'url' => 'https://cek.dp.ua/index.php/'
                        . 'cpojivaham/pryiednannia-do-elektromerezh/kontakty-sluzhby-pidkliuchennia.html'],
                ],
            ]);}
            NavBar::end();
        ?>

        <div class="info-main col-lg-12">   
          <div class="info-header col-lg-12">    
        <!--Вывод логотипа-->
        <? if(!strpos(Yii::$app->request->url,'/cek')): ?>
        <? if(strlen(Yii::$app->request->url)==9): ?>
        <img class="logo_site" src="web/Logo.png" alt="ЦЕК" />
        <? endif; ?>

        <? if(strlen(Yii::$app->request->url)<>9): ?>
            <img class="logo_site" src="../Logo.png" alt="ЦЕК" />
        <? endif; ?>
        <? endif; ?>

        <? if(strpos(Yii::$app->request->url,'/cek')): ?>
            <? if(strlen(Yii::$app->request->url)==12): ?>
                <img class="logo_site" src="web/Logo.png" alt="ЦЕК" />
            <? endif; ?>

            <? if(strlen(Yii::$app->request->url)<>12): ?>
                <img class="logo_site" src="../Logo.png" alt="ЦЕК" />
            <? endif; ?>
        <? endif; ?>
        <div class="name-company">              
            <p class="head_cek"><span>П<i>р</i>ат Підприємство з експлуатації електричних мереж</span>
                <s>Центральна Енергетична Компанія</s></p>
            <div>Твоє джерело енергії</div>
        </div>
        <div class="head-phone">              
        <div class="call">
        <div class="custom">
                <p><a href="tel:0800300015">0 800 300 015</a></p>
        <div><span>Call-центр</span>безкоштовно цілодобово</div></div>
            <p> 
           <div class="sw_ver"><span class="glyphicon glyphicon-eye-open"></span>
               <?php
               $switch=$session->get('switch');
                if(isset($switch)):
                    if($switch==0):
               ?>         
                    <a href="/Connect/web/switch">Версія для слабозорих</a></div>
                    <?php endif; ?>
               <?php if($switch==1): ?>
                        <a href="/Connect/web/switch">Звичайна версія</a></div>
               <?php endif; ?>
               <?php endif; ?>
               <?php if(!isset($switch)): ?>
                    <a href="/Connect/web/switch">Версія для слабозорих</a></div>
               <?php endif; ?>
                </p> 
                    <div class="clr"></div>
        </div>
        </div>
        
        <div class="logo_ESFL">
            <div class="custom">
                <p><a href="https://www.esfmc.com/ua/investirovanie/elektroenergetika.html" target="blank">
                        <? if(!strpos(Yii::$app->request->url,'/cek')): ?>
                        <img src="../logo_ESFL.png" alt="" 
                             style="display: block; margin-left: auto; margin-right: auto;" width="157" height="58">
                        <? endif; ?>
                        <? if(strpos(Yii::$app->request->url,'/cek')): ?>
                        <img src="web/logo_ESFL.png" alt="" 
                             style="display: block; margin-left: auto; margin-right: auto;" width="157" height="58">
                        <? endif; ?>
                        <span class="esfl_label">Входить до групи</span> 
                        <span class="esfl_label1">Енергетичний стандарт</span> </a></p>
            </div>
            <div class="clr"></div>
            </div>
        </div>
            
        <?php if(!$flag): ?>
            <div class="services col-lg-12">
                <? if(strpos(Yii::$app->request->url,'/cek')): ?>
                    <div class="rh3"><a href="/Connect/web/cabinet"><img src="./cab1.png" alt="" /></a>
                <? endif; ?>
                <? if(!strpos(Yii::$app->request->url,'/cek')): ?>
                        <div class="rh3"><a href="/Connect/web/cabinet"><img src="../cab1.png" alt="" /></a>
                <? endif; ?>
                            <div><a href="/Connect/web/cabinet">Особистий кабінет</a></div>
                </div>
            <? if(!strpos(Yii::$app->request->url,'/cek')): ?>
                <div class="rh2"><a href="/Connect/web/proposal"><img src="../prop1.png" alt="" /></a>
            <? endif; ?>
            <? if(strpos(Yii::$app->request->url,'/cek')): ?>
                    <div class="rh2"><a href="/Connect/web/proposal"><img src="./prop1.png" alt="" /></a>
            <? endif; ?>
                        <div><a href="/Connect/web/proposal">Подати заявку</a></div>
            </div>
                <? if(!strpos(Yii::$app->request->url,'/cek')): ?>
                        <div class="rh1"><a href="/Connect/web/cnt_con"><img src="../calc1.png" alt="" /></a>
                <? endif; ?>
                <? if(strpos(Yii::$app->request->url,'/cek')): ?>
                        <div class="rh1"><a href="/Connect/web/cnt_con"><img src="./calc1.png" alt="" /></a>
                <? endif; ?>
                                <div><a href="/Connect/web/cnt_con">Розрахунок вартості</a></div>
                </div>
            </div>
         <?php endif; ?>       
        </div>
        <div class="container">
            
            <?php if($flag): ?>
                <div class="page-header">
                    <small class="text-info">Ви зайшли як: <mark><?php echo $department; ?></mark> </small></h1>
                </div>
            <?php endif; ?>
            
                       
            <?= Breadcrumbs::widget([
                'homeLink' => ['label' => 'Головна', 'url' => '/Connect'],
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
            <?= $content ?>
        </div>
    </div>

<!--    <footer class="footer">
        <div class="container">
            <p class="pull-left">&copy; ЦЕК <?= date('Y') ?></p>
            <p class="pull-right"><?//= Yii::powered() ?></p>
        </div>
    </footer>-->

<div id="footer" class="fix">
      <div class="wrap1">
        <div class="footer-logo">
            <div class="moduletable">


            
            <div class="custom">
                <?php
               $switch=$session->get('switch');
                if(isset($switch)):
                    if($switch==0):
               ?>         
                        <p><img src="../Logo-footer.png" alt=""></p>
                    <?php endif; ?>
               <?php if($switch==1): ?>
                        <p><img class="logo-footer" src="../Logo.png" alt=""></p>
               <?php endif; ?>
               <?php endif; ?>
               <?php if(!isset($switch)): ?> 
                        <p><img src="../Logo-footer.png" alt=""></p>
                <?php endif; ?>
            </div>
            </div>
	
        </div>       
        <div class="footer-menu1">
        <div class="moduletable">
                <ul class="nav1 menu">
                <li class="item-132"><a href="https://cek.dp.ua/index.php/tovarystvo.html"><span>Про підприємство</span></a></li>
                <li class="item-133"><a href="https://cek.dp.ua/index.php/tovarystvo/kerivnytstvo.html"><span>Керівництво</span></a></li>
                <li class="item-134"><a href="https://cek.dp.ua/index.php/cpojivaham/hrafik-pryiomu-hromadian.html"><span>Графік прийому</span></a></li>
                <li class="item-135"><a href="https://cek.dp.ua/index.php/tovarystvo/strukturni-pidrozdily.html"><span>Структурні підрозділи</span></a></li>
                <li class="item-136"><a href="https://cek.dp.ua/index.php/tovarystvo/zakup/investprohrama-zakupivli.html"><span>Інвестиційна программа</span></a></li>
                
                </ul>
        </div>
	
        </div>
        <div class="footer-menu2">
                <div class="moduletable">
                    <ul class="nav1 menu">
                    <li class="item-138"><a href="https://cek.dp.ua/index.php/cpojivaham.html"><span>Споживачам</span></a></li>
                    <li class="item-139"><a href="https://cek.dp.ua/index.php/cpojivaham/pryiednannia-do-elektromerezh.html"><span>Приєднання до електромереж</span></a></li>
                    <li class="item-140"><a href="https://cek.dp.ua/index.php/cpojivaham/vidkliuchennia.html"><span>Відключення</span></a></li>
                    <li class="item-141"><a href="https://cek.dp.ua/index.php/cpojivaham/zahalna-informatsiia/nashi-posluhy.html"><span>Наші послуги</span></a></li>
                    <li class="item-143"><a href="https://cek.dp.ua/index.php/pres-tsentr.html"><span>Прес-центр</span></a></li>
                    <li class="item-137"><a href="https://cek.dp.ua/index.php/tovarystvo/personal/vakansii.html"><span>Вакансії</span></a></li>
                    </ul>
		</div>
	
        </div>
<div class="footer-cont">
<div class="moduletable">
						

<div class="custom">
    <div><span>Контакти:</span></div>
<div>вул. Дмитра Кедріна, 28,&nbsp; м. Дніпро, 49008, Україна</div>
<div class="tel-foot">
    <a href="tel:0562310384"><span>Телефон:</span> 0562 31-03-84</a>
</div>
<div><a href="tel:0562312480"><span>Факс:</span> 0562 31-24-80</a></div>
<div><a href="tel:0800300015"><span>Call-центр:</span> 0800 30-00-15</a></div>
<div><a href="mailto:kanc@cek.dp.ua"><span>E-mail:</span> kanc@cek.dp.ua</a></div>
<p class="soc1">
    <a href="http://www.facebook.com/pratpeemcek" target="_blank" class="soc">
        <img src="/images/face.png" alt="">&nbsp; </a> <a href="https://www.youtube.com/channel/UCLOQrRe56Fkcgph2SdNAfhA?disable_polymer=true" 
           target="_blank" class="yout">
        <img src="/images/youtube.png" alt=""></a></p>
</div>
</div>
	
</div>
        <div class="footer-brands">
            		<div class="moduletable">
						

<div class="custom">
<div class="part1">
<div class="original_lab1">Входить до:</div>
    <a href="http://adsoeukr.org/" target="_blank">
        <img src="../OREM_white.png" alt="" class="origin_img1" title="Асоціація операторів розподільчих електричних мереж України" width="135" height="100">
    </a>
</div>
<div class="chernigiv">
<div>Партнери:</div>
<a href="http://chernigivoblenergo.com.ua/" target="_blank"><img src="../chernigiv.png" alt=""></a></div>
<div class="vse"><a href="http://www.voe.com.ua/" target="_blank"><img src="../vce2.png" alt="vce2" width="239" height="50"></a></div>
<div class="part2"><a href="https://www.soe.com.ua/" target="_blank"><img src="../part2.png" alt=""></a></div>
<div class="part3">&nbsp;</div>
<div class="part4"><a href="http://www.en.lg.ua/" target="_blank"><img src="../Leo_white.png" alt="" title="Луганське енергетичне об`єднання" width="55" height="55"></a></div>
<div class="part4">&nbsp;</div>
<div>&nbsp;</div>
<div>&nbsp;</div>
<div>&nbsp;</div>
<div>&nbsp;</div>
<div>&nbsp;</div>
<div>&nbsp;</div>
<p>&nbsp;</p></div>
		</div>
	
        </div>
      </div>        
    </div>


<div class="footer-copy">
      <div class="wrap1">        
        	
        <div class="moduletable">
					

        <div class="custom">
            <div class="copy-text">© ПрАТ «Підприємство з експлуатації електричних мереж «Центральна енергетична компанія»</div>
                
        </div>
            <div class="clr"></div>
        </div>
	 
      </div>       
</div>


<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
