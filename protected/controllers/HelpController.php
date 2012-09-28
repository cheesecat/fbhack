<?php

class HelpController extends Controller
{

    /**
     * @var Fbuser
     */
    protected $fb_user;
    /**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}


    /**
     * @var Help
     */
    protected $help = null;
	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view', 'show', 'notify'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionShow($id)
    {
        $this->layout ='fb';
        $this->render('show',array(
            'model'=>$this->loadModel($id),
        ));
    }

    public function actionNotify($id)
    {
        $this->fb_user = Fbuser::model()->findByPk($id);
        /** @var $fbuser Fbuser */
        $fbuser = $this->fb_user;
        if (!$fbuser) {
            throw new CHttpException('Bad user', 403);
        }
        $json = false;
        if (!isset($_POST['Help']) && isset($_POST['data'])) {
            $data = json_decode($_POST['data']);
            $_POST = $data;
            $json = true;
        }

        $help = new Help();
        $help->user_id = $fbuser->id;
        $help->lat = trim(@$_POST['Help']['lat']);
        $help->long = trim(@$_POST['Help']['long']);


        if (! $help->save() ) {
            var_dump($help->errors);
            throw new CHttpException('Bad data');
        }
        $this->help = $help;
        $uf = CUploadedFile::getInstance($help, 'image');
        if (!$uf && empty($_POST['image'])) {
            $this->notifyReal();
            return;
        }
        if (!$json) {
            $filename = $help->id . '_' . $uf->name;
            $uf->saveAs(dirname(__FILE__) . '/../../images/' . $filename);
            $help->image = $filename;
        } else {
            $filename = $help->id . '_' . uniqid() . '.jpg';
            file_put_contents(dirname(__FILE__) . '/../../images/' . $filename ,$_POST['image']);
            $help->image = $filename;
        }
        $help->save();
        $this->notifyReal();
    }

    protected function notifyReal()
    {
        $user_friends = Friend::model()->findAll('user_id = :user_id', array('user_id' => $this->fb_user->id));

        require dirname(__FILE__) . '/../extensions/facebook/sdk/Facebook.php';
        try {
            $f = new Facebook(Yii::app()->params['fb']);
            $params = array(
                'pomoc' => 'http://hack.ccat.pl/index.php/help/show/' . $this->help->id,
                'access_token' => $this->fb_user->access_token,
            );
            $f->api('/' . $this->fb_user->facebook_id . '/drunk-help:request', 'POST', $params);
        } catch (Exception $e) {
        }


        require dirname(__FILE__) . '/../extensions/places/places.php';
        $location = get_location($this->help->lat, $this->help->long, $this->fb_user->access_token);

        require dirname(__FILE__) . '/../extensions/twilio/call-request.php';

        $fl = array();
        foreach($user_friends as $friend) {
            /** @var $friend Friend */
            $fl[] = $friend->phone;
        }
        $name = array('name' => $this->fb_user->firstname, 'surname' => $this->fb_user->lastname);

        notify_by_twilio($fl, $name, $location);


        die('1');
    }

    /**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Help;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Help']))
		{
			$model->attributes=$_POST['Help'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Help']))
		{
			$model->attributes=$_POST['Help'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Help');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Help('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Help']))
			$model->attributes=$_GET['Help'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Help::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='help-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
