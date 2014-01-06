function TemplateWindow()
{
  var win_id = get_win_id();
  if (!win_id) return;
  
  var Tabs = new Ext.TabPanel({
    activeTab: 0,
    border: false,
    enableTabScroll: true,
    plain: true,
    height: 358
  });
  
  if (TemplateStore.getCount() == 0)
  {
    Tabs.add(emptyTemplate('Default'));
  }
  else
  {
    TemplateStore.each(function(r){
      Tabs.add(existingTemplate(r.data));
    });
  }
  
  Tabs.doLayout();
  
  var win = new Ext.ux.Window({
    id: win_id,
    title: 'Templates',
    width: 450,
    resizable: true,
    items: Tabs,
    doSubmit: function(){
      
      var form_count = 0;
      
      var errors = '';
      
      Ext.each(Tabs.items.items,function(tab)
      {
        
        Ext.each(tab.items.items[0], function(form)
        {
          // remove all hidden fields
          Ext.each(form.find('xtype','hidden'),function(hidden){
            if (hidden.name != 'id')
            {
              form.remove(hidden);
            }
          });
          
          form.doLayout();
          
          var grid = tab.items.items[1].items.items[0];
          
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
              
              form_count++;
              
              if (form_count == Tabs.items.items.length)
              {
                win.close();
                
                Ext.Msg.alert('Info',action.result.info);
                
                TemplateStore.load();
              }
            },
            failure: function(form, action){
              
              form_count++;
              
              if (form.url.match(/edit$/))
              {
                var template_name = form.items.items[1].getValue();
              }
              else
              {
                var template_name = form.items.items[0].getValue();
              }
              
              errors+= 'Template: <b>' + template_name + '</b><br/>';
              errors+= action.result.errors.record + '<br/>';
              
              if (form_count == Tabs.items.items.length)
              {
                Ext.Msg.alert('Error',errors);
              }
            }
          });
        });
      
      });
      
    },
    buttons: [
      {
        text: 'Add template',
        iconCls: 'icon-add',
        style: 'margin-right: 150px;',
        handler: function() {
          var grid = emptyTemplate('Default ' + Tabs.items.length);
          Tabs.add(grid);
          Tabs.activate(grid);
        }
      },{
        text: 'Save',
        handler: function() { win.doSubmit() }
      },{
        text: 'Close',
        handler: function() { win.close() }
      }
    ]
  });
  
  win.show();
  win.addListener('resize',function(win,width,height){
    Tabs.items.items[0].items.items[1].items.items[0].setHeight(height-170);
  });
}

function emptyTemplate(name)
{ 
  var form = new Ext.form.FormPanel({
    url: '<?php echo url_for('template/add') ?>',
    border: false,
    labelWidth: 60,
    bodyStyle: 'padding: 10px;',
    items: [
      {
        xtype: 'textfield',
        fieldLabel: 'Name',
        name: 'name',
        allowBlank: false,
        value: name
      },{
        xtype: 'combo',
        store: [["NATIVE","Native"],["MASTER","Master"],["SLAVE","Slave"]],
        fieldLabel: 'Type',
        displayField: 'field2',
        valueField: 'field1',
        width: 120,
        name: 'type',
        hiddenName: 'type',
        mode: 'local',
        triggerAction: 'all',
        forceSelection: true,
        editable: false,
        allowBlank: false,
        value: 'MASTER'
      }
    ]
  });
  
  var template = new Ext.Panel({
    title: name,
    border: false,
    items: [
      form,
      {
        layout: 'fit',
        border: false,
        items: new Ext.ux.RecordsGrid({
          defaultName: '%DOMAIN%',
          template: true
        })
      }
    ]
  });
  
  return template;
}


function existingTemplate(template)
{ 
  var form = new Ext.form.FormPanel({
    url: '<?php echo url_for('template/edit') ?>',
    border: false,
    labelWidth: 60,
    bodyStyle: 'padding: 10px;',
    items: [
      {
        xtype: 'hidden',
        name: 'id',
        value: template.id
      },{
        xtype: 'textfield',
        fieldLabel: 'Name',
        name: 'name',
        allowBlank: false,
        value: template.name
      },{
        xtype: 'combo',
        store: [["NATIVE","Native"],["MASTER","Master"],["SLAVE","Slave"]],
        fieldLabel: 'Type',
        displayField: 'field2',
        valueField: 'field1',
        width: 120,
        name: 'type',
        hiddenName: 'type',
        mode: 'local',
        triggerAction: 'all',
        forceSelection: true,
        editable: false,
        allowBlank: false,
        value: template.type
      }
    ]
  });
  
  var template = new Ext.Panel({
    title: template.name,
    border: false,
    items: [
      form,
      {
        layout: 'fit',
        border: false,
        items: new Ext.ux.RecordsGrid({
          records: template.records, 
          defaultName: '%DOMAIN%', 
          border: false,
          template: true
        })
      }
    ]
  });
  
  return template;
}

