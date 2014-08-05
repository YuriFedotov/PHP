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
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
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
		// @todo Please modify the following code to remove attributes that should not be searched.

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
            "SELECT cms_product.id, title, color, size, price,
            COUNT(cms_orders_items.quantity) AS quantity
            FROM cms_product
            JOIN cms_orders_items ON cms_product.id = cms_orders_items.product_id
            WHERE cms_orders_items.orders_id = '$id'
            GROUP BY cms_product.id
            ORDER BY cms_product.price DESC"
                    );
            
            return $model;
	}
              
}
