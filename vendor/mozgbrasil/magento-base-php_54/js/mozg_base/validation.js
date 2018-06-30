/**
 * Copyright © 2016 Mozg. All rights reserved.
 * See LICENSE.txt for license details.
 */

//---

Validation.addAllThese([

    ['validate-cpf-cnpj', 'O CPF ou CNPJ informado \xE9 invalido', function(v, elm) {

        var libCpfCnpj = require("cpf_cnpj");
        var CPF = libCpfCnpj.CPF;
        var CNPJ = libCpfCnpj.CNPJ;

        var isValid_cpf = CPF.isValid(v, true);

        var isValid_cnpj = CNPJ.isValid(v, true);

        console.log(isValid_cpf);
        console.log(isValid_cnpj);

        return (isValid_cpf || isValid_cnpj);
    }],

    ['validate-street-address', 'Endereço inválido, preencha no formato requerido: Rua Nome da Rua, 123<br>Obs. É obrigatório informar após o logradouro o delimitador virgula + espaço + até 5 números ou + complemento<br>Caso seu logradouro não tenha número, preencha com zero, então preencha no seguinte formato: Rua Nome da Rua, 0', function(v) {
        //return Validation.get('IsEmpty').test(v) || /(^[\w\s][^0-9|,]*)(((,\s|(N|n)º\s)([0-9/]+))|(\s?([0-9/]+)))+(.*?)$/.test(v);
        // http://regex101.com/
        return Validation.get('IsEmpty').test(v) || /^([\w\s][^|]*)(,\s)([0-9]{1,5})([\W\w\s]*)/.test(v); // Comentando esse trecho devido o caso de logradouro sem numero - ticket 6214, descomentei por achar a solução documentada no ticket 6647
        //return true;
    }],

    ['validate-telefone-br', 'Formato para Telefone inválido, preencha no formato requerido: (99) 9999-9999', function(v) {
        return Validation.get('IsEmpty').test(v) || /^(\()?\d{2}(\))?(-|\s)?\d{4}(-|\s)\d{4}$/.test(v);
    }],

    ['validate-telefone-br-nove-mascarado', 'Formato para Telefone inválido, preencha no formato requerido: (99) 99999-9999', function(v) {
        return Validation.get('IsEmpty').test(v) || /^(\()?\d{2}(\))?(-|\s)?\d{5}(-|\s)\d{4}$/.test(v);
    }],

    ['validate-celular-nove-digitos', 'Formato para Telefone inválido, preencha no formato requerido: 999999999', function(v) {
        return Validation.get('IsEmpty').test(v) || /^\d{9}$/.test(v);
    }],

    ['validate-mobile', 'Formato para Telefone inválido, preencha no formato requerido: +99 (99) 9999-9999', function(v) {
        return Validation.get('IsEmpty').test(v) || /^[\+]\d{2}?(-|\s)(\()?\d{2}(\))?(-|\s)?\d{4}(-|\s)\d{4}$/.test(v);
    }],

    ['validate-zip-brasil', 'Formato para CEP inválido, preencha no formato requerido: 99999-999', function(v) {
        return Validation.get('IsEmpty').test(v) || /(^\d{8}$)|(^\d{5}-\d{3}$)/.test(v);
    }],

    ['validate-cpf', 'CPF inválido, preencha no formato requerido: 999.999.999-99', function(v, elm) {

        var libCpfCnpj = require("cpf_cnpj");
        var CPF = libCpfCnpj.CPF;

        var isValid_cpf = CPF.isValid(v, true);

        console.log(isValid_cpf);

        return (isValid_cpf);
    }],

    ['validate-cnpj', 'CNPJ Inválido, preencha no formato requerido', function(v, elm) {

        var libCpfCnpj = require("cpf_cnpj");
        var CNPJ = libCpfCnpj.CNPJ;

        var isValid_cnpj = CNPJ.isValid(v, true);

        console.log(isValid_cnpj);

        return (isValid_cnpj);
    }]

]);

//---

console.log(Validation.creditCartTypes._object);

//

var relation_Cc = [
 // cc default
    {
        id_magento: 'VI',
        id_warder: 'visa',
    },
    {
        id_magento: 'MC',
        id_warder: 'mastercard',
    },
    {
        id_magento: 'AE',
        id_warder: 'amex',
    },
    {
        id_magento: 'DI',
        id_warder: 'discover',
    },
    {
        id_magento: 'JCB',
        id_warder: 'jcb',
    },
    {
        id_magento: 'SM',
        id_warder: 'maestro',
    },
 // cc new
    {
        id_magento: 'DC',
        id_warder: 'dinersclub',
    },
    {
        id_magento: 'ELO',
        id_warder: 'elo',
    },
    {
        id_magento: 'AU',
        id_warder: 'aura',
    },
    {
        id_magento: 'hipercard',
        id_warder: 'hipercard',
    },
];

//

// https://www.npmjs.com/package/creditcard-info
var creditcardInfo = require('creditcard-warder');
var creditcardRules = creditcardInfo().rules;

//

for (var key in relation_Cc) {
    if (relation_Cc.hasOwnProperty(key)) {

        var key = key;
        var item = relation_Cc[key];

        //console.log(key);
        //console.log(item);

        var id_magento = item.id_magento;
        var id_warder = item.id_warder;

        //console.log('id_magento: ' + id_magento + ' id_warder: ' + id_warder);

        var regexp_cvn = '^[0-9]{3}$';

        if (typeof Validation.creditCartTypes._object[id_magento] != "undefined") {
            var regexp_card_number = Validation.creditCartTypes._object[id_magento][0];
            var regexp_cvn = Validation.creditCartTypes._object[id_magento][1];
            var luhn = Validation.creditCartTypes._object[id_magento][2];
            /*console.log('##### Native RegExp');
            console.log(regexp_card_number);
            console.log(regexp_cvn);
            console.log(luhn);*/
        }

        // grab the Array item which matchs
        var item_Rule = creditcardRules.find(item => item.type === id_warder);
        var regexp_card_number = item_Rule.pattern;
        //console.log(regexp_card_number);

        /*console.log('##### New RegExp');
        console.log(regexp_card_number);
        console.log(regexp_cvn);*/

        Validation.creditCartTypes.set(id_magento, [new RegExp(regexp_card_number), new RegExp(regexp_cvn), true]);

    }
}

Validation.creditCartTypes.set('hiper', [new RegExp(/^(637095\d{10}(\d{3})?)|(3841\d{15})$/), new RegExp('^[0-9]{3}$'), true]);

//console.log(Validation);
console.log(Validation.creditCartTypes._object);

//---
