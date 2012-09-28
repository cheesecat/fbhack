<?php
/* @var $this HelpController */
/* @var $model Help */

$this->breadcrumbs=array(
	'Helps'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Help', 'url'=>array('index')),
	array('label'=>'Manage Help', 'url'=>array('admin')),
);
?>

<h1>Create Help</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>