<?php
namespace common\components;
use Yii;
use yii\base\Component;
use backend\models\UserProfile;
use common\models\User;
use yii\helpers\FileHelper;
class GeneralComponent extends Component{
   
   // User Information
    public function userInformation(){
       
       $userInfoList = UserProfile::find()->where(['user_id' => Yii::$app->user->id])->one();
       return  $userInfoList->full_name_en;
    }
    // End User Information

    // User status list from const common/User
    public function userStatusList(){
    	$commonUserObj = new User();
    	$statusArray = [
    		['id'=>$commonUserObj::STATUS_DELETED, 'name'=>$commonUserObj::STATUS_DELETED],
    		['id'=>$commonUserObj::STATUS_ACTIVE, 'name'=>$commonUserObj::STATUS_ACTIVE],
    		['id'=>$commonUserObj::STATUS_BLOCKE, 'name'=>$commonUserObj::STATUS_BLOCKE],
    	];
    	return $statusArray;
    }
    // End User status

    // Main Address List
    public function mainAddress($countryId=''){
    	$jsonValue = file_get_contents(Yii::getAlias('@webroot')."/js/countryList.json");
    	$countriesList = json_decode($jsonValue,true);
    	if($countryId == ''){
                  return $countriesList['countryCodes'];
        }else{
       		$optionValue = '';
       		$countriesCode ='';
       		$citiesName = '';

       		foreach($countriesList['countryCodes'] as $key => $countriesCode) {
       	
            if($countriesCode['country_code'] == $countryId ){

              if(isset($countriesCode['main_city'])){
                foreach ($countriesCode['main_city'] as $key => $citiesName) {
                 
                 $optionValue.=  "<option value='".$citiesName['city_code']."'>".$citiesName['city_name']."</option>";
                }
                return $countriesCode['dialling_code'].'__'.$optionValue;
              }else{
                $optionValue = "<option>-</option>";
                return $countriesCode['dialling_code'].'__'.$optionValue;
              }
            }
			    }       		
       	}
      }
        // End Main Address List

    // Generate File Name
    public function generateFileName($username){
        if(isset($username))
          $fileName = $username.'_'.time().'_'.rand(1000, 9999); 
        else
          $fileName = Null;

        return $fileName;
    }
    // End Generate File Name

    //  Generate Folder
        public function generateFolder($rootPath,$folderName){
          $path = $rootPath.$folderName;
          if(!empty($folderName)){
            FileHelper::createDirectory($path);
            return $path;
          }
        }
    //  End Generate Folder
  }
?>