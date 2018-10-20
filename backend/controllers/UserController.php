<?php

namespace backend\controllers;

use Yii;
use backend\models\User;
use backend\models\UserSearch;
use backend\models\UserProfile;
use yii\web\Controller;
use \yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\widgets\ActiveForm;
use yii\web\Response;
use \yii\db\Exception;
use yii\web\UploadedFile;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
            'class' => AccessControl::className(),
            'only' => ['index','view','create', 'update','delete'],
            'rules' => [
                [
                    'allow' => true,
                    'roles' => ['@'],
                ],
              ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if(!Yii::$app->request->isAjax)
        {
            return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            ]);
        }
        
    }

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $userProfile = new UserProfile();
        $userProfile = $userProfile->findOne(['user_id' => $id]);

        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new User();
        $userProfile = new UserProfile();


        if ($model->load(Yii::$app->request->post()) && $userProfile->load(Yii::$app->request->post())) {

           $model->password_hash =Yii::$app->security->generatePasswordHash($model->password_hash);
           $model->auth_key = Yii::$app->security->generateRandomString();
           $model->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();

           Yii::$app->generalComp->saveFormFile($model->username,'image_file','user_image',$userProfile,'uploads/users/',$model->username);


           $model->created_by= Yii::$app->user->id;
           $model->created_at = time();

            
            // user profile save
            $max = $model->find()->max('id')+1;
            $model->id = $max; 
            $userProfile->user_id = $max;

            //Save All Data
            if($model->save(false)){
                $userProfile->save(false);
                
                $arr = ['action'=>1,'message'=>'User is Successfully Updated!'];
                echo json_encode($arr);
            }else{
                $arr = ['action'=>0,'message'=>'Failed To updated User!'];
            }

             
        } else {
            return $this->renderAjax('create', [
                'model' => $model,
                'userProfile' => $userProfile,
            ]);
        }
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $userProfile = new UserProfile();
        $userProfile = $userProfile->findOne(['user_id' => $id]);
        $oldFile = $userProfile->user_image;
        $oldPass = $model->password_hash;
         if ($model->load(Yii::$app->request->post()) && $userProfile->load(Yii::$app->request->post())) {

           if($oldPass != $model->password_hash)
           $model->password_hash =Yii::$app->security->generatePasswordHash($model->password_hash);

           //Save File
           Yii::$app->generalComp->saveFormFile($model->username,'image_file','user_image',$userProfile,'uploads/users/',$model->username,$oldFile);

           $model->updated_by= Yii::$app->user->id;
           $model->updated_at = time();


            //Save All Data
            if($model->save(false)){
                $userProfile->save(false);
                
                $arr = ['action'=>1,'message'=>'User is Successfully Updated!'];
                echo json_encode($arr);
            }else{
                $arr = ['action'=>0,'message'=>'Failed To updated User!'];
            }

        } else {

            return $this->renderAjax('update', [
                'model' => $model,
                'userProfile' => $userProfile,
            ]);
        }
    }

    // ajax validation
    public function actionValidate($id=null)
    {
        $model = $id===null ? new User : User::findOne($id);

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
    }
    
    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    // User Location
    public function actionGetCountry($countryId){


          $arr = ['zip'=>Yii::$app->generalComp->zipValue($countryId),
          'city'=>Yii::$app->generalComp->cityList($countryId,'change')];
                echo json_encode($arr);
    }
}
