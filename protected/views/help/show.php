<?php

/** @var $this HelpController */
/** @var $model Help */

?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta property="og:title" content="help" />
    <meta property="og:type" content="drunk-help:pomoc" />
    <meta property="og:description" content="Potrzebuje pomocy" />
    <meta property="fb:app_id" content="161728350617606" />
    <meta property="og:url" content="<?php echo($this->createAbsoluteUrl('help/show', array('id'=>$model->id))); ?>" />
    <meta property="og:image" content="<?php echo $model->image ? ('http://hack.ccat.pl/images/' . $model->id . '_' . $model->image) : $this->createAbsoluteUrl('/images/payu.jpg');?>" />
    <meta property="drunk-help:location:latitude"  content="<?php echo $model->lat; ?>">
    <meta property="drunk-help:location:longitude" content="<?php echo $model->long; ?>">
    <title></title>
</head>
<body>

</body>
</html>
