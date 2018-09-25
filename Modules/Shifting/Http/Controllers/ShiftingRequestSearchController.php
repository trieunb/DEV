<?php
/**
*|--------------------------------------------------------------------------
*| Shifting Request
*|--------------------------------------------------------------------------
*| Package       : Shifting Request
*| @author       : Trieunb - ANS806 - trieunb@ans-asia.com
*| @created date : 2017/01/08
*| Description   : 
*/
namespace Modules\shifting\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller;
use Modules\Common\Http\Controllers\ComboboxController as Combobox;
use Session, Paginator, Dao;

class ShiftingRequestSearchController extends Controller {
    /**
     * list shifting
     * -----------------------------------------------
     * @author      :   ANS810 - 2017/03/28 - create
     * @param       :
     * @return      :   mixed
     * @access      :   public
     * @see         :   remark
     */
    public function getSearch() {
        try {
            //get library
            $move_status_div    = Combobox::libraryCode('move_status_div');
            $paginator          = new Paginator(0, 0, 0, 0);
            $paginate   = $paginator->show(1, 'paginate');
            $fillter    = $paginator->fillter();
            return view('shifting::ShiftingRequestSearch.index',compact('paginate', 'fillter', 'move_status_div'));
        } catch (\Exception $e) {
            return response()->json(array('response'=>false));
        }
    }

    /**
     * search list shifing request
     * -----------------------------------------------
     * @author      :   ANS810 - 2018/03/28 - create
     * @param       :
     * @return      :   mixed
     * @access      :   public
     * @see         :   remark
     */
    public function postSearch(Request $request){
        try {
            $param                = $request->all();
            
            $sql                  = "SPC_042_SHIFTING_REQUEST_LIST_SEARCH_FND1";
            $data                 = Dao::call_stored_procedure($sql,$param,true);
            
            $mShiftingRequestList = isset($data[0]) ? $data[0] : array();
            $paginator            = new Paginator($data[1][0]['pageMax'], $data[1][0]['pagesize'], $data[1][0]['page'], $data[1][0]['totalRecord']);
            $paginate             = $paginator->show($data[1][0]['page'], 'paginate');
            $fillter              = $paginator->fillter();
            
            $html                 = view('shifting::ShiftingRequestSearch.list',compact('mShiftingRequestList','paginate', 'fillter'))->render();            

            //return data
            return response()->json(array(
                'response'      => true,
                'html'          => $html
            ));
        } catch (\Exception $e) {
            return response()->json(array(
                'response'  => false,
                'error'     => $e->getMessage()
            ));
        }        
    }

    /**
     * approve shifting search
     * -----------------------------------------------
     * @author      :   ANS804 - 2018/03/26 - create
     * @param       :
     * @return      :   mixed
     * @access      :   public
     * @see         :   remark
     */
    public function postApproved(Request $request) {
        try {
            //get data from client
            $data                  = $request->all();
            $data['data_approved'] =  json_encode($data['data_approved']);//parse json to string

            $data['cre_user_cd']   = \GetUserInfo::getInfo('user_cd');
            $data['cre_prg_cd']    = '042_shifting-request-search';
            $data['cre_ip']        = \GetUserInfo::getInfo('user_ip');
            
            //call stored
            $sql                   = "SPC_042_SHIFTING_REQUEST_LIST_SEARCH_ACT1";
            $result                = Dao::call_stored_procedure($sql, $data);
            
            //return result to client
            if (empty($result[0])) {
                return response()->json(array(
                    'response'    => true,
                    'error_cd'    => $result[1][0]['error_cd'],
                ));
            } else {
                return response()->json(array(
                    'response' => false,
                    'error'    => $result[0][0]['Message'],
                    'error_cd' => $result[1][0]['error_cd']
                ));
            }
        } catch(\Exception $e) {
            return response()->json(array(
                    'response'=> $e->getMessage()
            ));
        }
    }
}
