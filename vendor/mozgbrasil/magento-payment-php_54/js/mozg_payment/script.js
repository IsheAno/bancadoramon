/**
 * Copyright © 2017 Mozg. All rights reserved.
 * See LICENSE.txt for license details.
 */

//

// 2017-10-25 18:35:42

//

/*
- object literals
*/

//

card_previousCardNumber = "";

var debugData = [];

var Mozg = Mozg || {};

Mozg.Payment = {

    _version: '1.0.0',

    construct: function(){

        debugData.push(arguments.callee.name);

        console.log('Mozg.Payment.construct');

        console.log(arguments);

        //

    },

    variableObject: {},

    initialize: function(){

        debugData.push(arguments.callee.name);

        console.log('Mozg.Payment.initialize');

        console.log(arguments);

        //

        var variableObject = Mozg.Payment.variableObject;

        variableObject.config = arguments[0];

        //

        Mozg.Payment.construct(variableObject.config);

        //

        debugData.push(variableObject.config);

        //

    },

    fixPci: function(){

        debugData.push(arguments.callee.name);

        console.log('Mozg.Payment.fixPci');

		//#

		//

	    if(typeof(checkout) !== 'undefined'){
	        checkout.gotoSection('payment');
	    }

	    //

	    // http://www.w3schools.com/jsref/coll_form_elements.asp
	    var myForm = document.getElementById("co-payment-form");
	    //console.log(myForm);
	    if(!myForm){
	        var myForm = document.getElementById("onestepcheckout-general-form");
	    }
	    //console.log(myForm);
	    var i;
	    for (i = 0; i < myForm.length; i++) {
	        if(myForm.elements[i].type == 'text'){
	            //console.log(myForm.elements[i]);
	            //console.log(myForm.elements[i].type);
	            //console.log(myForm.elements[i].value);
	            myForm.elements[i].value = '';
	        }
	    }

		//

		//#

    },

    observerCheckout: function(type, message){

        debugData.push(arguments.callee.name);

        console.log('Mozg.Payment.observerCheckout');

		//#

		//

	    // https://erfanimani.com/four-ways-to-edit-magentos-javascript

	    //

        if(typeof(Checkout) !== 'undefined'){

            // Add a "parent_METHOD" method to prototype which is then a clone of the super "METHOD"
    	    Checkout.prototype.parent_reloadReviewBlock = Checkout.prototype.reloadReviewBlock;

    	    // Using addMethods() to overload toggleFull
    	    Checkout.addMethods({
    	        reloadReviewBlock: function() {

    	            console.log('Payment.prototype.reloadReviewBlock');
    	            console.log(arguments);

    	            // As we "cloned" "METHOD" to "parent_METHOD", we still have access to the super method
    	            // And, as in wrap(), we can avoid calling this.parent_toggleFull() if we do not want to use the super method
    	            this.parent_reloadReviewBlock();

    	        }
    	    });

        }

	    //

        if(typeof(Payment) !== 'undefined'){

            // Add a "parent_METHOD" method to prototype which is then a clone of the super "METHOD"
    	    Payment.prototype.parent_save = Payment.prototype.save;

    	    // Using addMethods() to overload toggleFull
    	    Payment.addMethods({
    	        save: function() {

    	            console.log('Payment.prototype.save');
    	            console.log(arguments);

    	            // As we "cloned" "METHOD" to "parent_METHOD", we still have access to the super method
    	            // And, as in wrap(), we can avoid calling this.parent_toggleFull() if we do not want to use the super method
    	            this.parent_save();

    	        }
    	    });

        }

	    //

        if(typeof(Review) !== 'undefined'){

            // Add a "parent_METHOD" method to prototype which is then a clone of the super "METHOD"
    	    Review.prototype.parent_save = Review.prototype.save;

    	    // Using addMethods() to overload toggleFull
    	    Review.addMethods({
    	        save: function() {

    	            console.log('Review.prototype.save');
    	            console.log(arguments);

    	            // As we "cloned" "METHOD" to "parent_METHOD", we still have access to the super method
    	            // And, as in wrap(), we can avoid calling this.parent_toggleFull() if we do not want to use the super method
    	            this.parent_save();

    	        }
    	    });

            //

            // #######
    	    // #######
            // Caso retorne erro de transação limpa os formularios de pagamento no checkout nativo

    	    // Add a "parent_METHOD" method to prototype which is then a clone of the super "METHOD"
    	    Review.prototype.parent_nextStep = Review.prototype.nextStep;

    	    // Using addMethods() to overload toggleFull
    	    Review.addMethods({
    	        nextStep: function(transport) {

    	            console.log('Review.prototype.nextStep');
    	            console.log(arguments);

    	            var name = 'Review:nextStep';

    	            var event_name = name + '_before';
    	            var options = {
    	                _this: this,
    	                transport: transport
    	            };
    	            $(document).fire(event_name, options);
    	            console.log(event_name);

    	            // As we "cloned" "METHOD" to "parent_METHOD", we still have access to the super method
    	            // And, as in wrap(), we can avoid calling this.parent_toggleFull() if we do not want to use the super method
    	            this.parent_nextStep(transport);

    	            var event_name = name + '_after';
    	            var options = {
    	                _this: this,
    	                transport: transport
    	            };
    	            $(document).fire(event_name, options);
    	            console.log(event_name);
    	        }
    	    });

    	    //

    	    document.observe("Review:nextStep_after", function() {
    	        console.log('observe("Review:nextStep_after"');
    	        console.log(arguments);
    	        //console.log(e);

    	        var dataAvailable = arguments[0];
    	        console.log(dataAvailable);

    	        var json = dataAvailable.memo.transport.responseJSON;
    	        console.log(json);

    	        if(json.error){
    	            // Checkout_Nativo - {"success":false,"error":true,"error_messages":"message"}
    	            // IWD_Opc - {"error":"message","redirect":"http:"}
    	            var error_return = (json.error != "undefined");
    	            console.log('json.error: ' + error_return);
    	        }

    	        if (error_return){
    	            Mozg.Payment.fixPci();
    	        }

    	    });

            //

        }

	    //

	    // #######
	    // #######
	    // Caso retorne erro de transação limpa os formularios de pagamento no checkout IWD_Opc

	    //console.log(IWD);
	    //console.log(IWD.OPC);

	    if (typeof IWD !== 'undefined') {

	        var IWD_error = function(){
	            console.log('#### IWD.OPC.Plugin.event(error ');
	            console.log(arguments);

	            Mozg.Payment.fixPci();
	        };

	        IWD.OPC.Plugin.event('error', IWD_error);

	    }

	    //

		//#

    },

    boletoTefEasterEgg: function(event, method_code){

        debugData.push(arguments.callee.name);

        console.log('Mozg.Payment.boletoTefEasterEgg');

		//#

		var element = event.element();

	    //console.log(event.shiftKey);

	    if(!event.shiftKey){
	        return false;
	    }

	    // NPM

	    // https://www.npmjs.com/package/cpf_cnpj

	    var libCpfCnpj = require("cpf_cnpj");
	    var CPF = libCpfCnpj.CPF;
	    var CNPJ = libCpfCnpj.CNPJ;

	    var generate_cpf = CPF.generate(true);

	    var generate_cnpj = CNPJ.generate(true);

	    // https://www.npmjs.com/package/random-item

	    var randomItem = require('random-item');

	    var random_item = randomItem([generate_cpf, generate_cnpj]);

	    console.log(random_item);

	    // https://www.npmjs.com/package/random-firstname

	    var randomFirstName = require('random-firstname');

	    var random_firstname = randomFirstName();

	    console.log(random_firstname);

	    // https://www.npmjs.com/package/random-lastname

	    var randomLastName = require('random-lastname');

	    var random_lastname = randomFirstName();

	    console.log(random_lastname);

	    //

	    var elementId_boleto_type = method_code + '_boleto_type';
	    var elementId_eletronictransfer_type = method_code + '_eletronictransfer_type';

	    var element_boleto = document.getElementById(elementId_boleto_type);
	    var element_eletronictransfer = document.getElementById(elementId_eletronictransfer_type);

	    //console.log(element_boleto);
	    //console.log(element_eletronictransfer);

	    if(element_boleto){
	        var elementId_selectObject = element_boleto;
	    }

	    if(element_eletronictransfer){
	        var elementId_selectObject = element_eletronictransfer;
	    }

	    var selectObjectLength = elementId_selectObject.options.length;

	    var selectObjectLength = selectObjectLength - 1;

	    console.log(selectObjectLength);

	    // https://www.npmjs.com/package/random-integral

	    var randomInt = require('random-integral');

	    var _randomInt = randomInt({ min: 1, max: selectObjectLength });

	    console.log(_randomInt);

	    var elementId_social_security_number = method_code + '_social_security_number';
	    var elementId_firstname = method_code + '_firstname';
	    var elementId_lastname = method_code + '_lastname';

	    //

	    elementId_selectObject.selectedIndex = _randomInt;

	    $(elementId_social_security_number).value = random_item;
	    $(elementId_firstname).value = random_firstname;
	    $(elementId_lastname).value = random_lastname;

	    //

		//#

    },

	ccEasterEgg: function(event, method_code){

        debugData.push(arguments.callee.name);

        console.log('Mozg.Payment.ccEasterEgg');

		//#

		var element = event.element();

	    //console.log(event.shiftKey);

	    if(!event.shiftKey){
	        return false;
	    }

	    // NPM

	    // https://www.npmjs.com/package/random-item

	    var randomItem = require('random-item');

	    var random_item = randomItem(['Visa', 'MasterCard', 'Amex', 'Diners', 'Discover', 'JCB']);

	    //console.log(random_item);

	    // https://www.npmjs.com/package/creditcardgenerator

	    var MozgNodeFramework = require("@mozg/node-framework");
	    var creditcardgenerator = MozgNodeFramework.creditcardgenerator;

	    var creditcard_json = creditcardgenerator.create(random_item);

	    var random_creditcard = creditcard_json.number;

	    //console.log(random_creditcard);

	    // https://www.npmjs.com/package/random-month

	    var randomMonth = require('random-month');

	    var random_month = randomMonth();

	    //console.log(random_month);

	    // https://www.npmjs.com/package/random-year

	    var randomYear = require('random-year');

	    var random_year = randomYear({ min: 2017, max: 2026 });

	    //console.log(random_year);

	    // https://github.com/mock-end/random-natural

	    var randomNatural = require('random-natural');

	    var random_number = randomNatural({ min: 100, max: 999 });

	    //console.log(random_number);

	    // https://www.npmjs.com/package/random-fullname

	    var randomFullName = require('random-fullname');

	    var random_fullname = randomFullName();

	    //console.log(random_fullname);

	    //

	    var elementId_cc_number = method_code + '_cc_number';
	    var elementId_cc_owner = method_code + '_cc_owner';
	    var elementId_cc_cid = method_code + '_cc_cid';
	    var elementId_expiration = method_code + '_expiration';
	    var elementId_expiration_yr = method_code + '_expiration_yr';


	    //

	    $(elementId_cc_number).value = random_creditcard;
	    $(elementId_cc_owner).value = random_fullname;
	    $(elementId_cc_cid).value = random_number;
	    $(elementId_expiration).value = random_month;
	    $(elementId_expiration_yr).value = random_year;

	    $(elementId_cc_number).triggerEvent('change');
	    $(elementId_cc_number).triggerEvent('keyup');

	    //

		//#

    },

	selectBrand: function(method_code, elementId_cc_number, hasInstallments, url_installments){

	    debugData.push(arguments.callee.name);

	    console.log('Mozg.Payment.selectBrand');

	    //#

	    console.log(arguments);
	    //console.dir(this);

	    cardNumber = document.getElementById(elementId_cc_number).value;
	    dontHideErrorFrame = null;

	    //

	    var method_code = method_code;
	    var cardType = null;
	    var greyInactive = false;
	    var hasInstallments = hasInstallments;
	    var url_installments = url_installments;

	    //

	    // empty card field - reset all
	    if (cardNumber.length == 0) {
	        card_previousCardNumber = cardNumber;

	        //

	        Mozg.Payment.cardSetCardBrand(method_code, cardType, greyInactive, hasInstallments, url_installments);

	        //

	        return;
	    }

	    // When editing the card (but not adding digits at the end), don't reformat the number
	    var l=0;
	    while(l < card_previousCardNumber.length && l < cardNumber.length) {
	        if(cardNumber[l] != card_previousCardNumber[l]) {
	            card_previousCardNumber = cardNumber;
	            return;
	        }
	        l++;
	    }

	    // remove all whitespace
	    reg = /\s+/g;
	    cardNumber = cardNumber.replace(reg,'');

	    //console.log(cardNumber);

	    nrOfDigits = cardNumber.length;
	    if(nrOfDigits > 19){
	        return;
	    }

	    //

	    // https://www.npmjs.com/package/creditcard-info

	    var creditcardInfo = require('creditcard-warder');

	    var brand = creditcardInfo(cardNumber).getBrand();

	    var cardType = {};
	    cardType['visa'] = "VI";
	    cardType['mastercard'] = "MC";
	    cardType['amex'] = "AE";
	    cardType['dinersclub'] = "DC";
	    cardType['discover'] = "DI";
	    cardType['jcb'] = "JCB";
	    cardType['elo'] = "ELO";
	    cardType['hipercard'] = "hipercard";
	    cardType['aura'] = "AU";
	    cardType['visaelectron'] = "VI";
	    cardType['maestro'] = "SM";

	    //console.log(cardType);

	    var cardType = cardType[brand];

	    console.log('cardType: ' + cardType);

	    //

	    if(cardType != null) {
	        var greyInactive = true;
	        Mozg.Payment.cardSetCardBrand(method_code, cardType, greyInactive, hasInstallments, url_installments);
	    } else if(nrOfDigits > 4) {
	        var cardType = null;
	        var greyInactive = true;
	        Mozg.Payment.cardSetCardBrand(method_code, cardType, greyInactive, hasInstallments, url_installments);
	    } else {
	        var cardType = null;
	        var greyInactive = false;
	        Mozg.Payment.cardSetCardBrand(method_code, cardType, greyInactive, hasInstallments, url_installments);
	    }

	    //show value with white space after four numbers
	    result = cardNumber.replace(/(\d{4})/g, '$1 ');
	    result = result.replace(/\s+$/, ''); //remove trailing spaces
	    card_previousCardNumber = result;
	    document.getElementById(elementId_cc_number).value = result;

	    //console.log(result);

	    //#

	},

	cardSetCardBrand: function(method_code, cardType, greyInactive, hasInstallments, url_installments){

	    debugData.push(arguments.callee.name);

	    console.log('Mozg.Payment.cardSetCardBrand');

	    //#

	    console.log(arguments);

	    //

	    var elementId_group_types = method_code + '_group_types';
	    var group_types = eval(elementId_group_types);

	    var elementId_cc_type = method_code + '_cc_type';

	    var elementId_installments = method_code + '_installments';

	    var elementId_cc_cid = method_code + '_cc_cid';

	    //

	    for(var i = 0; i < group_types.length; ++i) {
	        var imageId =  elementId_cc_type + '_'  + i;
			//console.log('imageId: ' + imageId);
			//console.log('cardType: ' + cardType + ' / group_types[i]: ' + group_types[i]);
	        if(cardType != null && group_types[i] == cardType) {
				//console.log('# remove class imageId: ' + imageId);
	            // remove class
	            $(imageId).removeClassName('grey');
	            // set hidden field cc type for installments
	            $(elementId_cc_type).setValue(cardType);
	        } else {
	            //console.log(greyInactive);
	            if(greyInactive) {
					//console.log('add class imageId: ' + imageId);
	                // add class
	                $(imageId).addClassName('grey');
					//console.info($(imageId));
	            } else {
					//console.log('remove class imageId: ' + imageId);
	                // remove class
	                $(imageId).removeClassName('grey');
	            }
	        }
	        document.getElementById(imageId).style.display="inline";
	    }

	    //

	    if(hasInstallments && cardType != null ) {
	        var url = url_installments;
	        var elementId = elementId_installments;

	        /*console.log(url);
	        console.log(cardType);
	        console.log(elementId);*/

	        //var interval = setInterval(function() {
	            //#####Mozg.Payment.getInstallments(url, cardType, elementId);
	        //}, 500);
	    }

	    //

	    var elementId = elementId_cc_cid;
	    var selectedCardType = cardType != null ? cardType : null;

	    Mozg.Payment.cardSetCvcElementselectedCardType(elementId, selectedCardType);

	    Mozg.Payment.cardSetCVCRequired(elementId, selectedCardType);

	    //

	    //#

	},

	getInstallments: function(url, ccType, elementId){

	    debugData.push(arguments.callee.name);

	    console.log('Mozg.Payment.getInstallments');

	    //#

	    console.log(arguments);

	    ajaxReq = new Ajax.Request(url, {
	        parameters: {ccType: ccType, isAjax: 1, method: 'POST'},
	        onSuccess: function(transport) {

	            if(transport.status == 200) {

	                // get current selected installment
	                var currentSelectedInstallment = document.getElementById(elementId).getValue();

	                // clear the select box
	                document.getElementById(elementId).options.length = 0;

	                var response = transport.responseText.evalJSON();

	                var sel = false;
	                for (var key in response) {

	                    // change the installments
	                    var opt = document.createElement('option');
	                    opt.text = response[key]
	                    opt.value = key;
	                    // check if selected installment is still available
	                    if(currentSelectedInstallment == key) {
	                        sel = true;
	                    }
	                    $(elementId).options.add(opt);
	                }

	                if(sel == true && currentSelectedInstallment) {
	                    document.getElementById(elementId).value=currentSelectedInstallment;
	                }
	            }
	        },
	        onFailure: function(){
	            alert('Server Error. Please try again.');
	        }
	    });

	    //#

	},

	cardSetCvcElementselectedCardType: function(elementId, selectedCardType){

	    debugData.push(arguments.callee.name);

	    console.log('Mozg.Payment.cardSetCvcElementselectedCardType');

	    //#

	    console.log(arguments);

	    var cvcCodeElem = document.getElementById(elementId);

	    // first remove classnames
	    if(cvcCodeElem != null) {
	        cvcCodeElem.removeClassName('maximum-length-3');
	        cvcCodeElem.removeClassName('minimum-length-3');
	        cvcCodeElem.removeClassName('maximum-length-4');
	        cvcCodeElem.removeClassName('minimum-length-4');
	    }

	    if(selectedCardType == null) {
	        // error do nothing
	    } else if(selectedCardType == "AE") {
	        cvcCodeElem.maxLength = 4;
	        cvcCodeElem.addClassName('maximum-length-4');
	        cvcCodeElem.addClassName('minimum-length-4');
	    } else {
	        cvcCodeElem.maxLength = 3;
	        cvcCodeElem.addClassName('maximum-length-3');
	        cvcCodeElem.addClassName('minimum-length-3');
	    }

	    //#

	},

	cardSetCVCRequired: function(elementId, selectedCardType){

	    debugData.push(arguments.callee.name);

	    console.log('Mozg.Payment.cardSetCVCRequired');

	    //#

	    console.log(arguments);

	    var cvcCodeElem = document.getElementById(elementId);
	    var cvcCodeElemLabel = document.getElementById(elementId + '_label');
	    var cvcCodeElemLabelEm = document.getElementById(elementId + '_label_em');

	    if(selectedCardType != null && selectedCardType == "SM") {
	        // cvc not required
	        cvcCodeElem.removeClassName('required-entry');
	        cvcCodeElemLabel.removeClassName('required');
	        cvcCodeElemLabelEm.hide();
	    } else {
	        // cvc required
	        cvcCodeElem.addClassName('required-entry');
	        cvcCodeElemLabel.addClassName('required');
	        cvcCodeElemLabelEm.show();
	    }

	    //#

	},

	novo: function(){

	    debugData.push(arguments.callee.name);

	    console.log('Mozg.Payment.novo');

	    //#


	    //#

	},

};

//

Event.observe(window, 'load', function() {
	console.log('load');
	Mozg.Payment.observerCheckout();
});

//
