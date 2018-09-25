<?php
/**
*|--------------------------------------------------------------------------
*| Manufactor Instruction Report Export
*|--------------------------------------------------------------------------
*| Package       : Apel
*| @author       : ANS810 - dungnn@ans-asia.com
*| @created date : 2018/02/06
*| 
*/
namespace Modules\Export\Http\Controllers;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Excel, PHPExcel_Worksheet_Drawing, PHPExcel_Worksheet;
use Session, DB, Dao, Button;
use Modules\Common\Http\Controllers\CommonController as common;
class ManufacturingInstructionReportExportController extends Controller
{
    public $title           = 'Manufactor Instruction Report';
    public $company         = 'Apel';
    public $description     = '製造指示書発行';
    protected $totalLine    = '54';

    /*
     * Header
     * @var array
     */
    private $header = [
        '社内発注書番号',
        '枝番',
        '製品コード',
        '製品名',
        '希望納期',
        '発注数量',
        '指示数量',
        '残数量',
        '特殊',
        '備考'
    ];
   
    /*
    * postManufactureReportOutput
    * -----------------------------------------------
    * @author      :   ANS810 - 2018/02/06 - create
    * @param       :
    * @return      :
    * @access      :   public
    * @see         :   remark
    */
    public function postManufactureReportOutput(Request $request) {
        try {
            $param          = \Input::all();
            $sql            = "SPC_028_MANUFACTURE_REPORT_SEARCH_FND1";    //name stored
            $result         = Dao::call_stored_procedure($sql, $param,true);
            $result[0]      = isset($result[0]) ? $result[0] : NULL;            

            //width of columns
            $arrWidthColumns     =   array(
                'A'     =>  18,
                'B'     =>  10,
                'C'     =>  15,
                'D'     =>  30,
                'E'     =>  15,
                'F'     =>  15,
                'G'     =>  15,
                'H'     =>  15,
                'I'     =>  10,
                'J'     =>  30,
            );
            if ( !is_null($result[0])) {
                $filename    = '製造指示書発行_'.date("YmdHis");
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
                        $sheet->getStyle('A1:J1')->applyFromArray($styleAllBorder);
                        $sheet->getStyle('A1:J1')->getFont()->getColor()->setRGB('ffffff');
                        $sheet->cells('A1:J1', function($cells) {
                            $cells->setBackground('#248af0');
                            $cells->setAlignment('center');
                            $cells->setValignment('center');
                        });
                        //write data to excel file.
                        foreach ($result[0] as $k1 => $v1) {
                            $row ++;
                            $sheet->getStyle('A'.$row.':J'.$row)->applyFromArray($styleAllBorder);
                            $sheet->getStyle('A'.$row.':J'.$row)->getAlignment()->setWrapText(true);
                            $sheet->row($row, array(
                                $v1['in_order_no'], 
                                $v1['disp_order'], 
                                $v1['product_cd'],                               
                                $v1['product_nm'],                               
                                $v1['hope_delivery_date'],                               
                                $v1['in_order_qty'],         
                                '',                      
                                $v1['subtract_manufacture_qt'],                               
                                $v1['manufacture_kind_div_nm'],                               
                                '',                              
                            ));
                            //even odd row background color
                            if($row % 2 != 0){
                                $sheet->cells('A'.$row.':J'.$row, function($cells) {
                                    $cells->setBackground('#FFF2CC');
                                });
                            }
                             $sheet->cells('A'.$row.':A'.$row, function($cells) {
                                $cells->setAlignment('left');
                                $cells->setValignment('center');
                            });

                            $sheet->cells('B'.$row.':C'.$row, function($cells) {
                                $cells->setAlignment('right');
                                $cells->setValignment('center');
                            });

                            $sheet->cells('D'.$row.':D'.$row, function($cells) {
                                $cells->setAlignment('left');
                                $cells->setValignment('center');
                            });

                            $sheet->cells('E'.$row.':E'.$row, function($cells) {
                                $cells->setAlignment('center');
                                $cells->setValignment('center');
                            });

                            $sheet->cells('F'.$row.':H'.$row, function($cells) {
                                $cells->setAlignment('right');
                                $cells->setValignment('center');
                            });     
                            $sheet->cells('I'.$row.':I'.$row, function($cells) {
                                $cells->setAlignment('left');
                                $cells->setValignment('center');
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
    * @author      :   ANS810 - 2018/02/13 - create
    * @param       :
    * @return      :
    * @access      :   public
    * @see         :   remark
    */
    public function postManufactureReportExport(Request $request) {
        try {
            $param                     = $request->all();            

            $param['update_list']      = json_encode($param['update_list']);//parse json to string
            $param['cre_user_cd']      = \GetUserInfo::getInfo('user_cd');
            $param['cre_prg_cd']       = '028_manufacture-instruction-report';
            $param['cre_ip']           = \GetUserInfo::getInfo('user_ip');  
            $sql                       = "SPC_028_MANUFACTURE_REPORT_SEARCH_ACT1";
            $result                    = Dao::call_stored_procedure($sql, $param,true);
            $response                  =   true;
            $error                     =   '';
            $error_cd                  =   '';
            $zip_array                 =   '';
            $error_flag                =   false;
            $error_product             = isset($result[4]) ? $result[4] : '';

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
                        $arrWidthColumns    = array_fill_keys($arrWidthColumns, 1.67);
                        // get data by key
                        $key            = ['in_order_no' => $result[2][$k]['in_order_no']];
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
                        $data_pagi      = dataPageExcel($row_data,1);

                        if ( !is_null($row_data)) {
                            $filename    = '製造指示書_'.$key['in_order_no'];
                            \Excel::create($filename, function($excel) use ($data_pagi, $row_data, $arrWidthColumns) {
                                $excel->sheet('Sheet 1', function($sheet) use ($data_pagi, $row_data, $arrWidthColumns) {

                                    // Init 
                                    // Vị trí mỗi page ( First postition of every page)
                                    $pos        =   0;
                                    // Sum height header and footer
                                    $page_size  =   13;

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
                                        */
                                        //1. Set value (製造指示書)
                                        //create text header
                                        // row 1
                                        $textHeaderFormTitle = new \PHPExcel_RichText();
                                        $objBold = $textHeaderFormTitle->createTextRun('製造指示書');
                                        $objBold->getFont()->setName('ＭＳ Ｐゴシック')
                                                           ->setSize(26)
                                                           ->setBold(true);

                                        $sheet->setCellValue('V'.$pos, $textHeaderFormTitle);
                                        $sheet->getRowDimension($pos)->setRowHeight(29);
                                        // row 2
                                        $posR2 = $pos+1;
                                        //社内発注書番号
                                        $sheet->setCellValue('AL'.$posR2,'発行日');
                                        $sheet->mergeCells('AL'.$posR2.':AU'.$posR2);
                                        $sheet->cells('AL'.$posR2.':AU'.$posR2, function($cells){
                                            $cells->setAlignment('center');  
                                            $cells->setValignment('bottom');   
                                        });
                                        //
                                        $sheet->setCellValue('AV'.$posR2,":");
                                        //
                                        $sheet->setCellValue('AW'.$posR2,$data_pagi[$i][0]['cre_datetime']);
                                        $sheet->mergeCells('AW'.$posR2.':BG'.$posR2);
                                        $sheet->cells('AW'.$posR2.':BG'.$posR2, function($cells){
                                            $cells->setAlignment('center');
                                            $cells->setValignment('bottom');
                                        });
                                        $sheet->getStyle('AW'.$posR2.':BG'.$posR2)->getBorders()->getBottom()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN, "#000000");
                                        //
                                        $sheet->getRowDimension($posR2)->setRowHeight(29);

                                        // row 3
                                        $posR3 = $posR2+1;
                                        //製造課　御中
                                        $sheet->setCellValue('C'.$posR3,'製造課　御中');
                                        $sheet->mergeCells('C'.$posR3.':K'.$posR3);
                                        $sheet->cells('C'.$posR3.':K'.$posR3, function($cells){
                                            $cells->setAlignment('center');
                                            $cells->setValignment('bottom');
                                        });
                                        $sheet->getStyle('C'.$posR3.':K'.$posR3)->getBorders()->getBottom()->setBorderStyle(\PHPExcel_Style_Border::BORDER_DOUBLE, "#000000");
                                        //製造指示書番号
                                        $sheet->setCellValue('AL'.$posR3,'製造指示書番号');
                                        $sheet->mergeCells('AL'.$posR3.':AU'.$posR3);
                                        $sheet->cells('AL'.$posR3.':AU'.$posR3, function($cells){
                                            $cells->setAlignment('center');  
                                            $cells->setValignment('bottom');   
                                        });
                                        //
                                        $sheet->setCellValue('AV'.$posR3,":");
                                        //
                                        $sheet->setCellValue('AW'.$posR3,$data_pagi[$i][0]['manufacture_no']);
                                        $sheet->mergeCells('AW'.$posR3.':BG'.$posR3);
                                        $sheet->cells('AW'.$posR3.':BG'.$posR3, function($cells){
                                            $cells->setAlignment('center');
                                            $cells->setValignment('bottom');
                                        });
                                        $sheet->getStyle('AW'.$posR3.':BG'.$posR3)->getBorders()->getBottom()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN, "#000000");
                                        //
                                        $sheet->getRowDimension($posR3)->setRowHeight(29);
                                        
                                        // row 4
                                        $posR4 = $posR3+1;
                                        $sheet->getRowDimension($posR4)->setRowHeight(29);

                                        // row 5
                                        $posR5 = $posR4+1;
                                        //注文書有効期限
                                        $sheet->setCellValue('AG'.$posR5,'注文書有効期限');
                                        $sheet->mergeCells('AG'.$posR5.':AR'.$posR5);
                                        $sheet->cells('AG'.$posR5.':AR'.$posR5, function($cells){
                                            $cells->setAlignment('center');
                                            $cells->setValignment('bottom');
                                        });
                                        $sheet->setBorder('AG'.$posR5.':AR'.$posR5, \PHPExcel_Style_Border::BORDER_THIN, "#000000");                                
                                        //一ヶ月
                                        $sheet->setCellValue('AS'.$posR5,'一ヶ月');
                                        $sheet->mergeCells('AS'.$posR5.':BG'.$posR5);
                                        $sheet->cells('AS'.$posR5.':BG'.$posR5, function($cells){
                                            $cells->setAlignment('center');
                                            $cells->setValignment('bottom');
                                        });
                                        $sheet->setBorder('AS'.$posR5.':BG'.$posR5, \PHPExcel_Style_Border::BORDER_THIN, "#000000");
                                        //
                                        $sheet->getRowDimension($posR5)->setRowHeight(29);

                                        // row 6
                                        $posR6 = $posR5+1;
                                        //製造種別：
                                        $sheet->setCellValue('C'.$posR6,'製造種別：');
                                        $sheet->mergeCells('C'.$posR6.':H'.$posR6);
                                        $sheet->cells('C'.$posR6.':H'.$posR6, function($cells){
                                            $cells->setAlignment('center');
                                            $cells->setValignment('bottom');
                                        });
                                         //                               
                                        $sheet->setCellValue('J'.$posR6,$data_pagi[$i][0]['manufacture_kind_div']);
                                        $sheet->mergeCells('J'.$posR6.':P'.$posR6);
                                        $sheet->cells('J'.$posR6.':P'.$posR6, function($cells){
                                            $cells->setAlignment('left');
                                            $cells->setValignment('bottom');
                                        });
                                        $sheet->getStyle('J'.$posR6.':P'.$posR6)->getBorders()->getBottom()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN, "#000000");
                                        //
                                        $sheet->getRowDimension($posR6)->setRowHeight(29);
                                        //希望納期
                                        $sheet->setCellValue('AG'.$posR6,'希望納期');
                                        $sheet->mergeCells('AG'.$posR6.':AR'.$posR6);
                                        $sheet->cells('AG'.$posR6.':AR'.$posR6, function($cells){
                                            $cells->setAlignment('center');
                                            $cells->setValignment('bottom');
                                        });
                                        $sheet->setBorder('AG'.$posR6.':AR'.$posR6, \PHPExcel_Style_Border::BORDER_THIN, "#000000");                                
                                        //
                                        $sheet->setCellValue('AS'.$posR6,$data_pagi[$i][0]['hope_delivery_date']);
                                        $sheet->mergeCells('AS'.$posR6.':BG'.$posR6);
                                        $sheet->cells('AS'.$posR6.':BG'.$posR6, function($cells){
                                            $cells->setAlignment('center');
                                            $cells->setValignment('bottom');
                                        });
                                        $sheet->setBorder('AS'.$posR6.':BG'.$posR6, \PHPExcel_Style_Border::BORDER_THIN, "#000000");
                                        //
                                        $sheet->getRowDimension($posR6)->setRowHeight(29);

                                        // row 7
                                        $posR7= $posR6+1;
                                        $sheet->getRowDimension($posR7)->setRowHeight(15);

                                        // row 8
                                        $posR8 = $posR7+1;
                                        //No.
                                        $sheet->setCellValue('A'.$posR8,'No.');
                                        $sheet->mergeCells('A'.$posR8.':C'.$posR8);
                                        $sheet->cells('A'.$posR8.':C'.$posR8, function($cells){
                                            $cells->setAlignment('center');
                                            $cells->setValignment('center');
                                        });
                                        $sheet->setBorder('A'.$posR8.':C'.$posR8, \PHPExcel_Style_Border::BORDER_THIN, "#000000");  
                                        //製品 コード
                                        $sheet->setCellValue('D'.$posR8,'製品 コード');
                                        $sheet->mergeCells('D'.$posR8.':H'.$posR8);
                                        $sheet->cells('D'.$posR8.':H'.$posR8, function($cells){
                                            $cells->setAlignment('center');
                                            $cells->setValignment('center');
                                        });
                                        $sheet->setBorder('D'.$posR8.':H'.$posR8, \PHPExcel_Style_Border::BORDER_THIN, "#000000"); 
                                        // Wraptext
                                        $sheet->getStyle('D'.$posR8.':'.'H'.$posR8)->getAlignment()->setWrapText(true);
                                        //製品名
                                        $sheet->setCellValue('I'.$posR8,'製品名');
                                        $sheet->mergeCells('I'.$posR8.':U'.$posR8);
                                        $sheet->cells('I'.$posR8.':U'.$posR8, function($cells){
                                            $cells->setAlignment('left');
                                            $cells->setValignment('center');
                                        });
                                        $sheet->setBorder('I'.$posR8.':U'.$posR8, \PHPExcel_Style_Border::BORDER_THIN, "#000000");
                                        //規格
                                        $sheet->setCellValue('V'.$posR8,'規格');
                                        $sheet->mergeCells('V'.$posR8.':AH'.$posR8);
                                        $sheet->cells('V'.$posR8.':AH'.$posR8, function($cells){
                                            $cells->setAlignment('left');
                                            $cells->setValignment('center');
                                        });
                                        $sheet->setBorder('V'.$posR8.':AH'.$posR8, \PHPExcel_Style_Border::BORDER_THIN, "#000000");
                                        //数量
                                        $sheet->setCellValue('AI'.$posR8,'数量');
                                        $sheet->mergeCells('AI'.$posR8.':AM'.$posR8);
                                        $sheet->cells('AI'.$posR8.':AM'.$posR8, function($cells){
                                            $cells->setAlignment('center');
                                            $cells->setValignment('center');
                                        });
                                        $sheet->setBorder('AI'.$posR8.':AM'.$posR8, \PHPExcel_Style_Border::BORDER_THIN, "#000000");
                                        //単位
                                        $sheet->setCellValue('AN'.$posR8,'単位');
                                        $sheet->mergeCells('AN'.$posR8.':AQ'.$posR8);
                                        $sheet->cells('AN'.$posR8.':AQ'.$posR8, function($cells){
                                            $cells->setAlignment('center');
                                            $cells->setValignment('center');
                                        });
                                        $sheet->setBorder('AN'.$posR8.':AQ'.$posR8, \PHPExcel_Style_Border::BORDER_THIN, "#000000");
                                        //シリアル番号
                                        $sheet->setCellValue('AR'.$posR8,'シリアル番号');
                                        $sheet->mergeCells('AR'.$posR8.':BG'.$posR8);
                                        $sheet->cells('AR'.$posR8.':BG'.$posR8, function($cells){
                                            $cells->setAlignment('center');
                                            $cells->setValignment('center');
                                        });
                                        $sheet->setBorder('AR'.$posR8.':BG'.$posR8, \PHPExcel_Style_Border::BORDER_THIN, "#000000");
                                        //
                                        $sheet->getRowDimension($posR8)->setRowHeight(29);

                                        // row 9
                                        $posR9 = $posR8+1;
                                        //No.
                                        $sheet->setCellValue('A'.$posR9,1);
                                        $sheet->mergeCells('A'.$posR9.':C'.$posR9);
                                        $sheet->cells('A'.$posR9.':C'.$posR9, function($cells){
                                            $cells->setAlignment('center');
                                            $cells->setValignment('center');
                                        });
                                        $sheet->setBorder('A'.$posR9.':C'.$posR9, \PHPExcel_Style_Border::BORDER_THIN, "#000000");  
                                        //製品 コード
                                        $sheet->setCellValue('D'.$posR9,$data_pagi[$i][0]['product_cd']);
                                        $sheet->mergeCells('D'.$posR9.':H'.$posR9);
                                        $sheet->cells('D'.$posR9.':H'.$posR9, function($cells){
                                            $cells->setAlignment('center');
                                            $cells->setValignment('center');
                                        });
                                        $sheet->setBorder('D'.$posR9.':H'.$posR9, \PHPExcel_Style_Border::BORDER_THIN, "#000000"); 
                                        // Wraptext
                                        $sheet->getStyle('D'.$posR9.':'.'H'.$posR9)->getAlignment()->setWrapText(true);
                                        //製品名
                                        $sheet->setCellValue('I'.$posR9,$data_pagi[$i][0]['product_nm']);
                                        $sheet->mergeCells('I'.$posR9.':U'.$posR9);
                                        $sheet->cells('I'.$posR9.':U'.$posR9, function($cells){
                                            $cells->setAlignment('left');
                                            $cells->setValignment('center');
                                        });
                                        $sheet->setBorder('I'.$posR9.':U'.$posR9, \PHPExcel_Style_Border::BORDER_THIN, "#000000");
                                        $sheet->getStyle('I'.$posR9.':U'.$posR9)->getAlignment()->setWrapText(true);
                                        //規格
                                        $sheet->setCellValue('V'.$posR9,$data_pagi[$i][0]['specification']);
                                        $sheet->mergeCells('V'.$posR9.':AH'.$posR9);
                                        $sheet->cells('V'.$posR9.':AH'.$posR9, function($cells){
                                            $cells->setAlignment('left');
                                            $cells->setValignment('center');
                                        });
                                        $sheet->setBorder('V'.$posR9.':AH'.$posR9, \PHPExcel_Style_Border::BORDER_THIN, "#000000");
                                        //数量
                                        $sheet->setCellValue('AI'.$posR9,$data_pagi[$i][0]['manufacture_qty']);
                                        $sheet->mergeCells('AI'.$posR9.':AM'.$posR9);
                                        $sheet->cells('AI'.$posR9.':AM'.$posR9, function($cells){
                                            $cells->setAlignment('center');
                                            $cells->setValignment('center');
                                        });
                                        $sheet->setBorder('AI'.$posR9.':AM'.$posR9, \PHPExcel_Style_Border::BORDER_THIN, "#000000");
                                        //単位
                                        $sheet->setCellValue('AN'.$posR9,$data_pagi[$i][0]['unit']);
                                        $sheet->mergeCells('AN'.$posR9.':AQ'.$posR9);
                                        $sheet->cells('AN'.$posR9.':AQ'.$posR9, function($cells){
                                            $cells->setAlignment('center');
                                            $cells->setValignment('center');
                                        });
                                        $sheet->setBorder('AN'.$posR9.':AQ'.$posR9, \PHPExcel_Style_Border::BORDER_THIN, "#000000");
                                        //シリアル番号
                                        $sheet->setCellValue('AR'.$posR9,$data_pagi[$i][0]['serial_no']);
                                        $sheet->mergeCells('AR'.$posR9.':BG'.$posR9);
                                        $sheet->cells('AR'.$posR9.':BG'.$posR9, function($cells){
                                            $cells->setAlignment('center');
                                            $cells->setValignment('center');
                                        });
                                        $sheet->setBorder('AR'.$posR9.':BG'.$posR9, \PHPExcel_Style_Border::BORDER_THIN, "#000000");
                                        //
                                        $sheet->getRowDimension($posR9)->setRowHeight(69);

                                        // row 10
                                        $posR10 = $posR9+1;
                                        //備考
                                        $sheet->setCellValue('A'.$posR10,'備考');
                                        $sheet->mergeCells('A'.$posR10.':C'.$posR10);
                                        $sheet->cells('A'.$posR10.':C'.$posR10, function($cells){
                                            $cells->setAlignment('center');
                                            $cells->setValignment('center');
                                        });
                                        $sheet->setCellValue('D'.$posR10,$data_pagi[$i][0]['remarks']);
                                        $sheet->mergeCells('D'.$posR10.':BG'.$posR10);
                                        $sheet->cells('D'.$posR10.':BG'.$posR10, function($cells){
                                            $cells->setAlignment('left');
                                            $cells->setValignment('center');
                                        });
                                        $sheet->getStyle('D'.$posR10.':'.'BG'.$posR10)->getAlignment()->setWrapText(true);
                                        $sheet->setBorder('A'.$posR10.':BG'.$posR10, \PHPExcel_Style_Border::BORDER_THIN, "#000000");
                                        $sheet->getRowDimension($posR10)->setRowHeight(68);

                                        // row 11
                                        $posR11 = $posR10+1;
                                        $sheet->getRowDimension($posR11)->setRowHeight(6);

                                        /*********************************************************************
                                        *  START - BORDER STYLE
                                        *********************************************************************/
                                        $sheet->setOrientation('portrait');                                
                                        //focus on A1 cell
                                        $sheet->setSelectedCells('A1');
                                        // Set top, right, bottom, left
                                        $sheet->setPageMargin(array(
                                            0.7, 0.2, 0.7, 0.5
                                        ));
                                        $sheet->getPageSetup()->setPaperSize(\PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
                                        
                                        /*********************************************************************
                                        *  END - BORDER STYLE
                                        *********************************************************************/ 

                                        /*********************************************************************
                                        *  START - FOOTER
                                        *********************************************************************/ 
                                        // row 12
                                        $posR12= $posR11+1;
                                        $sheet->setCellValue('AI'.$posR12,'');
                                        $sheet->mergeCells('AI'.$posR12.':AO'.$posR12);
                                        $sheet->cells('AI'.$posR12.':AO'.$posR12, function($cells){
                                            $cells->setAlignment('center');  
                                            $cells->setValignment('bottom');   
                                        });
                                        $sheet->setBorder('AI'.$posR12.':AO'.$posR12, \PHPExcel_Style_Border::BORDER_THIN, "#000000");
                                        //
                                        $sheet->setCellValue('AR'.$posR12,'');
                                        $sheet->mergeCells('AR'.$posR12.':AX'.$posR12);
                                        $sheet->cells('AR'.$posR12.':AX'.$posR12, function($cells){
                                            $cells->setAlignment('center');  
                                            $cells->setValignment('bottom');   
                                        });
                                        $sheet->setBorder('AR'.$posR12.':AX'.$posR12, \PHPExcel_Style_Border::BORDER_THIN, "#000000");
                                        //
                                        $sheet->setCellValue('BA'.$posR12,'');
                                        $sheet->mergeCells('BA'.$posR12.':BG'.$posR12);
                                        $sheet->cells('BA'.$posR12.':BG'.$posR12, function($cells){
                                            $cells->setAlignment('center');  
                                            $cells->setValignment('bottom');   
                                        });
                                        $sheet->setBorder('BA'.$posR12.':BG'.$posR12, \PHPExcel_Style_Border::BORDER_THIN, "#000000");
                                        $sheet->getRowDimension($posR12)->setRowHeight(18);



                                        /*********************************************************************
                                        *  END - FOOTER
                                        *********************************************************************/  

                                        // row 13
                                        $posR13= $posR12+1;                                
                                        $sheet->getStyle('AI'.$posR13.':AO'.$posR13)->getBorders()->getLeft()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN, "#000000");
                                        $sheet->getStyle('AI'.$posR13.':AO'.$posR13)->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN, "#000000");  
                                        //
                                        $sheet->getStyle('AR'.$posR13.':AX'.$posR13)->getBorders()->getLeft()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN, "#000000");
                                        $sheet->getStyle('AR'.$posR13.':AX'.$posR13)->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN, "#000000");  
                                        //
                                        $sheet->getStyle('BA'.$posR13.':BG'.$posR13)->getBorders()->getLeft()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN, "#000000");
                                        $sheet->getStyle('BA'.$posR13.':BG'.$posR13)->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN, "#000000");  
                                        //
                                        $sheet->getRowDimension($posR13)->setRowHeight(18);

                                        // row 14
                                        $posR14= $posR13+1;                                
                                        $sheet->getStyle('AI'.$posR14.':AO'.$posR14)->getBorders()->getLeft()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN, "#000000");
                                        $sheet->getStyle('AI'.$posR14.':AO'.$posR14)->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN, "#000000");  
                                        //
                                        $sheet->getStyle('AR'.$posR14.':AX'.$posR14)->getBorders()->getLeft()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN, "#000000");
                                        $sheet->getStyle('AR'.$posR14.':AX'.$posR14)->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN, "#000000");  
                                        //
                                        $sheet->getStyle('BA'.$posR14.':BG'.$posR14)->getBorders()->getLeft()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN, "#000000");
                                        $sheet->getStyle('BA'.$posR14.':BG'.$posR14)->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN, "#000000");  
                                        //
                                        $sheet->getRowDimension($posR14)->setRowHeight(18);

                                        // row 15
                                        $posR15= $posR14+1;                                
                                        $sheet->getStyle('AI'.$posR15.':AO'.$posR15)->getBorders()->getLeft()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN, "#000000");
                                        $sheet->getStyle('AI'.$posR15.':AO'.$posR15)->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN, "#000000");  
                                        $sheet->getStyle('AI'.$posR15.':AO'.$posR15)->getBorders()->getBottom()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN, "#000000");
                                        //
                                        $sheet->getStyle('AR'.$posR15.':AX'.$posR15)->getBorders()->getLeft()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN, "#000000");
                                        $sheet->getStyle('AR'.$posR15.':AX'.$posR15)->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN, "#000000");  
                                         $sheet->getStyle('AR'.$posR15.':AX'.$posR15)->getBorders()->getBottom()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN, "#000000");  
                                        //
                                        $sheet->getStyle('BA'.$posR15.':BG'.$posR15)->getBorders()->getLeft()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN, "#000000");
                                        $sheet->getStyle('BA'.$posR15.':BG'.$posR15)->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN, "#000000");  
                                        $sheet->getStyle('BA'.$posR15.':BG'.$posR15)->getBorders()->getBottom()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN, "#000000");
                                        //
                                        $sheet->getRowDimension($posR15)->setRowHeight(18);
                                        $sheet->setBreak( 'A'.$posR15, PHPExcel_Worksheet::BREAK_ROW);
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
            $zipFileName    =   '製造指示書_'.date("YmdHis").'.zip';

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
                    'error_product' => $error_product,
                ]);
            }

            
        } catch (\Exception $e) {
            return response()->json(array(
                        'response'      => false,
                        'error'         => $e->getMessage(),
                    ));
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

    /*
    * getDataDetail
    * -----------------------------------------------
    * @author      :   ANS810 - 2018/02/01 - create
    * @param       :   
    * @return      :   format data footer excel
    * @access      :   protected
    * @see         :   remark
    */
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
