[checkmark]: https://raw.githubusercontent.com/mozgbrasil/mozgbrasil.github.io/master/assets/images/logos/logo_32_32.png "MOZG"
![valid XHTML][checkmark]

[url-method]: http://correios.com.br/
[requerimentos]: http://mozgbrasil.github.io/requerimentos/
[limites-de-dimensoes-e-de-peso]: http://www.correios.com.br/para-voce/precisa-de-ajuda/limites-de-dimensoes-e-de-peso
[mao-propria-mp]: http://www.correios.com.br/para-voce/correios-de-a-a-z/mao-propria-mp
[aviso-de-recebimento-ar]: http://www.correios.com.br/para-voce/correios-de-a-a-z/aviso-de-recebimento-ar
[valor-declarado]: http://www.correios.com.br/para-voce/correios-de-a-a-z/valor-declarado
[contact-correios]: http://www2.correios.com.br/sistemas/falecomoscorreios/
[encomendas-prazo]: http://www2.correios.com.br/sistemas/precosprazos/default.cfm
[tickets]: https://cerebrum.freshdesk.com/support/tickets/new
[preco]: http://www.cerebrum.com.br/preco/
[github-boxpacker]: https://github.com/mozgbrasil/magento-boxpacker-php_54#mozgboxpacker
[getcomposer]: https://getcomposer.org/
[uninstall-mods]: https://getcomposer.org/doc/03-cli.md#remove
[artigo-composer]: http://mozg.com.br/ubuntu/composer
[ioncube-loader]: http://www.ioncube.com/loaders.php
[acordo]: http://mozg.com.br/acordo-licenca-usuario-final/

# Mozg\Correios

## Sinopse

Integração ao [Correios][url-method]

## Motivação

Atender o mercado de módulos para Magento oferecendo melhorias e um excelente suporte

## Suporte / Dúvidas

Para obter o devido suporte [Clique aqui][tickets], relatando o motivo da ocorrência o mais detalhado possível e anexe o print da tela para nosso entendimento

## Preço

[Clique aqui][preco]

## Recursos do módulo

- [✓] Cálculo do frete
- [✓] Rastreamento
- [✓] Notificação automática do rastreamento
- [✓] Impressão de etiquetas

## Característica técnica

Atualmente diversos módulos de terceiros relativo a métodos de entrega sempre soma o peso e dimensões dos produtos gerando falha na requisição a transportadora devido não terem um sistema que separa os produtos em sua devida embalagem distribuindo seu peso.

O nosso módulo foi desenvolvido visando total transparência dos processos executados, para efeito de análise visualize os processos armazenado em log.

A extensão permite você definir as dimensões de seus produtos, as dimensões, peso e valor de sua Embalagem/Caixa e regras de como empacotar diferentes combinações de produtos em conjunto como por exemplo embalar os produtos separadamente ou combinar os produtos na mesma Embalagem/Caixa.

A extensão escolhe qual embalagem será utilizado para embalar os produtos para o pedido.

A extensão pode distribuir os produtos em diversas embalagens até o peso máximo suportado para a embalagem.

Como será cadastrado a embalagem com as dimensões e peso suportado pelas transportadoras não deve ocorrer falha relativa as dimensões ou peso.

A primeira coisa a se levar em consideração no uso do módulo é o [Gerenciamento de Embalagem/Caixa][github-boxpacker], como já vem alguns registros pré inseridos certifique se de atualizar os registros conforme sua necessidade.

Certifique se ter cadastrado as devidas dimensões para os produtos.

Para cada embalagem é feito uma requisição a transportadora onde é passado os devidos parâmetros

O módulo possui armazenamento de cache

Na finalização do pedido é armazenado no histórico do pedido um comentário contendo um identificador único que poderá ser usado para consulta no arquivo de log a discriminação dos pacotes seus itens e a visualização de cada pacote com seus itens em 3D

Sempre confira as informações de frete antes de processar cada pedido, caso algo esteja inconsistente será necessário cancelar o pedido até a correção da ocorrência

Para o rastreamento do pacote é feito acesso ao WebService onde é passado os devidos parâmetros e exibido o devido retorno

O módulo possui notificação automática do rastreamento sendo enviado e-mail sobre a mudança de status das suas encomendas ou pacotes do site do correios

Para usar esse recurso, configure o Magento para usar a CRON

## Setup Cron

Para o uso do método é necessário ativar a <a href="https://pt.wikipedia.org/wiki/Crontab">CRON</a> para o <a href="https://magento.com/">Magento</a>

<a href="https://mozg.com.br/dicas/dicas-magento1#como-ativar-a-cron-no-magento">Clique aqui</a> para visualizar o documento da MOZG

Certifique-se de que ação esteja sendo executado a cada minuto

Esse módulo usa o cronjob para processar as notificações

## Resumo: fluxo do módulo

- Cliente compra na loja utilizando o método de entrega;

- Administrador da loja verifica pagamento e estoque do item;

- Administrador da loja seleciona os pedidos para geração das etiquetas e códigos de rastreamento junto aos Correios;

- Módulo envia os dados do pedido aos Correios. Recebe código de rastreamento, envia e-mail com código ao cliente e imprime etiqueta;

- Equipe interna de expedição ou logística verifica etiquetas e cola nos objetos à serem remetidos;

- O responsável dos correios coleta os objetos etiquetados na loja. Objetos são registrados no sistema dos Correios mediante leitura do código de barras da etiqueta (impressa via módulo e integração SIGEP)

Para o código de barras da "etiqueta com digito verificador" e "CEP do destinatário" é usado o modelo "code128"

Caso tenha interesse na leitura de código de barras pesquisa na WEB por "read barcode online"

Ou use o seguinte serviço

https://online-barcode-reader.inliteresearch.com/

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

	composer require mozgbrasil/magento-correios-php_54:dev-master

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

	composer remove mozgbrasil/magento-correios-php_54 && composer clear-cache && composer update

--

### Para desativar o módulo

1. Antes de efetuar qualquer processo que envolva atualização sobre o Magento é necessário manter o Compiler e Cache desativado

2. Caso queira desativar os módulos da MOZG renomeie a seguinte pasta app/code/local/Mozg

A desativação do módulo pode ser usado para detectar se determinada ocorrência tem relação com o módulo

## Como configurar o método de entrega

Antes de configurar o módulo você deve cadastrar o CEP de origem, indo ao backend em:

	STORES -> Configuration -> Sales/Shipping Settings -> Origin

Para configurar o método de entrega, acesse no backend em:

	STORES -> Configuration -> Sales/Shipping Methods -> Correios (powered by MOZG)

Você terá os campos a seguir

### • **Ativar**

Para "ativar" ou "desativar" o uso do método

### • **Ordem de exibição**

É a ordem apresentada em métodos de entrega no passo de fechamento de pedido

### • **Título**

Nome do método que deve ser exibido

### • **Serviços**

Selecione os serviços desejado, para selecionar mais de um, segure a tecla "Ctrl" e clique nos serviços

### • **Serviço Para Entrega Gratuita**

Quando houver um desconto de frete grátis, esse serviço terá o valor zero

### • **Calcular taxa de manuseio**

Podendo ser fixo ou percentual

### • **Taxa de Manuseio**

Será adicionado o valor ao frete

### • **Mostrar método se não aplicável**

Quando configurado como "Não", caso seja retornado algum serviço com erro, não será exibido o método de entrega

### • **Debug**

Deve ser armazenado os processos do módulo em var/log/

O arquivo

DATE_mozg.log

se trata de log do módulo sendo um log mais detalhado contendo todos os processos inclusive das execuções realizadas pelas bibliotecas externas do módulo

O arquivo

shipping_METHOD.log

se trata de log nativo do magento relativo ao método de entrega

### • **Enviar para países aplicáveis**

Você pode definir se o método deve funcionar para "Todos os Países aceito" ou "Especificar Países "

### • **Enviar para países específicos**

Você deve selecionar os países que o método deve ser funcional

### • **Identificador do atributo largura dos produtos**

Permite definir o nome do atributo de largura dos produtos usado no projeto

### • **Identificador do atributo comprimento dos produtos**

Permite definir o nome do atributo de comprimento dos produtos usado no projeto

### • **Identificador do atributo altura dos produtos**

Permite definir o nome do atributo de altura dos produtos usado no projeto

### • **Unidade de medida**

Sendo o padrão do peso do produto como kilo

Caso esteja usando a unidade de massa em gramas, tanto os produtos como as embalagens devem respeitar o mesmo padrão

Ao informar na configuração do método o uso da unidade de massa em gramas é feito a conversão do peso de grama para kilo

1 Kg no formato "Kilo" será "1.000", já em "Gramas" será "1000.000"

### • **Exibir Prazo de Entrega**

Se será ou não mostrado o prazo de entrega para seu cliente

### • **Mensagem que Exibe o Prazo de Entrega**

Se será ou não mostrado o prazo de entrega para seu cliente

### • **Adicionar (dias) ao prazo de entrega**

Quantidade de dias que será adicionado ao prazo

### • **Mostrar serviço com retorno de erro**

Quando configurado como "Não", caso seja retornado algum serviço com erro, o mesmo não deve ser exibido no método de entrega

### • **Ativar rastreamento automático via CRON**

Quando alterado o registro para Não o processo não deve ser executado pela CRON em execução do Magento

### • **Código administrativo junto à ECT**

Se você possui contrato com os Correios, preencha nesse campo o número do contrato

*Lojistas com contrato possuem valor diferenciado de postagem junto aos Correios*

### • **Senha Administrativa dos Correios (Serviços Com Contrato)**

Senha do seu contrato, por padrão são os 8 primeiros dígitos do CNPJ

### • **Utilizar Serviço de Mão Própria**

É o [serviço][mao-propria-mp] adicional pelo qual o remetente recebe a garantia de que o objeto, por ele postado sob Registro, será entregue somente ao próprio destinatário, através da confirmação de sua identidade

### • **Utilizar Serviço de Aviso de Recebimento**

É o [serviço][aviso-de-recebimento-ar] adicional que, por meio do preenchimento de formulário próprio, físico ou digital, permite comprovar, junto ao remetente, a entrega do objeto.

### • **Utilizar Serviço de Valor Declarado**

É o [serviço][valor-declarado] adicional que garante o valor real do objeto postado sob registro em caso eventual de avaria ou extravio.

### • **Ambiente de teste**

Informe se será usado o ambiente de teste do SIGEP

### • **Usuário Administrativa dos Correios (Serviços Com Contrato)**

Usuário do seu contrato

### • **Senha Administrativa dos Correios (Serviços Com Contrato)**

Senha do seu contrato, por padrão são os 8 primeiros dígitos do CNPJ

### • **Código administrativo junto à ECT**

Se você possui contrato com os Correios, preencha nesse campo o número do contrato

*Lojistas com contrato possuem valor diferenciado de postagem junto aos Correios*

### • **Número do contrato**

Informe o número do contrato

### • **Cartão de postagem**

Informe o cartão do contrato

### • **C.N.P.J.**

Informe o CNPJ

### • **Diretória**

Informe a diretória

### • **Logo**

Efetue o upload de uma imagem 120x100

### • **Serviço de Postagem**

Pode ser informado o código do serviço, caso este parâmetro esteja vazio, será informado o código do método

Esse recurso foi criado devido a necessidade de cliente que vende os produtos com apenas as opções de SEDEX e PAC (sem contrato).

Mas tem contrato com os Correios, o que permite preços menores e uma margem para os problemas de extravio

Permitindo fazer a venda do frete como SEDEX ou PAC e despachar com outro formato (e-Sedex por exemplo)

## Perguntas mais frequentes "FAQ"

### Erro: Não foi exibido na visualização do pedido as informações relativa a embalagem

Na finalização do pedido é armazenada as informações da embalagem em "additional_information" em "mozg_boxes"

Em certa ocasião foi detectado que um determinado método de pagamento estava sobeescrevendo o que é armazendo em "additional_information"

### Erro: Não foi possível obter as etiquetas solicitadas.

Certifique se de ter configurado o método com dados do contrato junto ao Correios e que esteja selecionado o uso do ambiente de produção

Certifique se de ter ativado algum serviço com contrato e finalizado um pedido com esse serviço relacionado

Em seguida teste a geração da etiqueta para esse novo pedido

Podemos fazer as simulações para identificar a causa

* Simulação de autenticação SIGEP
* Simulação de acesso ao método "buscaContrato"
* Simulação de acesso ao método "buscaCliente"
* Simulação de acesso ao método "solicitaEtiquetas"

Na simulação de autenticação SIGEP, deve ser retornado "true"

### Sobre a notificação automática do rastreamento

O módulo possui notificação automática do rastreamento sendo enviado e-mail sobre a mudança de status das suas encomendas ou pacotes do site do correios

Para efeito de teste, cadastre um código de rastreamento do Correios para o pedido, quando houver mudança desse registro no sistema do Correios, o módulo deve resgatar o status do pedido no Correios e informar o cliente sobre a mudança de status da encomenda

Para visualizar os e-mails que é enviado pelo Magento recomendo o uso do seguinte módulo, esse módulo tem um recurso que armazena em log os e-mails enviado

https://github.com/aschroder/Magento-SMTP-Pro-Email-Extension

Caso queira, instale o módulo via Composer

    composer require aschroder/smtp_pro

Procure manter o máximo de bibliotecas gerenciadas pelo Composer para sempre obter as atualizações

### Como usar o recurso de Impressão de etiquetas

--

O módulo oferece a integração com os serviços do SIGEP Web do Correios, possibilitando a automação das postagens da sua empresa.

Para ter acesso a este serviço será necessário:

Possuir contrato com o Correios.

Solicitar ao representante comercial da ECT permissão de acesso para utilização do Web Service através de login e senha.

Você não pode gerar etiquetas usando o servidor de homologação dos Correios. Então você vai precisar de um usuário e senha real (que você consegue com o Correios).

--

No Magento, na configuração do método do Correios temos um bloco relativo a "Configurações SIGEP"

Onde deve ser feito o devido preenchimento

Na visualização do pedido é exibido o bloco "Correios SIGEP"

--

Para mais informações sobre o SIGEP Web, acesse

http://www.correios.com.br/para-sua-empresa/encomendas/sigep-web

https://www.correios.com.br/para-sua-empresa/comercio-eletronico/sistemas-geradores-de-etiquetas-de-enderecamento

--

### O que é a PLP (pré lista de postagem)?

A PLP (pré-lista de postagem) é um controle composto por um formulário + uma etiqueta de postagem.

Esse controle indica que o vendedor realizou a postagem da mercadoria numa agência dos Correios e que essa recebeu o pacote.

Obrigatoriamente, você terá que imprimir e colar a etiqueta na embalagem do seu produto (na maior face do pacote). A outra parte, que é o formulário PLP (pré lista de postagem), deve ser entregue ao atendente dos Correios, que entregará sua via como comprovante da postagem.

Com a PLP, após a postagem do produto o código de rastreio da mercadoria será adicionado automaticamente, você não precisará mais preenchê-lo!

### Sobre o novo padrão da etiqueta

Ao efetuar login em

http://www.corporativo.correios.com.br/encomendas/sigepweb/

E clicar no bloco "SIGEP WEB"

Temos o acesso aos diversos manuais

Vemos no manual de integração em

http://www.corporativo.correios.com.br/encomendas/sigepweb/doc/Manual_de_Implementacao_do_Web_Service_SIGEP_WEB.pdf

Que o mesmo informa a data de revisão de 08/03/2017

No manual ao efetuar pesquisa por RPC

Temos o apontando do seguinte manual

http://www.correios.com.br/para-voce/correios-de-a-a-z/embalagens-recomendadas-pelos-correios/arquivos/GuiaTcnicoEmbalagensRPC.pdf

No manual ao efetuar pesquisa por RPC, vemos o novo padrão de etiqueta

Está sendo usado a seguinte dimensão 138,11 x 106,36 mm para a etiqueta

Essa alternativa é compatível com etiquetas padrão
Pimaco (6088|6288), Avery (15188|25188) e Colacril (4083|4084).

A seguir podemos visualizar o novo modelo de etiqueta

http://phpstack-12167-48796-234999.cloudwaysapps.com/magento-1.9.3.2-dev35/root/mozg_etiquetas.pdf?rand=1710356375

A seguir podemos visualizar o antigo modelo de etiqueta

https://mighty-basin-45414.herokuapp.com/stavarengo_php_sigep_relatorios_pdf.php

### Como imprimir 4 etiquetas na mesma pagina

Deve se configurar na hora da impressão para imprimir X páginas por folha

Efetuando pesquisa pelo termo "imprimir varias folhas por pagina"

Encontrei o artigo do Acrobat Reader, mencionando esse recurso

https://helpx.adobe.com/pt/acrobat/kb/print-multiple-pages-per-sheet.html

### Como conferir os valores dos fretes junto a transportada

Você pode visualizar no log os parâmetros enviado a transportada

Quando finalizado o pedido é armazenado no historico as dimensões da caixa que foi usada para o obter o frete

#### Simulação da requisição do preço

Ao efetuar o calculo do frete do produto

Temos o devido retorno ao processar a seguinte requisição

	curl --header 'Content-Type: text/xml;charset=UTF-8' --header 'CalcPrecoPrazo' --data '<?xml version="1.0" encoding="UTF-8"?>
	<SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ns1="http://tempuri.org/">
	<SOAP-ENV:Body>
	    <ns1:CalcPrecoPrazo>
	        <ns1:nCdEmpresa />
	        <ns1:sDsSenha />
	        <ns1:nCdServico>04510</ns1:nCdServico>
	        <ns1:sCepOrigem>88113320</ns1:sCepOrigem>
	        <ns1:sCepDestino>08215430</ns1:sCepDestino>
	        <ns1:nVlPeso>1.05</ns1:nVlPeso>
	        <ns1:nCdFormato>1</ns1:nCdFormato>
	        <ns1:nVlComprimento>18</ns1:nVlComprimento>
	        <ns1:nVlAltura>9</ns1:nVlAltura>
	        <ns1:nVlLargura>14</ns1:nVlLargura>
	        <ns1:nVlDiametro>0</ns1:nVlDiametro>
	        <ns1:sCdMaoPropria>N</ns1:sCdMaoPropria>
	        <ns1:nVlValorDeclarado>0</ns1:nVlValorDeclarado>
	        <ns1:sCdAvisoRecebimento>N</ns1:sCdAvisoRecebimento>
	    </ns1:CalcPrecoPrazo>
	</SOAP-ENV:Body>
	</SOAP-ENV:Envelope>' http://ws.correios.com.br/calculador/CalcPrecoPrazo.asmx?WSDL

ou

	http://wsdlbrowser.com/soapclient?wsdl_url=http%3A%2F%2Fws.correios.com.br%2Fcalculador%2FCalcPrecoPrazo.asmx%3FWSDL&function_name=CalcPrecoPrazo

#### Simulação da requisição da consulta

Temos o devido retorno ao processar a seguinte requisição

	curl --header 'Content-Type: text/xml;charset=UTF-8' --header 'SOAPAction:buscaEventos' --data '<?xml version="1.0" encoding="UTF-8"?>
	<SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ns1="http://resource.webservice.correios.com.br/">
	<SOAP-ENV:Body>
	    <ns1:buscaEventos>
	        <usuario>ECT</usuario>
	        <senha>SRO</senha>
	        <tipo>L</tipo>
	        <resultado>T</resultado>
	        <lingua>101</lingua>
	        <objetos>PG781754484BR</objetos>
	    </ns1:buscaEventos>
	</SOAP-ENV:Body>
	</SOAP-ENV:Envelope>' http://webservice.correios.com.br/service/rastro/Rastro.wsdl

ou

	http://wsdlbrowser.com/soapclient?wsdl_url=http%3A%2F%2Fwebservice.correios.com.br%2Fservice%2Frastro%2FRastro.wsdl&function_name=buscaEventos

#### Simulação de autenticação SIGEP

Nesse caso usando a URL do ambiente de teste

Para o ambiente de produção deve ser usado

https://apps.correios.com.br/SigepMasterJPA/AtendeClienteService/AtendeCliente?wsdl

Temos o devido retorno ao processar a seguinte requisição

	curl --header 'Content-Type: text/xml;charset=UTF-8' --header 'verificaDisponibilidadeServico' --data '<?xml version="1.0" encoding="UTF-8"?>
	<SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ns1="http://cliente.bean.master.sigep.bsb.correios.com.br/">
	  <SOAP-ENV:Body>
	    <ns1:verificaDisponibilidadeServico>
	      <codAdministrativo>08082650</codAdministrativo>
	      <numeroServico>40215</numeroServico>
	      <cepOrigem>70002900</cepOrigem>
	      <cepDestino>81350120</cepDestino>
	      <usuario>sigep</usuario>
	      <senha>n5f9t8</senha>
	    </ns1:verificaDisponibilidadeServico>
	  </SOAP-ENV:Body>
	</SOAP-ENV:Envelope>' --insecure https://apphom.correios.com.br/SigepMasterJPA/AtendeClienteService/AtendeCliente?wsdl

ou

	http://wsdlbrowser.com/soapclient?wsdl_url=https%3A%2F%2Fapphom.correios.com.br%2FSigepMasterJPA%2FAtendeClienteService%2FAtendeCliente%3Fwsdl&function_name=verificaDisponibilidadeServico

#### Simulação de acesso ao método "buscaContrato"

	curl --header 'Content-Type: text/xml;charset=UTF-8' --header 'buscaContrato' --data '<?xml version="1.0" encoding="UTF-8"?>
	<SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ns1="http://cliente.bean.master.sigep.bsb.correios.com.br/">
	<SOAP-ENV:Body>
	<ns1:buscaContrato>
	  <numero>???</numero>
	  <diretoria>0</diretoria>
	  <usuario>???</usuario>
	  <senha>???</senha>
	</ns1:buscaContrato>
	</SOAP-ENV:Body>
	</SOAP-ENV:Envelope>' --insecure https://apps.correios.com.br/SigepMasterJPA/AtendeClienteService/AtendeCliente?wsdl

#### Simulação de acesso ao método "buscaCliente"

	curl --header 'Content-Type: text/xml;charset=UTF-8' --header 'buscaCliente' --data '<?xml version="1.0" encoding="UTF-8"?>
	<SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ns1="http://cliente.bean.master.sigep.bsb.correios.com.br/">
	<SOAP-ENV:Body>
	<ns1:buscaCliente>
	  <idContrato>???</idContrato>
	  <idCartaoPostagem>???</idCartaoPostagem>
	  <usuario>???</usuario>
	  <senha>???</senha>
	</ns1:buscaCliente>
	</SOAP-ENV:Body>
	</SOAP-ENV:Envelope>' --insecure https://apps.correios.com.br/SigepMasterJPA/AtendeClienteService/AtendeCliente?wsdl

#### Simulação de acesso ao método "solicitaEtiquetas"

Temos o devido retorno ao processar a seguinte requisição

	curl --header 'Content-Type: text/xml;charset=UTF-8' --header 'solicitaEtiquetas' --data '<?xml version="1.0" encoding="UTF-8"?>
	<SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ns1="http://cliente.bean.master.sigep.bsb.correios.com.br/">
	<SOAP-ENV:Body>
	<ns1:solicitaEtiquetas>
	  <tipoDestinatario>C</tipoDestinatario>
	  <identificador>???</identificador>
	  <idServico>104625</idServico>
	  <qtdEtiquetas>1</qtdEtiquetas>
	  <usuario>???</usuario>
	  <senha>???</senha>
	</ns1:solicitaEtiquetas>
	</SOAP-ENV:Body>
	</SOAP-ENV:Envelope>' --insecure https://apps.correios.com.br/SigepMasterJPA/AtendeClienteService/AtendeCliente?wsdl

ou

	http://wsdlbrowser.com/soapclient?wsdl_url=https%3A%2F%2Fapphom.correios.com.br%2FSigepMasterJPA%2FAtendeClienteService%2FAtendeCliente%3Fwsdl&function_name=solicitaEtiquetas

#### Simulação de acesso ao método "validaEtiquetaPLP"

Temos o devido retorno ao processar a seguinte requisição

	curl --header 'Content-Type: text/xml;charset=UTF-8' --header 'validaEtiquetaPLP' --data '<?xml version="1.0" encoding="UTF-8"?>
	<SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ns1="http://cliente.bean.master.sigep.bsb.correios.com.br/">
	<SOAP-ENV:Body>
	<ns1:validaEtiquetaPLP>
	  <numeroEtiqueta>???</numeroEtiqueta>
	  <idPlp>???</idPlp>
	  <usuario>???</usuario>
	  <senha>???</senha>
	</ns1:validaEtiquetaPLP>
	</SOAP-ENV:Body>
	</SOAP-ENV:Envelope>' --insecure https://apps.correios.com.br/SigepMasterJPA/AtendeClienteService/AtendeCliente?wsdl

### Como é feito o cálculo de preços

[Clique aqui][encomendas-prazo] para calcular o preço da encomenda o sistema utiliza a distância origem/destino(CEP), o peso e as dimensões do objeto

### Como aplicar o Frete Grátis

Na configuração do módulo para o método de entrega é possível definir o "Serviço Para Entrega Gratuita" recurso que deve ser aplicado quando definido a ação de "Frete Grátis" nas "Regras da Promoção"

No Backend do Magento, acesse o menu: Promoções -> Regras de Promoção -> Criar regra -> Crie uma regra e defina na aba "Ações" o uso do Frete Grátis

Dessa forma na exibição do cálculo do frete será exibido para o serviço escolhido o valor zerado

Esse recurso se trata de regra nativa do Magento caso tenha algum problema sugiro desativar todas as regras de promoção e ativar uma de cada vez até encontrar o motivo da ocorrência

### Método retornando: "Peso excedido"
### Método retornando: "Dimensões dos produtos fora do permitido"

No link a seguir vemos os [limites de dimensões e de peso][limites-de-dimensoes-e-de-peso]

### Dados de contato - Correios

Para entrar em contato com o [Correios][contact-correios]

## Manual

https://www.correios.com.br/para-voce/correios-de-a-a-z/embalagens-recomendadas-pelos-correios/arquivos

http://www.correios.com.br/para-voce/correios-de-a-a-z/pdf/calculador-remoto-de-precos-e-prazos/manual-de-implementacao-do-calculo-remoto-de-precos-e-prazos

http://blog.correios.com.br/comercioeletronico/wp-content/uploads/2011/10/Guia-Tecnico-Rastreamento-XML-Cliente-Vers%C3%A3o-e-commerce-v-1-5.pdf

http://blog.correios.com.br/comercioeletronico/

http://blog.correios.com.br/comercioeletronico/?p=404

http://blog.correios.com.br/comercioeletronico/?p=155

http://www.correios.com.br/produtosaz/produto.cfm?id=8560360B-5056-9163-895DA62922306ECA

http://www.correios.com.br/para-sua-empresa/encomendas/sigep-web

http://www.corporativo.correios.com.br/encomendas/sigepweb/doc/Manual_do_Usuario_SigepWeb.pdf

http://www2.correios.com.br/hotsites/ecommerce/

:cat2:
