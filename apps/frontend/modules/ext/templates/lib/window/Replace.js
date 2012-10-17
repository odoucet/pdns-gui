function ReplaceWindow()
{
  var win_id = get_win_id();
  if (!win_id) return;
  
  var form = new Ext.form.FormPanel({
    url: '<?php echo url_for('domain/replace') ?>',
    height: 210,
    labelWidth: 60,
    border: false,
    bodyStyle: 'padding: 10px;',
    items: [
      {
        xtype: 'fieldset',
        title: 'Search for...',
        defaults: { allowBlank: false },
        items: [
          new Ext.ux.TypeCombo({
            name: 'search_type',
            fieldLabel: 'Type',
            hiddenName: 'search_type'
          }),
          {
            xtype: 'textfield',
            fieldLabel: 'Content',
            name: 'search_content'
          }
        ]
      },{
        xtype: 'fieldset',
        title: 'Replace with...',
        defaults: { allowBlank: false },
        items: [
          new Ext.ux.TypeCombo({
            name: 'replace_type',
            fieldLabel: 'Type',
            hiddenName: 'replace_type'
          }),
          {
            xtype: 'textfield',
            fieldLabel: 'Content',
            name: 'replace_content'
          }
        ]
      }
    ]
  });
  
  var win = new Ext.ux.Window({
    id: win_id,
    title: 'Search and replace',
    width: 300,
    doSubmit: function(){
      form.form.submit({
        success: function(form,action){
          win.close();
          
          Ext.Msg.minWidth = 360;
          
          Ext.Msg.alert('Info',action.result.info);
          
          DomainStore.load();
        }
      });
    },
    items: form,
    buttons: [
      {
        text: 'Submit',
        handler: function() { win.doSubmit() }
      },{
        text: 'Close',
        handler: function() { win.close() }
      }
    ]
  });
  
  win.show();
}
