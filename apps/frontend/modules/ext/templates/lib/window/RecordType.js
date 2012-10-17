function RecordTypeWindow()
{
  var win_id = get_win_id();
  if (!win_id) return;
  
  var form = new Ext.form.FormPanel({
    bodyStyle: 'padding: 10px;',
    autoScroll: true,
    border: false,
    height: 400,
    labelWidth: 60,
    url: '<?php echo url_for('template/recordtype') ?>',
    items: [
      <?php foreach (sfConfig::get('app_record_type',array()) as $type => $description) : ?>
        {
          xtype: 'xcheckbox',
          fieldLabel: '<?php echo $type ?>',
          boxLabel: "<?php echo $description ?>",
          name: 'record_type[<?php echo $type ?>]',
          height: <?php echo ($type == 'SOA') ? 80 : 40 ?>
        },
      <?php endforeach ?>
    ]
  });
  
  Ext.each(form.find('xtype','xcheckbox'),function(field){
    
    var r = RecordTypeStore.getById(field.fieldLabel);

    field.checked = r.data.state;
  });
  
  var win = new Ext.ux.Window({
    id: win_id,
    title: 'Record types',
    width: 700,
    doSubmit: function(){
      form.form.submit({
        success: function(form,action){
          win.close();
          
          RecordTypeStore.load();
        }
      });
    },
    items: form,
    buttons: [
      {
        text: 'Update',
        handler: function() { win.doSubmit() }
      },{
        text: 'Close',
        handler: function() { win.close() }
      }
    ]
  });
  
  win.show();
}
