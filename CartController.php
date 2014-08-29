<?php

class CartController extends Controller
{
	public function actionIndex()
	{
          if(Cart::GetTotalSumCart() > 0){
            if(isset($_POST) && $_POST['recount'] == '1'){
            unset($_POST['recount']);
            foreach ($_POST as $key => $value) {
               if(is_numeric($value)){
               $product = Product::model()->findByPk($key);
               Yii::app()->shoppingCart->put($product);
               Yii::app()->shoppingCart->update($product, $value);
              }
            }
          }
          
          
           $update = new Customers;
           $model = Yii::app()->shoppingCart->getPositions();
           
             if(isset($_POST['Customers']) && $_POST['create'] == '1')
             {
			$update->attributes=$_POST['Customers'];
			 if($update->save()){
                              $orders = new Orders;
                              $orderSave['customer_id'] = $update -> id;
                              $orderSave['total_price'] = Cart::GetTotalSumCart();
                              $orders->attributes = $orderSave;
                            if($orders->save()){
                              foreach($model as $position){
                                $oredrsItemsSave['orders_id'] = $orders -> id;
                                $oredrsItemsSave['product_id'] = $position['id'];
                                $oredrsItemsSave['quantity'] = $position['quantity'];
                                $OrdersItem = new OrdersItems;
                                $OrdersItem->attributes =  $oredrsItemsSave;
                                $OrdersItem->save();
                               }
                               if($OrdersItem->save()){
                                   $total = Cart::GetTotalSumCart();
                                   Yii::app()->shoppingCart->clear();
                                   Yii::app()->user->setFlash('finish', 'Спасибо! Ваш заказ успешно принят. Наш менеджер свяжется с вами в ближайшее время.');
                                   $this->render('view', array('model' => $model, 'update' => $update,  'total' => $total));
                                   exit;
                               }
                            }
                         }
             }
            
            $this->render('index', array('model' => $model, 'update' => $update));
          }
         else{
          $this->render('empty'); 
          }
       }
        

        

       public function actionaddCart($id)
       {
            
            $model = Product::model()->findByPk($id);
            Yii::app()->shoppingCart[] = $model; 
            Yii::app()->user->setFlash('addCart', "$model->title стоимостю $model->price грн. добавлен в  корзину.");
            $this->redirect(Yii::app()->request->urlReferrer);
       }
        
        public function actionremove($id)
       {
           $model = Product::model()->findByPk($id);
           Yii::app()->shoppingCart->remove($model->getId()); 
           $this->redirect(Yii::app()->request->urlReferrer); 
       }
}
