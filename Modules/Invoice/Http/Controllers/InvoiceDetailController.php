<?php

namespace Modules\Invoice\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Common\Http\Controllers\ComboboxController as Combobox;
use Session, Dao, Button;

class InvoiceDetailController extends Controller
{
	/**
	 * Show the form for creating a new resource.
	 * @return Response
	 */
	public function getDetail()
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
            $storage_manager_div    = Combobox::libraryCode('storage_manager_div');

			$mode  = 'U';
            $from  = 'InvoiceDetail';
            if (Session::has('SELF')) {
                Session::forget('SELF');
                Session::forget('InvoiceDetail');
            } else {
                if (Session::has('InvoiceDetail')) {
                    $param = Session::get('InvoiceDetail');
                    if (!empty($param)) {
                        $mode  = $param['mode'];
                        $from  = $param['from'];
                        if (isset($param['inv_no']) && !empty($param['inv_no'])) {
                            $inv_no          = $param['inv_no'];
                        }
                    }
                }
            }
            $cre_user_cd    = \GetUserInfo::getInfo('user_cd');
            $cre_user_nm    = \GetUserInfo::getInfo('user_nm_j');
            return view('invoice::InvoiceDetail.detail', compact('inv_no', 'mode', 'from', 'cre_user_cd', 'cre_user_nm'
                                                            ,'port_country_div','port_city_div','shipment_div','currency_div'
                                                            ,'trade_terms_div','payment_conditions_div','unit_q_div','unit_w_div'
                                                            ,'unit_m_div','sales_detail_div','storage_manager_div'));
		} catch (\Exception $e) {
            return response()->json(array('response' => false));
        }
	}
    /**
     * refer accept detail
     * -----------------------------------------------
     * @author      :   ANS804 - 2018/01/05 - create
     * @param       :
     * @return      :   mixed
     * @access      :   public
     * @see         :   remark
     */
    public function referFwdDetail(Request $request) {
        try {
            $unit_q_div         = Combobox::libraryCode('unit_q_div');
            $unit_w_div         = Combobox::libraryCode('unit_w_div');
            $unit_m_div         = Combobox::libraryCode('unit_m_div');
            $sales_detail_div   = Combobox::libraryCode('sales_detail_div');

            $param = $request->all();

            $sql   = "SPC_014_INVOICE_DETAIL_INQ2"; 
            $data  = Dao::call_stored_procedure($sql, $param, true);
            // return $data;
            $fwd_h = isset($data[0]) ? $data[0] : array();
            $inv_d = isset($data[1]) ? $data[1] : array();
            
            $html_fwd_d  =  view('invoice::InvoiceDetail.table_invoice', compact('inv_d','unit_q_div','unit_w_div','unit_m_div','sales_detail_div'))->render();
            if (isset($data) && !empty($data)) {
                return response()->json([
                                'response'   =>  true,
                                'html_fwd_d' =>  $html_fwd_d,
                                'fwd_h'      =>  $fwd_h[0],
                            ]);
            } else {
                return response()->json([
                                'response'   =>  false,
                                'html_fwd_d' =>  '',
                                'fwd_h'      =>  '',
                            ]);
            }
            
        } catch (\Exception $e) {
            return response()->json([
                        'response'  => $e->getMessage()
                    ]);
        }
    }

    /**
     * Create/Update Accept Information
     * -----------------------------------------------
     * @author      :   ANS804 - 2018/01/05 - create
     * @param       :
     * @return      :   mixed
     * @access      :   public
     * @see         :   remark
     */
    public function postSave(Request $request) {
        try {
            //get data from client
            $data                   =  $request->all();
            
            $data['t_invoice_d']    =  json_encode($data['t_invoice_d']);//parse json to string
            $data['t_carton_d']     =  json_encode($data['t_carton_d']);//parse json to string
            $data['cre_user_cd']    =  \GetUserInfo::getInfo('user_cd');
            $data['cre_prg_cd']     =  '014_invoice-detail';
            $data['cre_ip']         =  \GetUserInfo::getInfo('user_ip');
            // return count($data);
            $sql                    =  "SPC_014_INVOICE_DETAIL_ACT1";
            $result                 =  Dao::call_stored_procedure($sql, $data);
            // return $result;
            //return result to client
            if (empty($result[0])) {
                return response()->json(array(
                    'response'              => true,
                    'error_cd'              => $result[1][0]['error_cd'],
                    'inv_no'                => $result[1][0]['inv_no'],
                    'error_rcv_status'      => $result[1][0]['error_rcv_status'],
                    'error_fwd'             => $result[1][0]['error_fwd'],
                    'error_fwd_status'      => $result[1][0]['error_fwd_status'],
                    'errors_item'           => $result[2][0]
                ));
            } else {
                return response()->json(array(
                    'response'          => false,
                    'error'             => $result[0][0]['Message'],
                    'error_cd'          => $result[1][0]['error_cd'],
                    'errors_item'       => $result[2][0]
                ));
            }
        } catch (\Exception $e) {
            return response()->json(array(
                    'response'=> $e->getMessage()
            ));
        }        
    }

    /**
     * Create/Update Accept Information
     * -----------------------------------------------
     * @author      :   ANS804 - 2018/01/05 - create
     * @param       :
     * @return      :   mixed
     * @access      :   public
     * @see         :   remark
     */
    public function checkDeposit(Request $request) {
        try {
            //get data from client
            $data                   =  $request->all();
            // return count($data);
            $sql                    =  "SPC_014_INVOICE_DETAIL_INQ3";
            $result                 =  Dao::call_stored_procedure($sql, $data);
            return response()->json(array(
                    'response'             => true,
                    'warning'              => $result[0][0]['warning'],
                    'deposit_no'           => $result[0][0]['deposit_no'],
                ));
        } catch (\Exception $e) {
            return response()->json(array(
                    'response'=> $e->getMessage()
            ));
        }        
    }

    /**
     * refer invoice detail
     * -----------------------------------------------
     * @author      :   ANS831 - 2018/04/17 - create
     * @param       :
     * @return      :   mixed
     * @access      :   public
     * @see         :   remark
     */
    public function referInvoiceDetail(Request $request) {
        try {
            $unit_q_div         = Combobox::libraryCode('unit_q_div');
            $unit_w_div         = Combobox::libraryCode('unit_w_div');
            $unit_m_div         = Combobox::libraryCode('unit_m_div');
            $sales_detail_div   = Combobox::libraryCode('sales_detail_div');

            $param          = ['inv_no' => $request->inv_no];
            $mode           = $request->mode;
            $sql            = "SPC_014_INVOICE_DETAIL_INQ1"; 
            $data           = Dao::call_stored_procedure($sql, $param, true);
        
            $inv_h          = isset($data[0]) ? $data[0][0] : array();
            
            $inv_d          = isset($data[1]) ? $data[1] : array();
            $carrton_d      = isset($data[2]) ? $data[2] : array();

            $header_html    = view('layouts._operator_info',compact('fwd_h'))->render();
            $button         = Button::showButtonServer(array(   'btn-back', 
                                                                'btn-save', 
                                                                'btn-delete', 
                                                                'btn-invoice', 
                                                                'btn-delivery-note', 
                                                                'btn-print-packing', 
                                                                'btn-print-mark'
                                                            ), $mode);

            $html_inv_d     =  view('invoice::InvoiceDetail.table_invoice'  , compact('inv_d','unit_q_div','unit_w_div','unit_m_div','sales_detail_div'))->render();
            $html_carton_d  =  view('invoice::InvoiceDetail.table_carton'   , compact('carrton_d','unit_m_div','unit_w_div'))->render();

            if (isset($data) && !empty($data)) {
                return response()->json([
                                'response'      =>  true,
                                'html_inv_d'    =>  $html_inv_d,
                                'html_carton_d' =>  $html_carton_d,
                                'inv_h'         =>  $inv_h,
                                'button'        =>  $button,
                                'mode'          =>  $mode,
                            ]);
            } else {
                return response()->json([
                                'response'      =>  false,
                                'html_inv_d'    =>  '',
                                'html_carton_d' =>  '',
                                'inv_h'         =>  '',
                                'button'        =>  $button,
                                'mode'          =>  $mode,
                            ]);
            }
            
        } catch (\Exception $e) {
            return response()->json(['response' => false]);
        }
    }
    /**
     * Delete invoice detail
     * -----------------------------------------------
     * @author      :   ANS806 - 2018/01/05 - create
     * @param       :
     * @return      :   mixed
     * @access      :   public
     * @see         :   remark
     */
    public function deleteInvoice(Request $request) {
        try {
            //get data from client
            $data                   = $request->all();
            $data['cre_user_cd']    = \GetUserInfo::getInfo('user_cd');
            $data['cre_prg_cd']     = '014_invoice-detail';
            $data['cre_ip']         = \GetUserInfo::getInfo('user_ip');

            //call stored
            $sql                    = "SPC_014_INVOICE_DETAIL_ACT2";
            $result                 = Dao::call_stored_procedure($sql, $data);

            //return result to client
            if (empty($result[0])) {
                return response()->json(array(
                    'response'      => true,
                    'error_cd'      => $result[1][0]['error_cd'],
                    'deposit_no'    => $result[1][0]['deposit_no'],
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
