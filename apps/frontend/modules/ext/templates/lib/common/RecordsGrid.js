/* Records */
Ext.ux.RecordsGrid = function(cfg){

  if (!cfg) var cfg = {};
  
  if (cfg.template)
  {
    if (!cfg.records)
    {
      cfg.records = [
        { 
          name: '%DOMAIN%', 
          type: 'SOA',
          content: 'master.dns hostmaster.%DOMAIN% %SERIAL%',
          ttl: <?php echo sfConfig::get('app_default_ttl') ?>
        },{
          name: '%DOMAIN%', 
          type: 'NS',
          content: 'master.dns',
          ttl: <?php echo sfConfig::get('app_default_ttl') ?>
        },{
          name: '%DOMAIN%', 
          type: 'MX',
          content: 'mail.server',
          ttl: <?php echo sfConfig::get('app_default_ttl') ?>,
          prio: 0
        }
      ];
    }
    
    var store = new Ext.data.JsonStore({
      fields : [ 'id','name','type','content','ttl','prio','needs_commit' ],
      root: 'records',
      data: cfg
    });
  }
  else
  {
    var store = new Ext.data.JsonStore({
      url: '<?php echo url_for('domain/listrecords') ?>',
      baseParams: { id: cfg.domain_id },
      fields : [ 'id','name','type','content','ttl','prio','needs_commit' ],
      root: 'Record',
      autoLoad: true
    });
  }
  
  var defaultCfg = {
    border: false,
    store: store,
    height: 260,
    loadMask: true,
    enableHdMenu: false,
    enableColumnMove: false,
    clicksToEdit: 1,
    columns: [
      {
        header: 'Name',
        dataIndex: 'name',
        editor: new Ext.form.TextField({
          allowBlank: false
        }),
        renderer: function(v, meta, r){
          if (r.data.needs_commit)
          {
            meta.attr = 'style="font-weight: bold;"';
          }
          
          return v;
        }
      },{
        header: 'Type',
        dataIndex: 'type',
        width: 50,
        fixed: true,
        editor: new Ext.ux.TypeCombo(),
        renderer: function(v, meta, r){
          if (r.data.needs_commit)
          {
            meta.attr = 'style="font-weight: bold;"';
          }
          
          return v;
        }
      },{
        header: 'Content',
        dataIndex: 'content',
        editor: new Ext.form.TextField({
          allowBlank: false
        }),
        renderer: function(v, meta, r){
          if (r.data.needs_commit)
          {
            meta.attr = 'style="font-weight: bold;"';
          }
          
          return v;
        }
      },{
        header: 'TTL',
        dataIndex: 'ttl',
        width: 50,
        fixed: true,
        editor: new Ext.form.TextField({
          allowBlank: false,
          maskRe: /^[0-9]$/
        }),
        renderer: function(v, meta, r){
          if (r.data.needs_commit)
          {
            meta.attr = 'style="font-weight: bold;"';
          }
          
          return v;
        }
      },{
        header: 'Prio',
        dataIndex: 'prio',
        width: 40,
        fixed: true,
        editor: new Ext.form.TextField({
          maskRe: /^[0-9]$/
        }),
        renderer: function(v, meta, r){
          if (r.data.needs_commit)
          {
            meta.attr = 'style="font-weight: bold;"';
          }
          
          return v;
        }
      },{
        id: 'delete',
        header: '',
        dataIndex: 'id',
        width: 26,
        fixed: true,
        renderer: function(v){
          return '<?php echo image_tag('bin.gif') ?>';
        }
      }
    ],
    listeners: {
      cellclick: function(grid, rowIndex, columnIndex, e){
        
        var columnId = grid.getColumnModel().getColumnId(columnIndex);
        
        /* Delete clicked */
        if (columnId == 'delete')
        {
          <?php echo ext_log('delete clicked') ?>
          var record = grid.getStore().getAt(rowIndex);
          grid.store.remove(record);
        }
      }
    },
    viewConfig:{
      forceFit: true,
      deferEmptyText: true,
      emptyText: 'No records to display'
    },
    bbar: [
      {
        xtype: 'button',
        text: 'Add record',
        iconCls: 'icon-add',
        handler: function(){
          grid.store.add(new grid.store.recordType({
            name: cfg.defaultName,
            type: 'A',
            ttl: <?php echo sfConfig::get('app_default_ttl') ?>
          }));
          
          grid.getView().focusRow(grid.store.getCount() - 1);
        }
      }
    ]
  };
  
  Ext.applyIf(cfg, defaultCfg);
  
  var grid = new Ext.grid.EditorGridPanel(cfg);
  
  return grid;
}
