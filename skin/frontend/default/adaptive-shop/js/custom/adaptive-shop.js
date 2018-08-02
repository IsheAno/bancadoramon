(function($) {

    var general = {

        init: function() {

            this.headerCache();
        },
        headerCache: function() {

            const baseUrl = location.protocol + '//' + location.host;

            const $cartContainer = $('.cart-container');
            const $loginContainer = $('#login-container');

            const myAccountIcon =
                `<svg width="21px" height="21px" viewBox="0 0 21 21" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                <g id="Symbols" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                    <g id="header" transform="translate(-869.000000, -80.000000)" stroke="#C7B199">
                        <g id="ico_login" transform="translate(869.000000, 80.000000)">
                            <g id="header_ico_Login" transform="translate(1.000000, 1.000000)">
                                <circle id="Oval" stroke-width="2" cx="9.5" cy="5.5" r="5.5"></circle>
                                <polygon id="Rectangle" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" points="3 11 16 11 19 19 0 19"></polygon>
                            </g>
                        </g>
                    </g>    
                </g>
            </svg>`;

            let anonymousUserTemplate =
                `<a href="${baseUrl}/customer/account/login/">
                    ${ myAccountIcon}
                    Login
                </a>
                <span class="separator">|</span>
                <a href="${ baseUrl}/customer/account/create/">Cadastre-se</a>`;

            let authenticatedUserTemplate =
                `<a href="${baseUrl}/customer/account/">
                        ${ myAccountIcon}
                        Minha conta
                    </a>
                    <span class="separator">|</span>
                    <a href="${ baseUrl}/customer/account/logout/">Sair&nbsp;</a>
                `;

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

            var updateUser = (authenticated) => {

                $loginContainer.html(authenticated ? authenticatedUserTemplate : anonymousUserTemplate);
            };

            var updateFormKey = (formKey) => {

                $('form[action*=form_key]').each(function() {

                    $(this).attr('action', $(this).attr('action').replace(/form_key\/([a-zA-Z0-9]+)/, 'form_key/' + formKey));
                });

                $('input[name*=form_key]').each(function() {

                    $(this).attr('value', formKey);
                });
            };

            var updateCart = (cart) => {

                $cartContainer.html(cartTemplate(cart.itemsCount));
            };

            $.getJSON(baseUrl + '/session.php', function(data) {

                updateUser(data.isLoggedIn);
                updateFormKey(data.formKey);
                updateCart(data.cart);
            });
        },
    };

    $(document).ready(function() {

        general.init();

        $(".form-list-product .qty").keyup(function() {

            var urlCartCatalog = $(this).closest(".form-list-product").find(".bt-nova button").attr('onclick');
            urlCartCatalog = urlCartCatalog.replace(/qty\/.{0,}/, 'qty/' + $(this).val() + "')");

            $(this).closest(".form-list-product").find(".bt-nova button").attr('onclick', urlCartCatalog);
        });

        $(".list-qtd span.less").on("click", function() {

            var qtd = $(this).closest(".form-list-product").find('.qty').val();
            qtd = parseInt(qtd) - 1;

            var urlCartCatalog = $(this).closest(".form-list-product").find(".bt-nova button").attr('onclick');
            urlCartCatalog = urlCartCatalog.replace(/qty\/.{0,}/, 'qty/' + qtd + "')");

            $(this).closest(".form-list-product").find(".bt-nova button").attr('onclick', urlCartCatalog);
        });

        $(".list-qtd span.more").on("click", function() {

            var qtd = $(this).closest(".form-list-product").find('.qty').val();
            qtd = parseInt(qtd) + 1;

            var urlCartCatalog = $(this).closest(".form-list-product").find(".bt-nova button").attr('onclick');
            urlCartCatalog = urlCartCatalog.replace(/qty\/.{0,}/, 'qty/' + qtd + "')");

            $(this).closest(".form-list-product").find(".bt-nova button").attr('onclick', urlCartCatalog);
        });
    });



})(jQuery);