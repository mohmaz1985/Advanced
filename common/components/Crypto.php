<?php

namespace common\components;

use Yii;
use yii\base\Model;
use yii\helpers\Html;
/**
 * ContactForm is the model behind the contact form.
 */
class Crypto extends Model
{

  function doEncrypt($string) {

    $key = "Moh@85_275859585959595955"; //key to encrypt and decrypts.
    $result = '';

     for($i=0; $i<strlen($string); $i++) {
       $char = substr($string, $i, 1);
       $keychar = substr($key, ($i % strlen($key))-1, 1);
       $char = chr(ord($char)+ord($keychar));
       $result.=$char;
     }

     return urlencode(base64_encode($result));
    }

    function doDecrypt($string) {
      $key = "Moh@85_275859585959595955"; //key to encrypt and decrypts.
      $result = '';
      $string = base64_decode(urldecode($string));
       for($i=0; $i<strlen($string); $i++) {
         $char = substr($string, $i, 1);
         $keychar = substr($key, ($i % strlen($key))-1, 1);
         $char = chr(ord($char)-ord($keychar));
         $result.=$char;
       }
     return $result;
    }
}
