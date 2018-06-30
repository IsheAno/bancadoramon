[checkmark]: https://raw.githubusercontent.com/mozgbrasil/mozgbrasil.github.io/master/assets/images/logos/logo_32_32.png "MOZG"
![valid XHTML][checkmark]

# Mozg\Framework\Shopfacil

## Sinopse

Integração ao [Bradesco SPS][url-method]

## Perguntas mais frequentes "FAQ"

### Habilitação Boleto

Os critérios para utilizar o Boleto Bradesco Com. Eletrônico são:

Ser correntista Bradesco Pessoa Jurídica;

Contatar o gerente de conta Bradesco para assinar contrato específico do Comércio Eletrônico.

Para solicitar/configurar o Boleto Bradesco, você precisa:

Contatar o banco/agência e fazer a solicitação de boleto com registro carteira 26.

Esse passo envolve assinatura de contrato com o Banco.

Receber um e-mail do Banco (Kit Scopus) com URL do gerenciador, e-mail de login e senha de acesso ao ambiente Bradesco.

Você deve acessar o ambiente Bradesco pela URL do gerenciador.

Veja os exemplos enviados no email (Kit Scopus):

    URL do gerenciador - https://meiosdepagamentobradesco.com.br/gerenciador/login.jsp
    E-mail de Login
    Senha de acesso

Para configurar o Boleto:

1. No ambiente Bradesco, acesse as abas CONFIGURAÇÕES > MEIOS DE PAGAMENTO > BOLETO e preencha os seguintes dados:

    Habilitar "frase" do boleto: Inativo
    Habilitar "referência" do boleto: Ativo
    Apresentar Agência e Conta: Inativo
    Vencimento: 5 dias

### Habilitação Débito online

Solicitar ao seu gerente a liberação do débito online do Bradesco (SPS Bradesco).

A afiliação será enviada pelo Bradesco no padrão:

	Convênio de homologação: 101xx1
	login: dm_cm132
	senha: 12345678
	Assinatura: 8EA7657E-6373-667D-0229-A82E842A3A1A

Ao receber estas informações o lojista deverá Solicitar a habilitação do meio de pagamento para Cielo.
Cadastrar no MUP Teste (sistema do Bradesco, o e-mail de com os dados do Bradesco virá com a URL para acesso).

Cadastrar as informações abaixo:

Endereço IP da loja: 209.134.48.121

Em "Página de confirmação de compra" e "Página de falha no pagamento": 

https://www.pagador.com.br/pagador/recebe.asp

Em "URL para notificação p/ Cartões Bradesco": 

https://www.pagador.com.br/pagador/bradesco/setTransacao.asp

Nos Campos "Post a ser enviado para a loja na notificação", "Post a ser enviado para a loja na confirmação de compra" e "Post a ser enviado para a loja na falha da autorização":

Adicionar: 

numOrder=[%lid_m%]&merchantid=[%merchantid%]&cod=[%errorcod%]&cctype=[%cctype%]&ccname=[%ccname%]&ccemail=[%ccemail%]&numparc=[%numparc%]&valparc=[%valparc%]&valtotal=[%valtotal%]&prazo=[%prazo%]&tipopagto=[%tipopagto%]&assinatura=[%assinatura%]

Em "URL de entrada na loja": endereço do site

Em "URL do gerenciador da loja": https://www.pagador.com.br/admin/login.asp

Na última opção: capture now (1001).

Enviar o e-mail abaixo para a Scopus solicitando a homologação

Para: homologacao@scopus.com.br; kit@scopus.com.br

Assunto: Dados do ambiente de produção Débito SPS

Corpo do email:

	Prezados,

	Favor liberar o cliente abaixo no ambiente de produção:

	Razão Social: XXXXX
	CNPJ: XXXX
	Nome da loja: XXXXX
	Número da loja: XXXXX
	Manager: XXXXX
	Senha: XXXXXXX
	URL da Loja: https://www.XXXXXXXXX
	Meio de Pagamento para Homologar: Débito em Conta

Receber os dados de produção:

A afiliação será enviada pelo Bradesco no padrão:

	Convênio de Produção: 101xx1
	login: dm_cm132
	senha: 12345678
	URL para teste: http://mup.comercioeletronico.com.br/sepsManager/senha.asp?loja=XXXX

Cadastrar no MUP Produção, as mesmas informações do Passo 3.

Atualizar o número de Convênio no ambiente de produção.

### Dados de contato - Bradesco

kit@scopus.com.br

## Manual

https://homolog.meiosdepagamentobradesco.com.br/manual/Manual_BoletoBancario.pdf
 
https://homolog.meiosdepagamentobradesco.com.br/manual/Manual_ConsultaPedidos.pdf
 
https://homolog.meiosdepagamentobradesco.com.br/manual/Manual_API_Transferencia.pdf

:cat2:
