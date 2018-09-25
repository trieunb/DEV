<?php
/**
*|--------------------------------------------------------------------------
*| Stocking
*|--------------------------------------------------------------------------
*| Package          : Stocking
*| @author          : TuanNK - ANS818 - tuannk@ans-asia.com
*| @created date    : 2017/01/08
*| @updater         : DaoNX - ANS804 - daonx@ans-asia.com
*| @created date    : 2018/05/04
*| Description      : 
*/
namespace Modules\Stocking\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Paginator,Dao;

class StockingDetailController extends Controller
{
    /**
     * list accept
     * -----------------------------------------------
     * @author      :   ANS806 - 2017/01/08 - create
     * @param       :
     * @return      :   mixed
     * @access      :   public
     * @see         :   remark
     */
    public function getSearch() {
        try {
            $purchase_detail_amt_round_div = 1;
            
            $paginator                     = new Paginator(0, 0, 0, 0);
            $paginate                      = $paginator->show(1, 'paginate');
            $fillter                       = $paginator->fillter();
            
            //get ctl_val1
            $sql                           = "SPC_038_STOCKING_DETAIL_INQ1"; 
            $dataCtl                       = Dao::call_stored_procedure($sql);

            if(!empty($dataCtl)) {
               $purchase_detail_amt_round_div  = $dataCtl[0][0]['purchase_detail_amt_round_div'];
            }

            //get tax_rate
            $paramTax   = ['date' => date('Y-m-d')];
            $sql        = "SPC_038_STOCKING_DETAIL_INQ2";
            $dataTax    = Dao::call_stored_procedure($sql,$paramTax);

            $tax_rate   = !empty($dataTax[0][0]['tax_rate']) ? $dataTax[0][0]['tax_rate'] : 0;

            return view('stocking::StockingDetail.index',compact('paginate', 'fillter', 'purchase_detail_amt_round_div', 'tax_rate'));
        } catch (\Exception $e) {
            return response()->json(array('response'=>false));
        }
    }

    /**
     * search list stocking detail
     * -----------------------------------------------
     * @author      :   ANS810 - 2018/05/04 - create
     * @param       :
     * @return      :   mixed
     * @access      :   public
     * @see         :   remark
     */
    public function postSearch(Request $request){
        try {
            $param     = $request->all();
            
            $sql       = "SPC_038_STOCKING_DETAIL_FND1";
            $data      = Dao::call_stored_procedure($sql,$param,true);
            
            $dataLists = isset($data[0]) ? $data[0] : array();
            $paginator = new Paginator($data[1][0]['pageMax'], $data[1][0]['pagesize'], $data[1][0]['page'], $data[1][0]['totalRecord']);
            $paginate  = $paginator->show($data[1][0]['page'], 'paginate');
            $fillter   = $paginator->fillter();
            
            $round     = isset($data[2]) ? $data[2] : [];
            
            $html      = view('stocking::StockingDetail.list',compact('dataLists','paginate', 'fillter'))->render();            

            //return data
            return response()->json(array(
                'response' => true,
                'html'     => $html,
                'round'    => $round,
            ));
        } catch (\Exception $e) {
            return response()->json(array(
                'response'  => false,
                'error'     => $e->getMessage()
            ));
        }        
    }

    /**
     * Create/Update Stocking Detail
     * -----------------------------------------------
     * @author      :   ANS810 - 2018/05/04 - create
     * @param       :
     * @return      :   mixed
     * @access      :   public
     * @see         :   remark
     */
    public function postSave(Request $request) {
        try {
            //get data from client
            $data                = $request->all();
            $data['data_save']   =  json_encode($data['data_save']);//parse json to string
            
            $data['cre_user_cd'] = \GetUserInfo::getInfo('user_cd');
            $data['cre_prg_cd']  = '038_stocking-detail';
            $data['cre_ip']      = \GetUserInfo::getInfo('user_ip');
            
            $sql                 = "SPC_038_STOCKING_DETAIL_ACT1";
            $result              = Dao::call_stored_procedure($sql, $data);

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
                ));
            }
        } catch (\Exception $e) {
            return response()->json(array(
                    'response'=> $e->getMessage()
            ));
        }        
    }
}
