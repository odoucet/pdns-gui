/* GridRowChecker */

Ext.ux.GridRowChecker = Ext.extend(Object, {
      header: "",
      width: 23,
      sortable: false,
      fixed: true,
      menuDisabled: true,
      dataIndex: '',
      id: 'selection-checkbox',
      rowspan: undefined,

      init: function(grid) {
        this.grid = grid;
        this.gridSelModel = this.grid.getSelectionModel();
        this.gridSelModel.originalMouseDown = this.gridSelModel.handleMouseDown;
      this.gridSelModel.handleMouseDown = this.onGridMouseDown;
        grid.getColumnModel().config.unshift(this);
        grid.getChecked = this.getChecked.createDelegate(this);
        grid.checkAll = this.checkAll.createDelegate(this);
        grid.uncheckAll = this.uncheckAll.createDelegate(this);
      },

    renderer: function() {
      return '<input class="x-row-checkbox" type="checkbox">';
    },

    getChecked: function() {
      var result = [];
      var cb = this.grid.getEl().query("div.x-grid3-col-selection-checkbox > input[type=checkbox]");
      var idx = 0;
      this.grid.store.each(function(rec) {
        if (cb[idx++].checked) {
          result.push(rec);
        }
      });
      delete cb;
      return result;
    },

    checkAll: function() {
      this.grid.getEl().select("div.x-grid3-col-selection-checkbox > input[type='checkbox']").each(function(e){
        e.dom.checked = true;
      });
    },

    uncheckAll: function() {
      this.grid.getEl().select("div.x-grid3-col-selection-checkbox > input[type='checkbox']").each(function(e){
        e.dom.checked = false;
      });
    },

    onGridMouseDown: function(g, rowIndex, e) {
      if (e.getTarget('div.x-grid3-col-selection-checkbox')) {
        e.stopEvent();
        return false;
      }
        this.originalMouseDown.apply(this, arguments);
    }
    });

/* emptyText fix */
Ext.form.Action.Submit.prototype.run = Ext.form.Action.Submit.prototype.run.createInterceptor(function() {
  this.form.items.each(function(item) {

    if (!item.el) return false;
    
    if (item.el.getValue() == item.emptyText) {
      item.el.dom.value = '';
    }
  });
});
 
Ext.form.Action.Submit.prototype.run = Ext.form.Action.Submit.prototype.run.createSequence(function() {
  this.form.items.each(function(item) {
    
    if (!item.el) return false;
    
    if (item.el.getValue() == '' && item.emptyText) {
      item.el.dom.value = item.emptyText;
    }
  });
 });
