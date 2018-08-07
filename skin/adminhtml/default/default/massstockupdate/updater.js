/**
 * Copyright © 2016 Wyomind. All rights reserved.
 * See LICENSE.txt for license details.
 */
var updater = {
    init: function () {
        data = new Array();
        $$('.updater').each(
            function (u) {
                profile = [u.id.replace("profile_", ""), u.readAttribute('cron')];
                data.push(profile);
            }
        );

        new Ajax.Request(
            updater_url, {
                method: 'post',
                parameters: {data: Object.toJSON(data)},
                loaderArea: false,
                onSuccess: function (response) {
                 
                    resp = (response.responseText.evalJSON());
                   
                    resp.each(
                        function (r) {
                            $("profile_" + r.id).replace(r.content);
                        }
                    );
                    setTimeout(
                        function () {
                            updater.init();
                        }, 1000
                    );
                }
            }
        );
    },
    generate: function (generate_url) {
        $$('.action-select').each(function (select) {
            select.selectedIndex = 0;
        });
        new Ajax.Request(
            generate_url, {
                method: 'get',
                asynchronous: true,
                loaderArea: false,
                onSuccess: function () {
                   location.reload();
                }
            }
        );
    }
};

document.observe(
    'dom:loaded', function () {
        updater.init();
    }
);