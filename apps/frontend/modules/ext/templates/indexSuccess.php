<h1>PowerDNS GUI</h1>

<p>
Web based GUI which aids in administering domains and records for the PowerDNS name server software with MySQL backend.
</p>

<p>Copyright &copy; 2009 - <?php echo link_to('Level 7 Systems Ltd.','http://level7systems.co.uk') ?></p>
<p>Forked and patched by O. Doucet on <?php echo link_to('GitHub.', 'https://github.com/odoucet/pdns-gui') ?></p>

<?php if (SF_ENVIRONMENT == 'dev') : ?>
  <?php echo javascript_include_tag('/frontend_dev.php/js/ext/loading.pjs') ?>
<?php else : ?>
  <?php echo javascript_include_tag('/js/ext/loading.pjs') ?>
<?php endif ?>

<script type="text/javascript">document.getElementById('loading-msg').innerHTML = 'Loading CSS';</script>

<?php echo stylesheet_tag('/extjs/lib/resources/css/ext-all') ?>

<?php echo stylesheet_tag('Spinner') ?>

<script type="text/javascript">document.getElementById('loading-msg').innerHTML = 'Loading Ext';</script>

<?php echo javascript_include_tag('/extjs/lib/adapter/ext/ext-base.js') ?>

<?php if (SF_ENVIRONMENT == 'dev') : ?>
  <?php echo javascript_include_tag('/extjs/lib/ext-all-debug.js') ?>
<?php else : ?>
  <?php echo javascript_include_tag('/extjs/lib/ext-all.js') ?>
<?php endif ?>

<script type="text/javascript">document.getElementById('loading-msg').innerHTML = 'Loading UI';</script>

<?php if (SF_ENVIRONMENT == 'dev') : ?>
  <?php echo javascript_include_tag('/frontend_dev.php/js/ext/application.pjs') ?>
<?php else : ?>
  <?php echo javascript_include_tag('/js/ext/application.pjs') ?>
<?php endif ?>

<noscript>

  <div id="no-js">
 
  <p>JavaScript must be enabled in order for you to use this system 
  in standard view. However, it seems JavaScript is either disabled or not 
  supported by your browser. To use standard view, enable JavaScript by 
  changing your browser options, then 
  <?php echo link_to('try again.','ext/index') ?></p>
  
  <br/><br/>
  
  </div>
  
</noscript>

