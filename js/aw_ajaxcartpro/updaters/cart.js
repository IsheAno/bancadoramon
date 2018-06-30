var AW_AjaxCartProUpdaterObject = new AW_AjaxCartProUpdater('cart', ['.col-main', '#main']);
Object.extend(AW_AjaxCartProUpdaterObject, {
    updateOnUpdateRequest: true,
    updateOnActionRequest: false,

    beforeUpdate: function(html){
        return null;
    },

    update: function(html) {
        this.beforeUpdate(html);
        var selector = this._getTargetSelector();
        if (selector === null) {
            return false;
        }
        $$(selector)[0].innerHTML = html;
        this._evalScripts(html);
        this.afterUpdate(html, [selector]);
        
        return true;

    },

    afterUpdate: function(html, selectors){
        var me = this;
        selectors.each(function(selector){
            me._effect(selector);
        });

        if (typeof $j != 'undefined') {
            if ($j('body.checkout-cart-index').length) {
                $j('input[name^="cart"]').focus(function () {
                    $j(this).siblings('button').fadeIn();
                });
            }
        }
        return null;
    },

    _getTargetSelector: function() {
        var targetSelector = null;
        this.selectors.each(function(selector){
            if (targetSelector !== null) {
                return;
            }
            if ($$(selector).length > 0) {
                targetSelector = selector;
            }
        });
        return targetSelector;
    },

    _effect: function(obj) {
        var el = $$(obj)[0];
        switch(this.config.cartAnimation) {
            case 'opacity':
                el.hide();
                new Effect.Appear(el);
                break;
            case 'grow':
                el.hide();
                new Effect.BlindDown(el);
                break;
            case 'blink':
                new Effect.Pulsate(el);
                break;
            default:
        }
    }
});
AW_AjaxCartPro.registerUpdater(AW_AjaxCartProUpdaterObject);
delete AW_AjaxCartProUpdaterObject;





