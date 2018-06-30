[checkmark]: https://raw.githubusercontent.com/mozgbrasil/mozgbrasil.github.io/master/assets/images/logos/logo_32_32.png "MOZG"
![valid XHTML][checkmark]

[psr4]: http://www.php-fig.org/psr/psr-4/
[getcomposer]: https://getcomposer.org/
[uninstall-mods]: https://getcomposer.org/doc/03-cli.md#remove
[url-method]: https://www.loggi.com/contas/criar/GH5THM/

# Mozg\Framework\Loggi

## Sinopse

SDK de integração a [Loggi][url-method]

## Perguntas mais frequentes "FAQ"

### Simulação de transação

Podemos executar o comando curl via terminal ou pelo seguinte serviço http://onlinecurl.com/

### Requisição de Orçamentos

	curl --request POST https://staging.loggi.com/api/v1/endereco/orcamento/ --header 'Content-Type: application/json;charset=UTF-8' --header 'Authorization: ApiKey mailer@mozg.com.br:95625355c67893da31e1426347aa5a9ef149d797' --data '{
	   "city":"1",
	   "package_type":"large_box",
	   "payment_method":"",
	   "transport_type":"1",
	   "slo":1,
	   "waypoints":[
	      {
	         "by":"cep",
	         "query":{
	            "cep":"04750000",
	            "number":"207",
	            "instructions":"Retirar pacote"
	         }
	      },
	      {
	         "by":"cep",
	         "query":{
	            "cep":"02180110",
	            "number":"181",
	            "instructions":"Entregar pacote"
	         }
	      }
	   ]
	}' --verbose

### Listar Pedidos 

	curl --request GET https://staging.loggi.com/api/v1/pedidos-status/ --header 'Content-Type: application/json;charset=UTF-8' --header 'Authorization: ApiKey [email da conta]:[chave da API da conta]'

### Listar Métodos de Pagamento 

	curl --request GET https://staging.loggi.com/api/v1/metodos-de-pagamento/ --header 'Content-Type: application/json;charset=UTF-8' --header 'Authorization: ApiKey [email da conta]:[chave da API da conta]'

:cat2: