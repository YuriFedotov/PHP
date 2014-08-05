<?php

class UserController extends Controller
{
	

	
	public function filters()
	{
		return array(
			'accessControl', 
		
		);
	}

	
	public function accessRules() 
	{
		
	
			return array(
				
			array('allow',  
				'actions'=>array('view', 'index', 'password', 'update', 'delete'),
				'roles'=>array('2'),
			),
			array('deny',  
				'users'=>array('*'),
			),
		);
	
	}


	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}
	
	public function actionPassword($id)
	{
            $model = $this->loadModel($id);
            
            if(isset($_POST['password'])){
            $model->password = $_POST['password'];
           
             
            if($model->save()){
		$this->redirect(array('view', 'id' => $model->id));
            }
            }
            
            $this->render('password');
        }

	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);



		if(isset($_POST['User']))
		{
			$model->attributes=$_POST['User'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}


	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

	
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
	}


	
	public function actionIndex()
	{
                if(isset($_POST['noban'])){ 
                    $model = User::model()->updateByPk($_POST['User_id'], array('ban' => 1));
                }
                else if(isset($_POST['ban'])){ 
                    $model = User::model()->updateByPk($_POST['User_id'], array('ban' => 0));
                }
                
                if(isset($_POST['admin'])){
                    $model = User::model()->updateByPk($_POST['User_id'], array('role' => 2));
                }
                else if(isset($_POST['noadmin'])){ 
                    $model = User::model()->updateByPk($_POST['User_id'], array('role' => 1), array('condition' => 'id<>'.Yii::app()->user->id)); // для того чтобы пользователь не мог сам себе поменять роль.
                }
                
           
		$model=new User('search');
		$model->unsetAttributes(); 
		if(isset($_GET['User']))
			$model->attributes=$_GET['User'];

		$this->render('index',array(
			'model'=>$model,
		));
	}

	public function loadModel($id)
	{
		$model=User::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'Ошибка!.');
		return $model;
	}

	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='user-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
