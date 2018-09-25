<?php
/**
*|--------------------------------------------------------------------------
*| Accpet
*|--------------------------------------------------------------------------
*| Package       : Accpet  
*| @author       : DaoNX - ANS804 - daonx@ans-asia.com
*| @created date : 2018/01/22
*| Description   : 
*/
namespace Modules\Accept\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Common\Http\Controllers\ComboboxController as Combobox;
use Paginator, Dao;

class AcceptSearchController extends Controller
{
    /**
     * list accept
     * -----------------------------------------------
     * @author      :   ANS804 - 2018/01/22 - create
     * @param       :
     * @return      :   mixed
     * @access      :   public
     * @see         :   remark
     */
    public function getSearch() {
        try {
            $rcv_status_div       = Combobox::libraryCode('rcv_status_div');

            $paginator  = new Paginator(0,0,0,0);
            $paginate   = $paginator->show(1, 'paginate');
            $fillter    = $paginator->fillter();
            return view('accept::AcceptSearch.index',compact('paginate', 'fillter', 'rcv_status_div'));
        } catch (\Exception $e) {
            return response()->json(array('response'=>false));
        }
    }
    /**
     * search accept list
     * -----------------------------------------------
     * @author      :   ANS804 - 2018/01/22 - create
     * @param       :
     * @return      :   mixed
     * @access      :   public
     * @see         :   remark
     */
    public function postSearch(Request $request) {
        try {
            $param     = $request->all();
            $sql       = "SPC_006_ACCEPT_SEARCH_FND1";
            $data      = Dao::call_stored_procedure($sql, $param, true);
            
            $rcvList   = isset($data[0]) ? $data[0] : array();
            $paginator = new Paginator($data[1][0]['pageMax'], $data[1][0]['pagesize'], $data[1][0]['page'], $data[1][0]['totalRecord']);
            $paginate  = $paginator->show($data[1][0]['page'], 'paginate');
            $fillter   = $paginator->fillter();
            
            $html      = view('accept::AcceptSearch.list',compact('rcvList','paginate', 'fillter'))->render();
            return response()->json(array(
                'response'      => true,
                'html'          => $html
            ));
        } catch (\Exception $e) {
            return response()->json(array(
                                'response'  =>  false,
                                'error'     =>  $e->getMessage()
                            ));
        }
    }
    /**
     * approve accept detail
     * -----------------------------------------------
     * @author      :   ANS806 - 2017/12/05 - create
     * @param       :
     * @return      :   mixed
     * @access      :   public
     * @see         :   remark
     */
    public function approveAcceptList(Request $request) {
        try {
            //get data from client
            $data                = $request->all();
            $data['rcv_list']    =  json_encode($data['rcv_list']);
            $data['cre_user_cd'] = \GetUserInfo::getInfo('user_cd');
            $data['cre_prg_cd']  = '006_accept-search';
            $data['cre_ip']      = \GetUserInfo::getInfo('user_ip');
            //call stored
            $sql                 = "SPC_006_ACCEPT_SEARCH_ACT1";
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
