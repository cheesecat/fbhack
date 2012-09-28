<?php
/* @var $this HelpController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Helps',
);

$this->menu=array(
	array('label'=>'Create Help', 'url'=>array('create')),
	array('label'=>'Manage Help', 'url'=>array('admin')),
);
?>

<h1>Helps</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
