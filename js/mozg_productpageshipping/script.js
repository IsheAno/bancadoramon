/**
 * Copyright © 2016 Mozg. All rights reserved.
 * See LICENSE.txt for license details.
 */

// ---

if (typeof Product == 'undefined') {
    var Product = {};
}

if (Product.Config) {

    /*
        Some of these override earlier varien/product.js methods, therefore
        varien/product.js must have been included prior to this file.
    */

    Product.Config.prototype.getMatchingSimpleProduct = function() {
        var inScopeProductIds = this.getInScopeProductIds();
        if ((typeof inScopeProductIds != 'undefined') && (inScopeProductIds.length == 1)) {
            return inScopeProductIds[0];
        }
        return false;
    };

    /*
        Find products which are within consideration based on user's selection of
        config options so far
        Returns a normal array containing product ids
        allowedProducts is a normal numeric array containing product ids.
        childProducts is a hash keyed on product id
        optionalAllowedProducts lets you pass a set of products to restrict by,
        in addition to just using the ones already selected by the user
    */
    Product.Config.prototype.getInScopeProductIds = function(optionalAllowedProducts) {

        var childProducts = this.config.childProducts;
        var allowedProducts = [];

        if ((typeof optionalAllowedProducts != 'undefined') && (optionalAllowedProducts.length > 0)) {
            // alert("starting with: " + optionalAllowedProducts.inspect());
            allowedProducts = optionalAllowedProducts;
        }

        for (var s = 0, len = this.settings.length - 1; s <= len; s++) {
            if (this.settings[s].selectedIndex <= 0) {
                break;
            }
            var selected = this.settings[s].options[this.settings[s].selectedIndex];
            if (s == 0 && allowedProducts.length == 0) {
                allowedProducts = selected.config.allowedProducts;
            } else {
                // alert("merging: " + allowedProducts.inspect() + " with: " + selected.config.allowedProducts.inspect());
                allowedProducts = allowedProducts.intersect(selected.config.allowedProducts).uniq();
                // alert("to give: " + allowedProducts.inspect());
            }
        }

        //If we can't find any products (because nothing's been selected most likely)
        //then just use all product ids.
        if ((typeof allowedProducts == 'undefined') || (allowedProducts.length == 0)) {
            productIds = Object.keys(childProducts);
        } else {
            productIds = allowedProducts;
        }
        return productIds;
    };

}

//---