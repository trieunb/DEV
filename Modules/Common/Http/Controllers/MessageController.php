<?php
/*
|--------------------------------------------------------------------------
| Common
|--------------------------------------------------------------------------
| Package       : Apel  
| @author       : TuanNT - ANS796 - tuannt@ans-asia.com
| @created date : 2017/11/22
|
*/
namespace Modules\Common\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Dao,File;
use Modules\Common\Http\Controllers\CsvController as csv;
class MessageController extends Controller
{
    /**
    * get all message
    * -----------------------------------------------
    * @author      :   ANS796 - 2017/11/22 - create
    * @param       :
    * @return      :
    * @access      :   public
    * @see         :   remark
    */
    public function getIndex(){
    
        $data     = self::getMessages();
        //return view('Common::message.index',compact('data')); 
    }

    /**
    * get message and update into file msg_ja.js
    *
    * @author      :  ANS796 - 2017/11/22 - create
    * @param       :   null
    * @return      :   null
    * @access      :   public
    * @see         :
    */
    public static function postLanguageMessage(){
        //
        $lang_folder_path = base_path() . '/public/message/';
        // get file path of messages.php file based on language
        $lang_file_path = $lang_folder_path . 'msg' . '.js';
        
        $_text    =    '';
        $_type    =    '';
        $_title    =    '';
        $script    =    '';
        if (File::exists($lang_folder_path)) {
            $message_data = self::getMessages();

            if (!empty($message_data)) {
                foreach ($message_data as $k => $row){
                    if($k != 0){
                        $_text[$row[0]]      = htmlspecialchars_decode($row[3]);
                        $_type[$row[0]]      = $row[1];
                        $_title[$row[0]]     = $row[2];

                        $script  = "var _text = " . json_encode($_text, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) . ";";
                        $script .= "var _type = " . json_encode($_type, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) . ";";
                        $script .= "var _title = " . json_encode($_title, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) . ";";
                    }
                }
            }
            $controls = array(
                "btn-msg-ok"                => 'はい',
                "btn-msg-cancel"            => 'いいえ',
                "txt-search-placeholder"    => '入力して検索します...',
            );
            $script .= "var _controls = " . json_encode($controls, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) . ";";


           //$script .= 'var _controls = {"btn-msg-ok":"' . \Lang::get('controls.btn-msg-ok') . '","btn-msg-cancel":"'. \Lang::get('controls.btn-msg-cancel') . '"};';
            // write into file
            $bytes_written = File::put($lang_file_path, $script);
            return response()->json(array('response'=>'true','status'=>'ok'));
        }else{
            return response()->json(array('response'=>'false','status'=>'ng'));
        }
    } 
    /**
    * read all message from csv
    *
    * @author      :   ANS796 - 2017/11/22 - create
    * @param       :   null
    * @return      :   null
    * @access      :   public
    * @see         :
    */
    private static  function getMessages(){
        $fileName = mb_convert_encoding('メッセージ管理.csv', 'SJIS');
        // language folder path
        $file = base_path() . '/public/message/'.$fileName;
        $data = csv::inputCSV($file);
        return $data;
    }

}
