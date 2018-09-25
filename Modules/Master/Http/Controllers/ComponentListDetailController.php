<?php
/**
*|--------------------------------------------------------------------------
*| Order
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
use Session, Dao, Button;

class ComponentListDetailController extends Controller
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
            $unit_q_div        = Combobox::libraryCode('unit_q_div');

            $mode       = 'U';
            $from       = 'ComponentListDetail';
            $is_new     =   'false';
            $componentListDetail        = '';
            if (Session::has('SELF')) {
                Session::forget('SELF');
                Session::forget('ComponentListDetail');
            } else {
                if (Session::has('ComponentListDetail')) {
                    $param = Session::get('ComponentListDetail');
                    if (!empty($param)) {
                        $mode       = $param['mode'];
                        $from       = $param['from'];
                        $is_new     = (isset($param['is_new']) && !empty($param['is_new']))  ? $param['is_new'] : 'false';
                        if (isset($param['parent_item_cd']) && !empty($param['parent_item_cd'])) {
                            $parent_item_cd        = $param['parent_item_cd'];
                            $child_item_cd         = $param['child_item_cd'];
                        }
                    }
                }
            }
            return view('master::ComponentListDetail.detail', compact('parent_item_cd', 'child_item_cd', 'mode', 'from', 'is_new', 'unit_q_div'));
        } catch (\Exception $e) {
            return response()->json(array('response'=>false));
        }
    }
    /**
    * Create/Update bom Information
    * -----------------------------------------------
    * @author      :   ANS806 - 2017/12/15 - create
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
            $data['cre_prg_cd']     = '070_component-list-detail';
            $data['cre_ip']         = \GetUserInfo::getInfo('user_ip');
            //call stored
            $sql                    = "SPC_070_COMPONENT_LIST_DETAIL_ACT1";
            $result                 = Dao::call_stored_procedure($sql, $data);
            //get data error
            $data_err               = isset($result[2]) ? $result[2] : NULL;
            //return result to client
            if (empty($result[0])) {
                return response()->json(array(
                    'response'      => true,
                    'error_cd'      => $result[1][0]['error_cd'],
                    'data_err'      => $data_err
                ));
            } else {
                return response()->json(array(
                    'response'      => false,
                    'error'         => $result[0][0]['Message'],
                    'error_cd'      => $result[1][0]['error_cd'],
                    'data_err'      => $data_err
                ));
            }
        } catch (\Exception $e) {
            return response()->json(array(
                    'response'=> $e->getMessage()
            ));
        }        
    }
    /**
    * refer bom information
    * -----------------------------------------------
    * @author      :   ANS7806 - 2017/11/08 - create
    * @param       :
    * @return      :   mixed
    * @access      :   public
    * @see         :   remark
    */
    public function postRefer(Request $request)
    {
        try{
            $item_cd      = Session::get('ComponentListDetail');
            if ($item_cd != null && Session::has('ComponentListDetail')) {
                $item_cd['parent_item_cd']    = '';
                $item_cd['child_item_cd']     = '';
                Session::set('ComponentListDetail', $item_cd);
            }
            $param  = [
                    'parent_item_cd'   => $request->parent_item_cd,
                    'child_item_cd'    => $request->child_item_cd
                ];
            $mode           =   $request->mode;
            $sql            = "SPC_070_COMPONENT_LIST_DETAIL_INQ1"; 
            $data           = Dao::call_stored_procedure($sql, $param, true);
            $header         = isset($data[0][0]) ? $data[0][0] : array();
            $header_html    = view('layouts._operator_info',compact('header'))->render();
            //
            if(isset($data[0]) && !empty($data[0])) {
                $mode           = 'U';
                $header_button  = Button::showButtonServer(array('btn-back', 'btn-save','btn-delete'), $mode);
                return response()->json(array(
                    'response'      => true,
                    'data'          => $data[0][0],
                    'header'        => $header_html,
                    'button'        => $header_button
                ));
            } else {
                $header_html    = view('layouts._operator_info')->render();
                $mode           = 'I';
                $header_button  = Button::showButtonServer(array('btn-back', 'btn-save','btn-delete'), $mode);
                return response()->json(array(
                    'response'      => false,
                    'data'          => '',
                    'header'        => $header_html,
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
    * Delete bom detail
    * -----------------------------------------------
    * @author      :   ANS806 - 2017/12/05 - create
    * @param       :
    * @return      :   mixed
    * @access      :   public
    * @see         :   remark
    */
    public function deleteBom(Request $request) {
        try {
            //get data from client
            $data                   = $request->all();
            $data['cre_user_cd']    = \GetUserInfo::getInfo('user_cd');
            $data['cre_prg_cd']     = '070_component-list-detail';
            $data['cre_ip']         = \GetUserInfo::getInfo('user_ip');
            //call stored
            $sql                    = "SPC_070_COMPONENT_LIST_DETAIL_ACT2";
            $result                 = Dao::call_stored_procedure($sql, $data);
            //return result to client
            if (empty($result[0])) {
                return response()->json(array(
                    'response'      => true,
                    'error_cd'      => $result[1][0]['error_cd'],
                ));
            } else {
                return response()->json(array(
                    'response'      => false,
                    'error'         => $result[0][0]['Message'],
                    'error_cd'      => $result[1][0]['error_cd']
                ));
            }
        } catch(\Exception $e) {
            return response()->json(array(
                    'response'=> $e->getMessage()
            ));
        }
    }
}
