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
        <h2>Rocambole de Bacalhau</h2>
        <img src="receitas/06-rocambole-de-bacalhau.jpg" alt="Rocambole de Bacalhau" width="838" height="470" class="photo-destaque" /> </div>
      <div id="receita">
        <div id="receita-l">
          <h3>Modo de Preparo</h3>
          <p class="passos">1.  Coloque em uma panela 20 g de manteiga, 1 colher (sopa) de cebola picadinha e leve ao fogo médio até dourar.</p>
          <p class="passos">2.  Adicione 200 g de cenoura ralada no ralo grosso, 30 g de azeitona preta picada, 1 gema e 2 colheres (sopa) de amido de milho (15 g) já dissolvido em 500 ml de leite.</p>
          <p class="passos">2.1 Misture bem até engrossar por cerca de 10 minutos.</p>
          <p class="passos">3. Desligue o fogo, junte cebolinha picadinha e sal a gosto, misture e deixe esfriar.</p>
          <p class="passos">3.1 Agora com o recheio pronto, separe e reserve-o em ambiente fresco e vamos começar a produzir a massa.</p>
          <p class="passos">4. Em uma panela com 200 ml de azeite aquecido refogue 1 cebola picadinha e 1 colher (sopa) de alho amassado.</p>
          <p class="passos">5. Acrescente 500 g de bacalhau dessalgado e desfiado, 600 g de batata cozida, descascada e amassada e suco de 1 limão,</p>
          <p class="passos">6. Coloque sal e pimenta-do-reino moída a gosto e misture bem até formar um creme grosso e soltar do fundo da panela.</p>
          <p class="passos">7. Transfira a massa de bacalhau para uma assadeira retangular de 40x30 untada com manteiga e levemente polvilhada com farinha de rosca</p>
          <p class="passos">8. Espalhe bem a massa com as costas de uma colher por toda a assadeira.</p>
          <p class="passos">9. Leve ao forno médio pré-aquecido a 180°C por 30 minutos.</p>
          <p class="passos">10. Retire do forno, desenforme sobre um pano de prato seco polvilhado com farinha de rosca e deixe esfriar.</p>
          <p class="passos">11. Depois que a massa esfriou distribua o recheio de cenoura e enrole bem a massa com a ajuda do pano de prato formando um rocambole.</p>
          <p class="passos">12. Leve para a geladeira para firmar por mais ou menos 2 horas e sirva a mesa.</p>
        </div>
        <div id="receita-r">
          <h4>Origem do prato</h4>
          <p>O rocambole de bacalhau é uma receita antiga de Portugal, de uma cidade chamada Coimbra, localizada próxima de Lisboa. O prato foi criado para lanches e acompanha um bom vinho tinto e água fresca. Aprecie essa iguaria européia.</p>
          <p class="info rtempo">02h00 de preparo</p>
          <p class="info rforno">Forno Tradicional</p>
          <p class="info rserve">Serve 6 pessoas</p>
          <p class="info racompanha">Acompanha Vinho Tinto</p>
          <p style="text-align:center"><a href="#"><img src="images/receita-ingredientes.png" title="Lista de Igredientes" /></a></p>
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
        <img src="vinhos/vinho-chileno-casa-del-maipo-carbenet-sauignon.jpg" width="280" height="280" alt="Vinho" align="right" />
        <h5 class="flag-chile">Vinho Chileno Casa del Maipo Cabernet Sauvignon</h5>
        <p>Casas Del Maipo selecionou para você os melhores vinhos do vale Limari para oferecer este elegante e equilibrado Cabernet Sauvignon de cor vermelho rubi e aromas de frutas vermelhas.</p>
        <p class="infos">Quantidade: 750ml<br />
          Tipo: Mallbec<br />
        </p>
        <p class="infos">&nbsp;</p>
        <p><a href="http://www.bancadoramon.com.br/vinho-casa-del-maipo-cabernet-sauvignon-750-ml.html" target="_blank"><img src="images/btn-comprar-bebida.png" width="183" height="37" alt="Comprar Bebida" /></a></p>
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