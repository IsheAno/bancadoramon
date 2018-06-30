var AW_AjaxCartProUpdaterObject = new AW_AjaxCartProUpdater('addProductConfirmation');
Object.extend(AW_AjaxCartProUpdaterObject, {
    updateOnUpdateRequest: true,
    updateOnActionRequest: false,

    beforeUpdate: function(html){
        return null;
    },
    afterUpdate: function(html, selectors){
        
        $j = jQuery;

        var header = {

            cartCache: function () {

                const baseUrl = location.protocol + '//' + location.host;

                const $cartContainer = $j('.cart-container');

                var cartTemplate = (itensCart) => {

                    return `<span class="bold cart cor-sec">
                        <a href="${ baseUrl}/checkout/cart/">
                            <svg width="26px" height="26px" viewBox="0 0 26 26" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                <g id="Symbols" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <g id="header" transform="translate(-1057.000000, -77.000000)" stroke="#C7B199">
                                        <g id="ico_carrinho/empty" transform="translate(1057.000000, 77.000000)">
                                            <g id="header_ico_Carrinho" transform="translate(1.000000, 1.000000)">
                                                <circle id="Oval" stroke-width="2" cx="9" cy="22" r="2"></circle>
                                                <circle id="Oval" stroke-width="2" cx="17" cy="22" r="2"></circle>
                                                <polyline id="Path-2" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" points="0 0 3 2 6 18 21 18"></polyline>
                                                <polyline id="Path-3" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" points="4 4 24 5 22 14 6 14"></polyline>
                                            </g>
                                        </g>
                                    </g>
                                </g>
                            </svg> 
                            ${ itensCart} itens
                        </a>
                    </span>`;
                };



                var updateCart = (cart) => {

                    $cartContainer.html(cartTemplate(cart.itemsCount));
                };

                $j.getJSON(baseUrl + '/session.php', function (data) {
                    updateCart(data.cart);
                });
            },
        };
        
        header.cartCache();
        
        return null;
    }
});
AW_AjaxCartPro.registerUpdater(AW_AjaxCartProUpdaterObject);
delete AW_AjaxCartProUpdaterObject;