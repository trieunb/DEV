<?php
/**
 *|--------------------------------------------------------------------------
 *| Partial Order Export
 *|--------------------------------------------------------------------------
 *| Package       : Apel
 *| @author       : ANS804 - daonx@ans-asia.com
 *| @created date : 2018/02/13
 *| 
 */
namespace Modules\Export\Http\Controllers;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Common\Http\Controllers\CommonController as common;
use Excel;
use Session, DB, Dao, Button;

class ComponentOrderSearchExportController extends Controller {
    public $title           = 'Component Order';
    public $company         = 'Apel';
    public $description     = '部品発注書一覧';
    protected $file_excel   = '部品発注書_';
    protected $totalLine    = '58';
    /*
     * Header
     * @var array
     */
    private $header = [
        '発注日',
        '注文番号',
        '行番号',
        'ステータス',
        '仕入先コード',
        '仕入先名',
        '希望納期',
        '品名',
        '規格',
        '数量',
        '単位',
        '単価',
        '金額',
        '備考',
        '購入依頼書番号',
        '社内発注書番号',
        '製造指示書番号'
    ];
    /*
     * postExcelOutput
     * -----------------------------------------------
     * @author      :   ANS804 - 2018/01/23 - create
     * @param       :
     * @return      :
     * @access      :   public
     * @see         :   remark
     */
    public function postExcelOutput(Request $request) {
        try {
            $param  = \Input::all();
            $sql    = "SPC_035_COMPONENT_ORDER_SEARCH_FND1";//name stored
            $result = Dao::call_stored_procedure($sql, $param,true);
            $data   = isset($result[0]) ? $result[0] : NULL;
            
            //width of columns
            $arrWidthColumns     =   array(
                'A'     =>  15,//parts_order_date
                'B'     =>  17,//parts_order_no
                'C'     =>  10,//parts_order_detail_no
                'D'     =>  17,//parts_order_status_div_nm
                'E'     =>  20,//supplier_cd
                'F'     =>  30,//client_nm
                'G'     =>  15,//hope_delivery_date
                'H'     =>  30,//item_nm_j
                'I'     =>  30,//specification
                'J'     =>  9,//parts_order_qty
                'K'     =>  8,//unit_qty_div_nm
                'L'     =>  15,//parts_order_unit_price
                'M'     =>  15,//parts_order_amt
                'N'     =>  30,//detail_remarks
                'O'     =>  17,
                'P'     =>  17,
                'Q'     =>  17,
            );

            if (!is_null($data)) {
                $filename    = '部品発注書一覧_'.date("YmdHis");
                \Excel::create($filename, function($excel) use ($data, $arrWidthColumns) {
                    $excel->sheet('Sheet 1', function($sheet) use ($data, $arrWidthColumns) {
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
                        $sheet->getStyle('A1:Q1')->applyFromArray($styleAllBorder);
                        $sheet->getStyle('A1:Q1')->getFont()->getColor()->setRGB('ffffff');
                        $sheet->cells('A1:Q1', function($cells) {
                            $cells->setBackground('#248af0');
                            $cells->setAlignment('center');
                            $cells->setValignment('center');
                        });
                        //write data to excel file.
                        foreach ($data as $k1 => $v1) {
                            $row ++;
                            $sheet->getStyle('A'.$row.':Q'.$row)->applyFromArray($styleAllBorder);
                            $sheet->getStyle('A'.$row.':Q'.$row)->getAlignment()->setWrapText(true);
                            $sheet->row($row, array(
                                $v1['parts_order_date'], 
                                $v1['parts_order_no'], 
                                $v1['parts_order_detail_no'], 
                                $v1['parts_order_status_div_nm'], 
                                $v1['supplier_cd'],                                
                                $v1['supplier_nm'], 
                                $v1['hope_delivery_date'],
                                $v1['item_nm_j'],
                                $v1['specification'],
                                $v1['parts_order_qty'],
                                $v1['unit_qty_div'],                                
                                $v1['parts_order_unit_price'],
                                $v1['parts_order_amt'],
                                $v1['detail_remarks'],
                                $v1['buy_no'],
                                $v1['in_order_no'],
                                $v1['manufacture_no']
                            ));

                            //even odd row background color
                            if($row % 2 != 0){
                                $sheet->cells('A'.$row.':Q'.$row, function($cells) {
                                    $cells->setBackground('#FFF2CC');
                                });
                            }
                            // parts_order_date
                            $sheet->cells('A'.$row.':A'.$row, function($cells) {
                                $cells->setAlignment('center');
                                $cells->setValignment('center');
                            });

                            //parts_order_no
                            $sheet->cells('B'.$row.':B'.$row, function($cells) {
                                $cells->setAlignment('left');
                                $cells->setValignment('center');
                            });
                            
                            //parts_order_detail_no
                            $sheet->cells('C'.$row.':C'.$row, function($cells) {
                                $cells->setAlignment('right');
                                $cells->setValignment('center');
                            });
                            
                            //status
                            $sheet->cells('D'.$row.':D'.$row, function($cells) {
                                $cells->setAlignment('left');
                                $cells->setValignment('center');
                            });
                            
                            //supplier_cd
                            $sheet->cells('E'.$row.':E'.$row, function($cells) {
                                $cells->setAlignment('left');
                                $cells->setValignment('center');
                            });
                            
                            //supplier_nm
                            $sheet->cells('F'.$row.':F'.$row, function($cells) {
                                $cells->setAlignment('left');
                                $cells->setValignment('center');
                            });

                            //hope_delivery_date
                            $sheet->cells('G'.$row.':G'.$row, function($cells) {
                                $cells->setAlignment('center');
                                $cells->setValignment('center');
                            });

                            //item_nm_j
                            $sheet->cells('H'.$row.':H'.$row, function($cells) {
                                $cells->setAlignment('left');
                                $cells->setValignment('center');
                            });

                            //specification
                            $sheet->cells('I'.$row.':I'.$row, function($cells) {
                                $cells->setAlignment('left');
                                $cells->setValignment('center');
                            });

                            //parts_order_qty
                            $sheet->cells('J'.$row.':J'.$row, function($cells) {
                                $cells->setAlignment('right');
                                $cells->setValignment('center');
                            });

                            //unit_qty_div
                            $sheet->cells('K'.$row.':K'.$row, function($cells) {
                                $cells->setAlignment('left');
                                $cells->setValignment('center');
                            });

                            //parts_order_unit_price
                            $sheet->cells('L'.$row.':L'.$row, function($cells) {
                                $cells->setAlignment('right');
                                $cells->setValignment('center');
                            });

                            //parts_order_amt
                            $sheet->cells('M'.$row.':M'.$row, function($cells) {
                                $cells->setAlignment('right');
                                $cells->setValignment('center');
                            });

                            //detail_remarks
                            $sheet->cells('N'.$row.':Q'.$row, function($cells) {
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
     * @author      :   ANS804 - 2018/01/17 - create
     * @param       :
     * @return      :
     * @access      :   public
     * @see         :   remark
     */
    public function postExportExcel(Request $request) {
        try {
            //data master layout
            $header_company            = common::getDataHeaderExcel('JP');

            $data                      = $request->all();
            $data['parts_order_no']    = json_encode($data['parts_order_no']);//parse json to string
            $data['cre_user_cd']       = \GetUserInfo::getInfo('user_cd');
            $data['cre_prg_cd']        = '036-order-search';
            $data['cre_ip']            = \GetUserInfo::getInfo('user_ip');
            
            $report_number_parts_order = $data['report_number_parts_order'];
            unset($data['report_number_parts_order']);

            $sql                       =   "SPC_036_COMPONENT_ORDER_EXPORT_ACT1"; 
            $result                    =   Dao::call_stored_procedure($sql, $data, true);
            
            $response                  =   true;
            $error                     =   [];
            $error_cd                  =   [];
            $zip_array                 =   [];
            $error_flag                =   false;

            if (!empty($result[0])) {//$error_flag = false;
                $response   =   false;
                $error_cd   =   '';
                $error      =   $result[0][0]['Message'];
            } else {
                if (isset($result[1]) && !empty($result[1][0]['error_cd'])) {//$error_flag = false;
                    $response   =   true;
                    $error_cd   =   $result[1][0]['error_cd'];
                } else {//$error_flag = true;
                    //width of columns
                    $arrWidthColumns    = array(
                        'A','B','C','D','E','F','G','H','I','J','K','L','M',
                        'N','O','P','Q','R','S','T','U','V','W','X','Y','Z',
                        'AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ',
                        'AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT',
                        'AU','AV','AW','AX','AY','AZ','BA','BB','BC','BD',
                        'BE','BF','BG','BH','BI','BJ','BK','BL','BM','BN',
                        'BO','BP','BQ','BR','BS','BT','BU','BV','BW','BX','BY','BZ',
                    );
                    $arrWidthColumns       = array_fill_keys($arrWidthColumns, 1.67);
                    $arrWidthColumns['BC'] = 3.34;
                    $marginPage            = [0.2, 0, 0.2, 0.6];

                    for ($k = 0; $k < count($result[2]); $k++) {
                        // get data by key
                        $key         = ['parts_order_no' => $result[2][$k]['parts_order_no']];
                        // get data header by key 
                        $header      = getDataByKey($key, $result[2])[0];
                        // get data data_detail by key
                        $data_detail = getDataByKey($key, $result[3]);
                        // get data pagination of each page
                        $data_pagi   = dataPageExcel($data_detail, 15);
                        // file name
                        $filename   = $this->file_excel.$key['parts_order_no'];
                        // **********************************************************************
                        //      Export Excel
                        // **********************************************************************
                        \Excel::create($filename, function($excel) use ($data_pagi, $header, $arrWidthColumns, $marginPage, $header_company, $report_number_parts_order) {
                            $excel->sheet('Sheet 1', function($sheet) use ($data_pagi, $header, $arrWidthColumns, $marginPage, $header_company, $report_number_parts_order) {
                                // set font for excel
                                $sheet->getDefaultStyle()->getFont()->setName('ＭＳ Ｐゴシック')->setSize(12);
                                // set width for colum excel
                                $sheet->setWidth($arrWidthColumns);
                                // set Gridlines
                                $sheet->setShowGridlines(false);
                                //set margin for page
                                $sheet->setPageMargin($marginPage);
                                // Init 
                                // Vị trí mỗi page ( First postition of every page)
                                $pos        =   0;
                                // Sum height header and footer
                                $page_size  =   24;
                                //set margin for page

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

                                    /////////////////////////////////////////////////////////////
                                    $posR1 = $pos;
                                    //report_number_parts_order
                                    $sheet->setCellValue('BS'.$pos, $report_number_parts_order);
                                    $sheet->mergeCells('BS'.$pos.':BZ'.$pos);
                                    $sheet->cells('BS'.$pos.':BZ'.$pos, function($cells){
                                        $cells->setAlignment('right');  
                                        $cells->setValignment('bottom');   
                                    });

                                    /////////////////////////////////////////////////////////////
                                    $posR2 = $pos + 1;
                                    //create text header
                                    $textHeaderFormTitle = new \PHPExcel_RichText();
                                    $objBold = $textHeaderFormTitle->createTextRun('部品発注書');
                                    $objBold->getFont()->setName('ＭＳ Ｐゴシック')
                                                       ->setSize(26)
                                                       ->setBold(true);

                                    $sheet->setCellValue('AF'.$posR2, $textHeaderFormTitle);

                                    //発注日
                                    $sheet->setCellValue('BB'.$posR2,'発注日');
                                    $sheet->mergeCells('BB'.$posR2.':BK'.$posR2);
                                    $sheet->cells('BB'.$posR2.':BK'.$posR2, function($cells){
                                        $cells->setAlignment('center');  
                                        $cells->setValignment('bottom');   
                                    });

                                    $sheet->setCellValue('BP'.$posR2,$header['parts_order_date']);
                                    $sheet->mergeCells('BP'.$posR2.':BY'.$posR2);
                                    $sheet->cells('BP'.$posR2.':BY'.$posR2, function($cells){
                                        $cells->setAlignment('center');  
                                        $cells->setValignment('bottom');   
                                    });

                                    /////////////////////////////////////////////////////////////
                                    $posR3 = $pos+2;

                                    //注文番号
                                    $sheet->setCellValue('BB'.$posR3,'注文番号');
                                    $sheet->mergeCells('BB'.$posR3.':BK'.$posR3);
                                    $sheet->cells('BB'.$posR3.':BK'.$posR3, function($cells){
                                        $cells->setAlignment('center');  
                                        $cells->setValignment('bottom');   
                                    });

                                    $sheet->setCellValue('BP'.$posR3,$header['parts_order_no']);
                                    $sheet->mergeCells('BP'.$posR3.':BZ'.$posR3);
                                    $sheet->cells('BP'.$posR3.':BZ'.$posR3, function($cells){
                                        $cells->setAlignment('center');  
                                        $cells->setValignment('bottom');   
                                    });

                                    /////////////////////////////////////////////////////////////
                                    $posR4 = $pos+3;

                                    //supplier_nm
                                    $sheet->setCellValue('A'.$posR4,$header['supplier_nm']);
                                    $sheet->mergeCells('A'.$posR4.':AR'.$posR4);

                                    /////////////////////////////////////////////////////////////
                                    $posR5 = $pos+4;

                                    //supplier_staff_nm
                                    $sheet->setCellValue('A'.$posR5,$header['supplier_staff_nm']);
                                    $sheet->mergeCells('A'.$posR5.':AR'.$posR5);
                                    //company_nm
                                    $sheet->setCellValue('BC'.$posR5,$header_company['company_nm']);
                                    $sheet->mergeCells('BC'.$posR5.':BZ'.$posR5);
                                    

                                    /////////////////////////////////////////////////////////////
                                    $posR6 = $pos+5;

                                    //contact_nm
                                    $sheet->setCellValue('BI'.$posR6,$header['contact_nm']);
                                    $sheet->mergeCells('BI'.$posR6.':BZ'.$posR6);

                                    /////////////////////////////////////////////////////////////
                                    $posR7 = $pos+6;

                                    //supplier_tel
                                    $sheet->setCellValue('A'.$posR7,'TEL:'.$header['supplier_tel']);
                                    $sheet->mergeCells('A'.$posR7.':Q'.$posR7);
                                    //supplier_fax
                                    $sheet->setCellValue('R'.$posR7,'FAX:'.$header['supplier_fax']);
                                    $sheet->mergeCells('R'.$posR7.':AH'.$posR7);
                                    //company_zip
                                    $sheet->setCellValue('BD'.$posR7,'〒 '.$header_company['company_zip']);
                                    $sheet->mergeCells('BD'.$posR7.':BZ'.$posR7);

                                    /////////////////////////////////////////////////////////////
                                    $posR8 = $pos+7;
                                    $posR9 = $pos+8;

                                    //件名
                                    $sheet->setCellValue('A'.$posR8,'件名');
                                    $sheet->mergeCells('A'.$posR8.':C'.$posR9);
                                    $sheet->cells('A'.$posR8.':C'.$posR9, function($cells){
                                        $cells->setAlignment('center');
                                        $cells->setValignment('center');
                                    });
                                    //parts_order_subject
                                    $sheet->setCellValue('D'.$posR8,$header['parts_order_subject']);
                                    $sheet->mergeCells('D'.$posR8.':Y'.$posR9);
                                    $sheet->cells('D'.$posR8.':Y'.$posR9, function($cells){
                                        $cells->setAlignment('left');
                                        $cells->setValignment('top');
                                    });
                                    //company_address
                                    $sheet->setCellValue('BE'.$posR8,$header_company['company_address']);
                                    $sheet->mergeCells('BE'.$posR8.':BZ'.$posR8);
                                    

                                    //company_tel
                                    $sheet->setCellValue('BC'.$posR9,'TEL:'.$header_company['company_tel']);
                                    $sheet->mergeCells('BC'.$posR9.':BN'.$posR9);
                                    //company_fax
                                    $sheet->setCellValue('BO'.$posR9,'FAX:'.$header_company['company_fax']);
                                    $sheet->mergeCells('BO'.$posR9.':BZ'.$posR9);

                                    /////////////////////////////////////////////////////////////
                                    $posR10 = $pos+9;
                                    $posR11 = $pos+10;
                                    $sheet->getRowDimension($posR10)->setRowHeight(19);
                                    $sheet->getRowDimension($posR11)->setRowHeight(19);

                                    //注文書有効期限
                                    $sheet->setCellValue('AZ'.$posR10,'注文書有効期限');
                                    $sheet->mergeCells('AZ'.$posR10.':BK'.$posR10);
                                    $sheet->cells('AZ'.$posR10.':BK'.$posR10, function($cells){
                                        $cells->setAlignment('center');  
                                        $cells->setValignment('center');   
                                    });
                                    //expiration_date
                                    $sheet->setCellValue('BL'.$posR10,$header['expiration_date']);
                                    $sheet->mergeCells('BL'.$posR10.':BZ'.$posR10);
                                    $sheet->cells('BL'.$posR10.':BZ'.$posR10, function($cells){
                                        $cells->setAlignment('center');  
                                        $cells->setValignment('center');   
                                    });

                                    //希望納期
                                    $sheet->setCellValue('AZ'.$posR11,'希望納期');
                                    $sheet->mergeCells('AZ'.$posR11.':BK'.$posR11);
                                    $sheet->cells('AZ'.$posR11.':BK'.$posR11, function($cells){
                                        $cells->setAlignment('center');  
                                        $cells->setValignment('center');   
                                    });
                                    //hope_delivery_date
                                    $sheet->setCellValue('BL'.$posR11,$header['hope_delivery_date']);
                                    $sheet->mergeCells('BL'.$posR11.':BZ'.$posR11);
                                    $sheet->cells('BL'.$posR11.':BZ'.$posR11, function($cells){
                                        $cells->setAlignment('center');  
                                        $cells->setValignment('center');   
                                    });

                                    /////////////////////////////////////////////////////////////
                                    $posR12 = $pos+11;
                                    $sheet->getRowDimension($posR12)->setRowHeight(0);

                                    /*
                                     * Set text title for row 13
                                    */
                                    // Set fix infor
                                    // array(
                                    //      '番号'
                                    //     ,'コード'
                                    //     ,'品名'
                                    //     ,'規格'
                                    //     ,'数量'
                                    //     ,'単位'
                                    //     ,'単価'
                                    //     ,'金額'
                                    //     ,'備考'
                                    // )); 

                                    /////////////////////////////////////////////////////////////
                                    $posR13 = $pos+12;
                                    $sheet->getRowDimension($posR13)->setRowHeight(19);

                                    // 番号
                                    $sheet->setCellValue('A'.$posR13,'番号');
                                    $sheet->mergeCells('A'.$posR13.':C'.$posR13);                        
                                    $sheet->cells('A'.$posR13.':C'.$posR13, function($cells){
                                        $cells->setAlignment('center');  
                                        $cells->setValignment('center');   
                                    });
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
                                    $sheet->mergeCells('AT'.$posR13.':BB'.$posR13);                        
                                    $sheet->cells('AT'.$posR13.':BB'.$posR13, function($cells){
                                        $cells->setAlignment('center');  
                                        $cells->setValignment('center');   
                                    });
                                    //金額
                                    $sheet->setCellValue('BC'.$posR13,'金額');
                                    $sheet->mergeCells('BC'.$posR13.':BK'.$posR13);                        
                                    $sheet->cells('BC'.$posR13.':BK'.$posR13, function($cells){
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
                                    
                                    /*********************************************************************
                                    *  END - HEADER
                                    *********************************************************************/

                                    /////////////////////////////////////////////////////////////
                                    $posR14 = $pos+13;
                                    //write data detail to excel file.

                                    foreach ($data_pagi[$i] as $k => $v) {
                                        /*
                                         * Render data
                                        */
                                        $row = $posR14 + $k;
                                        $sheet->getRowDimension($row)->setRowHeight(45);
                                        
                                        // 番号 - parts_order_detail_no
                                        $sheet->setCellValue('A'.$row,$v['parts_order_detail_no']);
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
                                        //品名 - item_nm_j
                                        $sheet->setCellValue('H'.$row,$v['item_nm_j']);
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
                                        $sheet->setCellValue('AI'.$row,$v['parts_order_qty']);
                                        $sheet->mergeCells('AI'.$row.':AO'.$row);                        
                                        $sheet->cells('AI'.$row.':AO'.$row, function($cells){
                                            $cells->setAlignment('center');  
                                            $cells->setValignment('center');   
                                        });
                                        //単位 - unit
                                        $sheet->setCellValue('AP'.$row,$v['unit']);
                                        $sheet->mergeCells('AP'.$row.':AS'.$row);                        
                                        $sheet->cells('AP'.$row.':AS'.$row, function($cells){
                                            $cells->setAlignment('center');  
                                            $cells->setValignment('center');   
                                        });
                                        //単価 - parts_order_unit_price
                                        $sheet->setCellValue('AT'.$row,$v['parts_order_unit_price']);
                                        $sheet->mergeCells('AT'.$row.':BB'.$row);                        
                                        $sheet->cells('AT'.$row.':BB'.$row, function($cells){
                                            $cells->setAlignment('center');  
                                            $cells->setValignment('center');   
                                        });
                                        //金額 - parts_order_amt
                                        $sheet->setCellValue('BC'.$row,$v['parts_order_amt']);
                                        $sheet->mergeCells('BC'.$row.':BK'.$row);                        
                                        $sheet->cells('BC'.$row.':BK'.$row, function($cells){
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

                                        //Wraptext for 品名 - item_nm_j
                                        $sheet->getStyle('H'.$row.':'.'S'.$row)->getAlignment()->setWrapText(true);
                                        //Wraptext for 規格 - specification
                                        $sheet->getStyle('T'.$row.':'.'AH'.$row)->getAlignment()->setWrapText(true);
                                        //Wraptext for //備考 - detail_remarks
                                        $sheet->getStyle('BL'.$row.':'.'BZ'.$row)->getAlignment()->setWrapText(true);

                                        // Increment auto 'row'
                                        $row++;
                                    }

                                    /*********************************************************************
                                    *  START - BORDER STYLE FOR DETAIL
                                    *********************************************************************/ 
                                    $posR28 = $pos+27;

                                    // Set fix row height
                                    for( $j = $posR14; $j <= $posR28; $j++ ){
                                        $sheet->getRowDimension($j)->setRowHeight(55);

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
                                        $sheet->mergeCells('AT'.$j.':'.'BB'.$j);
                                        $sheet->cells('AT'.$j.':'.'BB'.$j, function($cells) use($v){
                                            $cells->setAlignment('right');
                                            $cells->setValignment('center');
                                        });
                                        $sheet->mergeCells('BC'.$j.':'.'BK'.$j);
                                        $sheet->cells('BC'.$j.':'.'BK'.$j, function($cells) use($v){
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
                                    $posR29 = $pos+28;
                                    $posR30 = $pos+29;
                                    $posR31 = $pos+30;
                                    $posR32 = $pos+31;
                                    $posR34 = $pos+33;
                                    $sheet->getRowDimension($posR29)->setRowHeight(19);
                                    $sheet->getRowDimension($posR30)->setRowHeight(19);
                                    $sheet->getRowDimension($posR31)->setRowHeight(19);
                                    // 備考
                                    $sheet->setCellValue('A'.$posR32,'備考');
                                    $sheet->mergeCells('A'.$posR32.':C'.$posR34);
                                    $sheet->cells('A'.$posR32.':C'.$posR32, function($cells){
                                        $cells->setAlignment('center');
                                        $cells->setValignment('center');
                                    });
                                    $sheet->setCellValue('D'.$posR32,$header['remarks']);
                                    $sheet->mergeCells('D'.$posR32.':BZ'.$posR34);
                                    $sheet->cells('D'.$posR32.':BZ'.$posR34, function($cells){
                                        $cells->setAlignment('left');
                                        $cells->setValignment('top');
                                    });

                                    /////////////////////////////////////////////////////////////
                                    $posR35 = $pos+34;
                                    $sheet->getRowDimension($posR35)->setRowHeight(10);

                                    /////////////////////////////////////////////////////////////
                                    $posR36 = $pos+35;
                                    $sheet->getRowDimension($posR36)->setRowHeight(19);
                                    $posR37 = $pos+36;
                                    $posR39 = $pos+38;
                                    $posR40 = $pos+39;

                                    // 購入材料又は製造プロセスの変更を実施する前に、弊社
                                    $sheet->setCellValue('A'.$posR36,'購入材料又は製造プロセスの変更を実施する前に、弊社');
                                    $sheet->cells('A'.$posR36, function($cells){
                                        $cells->setAlignment('left');  
                                        $cells->setValignment('bottom');   
                                        $cells->setFont(array(
                                            'size' => 11
                                        ));
                                    });

                                    // に文書による通知を行ってから変更を実施してください。
                                    $sheet->setCellValue('A'.$posR37,'に文書による通知を行ってから変更を実施してください。');
                                    $sheet->cells('A'.$posR37, function($cells){
                                        $cells->setAlignment('left');  
                                        $cells->setValignment('bottom');   
                                        $cells->setFont(array(
                                            'size' => 11
                                        ));
                                    });

                                    // 課長
                                    $sheet->setCellValue('AZ'.$posR36,'課長');
                                    $sheet->mergeCells('AZ'.$posR36.':BF'.$posR36);
                                    $sheet->cells('AZ'.$posR36.':BF'.$posR36, function($cells){
                                        $cells->setAlignment('center');  
                                        $cells->setValignment('bottom');   
                                        $cells->setFont(array(
                                            'size' => 11
                                        ));
                                    });
                                    $sheet->mergeCells('AZ'.$posR37.':BF'.$posR39);

                                    // 部長
                                    $sheet->setCellValue('BJ'.$posR36,'部長');
                                    $sheet->mergeCells('BJ'.$posR36.':BP'.$posR36);
                                    $sheet->cells('BJ'.$posR36.':BP'.$posR36, function($cells){
                                        $cells->setAlignment('center');  
                                        $cells->setValignment('bottom');   
                                        $cells->setFont(array(
                                            'size' => 11
                                        ));
                                    });
                                    $sheet->mergeCells('BJ'.$posR37.':BP'.$posR39);

                                    // 
                                    $sheet->mergeCells('BT'.$posR36.':BZ'.$posR36);
                                    $sheet->mergeCells('BT'.$posR37.':BZ'.$posR39);

                                    // Page1/total page
                                    $sheet->setCellValue('AK'.$posR40,($i+1).'/'.count($data_pagi));

                                    // format footer
                                    $sheet->mergeCells('AT'.$posR29.':BB'.$posR29);
                                    $sheet->mergeCells('BC'.$posR29.':BK'.$posR29);
                                    $sheet->mergeCells('BL'.$posR29.':BZ'.$posR29);
                                    $sheet->mergeCells('AT'.$posR30.':BB'.$posR30);
                                    $sheet->mergeCells('BC'.$posR30.':BK'.$posR30);
                                    $sheet->mergeCells('BL'.$posR30.':BZ'.$posR30);
                                    $sheet->mergeCells('AT'.$posR31.':BB'.$posR31);
                                    $sheet->mergeCells('BC'.$posR31.':BK'.$posR31);
                                    $sheet->mergeCells('BL'.$posR31.':BZ'.$posR31);
                                    if($i === count($data_pagi) - 1) {
                                        // 小計
                                        $sheet->setCellValue('AT'.$posR29,'小計');
                                        $sheet->cells('AT'.$posR29.':BB'.$posR29, function($cells){
                                            $cells->setAlignment('right');
                                            $cells->setValignment('center');
                                        });
                                        $sheet->setCellValue('BC'.$posR29,$header['total_detail_amt']);
                                        $sheet->cells('BC'.$posR29.':BK'.$posR29, function($cells){
                                            $cells->setAlignment('right');
                                            $cells->setValignment('center');
                                        });
                                        //
                                        $sheet->cells('A'.$posR29, function($cells) {
                                            $cells->setBorder('thin', 'none', 'none', 'thin', \PHPExcel_Style_Border::BORDER_THIN);
                                        });
                                        // 消費税
                                        $sheet->setCellValue('AT'.$posR30,'消費税');
                                        $sheet->cells('AT'.$posR30.':BB'.$posR30, function($cells){
                                            $cells->setAlignment('right');
                                            $cells->setValignment('center');
                                        });
                                        $sheet->setCellValue('BC'.$posR30,$header['total_tax']);
                                        $sheet->cells('BC'.$posR30.':BK'.$posR30, function($cells){
                                            $cells->setAlignment('right');
                                            $cells->setValignment('center');
                                        });
                                        //
                                        $sheet->cells('A'.$posR30, function($cells) {
                                            $cells->setBorder('none', 'none', 'none', 'thin', \PHPExcel_Style_Border::BORDER_THIN);
                                        });
                                        // 合計
                                        $sheet->setCellValue('AT'.$posR31,'合計');
                                        $sheet->cells('AT'.$posR31.':BB'.$posR31, function($cells){
                                            $cells->setAlignment('right');
                                            $cells->setValignment('center');
                                        });
                                        $sheet->setCellValue('BC'.$posR31,$header['total_amt']);
                                        $sheet->cells('BC'.$posR31.':BK'.$posR31, function($cells){
                                            $cells->setAlignment('right');
                                            $cells->setValignment('center');
                                        });
                                        //
                                        $sheet->cells('A'.$posR31, function($cells) {
                                            $cells->setBorder('none', 'none', 'thin', 'thin', \PHPExcel_Style_Border::BORDER_THIN);
                                        });
                                    }

                                    // set border for page
                                    $sheet->setBorder('AZ'.$posR10.':BZ'.$posR11, \PHPExcel_Style_Border::BORDER_THIN, "#000000");
                                    $sheet->setBorder('A'.$posR13.':BZ'.$posR28, \PHPExcel_Style_Border::BORDER_THIN, "#000000");
                                    $sheet->setBorder('A'.$posR8.':Y'.$posR9, \PHPExcel_Style_Border::BORDER_THIN, "#000000");
                                    $sheet->setBorder('BC'.$posR29.':BZ'.$posR31, \PHPExcel_Style_Border::BORDER_THIN, "#000000");
                                    $sheet->setBorder('A'.$posR32.':BF'.$posR34, \PHPExcel_Style_Border::BORDER_THIN, "#000000");
                                    $sheet->setBorder('AZ'.$posR36.':BF'.$posR39, \PHPExcel_Style_Border::BORDER_THIN, "#000000");
                                    $sheet->setBorder('BJ'.$posR36.':BP'.$posR39, \PHPExcel_Style_Border::BORDER_THIN, "#000000");
                                    $sheet->setBorder('BT'.$posR36.':BZ'.$posR39, \PHPExcel_Style_Border::BORDER_THIN, "#000000");


                                    
                                    /*********************************************************************
                                    *  END - FOOTER
                                    *********************************************************************/  
                                }
                                
                            });
                        })->store('xlsx', DOWNLOAD_EXCEL_PUBLIC);  
                        $filename       =   $filename.'.xlsx';
                        $zip_array[]    =   $filename;                    
                        $error_flag     =   true;
                    }
                }
            }

            /*********************************************************************
            *  2. Xuất file xlsx or zip
            *  2. Export file xlsx or zip
            *********************************************************************/
            // 
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
            return response(array('response'=> $e->getMessage()));
        }
    }
    /*
     * getDataHeader
     * -----------------------------------------------
     * @author      :   ANS804 - 2018/02/22 - create
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
     * @author      :   ANS804 - 2018/02/22 - create
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
                '1',
                '1',
                '1',
                '1',
                '1',
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
     * @author      :   ANS804 - 2018/02/22 - create
     * @param       :   
     * @return      :   format data footer excel
     * @access      :   protected
     * @see         :   remark
     */
    protected function getDataDetail($data) {
        try {
            $data_Detail = [];
            if (!empty($data)) {
                foreach ($data as $key => $value) {
                    $data_Detail[] =  '4';
                }
            }
            return $data_Detail;
        } catch (\Exception $e) {
            return response()->json(['response' => false]);
        }
    }
}
