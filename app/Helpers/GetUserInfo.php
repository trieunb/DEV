<?php
/**
*-------------------------------------------------------------------------*
* Apel
* Helpers get info of user loggin 
*
* 処理概要/process overview :
* 作成日/create date         :   2017/11/22
* 作成者/creater             :   ANS796 - tuannt@ans-asia.com
*
* @package                  :   Helpers
* @copyright                :   Copyright (c) ANS-ASIA
* @version                  :   1.0.0
*-------------------------------------------------------------------------*
*/
namespace App\Helpers;
use Form,Lang;
use Request;
class GetUserInfo {

    protected static $user_info;
    
    public function __construct(){
    }

    /*
    | get user info login
    |
    |
    */
    public static function getInfo($str = null){
        try{
            /*
            |   get session user login info 
            |   if not session then return null  
            */
            if(\Session::has('user_info'))  {
                self::$user_info = \Session::get('user_info');
            }else{
                self::$user_info = null;
            }
            if (self::$user_info != null && $str != null) {

                if ($str =='user_ip') {
                    return Request::ip();
                }
                
                return self::$user_info[$str];     
            }else{
                return null;
            }
        }catch (\Exception $e) {
            return $e->getMessage() ;
        }
    }

    /*
    | set user info login
    |
    |
    */
    public static function setInfo($str = null,$param){
        try{
            /*
            |   get session user login info 
            |   if not session then return null  
            */
            if(\Session::has('user_info'))  {
                self::$user_info = \Session::get('user_info');
                if (self::$user_info!=null && $str!=null) {
                    self::$user_info[$str]=$param;
                    \session::set('user_info',self::$user_info);     
                }else{
                    return null;
                }
            }else{
                self::$user_info = null;
            }
        }catch (\Exception $e) {
            return $e->getMessage() ;
        }
    }
}