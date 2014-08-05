<?php

$this->menu=array(
	array('label'=>'Просмотр заказов', 'url'=>array('index')),
	array('label'=>'Обновить заказ', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Удалить заказ', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	
);
?>

<h1>Подробный просмотр заказа #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
        'id',
    
             'customer_id' => array(
             'name' => 'customer_id',
             'value' => $model->customers->name, 
             ),   
            
            
             'customer_region' => array(
            'name' => 'Область, город',
            'value' => $model->customers->address, 
            'customer_address' => array(
            'name' => 'улица, дом/квартира',
            'value' => $model->customers->city, 
             ),
            'customer_tel' => array(
            'name' => 'телефон',
            'value' => $model->customers->tel,
             ),
            'email' => array(
            'name' => 'Электронный ящик',
            'value' =>$model->customers->email,
             ),
            
            
  
		'total_price',
		'created' => array(
                'name' => 'created',
                'value' => date("j.m.Y H:i", $model->created),
                
                ),
	),
)); 


?>

<h3>Заказаные товары</h3>

<?php 
$num = 1;
foreach ($detals as $model){
    ?>

<h6>Товар номер <?php echo $num; ?></h6>

<?php
$this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
                     'name' => array(
            'name' => 'Название товара',
            'value' => $model->title, 
             ),
                     'size' => array(
            'name' => 'Размер',
            'value' => $model->size, 
             ),
                     'price' => array(
            'name' => 'Цена',
            'value' => $model->price, 
             ),
                     'quantity' => array(
            'name' => 'Количество',
            'value' => $model->quantity,
             ),
                     'color' => array(
            'name' => 'Цвет',
            'value' => $model->color, 
             ),
           
	   ),
        )
     );      
$num++;
}

