<?php

namespace Modules\Common\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Dao, Input, Session, DB;

class ComboboxController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function getComboboxData(Request $request)
    {
         try {
            // $nm_j = 'lib_val_nm_j';
            // $ab_j = 'lib_val_ab_j';
            // $nm_e = 'lib_val_nm_e';
            // $ab_e = 'lib_val_ab_e';
            /*if ($request->countryCode == 'JP') {
                $nm = 'lib_val_nm_j';
                $ab = 'lib_val_ab_j';
            }*/
            // $data = DB::table('s_lib_val')->where('lib_cd', $request->libCd)
            //                             ->select('lib_cd', 'lib_val_cd', $nm_j, $ab_j, $nm_e, $ab_e, 'disp_order', 'lib_val_ctl1', 'lib_val_ctl2')
            //                             ->orderBy('disp_order')
            //                             ->orderBy('lib_val_cd')
            //                             ->get();

            $param_store        = ['lib_cd' => $request->libCd];
            $sql                = "SPC_COM_GET_COMBOBOX";
            $data               = Dao::call_stored_procedure($sql, $param_store);
            $result             = $data[0];
            if (isset($result[0]['Data']) && $result[0]['Data'] == 'EXCEPTION') {
                $result  = null;
            } 
            return response()->json(array('response' => true, 'data' => $result));
        } catch(\Exception $e){
            return response()->json(array('response' => false, 'status' => 'ng'));
        }
    }
    /*
    * get library code
    * -----------------------------------------------
    * @author      :   ANS798 - 2016/01/05 - create
    * @param       :
    * @return      :
    * @access      :   public
    * @see         :   remark
    */
    public static function libraryCode($name_type = ''){
        if ($name_type !='') {

            $param      = array( $name_type );              //param pass to stored
            $sql        = "SPC_COM_GET_COMBOBOX";           //name stored
            $result     = Dao::call_stored_procedure( $sql , $param);
            if (isset($result[0])) {
                return $result[0];
            }else{
                return null;
            }
            
        }
    }
}
