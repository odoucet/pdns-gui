/* Multi Window */
Ext.ux.Window = function(cfg){

  if (!cfg) var cfg = {};
  
  var width = 300;
  
  if (cfg.items instanceof Array)
  {
    if (cfg.items[0].width) width = cfg.items[0].width;
  }
  else
  {
    if (cfg.items.width) width = cfg.items.width;
  }
  
  var defaultCfg = {
    layout:'fit',
    width: width + 10,
    resizable: false,
    plain: true,
    buttonAlign: 'center',
    listeners: {
      render: function(win){
        var map = new Ext.KeyMap(win.getEl(), {
            key: 13, // or Ext.EventObject.ENTER
            fn: function(key,e){
              
              var target = Ext.get(e.getTarget());
              
              if (target.dom.type == 'textarea')
              {
                return false;
              }
              
              if ('doSubmit' in win)
              {
                win.doSubmit();
              }
            },
            scope: win
        });
      },
      beforeShow: function() {
        if ((this.x == undefined) && (this.y == undefined))
        {
          
          this.y = 40;
          
          var vp_width = viewport.getSize()['width'];
          var win_width = this.getSize()['width'];
          
          this.x = (vp_width - win_width) / 2;
          
          var prev;
          this.manager.each(function(w) {
              if (w == this) {
                  if (prev) {
                    var o = 20;
                      var p = prev.getPosition();
                      this.x = p[0] + o;
                      this.y = p[1] + o;
                  }
                  return false;
              }
              if (w.isVisible()) prev = w;
          }, this);
        }
      }
    }
  };
  
  if (cfg.helpTopic)
  {
    defaultCfg.tools = [{
      id: 'help',
      handler: function(){
        getHelp(cfg.helpTopic);
      }
    }];
  }
  
  Ext.applyIf(cfg, defaultCfg);
  
  return new Ext.Window(cfg);
}

/**
 * Helper function which checks if window is already open
 */
function get_win_id(object_id)
{
  if (!object_id) object_id = '';
  else if (typeof object_id.id != 'undefined') object_id = object_id.id;
  
  var match = /function (.*)\(/i.exec(get_win_id.caller.toString());
  
  var win_id = match[1] + object_id;
  
  // check if not already open
  var win = Ext.WindowMgr.get(win_id);
  if (win)
  {
    Ext.fly(win.getEl()).frame("ff0000");
    win.toFront();
    return false;
  }
  
  return win_id;
}
