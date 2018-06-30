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
					href : 'ingredientes.php',
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
  <?php include('header.php');?>
  <div id="main">
    <div id="main-container">
      <h1>Harmonizações Perfeitas</h1>
      <div id="content-full">
        <h2>Bacalhau Gomes de Sá</h2>
        <img src="receitas/02-bacalhau-gomes-de-sa.jpg" alt="Bacalhau Cozido" width="838" height="470" class="photo-destaque" /> </div>
      <div id="receita">
        <div id="receita-l">
          <h3>Modo de Preparo</h3>
          <p class="passos">1. Demolhe o bacalhau em um tacho e escalde com água fervendo</p>
          <p class="passos">2. Tape e abafe o recipiente com um pano e deixe uns 20 minutos</p>
          <p class="passos">3. Escorra o bacalhau, retire-lhe as peles e as espinhas e desfaça-o em lascas</p>
          <p class="passos">4. Cubra-as com leite quente em um recipiente e deixe-o imerso durante 2 horas.</p>
          <p class="passos">4.1 Corte as cebolas e o alho em rodelas e leve a alourar com um pouco de azeite.</p>
          <p class="passos">4.2 Cozinhe as batatas com pele ao mesmo tempo em que doura o tempeiro</p>
          <p class="passos">5. Descasque as batatas cuidadosamente e junte-as às rodelas do tempeiro dourado</p>
          <p class="passos">6. Junte o bacalhau escorrido e mexa tudo sem deixar refogar</p>
          <p class="passos">7. Tempere com sal, pimenta, azeite e ou vinagre se desejar</p>
          <p class="passos">8. Coloque tudo em um tabuleiro de barro e leve a forno durante 15 minutos.</p>
          <p class="passos">9. Após isso, o design fica por conta de ovos cozidos e azeitonas pretas.</p>
        </div>
        <div id="receita-r">
          <h4>Origem do prato</h4>
          <p>A tradicional receita portuguesa deste peixe, de autoria de José Luís Gomes de Sá, falecido em 1926, e na época cozinheiro do Restaurante Lisbonense, no Porto, lugar em que criou a receita.</p>
          <p class="info rtempo">03h00 de preparo</p>
          <p class="info rforno">Forno Tradicional</p>
          <p class="info rserve">Serve 4 pessoas</p>
          <p class="info racompanha">Acompanha Vinho Tinto</p>
          <p style="text-align:center"><a class="fancybox fancybox.iframe" href="ingredientes02.php"><img src="images/receita-ingredientes.png" title="Lista de Igredientes" /></a></p>
        </div>
      </div>
      <div id="receita">
      	<div id="leitores">
        <h3>Fotos dos Leitores</h3>
        <div class="photo-leitor"></div>
        <div class="photo-leitor"></div>
        <div class="photo-leitor"></div>
        <div class="photo-leitor"></div>
        <div class="photo-leitor"></div>
        <div class="photo-leitor"></div>
        <div class="botoes">
        	<div class="put"><a href="#"><img src="images/btn-enviar-foto.png" width="126" height="23" alt="Envie sua foto" /></a></div>
            <div class="share"><a href="#"><img src="images/btn-compartilhar.png" width="147" height="23" alt="Compartilhar Receita" /></a></div>
        </div>
        </div>
        <div id="bebida">
        <h3>Bebida Ideal para acompanhar seu prato</h3>
        <img src="vinhos/vinho-chileno-undurraga-cabernet-sauvignon.jpg" width="280" height="280" alt="Vinho" align="right" />
        <h5 class="flag-chile">Undurraga Cabernet Sauvignon</h5>
        <p>Muito complexo olfativamente, com frutas em compota, groselhas, violetas, trufas negras, notas balsâmicas e um fino tostado que provém da madeira.</p>
        <p class="infos">Quantidade: 750ml<br />
          Tipo: Tinto<br />
          <br />
        </p>
        <p><a href="http://www.bancadoramon.com.br/vinho-undurraga-cabernet-sauvignon-750ml.html" target="_blank"><img src="images/btn-comprar-bebida.png" width="183" height="37" alt="Comprar Bebida" /></a></p>
        </div>
      </div>
      <hr />
      <?php include('recomendacao.php') ?>
    </div>
    <?php include('sidebar-interna.php'); ?>
  </div>
</div>
<div id="footer">
  <?php include('footer.php'); ?>
</div>
</body>
</html>