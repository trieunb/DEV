<?php
/*
|--------------------------------------------------------------------------
| Common Refer 
|--------------------------------------------------------------------------
| Package       : Apel  
| @author       : ANS796 - tuannt@ans-asia.com
| @created date : 2017/12/12
|
*/
namespace Modules\Common\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Dao,Input,Session;
class ReferController extends Controller
{
    /**
    * refer product information
    * -----------------------------------------------
    * @author      :   ANS796 - 2017/12/12 - create
    * @param       :    
    * @return      :
    * @access      :   public
    * @see         :   remark
    */
    public function postReferProductCd(){
        try{
            $param  = array(Input::get('product_cd'));
            $sql    = 'SPC_REFER_PRODUCT_CD';
            $result = Dao::call_stored_procedure($sql, $param, true);
            if (!empty($result)) {
                return response()->json(array(
                    'response'  =>  'true',
                    'data'      =>  $result[0][0])
                );
            }else{
                return response()->json(array('response'=>'false'));
            }
            
        }catch(Exception $e){
            return response()->json(array('response'=>'false'));
        }
    }
    /**
    * refer client information
    * -----------------------------------------------
    * @author      :   ANS796 - 2017/12/12 - create
    * @param       :    
    * @return      :
    * @access      :   public
    * @see         :   remark
    */
    public function postReferClientCd(){
        try{
            $param  = array(Input::get('client_cd'));
            $sql    = 'SPC_REFER_CLIENT_CD';
            $result = Dao::call_stored_procedure($sql, $param, true);
            if (!empty($result)) {
                return response()->json(array(
                    'response'  =>  'true',
                    'data'      =>  $result[0][0])
                );
            }else{
                return response()->json(array('response'=>'false'));
            }
            
        }catch(Exception $e){
            return response()->json(array('response'=>'false'));
        }
    }
     /**
    * get tax rate
    *
    * @author      :   ANS806 - 2017/11/29 - create
    * @param       :   null
    * @return      :   null
    * @access      :   public
    * @see         :
    */
    public function getTaxRate(Request $request)
    {
        try {
            //get data from client
            $data                   = $request->all();
            //call stored
            $sql                    = "SPC_GET_TAX_RATE";
            $result                 = Dao::call_stored_procedure($sql, $data, true);
            //return result to client
            if (isset($result[0][0]) && !empty($result[0][0])) {
                return response()->json(array(
                    'response'      => true,
                    'tax_rate'      => $result[0][0]['tax_rate']
                ));
            } else {
                return response()->json(array(
                    'response'      => false
                ));
            }
        } catch(\Exception $e){
            return response()->json(array('response' => false, 'status' => 'ng'));
        }
    }
    /**
    * refer user
    * -----------------------------------------------
    * @author      :   ANS806 - 2017/12/07 - create
    * @param       :
    * @return      :   mixed
    * @access      :   public
    * @see         :   remark
    */
    public function referUser(Request $request)
    {
        try {
            $param  =   $request->all(); 
            $sql    =   "SPC_GET_USER"; 
            $data   =   Dao::call_stored_procedure($sql, $param, true);
            if (isset($data[0]) && !empty($data[0])) {
                return response()->json(array(
                    'response'      => true,
                    'data'          => $data[0][0]
                ));
            } else {
                return response()->json(array(
                    'response'      => false
                ));
            }
        } catch (\Exception $e) {
            return response()->json(array(
                    'response'=> $e->getMessage()
            ));
        }    
    }
    /**
    * refer library city and country
    * -----------------------------------------------
    * @author      :   ANS806 - 2017/12/07 - create
    * @param       :
    * @return      :   mixed
    * @access      :   public
    * @see         :   remark
    */
    public function referCity(Request $request)
    {
        try {
            $param  =   $request->all(); 
            $sql    =   "SPC_GET_LIB_CITY_COUNTRY"; 
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
    * refer library country
    * -----------------------------------------------
    * @author      :   ANS806 - 2017/12/07 - create
    * @param       :
    * @return      :   mixed
    * @access      :   public
    * @see         :   remark
    */
    public function referCountry(Request $request)
    {
        try {
            $param  =   $request->all(); 
            $sql    =   "SPC_GET_LIB_COUNTRY"; 
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
    * refer pi accept
    * -----------------------------------------------
    * @author      :   ANS806 - 2017/12/18 - create
    * @param       :
    * @return      :   mixed
    * @access      :   public
    * @see         :   remark
    */
    public function referPiAccept(Request $request)
    {
        try {
            $param  =   $request->all(); 
            $sql    =   "SPC_GET_PI_ACCEPT"; 
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
    * refer item
    * -----------------------------------------------
    * @author      :   ANS806 - 2017/12/20 - create
    * @param       :
    * @return      :   mixed
    * @access      :   public
    * @see         :   remark
    */
    public function referItem(Request $request)
    {
        try {
            $param  =   $request->all(); 
            $sql    =   "SPC_GET_ITEM"; 
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
    * refer warehouse
    * -----------------------------------------------
    * @author      :   ANS804 - 2017/12/26 - create
    * @param       :
    * @return      :   mixed
    * @access      :   public
    * @see         :   remark
    */
    public function referWarehouse(Request $request)
    {
        try {
            $param  =   $request->all(); 
            $sql    =   "SPC_GET_LIB_WAREHOUSE"; 
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
    * refer manufacture no
    * -----------------------------------------------
    * @author      :   ANS806 - 2018/04/10 - create
    * @param       :
    * @return      :   mixed
    * @access      :   public
    * @see         :   remark
    */
    public function referManufacture(Request $request)
    {
        try {
            $param  =   $request->all(); 
            $sql    =   "SPC_REFER_MANUFACTURE_NO"; 
            $data   =   Dao::call_stored_procedure($sql, $param, true);
            if (isset($data[0]) && !empty($data[0])) {
                return response()->json(array(
                    'response'      => true,
                    'data'          => $data[0][0]
                ));
            } else {
                return response()->json(array(
                    'response'      => false
                ));
            }
        } catch (\Exception $e) {
            return response()->json(array(
                    'response'=> $e->getMessage()
            ));
        }    
    }
}
