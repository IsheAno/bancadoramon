[checkmark]: https://raw.githubusercontent.com/mozgbrasil/mozgbrasil.github.io/master/assets/images/logos/logo_32_32.png "MOZG"
![valid XHTML][checkmark]

[psr4]: http://www.php-fig.org/psr/psr-4/
[requirements]: http://mozgbrasil.github.io/requirements/
[getcomposer]: https://getcomposer.org/
[uninstall-mods]: https://getcomposer.org/doc/03-cli.md#remove

# Mozg\Framework\Cielo

## Sinopse

SDK de integração a Cielo e Braspag

## Perguntas mais frequentes "FAQ"

### Obtendo o MerchantId e MerchantKey para uso da API 3.0

Efetue o cadastro no seguinte ambiente para obter os dados de integração

https://cadastrosandbox.cieloecommerce.cielo.com.br/

https://cadastrosandbox.braspag.com.br/

### Simulação de transação

Podemos executar o comando curl via terminal ou pelo seguinte serviço

http://onlinecurl.com/

### Checkout Cielo - https://developercielo.github.io/Checkout-Cielo/?shell#carrinho-de-compras

    curl \
    --request POST https://cieloecommerce.cielo.com.br/api/public/v1/orders \
    --header 'Content-Type: application/json' \
    --header 'MerchantId: a2133427-a0f8-4fe8-b605-6469161e7711' \
    --data '{
     "OrderNumber": "145000661",
     "SoftDescriptor": "AcmeCorp",
     "Cart": {
       "Items": [
         {
           "Name": "Retro Chic Eyeglasses",
           "Description": "",
           "UnitPrice": "10000",
           "Quantity": 3,
           "Type": "Digital",
           "Sku": "ace002",
           "Weight": 1000
         }
       ]
     },
     "Shipping": {
       "Type": "Free",
       "SourceZipCode": "03107000",
       "TargetZipCode": "08215070",
       "Address": {
         "Street": "Avenida Córrego do Jacuu",
         "Number": "12",
         "Complement": "ap. 23 B",
         "District": "Itaquera",
         "City": "São Paulo",
         "State": "SP"
       }
     },
     "Payment": {
       "BoletoDiscount": 0,
       "DebitDiscount": 0
     },
     "Customer": {
       "Identity": "25739569000102",
       "FullName": "Eula Jackson",
       "Email": "mailer@mozg.com.br",
       "Phone": "1129928269"
     },
     "Options": {
       "AntifraudEnabled": "false"
     }
   }' --verbose

### Cielo API 3.0 - CreditCard - https://developercielo.github.io/Webservice-3.0/#criando-uma-venda-com-analise-de-fraude

    curl \
    --request POST https://apisandbox.cieloecommerce.cielo.com.br/1/sales/ \
    --header 'Content-Type: application/json' \
    --header 'MerchantId: a2133427-a0f8-4fe8-b605-6469161e7711' \
    --header 'MerchantKey: XUMUBMGQBPNUAYIESMSHTCNLVTNEXIDPHXQRZYOC' \
    --header 'RequestId: xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx' \
    --data '{  
       "MerchantOrderId":"201411173454307",
       "Customer":{  
          "Name":"Comprador crédito AF",
          "Email":"compradorteste@live.com",
          "Birthdate":"1991-01-02",
          "Address":{  
             "Street":"Rua Júpter",
             "Number":"174",
             "Complement":"AP 201",
             "ZipCode":"21241140",
             "City":"Rio de Janeiro",
             "State":"RJ",
             "Country":"BRA"
          },
          "DeliveryAddress":{  
             "Street":"Rua Júpter",
             "Number":"174",
             "Complement":"AP 201",
             "ZipCode":"21241140",
             "City":"Rio de Janeiro",
             "State":"RJ",
             "Country":"BRA"
          }
       },
       "Payment":{  
         "Type":"CreditCard",
         "Amount":100,
         "Currency":"BRL",
         "Country":"BRA",
         "ServiceTaxAmount":0,
         "Installments":1,
         "SoftDescriptor":"123456789ABCD",
         "Interest":"ByMerchant",
         "Capture":false,
         "Authenticate":false,
         "CreditCard":{  
             "CardNumber":"4024007197692931",
             "Holder":"Teste accept",
             "ExpirationDate":"12/2030",
             "SecurityCode":"023",
             "Brand":"Visa"
         },
         "FraudAnalysis":{
           "Sequence":"AuthorizeFirst",
           "SequenceCriteria":"Always",
           "FingerPrintId":"074c1ee676ed4998ab66491013c565e2",
           "Browser":{
             "CookiesAccepted":false,
             "Email":"compradorteste@live.com",
             "HostName":"Teste",
             "IpAddress":"200.190.150.350",
             "Type":"Chrome"
            },
           "Cart":{
             "IsGift":false,
             "ReturnsAccepted":true,
             "Items":[{
               "GiftCategory":"Undefined",
               "HostHedge":"Off",
               "NonSensicalHedge":"Off",
               "ObscenitiesHedge":"Off",
               "PhoneHedge":"Off",
               "Name":"ItemTeste",
               "Quantity":1,
               "Sku":"201411170235134521346",
               "UnitPrice":123,
               "Risk":"High",
               "TimeHedge":"Normal",
               "Type":"AdultContent",
               "VelocityHedge":"High",
               "Passenger":{
                 "Email":"compradorteste@live.com",
                 "Identity":"1234567890",
                 "Name":"Comprador accept",
                 "Rating":"Adult",
                 "Phone":"999994444",
                 "Status":"Accepted"
                }
               }]
           },
           "MerchantDefinedFields":[{
                "Id":95,
                "Value":"Eu defini isso"
            }],
            "Shipping":{
                "Addressee":"Sr Comprador Teste",
                "Method":"LowCost",
                "Phone":"21114740"
            },
            "Travel":{
                "DepartureTime":"2010-01-02",
                "JourneyType":"Ida",
                "Route":"MAO-RJO",
              "Legs":[{
                    "Destination":"GYN",
                    "Origin":"VCP"
              }]
            }
         }
      }
    }' --verbose

### Cielo API 3.0 - CreditCard - https://developercielo.github.io/Webservice-3.0/#criando-uma-transação-completa

    curl \
    --request POST https://apisandbox.cieloecommerce.cielo.com.br/1/sales/ \
    --header 'Content-Type: application/json' \
    --header 'MerchantId: a2133427-a0f8-4fe8-b605-6469161e7711' \
    --header 'MerchantKey: XUMUBMGQBPNUAYIESMSHTCNLVTNEXIDPHXQRZYOC' \
    --header 'RequestId: xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx' \
    --data '{  
       "MerchantOrderId":"2014111703",
       "Customer":{  
          "Name":"Comprador crédito completo",
          "Email":"compradorteste@teste.com",
          "Birthdate":"1991-01-02",
          "Address":{  
             "Street":"Rua Teste",
             "Number":"123",
             "Complement":"AP 123",
             "ZipCode":"12345987",
             "City":"Rio de Janeiro",
             "State":"RJ",
             "Country":"BRA"
          },
            "DeliveryAddress": {
                "Street": "Rua Teste",
                "Number": "123",
                "Complement": "AP 123",
                "ZipCode": "12345987",
                "City": "Rio de Janeiro",
                "State": "RJ",
                "Country": "BRA"
            }
       },
       "Payment":{  
         "Type":"CreditCard",
         "Amount":15700,
         "Currency":"BRL",
         "Country":"BRA",
         "ServiceTaxAmount":0,
         "Installments":1,
         "Interest":"ByMerchant",
         "Capture":true,
         "Authenticate":false,
         "SoftDescriptor":"123456789ABCD",
         "CreditCard":{  
             "CardNumber":"1234123412341231",
             "Holder":"Teste Holder",
             "ExpirationDate":"12/2030",
             "SecurityCode":"123",
             "SaveCard":"false",
             "Brand":"Visa"
         }
       }
    }' --verbose

### Cielo API 3.0 - DebitCard - https://developercielo.github.io/Webservice-3.0/#criando-uma-venda-simplificada25

    curl \
    --request POST https://apisandbox.cieloecommerce.cielo.com.br/1/sales/ \
    --header 'Content-Type: application/json' \
    --header 'MerchantId: a2133427-a0f8-4fe8-b605-6469161e7711' \
    --header 'MerchantKey: XUMUBMGQBPNUAYIESMSHTCNLVTNEXIDPHXQRZYOC' \
    --header 'RequestId: xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx' \
    --data '{  
       "MerchantOrderId":"2014111703",
       "Customer":{  
          "Name":"Comprador crédito simples"
       },
       "Payment":{  
         "Type":"DebitCard",
         "Amount":15700,
         "ReturnUrl":"http://www.cielo.com.br",
         "DebitCard":{  
             "CardNumber":"4551870000000183",
             "Holder":"Teste Holder",
             "ExpirationDate":"12/2030",
             "SecurityCode":"123",
             "Brand":"Visa"
         }
       }
    }' --verbose

### Cielo API 3.0 - Boleto - https://developercielo.github.io/Webservice-3.0/#pagamentos-com-boleto

    curl \
    --request POST https://apisandbox.cieloecommerce.cielo.com.br/1/sales/ \
    --header 'Content-Type: application/json' \
    --header 'MerchantId: a2133427-a0f8-4fe8-b605-6469161e7711' \
    --header 'MerchantKey: XUMUBMGQBPNUAYIESMSHTCNLVTNEXIDPHXQRZYOC' \
    --header 'RequestId: xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx' \
    --data '{  
    "MerchantOrderId":"2014111703",
    "Customer":
    {  
        "Name":"Comprador Teste Boleto",
        "Identity": "1234567890",
        "Address":
        {
          "Street": "Avenida Marechal Câmara",
          "Number": "160",  
          "Complement": "Sala 934",
          "ZipCode" : "22750012",
          "District": "Centro",
          "City": "Rio de Janeiro",
          "State" : "RJ",
          "Country": "BRA"
        }
    },
    "Payment":
    {  
        "Type":"Boleto",
        "Amount":15700,
        "Provider":"Bradesco",
        "Address": "Rua Teste",
        "BoletoNumber": "123",
        "Assignor": "Empresa Teste",
        "Demonstrative": "Desmonstrative Teste",
        "ExpirationDate": "5/1/2018",
        "Identification": "11884926754",
        "Instructions": "Aceitar somente até a data de vencimento, após essa data juros de 1% dia."
    }
  }' --verbose

### Cielo API 3.0 - TEF - https://developercielo.github.io/Webservice-3.0/#pagamentos-com-transfer%C3%AAncia-eletronica

    curl \
    --request POST https://apisandbox.cieloecommerce.cielo.com.br/1/sales/ \
    --header 'Content-Type: application/json' \
    --header 'MerchantId: a2133427-a0f8-4fe8-b605-6469161e7711' \
    --header 'MerchantKey: XUMUBMGQBPNUAYIESMSHTCNLVTNEXIDPHXQRZYOC' \
    --header 'RequestId: xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx' \
    --data '{  
        "MerchantOrderId":"2014111706",
        "Customer":
        {  
            "Name":"Comprador Transferência Eletronica"
        },
        "Payment":
        {  
            "Type":"EletronicTransfer",
            "Amount":15700,
            "Provider":"Bradesco",
            "ReturnUrl":"http://www.cielo.com.br"
        }
    }' --verbose

### Braspag API (V2) - CreditCard - http://apidocs.braspag.com.br/

    curl \
    --request POST https://apisandbox.braspag.com.br/v2/sales/ \
    --header 'Content-Type: application/json' \
    --header 'MerchantId: 1985000c-22f7-4429-9a92-fa5cb27de0e0' \
    --header 'MerchantKey: VJGOUODUJMCLCDAVPIBSSAPMWCTQVQBTHOXRUZFS' \
    --header 'RequestId: xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx' \
    --data '{  
        "MerchantOrderId":"2014111703",
        "Customer":{  
            "Name":"Comprador Teste"     
        },
        "Payment":{  
            "Type":"CreditCard",
            "Amount":15700,
            "Provider":"Simulado",
            "Installments":1,
            "CreditCard":{  
                "CardNumber":"1234123412341231",
                "Holder":"Teste Holder",
                "ExpirationDate":"12/2021",
                "SecurityCode":"123",
                "Brand":"Visa"
            }
        }
    }' --verbose

### Braspag API (V2) - Boleto - http://apidocs.braspag.com.br/

    curl \
    --request POST https://apisandbox.braspag.com.br/v2/sales/ \
    --header 'Content-Type: application/json' \
    --header 'MerchantId: 1985000c-22f7-4429-9a92-fa5cb27de0e0' \
    --header 'MerchantKey: VJGOUODUJMCLCDAVPIBSSAPMWCTQVQBTHOXRUZFS' \
    --header 'RequestId: xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx' \
    --data '{  
    "MerchantOrderId":"2014111703",
    "Customer":
    {  
        "Name":"Comprador Teste Boleto",
        "Identity": "1234567890",
        "Address":
        {
          "Street": "Avenida Marechal Câmara",
          "Number": "160",  
          "Complement": "Sala 934",
          "ZipCode" : "22750012",
          "District": "Centro",
          "City": "Rio de Janeiro",
          "State" : "RJ",
          "Country": "BRA"
        }
    },
    "Payment":
    {  
        "Type":"Boleto",
        "Amount":15700,
        "Provider":"Simulado",
        "Address": "Rua Teste",
        "BoletoNumber": "123",
        "Assignor": "Empresa Teste",
        "Demonstrative": "Desmonstrative Teste",
        "ExpirationDate": "5/1/2018",
        "Identification": "11884926754",
        "Instructions": "Aceitar somente até a data de vencimento, após essa data juros de 1% dia."
    }
  }' --verbose

### Braspag API (V2) - TEF - http://apidocs.braspag.com.br/

    curl \
    --request POST https://apisandbox.braspag.com.br/v2/sales/ \
    --header 'Content-Type: application/json' \
    --header 'MerchantId: 1985000c-22f7-4429-9a92-fa5cb27de0e0' \
    --header 'MerchantKey: VJGOUODUJMCLCDAVPIBSSAPMWCTQVQBTHOXRUZFS' \
    --header 'RequestId: xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx' \
    --data '{  
        "MerchantOrderId":"2014111706",
        "Customer":
        {  
            "Name":"Comprador Transferência Eletronica"
        },
        "Payment":
        {  
            "Type":"EletronicTransfer",
            "Amount":15700,
            "Provider":"Simulado",
            "ReturnUrl":"http://www.cielo.com.br"
        }
    }' --verbose

### Sobre o retorno "129 - Affiliation not found" ou "314 - Invalid Integration"

Se trata de erro relacionado ao MerchantId e MerchantKey inválido

Certifique se de analisar os dados de filiação e a URL

Como as simulações acima estão funcionais você pode alterar somente o MerchantId e MerchantKey para testar se seus dados de filiação está retornando o processo de transação

:cat2:
