<?php
namespace common\components;
use Yii;
use yii\base\Component;
use backend\models\UserProfile;
use common\models\User;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;
use yii\helpers\Html;

class GeneralComponent extends Component{
   
   // User Login Information
    public function userLoginInformation($info){
       
       $userInfoList = UserProfile::find()->where(['user_id' => Yii::$app->user->id])->one();
       if($info=='full_name_en')
         $info = $userInfoList->full_name_en;
       else if($info=='full_name_ar')
         $info = $userInfoList->full_name_ar;

       return $info;
    }
    // User Login Information

    // User Information
    public function userInformation($info,$userID){
       
       $userProfileModel = UserProfile::find()->where(['user_id' => $userID])->one();
       if($info=='country')
         $info = $userProfileModel->country;
       else if($info=='city')
         $info = $userProfileModel->city;
       else if($info=='zip')
         $info = $userProfileModel->zip;
       else if($info=='user_image')
         $info = $userProfileModel->user_image;
       else if($info=='full_name_ar')
         $info = $userProfileModel->full_name_ar;
       else if($info=='full_name_en')
         $info = $userProfileModel->full_name_en;
       
       return $info;
    }
    // End User Information


    // User status list from const common/User
    public function userStatusList(){
    	$commonUserObj = new User();
      $statusList = $this->statusLable();
    	$statusArray = [
    		['id'=>$commonUserObj::STATUS_DELETED, 'name'=>$statusList[$commonUserObj::STATUS_DELETED]],
    		['id'=>$commonUserObj::STATUS_ACTIVE, 'name'=>$statusList[$commonUserObj::STATUS_ACTIVE]],
    		['id'=>$commonUserObj::STATUS_BLOCKE, 'name'=>$statusList[$commonUserObj::STATUS_BLOCKE]],
    	];
    	return $statusArray;
    }
    // End User status

    // Get Country List
    public function countryList($countryId=''){
    
    	$jsonValue = file_get_contents(Yii::getAlias('@webroot')."/js/countryList.json");
    	$countriesList = json_decode($jsonValue,true);

      $this->cityList($countryId);
      return $countriesList['countryCodes'];	
    }
    // End Get Country List


    
    // Get Auto Adress
    public function getAutoAdress($country='',$city='',$zip='',$full_address='',$show){
      $jsonValue = file_get_contents(Yii::getAlias('@webroot')."/js/countryList.json");
      $countriesList = json_decode($jsonValue,true);

       foreach($countriesList['countryCodes'] as $key => $countryValue)
       {  
          if ( $countryValue['country_code'] == $country ){
             $countryName = $countryValue['country_name'];
             $zip = $countryValue['dialling_code'];

             foreach ($countryValue['main_city'] as $key => $citiesName) {
                   
                    if($citiesName['city_code']==$city)
                    {
                      $cityName = $citiesName['city_name'];
                    }
             }

          }
             
       }

       switch ($show) {
        case "all":
            return $countryName.' - '.$cityName.' - '.$zip.'<br/>'.$full_address;
            break;
        case "country":
            return $countryName;
            break;
        case "zip":
            return $zip;
            break;
        case "city":
            return $cityName;
            break;
        default:
            echo "-";
      }
    }
    // Get Auto Adress
    
    // Get City List
      public function cityList($countryId= '',$change=''){
      $jsonValue = file_get_contents(Yii::getAlias('@webroot')."/js/countryList.json");
      $countriesList = json_decode($jsonValue,true);
      $cityList = '';

          if(!empty($countryId)){
            foreach($countriesList['countryCodes'] as $key => $countriesCode) {

              if(isset($countryId) && $countriesCode['country_code'] == $countryId){
                if(isset($countriesCode['main_city'])){
                   if($change == 'change'){
                      $cityList.=  "<option value=''>Select City</option>";
                      foreach ($countriesCode['main_city'] as $key => $citiesName) {
                   
                       $cityList.=  "<option value='".$citiesName['city_code']."'>".$citiesName['city_name']."</option>";
                      }
                   }else{
                      $cityList = $countriesCode['main_city'];
                   }
                }
              }
          }
          return $cityList;
        }else{
          return [''];
        }
        
      }
     // End Get City List

    //Get zipValue
      public function zipValue($countryId=''){
        
      $jsonValue = file_get_contents(Yii::getAlias('@webroot')."/js/countryList.json");
      $countriesList = json_decode($jsonValue,true);
      $zipValue ='';
      if(!empty($countryId)){
            foreach($countriesList['countryCodes'] as $key => $countriesCode) {
              if(isset($countryId) && $countriesCode['country_code'] == $countryId){
                $zipValue = $countriesCode['dialling_code'];
              }
            }
      }
      return $zipValue;

      }
    //End Get zipValue

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

    // Save Form File
    public function saveFormFile($fileName,$filedName,$dbColumn,$model,$rootPath,$folderName,$oldFile=''){
       
       $model->{$filedName} = UploadedFile::getInstance($model,$filedName);
       
       // Delete Old
       if(!empty($oldFile) && !empty($model->{$filedName})){
          $this->deleteFile($oldFile);
       }
       // Save File
      
       if(!empty($model->{$filedName})){
         $image_name = Yii::$app->generalComp->generateFileName($fileName);
         $folderPath = Yii::$app->generalComp->generateFolder($rootPath,$folderName);
         
         $image_path = $folderPath.'/'.$image_name.'.'.$model->{$filedName}->extension;

         $model->{$filedName}->saveAs($image_path);
         //Save the path in db
         $model->{$dbColumn}= $image_path; 
       }
       
    }
    // End Save Form File

    // Delete file
    public function deleteFile($fileName){
        if (file_exists($fileName))
        {
           unlink($fileName);
           return true;
        }
    }
    // End Delete file

    // Show Image
    public function getImage($path,$class='',$width='',$height='',$alt=''){
          
          if(file_exists($path)){
            return Html::img($path,['class'=>$class,'width' => $width , 'height'=>$height, 'alt' => $alt]);
          }else{
            return '<p class="text-center text-info"><small>Image not found</small></p>';
          }
          
    }
    // End Show Image

    // get Status
    public function getStatus($status){

      $statusList = $this->statusLable();
      $statusTextClass = $this->statusTextClass();

      return '<p class="'.$statusTextClass[$status].'">'.$statusList[$status].'</p>';
    }
    // End get Status

    // Status lable
    public function statusLable(){
      $statusList = [0=>'STATUS_DELETED',10=>'STATUS_ACTIVE',20=>'STATUS_BLOCKE',30=>'STATUS_ONLINE',40=>'STATUS_OFFLINE'];

      return $statusList;
    }
    // Status lable

    // Status text class
    public function statusTextClass(){
      $statusTextClass = [0=>'text-danger',10=>'text-success',20=>'text-danger',30=>'text-warning',40=>'text-success'];

      return $statusTextClass;
    }
    // Status text class

    // Get Date And Time
    public function getDateAndTime($dateAndTime){
      date_default_timezone_set('Asia/Amman');
      return date('y-m-d H:i:s e',$dateAndTime);
    }
    // End Get Date And Time
  }
?>