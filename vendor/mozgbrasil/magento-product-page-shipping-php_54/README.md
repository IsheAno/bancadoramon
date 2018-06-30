[checkmark]: https://raw.githubusercontent.com/mozgbrasil/mozgbrasil.github.io/master/assets/images/logos/logo_32_32.png "MOZG"
![valid XHTML][checkmark]

[requerimentos]: http://mozgbrasil.github.io/requerimentos/
[tickets]: https://cerebrum.freshdesk.com/support/tickets/new
[preco]: http://www.cerebrum.com.br/preco/
[getcomposer]: https://getcomposer.org/
[uninstall-mods]: https://getcomposer.org/doc/03-cli.md#remove
[artigo-composer]: http://mozg.com.br/ubuntu/composer
[ioncube-loader]: http://www.ioncube.com/loaders.php
[acordo]: http://mozg.com.br/acordo-licenca-usuario-final/

# Mozg\ProductPageShipping

## Sinopse

Visualização dos métodos de entrega na página do produto.

## Motivação

Atender o mercado de módulos para Magento oferecendo melhorias e um excelente suporte

## Suporte / Dúvidas

Para obter o devido suporte [Clique aqui][tickets], relatando o motivo da ocorrência o mais detalhado possível e anexe o print da tela para nosso entendimento

## Preço

[Clique aqui][preco]

## Característica técnica

É exibido na página de visualização do produto especificamente no bloco com o identificador "product.info.extrahint" o formulário para calculo do frete do produto, onde é retornado os serviços de entrega com seus respectivos valores.

## Testando na Heroku

Gostaria de apresentar o aplicativo que disponibilizei para a plataforma Heroku

Com apenas 1 clique, o aplicativo cria sua loja virtual usando a plataforma de comércio eletrônico Magento e instala os módulos da MOZG

[https://github.com/mozgbrasil/heroku-magento#descrição](https://github.com/mozgbrasil/heroku-magento#descrição)

## Instalação - Atualização - Desinstalação - Desativação

--

Sugiro "printar" as telas com todos os procedimentos executados

Envie para nós as imagens das telas na eventualidade de quaisquer dificuldades

--

Este módulo destina-se a ser instalado usando o [Composer][getcomposer]

Execute o seguinte comando no terminal, para visualizar a existencia do Composer e sua versão

	composer --version

Caso não tenha o Composer em seu ambiente, sugiro ler o seguinte artigo [Clique aqui][artigo-composer]

--

É necessário que o servidor tenha o suporte a extensão [ionCube PHP Loader][ioncube-loader]

Para visualizar se essa extensão está ativa em seu servidor

Certique se da presença do arquivo phpinfo.php na raiz do seu projeto

	<?php phpinfo(); ?>

Caso não exista o arquivo phpinfo.php na raiz do projeto Magento, crie o mesmo adicionado o conteúdo acima

Acesse o arquivo pelo browser

Em seguida pesquise pelo termo "ionCube PHP Loader"

Caso o seu servidor não tenha o suporte a extensão, entre em contato com sua empresa de hospedagem e peça para que eles ativem a extensão

Caso tenha a permissão e queira ativar a extensão, [Clique aqui][ioncube-loader]

Em "Loader Downloads API", efetue download do pacote compatível com o seu servidor

Descompacte o pacote e faça upload do arquivo "loader-wizard.php" para seu servidor, onde será demonstrado o passo a passo para a ativação da extensão

[Clique aqui](https://youtu.be/GZ2J6MLkko4) para ver os processos executados

--

Na presença do "ionCube PHP Loader" efetue o download do seguinte arquivo e coloque na raiz do seu servidor e acesse, se funcionar quer dizer que o "ionCube" está lendo esse tipo de encriptação

https://raw.githubusercontent.com/mozgbrasil/heroku-magento/master/phpinfo-ioncube-encoder10-x86-64-php_54.php

--

Para utilizar o(s) módulo(s) da MOZG é necessário aceitar o [Acordo de licença do usuário final][acordo]

--

Sugiro manter um ambiente de testes para efeito de testes e somente após os devidos testes aplicar os devidos procedimento no ambiente de produção

--

Sugiro efetuar backup da plataforma Magento e do banco de dados

--

Antes de efetuar qualquer atualização no Magento sempre mantenha o Compiler e o Cache desativado

--

Certique se da presença do arquivo composer.json na raiz do seu projeto Magento e que o mesmo tenha os parâmetros semelhantes ao modelo JSON abaixo

	{
	  "minimum-stability": "dev",
	  "prefer-stable": true,
	  "license": [
	    "proprietary"
	  ],
	  "repositories": [
	    {
	      "type": "composer",
	      "url": "https?://packages.firegento.com"
	    }
	  ],
	  "extra": {
	    "magento-root-dir": "./",
	    "magento-deploystrategy": "copy",
	    "magento-force": true
	  }
	}

Caso não exista o arquivo composer.json na raiz do projeto Magento, crie o mesmo adicionado o conteúdo acima

### Para instalar o módulo execute o comando a seguir no terminal do seu servidor no diretório do seu projeto

	composer require mozgbrasil/magento-product-page-shipping-php_54:dev-master

Você pode verificar se o módulo está instalado, indo ao backend em:

	STORES -> Configuration -> ADVANCED/Advanced -> Disable Modules Output

--

### Para atualizar o módulo execute o comando a seguir no terminal do seu servidor no diretório do seu projeto

Antes de efetuar qualquer processo que envolva atualização no Magento é recomendado manter o Compiler e Cache desativado

	composer clear-cache && composer update

Na ocorrência de erro, renomeie a pasta /vendor/mozgbrasil e execute novamente

Para checar a data do módulo execute o seguinte comando

	grep -ri --include=*.json 'time": "' ./vendor/mozgbrasil

--

### Para [desinstalar][uninstall-mods] o módulo execute o comando a seguir no terminal do seu servidor no diretório do seu projeto

	composer remove mozgbrasil/magento-product-page-shipping-php_54 && composer clear-cache && composer update

--

### Para desativar o módulo

1. Antes de efetuar qualquer processo que envolva atualização sobre o Magento é necessário manter o Compiler e Cache desativado

2. Caso queira desativar os módulos da MOZG renomeie a seguinte pasta app/code/local/Mozg

A desativação do módulo pode ser usado para detectar se determinada ocorrência tem relação com o módulo

## Como configurar o método de entrega

Para configurar o método, acesse no backend em:

	STORES -> Configuration -> MOZG -> Geral -> Visualização dos métodos de entrega na página do produto

Você terá os campos a seguir

### • **Ativar**

Para "ativar" ou "desativar" o uso do método

### • **Debug**

Ative o mesmo para visualizar os nomes dos blocos

### • **Nome do Bloco**

Insira o nome do bloco

## Perguntas mais frequentes "FAQ"

### O recurso está ativo mas não está sendo exibido
### ou
### Alterando a posição do recurso

Na configuração do método ative o debug

Será exibido varios blocos de recursos internos com seu respectivo nome

Escolha o nome do bloco que deseja que seja feito a inserção do recurso

Na configuração do método altere para um novo nome do bloco

### Como alterar o layout do recurso

Você pode adicionar o bloco abaixo em qualquer posição da página de produto e personalizar conforme sua necessidade

	<!-- MOZG -->
	<script>
	Event.observe(window, 'load', function() { 
	    $('block-shipping-form').remove();
	});
	</script>

	<div class="block" style="width:300px">
	    <div class="block-title">
	        <strong><span><?php echo $this->__('Estimate Shipping') ?></span></strong>
	    </div>
	    <div class="block-content">
	        <ul class="shipping-estimation-form" id="shipping-estimation-form">
	           <li class="item odd" id="li-estimate-country">
	              <label for="estimate_country">País</label>
	              <div class="input-box">
	                 <select name="estimate[country_id]" id="estimate_country" class="validate-select" title="País">
	                    <option value=""> </option>
	                    <option value="BR" selected="selected">Brasil</option>
	                 </select>
	              </div>
	           </li>
	           <li class="item even" id="li-estimate-region">
	              <label for="estimate_region_id">Estado</label>
	              <div class="input-box">
	                 <input type="text" class="input-text" id="_estimate_region" name="estimate[region]" value="SP">
	              </div>
	           </li>
	           <li class="item odd" id="li-estimate-city">
	              <label for="city">Cidade</label>
	              <div class="input-box">
	                 <input class="input-text" id="estimate_city" name="estimate[city]" value="São Paulo" type="text">
	              </div>
	           </li>
	           <li class="item last even" id="li-estimate-postcode">
	              <label for="estimate_postcode">CEP</label>
	              <div class="input-box">
	                 <input class="input-text validate-postcode" id="estimate_postcode" name="estimate[postcode]" value="08250-580" type="text">
	              </div>
	           </li>
	        </ul>
	        <div class="actions">
	            <div class="f-right">
	                <button onclick="estimateProductShipping('new-shipping-estimate-results');" class="button " title="Ok" type="button"><span><span>Ok</span></span></button>
	            </div>
	        </div>
	        <div id="new-shipping-estimate-results"></div>
	    </div>
	</div>
	<!-- / MOZG -->

## Contribuintes

Equipe Mozg

## License

[Comercial License](LICENSE.txt)

## Badges

[![Join the chat at https://gitter.im/mozgbrasil](https://badges.gitter.im/Join%20Chat.svg)](https://gitter.im/mozgbrasil/)
[![Latest Stable Version](https://poser.pugx.org/mozgbrasil/magento-product-page-shipping-php_54/v/stable)](https://packagist.org/packages/mozgbrasil/magento-product-page-shipping-php_54)
[![Total Downloads](https://poser.pugx.org/mozgbrasil/magento-product-page-shipping-php_54/downloads)](https://packagist.org/packages/mozgbrasil/magento-product-page-shipping-php_54)
[![Latest Unstable Version](https://poser.pugx.org/mozgbrasil/magento-product-page-shipping-php_54/v/unstable)](https://packagist.org/packages/mozgbrasil/magento-product-page-shipping-php_54)
[![License](https://poser.pugx.org/mozgbrasil/magento-product-page-shipping-php_54/license)](https://packagist.org/packages/mozgbrasil/magento-product-page-shipping-php_54)
[![Monthly Downloads](https://poser.pugx.org/mozgbrasil/magento-product-page-shipping-php_54/d/monthly)](https://packagist.org/packages/mozgbrasil/magento-product-page-shipping-php_54)
[![Daily Downloads](https://poser.pugx.org/mozgbrasil/magento-product-page-shipping-php_54/d/daily)](https://packagist.org/packages/mozgbrasil/magento-product-page-shipping-php_54)
[![Reference Status](https://www.versioneye.com/php/mozgbrasil:magento-product-page-shipping-php_54/reference_badge.svg?style=flat-square)](https://www.versioneye.com/php/mozgbrasil:magento-product-page-shipping-php_54/references)
[![Dependency Status](https://www.versioneye.com/php/mozgbrasil:magento-product-page-shipping-php_54/1.0.0/badge?style=flat-square)](https://www.versioneye.com/php/mozgbrasil:magento-product-page-shipping-php_54/1.0.0)

:cat2:
