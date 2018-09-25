<?php
/**
*|--------------------------------------------------------------------------
*| stock manage
*|--------------------------------------------------------------------------
*| Package       : stock manage  
*| @author       : Trieunb - ANS806 - trieunb@ans-asia.com
*| @created date : 2017/08/10
*| Description   : 
*/
namespace Modules\stockmanage\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Common\Http\Controllers\ComboboxController as Combobox;
use Session, Dao, Button;

class InputOutputDetailController extends Controller
{
    /**
    * detail stock manage
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
            //get library
            $in_out_div          = Combobox::libraryCode('in_out_div');
            $in_out_data_div     = Combobox::libraryCode('in_out_data_div');
            $mode  = 'U';
            $from  = 'InputOutputDetail';
            if (Session::has('SELF')) {
                Session::forget('SELF');
                Session::forget('InputOutputDetail');
            } else {
                if (Session::has('InputOutputDetail')) {
                    $param = Session::get('InputOutputDetail');
                    if (!empty($param)) {
                        $mode  = $param['mode'];
                        $from  = $param['from'];
                        if (isset($param['in_out_no']) && !empty($param['in_out_no'])) {
                            $in_out_no          = $param['in_out_no'];
                        }
                    }
                }
            }
            return view('stockmanage::InputOutputDetail.detail', compact('in_out_no', 'mode', 'from', 'in_out_div', 'in_out_data_div'));
        } catch (\Exception $e) {
            return response()->json(array('response'=>false));
        }
    }
    /**
    * refer parts detail
    * -----------------------------------------------
    * @author      :   ANS806 - 2017/12/07 - create
    * @param       :
    * @return      :   mixed
    * @access      :   public
    * @see         :   remark
    */
    public function referItemSerial(Request $request)
    {
        try {
            $param  =   $request->all(); 
            $sql    =   "SPC_048_INPUT_OUTPUT_DETAIL_INQ2"; 
            $data   =   Dao::call_stored_procedure($sql, $param, true);
            // return $data;
            if (isset($data[0]) && !empty($data[0])) {
                return response()->json(array(
                    'response'      => true,
                    'data'          => $data[0][0]
                ));
            } else {
                return response()->json(array(
                    'response'      => false,
                ));
            }
        } catch (\Exception $e) {
            return response()->json(array(
                    'response'=> $e->getMessage()
            ));
        }    
    }

     /**
    * refer purhchase request
    * -----------------------------------------------
    * @author      :   ANS806 - 2018/02/03 - create
    * @param       :
    * @return      :   mixed
    * @access      :   public
    * @see         :   remark
    */
    public function referInOut(Request $request) 
    {
        try {
            $param      =   ['in_out_no' => $request->in_out_no];

            $sql        =   "SPC_048_INPUT_OUTPUT_DETAIL_INQ1"; 
            $data       =   Dao::call_stored_procedure($sql, $param, true);
            // return $data; die;
            $in_out_h       =   isset($data[0]) ? $data[0][0] : array();
            $in_out_d       =   isset($data[1]) ? $data[1] : array();
            // return $in_out_h; die;
            $header         =   $in_out_h;
            // return $header;
            $header_html    =   view('layouts._operator_info',compact('header'))->render();
            // $button         =   Button::showButtonServer(array('btn-back', 'btn-save', 'btn-delete'), $request->mode);

            $html_in_out_d  =  view('stockmanage::InputOutputDetail.input_output_table',compact('in_out_d'))->render();
            
            if (isset($data) && !empty($data)) {
                return response()->json([
                                'response'      =>  true,
                                'html_in_out_d' =>  $html_in_out_d,
                                'header_html'   =>  $header_html,
                                'in_out_h'      =>  $in_out_h,
                                // 'button'        =>  $button,
                            ]);
            } else {
                return response()->json([
                                'response'      =>  false,
                                'html_in_out_d' =>  '',
                                'header_html'   =>  '',
                                'in_out_h'      =>  '',
                                // 'button'        =>  $button,
                            ]);
            }
        } catch (\Exception $e) {
            return response()->json(['response' => false]);
        }
    }
    /**
    * Create/Update Purchase Request
    * -----------------------------------------------
    * @author      :   ANS806 - 2018/02/03 - create
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
            // return $data;
            $data['t_in_out_d']     = json_encode($data['t_in_out_d']);//parse json to string

            $data['cre_user_cd']    = \GetUserInfo::getInfo('user_cd');
            $data['cre_prg_cd']     = '048_input_output_detail';
            $data['cre_ip']         = \GetUserInfo::getInfo('user_ip');
            //call stored
            $sql                    = "SPC_048_INPUT_OUTPUT_DETAIL_ACT1";
            $result                 = Dao::call_stored_procedure($sql, $data);
            // return $result; die;
            $error_list             = isset($result[3]) ? $result[3] : array();
            //return result to client
            if (empty($result[0])) {
                return response()->json(array(
                    'response'      => true,
                    'error_cd'      => $result[1][0]['error_cd'],
                    'in_out_no'     => $result[1][0]['in_out_no'],
                    'errors_item'   => $result[2][0],
                    'error_list'    => $error_list
                ));
            } else {
                return response()->json(array(
                    'response'      => false,
                    'error'         => $result[0][0]['Message'],
                ));
            }
        } catch (\Exception $e) {
            return response()->json(array(
                        'response'      => false,
                        'error'         => $e->getMessage(),
                    ));
        }        
    }
    /**
    * Check Serial Exist
    * -----------------------------------------------
    * @author      :   ANS806 - 2018/02/03 - create
    * @param       :
    * @return      :   mixed
    * @access      :   public
    * @see         :   remark
    */
    public function postSerialExist(Request $request)
    {
        try {
            //get data from client
            $data                   = $request->all();
            // return $data;
            $data['t_in_out_d']     = json_encode($data);//parse json to string
            //call stored
            $sql                    = "SPC_048_INPUT_OUTPUT_DETAIL_ACT2";
            $result                 = Dao::call_stored_procedure($sql, $data);
            // return $result; die;
            $error_list             = isset($result[0]) ? $result[0] : array();
            return response()->json([
                    'serial_exist_list' =>   $error_list
                ]);

        } catch (\Exception $e) {
            return response()->json(array(
                        'response'      => false,
                        'error'         => $e->getMessage(),
                    ));
        }        
    }
}
