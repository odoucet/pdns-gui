var Start = new Ext.Panel({
  bodyStyle: 'padding: 20px;',
  border: false,
  html: 'Click on the domain name in the left panel to start...'
});


var Tabs = new Ext.TabPanel({
  deferredRender: false,
  enableTabScroll:true,
  layoutOnTabChange: true,
  defaults: { autoScroll:true, hideMode: 'offsets' },
  plain: true
});
