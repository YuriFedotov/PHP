<?php


class Category extends CActiveRecord
{

	public function tableName()
	{
		return '{{Category}}';
	}

public function rules()
	{
	
		return array(
                        array('title, position', 'required'),
			array('title', 'length', 'max'=>255),
			array('position', 'length', 'max'=>8),
			array('id, title, position', 'safe', 'on'=>'search'),
		);
	}


	public function relations()
	{
	
		return array(
		);
	}


	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'title' => 'Заголовок',
			'position' => 'Позиция',
		);
	}

	public function search()
	{
	
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('position',$this->position,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
                     'pagination'=>array("pageSize"=>5), // вывод по 15 строк
		));
	}

	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
        
               public static function all()
        {
          // $models = self::model()->findAll();
          // $array = array();
          // foreach ($models as $one){
          //     $array[$one->id] = $one->title;
          //  }
          // return $array;
         
            return CHtml::listData(self::model()->findAll(), 'id', 'title');
        }
        
        public static function menu($position)
        {
         $models = self::model() -> findAllByAttributes(array('position' => $position));
         $array = array();
          if($position == 'top'){
             $array[] = array('label'=>'Главная', 'url'=>array('/site/index'));
          }
               
         foreach($models as $one){
                    $array[] = array('label' => $one->title, 'url' => array('/product/index/id/'.$one->id));
         }
         
         if($position == 'top'){
            $array[] = array('label'=>'О нас', 'url'=>array('/site/page', 'view'=>'about'));
            $array[] = array('label'=>'Контакты', 'url'=>array('/site/contact'));
            $array[] = array('label'=>'Вход', 'url'=>array('/site/login'), 'visible'=>Yii::app()->user->isGuest);
            $array[] = array('label'=>'Выход ('.Yii::app()->user->name.')', 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest);
            $array[] = array('label'=>'Регистрация', 'url'=>array('/site/registration'), 'visible'=>Yii::app()->user->isGuest); //если пользователь гость то ссылка нам видна 
	   if(Yii::app()->user->checkAccess('2')){ 
                $array[] = array('label' => 'Админка', 'url' => array('/admin'));
         }
       }
          return $array;
        }
}
