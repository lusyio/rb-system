<link rel="stylesheet" href="/css/swiper.min.css">
<script src="/js/swiper.min.js"></script>
<link rel="stylesheet" href="/css/dop2.css?ver=5">
<link rel="stylesheet" href="/css/circle.css">
<div class="newmain">
	<div class="row">
		<div class="col-md-5 col-lg-4">
					<!-- Swiper -->
		<div class="swiper-container">
		    <div class="swiper-wrapper">
				<div class="swiper-slide">
					<?php include 'main/wj-proiz.php';?>
				</div>
				 <div class="swiper-slide">
					<?php include 'main/wj-finance.php'; ?>
				</div>
				<div class="swiper-slide">
					<?php include 'main/wj-otgryz.php'; ?>
				</div>
		    </div>
			<div class="swiper-button-next"></div>
			<div class="swiper-button-prev"></div>
		</div>
		
				 
				 
				<!-- Swiper -->
		
		  <script>
		    var swiper = new Swiper('.swiper-container', { 
			    autoHeight: true, //enable auto height
			  autoplay: {
			    delay: 15000,
			  },
			  loop: false,
		      navigation: {
		        nextEl: '.swiper-button-next',
		        prevEl: '.swiper-button-prev',
		      },
		    });
		  </script>
		</div>
		<div class="col-md-7 col-lg-8">
			<?php include 'main/today.php'; ?>
		</div>
	</div>
</div>