function DomainWindow(domain)
{
  var win_id = get_win_id(domain);
  if (!win_id) return;
  
  var form = new Ext.form.FormPanel({
    height: 260,
    border: false,
    url: '<?php echo url_for('domain/edit') ?>',
    defaults: { allowBlank: false },
    items: [
      {
        xtype: 'hidden',
        name: 'id',
        value: domain.id
      },{
        layout: 'fit',
        border: false,
        items: new Ext.ux.RecordsGrid({
          defaultName: domain.name,
          border: false,
          domain_id: domain.id
        })
      }
    ]
  });
  
  var win = new Ext.ux.Window({
    id: win_id,
    title: domain.name + ' ('+domain.type+')',
    width: 450,
    resizable: true,
    items: form,
    doSubmit: function(){
      // remove all hidden fields
      Ext.each(form.find('xtype','hidden'),function(hidden){
        if (hidden.name != 'id')
        {
          form.remove(hidden);
        }
      });
      
      form.doLayout();
      
      var grid = form.items.items[form.items.items.length-1].items.items[0];
      
      var i = 0;
      grid.store.each(function(r){
        
        form.add({
          xtype: 'hidden',
          name: 'record['+i+'][id]',
          value: r.data.id
        });
        
        form.add({
          xtype: 'hidden',
          name: 'record['+i+'][name]',
          value: r.data.name
        });
        
        form.add({
          xtype: 'hidden',
          name: 'record['+i+'][type]',
          value: r.data.type
        });
        
        form.add({
          xtype: 'hidden',
          name: 'record['+i+'][content]',
          value: r.data.content
        });
        
        form.add({
          xtype: 'hidden',
          name: 'record['+i+'][ttl]',
          value: r.data.ttl
        });
        
        form.add({
          xtype: 'hidden',
          name: 'record['+i+'][prio]',
          value: r.data.prio
        });
        
        i++;
      });
      
      form.doLayout();
      
      form.form.submit({
        success: function(form, action){
          
          win.close();
          
          DomainStore.load();
        },
        failure: function(form, action){
          
          Ext.Msg.alert('Error',action.result.errors.record);
        }
      });
      
    },
    buttons: [
      {
        text: 'Save',
        handler: function() { win.doSubmit() }
      },{
        text: 'Close',
        handler: function() { win.close() }
      }
    ],
    tools: [{
      id: 'gear',
      handler: function(e,el){
        var menu = new Ext.menu.Menu({
          items: [
            {
              text: 'Delete',
              iconCls: 'icon-bin',
              handler: function(){
                
                // Show a dialog using config options:
                Ext.Msg.show({
                  title:'Confirm',
                  closable: false,
                  msg: 'Are you sure you want to delete this domain.',
                  buttons: Ext.Msg.YESNO,
                  icon: Ext.MessageBox.QUESTION,
                  fn: function(button){
                    <?php echo ext_log("'Pressed button: ' + button") ?>
                    if (button == 'yes')
                    {
                      Ext.Ajax.request({
                        url: '<?php echo url_for('domain/delete') ?>',
                        success: function(r){
                          var response = Ext.decode(r.responseText);
                          
                          if (response.success)
                          {
                            win.close()
                            DomainStore.reload();
                          }
                          else
                          {
                            Ext.Msg.alert('Error',"Operation failed");
                          }
                        },
                        failure: function(){
                          Ext.Msg.alert('Error','Request failed.');
                        },
                        params: { id: domain.id }
                      });
                    }
                  }
                });
                
              }
            }
          ]
        });
        
        menu.showAt(e.xy);
      }
    }]
  });
  
  win.show();
  
  win.addListener('resize',function(win,width,height){
    form.items.items[1].items.items[0].setHeight(height-72);
  });
  
  
}
