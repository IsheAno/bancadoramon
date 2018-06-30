[checkmark]: https://raw.githubusercontent.com/mozgbrasil/mozgbrasil.github.io/master/assets/images/logos/logo_32_32.png "MOZG"
![valid XHTML][checkmark]

[requerimentos]: http://mozgbrasil.github.io/requerimentos/
[getcomposer]: https://getcomposer.org/
[uninstall-mods]: https://getcomposer.org/doc/03-cli.md#remove
[git-releases]: https://github.com/mozgbrasil/magento-base-php_54/releases
[artigo-composer]: http://mozg.com.br/ubuntu/composer
[ioncube-loader]: http://www.ioncube.com/loaders.php
[acordo]: http://mozg.com.br/acordo-licenca-usuario-final/

# Mozg\Base

## Sinopse

Módulo requerido para funcionamento dos demais módulos

## Motivação

Atender o mercado de módulos para Magento oferecendo um excelente suporte

## Característica técnica

Uso interno

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

	composer require mozgbrasil/magento-base-php_54:dev-master

Você pode verificar se o módulo está instalado, indo ao backend em:

	STORES -> Configuration -> ADVANCED/Advanced -> Disable Modules Output

--

### Para atualizar o módulo execute o comando a seguir no terminal do seu servidor no diretório do seu projeto

Antes de efetuar qualquer processo que envolva atualização no Magento é recomendado manter o Compiler e Cache desativado

	composer clear-cache && composer update

Na ocorrência de erro, renomeie a pasta /vendor/mozgbrasil e execute novamente

Para checar a data do módulo execute o seguinte comando

	grep -ri --include=*.json 'time": "' ./vendor/mozgbrasil

### Para usar uma versão especifica do módulo

Primeiro acesse as versões disponibilizadas acessando os [releases][git-releases]https://github.com/mozgbrasil/magento-base-php_54/releases

Utilize a versão que atenda a data correspondente a seu arquivo de licença

Para isso altere no arquivo composer.json para a devida versão

    "require" : {
        "mozgbrasil/magento-base-php_54": "1.0.0"

Em seguida execute o comando a seguir no terminal do seu servidor

	composer update

--

### Para [desinstalar][uninstall-mods] o módulo execute o comando a seguir no terminal do seu servidor

	composer remove mozgbrasil/magento-base-php_54 && composer clear-cache && composer update

--

### Para desativar o módulo

1. Antes de efetuar qualquer processo que envolva atualização sobre o Magento é necessário manter o Compiler e Cache desativado

2. Caso queira desativar os módulos da MOZG renomeie a seguinte pasta app/code/local/Mozg

A desativação do módulo pode ser usado para detectar se determinada ocorrência tem relação com o módulo

## Como configurar o método

Para configurar o método, acesse no backend em:

	STORES -> Configuration -> MOZG -> Geral

Você terá os campos a seguir

### • **Ativar estilo ao IWD_Opc**

Deve aplicar estilo ao IWD_Opc

## Perguntas mais frequentes "FAQ"

### Sobre as colunas "ID do Método de Pagamento" e "Status da Transacão"

[![Clique para visualizar o vídeo](https://img.youtube.com/vi/YuZd8GHWHrM/0.jpg)](https://youtu.be/YuZd8GHWHrM "Clique para visualizar o vídeo")

Essas colunas foram adicionadas para uso interno do programador da MOZG, se tratando de recurso experimental

Essas colunas devem ser exibida caso exista registro que o módulo "husseycoding_customordergrid" esteja ativado

O módulo "husseycoding_customordergrid" permite adicionar colunas na grade de dados de pedidos

Como o módulo não exibe o ID do método de pagamento, o módulo da MOZG está adicionado essa nova coluna extendendo a funcionalidade do módulo "husseycoding_customordergrid"

É possivel efetuar pesquisa na nova coluna "ID do Método de Pagamento"

Foi colocado condição que ao pesquisar na coluna "ID do Método de Pagamento" por "mozg_cielo" deve ser exibido na coluna "Status da Transacão" o status da transação de cada pedido na grade de dados

É recomendado a instalação o módulo via composer executando o seguinte comando no terminal

    composer require husseycoding/customordergrid

Abaixo temos as URLs relativa ao repositório do módulo e o video demonstrativo

https://github.com/husseycoding/customordergrid

https://www.youtube.com/watch?v=dxIUntG6M2A

### Como remover arquivos do projeto

A seguir é efetuado pesquisa nos diretórios pelas nomenclaturas

	find /home/marcio/dados/public_html/application-dev39/FLOX/flox_public_html/ -type d -name 'Mozg*'

	find /home/marcio/dados/public_html/application-dev39/FLOX/flox_public_html/ -type d -name 'mozg*'

	find /home/marcio/dados/public_html/application-dev39/FLOX/flox_public_html/ -type l -name 'Mozg*'

	find /home/marcio/dados/public_html/application-dev39/FLOX/flox_public_html/ -type l -name 'mozg*'

Como vemos que são retornado somente as pastas vinculada a MOZG, podemos excluir os diretório

	find /home/marcio/dados/public_html/application-dev39/FLOX/flox_public_html/ -type d -name 'Mozg*' | xargs rm -rf

	find /home/marcio/dados/public_html/application-dev39/FLOX/flox_public_html/ -type d -name 'mozg*' | xargs rm -rf

	find /home/marcio/dados/public_html/application-dev39/FLOX/flox_public_html/ -type l -name 'Mozg*' | xargs rm -rf

	find /home/marcio/dados/public_html/application-dev39/FLOX/flox_public_html/ -type l -name 'mozg*' | xargs rm -rf

Execute a primeira instrução somente para efeito de conferencia

Em seguida exclua a pasta vendor na raiz do projeto e se necessário atualize os requerimentos do Composer

## COMO ?

## Contribuintes

Equipe Mozg

## License

[Comercial License](LICENSE.txt)

## Badges

[![Join the chat at https://gitter.im/mozgbrasil](https://badges.gitter.im/Join%20Chat.svg)](https://gitter.im/mozgbrasil/)
[![Latest Stable Version](https://poser.pugx.org/mozgbrasil/magento-base-php_54/v/stable)](https://packagist.org/packages/mozgbrasil/magento-base-php_54)
[![Total Downloads](https://poser.pugx.org/mozgbrasil/magento-base-php_54/downloads)](https://packagist.org/packages/mozgbrasil/magento-base-php_54)
[![Latest Unstable Version](https://poser.pugx.org/mozgbrasil/magento-base-php_54/v/unstable)](https://packagist.org/packages/mozgbrasil/magento-base-php_54)
[![License](https://poser.pugx.org/mozgbrasil/magento-base-php_54/license)](https://packagist.org/packages/mozgbrasil/magento-base-php_54)
[![Monthly Downloads](https://poser.pugx.org/mozgbrasil/magento-base-php_54/d/monthly)](https://packagist.org/packages/mozgbrasil/magento-base-php_54)
[![Daily Downloads](https://poser.pugx.org/mozgbrasil/magento-base-php_54/d/daily)](https://packagist.org/packages/mozgbrasil/magento-base-php_54)
[![Reference Status](https://www.versioneye.com/php/mozgbrasil:magento-base-php_54/reference_badge.svg?style=flat-square)](https://www.versioneye.com/php/mozgbrasil:magento-base-php_54/references)
[![Dependency Status](https://www.versioneye.com/php/mozgbrasil:magento-base-php_54/1.0.0/badge?style=flat-square)](https://www.versioneye.com/php/mozgbrasil:magento-base-php_54/1.0.0)

:cat2:
