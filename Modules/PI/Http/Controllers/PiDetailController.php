<?php
/**
*|--------------------------------------------------------------------------
*| PI
*|--------------------------------------------------------------------------
*| Package       : PI  
*| @author       : Trieunb - ANS806 - trieunb@ans-asia.com
*| @created date : 2017/01/08
*| Description   : 
*/
namespace Modules\Pi\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Session, DB, Dao, Button;
use Modules\Common\Http\Controllers\ComboboxController as Combobox;
use Modules\Common\Http\Controllers\CsvController as csv;

class PiDetailController extends Controller
{
    /**
    * get detail estimate
    * -----------------------------------------------
    * @author      :   ANS806 - 2017/01/08 - create
    * @param       :
    * @return      :   mixed
    * @access      :   public
    * @see         :   remark
    */
    public function getDetail(Request $request)
    {
        try {
            //get library
            $port_country_div       = Combobox::libraryCode('port_country_div');
            $port_city_div          = Combobox::libraryCode('port_city_div');
            $shipment_div           = Combobox::libraryCode('shipment_div');
            $currency_div           = Combobox::libraryCode('currency_div');
            $trade_terms_div        = Combobox::libraryCode('trade_terms_div');
            $payment_conditions_div = Combobox::libraryCode('payment_conditions_div');
            $unit_q_div             = Combobox::libraryCode('unit_q_div');
            $unit_w_div             = Combobox::libraryCode('unit_w_div');
            $unit_m_div             = Combobox::libraryCode('unit_m_div');
            $sales_detail_div       = Combobox::libraryCode('sales_detail_div');
            $bank_div               = Combobox::libraryCode('bank_div');
            //dd($shipment_div);
            $mode       = 'U';
            $from       = 'PiDetail';
            $pi_no      = '';
            $is_new     = 'false';
            if (Session::has('SELF')) {
                Session::forget('SELF');
                Session::forget('PiDetail');
                $mode       = 'U';
            } else {
                if (Session::has('PiDetail')) {
                    $param = Session::get('PiDetail');
                    if (!empty($param)) {
                        $mode  = $param['mode'];
                        $from  = $param['from'];
                         $is_new = (isset($param['is_new']) && !empty($param['is_new']))  ? $param['is_new'] : 'false';
                        if (isset($param['pi_no']) && !empty($param['pi_no'])) {
                            $pi_no          = $param['pi_no'];
                        }
                    }
                }
            }
            $cre_user_cd    = \GetUserInfo::getInfo('user_cd');
            $cre_user_nm    = \GetUserInfo::getInfo('user_nm_j');
            return view('pi::PiDetail.detail', compact('pi_no', 'mode', 'from', 'cre_user_cd', 'cre_user_nm',
                                            'is_new','port_country_div','port_city_div','shipment_div','currency_div'
                                        ,'trade_terms_div','payment_conditions_div','unit_q_div','unit_w_div','unit_m_div','sales_detail_div','bank_div'));
        } catch (\Exception $e) {
            return response()->json(array('response' => false));
        }
    }
    /**
    * refer pi detail
    * -----------------------------------------------
    * @author      :   ANS806 - 2017/12/11 - create
    * @param       :
    * @return      :   mixed
    * @access      :   public
    * @see         :   remark
    */
    public function referPiDetail(Request $request) 
    {
        try {
            $unit_q_div         = Combobox::libraryCode('unit_q_div');
            $unit_w_div         = Combobox::libraryCode('unit_w_div');
            $unit_m_div         = Combobox::libraryCode('unit_m_div');
            $sales_detail_div   = Combobox::libraryCode('sales_detail_div');

            $param      =   ['pi_no' => $request->pi_no];
            $mode       =   $request->mode;

            $sql        =   "SPC_001_PI_DETAIL_INQ1"; 
            $data       =   Dao::call_stored_procedure($sql, $param, true);
            
            $pi_h       =   isset($data[0]) ? $data[0] : array();
            $pi_d       =   isset($data[1]) ? $data[1] : array();

            if (!empty($pi_h)) {
                $pi_status       =   $data[0][0]['pi_status_div'];
            }
            switch ($pi_status) {
                case "10":
                    $status   =   'R';
                    break;
                case "20":
                    $status   =   'A';
                    break;
                case "30":
                    $status   =   'O';
                    break;
                case "90":
                    $status   =   'L';
                    break;
                default:
                    $status   =   'R';
                    break;
            }
            $header_html    = view('layouts._operator_info',compact('pi_h'))->render();
            $button         =   Button::showButtonServer(array('btn-back', 'btn-save', 
                                                            'btn-delete', 'btn-print', 
                                                            'btn-approve', 'btn-cancel-approve', 
                                                            'btn-copy'), ($mode != 'I') ? $status : $mode);
            $html_pi_d  =  view('pi::PiDetail.table_pi',compact('pi_d','unit_q_div','unit_w_div','unit_m_div','sales_detail_div'))->render();
            if (isset($data) && !empty($data)) {
                return response()->json([
                                'response'      =>  true,
                                'html_pi_d'     =>  $html_pi_d,
                                'pi_h'          =>  $pi_h[0],
                                'button'        =>  $button,
                                'mode'          =>  $mode,
                                'status'        =>  $status,
                            ]);
            } else {
                return response()->json([
                                'response'      =>  false,
                                'html_pi_d'     =>  '',
                                'pi_h'          =>  '',
                                'button'        =>  $button,
                                'mode'          =>  $mode,
                                'status'        =>  $status,
                            ]);
            }
            
        } catch (\Exception $e) {
            return response()->json(['response' => false]);
        }
    }
    /**
    * refer cust m client
    * -----------------------------------------------
    * @author      :   ANS806 - 2017/12/07 - create
    * @param       :
    * @return      :   mixed
    * @access      :   public
    * @see         :   remark
    */
    public function referSuppliers(Request $request)
    {
        try {
            $param  =   $request->all(); 
            $sql    =   "SPC_001_PI_DETAIL_INQ2"; 
            $data   =   Dao::call_stored_procedure($sql, $param, true);
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
    * refer product pi detail
    * -----------------------------------------------
    * @author      :   ANS806 - 2017/12/07 - create
    * @param       :
    * @return      :   mixed
    * @access      :   public
    * @see         :   remark
    */
    public function referProduct(Request $request)
    {
        try {
            $param  =   $request->all(); 
            $sql    =   "SPC_001_PI_DETAIL_INQ3"; 
            $data   =   Dao::call_stored_procedure($sql, $param, true);
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
    * Create/Update Pi Information
    * -----------------------------------------------
    * @author      :   ANS806 - 2017/11/15 - create
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
            $data['t_pi_d']        =  json_encode($data['t_pi_d']);//parse json to string
            $data['cre_user_cd']    = \GetUserInfo::getInfo('user_cd');
            $data['cre_prg_cd']     = '001_pi-detail';
            $data['cre_ip']         = \GetUserInfo::getInfo('user_ip');
            //call stored
            $sql                    = "SPC_001_PI_DETAIL_ACT1";
            $result                 = Dao::call_stored_procedure($sql, $data);
            // return $result[0][0]['Data']; die;
            if (isset($result[0][0]['Data']) && $result[0][0]['Data'] == 'EXCEPTION') {
                return response()->json(array(
                    'response'      => false,
                    'error'         => $result[0][0]['Message'],
                ));
            } else {
                $error_list             = isset($result[3]) ? $result[3] : array();
                //return result to client
                if (empty($result[0])) {
                    return response()->json(array(
                        'response'      => true,
                        'error_cd'      => $result[1][0]['error_cd'],
                        'pi_no'         => $result[1][0]['pi_no'],
                        'pi_status'     => $result[1][0]['pi_status'],
                        'errors_item'   => $result[2][0],
                        'error_list'    => $error_list
                    ));
                } else {
                    return response()->json(array(
                        'response'      => false,
                        'error'         => $result[0][0]['Message'],
                        'error_cd'      => $result[1][0]['error_cd'],
                        'errors_item'   => $result[2][0],
                        'error_list'    => $error_list
                    ));
                }
            }
        } catch (\Exception $e) {
            return response()->json(array(
                    'response'=> $e->getMessage()
            ));
        }        
    }
    /**
    * Delete pi detail
    * -----------------------------------------------
    * @author      :   ANS806 - 2017/12/05 - create
    * @param       :
    * @return      :   mixed
    * @access      :   public
    * @see         :   remark
    */
    public function deletePi(Request $request) {
        try {
            //get data from client
            $data                   = $request->all();
            $data['cre_user_cd']    = \GetUserInfo::getInfo('user_cd');
            $data['cre_prg_cd']     = '001_pi-detail';
            $data['cre_ip']         = \GetUserInfo::getInfo('user_ip');
            //call stored
            $sql                    = "SPC_001_PI_DETAIL_ACT2";
            $result                 = Dao::call_stored_procedure($sql, $data);
            //return result to client
            if (empty($result[0])) {
                return response()->json(array(
                    'response'      => true,
                    'error_cd'      => $result[1][0]['error_cd'],
                    'pi_no'         => $result[1][0]['pi_no'],
                    'pi_status'     => $result[1][0]['pi_status']
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
    /**
    * approve pi detail
    * -----------------------------------------------
    * @author      :   ANS806 - 2017/12/05 - create
    * @param       :
    * @return      :   mixed
    * @access      :   public
    * @see         :   remark
    */
    public function approvePi(Request $request) {
        try {
            //get data from client
            $data                   = $request->all();
            $data['cre_user_cd']    = \GetUserInfo::getInfo('user_cd');
            $data['cre_prg_cd']     = '001_pi-detail';
            $data['cre_ip']         = \GetUserInfo::getInfo('user_ip');
            //call stored
            $sql                    = "SPC_001_PI_DETAIL_ACT3";
            $result                 = Dao::call_stored_procedure($sql, $data);
            //return result to client
            if (empty($result[0])) {
                return response()->json(array(
                    'response'      => true,
                    'error_cd'      => $result[1][0]['error_cd'],
                    'pi_no'         => $result[1][0]['pi_no'],
                    'pi_status'     => $result[1][0]['pi_status']
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
    /**
    * approve cancel pi detail
    * -----------------------------------------------
    * @author      :   ANS806 - 2017/12/05 - create
    * @param       :
    * @return      :   mixed
    * @access      :   public
    * @see         :   remark
    */
    public function cancelApprovePi(Request $request) {
        try {
            //get data from client
            $data                   = $request->all();
            $data['cre_user_cd']    = \GetUserInfo::getInfo('user_cd');
            $data['cre_prg_cd']     = '001_pi-detail';
            $data['cre_ip']         = \GetUserInfo::getInfo('user_ip');
            //call stored
            $sql                    = "SPC_001_PI_DETAIL_ACT4";
            $result                 = Dao::call_stored_procedure($sql, $data);
            //return result to client
            if (empty($result[0])) {
                return response()->json(array(
                    'response'      => true,
                    'error_cd'      => $result[1][0]['error_cd'],
                    'pi_no'         => $result[1][0]['pi_no'],
                    'pi_status'     => $result[1][0]['pi_status']
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
    /**
    * post Print pi detail
    * -----------------------------------------------
    * @author      :   ANS806 - 2017/12/05 - create
    * @param       :
    * @return      :   mixed
    * @access      :   public
    * @see         :   remark
    */
    public function postPrint(Request $request) {
        try {
            $param      =   ['pi_no' => $request->pi_no];
            $sql        =   "SPC_001_PI_DETAIL_INQ1"; 
            $data       =   Dao::call_stored_procedure($sql, $param, true);
            
            $pi_h       =   isset($data[0]) ? $data[0][0] : array();
            $pi_d       =   isset($data[1]) ? $data[1] : array();
            
            $file_name  =   date("YmdHis");
            return \Excel::create($file_name, function($excel) use ($pi_h, $pi_d, $file_name) {
                $excel->sheet($file_name, function($sheet) use ($pi_h, $pi_d) {
                    $sheet->loadView('report.pi.pi_detail', [
                                                    'pi_h'  =>  $pi_h, 
                                                    'pi_d'  =>  $pi_d])
                    ->mergeCells('F7:G14')
                    ->mergeCells('C10:E14')
                    ->mergeCells('C5:E9');
                });

            })->download('xlsx');
        } catch (\Exception $e) {
            return response()->json(array('response' => false));
        }
    }

    public function piImportCSV(Request $request)
    {
        try {
            $file = $request->file('file');

            $fileName           = $file->getClientOriginalName();
            $destinationPath    = UPLOAD_CSV;
            $file->move($destinationPath, $fileName);
            $filePath           = $destinationPath . "/" . $fileName;

            $data               = csv::inputCSV($filePath);
            //session::set('data_csv', $data);
            return response()->json([
                    'response'  => true,
                    'data'      =>  $data]
                );
        } catch(\Exception $e) {
            return response()->json(array('response' => false, 'status' => 'ng'));
        }
    }

    public function piDownloadCSV(Request $request)
    {
        try {
            $csv    = new csv;
            $data   = [
                ['PI No','受注 No','見積日,取引先コード','取引先名'],
                ['RT-20171015985','受注 No','2017/10/10','取引先コード','取引先名'],
                ['RT-20171015986','受注 No','2017/10/11','取引先コード','取引先名'],
                ['RT-20171015987','受注 No','2017/10/12','取引先コード','取引先名'],
                ['RT-20171015988','受注 No','2017/10/13','取引先コード','取引先名']
            ];
            $file   = $csv->outputCSV($data, 'filename_');
            return response()->json([
                    'response'  =>  true,
                    'file'      =>  $file]
                );
        } catch(\Exception $e) {
            return response()->json(array('response' => false, 'status' => 'ng'));
        }
    }
}
