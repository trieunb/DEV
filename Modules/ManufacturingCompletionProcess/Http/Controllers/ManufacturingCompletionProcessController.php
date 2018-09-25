<?php
/**
*|--------------------------------------------------------------------------
*| Manufacturing Completion Process
*|--------------------------------------------------------------------------
*| Package       : Manufacturing Completion Process  
*| @author       : TuanNK - ANS818 - tuannk@ans-asia.com
*| @created date : 2017/01/08
*| Description   : 
*/
namespace Modules\ManufacturingCompletionProcess\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Dao, Session;

class ManufacturingCompletionProcessController extends Controller
{
    /**
    * view manufacture completion process
    * -----------------------------------------------
    * @author      :   ANS817 - 2018/04/19 - create
    * @param       :
    * @return      :   mixed
    * @access      :   public
    * @see         :   remark
    */
    public function getIndex()
    {
        $from           = 'ManufacturingCompletionProcess';
        $manufacture_no = '';

        if (Session::has('SELF')) {
            Session::forget('SELF');
            Session::forget('ManufacturingCompletionProcess');
        } else {
            if (Session::has('ManufacturingCompletionProcess')) {
                $param = Session::get('ManufacturingCompletionProcess');

                if (!empty($param)) {
                    $from  = $param['from'];

                    if (isset($param['manufacture_no']) && !empty($param['manufacture_no'])) {
                        $manufacture_no = $param['manufacture_no'];
                    }
                }
            }
        }
        return view('manufacturingcompletionprocess::ManufacturingCompletionProcess.index', compact('manufacture_no', 'from'));
    }

    /**
    * refer manufacture no
    * -----------------------------------------------
    * @author      :   ANS817 - 2018/04/19 - create
    * @param       :
    * @return      :   mixed
    * @access      :   public
    * @see         :   remark
    */
    public function postRefermanufactureNo(Request $request)
    {
        try{
            $param       = $request->all();
            $sql         = "SPC_047_MANUFACTURING_COMPLETION_PROCESS_INQ1"; 
            $data        = Dao::call_stored_procedure($sql, $param, true);
            //render header info
            if (isset($data[0][0])) {
                $header      = $data[0][0];
                $header_html = view('layouts._operator_info', compact('header'))->render();
            } else {
                $header_html = '';
            }
            //render table
            $listData    = isset($data[2]) ? $data[2] : array();
            $table_html  = view('manufacturingcompletionprocess::ManufacturingCompletionProcess.table', compact('listData'))->render(); 

            //return data
            if(!empty($data[1][0])){
                return response()->json(array(
                    'response' => true,
                    'header'   => $header_html,
                    'data'     => $data[1][0],
                    'table'    => $table_html,
                ));
            }else{
                return response()->json(array(
                    'response'      => false
                ));
            }
        } catch (\Exception $e) {
            return response()->json(array(
                    'response'=> false
            ));
        }
    }

    /**
    * Insert/update Manufacturing Completion Process
    * -----------------------------------------------
    * @author      :   ANS817 - 2018/04/20 - create
    * @param       :
    * @return      :   mixed
    * @access      :   public
    * @see         :   remark
    */
    public function postSave(Request $request) 
    {
        try {
            //get data from client
            $data                = $request->all();
            $data['t_complete']  = json_encode($data['t_complete']);//parse json to string
            $data['cre_user_cd'] = \GetUserInfo::getInfo('user_cd');
            $data['cre_prg_cd']  = '047_manufacturing_completion_process';
            $data['cre_ip']      = \GetUserInfo::getInfo('user_ip');
            //call stored
            $sql                 = 'SPC_047_MANUFACTURING_COMPLETION_PROCESS_ACT1';
            $result              = Dao::call_stored_procedure($sql,$data);
            
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
}
