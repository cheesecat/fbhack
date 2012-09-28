<?php
/* @var $this HelpController */
/* @var $model Help */

$this->breadcrumbs=array(
	'Helps'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Help', 'url'=>array('index')),
	array('label'=>'Create Help', 'url'=>array('create')),
	array('label'=>'Update Help', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Help', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Help', 'url'=>array('admin')),
);
?>

<h1>View Help #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'lat',
		'long',
		'place',
		'image',
	),
)); ?>
