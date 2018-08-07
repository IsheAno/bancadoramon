document.observe("dom:loaded", function () {

    $('loading-mask').remove();
    $('import-error').hide();
    /* CRON SCHEDULER */
    if (!$('cron_setting').value.isJSON())
        $('cron_setting').value = '{"days":[],"hours":[]}';
    cron = $('cron_setting').value.evalJSON();
    cron.days.each(function (d) {
        if ($('d-' + d)) {
            $('d-' + d).checked = true;
            $('d-' + d).ancestors()[0].addClassName('checked');
        }

    });
    cron.hours.each(function (h) {
        if ($('h-' + h.replace(':', ''))) {
            $('h-' + h.replace(':', '')).checked = true;
            $('h-' + h.replace(':', '')).ancestors()[0].addClassName('checked');
        }
    });
    $$('.cron-box').each(function (e) {
        e.observe('click', function () {

            if (e.checked)
                e.ancestors()[0].addClassName('checked');
            else
                e.ancestors()[0].removeClassName('checked');
            d = new Array
            $$('.cron-d-box INPUT').each(function (e) {
                if (e.checked)
                    d.push(e.value);
            });
            h = new Array;
            $$('.cron-h-box INPUT').each(function (e) {
                if (e.checked)
                    h.push(e.value);
            });
            $('cron_setting').value = Object.toJSON({
                days: d,
                hours: h
            });
        });
    });


    massImportAndUpdate.mapping.initialize();
    BlackBox.initialize();
});

function createCodeMirror(selector, mode)
{
    return CodeMirror.fromTextArea(selector, {
        mode: {
            name: mode
        },
        lint: false,
        lineWrapping: true,
        styleActiveLine: true,
        matchBrackets: true,
        autoCloseBrackets: true,
        autoCloseTags: true,
        autoRefresh: true
    });
}


var BlackBox = {
    previewArea: null,
    currentAjaxRequest: null,
    initialize: function () {
        new Draggable('blackbox', {
            handle: 'header',
            onEnd: function () {
                BlackBox.savePosition();
            }
        });
        BlackBox.setPositionAndSize();


        $('blackbox').on('mouseup', function () {
            BlackBox.saveSize();
        });


        $('blackbox-size').observe('click', function () {
            BlackBox.switchSize();
        });

        $('blackbox-display').observe('click', function () {
            BlackBox.switchStatus();
        });
        $$(".updateOnChange").each(function (elt) {
            elt.observe("change", function () {
                BlackBox.toggleNotification();
            })
        })
        $$('.blackbox-input').each(function (btn) {
            btn.observe('click', function () {
                BlackBox.loadSource();
            });
        })
        $$('.blackbox-output').each(function (btn) {
            btn.observe('click', function () {
                BlackBox.loadOutput();
            });
        })
        $$('.blackbox-library').each(function (btn) {
            btn.observe('click', function () {
                BlackBox.loadLibrary();
            });
        })
    },
    libraryLoaded: false,
    setCookie: function (c_name, value, exdays) {
        var exdate = new Date();
        exdate.setDate(exdate.getDate() + exdays);
        var c_value = encodeURI(value) + ((exdays === null) ? '' : '; expires=' + exdate.toUTCString());
        document.cookie = c_name + '=' + c_value + '; path=/;';
    },
    getCookie: function (c_name) {
        var c_value = document.cookie;
        var c_start = c_value.indexOf(' ' + c_name + '=');
        if (c_start === -1) {
            c_start = c_value.indexOf(c_name + '=');
        }
        if (c_start === -1) {
            c_value = null;
        } else {
            c_start = c_value.indexOf('=', c_start) + 1;
            var c_end = c_value.indexOf(';', c_start);
            if (c_end === -1) {
                c_end = c_value.length;
            }
            c_value = decodeURI(c_value.substring(c_start, c_end));
        }
        return c_value;
    },
    savePosition: function () {
        var top = $('blackbox').getStyle('top').replace('px', '');
        var left = $('blackbox').getStyle('left').replace('px', '');
        var viewport = document.viewport.getDimensions();

        if (top < 0)
            top = 0;
        if (left < 0)
            left = 0;

        if (left > viewport.width - 20)
            left = viewport.width - 20;
        if (top > viewport.height - 20)
            top = viewport.height - 20;

        this.setCookie('blackbox.top', top);
        this.setCookie('blackbox.left', left);
    },
    saveSize: function () {
        var dimensions = $$('#blackbox .content')[0].getDimensions();
        var width = dimensions.width;
        var height = dimensions.height;

        this.setCookie('blackbox.width', width);
        this.setCookie('blackbox.height', height);
//        this.previewArea.refresh();
    },
    setPositionAndSize: function () {
        var top = this.getCookie('blackbox.top');
        var left = this.getCookie('blackbox.left');
        var width = this.getCookie('blackbox.width');
        var height = this.getCookie('blackbox.height');

        if (top === null) {
            top = 250;
        }
        if (left === null) {
            left = 900;
        }
        if (width === null) {
            width = 460;
        }
        if (height === null) {
            height = 360;
        }

//        $('blackbox').setStyle({
//            width: width + 'px',
//            height: height + 'px'
//        });

        $$('#blackbox .content')[0].setStyle({
            width: width + 'px',
            height: height + 'px'
        });

        $$('#blackbox.draggable')[0].setStyle({
            top: top + 'px',
            left: left + 'px',
            display: 'block'
        });
    },
    storage: {
        top: null,
        left: null,
        width: null,
        height: null
    },
    toggleButton: function (button) {
        var icons = button.childElements();
        icons.each(function (node) {
            node.toggleClassName('hide');
        });
    },
    switchStatus: function () {


        this.toggleButton($('blackbox-display'));
        $('blackbox').toggleClassName('arr_up');

        if ($('blackbox').hasClassName('arr_up')) {
            BlackBox.storage.height = $('blackbox').getStyle('height');

            $$('#blackbox .content')[0].addClassName("hidden")
            $$('#blackbox .footer')[0].addClassName("hidden")

            $('blackbox').setStyle({
                overflow: "hidden",
                height: '35px'
            });

            $('blackbox').removeClassName('resizable');
        } else {
            $$('#blackbox .content')[0].removeClassName("hidden")
            $$('#blackbox .footer')[0].removeClassName("hidden")

            $('blackbox').setStyle({
                overflow: "auto",
                height: 'auto'
            });

            if (false === $('blackbox').hasClassName('full')) {
                $('blackbox').addClassName('resizable');
            }
        }


    },
    switchSize: function () {

        this.toggleButton($('blackbox-size'));
        $('blackbox').toggleClassName('full');

        if ($('blackbox').hasClassName('full')) {
            BlackBox.storage.top = $('blackbox').getStyle('top');
            BlackBox.storage.left = $('blackbox').getStyle('left');
            BlackBox.storage.width = $$('#blackbox .content')[0].getStyle('width');
            BlackBox.storage.height = $$('#blackbox .content')[0].getStyle('height');
            $('blackbox').setStyle({
                top: '10px',
                left: '10px',
            })
            $$('#blackbox .content')[0].setStyle({
                width: (document.viewport.getDimensions().width - 40) + 'px',
                height: (document.viewport.getDimensions().height - 100) + 'px'
            });

            $('blackbox').removeClassName('resizable');
        } else {
            $('blackbox').setStyle({
                top: BlackBox.storage.top,
                left: BlackBox.storage.left,
            });
            $$('#blackbox .content')[0].setStyle({
                width: BlackBox.storage.width,
                height: BlackBox.storage.height
            });
            $('blackbox').addClassName('resizable');
        }



    },
    loadLibrary: function () {
        BlackBox.toggleLoader();
        new Ajax.Request(massImportAndUpdate.loadLibraryUrl, {
            parameters: massImportAndUpdate.getData(),
            method: 'post',
            showLoader: false,
            onError: function (response) {
                BlackBox.toggleError(response.responseText)

            },
            onSuccess: function (response) {
                $("blackbox-title").update($("profile_name").getValue() + " - " + "Library")
                response = response.responseText.evalJSON();

                if (response.status != 'valid') {
                    BlackBox.toggleError(response.body);
                } else {

                    BlackBox.tooglePreview(response, false)
                }
                callback();

            }
        })
    },
    loadOutput: function (callback) {
        BlackBox.toggleLoader();
        $("blackbox-title").update($("profile_name").getValue() + " - " + "Loading")

        new Ajax.Request(massImportAndUpdate.loadPreviewUrl, {
            parameters: massImportAndUpdate.getData(),
            method: 'post',
            showLoader: false,
            onError: function (response) {
                BlackBox.toggleError(response.responseText)

            },
            onSuccess: function (response) {
                $("blackbox-title").update($("profile_name").getValue() + " - " + "Output Preview")
                response = response.responseText.evalJSON();
                if (response.status != 'valid') {
                    BlackBox.toggleError(response.body);
                } else {

                    BlackBox.tooglePreview(response, false)
                }
                callback();

            }
        })
    },
    loadSource: function (callback) {

        BlackBox.toggleLoader();
        $("blackbox-title").update($("profile_name").getValue() + " - " + "Loading")
        new Ajax.Request(massImportAndUpdate.loadFileUrl, {
            parameters: massImportAndUpdate.getData(),
            method: 'post',
            showLoader: false,
            onError: function (response) {
                BlackBox.toggleError(response.responseText)

            },
            onSuccess: function (response) {
                $("blackbox-title").update($("profile_name").getValue() + " - " + "Source Preview")
                response = response.responseText.evalJSON();
                if (response.status != 'valid') {
                    BlackBox.toggleError(response.body);
                } else {

                    BlackBox.tooglePreview(response, true)
                }
                callback();

            }
        })
    },
    tooglePreview: function (data, source) {
        BlackBox.hideAllArea()
        table = "<table cellspacing='0' cellpading='0' width='100%'><thead><tr>";

        data.headers.each(function (header, i) {
            if (i == $("sku_offset").getValue() && source) {
                name = "<b style='color:red'>identifier</b>";
                variable = "identifier";
            } else {
                name = header;
                variable = header;
            }
            if (source) {

                table += "<th>" + name + " <br><i> $cell[" + i + "] | $cell['" + variable + "']</i></th>"
            } else {
                table += "<th>" + name + "</th>"
            }
        })
        table += "</tr></thead><tbody>";
        data.body.each(function (line) {
            table += "<tr>"
            line.each(function (value) {
                table += "<td>" + value + "</td>";
            })
            table += "</tr>"
        })
        $('content-preview').toggleClassName('hide').update(table);
        table += "</tbody><table>";
        if (source) {
            massImportAndUpdate.data = data.headers;
            massImportAndUpdate.mapping.updateSource();
        }

    },
    toggleNotification: function () {
        $("blackbox-title").update($("profile_name").getValue() + " - " + "Notification")
        BlackBox.hideAllArea()
        $('content-notification').toggleClassName('hide');

    },
    toggleError: function (msg) {
        $("blackbox-title").update($("profile_name").getValue() + " - " + "Error")
        BlackBox.hideAllArea()
        $('content-error').toggleClassName('hide').update(msg);

    },
    toggleLoader: function () {

        BlackBox.hideAllArea()
        $('content-loader').toggleClassName('hide');
    },
    hideAllArea: function () {
        $$('#blackbox .area').each(function (item) {
            item.addClassName('hide');
        });
    },
    setActiveButton: function (btn) {
        $$('#blackbox .button.active').each(function (activeBtn) {
            activeBtn.removeClassName('active');
        });
        $$('#blackbox .button.' + btn).each(function (inactiveBtn) {
            inactiveBtn.addClassName('active');
        });
    }
};


var massImportAndUpdate = {
    data: new Array,
    testFtp: function (url) {
        data = Form.serialize($$('FORM')[0], true);
        new Ajax.Request(url, {
            parameters: data,
            method: 'post',
            onSuccess: function (response) {
                alert(response.responseText);
            }
        });
    },
    getData: function () {

        if ($$('#identifier OPTION[value="' + $('identifier_code').value + '"]').length) {
            label = $$('#identifier OPTION[value="' + $('identifier_code').value + '"]')[0].innerText
        } else {
            label = "";
        }
        return {
            file_path: $('file_path').value,
            file_system_type: $('file_system_type').value,
            xpath_to_product: $('xpath_to_product').value,
            file_type: $('file_type').value,
            ftp_host: $('ftp_host').value,
            ftp_login: $('ftp_login').value,
            ftp_password: $('ftp_password').value,
            ftp_dir: $('ftp_dir').value,
            use_sftp: $('use_sftp').value,
            ftp_active: $('ftp_active').value,
            file_separator: $('file_separator').value,
            file_enclosure: $('file_enclosure').value,
            auto_set_instock: $('auto_set_instock').value,
            identifier_script: $('identifier_script').value,
            identifier_code: $('identifier_code').value,
            identifier_label: label,
            sku_offset: $('sku_offset').value,
            mapping: $('mapping').value,
            preserve_xml_column_mapping: $("preserve_xml_column_mapping").value,
            xml_column_mapping: $("xml_column_mapping").value,
            url_authentication: $('url_authentication').value,
            url_login: $('url_login').value,
            url_password: $('url_password').value,
            dropbox_token: $('dropbox_token').value,
            line_filter: $('line_filter').value,
            has_header: $('has_header').value,
            webservice_params: $('webservice_params').value,
            webservice_login: $('webservice_login').value,
            webservice_password: $('webservice_password').value
        };
    },
    mapping: {
        initialize: function () {

            xml_column_mapping = createCodeMirror($("xml_column_mapping"), "application/ld+json");
            function updateTextArea() {
                xml_column_mapping.save();
                BlackBox.toggleNotification();
            }
            xml_column_mapping.on('change', updateTextArea);

            massImportAndUpdate.mapping.sortable();
            document.observe('click', function (elt) {
                if (elt.findElement(".scope-link")) {

                    var row = elt.findElement(".scope-link").up(".scope-row");
                    massImportAndUpdate.mapping.scope.open(row)
                }
                if (elt.findElement(".scope-details .chevron-down")) {
                    var row = elt.findElement(".scope-details").up(".scope-row");
                    massImportAndUpdate.mapping.scope.close(row)
                }
                if (elt.findElement(".mapping-row .trash")) {
                    var row = elt.findElement(".mapping-row .trash").up("li.sortable");
                    massImportAndUpdate.mapping.row.delete(row)
                }
                if (elt.findElement(".scope-details BUTTON.apply")) {
                    var row = elt.findElement(".scope-details BUTTON.apply").up("li");
                    massImportAndUpdate.mapping.scope.apply(row)
                }
                if (elt.findElement(".scope-details BUTTON.reset")) {
                    var row = elt.findElement(".scope-details BUTTON.reset").up("li");
                    massImportAndUpdate.mapping.scope.reset(row)
                }
                if (elt.findElement(".mapping-row .code")) {
                    var row = elt.findElement(".mapping-row .code").up("li");
                    massImportAndUpdate.mapping.script.open(row)
                }
                if (elt.findElement("#scripting .validate")) {
                    massImportAndUpdate.mapping.script.validate()
                }
                if (elt.findElement("#scripting .cancel")) {
                    massImportAndUpdate.mapping.script.cancel()
                }
                if (elt.findElement("#scripting .clear")) {
                    massImportAndUpdate.mapping.script.clear()
                }
                if (elt.findElement(".mapping-row .link")) {
                    var row = elt.findElement(".mapping-row .link").up("li");
                    massImportAndUpdate.mapping.row.activate(row)
                }

                if (elt.findElement("A.new-row")) {
                    massImportAndUpdate.mapping.row.add()
                }

            })
            document.observe('change', function (elt) {
                if (elt.findElement("#identifier")) {
                    $("identifier_code").setValue(elt.findElement("#identifier").getValue());
                }
                if (elt.findElement("#identifier_source")) {
                    $("sku_offset").setValue(elt.findElement("#identifier_source").getValue());
                }
                if (elt.findElement(".mapping-row .attribute")) {
                    var row = elt.findElement(".mapping-row .attribute").up("li");
                    massImportAndUpdate.mapping.row.save(row)
                }
                if (elt.findElement(".mapping-row .source")) {
                    var row = elt.findElement(".mapping-row .source").up("li");
                    if (elt.findElement(".mapping-row .source").getValue() == '') {
                        row.select(".default")[0].removeClassName("invisible");
                    } else {
                        row.select(".default")[0].addClassName("invisible");
                    }

                    massImportAndUpdate.mapping.row.save(row)
                }
                if (elt.findElement(".mapping-row .default")) {
                    var row = elt.findElement(".mapping-row .default").up("li");
                    massImportAndUpdate.mapping.row.save(row)
                }
            })

            BlackBox.loadSource(function () {
                $$("LI.sortable").each(function (li) {
                    massImportAndUpdate.mapping.scope.apply(li)
                })
            });


        },
        sortable: function () {
            Sortable.create('mapping-area', {
                handle: 'grip',
                constraint: "vertical",
                scroll: window,
                only: 'sortable',
                onChange: function () {
                    massImportAndUpdate.mapping.save()
                }
            })
        },
        updateSource: function () {
            mapping = $("mapping").getValue().evalJSON();

            options = new Array;
            options.push("<option value = ''>custom value</option>")
            massImportAndUpdate.data.each(function (header, i) {

                options.push("<option value='" + i + "'>" + header + "</option>")
            })
            $$("#mapping-area .source").each(function (src, i) {

                src.update(options.join(""));
                src.select("OPTION")[0].selected = true;
                src.select("OPTION").each(function (option, x) {

                    if (typeof mapping[i].source != "undefined" && mapping[i].source == option.innerText) {
                        src.select("OPTION")[x].selected = true;
                    } else if (mapping[i].index == option.value) {
                        src.select("OPTION")[x].selected = true;
                    }
                })
            })

            $("identifier_source").update(options.join(""));
            sku_offset = $("sku_offset").getValue();
            $("identifier_source").select("OPTION").each(function (option, x) {

                if (sku_offset == option.value) {
                    $("identifier_source").select("OPTION")[x].selected = true;
                }
            })


        },
        save: function () {
            mapping = new Array();
            $$(".agregate").each(function (agregate) {
                if (agregate.getValue() != "") {
                    mapping.push(agregate.getValue().evalJSON());
                }

            })
            $("mapping").setValue(Object.toJSON(mapping));
        },
        row: {
            add: function () {
                $$(".add-row")[0].insert({before: massImportAndUpdate.template});

                var row = $$("LI.sortable")[$$("LI.sortable").length - 1]


                options = new Array;
                options.push("<option value = ''>custom value</option>")
                massImportAndUpdate.data.each(function (header, i) {

                    options.push("<option value='" + i + "'>" + header + "</option>")
                })
                row.select(".source")[0].update(options.join(""));
                row.select(".source")[0].select("OPTION")[0].selected = true;


                massImportAndUpdate.mapping.scope.apply(row)
                updateCreateConfigurableOnthefly();
                massImportAndUpdate.mapping.row.save(row)
                massImportAndUpdate.mapping.sortable()
            },
            save: function (li) {

                agregate = li.select('.agregate')[0];
                data = {};
                data.id = li.select('.attribute')[0].getValue();

                data.label = li.select(".attribute OPTION[value='" + data.id + "']")[0].innerText;
                data.index = li.select('.source')[0].getValue();
                data.source = "";
                if (data.index) {
                    data.source = li.select('.source OPTION')[Math.round(data.index) + 1].innerText;
                }

                data.default = li.select('.default')[0].getValue();
                data.scripting = li.select('.scripting')[0].getValue();
                storeviews = new Array();
                li.select(".scope-details INPUT[type='checkbox']").each(function (input) {
                    if (input.checked) {
                        storeviews.push(input.getValue());
                    }
                })

                data.storeviews = storeviews.uniq()
                data.enabled = !li.select('.link')[0].hasClassName("disabled");
                agregate.setValue(Object.toJSON(data));
                massImportAndUpdate.mapping.save()



            },
            delete: function (row) {
                if (confirm("Do you really want to delete this row?")) {
                    row.remove();
                    massImportAndUpdate.mapping.save()
                }

            },
            activate: function (row) {
                attribute = row.select(".attribute")[0];
                link = row.select(".link")[0];
                source = row.select(".source")[0];
                default_ = row.select(".default")[0];
                code = row.select(".code")[0];
                if (!link.hasClassName("disabled")) {
                    attribute.addClassName("disabled");
                    link.addClassName("disabled");
                    source.addClassName("disabled");
                    default_.addClassName("disabled");
                    code.addClassName("disabled");
                } else {
                    attribute.removeClassName("disabled");
                    link.removeClassName("disabled");
                    source.removeClassName("disabled");
                    default_.removeClassName("disabled");
                    code.removeClassName("disabled");
                }

                massImportAndUpdate.mapping.row.save(row)
            }
        },
        script: {
            row: null,
            open: function (row) {
                value = row.select(".scripting")[0].getValue();
                if (value.trim() == '') {
                    value = "<?php\n /* Your custom script */\n return $self;\n";
                }
                $$("#scripting #codemirror")[0].setValue(value.replace(/__LINE_BREAK__/g,"\n"));
                editor = createCodeMirror($$("#scripting #codemirror")[0], "application/x-httpd-php-open")
//                editor = CodeMirror.fromTextArea($$("#scripting #codemirror")[0], {
//                    lineNumbers: true,
//                    autoRefresh: true,
//                    matchBrackets: true,
//                    mode: "application/x-httpd-php-open",
//                    indentUnit: 4,
//                    indentWithTabs: true,
//                    enterMode: "keep",
//                    tabMode: "shift"
//
//                });
                massImportAndUpdate.mapping.script.row = row;
                $("overlay").setStyle({display: 'block'})
                new Draggable("scripting", {handle: 'handler'});
            },
            clear: function () {
                editor.setValue('');
                massImportAndUpdate.mapping.script.validate();

            },
            validate: function () {
                if (editor) {

                    massImportAndUpdate.mapping.script.row.select(".scripting")[0].setValue(editor.getValue().replace(/(?:\r\n|\r|\n)/g,"__LINE_BREAK__"));
                    if (editor.getValue() != "") {
                        massImportAndUpdate.mapping.script.row.select('.code')[0].addClassName("active")

                        massImportAndUpdate.mapping.script.row.select('.default')[0].addClassName("invisible")
                    } else {

                        massImportAndUpdate.mapping.script.row.select('.code')[0].removeClassName("active")
                      
                        if (massImportAndUpdate.mapping.script.row.hasClassName("sortable")) {
                            if (massImportAndUpdate.mapping.script.row.select(".source")[0].getValue() == '') {
                                massImportAndUpdate.mapping.script.row.select(".default")[0].removeClassName("invisible");
                            } else {
                                massImportAndUpdate.mapping.script.row.select(".default")[0].addClassName("invisible");
                            }
                        }
                    }
                }
                if (massImportAndUpdate.mapping.script.row.hasClassName("sortable")) {
                    massImportAndUpdate.mapping.row.save(massImportAndUpdate.mapping.script.row)
                }
                massImportAndUpdate.mapping.script.close()

            },
            cancel: function () {

                massImportAndUpdate.mapping.script.close()
            },
            close: function () {
                if (editor) {


                    editor.setValue('')
                    editor.toTextArea();
                }
                massImportAndUpdate.mapping.script.row = null;
                $("overlay").setStyle({display: 'none'})
            }
        },
        scope: {
            open: function (row) {
                row.down('.scope-link').addClassName("hidden");
                row.down('.scope-details').removeClassName("hidden");
            },
            close: function (row) {
                row.down('.scope-link').removeClassName("hidden");
                row.down('.scope-details').addClassName("hidden");

            },
            apply: function (row) {
                global = new Array();
                if (row.select('.default-scope')[0].checked) {
                    global.push("Default value");
                }
                row.select(".website").each(function (website) {
                    website.select(".store").each(function (store) {
                        list = website.select('.label')[0].innerText + " > ";
                        list += store.select('.label')[0].innerText + " > ";
                        views = new Array();
                        store.select(".store-view").each(function (view) {
                            if (view.select("INPUT[type='checkbox']")[0].checked) {
                                views.push(view.select('.label')[0].innerText);
                            }

                        })
                        if (views.length) {
                            list += views.join(',');
                            global.push(list);
                        }

                    })


                });
                if (global.length < 1) {
                    row.select(".default-scope")[0].checked = true;
                    massImportAndUpdate.mapping.scope.apply(row);
                    return;
                }
                row.select(".scope-summary")[0].update(global.join(" | "))

                massImportAndUpdate.mapping.scope.close(row)
                massImportAndUpdate.mapping.row.save(row)
            },
            reset: function (row) {


                massImportAndUpdate.mapping.scope.close(row)
            }

        }
    }
};
function Object2Array(obj) {
    return Object.keys(obj).map(function (x) {
        return obj[x];
    });
}