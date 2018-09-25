<?php
/**
*|--------------------------------------------------------------------------
*| CommonController
*|--------------------------------------------------------------------------
*| Package       : Apel
*| @author       : TuanNT - ANS796 - tuannt@ans-asia.com
*| @created date : 2016/12/27
*| 
*/
namespace Modules\SystemManagement\Http\Controllers;

use App\Http\Controllers\Controller;
use Dao,Input,Cookie;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Modules\Common\Http\Controllers\CommonController as Common;

class LoginController extends Controller
{
     /**
     * cookie expire
     *
     * @var int
     */
    private $cookieExpire;

    /**
     * url redirect when login succsess
     *
     * @var int
     */
    private $urlAfterLogin;

    /**
     * __construct
     * -----------------------------------------------
     * @author      :   TuanNT - 2017/11/22 - create
     *
     * @param       :   null
     *
     * @return      :   void
     * @access      :   public
     * @see         :   remark
     */
    public function __construct(){
        $this->cookieExpire     = time() + (86400 * 30);// 86400 = 1 day
        $this->urlAfterLogin    = '/dashboard';
    }

    /**
    |--------------------------------------------------------------------------
    | Login
    |--------------------------------------------------------------------------
    | Package       : COMMMON  
    | @author       : TuanNT - 2017/11/22 - create
    | @created date : 2017/11/22
    |
    */
    public function getIndex(Request $request){  
        
        $cookie_name = "user_cd";
        $cookie_pass = "password";
        
        $cookie_name = $request->cookie($cookie_name);
        $cookie_pass = $request->cookie($cookie_pass);
        
        if (\GetUserInfo::getInfo('user_cd') != null) {
            return redirect($this->urlAfterLogin);
        }

        return view('Auth::login.index',compact('cookie_name','cookie_pass'));
    }

    /**
    |--------------------------------------------------------------------------
    | Logout
    |--------------------------------------------------------------------------
    | Package       : COMMON  
    | @author       : TuanNT - 2017/11/22 - create
    | @created date : 2017/11/22
    |
    */
    public function getLogout(){
        if (session::has('user_info')){
            session::forget('user_info');
        }
        return redirect('/');
    }

    /**
    |--------------------------------------------------------------------------
    | Login
    |--------------------------------------------------------------------------
    | Package       : COMMON  
    | @author       : TuanNT - 2017/11/22 - create
    | @created date : 2017/11/22
    |
    */
    public function postDoLogin(){
        try{
            //check if url before login when redirect url 
            if (session::has('url_last_login')){
                $link = session::get('url_last_login');
                session::forget('url_last_login');
            }else{
                $link = '/login/success';
            }
            $input          = Input::all();
            
            //  get language_code
            $lang           = \App::getLocale();

            $sql        =   'SPC_073_LOGIN_ACT1';
            $result     =   Dao::call_stored_procedure( $sql , $input, true );
            if (isset($result[0][0]) && !empty($result[0][0])) {//user
                $user_info = [
                    'user_cd'           => $result[0][0]['user_cd'],
                    'user_nm_j'         => $result[0][0]['user_nm_j'],
                    'user_ab_j'         => $result[0][0]['user_ab_j'],   
                    'user_nm_e'         => $result[0][0]['user_nm_e'],
                    'user_ab_e'         => $result[0][0]['user_ab_e'],
                    'auth_role_div'     => $result[0][0]['auth_role_div'],
                    'user_ip'           => '::1'                        //TODO   
                ];
                session::put('user_info', $user_info);
                Common::getAllConstant();
                return response()->json(array('response'=>true,'status'=>'ok','link'=>$link));
            }else{
                return response()->json(array('response'=>false,'status'=>'not_found'));
            }       
        }catch(Exception $e){
            return response()->json(array('response'=>false,'status'=>'ng'));
        }    
    }
    /**
    |--------------------------------------------------------------------------
    | Login Success
    |--------------------------------------------------------------------------
    | Package       : COMMON  
    | @author       : TuanNT - 2018/05/03 - create
    | @created date : 2017/11/22
    |
    */
    public function getSuccess(){
        try{
            return view('systemmanagement::login.success');
        }catch(Exception $e){
            return response()->json(array('response'=>false,'status'=>'ng'));
        }    
    }
}
