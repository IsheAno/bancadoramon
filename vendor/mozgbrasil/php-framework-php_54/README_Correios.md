[checkmark]: https://raw.githubusercontent.com/mozgbrasil/mozgbrasil.github.io/master/assets/images/logos/logo_32_32.png "MOZG"
![valid XHTML][checkmark]

# Mozg\Framework

## Sinopse

Framework da Mozg

##  class Correios {

### Notificador de rastreamento correios via CRON

É executado os seguintes processos

	Entrada de dados

		e-mail
		objeto correios

	Validação

		Verificação de registro existente armazenado 

		Só deve ser armazenado o registro caso o status do método não seja entregue

	Armazenamento

		Registro em csv, armazenado na pasta /data/

	Processamento				

		Com base nos dados armazenado é feito acesso a URL de rastreamento e armazenado o devido conteudo em arquivo em sua devida pasta 

		Caso o status do rastreio seja entregue é movido o arquivo para a pasta complete, caso contrario para a pasta pending

			/data/
				trackings.csv
			/complete/
				12.xml
			/pending/
				123.xml

	Execução

		É feito acesso a pasta pending e processado cada arquivo onde 

		Caso tenha alteração no conteudo do arquivo local com o conteudo da URL de rastreamento deve ser feito a devida notificação, via e-mail

		Caso o status seja entrega o arquivo é movido para a pasta complete