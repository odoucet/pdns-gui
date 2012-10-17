function MarkInvalidTab(msg){
  if(!this.rendered || this.preventMark){
      return;
  }
  this.el.addClass(this.invalidClass);
  msg = msg || this.invalidText;
  switch(this.msgTarget){
      case 'qtip':
          this.el.dom.qtip = msg;
          this.el.dom.qclass = 'x-form-invalid-tip';
          if(Ext.QuickTips){
              Ext.QuickTips.enable();
          }
          break;
      case 'title':
          this.el.dom.title = msg;
          break;
      case 'under':
          if(!this.errorEl){
              var elp = this.el.findParent('.x-form-element', 5, true);
              this.errorEl = elp.createChild({cls:'x-form-invalid-msg'});
              this.errorEl.setWidth(elp.getWidth(true)-20);
          }
          this.errorEl.update(msg);
          Ext.form.Field.msgFx[this.msgFx].show(this.errorEl, this);
          break;
      case 'side':
          if(!this.errorIcon){
              var elp = this.el.findParent('.x-form-element', 5, true);
              this.errorIcon = elp.createChild({cls:'x-form-invalid-icon'});
          }
          this.alignErrorIcon();
          this.errorIcon.dom.qtip = msg;
          this.errorIcon.dom.qclass = 'x-form-invalid-tip';
          this.errorIcon.show();
          this.on('resize', this.alignErrorIcon, this);
      break;
  
     default:
          var t = Ext.getDom(this.msgTarget);
          t.innerHTML = msg;
          t.style.display = this.msgDisplay;
      break;
  }
  var pp;
  var tp = this.findParentBy(function(p, c){
      if(p.isXType('tabpanel')){
          return true;
      }
      pp = p;
  });
  if(!Ext.isEmpty(pp,false) && !Ext.isEmpty(tp,false)){
      var e = tp.getTabEl(pp);
      if(!Ext.isEmpty(e)) {
          Ext.fly(e).frame("ff0000");
      }
  }
  this.fireEvent('invalid', this, msg);
};
