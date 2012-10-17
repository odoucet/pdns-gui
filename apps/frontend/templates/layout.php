<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>

<style type="text/css">
#loading-mask{
  position:absolute;
  left:0;
  top:0;
  width:100%;
  height:100%;
  z-index:20000;
  background-color:white;
}
#loading{
  position:absolute;
  left:45%;
  top:40%;
  padding:2px;
  z-index:20001;
  height:auto;
}

#loading .loading-indicator{
  width: 300px;
  background:white;
  color:#444;
  font:bold 13px tahoma,arial,helvetica;
  padding:10px;
  margin:0;
  height:auto;
}
#loading-msg {
  font: normal 10px arial,tahoma,sans-serif;
}
</style>
<?php include_http_metas() ?>
<?php include_metas() ?>

<?php include_stylesheets() ?>

<?php include_javascripts() ?>

<?php include_title() ?>

<link rel="shortcut icon" href="/favicon.ico" />

</head>
<body>

<?php echo $sf_data->getRaw('sf_content') ?>

</body>
</html>
