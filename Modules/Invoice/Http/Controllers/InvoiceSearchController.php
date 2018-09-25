<?php
/**
*|--------------------------------------------------------------------------
*| Invoice
*|--------------------------------------------------------------------------
*| Package       : Invoice  
*| @author       : TuanNK - ANS818 - tuannk@ans-asia.com
*| @created date : 2017/02/08
*| Description   : 
*/
namespace Modules\Invoice\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Common\Http\Controllers\ComboboxController as Combobox;
use Paginator, Dao;

class InvoiceSearchController extends Controller
{
    /**
    * list Invoice
    * -----------------------------------------------
    * @author      :   ANS806 - 2017/02/08 - create
    * @param       :
    * @return      :   mixed
    * @access      :   public
    * @see         :   remark
    */
    public function getSearch() {
        try {
            $inv_data_div    = Combobox::libraryCode('inv_data_div');

            $paginator  = new Paginator(0,0,0,0);
            $paginate   = $paginator->show(1, 'paginate');
            $fillter    = $paginator->fillter();
            return view('invoice::InvoiceSearch.index',compact('paginate', 'fillter', 'inv_data_div'));
        } catch (\Exception $e) {
            return response()->json(array('response'=>false));
        }
    }

    /**
     * search invoice list
     * -----------------------------------------------
     * @author      :   ANS804 - 2018/02/28 - create
     * @param       :
     * @return      :   mixed
     * @access      :   public
     * @see         :   remark
     */
    public function postSearch(Request $request) {
        try {
            $param       = $request->all();
            $sql         = "SPC_015_INVOICE_SEARCH_FND1";
            $data        = Dao::call_stored_procedure($sql, $param, true);
            
            $invoiceList = isset($data[0]) ? $data[0] : array();
            $paginator   = new Paginator($data[1][0]['pageMax'], $data[1][0]['pagesize'], $data[1][0]['page'], $data[1][0]['totalRecord']);
            $paginate    = $paginator->show($data[1][0]['page'], 'paginate');
            $fillter     = $paginator->fillter();
            
            $html        = view('invoice::InvoiceSearch.list',compact('invoiceList','paginate', 'fillter'))->render();
            return response()->json(array(
                'response' => true,
                'html'     => $html
            ));
        } catch (\Exception $e) {
            return response()->json(array(
                                'response'  =>  false,
                                'error'     =>  $e->getMessage()
                            ));
        }
    }
}
