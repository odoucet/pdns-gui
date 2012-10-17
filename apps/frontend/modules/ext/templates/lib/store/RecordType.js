var RecordTypeStoreActive = new Ext.data.JsonStore({
  id: 'id',
  fields: [ 'id','state' ]
});

var RecordTypeStore = new Ext.data.JsonStore({
  url: '<?php echo url_for('template/recordtype') ?>',
  listeners: {
    load: function(store){
      
      RecordTypeStoreActive.removeAll();
      
      store.each(function(r){
        
        if (r.data.state == 1)
        {
          RecordTypeStoreActive.add(r);
        }
        
      });
    }
  }
});
