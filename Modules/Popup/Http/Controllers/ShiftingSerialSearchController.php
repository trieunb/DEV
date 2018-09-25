<?php
/**
 *|--------------------------------------------------------------------------
 *| Shifting
 *|--------------------------------------------------------------------------
 *| Package       : Shifting  
 *| @author       : TuanNK - ANS818 - tuannk@ans-asia.com
 *| @updater      : DaoNX - ANS804 - daonx@ans-asia.com
 *| @created date : 2017/01/08
 *| @updater date : 2018/03/28
 *| Description   : 
 */
namespace Modules\Popup\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Paginator, Dao;

class ShiftingSerialSearchController extends Controller
{
    /**
     * list shifting
     * -----------------------------------------------
     * @author      :   ANS804 - 2018/03/28 - create
     * @param       :
     * @return      :   mixed
     * @access      :   public
     * @see         :   remark
     */
    public function getSearch(Request $request) {
        try {
            $id         = '';
            $id         = $request->get('id');
            $paginator  = new Paginator(0, 0, 0, 0);
            $paginate   = $paginator->show(1, 'paginate');
            $fillter    = $paginator->fillter();
            return view('popup::ShiftingSerialSearch.index',compact('paginate', 'fillter', 'id'));
        } catch (\Exception $e) {
            return response()->json(array('response'=>false));
        }
    }
    /**
     * search shifting serial
     * -----------------------------------------------
     * @author      :   ANS804 - 2018/03/28 - create
     * @param       :
     * @return      :   mixed
     * @access      :   public
     * @see         :   remark
     */
    public function postItemRequested(Request $request) {
        try {
            $param = $request->all();
            $count = 0;
            
            $sql   = "SPC_104_SHIFTING_SERIAL_SEARCH_FND2";
            $data  = Dao::call_stored_procedure($sql, $param, true);
            $List  = isset($data[0]) ? $data[0] : array();
            
            $count = count($List);
            
            $html  = view('popup::ShiftingSerialSearch.list',compact('List'))->render();

            return response()->json(array(
                'response' => true,
                'html'     => $html,
                'data'     => $List,
                'count'    => $count
            ));
        } catch (\Exception $e) {
            return response()->json(array(
                                'response'  => false,
                                'error'     => $e->getMessage()
                            ));
        }
    }
    /**
     * search shifting serial
     * -----------------------------------------------
     * @author      :   ANS804 - 2018/03/28 - create
     * @param       :
     * @return      :   mixed
     * @access      :   public
     * @see         :   remark
     */
    public function postSearch(Request $request) {
        try {
            $param = $request->all();
            $sql   = "SPC_104_SHIFTING_SERIAL_SEARCH_FND1";
            
            $data  = Dao::call_stored_procedure($sql, $param, true);
            $List  = isset($data[0]) ? $data[0] : array();
            
            $html  = view('popup::ShiftingSerialSearch.list',compact('List'))->render();
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
}
