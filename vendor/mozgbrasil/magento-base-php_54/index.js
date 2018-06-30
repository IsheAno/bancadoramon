#!/usr/bin/env node

//

console.log(this);

//

Reset = "\x1b[0m"
BgCyan = "\x1b[46m"
consoleLogItem1 = BgCyan+'%s'+Reset;

//

console.log(consoleLogItem1, '### https://www.npmjs.com/package/cpf_cnpj');

const libCpfCnpj = require("cpf_cnpj");
var CPF = libCpfCnpj.CPF;
var CNPJ = libCpfCnpj.CNPJ;

var isValidCPF = CPF.isValid("532.820.857-96");
console.log(isValidCPF);
var isValidCNPJ = CNPJ.isValid("41.381.074/6738-65");
console.log(isValidCNPJ);


console.log(consoleLogItem1, '### https://www.npmjs.com/~mozg');

var mozg_node_framework = require('@mozg/node-framework');
//var mozg_node_framework = require("../index");

console.log(mozg_node_framework);

//

console.info(consoleLogItem1, '### https://www.npmjs.com/package/creditcardgenerator');

//var creditcardgenerator = require('creditcardgenerator');
var creditcardgenerator = mozg_node_framework.creditcardgenerator;

var creditcard_json = creditcardgenerator.create('Diners');

console.log(creditcard_json);

var creditcard = creditcard_json.number;

console.log(creditcard);

console.log('---');

//

console.info(consoleLogItem1, '### https://www.npmjs.com/package/random-natural');

var randomNatural = require('random-natural');

var random_number = randomNatural({ min: 1, max: 60 });

console.log(random_number);

var random_number = randomNatural({ min: 1, max: 60 });

console.log(random_number);

var random_number = randomNatural({ min: 1, max: 60 });

console.log(random_number);

var random_number = randomNatural({ min: 1, max: 60 });

console.log(random_number);

var random_number = randomNatural({ min: 1, max: 60 });

console.log(random_number);

var random_number = randomNatural({ min: 1, max: 60 });

console.log(random_number);

//

console.log(consoleLogItem1, '### FIM');

//
