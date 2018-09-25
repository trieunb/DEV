<?php
/**
*|--------------------------------------------------------------------------
*| Internal Order Export
*|--------------------------------------------------------------------------
*| Package       : Apel
*| @author       : ANS810 - dungnn@ans-asia.com
*| @created date : 2018/01/17
*| 
*/
namespace Modules\Export\Http\Controllers;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Excel, PHPExcel_Worksheet_Drawing;
use Session, DB, Dao, Button;
use Modules\Common\Http\Controllers\CommonController as common;
class InternalOrderSearchExportController extends Controller
{
    public $title           = 'Internal Order';
    public $company         = 'Apel';
    public $description     = '社内発注書一覧';
    protected $totalLine    = '54';
    /*
     * Header
     * @var array
     */
    private $header = [
        '社内発注書番号',
        '行番号',
        '発注日',
        '製造指示日',
        '希望納期',
        '発注者コード',
        '発注者名',
        '処理',
        '製品コード',
        '製品名',
        '発注数量',
        '製造指示状況'
    ];
   
    /*
    * getDownloadExcel
    * -----------------------------------------------
    * @author      :   ANS810 - 2018/01/17 - create
    * @param       :
    * @return      :
    * @access      :   public
    * @see         :   remark
    */
    public function postInternalOrderOutput(Request $request) {
        try {
            $param          = \Input::all();
            $sql            = "SPC_026_INTERNAL_ORDER_SEARCH_FND1";    //name stored
            $result         = Dao::call_stored_procedure($sql, $param,true);
            $result[0]      = isset($result[0]) ? $result[0] : NULL;            
            //width of columns
            $arrWidthColumns     =   array(
                'A'     =>  20,
                'B'     =>  10,
                'C'     =>  15,
                'D'     =>  15,
                'E'     =>  15,
                'F'     =>  15,
                'G'     =>  40,
                'H'     =>  15,
                'I'     =>  15,
                'J'     =>  30,
                'K'     =>  15,
                'L'     =>  15,
            );
            if ( !is_null($result[0])) {
                $filename    = '社内発注書一覧_'.date("YmdHis");
                \Excel::create($filename, function($excel) use ($result, $arrWidthColumns) {
                    $excel->sheet('Sheet 1', function($sheet) use ($result, $arrWidthColumns) {
                        $sheet->setWidth($arrWidthColumns);
                        $row = 1;
                        //border style
                        $styleAllBorder = array(
                            'borders' => array(
                                'allborders' => array(
                                    'style' => \PHPExcel_Style_Border::BORDER_THIN,
                                    'color' => array('rgb' => '000000'),
                                )
                            )
                        );
                        //create and format header
                        $sheet->row($row, $this->header);
                        $sheet->getStyle('A1:L1')->applyFromArray($styleAllBorder);
                        $sheet->getStyle('A1:L1')->getFont()->getColor()->setRGB('ffffff');
                        $sheet->cells('A1:L1', function($cells) {
                            $cells->setBackground('#248af0');
                            $cells->setAlignment('center');
                            $cells->setValignment('center');
                        });
                        //write data to excel file.
                        foreach ($result[0] as $k1 => $v1) {
                            $row ++;
                            $sheet->getStyle('A'.$row.':L'.$row)->applyFromArray($styleAllBorder);
                            $sheet->getStyle('A'.$row.':L'.$row)->getAlignment()->setWrapText(true);
                            $sheet->row($row, array(
                                $v1['in_order_no'], 
                                $v1['disp_order'], 
                                $v1['cre_datetime'], 
                                $v1['cre_datetime_manufacture'], 
                                $v1['hope_delivery_date'],                                
                                $v1['cre_user_cd'], 
                                $v1['user_nm_j'],
                                $v1['manufacture_kind_div_nm'],
                                $v1['product_cd'],
                                $v1['item_nm_j'],
                                $v1['in_order_qty'],                                
                                $v1['manufacture_status_div_nm']
                            ));
                            //even odd row background color
                            if($row % 2 != 0){
                                $sheet->cells('A'.$row.':L'.$row, function($cells) {
                                    $cells->setBackground('#FFF2CC');
                                });
                            }
                            
                            $sheet->cells('B'.$row.':E'.$row, function($cells) {
                                $cells->setAlignment('center');
                                $cells->setValignment('center');
                            });

                            $sheet->cells('G'.$row.':J'.$row, function($cells) {
                                $cells->setAlignment('left');
                                $cells->setValignment('left');
                            });

                            $sheet->cells('K'.$row.':K'.$row, function($cells) {
                                $cells->setAlignment('right');
                                $cells->setValignment('right');
                            });

                            $sheet->cells('L'.$row.':L'.$row, function($cells) {
                                $cells->setAlignment('left');
                                $cells->setValignment('left');
                            });
                        }
                        
                        $sheet->setOrientation('portrait');
                        //focus on A1 cell
                        $sheet->setSelectedCells('A1');
                    });
                })->store('xlsx', public_path('download/excel'));
                return response(array(
                    'response'  =>  true, 
                    'filename'  =>  '/download/excel/'.$filename.'.xlsx'));
            } else {
                return response(array('response'=> false));
            }
        } catch (\Exception $e) {
            return response(array('response'=> false));
        }
    }

    /*
    * download Excel
    * -----------------------------------------------
    * @author      :   ANS810 - 2018/01/17 - create
    * @param       :
    * @return      :
    * @access      :   public
    * @see         :   remark
    */
    public function postExportExcel(Request $request) {
        try {
            $data                   = $request->all();

            $data['internal_list']  = json_encode($data['internal_list']);//parse json to string
            $data['cre_user_cd']    = \GetUserInfo::getInfo('user_cd');
            $data['cre_prg_cd']     = '026_internal-order-search';
            $data['cre_ip']         = \GetUserInfo::getInfo('user_ip');
            //call stored
            $sql                    = "SPC_026_INTERNAL_ORDER_SEARCH_ACT1";
            $result                 = Dao::call_stored_procedure($sql, $data,true);
            $response               =   true;
            $error                  =   '';
            $error_cd               =   '';
            $zip_array              =   '';
            $error_flag             =   false;

            /*********************************************************************
            *  1. Vòng lặp tạo list file excel
            *  1. Loop create list file excel
            *********************************************************************/ 
            if (!empty($result[0])) {
                $response   =   false;
                $error_cd   =   '';
                $error      =   $result[0][0]['Message'];
            } else {
                if (isset($result[1]) && !empty($result[1][0]['error_cd'])) {
                    $response   =   true;
                    $error_cd   =   $result[1][0]['error_cd'];
                } else{
                    for ($k = 0; $k < count($result[2]); $k++) {
                        //width of columns
                        $arrWidthColumns     =   [
                        'A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z',
                        'AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ',
                        'BA','BB','BC','BD','BE','BF','BG',
                        ];    

                        $marginPage         =   [0.4, 0.4, 0.4, 0.4];

                        $arrWidthColumns    = array_fill_keys($arrWidthColumns, 1.67);
                        // get data by key
                        $key            = ['in_order_no' => $result[2][$k]['in_order_no']];
                        $header         = getDataByKey($key, $result[2])[0];
                        $row_data       = getDataByKey($key, $result[3]);                

                        /*********************************************************************
                        *  Tính chiều cao cố định của header ,footer ,body
                        *  Caculator line (height or area) header ,footer ,body
                        *********************************************************************/
                        $line_header    = numLinesDataExcel($this->getDataHeader(),true);
                        $line_footer    = numLinesDataExcel($this->getDataFooter(),true);
                        $line_detail    = $this->totalLine - ($line_header + $line_footer);
                        $pagi           = pagiDataExcel($this->getDataDetail($row_data), $line_detail);
                        // get data pagination
                        // Lấy ra địa chỉ phân trang - Cụ thể ntn xem ở file helper
                        $page           = $pagi[0];
                        // get data of every page
                        // lấy data số lượng row của data sẽ đổ vào page nào
                        // $data_pagi      = dataPageExcel($row_data,$page);
                        $data_pagi      = dataPageExcel($row_data,15);                

                        if ( !is_null($header)) {
                            $filename    = '社内発注書_'.$key['in_order_no'];
                            \Excel::create($filename, function($excel) use ($data_pagi,$row_data, $header, $arrWidthColumns, $marginPage) {
                                $excel->sheet('Sheet 1', function($sheet) use ($data_pagi,$row_data, $header, $arrWidthColumns, $marginPage) {

                                    // Init 
                                    //set margin for page
                                    $sheet->setPageMargin($marginPage);
                                    // Vị trí mỗi page ( First postition of every page)
                                    $pos        =   0;
                                    // Sum height header and footer
                                    $page_size  =   10;

                                    $sheet->setWidth($arrWidthColumns);

                                    // pagination
                                    for ($i = 0; $i < count($data_pagi); $i++) {
                                        if ($i == 0) {
                                            $pos    =   $i + 1;
                                        } else {
                                            $pos    =   $pos + ($page_size+count($data_pagi[$i-1])) + 1;
                                        }
                                        //create and format footer
                                        //create text body
                                        /*
                                         * font style
                                        */
                                        $sheet->setStyle(array(
                                                        'font' => array(
                                                            'name'      =>  'ＭＳ Ｐゴシック',
                                                            'size'      =>  12,
                                                            'bold'      =>  false
                                                        )
                                        ));
                                        /*********************************************************************
                                        *  HEADER
                                        *********************************************************************/
                                        /*
                                         * create and format header
                                         * row 1 -> 4
                                        */
                                        //1. Set value for internal_purchase_order_form(社内発注書)
                                        //create text header
                                        $textHeaderFormTitle = new \PHPExcel_RichText();
                                        $objBold = $textHeaderFormTitle->createTextRun('社内発注書');
                                        $objBold->getFont()->setName('ＭＳ Ｐゴシック')
                                                           ->setSize(26)
                                                           ->setBold(true);

                                        $sheet->setCellValue('V'.$pos, $textHeaderFormTitle);

                                        //社内発注日
                                        $sheet->setCellValue('AL'.$pos,'社内発注日');
                                        $sheet->mergeCells('AL'.$pos.':AU'.$pos);
                                        $sheet->setCellValue('AV'.$pos,":");
                                        $sheet->setCellValue('AW'.$pos,$header['internal_order_date']);
                                        $sheet->cells('AL'.$pos.':AU'.$pos, function($cells){
                                            $cells->setAlignment('center');  
                                            $cells->setValignment('bottom');   
                                        });
                                        $sheet->cells('AL'.$pos.':AU'.$pos, function($cells) {
                                            $cells->setFont(array(
                                                'size'       =>  10,
                                                'bold'       =>  true
                                            ));
                                        });
                                        $posR2 = $pos+1;
                                        //社内発注書番号
                                        $sheet->setCellValue('AL'.$posR2,'社内発注書番号');
                                        $sheet->mergeCells('AL'.$posR2.':AU'.$posR2);
                                        $sheet->setCellValue('AV'.$posR2,":");
                                        $sheet->setCellValue('AW'.$posR2,$header['in_order_no']." ");
                                        $sheet->cells('AL'.$posR2.':AU'.$posR2, function($cells){
                                            $cells->setAlignment('center');  
                                            $cells->setValignment('bottom');   
                                        });
                                        $sheet->cells('AL'.$posR2.':AU'.$posR2, function($cells) {
                                            $cells->setFont(array(
                                                'size'       =>  9,
                                                'bold'       =>  true
                                            ));
                                        });
                                        $posR3 = $pos+2;
                                        //担当者
                                        $sheet->setCellValue('AL'.$posR3,'担当者');
                                        $sheet->mergeCells('AL'.$posR3.':AU'.$posR3);
                                        $sheet->setCellValue('AV'.$posR3,":");
                                        $sheet->setCellValue('AW'.$posR3,$header['contact_nm']);
                                        $sheet->cells('AL'.$posR3.':AU'.$posR3, function($cells){
                                            $cells->setAlignment('center');  
                                            $cells->setValignment('bottom');   
                                        });
                                        $sheet->cells('AW'.$posR3, function($cells) {
                                            $cells->setFont(array(
                                                'size'       =>  9
                                            ));
                                        });
                                        $sheet->cells('AL'.$posR3.':AU'.$posR3, function($cells) {
                                            $cells->setFont(array(
                                                'size'       =>  10,
                                                'bold'       =>  true
                                            ));
                                        });
                                        $sheet->getRowDimension(4)->setRowHeight(10);
                                        /*
                                         * Set text title for row 5
                                        */
                                        // Set fix infor
                                        // array(
                                        //      'No'
                                        //     ,'コード'
                                        //     ,'処理'
                                        //     ,'製品名'
                                        //     ,'数量'
                                        //     ,'希望納期'
                                        //     ,'備考'
                                        // )); 

                                        $posR5 = $pos+4;

                                        // No
                                        $sheet->setCellValue('A'.$posR5,'No');
                                        $sheet->mergeCells('A'.$posR5.':B'.$posR5);                        
                                        $sheet->cells('A'.$posR5.':B'.$posR5, function($cells){
                                            $cells->setAlignment('center');  
                                            $cells->setValignment('center');   
                                        });
                                        //コード
                                        $sheet->setCellValue('C'.$posR5,'コード');
                                        $sheet->mergeCells('C'.$posR5.':G'.$posR5);                        
                                        $sheet->cells('C'.$posR5.':G'.$posR5, function($cells){
                                            $cells->setAlignment('center');  
                                            $cells->setValignment('center');   
                                        });
                                        //処理
                                        $sheet->setCellValue('H'.$posR5,'処理');
                                        $sheet->mergeCells('H'.$posR5.':J'.$posR5);                        
                                        $sheet->cells('H'.$posR5.':J'.$posR5, function($cells){
                                            $cells->setAlignment('center');  
                                            $cells->setValignment('center');   
                                        });
                                        //製品名
                                        $sheet->setCellValue('K'.$posR5,'製品名');
                                        $sheet->mergeCells('K'.$posR5.':AD'.$posR5);                        
                                        $sheet->cells('K'.$posR5.':AD'.$posR5, function($cells){
                                            $cells->setAlignment('center');  
                                            $cells->setValignment('center');   
                                        });
                                        //数量
                                        $sheet->setCellValue('AE'.$posR5,'数量');
                                        $sheet->mergeCells('AE'.$posR5.':AJ'.$posR5);                        
                                        $sheet->cells('AE'.$posR5.':AJ'.$posR5, function($cells){
                                            $cells->setAlignment('center');  
                                            $cells->setValignment('center');   
                                        });
                                        //希望納期
                                        $sheet->setCellValue('AK'.$posR5,'希望納期');
                                        $sheet->mergeCells('AK'.$posR5.':AQ'.$posR5);                        
                                        $sheet->cells('AK'.$posR5.':AQ'.$posR5, function($cells){
                                            $cells->setAlignment('center');  
                                            $cells->setValignment('center');   
                                        });
                                        //備考
                                        $sheet->setCellValue('AR'.$posR5,'備考');
                                        $sheet->mergeCells('AR'.$posR5.':BG'.$posR5);                        
                                        $sheet->cells('AR'.$posR5.':BG'.$posR5, function($cells){
                                            $cells->setAlignment('center');  
                                            $cells->setValignment('center');   
                                        });
                                        
                                        /*********************************************************************
                                        *  END - HEADER
                                        *********************************************************************/

                                        $posR6 = $pos+5;
                                        //write data to excel file.

                                        foreach ($data_pagi[$i] as $k => $v) {
                                            /*
                                             * Render data
                                            */
                                            $row = $posR6 + $k;
                                            $sheet->getRowDimension($row)->setRowHeight(45);
                                            // for No
                                            $sheet->setCellValue('A'.$row,$v['disp_order']);
                                            $sheet->mergeCells('A'.$row.':'.'B'.$row);
                                            $sheet->cells('A'.$row.':'.'B'.$row, function($cells) use($v){
                                                $cells->setAlignment('center');
                                                $cells->setValignment('center');
                                            });
                                            
                                            // for コード
                                            $sheet->setCellValue('C'.$row,$v['product_cd']);
                                            $sheet->mergeCells('C'.$row.':'.'G'.$row);
                                            $sheet->cells('C'.$row.':'.'G'.$row, function($cells) use($v){
                                                $cells->setAlignment('left');  
                                                $cells->setValignment('center');   
                                            });
                                            
                                            // for 処理
                                            $sheet->setCellValue('H'.$row,$v['manufacture_kind_div']);
                                            $sheet->mergeCells('H'.$row.':'.'J'.$row);
                                            $sheet->cells('H'.$row.':'.'J'.$row, function($cells) use($v){
                                                $cells->setAlignment('center');  
                                                $cells->setValignment('center');   
                                            });
                                            
                                            // for 製品名
                                            $sheet->setCellValue('K'.$row,$v['product_nm_j']);
                                            $sheet->mergeCells('K'.$row.':'.'AD'.$row);
                                            $sheet->cells('K'.$row.':'.'AD'.$row, function($cells) use($v){
                                                $cells->setAlignment('left');  
                                                $cells->setValignment('center');   
                                            });
                                            // Wraptext for 製品名
                                            $sheet->getStyle('K'.$row.':'.'AD'.$row)->getAlignment()->setWrapText(true);
                                            
                                            // for 数量
                                            $sheet->setCellValue('AE'.$row,$v['dest_nm']);
                                            $sheet->mergeCells('AE'.$row.':'.'AJ'.$row);
                                            $sheet->cells('AE'.$row.':'.'AJ'.$row, function($cells) use($v){
                                                $cells->setAlignment('right');  
                                                $cells->setValignment('center');   
                                            });
                                            
                                            // for 希望納期
                                            $sheet->setCellValue('AK'.$row,$v['hope_delivery_date']);
                                            $sheet->mergeCells('AK'.$row.':'.'AQ'.$row);
                                            $sheet->cells('AK'.$row.':'.'AQ'.$row, function($cells) use($v){
                                                $cells->setAlignment('center');  
                                                $cells->setValignment('center');   
                                            });
                                            
                                            // for 備考
                                            $sheet->setCellValue('AR'.$row,$v['detail_remarks']);
                                            $sheet->mergeCells('AR'.$row.':'.'BG'.$row);
                                            $sheet->cells('AR'.$row.':'.'BG'.$row, function($cells) use($v){
                                                $cells->setAlignment('left');  
                                                $cells->setValignment('center'); 
                                            });

                                            // Wraptext for 備考
                                            $sheet->getStyle('AR'.$row.':'.'BG'.$row)->getAlignment()->setWrapText(true);
                                            // Increment auto 'row'
                                            $row++;
                                        }

                                        /*********************************************************************
                                        *  START - BORDER STYLE
                                        *********************************************************************/ 
                                        $posB1 = $pos+4;
                                        $posB2 = $pos+19;
                                        $posB3 = $pos+3; 

                                        // Set fix row height
                                        for( $j = $posR6; $j <= $posB2 ; $j++ ){
                                            $sheet->getRowDimension($j)->setRowHeight(45);
                                            $sheet->mergeCells('A'.$j.':'.'B'.$j);                            
                                            $sheet->cells('A'.$j.':'.'B'.$j, function($cells) use($v){
                                                $cells->setAlignment('center');
                                                $cells->setValignment('center');
                                            });
                                            $sheet->mergeCells('C'.$j.':'.'G'.$j);
                                            $sheet->cells('C'.$j.':'.'G'.$j, function($cells) use($v){
                                                $cells->setAlignment('left');  
                                                $cells->setValignment('center');   
                                            });
                                            $sheet->mergeCells('H'.$j.':'.'J'.$j);
                                            $sheet->cells('H'.$j.':'.'J'.$j, function($cells) use($v){
                                                $cells->setAlignment('center');  
                                                $cells->setValignment('center');   
                                            });
                                            $sheet->mergeCells('K'.$j.':'.'AD'.$j);
                                            $sheet->cells('K'.$j.':'.'AD'.$j, function($cells) use($v){
                                                $cells->setAlignment('left');  
                                                $cells->setValignment('center');   
                                            });
                                            $sheet->mergeCells('AE'.$j.':'.'AJ'.$j);
                                            $sheet->cells('AE'.$j.':'.'AJ'.$j, function($cells) use($v){
                                                $cells->setAlignment('right');  
                                                $cells->setValignment('center');   
                                            });
                                            $sheet->mergeCells('AK'.$j.':'.'AQ'.$j);
                                            $sheet->cells('AK'.$j.':'.'AQ'.$j, function($cells) use($v){
                                                $cells->setAlignment('center');
                                                $cells->setValignment('center');
                                            });
                                            $sheet->mergeCells('AR'.$j.':'.'BG'.$j);
                                            $sheet->cells('AR'.$j.':'.'BG'.$j, function($cells) use($v){
                                                $cells->setAlignment('left');
                                                $cells->setValignment('center');
                                            });
                                        }

                                        //set border
                                        $sheet->setBorder('A'.$posB1.':BG'.$posB2, \PHPExcel_Style_Border::BORDER_THIN, "#000000");

                                        //$sheet->getStyle('A'.$pos.':BG'.$pos)->getBorders()->getTop()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN, "#000000");
                                        //$sheet->getStyle('A'.$pos.':A'.$posB3)->getBorders()->getLeft()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN, "#000000");
                                        //$sheet->getStyle('BG'.$pos.':BG'.$posB3)->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN, "#000000");                       
                                        
                                        $sheet->setOrientation('portrait');
                                        //focus on A1 cell
                                        $sheet->setSelectedCells('A1');
                                        /*********************************************************************
                                        *  END - BORDER STYLE
                                        *********************************************************************/  

                                        /*********************************************************************
                                        *  START - FOOTER
                                        *********************************************************************/ 
                                        $posF1 = $pos+21;
                                        $posF2 = $pos+22;
                                        $posF3 = $pos+24;
                                        // 部長
                                        $sheet->setCellValue('AI'.$posF1,'部長');
                                        $sheet->mergeCells('AI'.$posF1.':AO'.$posF1);                        
                                        $sheet->mergeCells('AI'.$posF2.':AO'.$posF3);                        
                                        $sheet->cells('AI'.$posF1.':AO'.$posF1, function($cells){
                                            $cells->setAlignment('center');  
                                            $cells->setValignment('bottom');   
                                        });
                                        $sheet->setBorder('AI'.$posF1.':AO'.$posF3, \PHPExcel_Style_Border::BORDER_THIN, "#000000");

                                        //課長
                                        $sheet->setCellValue('AR'.$posF1,'部長');
                                        $sheet->mergeCells('AR'.$posF1.':AX'.$posF1);                        
                                        $sheet->mergeCells('AR'.$posF2.':AX'.$posF3);        
                                        $sheet->cells('AR'.$posF1.':AX'.$posF1, function($cells){
                                            $cells->setAlignment('center');
                                            $cells->setValignment('bottom');
                                        });
                                        $sheet->setBorder('AR'.$posF1.':AX'.$posF3, \PHPExcel_Style_Border::BORDER_THIN, "#000000");

                                        //課長
                                        $sheet->setCellValue('BA'.$posF1,'');
                                        $sheet->mergeCells('BA'.$posF1.':BG'.$posF1); 
                                        $sheet->mergeCells('BA'.$posF2.':BG'.$posF3);
                                        $sheet->setBorder('BA'.$posF1.':BG'.$posF3, \PHPExcel_Style_Border::BORDER_THIN, "#000000");

                                        // Page1/total page
                                        $sheet->setCellValue('AD26','1/'.count($data_pagi));
                                        $sheet->cells('AD26', function($cells) {
                                            $cells->setFont(array(
                                                'size'       =>  10,
                                                'bold'       =>  false
                                            ));
                                        });
                                        $sheet->setBreak( 'A26' , \PHPExcel_Worksheet::BREAK_ROW );
                                        if(count($data_pagi) == 2){
                                             // Page2/total page
                                            $sheet->setCellValue('AD52','2/'.count($data_pagi));
                                            $sheet->cells('AD52', function($cells) {
                                                $cells->setFont(array(
                                                    'size'       =>  10,
                                                    'bold'       =>  false
                                                ));
                                            });
                                        }
                                        
                                        /*********************************************************************
                                        *  END - FOOTER
                                        *********************************************************************/  
                                    }
                                    
                                });
                            })->store('xlsx', DOWNLOAD_EXCEL_PUBLIC);    
                             $filename       =   $filename.'.xlsx';     
                             $zip_array[]    =   $filename;
                             $error_flag     =   true;
                        } else {
                            return response(array('response'=> false));
                        }
                    }
                }
            } 
                    

            /*********************************************************************
            *  2. Xuất file xlsx or zip
            *  2. Export file xlsx or zip
            *********************************************************************/
            // 
            $zipFileName    =   '社内発注書_'.date("YmdHis").'.zip';

            if ( $error_flag ) {
                if (count($zip_array) > 1) {
                    if (common::ZipFile(DOWNLOAD_EXCEL_PUBLIC, $zipFileName, $zip_array)) {
                        $fileDownload    =   $zipFileName;
                    }
                } else {
                    $fileDownload    =   $zip_array[0];
                }
                return response([
                    'response'  =>  $response, 
                    'fileName'  =>  DOWNLOAD_EXCEL.$fileDownload,
                    'error_cd'  =>  $error_cd,
                ]);
            } else {
                return response([
                    'response'  =>  $response,
                    'fileName'  =>  '',
                    'error_cd'  =>  $error_cd,
                    'error'     =>  $error,
                ]);
            }

            
        } catch (\Exception $e) {
            return response(array('response'=> false));
        }
    }

    /*
    * getDataHeader
    * -----------------------------------------------
    * @author      :   ANS810 - 2018/02/01 - create
    * @param       :   
    * @return      :   format data header excel
    * @access      :   protected
    * @see         :   remark
    */
    protected function getDataHeader() {
        try {
            $data_Header = [
                '2',
                '1',
                '1',
                '1',
            ];
            return $data_Header;
        } catch (\Exception $e) {
            return response()->json(['response' => false]);
        }
    }

    /*
    * getDataFooter
    * -----------------------------------------------
    * @author      :   ANS810 - 2018/02/01 - create
    * @param       :   
    * @return      :   format data footer excel
    * @access      :   protected
    * @see         :   remark
    */
    protected function getDataFooter() {
        try {
            $data_Footer = [
                '1',
                '1',
                '1',
                '1',
            ];
            return $data_Footer;
        } catch (\Exception $e) {
            return response()->json(['response' => false]);
        }
    }

    protected function getDataDetail($data) {
        try {
            $data_Detail = '';
            if (!empty($data)) {
                foreach ($data as $key => $value) {
                    $data_Detail[] =  '3';
                }
            }
            return $data_Detail;
        } catch (\Exception $e) {
            return response()->json(['response' => false]);
        }
    }

}
