/**
  * Ext.ux.IconCombo Extension Class for Ext 2.x Library
  *
  * @author  Ing. Jozef Sakalos
  * @version $Id: IconCombo.js 3378 2008-10-18 19:57:56Z chris $
  *
  * @class Ext.ux.IconCombo
  * @extends Ext.form.ComboBox
  */
Ext.ux.IconCombo = Ext.extend(Ext.form.ComboBox, {
    initComponent:function() {
 
        Ext.apply(this, {
            tpl:  '<tpl for=".">'
                + '<div class="x-combo-list-item ux-icon-combo-item '
                + '{' + this.iconClsField + '}">'
                + '{' + this.displayField + '}'
                + '</div></tpl>'
        });
 
        // call parent initComponent
        Ext.ux.IconCombo.superclass.initComponent.call(this);
 
    }, // end of function initComponent
 
    onRender:function(ct, position) {
        // call parent onRender
        Ext.ux.IconCombo.superclass.onRender.call(this, ct, position);
 
        // adjust styles
        this.wrap.applyStyles({position:'relative'});
        this.el.addClass('ux-icon-combo-input');
 
        // add div for icon
        this.icon = Ext.DomHelper.append(this.el.up('div.x-form-field-wrap'), {
            tag: 'div', style:'position:absolute'
        });
    }, // end of function onRender
 
    setIconCls:function() {
        var rec = this.store.getById(this.getValue());
        if(rec) {
            this.icon.className = 'ux-icon-combo-icon ' + rec.get(this.iconClsField);
        }
    }, // end of function setIconCls
 
    setValue: function(value) {
        Ext.ux.IconCombo.superclass.setValue.call(this, value);
        this.setIconCls();
    } // end of function setValue
});
 
// register xtype
Ext.reg('iconcombo', Ext.ux.IconCombo);
