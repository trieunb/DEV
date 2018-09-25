<?php
/**
*|--------------------------------------------------------------------------
*| Working Time Manage
*|--------------------------------------------------------------------------
*| Package       : Working Time Manage
*| @author       : Trieunb - ANS806 - trieunb@ans-asia.com
*| @created date : 2017/08/08
*| Description   : 
*/
namespace Modules\WorkingTimeManage\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Common\Http\Controllers\ComboboxController as Combobox;
use Session, DB, Dao, Button;

class WorkingTimeDetailController extends Controller
{
    /**
    * list working time
    * -----------------------------------------------
    * @author      :   ANS806 - 2017/01/08 - create
    * @param       :
    * @return      :   mixed
    * @access      :   public
    * @see         :   remark
    */
    public function getDetail()
    {
        try {
            //get library
            $work_hour_div          = Combobox::libraryCode('work_hour_div');
            $work_time_div          = Combobox::libraryCode('work_time_div');

            $mode           = 'U';
            $from           = 'WorkingTimeDetail';
            $workReportNo   = '';
            $userLogin      = \GetUserInfo::getInfo('user_cd');
            if (Session::has('SELF')) {
                Session::forget('SELF');
                Session::forget('WorkingTimeDetail');
                $mode  = 'U';
            } else {
                if (Session::has('WorkingTimeDetail')) {
                    $param = Session::get('WorkingTimeDetail');
                    if (!empty($param)) {
                        $mode  = $param['mode'];
                        $from  = $param['from'];
                        if (isset($param['work_report_no']) && !empty($param['work_report_no'])) {
                            $workReportNo = $param['work_report_no'];
                        }
                    }
                }
            }
            return view('workingtimemanage::WorkingTimeDetail.detail', compact('workReportNo', 'mode', 'from', 'userLogin', 'work_hour_div', 'work_time_div'));
        } catch (\Exception $e) {
            return response()->json(array('response'=>false));
        }
    }
    /**
    * refer Working time information
    * -----------------------------------------------
    * @author      :   ANS796 - 2018/01/03 - create
    * @param       :
    * @return      :   mixed
    * @access      :   public
    * @see         :   remark
    */
    public function postRefer(Request $request){
        try{
            //get library
            $work_hour_div      = Combobox::libraryCode('work_hour_div');
            $work_time_div      = Combobox::libraryCode('work_time_div');
            $param              = array($request->work_report_no);
            $sql                = "SPC_051_WORKING_TIME_DETAIL_INQ1"; 
            $data               = Dao::call_stored_procedure($sql,$param,true);
            $header             = isset($data[0][0]) ? $data[0][0] : array();
            $header_html        = view('layouts._operator_info',compact('header'))->render();
            $header_button      = Button::showButtonServer(array('btn-back', 'btn-save','btn-delete'), $request->mode);
            $workingTimeList    = isset($data[1]) ? $data[1] : array();
            $workingtime_html   = view('workingtimemanage::WorkingTimeDetail.table_workingtime',compact('workingTimeList', 'work_hour_div', 'work_time_div'))->render();
            //return data
            if(isset($data[0])){
                //update work_report_no in Session
                /*$working_info = Session::get('WorkingTimeDetail');
                if ($working_info != null && $request->mode == 'U') {
                    $working_info['work_report_no']= $data[0][0]['work_report_no'];
                    Session::set('WorkingTimeDetail', $working_info);     
                }*/
                return response()->json(array(
                    'response'      => true,
                    'working'       => $data[0][0],
                    'header'        => $header_html,
                    'button'        => $header_button,
                    'table'         => $workingtime_html
                ));
            }else{
                return response()->json(array(
                    'response'      => false,
                    'working'       => NULL,
                    'table'         => $workingtime_html
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
    * Create/Update WorkingTime Information
    * -----------------------------------------------
    * @author      :   ANS796 - 2018/01/03 - create
    * @param       :
    * @return      :   mixed
    * @access      :   public
    * @see         :   remark
    */
    public function postSave(Request $request)
    {
        try {
            //get data from client
            $data                       = $request->all();
            $data['workingtime_list']   = json_encode($data['workingtime_list']);//parse json to string
            $data['cre_user_cd']        = \GetUserInfo::getInfo('user_cd');
            $data['cre_prg_cd']         = '051_working-time-detail';
            $data['cre_ip']             = \GetUserInfo::getInfo('user_ip');
            //call stored
            $sql                        = "SPC_051_WORKING_TIME_DETAIL_ACT1";
            $result                     = Dao::call_stored_procedure($sql, $data);
            //get data error
            $data_err                   = isset($result[2]) ? $result[2] : NULL;
            //return result to client
            if (empty($result[0])) {
                return response()->json(array(
                    'response'          => true,
                    'error_cd'          => $result[1][0]['error_cd'],
                    'work_report_no'    => $result[1][0]['work_report_no'],
                    'data_err'          => $data_err
                ));
            }else{
                return response()->json(array(
                    'response'          => false,
                    'error'             => $result[0][0]['Message'],
                    'error_cd'          => $result[1][0]['error_cd'],
                    'work_report_no'    => $result[1][0]['work_report_no'],
                    'data_err'          => $data_err
                ));
            }
     } catch (\Exception $e) {
            return response()->json(array(
                    'response'=> $e->getMessage()
            ));
        }        
    }
    /**
    * delete WorkingTime information
    * -----------------------------------------------
    * @author      :   ANS796 - 2018/01/03 - create
    * @param       :
    * @return      :   mixed
    * @access      :   public
    * @see         :   remark
    */
    public function postDelete(Request $request){
        try{
            $param                  = array($request->work_report_no);
            $param['cre_user_cd']   = \GetUserInfo::getInfo('user_cd');
            $param['cre_prg_cd']    = '051_working-time-detail';
            $param['cre_ip']        = \GetUserInfo::getInfo('user_ip');
            $sql                    = "SPC_051_WORKING_TIME_DETAIL_ACT2"; 
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
