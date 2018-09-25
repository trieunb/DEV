<?php
/**
 *|--------------------------------------------------------------------------
 *| Check list
 *|--------------------------------------------------------------------------
 *| Package       : Check list  
 *| @author       : KhaDV - ANS831 - khadv@ans-asia.com
 *| @created date : 2017/02/01
 *| Description   : 
 */
namespace Modules\Popup\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Paginator, Dao;

class CheckListController extends Controller
{
    /**
     * list 
     * -----------------------------------------------
     * @author      :   ANS831 - 2018/02/01 - create
     * @param       :
     * @return      :   mixed
     * @access      :   public
     * @see         :   remark
     */
    public function getSearch() {
        try {
            $paginator  = new Paginator(0, 0, 0, 0);
            $paginate   = $paginator->show(1, 'paginate');
            $fillter    = $paginator->fillter();
            $sql        = "SPC_103_CHECK_LIST_INQ1"; 
            $data       = Dao::call_stored_procedure($sql);
            $checklist  =  isset($data[0]) ? $data[0] : '';
            return view('popup::CheckList.index',compact('paginate', 'fillter', 'checklist'));
        } catch (\Exception $e) {
            return response()->json(array('response'=>false));
        }
    }
}
