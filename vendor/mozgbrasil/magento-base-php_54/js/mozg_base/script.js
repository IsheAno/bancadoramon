/**
 * Copyright © 2016 Mozg. All rights reserved.
 * See LICENSE.txt for license details.
 */

//

// 2017-10-25 18:35:42

//

var getFnName = function(callee){
    var _callee = callee.toString().replace(/[\s\?]*/g,""),
    comb = _callee.length >= 50 ? 50 :_callee.length;
    _callee = _callee.substring(0,comb);
    var name = _callee.match(/^function([^\(]+?)\(/);
    if(name && name[1]){
    return name[1];
    }
    var caller = callee.caller,
    _caller = caller.toString().replace(/[\s\?]*/g,"");
    var last = _caller.indexOf(_callee),
    str = _caller.substring(last-30,last);
    name = str.match(/var([^\=]+?)\=/);
    if(name && name[1]){
    return name[1];
    }
    return "anonymous"
};

//

// https://plainjs.com/javascript/events/live-binding-event-handlers-14/

// helper for enabling IE 8 event bindings
function addEvent(el, type, handler) {
    if (el.attachEvent) el.attachEvent('on'+type, handler); else el.addEventListener(type, handler);
}

// live binding helper
function live(selector, event, callback, context) {
    addEvent(context || document, event, function(e) {
        var found, el = e.target || e.srcElement;
        while (el && !(found = el.id == selector)) el = el.parentElement;
        if (found) callback.call(el, e);
    });
}

//

/**
 * Checks display and visibility of elements and it's parents
 * @param  DomElement  el
 * @param  boolean isDeep Watch parents? Default is true
 * @return {Boolean}
 *
 * @author Oleksandr Knyga <oleksandrknyga@gmail.com>
 */
function Mozg_isVisible(el, isDeep) {
    var elIsVisible = true;

    if("undefined" === typeof isDeep) {
        isDeep = true;
    }

    elIsVisible = elIsVisible && el.offsetWidth > 0 && el.offsetHeight > 0;

    if(isDeep && elIsVisible) {

        while('BODY' != el.tagName && elIsVisible) {
            elIsVisible = elIsVisible && 'hidden' != window.getComputedStyle(el).visibility;
            el = el.parentElement;
        }
    }

    return elIsVisible;
}

//

// http://stackoverflow.com/questions/460644/trigger-an-event-with-prototype

Element.prototype.triggerEvent = function(eventName) {
    if (document.createEvent) {
        var evt = document.createEvent('HTMLEvents');
        evt.initEvent(eventName, true, true);

        return this.dispatchEvent(evt);
    }

    if (this.fireEvent)
        return this.fireEvent('on' + eventName);
}


//

//http://lawrence.ecorp.net/inet/samples/regexp-format.php
function cleanString(str) {
    return str.replace(/[^\d]/g, "");
}

//

//http://thecodeabode.blogspot.com/2010/11/window-open-popup-blocker-detect-for.html

var PopupWarning = {

    init: function() {
        this.urlPopup = arguments[0];
        this.widthPopup = arguments[1];
        this.heightPopup = arguments[2];
        this.idPopup = arguments[3];

        // http://stackoverflow.com/questions/16692119/how-do-i-subtract-a-percentage-from-a-number-in-a-string
        // subtract 10% of valueInString from
        var valueInString = this.widthPopup;
        var num = parseFloat(valueInString);
        var val = num - (num * .10);
        console.log(valueInString);
        console.log(num);
        console.log(val);
        this.widthDialog = val;

        var popups_are_disabled = this.popups_are_disabled();
        //alert(popups_are_disabled);
        if (popups_are_disabled == false) {
            //alert('Popup is NOT Blocked');
            this.redirect_to_action_page();
        } else {
            //alert('Popup is Blocked');
            this.redirect_to_instruction_page();
        }
    },

    redirect_to_instruction_page: function() {
        Dialog.confirm('Está ativado em seu navegador o recurso de bloqueio de popup, portanto clique no botão abaixo "Abrir Janela Popup" para abrir a nova janela.', {
            id: this.idPopup + '_' + Math.random(),
            className: 'magento',
            title: 'Aviso',
            width: this.widthDialog,
            height: 150,
            zIndex: 2000,
            destroyOnClose: true,
            showEffect: Effect.BlindDown,
            hideEffect: Effect.SwitchOff,
            cancelLabel: "Cancelar",
            okLabel: "Abrir janela Popup",
            ok: function(win) {
                PopupWarning.redirect_to_action_page();
                return true;
            },
            cancel: function(win) {}
        });
    },

    redirect_to_action_page: function() {
        window.open(this.urlPopup, this.idPopup, "toolbar=0,location=0,directories=0,status=1,menubar=1,titlebar=1,scrollbars=1,resizable=0,screenX=0,screenY=0,left=0,top=0,width=" + this.widthPopup + ",height=" + this.heightPopup + "");
    },

    popups_are_disabled: function() {
        var popup = window.open("about:blank", "popup_tester", "width=1,height=1,left=0,top=0");
        if (!popup || popup.closed || typeof popup == 'undefined' || typeof popup.closed == 'undefined' || popup.location == 'about:blank') {
            return true;
        }

        window.focus();
        popup.blur();

        //
        // Chrome popup detection requires that the popup validates itself - so we need to give
        // the popup time to load, then call js on the popup itself
        //
        if (navigator && (navigator.userAgent.toLowerCase()).indexOf("chrome") > -1) {
            var on_load_test = function() {
                PopupWarning.test_chrome_popups(popup);
            };
            var timer = setTimeout(on_load_test, 60);
            return;
        }

        popup.close();
        return false;
    },

    test_chrome_popups: function(popup) {
        if (popup && popup.chrome_popups_permitted && popup.chrome_popups_permitted() == true) {
            popup.close();
            return true;
        }

        //
        // If the popup js fails - popups are blocked
        //
        this.redirect_to_instruction_page();
    }
};

var popupIsClosed = false;

function openPopupsMozg(urlPopup, typePopup, processPopup, titlePopup, idPopup) {

    if (document.body && document.body.offsetWidth) {
        widthPopup = document.body.offsetWidth;
        heightPopup = document.body.offsetHeight;
        //console.log('widthPopup: ' + widthPopup + ' heightPopup: ' + heightPopup);
    }

    if (document.compatMode == 'CSS1Compat' && document.documentElement && document.documentElement.offsetWidth) {
        widthPopup = document.documentElement.offsetWidth;
        heightPopup = document.documentElement.offsetHeight;
        //console.log('widthPopup: ' + widthPopup + ' heightPopup: ' + heightPopup);
    }

    if (window.innerWidth && window.innerHeight) {
        widthPopup = window.innerWidth;
        heightPopup = window.innerHeight;
        //console.log('widthPopup: ' + widthPopup + ' heightPopup: ' + heightPopup);
    }

    widthPopup = 1010;
    heightPopup = heightPopup - 80;
    //console.log('widthPopup: ' + widthPopup + ' heightPopup: ' + heightPopup);

    if (heightPopup > 600) {
        heightPopup = 600;
    }

    if (typePopup == 'WinXilinus') {

        contentWinMozg = new Window({
            id: "xilinus_window_magento_cerebrum_telencephalon_" + idPopup,
            className: 'magento',
            title: '',
            //url:urlPopup,
            width: widthPopup,
            height: heightPopup,
            zIndex: 2000,
            destroyOnClose: true,
            //showEffect:Effect.BlindDown,
            //hideEffect: Effect.SwitchOff,
            onClose: function() {
                popupIsClosed = true;
            }
        });

        contentWinMozg.setStatusBar('<a href="' + urlPopup + '" target="_blank">' + urlPopup + '</a> &nbsp;&nbsp;&nbsp;&nbsp;');
        contentWinMozg.showCenter(true);
        contentWinMozg.setURL(urlPopup);

        contentWinMozg.setTitle(titlePopup);

        var counter = 0;

        var title_marque = '';

        var timer = setInterval(function() {

            console.log("turn no. " + counter);

            title_marque += '.';

            if (counter >= 4) {
                title_marque = '';
                counter = 0;
            }
            contentWinMozg.setTitle(title_marque);

            counter++;

        }, 1000);

        iframe = contentWinMozg.getContent();
        iframeId = iframe.id;

        iframe.onload = function() {

            clearInterval(timer);

            contentWinMozg.setTitle(titlePopup);
        }

    } else {

        if (processPopup == 'window_load') {
            PopupWarning.init(urlPopup, widthPopup, heightPopup, idPopup);
        } else {
            window.open(urlPopup, idPopup, "toolbar=0,location=0,directories=0,status=1,menubar=1,titlebar=1,scrollbars=1,resizable=0,screenX=0,screenY=0,left=0,top=0,width=" + widthPopup + ",height=" + heightPopup + "");
        }

    }

}

//

function loadJsCssfile(url, callback, filetype) {
    if (filetype == "js") {
        var fileref = document.createElement('script')
        fileref.setAttribute("type", "text/javascript")
        fileref.setAttribute("src", url)

        if (fileref.readyState) { //IE
            fileref.onreadystatechange = function() {
                if (fileref.readyState == "loaded" || fileref.readyState == "complete") {
                    fileref.onreadystatechange = null;
                    callback();
                }
            };
        } else { //Others
            fileref.onload = function() {
                callback();
            };
        }
    } else if (filetype == "css") {
        var fileref = document.createElement("link")
        fileref.setAttribute("rel", "stylesheet")
        fileref.setAttribute("type", "text/css")
        fileref.setAttribute("href", url)
    }
    if (typeof fileref != "undefined")
        document.getElementsByTagName("head")[0].appendChild(fileref)
}

//

/**
 * Customers
 */

//

// funcão para autocompletar o select box
function setSelected(select_box, value) {
    //console.log(select_box);
    //console.log(value);
    for (var i = 0; i < select_box.options.length; i++) {
        var curOpt = $(select_box.options[i]);
        //console.log(curOpt);
        //console.log(curOpt.text);
        //console.log(curOpt.value);
        if (curOpt.value == value)
            curOpt.selected = "selected";
    }
}

// funcão para autocompletar o select box
function selectEstado(select_box, value) {
    // hash para o mapeamento UF => Nome Estado
    estados = new Hash({
        AC: "Acre",
        AL: "Alagoas",
        AP: "Amapá",
        AM: "Amazonas",
        BA: "Bahia",
        CE: "Ceará",
        ES: "Espírito Santo",
        GO: "Goiás",
        MA: "Maranhão",
        MT: "Mato Grosso",
        MS: "Mato Grosso do Sul",
        MG: "Minas Gerais",
        PA: "Pará",
        PB: "Paraíba",
        PR: "Paraná",
        PE: "Pernambuco",
        PI: "Piauí",
        RJ: "Rio de Janeiro",
        RN: "Rio Grande do Norte",
        RS: "Rio Grande do Sul",
        RO: "Rondônia",
        RR: "Roraima",
        SC: "Santa Catarina",
        SP: "São Paulo",
        SE: "Sergipe",
        TO: "Tocantins",
        DF: "Distrito Federal"
    });

    var estado = estados.get(value);
    var optionsLenght = select_box.options.length;

    for (var i = 0; i < optionsLenght; i++) {
        var curOpt = $(select_box.options[i]);
        if (curOpt.text == estado) {
            curOpt.selected = "selected";
            return curOpt;
        }
    }
}

//

function denyNotNumber(field, event)
{
    var keyCode = ('which' in event) ? event.which : event.keyCode;

    // teclas backspace e delete
    if(keyCode == 8 || keyCode == 46)
        return true;

    // tecla tab
    if(keyCode == 9)
        return true;

    // teclas <- e ->
    if(keyCode == 37 || keyCode == 39)
        return true;

    // teclas home e end
    if(keyCode == 36 || keyCode == 35)
        return true;

    // teclas numericas
    if((keyCode >= 48 && keyCode <= 57) || (keyCode >= 96 && keyCode <= 105))
        return true;

    return false;
}

//

function capitalise(string) {
    return string.charAt(0).toUpperCase() + string.slice(1).toLowerCase();
}

//
