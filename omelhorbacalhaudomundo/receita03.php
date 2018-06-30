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
        <h2>Bacalhau com Creme ao Forno</h2>
        <img src="receitas/03-bacalhau-com-creme-ao-forno.jpg" alt="Bacalhau Cozido" width="838" height="470" class="photo-destaque" /> </div>
      <div id="receita">
        <div id="receita-l">
          <h3>Modo de Preparo</h3>
          <p class="passos">1. Na véspera, lave o bacalhau com muita água e corte em cubos formidáveis</p>
          <p class="passos">2.  Coloque em imersão numa tigela  d’água e reserve por 8 horas</p>
          <p class="passos">2.1 Troque a água da tigela por 3 ou 4 vezes</p>
          <p class="passos">3. Após isso escorra e desfie em lascas, deixando-o separado</p>
          <p class="passos">4. Em uma panela grande, aqueça o leite de sojal até ferver</p>
          <p class="passos">4.1 Cozinhe os dois juntos por 10 minutos depois escorra e separe-os em tigelas</p>
          <p class="passos">5. Preaqueça o forno em temperatura média 180ºC</p>
          <p class="passos">6. Em uma panela grande, aqueça o azeite e refogue as cebolas e o alho até dourarem.</p>
          <p class="passos">7. Junte o pimentão, as lascas de bacalhau e refogue por mais 5 minutos ou até secar. o líquido</p>
          <p class="passos">8. Polvilhe a farinha de trigo no refogado e junte 3 xícaras (chá) do leite original.</p>
          <p class="passos">9. Tempere com o sal, o louro em pó e a cebolinha e cozinhe em fogo médio.</p>
          <p class="passos">10. Mexa bastante até engrossar, após isso, retire do fogo e reserve.</p>
          <p class="passos">11. Coloque o restante do leite reservado em um prato fundo com as fatias de pão.</p>
          <p class="passos">12. Espalhe as fatias de pão no fundo do refratário e cubra com metade do refogado de bacalhau</p>
          <p class="passos">13. Repita as camadas com o restante do pão e do bacalhau</p>
          <p class="passos">14. Leve ao forno por 30 minutos ou até começar a dourar e depois é só servir.</p>
        </div>
        <div id="receita-r">
          <h4>Origem do prato</h4>
          <p>Criado e aperfeiçoado pelos grandes cheffs de culinária mediterrânea e especialistas em pescados e frutos do mar, esse prato é a combinação perfeita para um almoço romântico ou em família.</p>
          <p class="info rtempo">1 dia de preparo</p>
          <p class="info rforno">Forno Tradicional</p>
          <p class="info rserve">Serve 4 pessoas</p>
          <p class="info racompanha">Acompanha Vinho Tinto</p>
         <p style="text-align:center"><a class="fancybox fancybox.iframe" href="ingredientes03.php"><img src="images/receita-ingredientes.png" title="Lista de Igredientes" /></a></p>
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
        <img src="vinhos/vinho-chileno-undurraga-merlot.jpg" width="150" height="230" alt="Vinho" align="right" />
        <h5 class="flag-chile">Undurraga Merlot</h5>
        <p>Aromas de amora e cereja são as que dominam o perfil aromático, complementados por realces florais e características suaves e bem integradas de terra úmida e champignon, e que fazem parte da tipicidade desta fascinante uva.</p>
        <p class="infos">Quantidade: 750ml<br />
          Tipo: Chardonnay<br />
        </p>
        <p class="infos">&nbsp;</p>
        <p><a href="http://www.bancadoramon.com.br/vinho-chileno-undurraga-merlot-750ml.html" target="_blank"><img src="images/btn-comprar-bebida.png" width="183" height="37" alt="Comprar Bebida" /></a></p>
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