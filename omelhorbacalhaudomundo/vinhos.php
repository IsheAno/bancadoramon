<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>O melhor bacalhau do mundo | Banca do Ramon | 1933</title>
<link href="reset.css" rel="stylesheet" type="text/css" />
<link href="styles.css" rel="stylesheet" type="text/css" />
<link rel="shortcut icon" type="image/x-icon" href="favicon.ico">



<!-- Add jQuery library -->
<script type="text/javascript" src="lib/jquery-1.10.1.min.js"></script>

<!-- Add mousewheel plugin (this is optional) -->
<script type="text/javascript" src="lib/jquery.mousewheel-3.0.6.pack.js"></script>

<!-- Add fancyBox main JS and CSS files -->
<script type="text/javascript" src="source/jquery.fancybox.js?v=2.1.5"></script>
<link rel="stylesheet" type="text/css" href="source/jquery.fancybox.css?v=2.1.5" media="screen" />

<!-- Add Button helper (this is optional) -->
<link rel="stylesheet" type="text/css" href="source/helpers/jquery.fancybox-buttons.css?v=1.0.5" />
<script type="text/javascript" src="source/helpers/jquery.fancybox-buttons.js?v=1.0.5"></script>

<!-- Add Thumbnail helper (this is optional) -->
<link rel="stylesheet" type="text/css" href="source/helpers/jquery.fancybox-thumbs.css?v=1.0.7" />
<script type="text/javascript" src="source/helpers/jquery.fancybox-thumbs.js?v=1.0.7"></script>

<!-- Add Media helper (this is optional) -->
<script type="text/javascript" src="source/helpers/jquery.fancybox-media.js?v=1.0.6"></script>
<script type="text/javascript">
		$(document).ready(function() {
			/*
			 *  Simple image gallery. Uses default settings
			 */

			$('.fancybox').fancybox();

			/*
			 *  Different effects
			 */

			// Change title type, overlay closing speed
			$(".fancybox-effects-a").fancybox({
				helpers: {
					title : {
						type : 'outside'
					},
					overlay : {
						speedOut : 0
					}
				}
			});

			// Disable opening and closing animations, change title type
			$(".fancybox-effects-b").fancybox({
				openEffect  : 'none',
				closeEffect	: 'none',

				helpers : {
					title : {
						type : 'over'
					}
				}
			});

			// Set custom style, close if clicked, change title type and overlay color
			$(".fancybox-effects-c").fancybox({
				wrapCSS    : 'fancybox-custom',
				closeClick : true,

				openEffect : 'none',

				helpers : {
					title : {
						type : 'inside'
					},
					overlay : {
						css : {
							'background' : 'rgba(238,238,238,0.85)'
						}
					}
				}
			});

			// Remove padding, set opening and closing animations, close if clicked and disable overlay
			$(".fancybox-effects-d").fancybox({
				padding: 0,

				openEffect : 'elastic',
				openSpeed  : 150,

				closeEffect : 'elastic',
				closeSpeed  : 150,

				closeClick : true,

				helpers : {
					overlay : null
				}
			});

			/*
			 *  Button helper. Disable animations, hide close button, change title type and content
			 */

			$('.fancybox-buttons').fancybox({
				openEffect  : 'none',
				closeEffect : 'none',

				prevEffect : 'none',
				nextEffect : 'none',

				closeBtn  : false,

				helpers : {
					title : {
						type : 'inside'
					},
					buttons	: {}
				},

				afterLoad : function() {
					this.title = 'Image ' + (this.index + 1) + ' of ' + this.group.length + (this.title ? ' - ' + this.title : '');
				}
			});


			/*
			 *  Thumbnail helper. Disable animations, hide close button, arrows and slide to next gallery item if clicked
			 */

			$('.fancybox-thumbs').fancybox({
				prevEffect : 'none',
				nextEffect : 'none',

				closeBtn  : false,
				arrows    : false,
				nextClick : true,

				helpers : {
					thumbs : {
						width  : 50,
						height : 50
					}
				}
			});

			/*
			 *  Media helper. Group items, disable animations, hide arrows, enable media and button helpers.
			*/
			$('.fancybox-media')
				.attr('rel', 'media-gallery')
				.fancybox({
					openEffect : 'none',
					closeEffect : 'none',
					prevEffect : 'none',
					nextEffect : 'none',

					arrows : false,
					helpers : {
						media : {},
						buttons : {}
					}
				});

			/*
			 *  Open manually
			 */

			$("#fancybox-manual-b").click(function() {
				$.fancybox.open({
					//href : 'ingredientes.php',
					type : 'iframe',
					padding : 0
				});
			});
		});
	</script>
<style type="text/css">

.fancybox-custom .fancybox-skin {
	box-shadow: 0 0 50px #222;
}
</style>

</head>

<body>
<div id="container">
  <?php include('header-vinhos.php');?>
  <div id="main">
    <div id="main-container-full">
      <h1>Vinhos por Região</h1>
      <div id="regioes">
      	 <div class="flag"><img src="images/regiao/africa-do-sul.gif" width="60" height="60" title="África do Sul" /></div>
          <div class="flag"><img src="images/regiao/alemanha.gif" width="60" height="60" title="Alemanha" /></div>
          <div class="flag"><img src="images/regiao/argentina.gif" width="60" height="60" title="Argentina" /></div>
          <div class="flag"><img src="images/regiao/australia.gif" width="60" height="60" title="Austrália" /></div>
          <div class="flag"><img src="images/regiao/brasil.gif" width="60" height="60" title="Brasil" /></div>
          <div class="flag"><img src="images/regiao/chile.gif" width="60" height="60" title="Chile" /></div>
          <div class="flag"><img src="images/regiao/espanha.gif" width="60" height="60" title="Chile" /></div>
          <div class="flag"><img src="images/regiao/franca.gif" width="60" height="60" title="França" /></div>
          <div class="flag"><img src="images/regiao/italia.gif" width="60" height="60" title="Itália" /></div>
          <div class="flag"><img src="images/regiao/portugal.gif" width="60" height="60" title="Portugal" /></div>
      </div>
      <div id="clear"></div>
      <div id="head-vinho">
      	<div id="left">
        	<div id="bandeira-regiao"><h2>Chile</h2><img src="images/regiao/page-chile.png" width="131" height="131" alt="Chile" /></div>
        </div>
        <div id="right">
        	<div id="desc-regiao">
              <p>O Chile é, indubitavelmente, o país da América Latina que possui os melhores vinhos tintos elaborados com a uva Cabernet Sauvignon, alguns dos quais colocados pelos especialistas entre os melhores do mundo. Os vinhos tintos de outras uvas, especialmente a Merlot, melhoram a cada dia e alguns também já se destacam mundialmente. </p>
        	</div>
        </div>
      </div>
      <div id="clear"></div>
      <div id="vinho-full">
      	<h3 class="tinto">Casa Del Maipo - Cabernet Sauvignon</h3>
        <div id="box-vinho" class="tinto">
        	<a class="fancybox fancybox.iframe" href="vinho01.php"><img src="vinhos/vinho-chileno-casa-del-maipo-carbenet-sauignon.jpg" width="280" height="280" alt="Casa Del Maipo - Cabernet Sauvignon" /></a>
        </div>
      </div>
      <div id="vinho-full">
      	<h3 class="tinto">Marques Casa Concha  
   	    Cabernet Sauvignon</h3>
        <div id="box-vinho" class="tinto">
        	<a class="fancybox fancybox.iframe" href="vinho02.php"><img src="vinhos/vinho-chileno-marques-casa-concha-cabernet-sauvignon.jpg" width="280" height="280" alt="Marques Casa Concha " /></a>
        </div>
      </div>
      <div id="vinho-full">
      	<h3 class="tinto">Errazuriz Dom Maximiliano Reserva</h3>
        <div id="box-vinho" class="tinto">
        	<a class="fancybox fancybox.iframe" href="vinho03.php"><img src="vinhos/vinho-chileno-errazuriz-dom-maximiliano-reserva.jpg" width="280" height="280" alt="Errazuriz Dom Maximiliano Reserva" /></a>
        </div>
      </div>
      <div id="vinho-full">
      <h3 class="tinto">La Palma Camernere</h3>
        <div id="box-vinho" class="tinto">
        	<a class="fancybox fancybox.iframe" href="vinho04.php"><img src="vinhos/vinho-chileno-la-palma-camernere.jpg" width="280" height="280" alt="La Palma Camernere" /></a>
        </div>
      </div>
      <div id="vinho-full">
      <h3 class="tinto">La Capitana Shiraz</h3>
        <div id="box-vinho" class="tinto">
        	<a class="fancybox fancybox.iframe" href="vinho05.php"><img src="vinhos/vinho-chileno-la-capitana-shiraz.jpg" width="280" height="280" alt="La Capitana Shiraz" /></a>
        </div>
      </div>
      <div id="vinho-full">
      <h3 class="tinto">Casillero Del Diablo Syrah </h3>
        <div id="box-vinho" class="tinto">
        	<a class="fancybox fancybox.iframe" href="vinho06.php"><img src="vinhos/vinho-chileno-casillero-del-diablo-syrah .jpg" width="280" height="280" alt="Casillero Del Diablo Syrah " /></a>
        </div>
      </div>
      <div id="vinho-full">
      <h3 class="branco">La Palma Sauvignon Blanc </h3>
        <div id="box-vinho" class="branco">
        	<a class="fancybox fancybox.iframe" href="vinho-7.php"><img src="vinhos/vinho-chileno-la-palma-sauvignon-blanc .jpg" width="280" height="280" alt="La Palma Sauvignon Blanc " /></a>
        </div>
      </div>
      <div id="vinho-full">
      <h3 class="branco">La Palma Reserva Sauvignon Blanc</h3>
        <div id="box-vinho" class="branco">
        	<a class="fancybox fancybox.iframe" href="vinho08.php"><img src="vinhos/vinho-chileno-la-palma-reserva-sauvignon-blanc.jpg" width="280" height="280" alt="La Palma Reserva Sauvignon Blanc" /></a>
        </div>
      </div>
      <div id="vinho-full">
      <h3 class="branco">Vinho Chileno Carmen Chardonnay </h3>
        <div id="box-vinho" class="branco">
        	<a class="fancybox fancybox.iframe" href="vinho09.php"><img src="vinhos/vinho-chileno-carmen-chardonnay .jpg" width="280" height="280" alt="Vinho Chileno Carmen Chardonnay " /></a>
        </div>
      </div>
    </div>

  </div>
</div>
<div id="footer">
  <?php include('footer.php'); ?>
</div>
</body>
</html>