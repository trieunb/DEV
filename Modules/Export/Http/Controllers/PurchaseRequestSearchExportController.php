<?php
/**
*|--------------------------------------------------------------------------
*| Purchase Request Output
*|--------------------------------------------------------------------------
*| Package       : Apel
*| @author       : ANS796 - tuannt@ans-asia.com
*| @created date : 2018/01/23
*| 
*/
namespace Modules\Export\Http\Controllers;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Excel, PHPExcel_Worksheet_Drawing;
use Session, DB, Dao, Button;
use Modules\Common\Http\Controllers\CommonController as common;
class PurchaseRequestSearchExportController extends Controller
{
    protected $file_excel   = '購入依頼書_';
    protected $totalLine    = '58';
    /*
     * Header
     * @var array
     */
    private $header = [
        '購入依頼日',
        '購入依頼番号',
        '行番号',
        'ステータス',
        '仕入先コード',
        '仕入先名',
        '発注日',
        'コード',
        '品名',
        '規格',
        '数量',
        '単位',
        '単価',
        '金額',
        '備考'
    ];
    /**
    * Purchase Request Output
    * -----------------------------------------------
    * @author      :   ANS806 - 2017/12/05 - create
    * @param       :
    * @return      :   mixed
    * @access      :   public
    * @see         :   remark
    */
    public function postOutput(Request $request) {
        try {
            $param              =   $request->all();
            $sql                =   "SPC_032_PURCHASE_REQUEST_SEARCH_INQ1"; 
            $result             =   Dao::call_stored_procedure($sql, $param, true);
            $result[0]          = isset($result[0]) ? $result[0] : NULL;
            //width of columns
            $arrWidthColumns    =   array(
                'A'     =>  15,
                'B'     =>  20,
                'C'     =>  10,
                'D'     =>  15,
                'E'     =>  15,
                'F'     =>  30,
                'G'     =>  15,
                'H'     =>  15,
                'I'     =>  15,
                'J'     =>  30,
                'K'     =>  15,
                'L'     =>  15,
                'M'     =>  15,
                'N'     =>  18,
                'O'     =>  30,
            );
            if ( !is_null($result[0])) {
                $filename    = $this->file_excel.date("YmdHis");
                \Excel::create($filename, function($excel) use ($filename, $result, $arrWidthColumns) {
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
                        $sheet->getStyle('A1:O1')->applyFromArray($styleAllBorder);
                        $sheet->getStyle('A1:O1')->getFont()->getColor()->setRGB('ffffff');
                        $sheet->cells('A1:O1', function($cells) {
                            $cells->setBackground('#248af0');
                            $cells->setAlignment('center');
                            $cells->setValignment('center');
                        });
                        //write data to excel file
                        foreach ($result[0] as $k1 => $v1) {
                            $row ++;
                            $sheet->getStyle('A'.$row.':O'.$row)->applyFromArray($styleAllBorder);
                            $sheet->getStyle('A'.$row.':O'.$row)->getAlignment()->setWrapText(true);
                            $sheet->row($row, array(
                                $v1['buy_date'], 
                                $v1['buy_no'], 
                                $v1['buy_detail_no'], 
                                $v1['buy_status'], 
                                $v1['supplier_cd'], 
                                $v1['supplier_nm'],
                                $v1['parts_order_date'],
                                $v1['parts_cd'],
                                $v1['parts_nm'],
                                $v1['specification'],
                                $v1['buy_qty'],
                                $v1['buy_unit_nm'],
                                $v1['qty_unit_price'],
                                $v1['buy_detail_amt'],
                                $v1['detail_remarks'],
                            ));

                            //even odd row background color
                            if($row % 2 != 0){
                                $sheet->cells('A'.$row.':O'.$row, function($cells) {
                                    $cells->setBackground('#FFF2CC');
                                });
                            }
                            //align left data
                            $sheet->cells('A'.$row, function($cells) {
                                $cells->setAlignment('center');
                                $cells->setValignment('center');
                            });
                            //align left data
                            $sheet->cells('B'.$row, function($cells) {
                                $cells->setAlignment('left');
                                $cells->setValignment('center');
                            });
                            //align left data
                            $sheet->cells('C'.$row, function($cells) {
                                $cells->setAlignment('right');
                                $cells->setValignment('center');
                            });
                            //align left data
                            $sheet->cells('D'.$row.':F'.$row, function($cells) {
                                $cells->setAlignment('left');
                                $cells->setValignment('center');
                            });
                            //align left data
                            $sheet->cells('G'.$row, function($cells) {
                                $cells->setAlignment('center');
                                $cells->setValignment('center');
                            });
                            $sheet->cells('H'.$row.':J'.$row, function($cells) {
                                $cells->setAlignment('left');
                                $cells->setValignment('center');
                            });
                            //align left data
                            $sheet->cells('K'.$row, function($cells) {
                                $cells->setAlignment('right');
                                $cells->setValignment('center');
                            });
                            //align left data
                            $sheet->cells('L'.$row, function($cells) {
                                $cells->setAlignment('left');
                                $cells->setValignment('center');
                            });
                            $sheet->cells('M'.$row.':N'.$row, function($cells) {
                                $cells->setAlignment('right');
                                $cells->setValignment('center');
                            });
                            //align left data
                            $sheet->cells('O'.$row, function($cells) {
                                $cells->setAlignment('left');
                                $cells->setValignment('center');
                            });
                        }
                        $sheet->setOrientation('portrait');
                        //focus on A1 cell
                        $sheet->setSelectedCells('A1');
                    });
                })->store('xlsx', DOWNLOAD_EXCEL_PUBLIC);
                return response(array(
                    'response'  =>  true, 
                    'filename'  =>  DOWNLOAD_EXCEL.$filename.'.xlsx'));
            } else {
                return response(array('response'=> false));
            }
        } catch (\Exception $e) {
            return response()->json(array('response' => false));
        }
    }
    /**
    * Purchase Request Export
    * -----------------------------------------------
    * @author      :   ANS806 - 2017/12/05 - create
    * @param       :
    * @return      :   mixed
    * @access      :   public
    * @see         :   remark
    */
    public function postExport(Request $request) {
        try {
            //data master layout
            $header         =   common::getDataHeaderExcel('ctl_val1');

            $data                   = $request->all();
            $data['buy_list']       =  json_encode($data['buy_list']);//parse json to string
            $data['cre_user_cd']    = \GetUserInfo::getInfo('user_cd');
            $data['cre_prg_cd']     = '032_purchase_request-search';
            $data['cre_ip']         = \GetUserInfo::getInfo('user_ip');

            $sql        =   "SPC_033_PURCHASE_REQUEST_EXPORT_ACT1"; 
            $result     =   Dao::call_stored_procedure($sql, $data, true);

            // return $result; die;
            $response   =   true;
            $error      =   [];
            $error_cd   =   [];
            $zip_array  =   [];
            $error_flag =   false;
            if (!empty($result[0])) {
                $response   =   false;
                $error_cd   =   '';
                $error      =   $result[0][0]['Message'];
            } else {
                if (isset($result[1]) && !empty($result[1][0]['error_cd'])) {
                    $response   =   true;
                    $error_cd   =   $result[1][0]['error_cd'];
                } else {
                    //width of columns
                    $arrWidthColumns     =   [
                    'A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z',
                    'AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ',
                    'BA','BB','BC','BD','BE','BF','BG','BH','BI','BJ','BK','BL','BM','BN','BO','BP','BQ','BR','BS','BT','BU','BV','BW','BX','BY','BZ',
                    ];
                    $arrWidthColumns    = array_fill_keys($arrWidthColumns, 1.67);
                    $marginPage         =   [0.4, 0.3, 0.4, 0.4];
                    
                    for ($k = 0; $k < count($result[2]); $k++) {
                        // get data by key
                        $key    = ['buy_no' => $result[2][$k]['buy_no']];
                        // get data array buy_h by key
                        $buy_h  = getDataByKey($key, $result[2])[0];
                        // get data array buy_d by key
                        $buy_d  = getDataByKey($key, $result[3]);
                        // get data pagination of each page
                        $data_pagi      = dataPageExcel($buy_d, 15);
                        // $file_name  =   'buy_'.$request->buy_no.$i;
                        $file_name  =   $this->file_excel.$key['buy_no'];
                        // **********************************************************************
                        //      Export Excel
                        // **********************************************************************
                        Excel::create($file_name, function($excel) use ($data_pagi, $header, $buy_h, $buy_d, $arrWidthColumns, $marginPage) {
                            $excel->sheet('Sheet 1', function($sheet) use ($data_pagi, $header, $buy_h, $buy_d, $arrWidthColumns, $marginPage) {
                                // set font for excel
                                $sheet->getDefaultStyle()->getFont()->setName('ＭＳ Ｐゴシック')->setSize(12);
                                // set width for colum excel
                                $sheet->setWidth($arrWidthColumns);
                                // set Gridlines
                                $sheet->setShowGridlines(false);
                                //set margin for page
                                $sheet->setPageMargin($marginPage);
                                // $sheet->getPageSetup()->setPaperSize(\PHPExcel_Worksheet_PageSetup::PAPERSIZE_B5);
                                // Init 
                                // Vị trí mỗi page ( First postition of every page)
                                $pos        =   0;
                                // Sum height header and footer
                                $page_size  =   24;
                                // pagination
                                for ($i = 0; $i < count($data_pagi); $i++) {
                                    if ($i == 0) {
                                        $pos    =   $i + 1;
                                    } else {
                                        $pos    =   $pos + ($page_size+count($data_pagi[$i-1])) + 1;
                                    }
                                    /*********************************************************************
                                    *  HEADER
                                    *********************************************************************/
                                    /*
                                     * create and format header
                                     * row 1 -> 12
                                    */
                                    //1. Set value for 部品発注書
                                    //create text header
                                    $textHeaderFormTitle = new \PHPExcel_RichText();
                                    $objBold = $textHeaderFormTitle->createTextRun('購入依頼書');
                                    $objBold->getFont()->setName('ＭＳ Ｐゴシック')
                                                       ->setSize(26)
                                                       ->setBold(true);

                                    $sheet->setCellValue('AF'.$pos, $textHeaderFormTitle);

                                    //発注日
                                    $sheet->setCellValue('BB'.$pos,'購入依頼日');
                                    $sheet->mergeCells('BB'.$pos.':BK'.$pos);
                                    $sheet->cells('BB'.$pos.':BK'.$pos, function($cells){
                                        $cells->setAlignment('left');  
                                        $cells->setValignment('center');   
                                    });
                                    $sheet->getStyle('BB'.$pos.':BK'.$pos)->applyFromArray(getStyleExcel('fontBold'));

                                    $sheet->setCellValue('BP'.$pos, $buy_h['buy_date']);
                                    $sheet->mergeCells('BP'.$pos.':BY'.$pos);
                                    $sheet->cells('BP'.$pos.':BY'.$pos, function($cells){
                                        $cells->setAlignment('center');  
                                        $cells->setValignment('center');   
                                    });

                                    /////////////////////////////////////////////////////////////
                                    $posR2 = $pos+1;

                                    //注文番号
                                    $sheet->setCellValue('BB'.$posR2,'購入依頼番号');
                                    $sheet->mergeCells('BB'.$posR2.':BK'.$posR2);
                                    $sheet->cells('BB'.$posR2.':BK'.$posR2, function($cells){
                                        $cells->setAlignment('left');  
                                        $cells->setValignment('center');   
                                    });
                                    $sheet->getStyle('BB'.$posR2.':BK'.$posR2)->applyFromArray(getStyleExcel('fontBold'));

                                    $sheet->setCellValue('BP'.$posR2,$buy_h['buy_no']);
                                    $sheet->mergeCells('BP'.$posR2.':BZ'.$posR2);
                                    $sheet->cells('BP'.$posR2.':BZ'.$posR2, function($cells){
                                        $cells->setAlignment('center');  
                                        $cells->setValignment('bottom');   
                                    });

                                    /////////////////////////////////////////////////////////////
                                    $posR3 = $pos+2;

                                    //supplier_nm
                                    $sheet->setCellValue('A'.$posR3,$buy_h['supplier_nm']);
                                    $sheet->mergeCells('A'.$posR3.':AR'.$posR3);

                                    /////////////////////////////////////////////////////////////
                                    $posR4 = $pos+3;

                                    //supplier_staff_nm
                                    $sheet->setCellValue('A'.$posR4,$buy_h['supplier_staff_nm']);
                                    $sheet->mergeCells('A'.$posR4.':AR'.$posR4);
                                    //company_nm
                                    $sheet->setCellValue('BC'.$posR4,$header['company_nm']);
                                    $sheet->mergeCells('BC'.$posR4.':BZ'.$posR4);
                                    

                                    /////////////////////////////////////////////////////////////
                                    $posR5 = $pos+4;

                                    //contact_nm
                                    $sheet->setCellValue('BI'.$posR5,'担当： '.$buy_h['contact_nm']);
                                    $sheet->mergeCells('BI'.$posR5.':BZ'.$posR5);

                                    /////////////////////////////////////////////////////////////
                                    $posR6 = $pos+5;

                                    //supplier_tel
                                    $sheet->setCellValue('A'.$posR6,'TEL:'.$buy_h['supplier_tel']);
                                    $sheet->mergeCells('A'.$posR6.':Q'.$posR6);
                                    //supplier_fax
                                    $sheet->setCellValue('R'.$posR6,'FAX:'.$buy_h['supplier_fax']);
                                    $sheet->mergeCells('R'.$posR6.':AH'.$posR6);
                                    //company_zip
                                    $sheet->setCellValue('BD'.$posR6,'〒'.$header['company_zip']);
                                    $sheet->mergeCells('BD'.$posR6.':BZ'.$posR6);

                                    /////////////////////////////////////////////////////////////
                                    $posR7 = $pos+6;
                                    $posR8 = $pos+7;

                                    //件名
                                    $sheet->setCellValue('A'.$posR7,'件名');
                                    $sheet->mergeCells('A'.$posR7.':C'.$posR8);
                                    $sheet->cells('A'.$posR7.':C'.$posR8, function($cells){
                                        $cells->setAlignment('center');
                                        $cells->setValignment('center');
                                    });
                                    $sheet->getStyle('A'.$posR7.':C'.$posR8)->applyFromArray(getStyleExcel('fontBold'));

                                    //supplier_nm
                                    $sheet->setCellValue('D'.$posR7,$buy_h['subject_nm']);
                                    $sheet->mergeCells('D'.$posR7.':Y'.$posR8);
                                    $sheet->cells('D'.$posR7.':Y'.$posR8, function($cells){
                                        $cells->setAlignment('left');
                                        $cells->setValignment('top');
                                    });
                                    //company_address
                                    $sheet->setCellValue('BD'.$posR7,$header['company_address']);
                                    $sheet->mergeCells('BD'.$posR7.':BZ'.$posR7);
                                    //company_tel
                                    $sheet->setCellValue('BB'.$posR8,'TEL:'.$header['company_tel']);
                                    $sheet->mergeCells('BB'.$posR8.':BN'.$posR8);
                                    //company_fax
                                    $sheet->setCellValue('BO'.$posR8,'FAX:'.$header['company_fax']);
                                    $sheet->mergeCells('BO'.$posR8.':BZ'.$posR8);

                                    /////////////////////////////////////////////////////////////
                                    $posR9  = $pos+8;
                                    //set border
                                    $sheet->setBorder('AZ'.$posR9.':BZ'.$posR9, \PHPExcel_Style_Border::BORDER_THIN, "#000000");
                                    //希望納期
                                    $sheet->setCellValue('AZ'.$posR9,'希望納期');
                                    $sheet->mergeCells('AZ'.$posR9.':BK'.$posR9);
                                    $sheet->cells('AZ'.$posR9.':BK'.$posR9, function($cells){
                                        $cells->setAlignment('center');  
                                        $cells->setValignment('center');   
                                    });
                                    $sheet->getRowDimension($posR9)->setRowHeight(15);
                                    $sheet->getStyle('AZ'.$posR9.':BK'.$posR9)->applyFromArray(getStyleExcel('fontBold'));
                                    //hope_delivery_date
                                    $sheet->setCellValue('BL'.$posR9,$buy_h['hope_delivery_date']);
                                    $sheet->mergeCells('BL'.$posR9.':BZ'.$posR9);
                                    $sheet->cells('BL'.$posR9.':BZ'.$posR9, function($cells){
                                        $cells->setAlignment('center');  
                                        $cells->setValignment('center');   
                                    });

                                    /////////////////////////////////////////////////////////////
                                    $posR11 = $pos+9;
                                    $posR12 = $pos+10;
                                    $sheet->getRowDimension($posR11)->setRowHeight(10);
                                    $sheet->getRowDimension($posR12)->setRowHeight(4);

                                    /*
                                     * Set text title for header detail
                                    */

                                    /////////////////////////////////////////////////////////////
                                    $posR13 = $pos+11;
                                    $sheet->getRowDimension($posR13)->setRowHeight(15);
                                    // 番号
                                    $sheet->setCellValue('A'.$posR13,'番号');
                                    $sheet->mergeCells('A'.$posR13.':C'.$posR13);                        
                                    $sheet->cells('A'.$posR13.':C'.$posR13, function($cells){
                                        $cells->setAlignment('center');  
                                        $cells->setValignment('center');   
                                    });
                                    $sheet->getStyle('A'.$posR13.':C'.$posR13)->applyFromArray(getStyleExcel('fontBold'));
                                    //コード
                                    $sheet->setCellValue('D'.$posR13,'コード');
                                    $sheet->mergeCells('D'.$posR13.':G'.$posR13);                        
                                    $sheet->cells('D'.$posR13.':G'.$posR13, function($cells){
                                        $cells->setAlignment('center');  
                                        $cells->setValignment('center');   
                                    });
                                    //品名
                                    $sheet->setCellValue('H'.$posR13,'品名');
                                    $sheet->mergeCells('H'.$posR13.':S'.$posR13);                        
                                    $sheet->cells('H'.$posR13.':S'.$posR13, function($cells){
                                        $cells->setAlignment('center');  
                                        $cells->setValignment('center');   
                                    });
                                    //規格
                                    $sheet->setCellValue('T'.$posR13,'規格');
                                    $sheet->mergeCells('T'.$posR13.':AH'.$posR13);                        
                                    $sheet->cells('T'.$posR13.':AH'.$posR13, function($cells){
                                        $cells->setAlignment('center');  
                                        $cells->setValignment('center');   
                                    });
                                    //数量
                                    $sheet->setCellValue('AI'.$posR13,'数量');
                                    $sheet->mergeCells('AI'.$posR13.':AO'.$posR13);                        
                                    $sheet->cells('AI'.$posR13.':AO'.$posR13, function($cells){
                                        $cells->setAlignment('center');  
                                        $cells->setValignment('center');   
                                    });
                                    //単位
                                    $sheet->setCellValue('AP'.$posR13,'単位');
                                    $sheet->mergeCells('AP'.$posR13.':AS'.$posR13);                        
                                    $sheet->cells('AP'.$posR13.':AS'.$posR13, function($cells){
                                        $cells->setAlignment('center');  
                                        $cells->setValignment('center');   
                                    });
                                    //単価
                                    $sheet->setCellValue('AT'.$posR13,'単価');
                                    $sheet->mergeCells('AT'.$posR13.':BA'.$posR13);                        
                                    $sheet->cells('AT'.$posR13.':BA'.$posR13, function($cells){
                                        $cells->setAlignment('center');  
                                        $cells->setValignment('center');   
                                    });
                                    //金額
                                    $sheet->setCellValue('BB'.$posR13,'金額');
                                    $sheet->mergeCells('BB'.$posR13.':BK'.$posR13);                        
                                    $sheet->cells('BB'.$posR13.':BK'.$posR13, function($cells){
                                        $cells->setAlignment('center');  
                                        $cells->setValignment('center');   
                                    });
                                    //備考
                                    $sheet->setCellValue('BL'.$posR13,'備考');
                                    $sheet->mergeCells('BL'.$posR13.':BZ'.$posR13);                        
                                    $sheet->cells('BL'.$posR13.':BZ'.$posR13, function($cells){
                                        $cells->setAlignment('center');  
                                        $cells->setValignment('center');   
                                    });
                                    $sheet->getStyle('A'.$posR13.':BL'.$posR13)->applyFromArray(getStyleExcel('fontBold'));
                                    /*********************************************************************
                                    *  END - HEADER
                                    *********************************************************************/

                                    /////////////////////////////////////////////////////////////
                                    $posR14 = $pos+12;
                                    //write data to excel file.

                                    foreach ($data_pagi[$i] as $k => $v) {
                                        /*
                                         * Render data
                                        */
                                        $row = $posR14 + $k;
                                        $sheet->getRowDimension($row)->setRowHeight(51);
                                        // 番号 - parts_order_detail_no
                                        $sheet->setCellValue('A'.$row,$v['buy_detail_no']);
                                        $sheet->mergeCells('A'.$row.':C'.$row);                        
                                        $sheet->cells('A'.$row.':C'.$row, function($cells){
                                            $cells->setAlignment('center');  
                                            $cells->setValignment('center');   
                                        });
                                        //コード - parts_cd
                                        $sheet->setCellValue('D'.$row,$v['parts_cd']);
                                        $sheet->mergeCells('D'.$row.':G'.$row);                        
                                        $sheet->cells('D'.$row.':G'.$row, function($cells){
                                            $cells->setAlignment('center');  
                                            $cells->setValignment('center');   
                                        });
                                        //品名 - parts_nm
                                        $sheet->setCellValue('H'.$row,$v['parts_nm']);
                                        $sheet->mergeCells('H'.$row.':S'.$row);                        
                                        $sheet->cells('H'.$row.':S'.$row, function($cells){
                                            $cells->setAlignment('center');  
                                            $cells->setValignment('center');   
                                        });
                                        //規格 - specification
                                        $sheet->setCellValue('T'.$row,$v['specification']);
                                        $sheet->mergeCells('T'.$row.':AH'.$row);                        
                                        $sheet->cells('T'.$row.':AH'.$row, function($cells){
                                            $cells->setAlignment('center');  
                                            $cells->setValignment('center');   
                                        });
                                        //数量 - parts_order_qty
                                        $sheet->setCellValue('AI'.$row,$v['buy_qty']);
                                        $sheet->mergeCells('AI'.$row.':AO'.$row);                        
                                        $sheet->cells('AI'.$row.':AO'.$row, function($cells){
                                            $cells->setAlignment('center');  
                                            $cells->setValignment('center');   
                                        });
                                        //単位 - unit
                                        $sheet->setCellValue('AP'.$row,$v['buy_unit']);
                                        $sheet->mergeCells('AP'.$row.':AS'.$row);                        
                                        $sheet->cells('AP'.$row.':AS'.$row, function($cells){
                                            $cells->setAlignment('center');  
                                            $cells->setValignment('center');   
                                        });
                                        //単価 - parts_order_unit_price
                                        $sheet->setCellValue('AT'.$row,$v['buy_unit_price']);
                                        $sheet->mergeCells('AT'.$row.':BA'.$row);                        
                                        $sheet->cells('AT'.$row.':BA'.$row, function($cells){
                                            $cells->setAlignment('center');  
                                            $cells->setValignment('center');   
                                        });
                                        //金額 - parts_order_amt
                                        $sheet->setCellValue('BB'.$row,$v['buy_detail_amt']);
                                        $sheet->mergeCells('BB'.$row.':BK'.$row);                        
                                        $sheet->cells('BB'.$row.':BK'.$row, function($cells){
                                            $cells->setAlignment('center');  
                                            $cells->setValignment('center');   
                                        });
                                        //備考 - detail_remarks
                                        $sheet->setCellValue('BL'.$row,$v['detail_remarks']);
                                        $sheet->mergeCells('BL'.$row.':BZ'.$row);                        
                                        $sheet->cells('BL'.$row.':BZ'.$row, function($cells){
                                            $cells->setAlignment('center');  
                                            $cells->setValignment('center');   
                                        });
                                        //Wraptext for 品名 - parts_nm
                                        $sheet->getStyle('H'.$row.':'.'S'.$row)->getAlignment()->setWrapText(true);
                                        //Wraptext for 規格 - specification
                                        $sheet->getStyle('T'.$row.':'.'AH'.$row)->getAlignment()->setWrapText(true);
                                        //Wraptext for //備考 - detail_remarks
                                        $sheet->getStyle('BL'.$row.':'.'BZ'.$row)->getAlignment()->setWrapText(true);
                                    }

                                    /*********************************************************************
                                    *  START - BORDER STYLE FOR DETAIL
                                    *********************************************************************/ 
                                    $posR28 = $pos+26;

                                    // Set fix row height
                                    for( $j = $posR14; $j <= $posR28; $j++ ){
                                        $sheet->getRowDimension($j)->setRowHeight(51);

                                        $sheet->mergeCells('A'.$j.':'.'C'.$j);                            
                                        $sheet->cells('A'.$j.':'.'C'.$j, function($cells) use($v){
                                            $cells->setAlignment('center');
                                            $cells->setValignment('center');
                                        });
                                        $sheet->mergeCells('D'.$j.':'.'G'.$j);
                                        $sheet->cells('D'.$j.':'.'G'.$j, function($cells) use($v){
                                            $cells->setAlignment('left');  
                                            $cells->setValignment('center');   
                                        });
                                        $sheet->mergeCells('H'.$j.':'.'S'.$j);
                                        $sheet->cells('H'.$j.':'.'S'.$j, function($cells) use($v){
                                            $cells->setAlignment('left');  
                                            $cells->setValignment('center');   
                                        });
                                        $sheet->mergeCells('T'.$j.':'.'AH'.$j);
                                        $sheet->cells('T'.$j.':'.'AH'.$j, function($cells) use($v){
                                            $cells->setAlignment('left');  
                                            $cells->setValignment('center');   
                                        });
                                        $sheet->mergeCells('AI'.$j.':'.'AO'.$j);
                                        $sheet->cells('AI'.$j.':'.'AO'.$j, function($cells) use($v){
                                            $cells->setAlignment('right');  
                                            $cells->setValignment('center');   
                                        });
                                        $sheet->mergeCells('AP'.$j.':'.'AS'.$j);
                                        $sheet->cells('AP'.$j.':'.'AS'.$j, function($cells) use($v){
                                            $cells->setAlignment('center');
                                            $cells->setValignment('center');
                                        });
                                        $sheet->mergeCells('AT'.$j.':'.'BA'.$j);
                                        $sheet->cells('AT'.$j.':'.'BA'.$j, function($cells) use($v){
                                            $cells->setAlignment('right');
                                            $cells->setValignment('center');
                                        });
                                        $sheet->mergeCells('BB'.$j.':'.'BK'.$j);
                                        $sheet->cells('BB'.$j.':'.'BK'.$j, function($cells) use($v){
                                            $cells->setAlignment('right');
                                            $cells->setValignment('center');
                                        });
                                        $sheet->mergeCells('BL'.$j.':'.'BZ'.$j);
                                        $sheet->cells('BL'.$j.':'.'BZ'.$j, function($cells) use($v){
                                            $cells->setAlignment('left');
                                            $cells->setValignment('center');
                                        });
                                    }
                                    
                                    $sheet->setOrientation('portrait');
                                    //focus on A1 cell
                                    $sheet->setSelectedCells('A1');
                                    /*********************************************************************
                                    *  END - BORDER STYLE
                                    *********************************************************************/  

                                    /*********************************************************************
                                    *  START - FOOTER
                                    *********************************************************************/
                                    /////////////////////////////////////////////////////////////
                                    $posR29 = $pos+27;
                                    //set value total for footer
                                    $total_detail_amt   = '';
                                    $total_tax          = '';
                                    $total_amt          = '';
                                    if ($i == (count($data_pagi) - 1)) {
                                        $total_detail_amt   = $buy_h['total_detail_amt'];
                                        $total_tax          = $buy_h['total_tax'];
                                        $total_amt          = $buy_h['total_amt'];
                                    }

                                    // 小計
                                    $sheet->setCellValue('AT'.$posR29,'小計');
                                    $sheet->mergeCells('AT'.$posR29.':BA'.$posR29);
                                    $sheet->cells('AT'.$posR29.':BA'.$posR29, function($cells){
                                        $cells->setAlignment('right');
                                        $cells->setValignment('center');
                                    });
                                    $sheet->getRowDimension($posR29)->setRowHeight(20);
                                    $sheet->getStyle('AT'.$posR29.':BA'.$posR29)->applyFromArray(getStyleExcel('fontBold'));

                                    $sheet->setCellValue('BB'.$posR29,$total_detail_amt);
                                    $sheet->mergeCells('BB'.$posR29.':BK'.$posR29);
                                    $sheet->cells('BB'.$posR29.':BK'.$posR29, function($cells){
                                        $cells->setAlignment('right');
                                        $cells->setValignment('center');
                                    });
                                    $sheet->mergeCells('BL'.$posR29.':BZ'.$posR29);

                                    //
                                    $sheet->cells('A'.$posR29, function($cells) {
                                        $cells->setBorder('thin', 'none', 'none', 'thin', \PHPExcel_Style_Border::BORDER_THIN);
                                    });
                                    /////////////////////////////////////////////////////////////
                                    $posR30 = $pos+28;
                                    // 消費税
                                    $sheet->setCellValue('AT'.$posR30,'消費税');
                                    $sheet->mergeCells('AT'.$posR30.':BA'.$posR30);
                                    $sheet->cells('AT'.$posR30.':BA'.$posR30, function($cells){
                                        $cells->setAlignment('right');
                                        $cells->setValignment('center');
                                    });
                                    $sheet->getRowDimension($posR30)->setRowHeight(20);
                                    $sheet->getStyle('AT'.$posR30.':BA'.$posR30)->applyFromArray(getStyleExcel('fontBold'));

                                    $sheet->setCellValue('BB'.$posR30,$total_tax);
                                    $sheet->mergeCells('BB'.$posR30.':BK'.$posR30);
                                    $sheet->cells('BB'.$posR30.':BK'.$posR30, function($cells){
                                        $cells->setAlignment('right');
                                        $cells->setValignment('center');
                                    });
                                    $sheet->mergeCells('BL'.$posR30.':BZ'.$posR30);
                                    
                                    //
                                    $sheet->cells('A'.$posR30, function($cells) {
                                        $cells->setBorder('none', 'none', 'none', 'thin', \PHPExcel_Style_Border::BORDER_THIN);
                                    });
                                    /////////////////////////////////////////////////////////////
                                    $posR31 = $pos+29;
                                    // 合計
                                    $sheet->setCellValue('AT'.$posR31,'合計');
                                    $sheet->mergeCells('AT'.$posR31.':BA'.$posR31);
                                    $sheet->cells('AT'.$posR31.':BA'.$posR31, function($cells){
                                        $cells->setAlignment('right');
                                        $cells->setValignment('center');
                                    });
                                    $sheet->getRowDimension($posR31)->setRowHeight(20);
                                    $sheet->getStyle('AT'.$posR31.':BA'.$posR31)->applyFromArray(getStyleExcel('fontBold'));

                                    $sheet->setCellValue('BB'.$posR31,$total_amt);
                                    $sheet->mergeCells('BB'.$posR31.':BK'.$posR31);
                                    $sheet->cells('BB'.$posR31.':BK'.$posR31, function($cells){
                                        $cells->setAlignment('right');
                                        $cells->setValignment('center');
                                    });
                                    $sheet->mergeCells('BL'.$posR31.':BZ'.$posR31);

                                    //
                                    $sheet->cells('A'.$posR31, function($cells) {
                                        $cells->setBorder('none', 'none', 'thin', 'thin', \PHPExcel_Style_Border::BORDER_THIN);
                                    });
                                    /////////////////////////////////////////////////////////////
                                    $posR32 = $pos+30;
                                    $posR34 = $pos+32;
                                    // 備考
                                    $sheet->setCellValue('A'.$posR32,'備考');
                                    $sheet->mergeCells('A'.$posR32.':C'.$posR34);
                                    $sheet->cells('A'.$posR32.':C'.$posR32, function($cells){
                                        $cells->setAlignment('center');
                                        $cells->setValignment('center');
                                    });
                                    $sheet->getStyle('A'.$posR32.':C'.$posR32)->applyFromArray(getStyleExcel('fontBold'));

                                    $sheet->setCellValue('D'.$posR32, $buy_h['remarks']);
                                    $sheet->mergeCells('D'.$posR32.':BZ'.$posR34);
                                    $sheet->cells('D'.$posR32.':BZ'.$posR34, function($cells){
                                        $cells->setAlignment('left');
                                        $cells->setValignment('top');
                                    });

                                    /////////////////////////////////////////////////////////////
                                    $posR35 = $pos+33;
                                    $sheet->getRowDimension($posR35)->setRowHeight(5);

                                    /////////////////////////////////////////////////////////////
                                    $posR36 = $pos+34;
                                    $posR37 = $pos+35;
                                    $posR39 = $pos+37;

                                    // 総務部長
                                    $sheet->setCellValue('AZ'.$posR36,'総務部長');
                                    $sheet->mergeCells('AZ'.$posR36.':BF'.$posR36);
                                    $sheet->cells('AZ'.$posR36.':BF'.$posR36, function($cells){
                                        $cells->setAlignment('center');  
                                        $cells->setValignment('bottom');   
                                        $cells->setFont(array(
                                            'size' => 11
                                        ));
                                    });
                                    $sheet->mergeCells('AZ'.$posR37.':BF'.$posR39);
                                    $sheet->getStyle('AZ'.$posR36.':BF'.$posR36)->applyFromArray(getStyleExcel('fontBold'));
                                    // 所属長
                                    $sheet->setCellValue('BJ'.$posR36,'所属長');
                                    $sheet->mergeCells('BJ'.$posR36.':BP'.$posR36);
                                    $sheet->cells('BJ'.$posR36.':BP'.$posR36, function($cells){
                                        $cells->setAlignment('center');  
                                        $cells->setValignment('bottom');   
                                        $cells->setFont(array(
                                            'size' => 11
                                        ));
                                    });
                                    $sheet->mergeCells('BJ'.$posR37.':BP'.$posR39);
                                    $sheet->getStyle('BJ'.$posR36.':BP'.$posR36)->applyFromArray(getStyleExcel('fontBold'));
                                    // 担当者
                                    $sheet->setCellValue('BT'.$posR36,'担当者');
                                    $sheet->mergeCells('BT'.$posR36.':BZ'.$posR36);
                                    $sheet->cells('BT'.$posR36.':BZ'.$posR36, function($cells){
                                        $cells->setAlignment('center');  
                                        $cells->setValignment('bottom');   
                                        $cells->setFont(array(
                                            'size' => 11
                                        ));
                                    });
                                    $sheet->mergeCells('BT'.$posR37.':BZ'.$posR39);
                                    $sheet->getStyle('BT'.$posR36.':BZ'.$posR36)->applyFromArray(getStyleExcel('fontBold'));

                                    // set border for page
                                    $sheet->setBorder('A'.$posR13.':BZ'.$posR28, \PHPExcel_Style_Border::BORDER_THIN, "#000000");
                                    $sheet->setBorder('A'.$posR7.':Y'.$posR8, \PHPExcel_Style_Border::BORDER_THIN, "#000000");
                                    $sheet->setBorder('BB'.$posR29.':BZ'.$posR31, \PHPExcel_Style_Border::BORDER_THIN, "#000000");
                                    $sheet->setBorder('A'.$posR32.':BF'.$posR34, \PHPExcel_Style_Border::BORDER_THIN, "#000000");
                                    $sheet->setBorder('AZ'.$posR36.':BF'.$posR39, \PHPExcel_Style_Border::BORDER_THIN, "#000000");
                                    $sheet->setBorder('BJ'.$posR36.':BP'.$posR39, \PHPExcel_Style_Border::BORDER_THIN, "#000000");
                                    $sheet->setBorder('BT'.$posR36.':BZ'.$posR39, \PHPExcel_Style_Border::BORDER_THIN, "#000000");

                                    $posPage = $pos+38;
                                    $sheet->setCellValue('AK'.$posPage,($i+1).'/'.count($data_pagi));
                                    // set break page
                                    $posBreak = $pos+39;
                                    $sheet->setBreak( 'A'.$posBreak , \PHPExcel_Worksheet::BREAK_ROW );
                                    
                                    /*********************************************************************
                                    *  END - FOOTER
                                    *********************************************************************/  
                                }
                            });
                        })->store('xlsx', DOWNLOAD_EXCEL_PUBLIC);
                        $fileName       =   $file_name.'.xlsx';
                        $zip_array[]    =   $fileName;                    
                        $error_flag     =   true;
                    }
                }
            }
            // file name zip
            $zipFileName    =   $this->file_excel.date("YmdHis").'.zip';
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
            return response()->json(array(
                        'response'      => false,
                        'error'         => $e->getMessage(),
                    ));
        }
    }
}
