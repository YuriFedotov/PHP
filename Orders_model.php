<?php


class Orders extends CActiveRecord
{
	public $title;
        public $color;
        public $size;
        public $price;
        public $quantity;
               
                
	public function tableName()
	{
		return '{{orders}}';
	}


	public function rules()
	{
	
		return array(
			array('customer_id,total_price,created', 'required'),
			array('created', 'numerical', 'integerOnly'=>true),
			array('total_price', 'numerical'),
			array('customer_id', 'length', 'max'=>11),
			
			array('id, customer_id, total_price, created', 'safe', 'on'=>'search'),
		);
	}


	public function relations()
	{
		
		return array(
                     'customers' => array(self::BELONGS_TO, 'Customers','customer_id'),
                   
		);
	}


	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'customer_id' => 'Клиент',
			'total_price' => 'Общая сумма заказа',
			'created' => 'Создано',
		);
	}

	public function search()
	{


		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('customer_id',$this->customer_id,true);
		$criteria->compare('total_price',$this->total_price);
		$criteria->compare('created',$this->created);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
                        'pagination'=>array("pageSize"=>15), // вывод по 15 строк
		));
	}


	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
        
        
      public static function getDetals($id)
	{
		
            $model = Orders::model()->findAllBySql(
            "SELECT p.id, title, color, size, price, oi.quantity AS quantity
            FROM cms_product p
            JOIN cms_orders_items oi ON p.id = oi.product_id
            WHERE oi.orders_id =  '$id'
            ORDER BY p.price DESC "
            );
            return $model;
	}
              
}
