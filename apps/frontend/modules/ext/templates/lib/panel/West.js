var settingsMenu = new Ext.menu.Menu({
  items: [
    {
      text: 'Templates',
      handler: function(){ TemplateWindow() },
      iconCls: 'icon-brick'
    }
  ]
});

var West = new Ext.grid.GridPanel({
  title: 'Domains',
  iconCls: 'icon-world',
  store: DomainStore,
  hideHeaders: true,
  disableSelection: true,
  columns: [
    {
      id: 'domain',
      dataIndex: 'name',
      width: 180,
      renderer: function(v, meta, r){
        if (r.data.needs_commit)
        {
          meta.attr = 'style="font-weight: bold;"';
        }
        
        return v;
      }
    }
  ],
  viewConfig:{
    forceFit: true,
    scrollOffset: 1,
    emptyText: 'No domains to display.'
  },
  listeners: {
    cellclick: function(grid, rowIndex, columnIndex, e){
      
      var columnId = grid.getColumnModel().getColumnId(columnIndex);
      
      if (columnId == 'domain')
      {
        var record = grid.getStore().getAt(rowIndex).data;
        
        DomainWindow(record);
      }
    }
  },
  tbar: [
    {
      text: 'Settings',
      iconCls: 'icon-cog',
      menu: settingsMenu
    },{
      xtype: 'tbfill'
    },{
      text: 'Add domain',
      iconCls: 'icon-add',
      handler: function(){
        AddDomainWindow();
      }
    }
  ]
});
