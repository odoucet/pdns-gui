
<?php echo ext_log('Loading Viewport') ?>

/* Viewport */
viewport = new Ext.Viewport({
  layout: 'border',
  style:  'background: #FFFFFF;',
  items:[
    NorthRegion,
    {
      region:   'west',
      layout:   'fit',
      defaults: { border: true },
      margins:  '0 5 0 5',
      width:    200,
      border:   false,
      items: West
    },{
      region: 'center',
      border: false,
      margins: "0 5 0 0",
      bodyStyle: 'padding: 20px;',
      items: Start
    },
    SouthRegion
  ],
  listeners: {
    render: function(){
      loadStores();
    }
  }
});
