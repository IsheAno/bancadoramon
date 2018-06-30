/**
 * Copyright © 2016 Mozg. All rights reserved.
 * See LICENSE.txt for license details.
 */

//---

function symfony_dump() {
    // http://stackoverflow.com/questions/41389409/how-do-i-make-twigs-dump-function-to-display-the-data-unfolded
    console.log($$("pre.sf-dump a"));

    var itens = [];
    $$("pre.sf-dump a").each(function() {

        console.log(arguments);

        item = arguments[0];

        item.triggerEvent('click');

        itens.push(item);

    });

    console.log(itens);

    if(itens[0]){
        itens[0].triggerEvent('click');
    }

}

window.onload=function(){
    console.info('window.onload=function()');
};

function _onLoad() {
    console.info('window.addEventListener=function()');
    symfony_dump();
}

window.addEventListener("load", function(){ _onLoad(); });

//---

console.log(this);

//---