<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="format-detection" content="telephone=no">
<link rel="stylesheet" href="/css/bootstrap.min.css">
<link rel="stylesheet" href="/css/style.css?ver=24">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="/js/bootstrap.min.js"></script> <!-- htt p:/ /netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js -->
<script src="/js/custom.js?ver=1"></script>
<script src='/js/sweetalert2.all.js'></script>
<meta http-equiv="cache-control" content="no-cache">
<meta http-equiv="expires" content="0">
 <?php $url = preg_split('~\?~',$_SERVER['REQUEST_URI'])[0];?>
<script>
$(function () {
    var cur_url = '<?=$url?>';
    $('.menu li').each(function () {
        var link = $(this).find('a').attr('href');
        if (cur_url == link)
        {
            $(this).addClass('active');
        }
    });
});
</script>
</head>
<body>
<div class="jumbotron">
	<div class="row">
		<div class="col-lg-2 col-sm-12" id="sidebar">
			<div class="hidden-sm hidden-xs">
			<h1 style="margin-top: 80px">Рубеж</h1>
			<?php echo '<p>'.date("d.m.Y").'</p>'; ?>
			<hr>
			</div>
			<nav class="navbar navbar-expand-lg navbar-light">
			  <a class="navbar-brand hidden-lg" href="/">Рубеж</a>
			  <button class="navbar-toggler collapsed" type="button" data-toggle="collapse" data-target="#navbarColor03" aria-controls="navbarColor03" aria-expanded="false" aria-label="Toggle navigation" style="">
			    <i class="fas fa-bars"></i>
			  </button>
			
			  <div class="navbar-collapse collapse menu" id="navbarColor03" style="">
			    <ul class="nav flex-column w-100">
			     <li class="nav-item"> <a class="nav-link" href="/"><i class="fas fa-home"></i> Главная</a></li>
			     
			    <?php
				    
				    // Админ
					if ($iduser == 1) { $arr = ['tech-work','tech-add','tech-sclad','groupreport','tech-report','sclad','brigada','brigada-report', 'bankclient', 'vesylive', 'kassad', 'gsm', 'log', 'conf']; }
					
					// Ласковский С.В.
					if ($iduser == 2) { $arr = ['bankclient','groupreport', 'tech-work', 'sclad', 'vesylive', 'log']; }
					
					// Евдокимов
					if ($iduser == 4) { $arr = ['groupreport', 'gsm', 'kassad', 'sclad']; }
					
					// Чернюк
					if ($iduser == 5) { $arr = ['sclad']; }
					
					// Двореченец
					if ($iduser == 6) { $arr = ['vesylive']; }
					
					// Соколов
					if ($iduser == 7) { $arr = []; }
					
					// Корнеев
					if ($iduser == 8) { $arr = ['brigada-report','brigada', 'sclad', 'report']; }
					
					// Бригадиры
					if ($iduser == 9 or $iduser == 10 or $iduser == 23 or $iduser == 24) { $arr = ['report', 'sclad']; }
					
					// Вилков
					if ($iduser == 21) { $arr = ['brigada-report','brigada', 'sclad', 'report']; }
					
					// Саида 
					if ($iduser == 18 or $iduser == 26) { $arr = ['groupreport']; }
					
					// Канаев
					if ($iduser == 12) { $arr = []; }
					
					// Влада
					if ($iduser == 13) { $arr = ['groupreport','kassav']; }
					
					// Карпунин
					if ($iduser == 14) { $arr = ['gsm','groupreport']; }
					
					// Красовский
					if ($iduser == 17) { $arr = []; }
					
					// Филлипович
					if ($iduser == 20) { $arr = ['groupreport','bankclient', 'vesylive']; }
					
					// Седышев
					if ($iduser == 25) { $arr = ['tech-report', 'tech-sclad', 'tech-add', 'tech-work', 'report', 'sclad']; }
					
					foreach ($arr as $elem) {
						$html = '';
						if ($elem == 'log') { $num_log = DBOnce('COUNT(*) as count','log','datetime '.$bwnow); $name = 'Лог <span class="badge badge-secondary float-right" style=" position: relative; top: 2px; ">' . $num_log . '</span>'; $icon = 'far fa-list-alt';}
						if ($elem == 'report') { $name = 'Отчет'; $icon = 'far fa-clipboard';}
						if ($elem == 'groupreport') { $html = '<li class="nav-item position-relative"><a class="nav-link" ><i class="far fa-clipboard"></i> Отчет <i class="fas fa-angle-down float-right mt-1 mr-0"></i></a></li> 			     <ul class="drop"> 				     <li><a class="nav-link" href="/report/"><i class="fas fa-angle-right mr-0"></i>Общий</a></li> 				     <li><a class="nav-link" href="/vesy-report/"><i class="fas fa-angle-right mr-0"></i>С весовой</a></li> 			     </ul>';}
						if ($elem == 'conf') { $name = 'Настройки'; $icon = 'fas fa-cogs';}
						if ($elem == 'vesylive') { $name = 'Весы.Live'; $icon = 'fas fa-truck';}
						if ($elem == 'sclad') { $name = 'Склад'; $icon = 'fas fa-boxes';}
						if ($elem == 'gsm') { $name = 'ГСМ'; $icon = 'fas fa-plus';}
						if ($elem == 'tech-add') { $name = 'Учет запчастей'; $icon = 'fas fa-plus';}
						if ($elem == 'kassad' or $elem == 'kassav') { $name = 'Касса'; $icon = 'fas fa-plus';}
						if ($elem == 'reg') { $name = 'Учет'; $icon = 'fas fa-plus';}
						if ($elem == 'brigada') { $name = 'Добавить смены'; $icon = 'fas fa-plus';}
						if ($elem == 'bankclient') { $name = 'Банк-клиент'; $icon = 'fas fa-money-check-alt';}
						if ($elem == 'brigada-report') {$name = 'Отчет о смене'; $icon = 'fas fa-file-signature';}
						if ($elem == 'tech-report') {$name = 'Отчет механиков'; $icon = 'fas fa-file-signature';}
						
						if ($elem == 'tech-sclad') { $name = 'Склад запчастей'; $icon = 'fas fa-tools';}
						if ($elem == 'tec2h') { $html = '<li class="nav-item position-relative"><a class="nav-link" ><i class="fas fa-wrench"></i> Обслуживание <i class="fas fa-angle-down float-right mt-1"></i></a></li>
							<ul class="drop">
								<li><a class="nav-link" href="/tech-work/"><i class="fas fa-angle-right mr-0"></i>Работы</a></li>
								<li><a class="nav-link" href="/tech-tech/"><i class="fas fa-angle-right mr-0"></i>Техника</a></li>
								<li><a class="nav-link" href="/tech-mech/"><i class="fas fa-angle-right mr-0"></i>Механики</a></li>
							</ul>';}
						if ($elem == 'tech') { $count_norm = DBOnce('COUNT(*) as count','tech_work','status="inwork"'); $name = 'Техника <span class="badge badge-secondary float-right" style=" position: relative; top: 2px; ">' . $count_norm . '</span>'; $icon = 'fas fa-wrench';}
						if ($elem == 'tech-work') { $count_norm = DBOnce('COUNT(*) as count','tech_work','status="inwork"'); $name = 'Техника <span class="badge badge-secondary float-right" style=" position: relative; top: 2px; ">' . $count_norm . '</span>'; $icon = 'fas fa-wrench';}
						if (empty($html)) {
						echo '<li class="nav-item"><a class="nav-link" href="/'.$elem.'/"><i class="'.$icon.'"></i> '.$name.'</a></li>';
						} else {
							echo $html;
						}
					}
					$url = $_SERVER['REQUEST_URI'];
				?>
				<li class="nav-item"><a class="nav-link" href="/logout.php"><i class="fas fa-sign-out-alt"></i> Выйти</a></li>
			    </ul>
			  </div>
			</nav>		
		</div>
		<div class="col-lg-10 col-sm-12" id="content">
			

			
			
