
function removeLoadingMask(){
  
  checkIE();
  
  checkFirebug();
  
  setTimeout(function(){
    
    Ext.get('loading').remove();
    Ext.get('loading-mask').fadeOut({remove:true});
    
  }, 100);

}

function loadStores(){
  <?php echo ext_log('Loading stores...') ?>
  
  var emergency = function(){
    
    removeLoadingMask();
    
    Ext.Msg.alert('Error', 'Application failed to initialized correctly. Please reload in a few moments, and if this message apears again, contact our support team.');
    
    Ext.Ajax.request({
      url: '<?php echo url_for('ext/error') ?>',
      params: { error: 'Stores failed to load in 7 seconds.' }
    });

  }
  
  var t = emergency.defer(7000);
  
  Ext.get('loading-msg').update('Loading Stores');
  
  var storesCount = 0;
  
  var stores = [
    'DomainStore',
    'TemplateStore',
    'RecordTypeStore'
  ];
  
  Ext.each(stores,function(store){
    eval(store + ".on('load',function(){ storesCount++; <?php if (SF_ENVIRONMENT == 'dev') : ?> if (window.console) console.log('++"+store+" loaded. Count: ' + storesCount); <?php endif ?> Ext.get('loading-msg').update('Loading "+store+"'); if (storesCount == stores.length){ clearTimeout(t); removeLoadingMask(); } }," + store + ",{single: true});" + store + ".load();");
    
  });
  
};

function checkIE()
{
  if (Ext.isIE)
  {
    messages.add({
      html: 'Warning: Internet Explorer has known performance issues when used with PowerDNS GUI.  Mozilla Firefox or Apple Safari are recommended. <a href="#" class="hide">[ hide ]</a>',
      listeners: {
        render: function(panel){
          panel.body.on('click',function(e){
            var targetEl = Ext.get(e.getTarget());
            
            if (targetEl.hasClass('hide'))
            {
              messages.remove(panel);
              messages.doLayout();
            }
          });
        }
      }
    });
    
    messages.doLayout();
  }
}

function checkFirebug() {
  if(window.console && window.console.firebug)
  {
    messages.add({
      html: 'Warning: Firebug is known to cause performance issues with PowerDNS GUI.'
    });
    
    messages.doLayout();
  }
}
