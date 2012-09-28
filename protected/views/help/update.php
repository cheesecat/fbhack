<?php
/* @var $this HelpController */
/* @var $model Help */

$this->breadcrumbs=array(
	'Helps'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Help', 'url'=>array('index')),
	array('label'=>'Create Help', 'url'=>array('create')),
	array('label'=>'View Help', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Help', 'url'=>array('admin')),
);
?>

<h1>Update Help <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>