<?php
namespace common\components;
use Yii;
use yii\base\Component;
use backend\models\UserProfile;

class GeneralComponent extends Component{
   
    public function userInformation(){
       
       $userInfoList = UserProfile::find()->where(['user_id' => Yii::$app->user->id])->one();
       return  $userInfoList->full_name_en;
    }
}
?>