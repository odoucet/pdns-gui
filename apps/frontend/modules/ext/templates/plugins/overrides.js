Ext.override(Ext.Panel, {
  setIconCls: function(i){
    Ext.fly(this.ownerCt.getTabEl(this)).child('.x-tab-strip-text').replaceClass(this.iconCls, i);
    this.setIconClass(i);
  }
});

//make components only save state when told to by stateful = true
Ext.override(Ext.Component, {
    saveState : function(){
        if(Ext.state.Manager && this.stateful !== false){
            var state = this.getState();
            if(this.fireEvent('beforestatesave', this, state) !== false){
                Ext.state.Manager.set(this.stateId || this.id, state);
                this.fireEvent('statesave', this, state);
            }
        }
    },
 
    stateful : false
 
});

//decodeValue erroneously decodes empty arrays and objects
//empty arrays return as [undefined]
//empty objects (untested) probably fail or return as {undefined: undefined}
//either way, a simple check for empty value portions alleviates this issue
Ext.override(Ext.state.Provider, {
    decodeValue : function(cookie){
        var re = /^(a|n|d|b|s|o)\:(.*)$/;
        var matches = re.exec(unescape(cookie));
        if(!matches || !matches[1]) return; // non state cookie
        var type = matches[1];
        var v = matches[2];
        switch(type){
            case "n":
                return parseFloat(v);
            case "d":
                return new Date(Date.parse(v));
            case "b":
                return (v == "1");
            case "a":
                var all = [];
                if (v) {
                    var values = v.split("^");
                    for(var i = 0, len = values.length; i < len; i++){
                        all.push(this.decodeValue(values[i]));
                    }
                }
                return all;
           case "o":
                var all = {};
                if (v) {
                    var values = v.split("^");
                    for(var i = 0, len = values.length; i < len; i++){
                        var kv = values[i].split("=");
                        all[kv[0]] = this.decodeValue(kv[1]);
                    }
                }
                return all;
           default:
                return v;
        }
    }
});

//Panels added to the portal have their onResize function called twice.  Once with width and height on creation,
//and again with only width when put into the ColumnLayout.  If a panel is collapsed at creation, then the
//queuedBodySize object ends up with only the second call's data for width and height, effectively making panels
//that start collapsed autoSized when expanded the first time.
Ext.override(Ext.Panel, {
    onResize : function(w, h){
        if(w !== undefined || h !== undefined){
            if(!this.collapsed){
                if(typeof w == 'number'){
                    this.body.setWidth(
                            this.adjustBodyWidth(w - this.getFrameWidth()));
                }else if(w == 'auto'){
                    this.body.setWidth(w);
                }

                if(typeof h == 'number'){
                    this.body.setHeight(
                            this.adjustBodyHeight(h - this.getFrameHeight()));
                }else if(h == 'auto'){
                    this.body.setHeight(h);
                }
            }else{
                //these two lines are the primary fix.
                if (!this.queuedBodySize) this.queuedBodySize = {};
                this.queuedBodySize = {width: w || this.queuedBodySize.width, height: h || this.queuedBodySize.height};
                if(!this.queuedExpand && this.allowQueuedExpand !== false){
                    this.queuedExpand = true;
                    //switched this from expand to beforeexpand to keep the panel
                    //from expanding to full size, then popping back down to the correct size.
                    this.on('beforeexpand', function(){
                        delete this.queuedExpand;
                        this[this.collapseEl].show();
                        this.collapsed = false;
                        this.onResize(this.queuedBodySize.width, this.queuedBodySize.height);
                        this.doLayout();
                    }, this, {single:true});
                }
            }
            this.fireEvent('bodyresize', this, w, h);
        }
        this.syncShadow();
    }
});

