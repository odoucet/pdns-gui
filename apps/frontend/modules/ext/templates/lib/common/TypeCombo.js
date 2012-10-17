/* Type Combo */
Ext.ux.TypeCombo = function(cfg){

  if (!cfg) var cfg = {};

  var defaultCfg = {
    store: RecordTypeStoreActive,
    displayField: 'id',
    valueField: 'id',
    width: 120,
    name: 'type',
    hiddenName: 'type',
    mode: 'local',
    triggerAction: 'all',
    forceSelection: true,
    editable: false,
    emptyText: 'Select...'
  };

  Ext.applyIf(cfg, defaultCfg);
  
  return new Ext.form.ComboBox(cfg);
}
