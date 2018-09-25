<?php
/**
*-------------------------------------------------------------------------*
* Apel
* Helpers get user permission of function 
*
* 処理概要/process overview     :
* 作成日/create date            :   2018/05/15
* 作成者/creater                :   TuanNT - ANS796 - tuannt@ans-asia.com
*
* @package                      :   Helpers
* @copyright                    :   Copyright (c) ANS-ASIA
* @version                      :   1.0.0
*-------------------------------------------------------------------------*
*/
namespace App\Helpers;
use Form,Lang;
use Illuminate\Http\Request;
class AuthSystem {    
    public function __construct()
    {

    }

    /*
    | Trieunb create 
    | get user permission of function 
    |
    |
    */
    public static function hasPermission()
    {
        try {
            $request                 = Request();
            
            $params                  = [];
            $params['auth_role_div'] = \GetUserInfo::getInfo('auth_role_div');
            
            $routeName               = $request->route()->getName();
            $explodeRouteName        = explode('__', $routeName);
            $params['prg_cd']        = $explodeRouteName[0];
            $params['fnc_cd']        = isset($explodeRouteName[1]) ? $explodeRouteName[1] : 'view';
            
            $sql                     = "SPC_GET_PERMISSION_AUTH";
            $data                    = Dao::call_stored_procedure($sql, $params);
            // dd($params['prg_cd'], $params['fnc_cd'], $data);

            $accept                  = true;
            
            if (count($data) > 0) {
                if (isset($data[0][0]['fnc_use_div']) && $data[0][0]['fnc_use_div'] == 0) {
                    $accept          = false;
                }
            }
            
            return $accept;
        } catch (\Exception $e) {
            return $e->getMessage() ;
        }
    }
}
