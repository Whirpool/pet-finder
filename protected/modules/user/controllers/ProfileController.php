<?php

class ProfileController extends Controller
{
	public $defaultAction = 'profile';
	public $layout='//layouts/column2';

	/**
	 * @var CActiveRecord the currently loaded data model instance.
	 */
	private $_model;
	/**
	 * Shows a particular model.
	 */
	public function actionProfile()
	{
		$model = $this->loadUser();
	    $this->render('profile',array(
	    	'model'=>$model,
			'profile'=>$model->profile,
	    ));
	}


	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionEdit()
	{
		$model = $this->loadUser();
		$profile=$model->profile;
        Yii::import("xupload.models.XUploadForm");
        $photos = new XUploadForm;
		
		// ajax validator
		if(isset($_POST['ajax']) && $_POST['ajax']==='profile-form')
		{
			echo UActiveForm::validate(array($model,$profile));
			Yii::app()->end();
		}
		
		if(isset($_POST['User']))
		{
			$model->attributes=$_POST['User'];
			$profile->attributes=$_POST['Profile'];
			
			if($model->validate()&&$profile->validate()) {
				$model->save();
				$profile->save();
				Yii::app()->user->setFlash('profileMessage',UserModule::t("Changes is saved."));
				$this->redirect(array('/user/profile'));
			} else $profile->validate();
		}

		$this->render('edit',array(
			'model'=>$model,
			'profile'=>$profile,
            'photos' => $photos,
		));
	}
	
	/**
	 * Change password
	 */
	public function actionChangepassword() {
		$model = new UserChangePassword;
		if (Yii::app()->user->id) {
			
			// ajax validator
			if(isset($_POST['ajax']) && $_POST['ajax']==='changepassword-form')
			{
				echo UActiveForm::validate($model);
				Yii::app()->end();
			}
			
			if(isset($_POST['UserChangePassword'])) {
					$model->attributes=$_POST['UserChangePassword'];
					if($model->validate()) {
						$new_password = User::model()->notsafe()->findbyPk(Yii::app()->user->id);
						$new_password->password = UserModule::encrypting($model->password);
						$new_password->activkey=UserModule::encrypting(microtime().$model->password);
						$new_password->save();
						Yii::app()->user->setFlash('profileMessage',UserModule::t("New password is saved."));
						$this->redirect(array("profile"));
					}
			}
			$this->render('changepassword',array('model'=>$model));
	    }
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the primary key value. Defaults to null, meaning using the 'id' GET variable
	 */
	public function loadUser()
	{
		if($this->_model===null)
		{
			if(Yii::app()->user->id)
				$this->_model=Yii::app()->controller->module->user();
			if($this->_model===null)
				$this->redirect(Yii::app()->controller->module->loginUrl);
		}
		return $this->_model;
	}


    public function actionUpload( ) {
        Yii::import( "xupload.models.XUploadForm" );
        //Here we define the paths where the files will be stored temporarily
        $path = realpath( Yii::app( )->getBasePath( )."/../images/uploads/tmp/profile/" )."/";
        $publicPath = Yii::app( )->getBaseUrl( )."/images/uploads/tmp/profile/";
        $pathSmall = realpath( Yii::app( )->getBasePath( )."/../images/uploads/tmp/profile/small/" )."/";


        //This is for IE which doens't handle 'Content-type: application/json' correctly
        header( 'Vary: Accept' );
        if( isset( $_SERVER['HTTP_ACCEPT'] )
            && (strpos( $_SERVER['HTTP_ACCEPT'], 'application/json' ) !== false) ) {
            header( 'Content-type: application/json' );
        } else {
            header( 'Content-type: text/plain' );
        }

        //Here we check if we are deleting and uploaded file
        if( isset( $_GET["_method"] ) ) {
            if( $_GET["_method"] == "delete" ) {
                if( $_GET["file"][0] !== '.' ) {
                    $file = $path.$_GET["file"];
                    if( is_file( $file ) ) {
                        unlink( $file );
                    }
                }
                echo json_encode( true );
            }
        } else {
            $model = new XUploadForm;
            $model->file = CUploadedFile::getInstance( $model, 'file' );
            //We check that the file was successfully uploaded
            if( $model->file !== null ) {
                //Grab some data
                $model->mime_type = $model->file->getType( );
                $model->size = $model->file->getSize( );
                $model->name = $model->file->getName( );
                //(optional) Generate a random name for our file
                $filename = md5( Yii::app( )->user->id.microtime( ).$model->name);
                $filename .= ".".$model->file->getExtensionName( );
                if( $model->validate( ) ) {
                    //Move our file to our temporary dir
                    //here you can also generate the image versions you need
                    //using something like PHPThumb
                    $model->file->saveAs( $path.$filename );
                    chmod( $path.$filename, 0777 );
                    Yii::import('application.extensions.image.Image');                  // Image Extension for resize file
                    $image = new Image($path.$filename);
                    $image->resize(30,30,Image::NONE);
                    $image->save($pathSmall.$filename);


                    //Now we need to save this path to the user's session
                    if( Yii::app( )->user->hasState( 'avatar' ) ) {
                        $userImages = Yii::app( )->user->getState( 'avatar' );
                    } else {
                        $userImages = array();
                    }
                    $userImages[] = array(
                        "path"     => $pathSmall.$filename,
                        "filename" => $filename,
                        'size'     => $model->size,
                        'mime'     => $model->mime_type,
                        'name'     => $model->name,
                    );
                    Yii::app( )->user->setState( 'avatar', $userImages );

                    //Now we need to tell our widget that the upload was succesfull
                    //We do so, using the json structure defined in
                    // https://github.com/blueimp/jQuery-File-Upload/wiki/Setup
                    echo json_encode( array( array(
                            "name" => $model->name,
                            "type" => $model->mime_type,
                            "size" => $model->size,
                            "url" => $publicPath.$filename,
                            "delete_url" => $this->createUrl( "upload", array(
                                    "_method" => "delete",
                                    "file" => $filename
                                ) ),
                            "delete_type" => "POST"
                        ) ) );
                } else {
                    //If the upload failed for some reason we log some data and let the widget know
                    echo json_encode( array(
                            array( "error" => $model->getErrors( 'file' ),
                            ) ) );

                    Yii::log( "XUploadAction: ".CVarDumper::dumpAsString( $model->getErrors( ) ),
                        CLogger::LEVEL_ERROR, "xupload.actions.XUploadAction"
                    );
                }
            } else {
                throw new CHttpException( 500, "Could not upload file" );

            }
        }
    }
}