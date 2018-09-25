<?php
/**
*|--------------------------------------------------------------------------
*| Oversea Document
*|--------------------------------------------------------------------------
*| Package       : Oversea Document
*| @author       : TuanNK - ANS818 - tuannk@ans-asia.com
*| @created date : 2017/01/08
*| Description   : 
*/
namespace Modules\OverseaDocument\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller;
use Modules\Common\Http\Controllers\ComboboxController as Combobox;
use Session, Paginator, Dao;

class PackingListController extends Controller
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
    public function getSearch()
    {
        try {
            //get library
            $done_div   = Combobox::libraryCode('done_div');
            $paginator  = new Paginator(0, 0, 0, 0);
            $paginate   = $paginator->show(1, 'paginate');
            $fillter    = $paginator->fillter();
            return view('overseadocument::PackingList.index',compact('paginate', 'fillter', 'done_div'));
        } catch (\Exception $e) {
            return response()->json(array('response'=>false));
        }
    }

    /**
    * search packing list
    * -----------------------------------------------
    * @author      :   ANS810 - 2018/02/27 - create
    * @param       :
    * @return      :   mixed
    * @access      :   public
    * @see         :   remark
    */
    public function postSearch(Request $request){
        try{
            $param         = $request->all();

            $sql           = "SPC_017_PACKING_LIST_SEARCH_FND1";

            $data          = Dao::call_stored_procedure($sql,$param,true);

            $mPackingList  = isset($data[0]) ? $data[0] : array();

            $paginator     = new Paginator($data[1][0]['pageMax'], $data[1][0]['pagesize'], $data[1][0]['page'], $data[1][0]['totalRecord']);
            $paginate      = $paginator->show($data[1][0]['page'], 'paginate');
            $fillter       = $paginator->fillter();

            $html          = view('overseadocument::PackingList.list',compact('mPackingList','paginate', 'fillter'))->render();

            //return data
            return response()->json(array(
                'response'      => true,
                'html'          => $html
            ));
        }
        catch (\Exception $e) {
            return response()->json(array(
                'response'  => false,
                'error'     => $e->getMessage()
            ));
        }        
    }
}
