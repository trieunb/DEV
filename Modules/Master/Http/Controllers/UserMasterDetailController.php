<?php
/**
*|--------------------------------------------------------------------------
*| User Master
*|--------------------------------------------------------------------------
*| Package       : Master  
*| @author       : Trieunb - ANS806 - trieunb@ans-asia.com
*| @created date : 2017/01/08
*| Description   : 
*/
namespace Modules\Master\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Common\Http\Controllers\ComboboxController as Combobox;
use Session, DB, Dao, Button;

class UserMasterDetailController extends Controller
{
    /**
    * list pi
    * -----------------------------------------------
    * @author      :   ANS806 - 2017/08/08 - create
    * @param       :
    * @return      :   mixed
    * @access      :   public
    * @see         :   remark
    */
    public function getDetail()
    {
        try {
            $auth_role_div       = Combobox::libraryCode('auth_role_div');
            $incumbent_div       = Combobox::libraryCode('incumbent_div');
            $belong_div          = Combobox::libraryCode('belong_div');
            $position_div        = Combobox::libraryCode('position_div');

            $mode       =   'U';
            $from       =   'UserMasterDetail';
            $is_new     =   'false';
            if (Session::has('SELF')) {
                Session::forget('SELF');
                Session::forget('UserMasterDetail');
                $mode  = 'U';
            } else {
                if (Session::has('UserMasterDetail')) {
                    $param = Session::get('UserMasterDetail');
                    if (!empty($param)) {
                        $mode       = $param['mode'];
                        $from       = $param['from'];
                        $is_new     = (isset($param['is_new']) && !empty($param['is_new']))  ? $param['is_new'] : 'false';
                        if (isset($param['user_cd']) && !empty($param['user_cd'])) {
                            $userCd             = $param['user_cd'];
                            $userDetailList     = '';
                        }
                    }
                }
            }
            return view('master::UserMasterDetail.detail', compact('userDetailList', 'userCd', 'mode', 'from', 'is_new', 'auth_role_div', 'incumbent_div', 'belong_div', 'position_div'));
        } catch (\Exception $e) {
            return response()->json(array('response'=>false));
        }
    }
    /**
    * refer user information
    * -----------------------------------------------
    * @author      :   ANS796 - 2017/11/08 - create
    * @param       :
    * @return      :   mixed
    * @access      :   public
    * @see         :   remark
    */
    public function postRefer(Request $request){
        try{
            $param          = array($request->user_cd);
            $sql            = "SPC_076_USER_MASTER_DETAIL_INQ1"; 
            $data           = Dao::call_stored_procedure($sql,$param,true);
            $header         = isset($data[0][0]) ? $data[0][0] : array();
            $header_html    = view('layouts._operator_info',compact('header'))->render();
            //render list button Header
            $header_button  = Button::showButtonServer(array('btn-back', 'btn-save','btn-delete'), 'I');
            if(isset($data[0])){
                $header_button  = Button::showButtonServer(array('btn-back', 'btn-save','btn-delete'), 'U');
            }
            //update user_cd in Session
            $user_info      = Session::get('UserMasterDetail');
            //return data
            if(isset($data[0])){
                if ($user_info != null && Session::has('UserMasterDetail')) {
                    $user_info['user_cd']   = '';
                    $user_info['mode']      = 'U';
                    Session::set('UserMasterDetail',$user_info);
                }
                return response()->json(array(
                    'response'      => true,
                    'user'          => $data[0][0],
                    'header'        => $header_html,
                    'button'        => $header_button
                ));
            }else{
                if (Session::has('UserMasterDetail')) {
                    $user_info['user_cd']   = '';
                    $user_info['mode']      = 'I';
                    Session::set('UserMasterDetail', $user_info);
                }
                return response()->json(array(
                    'response'      => false,
                    'user'          => NULL,
                    'button'        => $header_button
                ));
            }
        }
        catch (\Exception $e) {
            return response()->json(array(
                    'response'=> false
            ));
        }        
    }
    /**
    * Create/Update User Information
    * -----------------------------------------------
    * @author      :   ANS796 - 2017/11/08 - create
    * @param       :
    * @return      :   mixed
    * @access      :   public
    * @see         :   remark
    */
    public function postSave(Request $request)
    {
        try {
            //get data from client
            $data                   = $request->all();
            $data['cre_user_cd']    = \GetUserInfo::getInfo('user_cd');
            $data['cre_prg_cd']     = '076_user-master-detail'; //\GetUserInfo::getInfo('user_id');
            $data['cre_ip']         = \GetUserInfo::getInfo('user_ip');
            //call stored
            $sql                    = "SPC_076_USER_MASTER_DETAIL_ACT1";
            $result                 = Dao::call_stored_procedure($sql,$data);
            //return result to client
            if (empty($result[0])) {
                return response()->json(array(
                    'response'      => true,
                    'error_cd'      => $result[1][0]['error_cd']
                ));
            }else{
                return response()->json(array(
                    'response'      => false,
                    'error'         => $result[0][0]['Message'],
                    'error_cd'      => $result[1][0]['error_cd']
                ));
            }
     } catch (\Exception $e) {
            return response()->json(array(
                    'response'=> $e->getMessage()
            ));
        }        
    }
    /**
    * delete user information
    * -----------------------------------------------
    * @author      :   ANS796 - 2017/11/08 - create
    * @param       :
    * @return      :   mixed
    * @access      :   public
    * @see         :   remark
    */
    public function postDelete(Request $request){
        try{
            $param                  = array($request->user_cd);
            $param['cre_user_cd']   = \GetUserInfo::getInfo('user_cd');
            $data['cre_prg_cd']     = '076_user-master-detail'; //\GetUserInfo::getInfo('user_id');
            $param['cre_ip']        = \GetUserInfo::getInfo('user_ip');
            $sql                    = "SPC_076_USER_MASTER_DETAIL_ACT2"; 
            $result                 = Dao::call_stored_procedure($sql,$param);
            //return data
            if(empty($result[0])){
               return response()->json(array(
                    'response'      => true,
                    'error_cd'      => $result[1][0]['error_cd']
                ));
            }else{
                return response()->json(array(
                    'response'      => false,
                    'error'         => $result[0][0]['Message'],
                    'error_cd'      => $result[1][0]['error_cd']
                ));
            }
        }
        catch (\Exception $e) {
            return response()->json(array(
                    'response'=> false
            ));
        }        
    }
}
