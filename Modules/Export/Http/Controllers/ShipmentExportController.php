<?php
/**
*|--------------------------------------------------------------------------
*| shipmnet export
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
class ShipmentExportController extends Controller
{
    protected $file_excel   = '出荷指示一覧_';
    protected $totalLine    = '52';
    /*
     * Header
     * @var array
     */
    private $header = [
        '入金NO',
        '出荷指示NO',
        '行番号',
        '出荷区分',
        '取引先名',
        '仕向地国名',
        '仕向地都市名',
        '製品名',
        '数量',
        '数量単位',
        'Gross重量',
        'ステータス',
        // '備考',
    ];
    /**
    * Shipment output
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
            $sql                =   "SPC_011_SHIPMENT_SEARCH_INQ1"; 
            $result             =   Dao::call_stored_procedure($sql, $param, true);
            $result[0]          =   isset($result[0]) ? $result[0] : NULL;
            //width of columns
            $arrWidthColumns    =   array(
                'A'     =>  15,
                'B'     =>  15,
                'C'     =>  15,
                'D'     =>  15,
                'E'     =>  30,
                'F'     =>  30,
                'G'     =>  30,
                'H'     =>  30,
                'I'     =>  15,
                'J'     =>  15,
                'K'     =>  15,
                'L'     =>  15,
                // 'M'     =>  40,
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
                        $sheet->getStyle('A1:L1')->applyFromArray($styleAllBorder);
                        $sheet->getStyle('A1:L1')->getFont()->getColor()->setRGB('ffffff');
                        $sheet->cells('A1:L1', function($cells) {
                            $cells->setBackground('#248af0');
                            $cells->setAlignment('center');
                            $cells->setValignment('center');
                        });
                        //write data to excel file
                        foreach ($result[0] as $k1 => $v1) {
                            $row ++;
                            $sheet->getStyle('A'.$row.':L'.$row)->applyFromArray($styleAllBorder);
                            $sheet->getStyle('A'.$row.':L'.$row)->getAlignment()->setWrapText(true);
                            $sheet->row($row, array(
                                $v1['deposit_no'], 
                                $v1['fwd_no'], 
                                $v1['fwd_detail_no'], 
                                $v1['forwarding_div'], 
                                $v1['client_nm'], 
                                $v1['dest_country_nm'],
                                $v1['dest_city_nm'],
                                $v1['item_nm'],
                                $v1['qty'],
                                $v1['unit_q_div'],
                                $v1['gross_weight'],
                                $v1['fwd_status_div'],
                                // $v1['inside_remarks'],
                            ));

                            //even odd row background color
                            if($row % 2 != 0){
                                $sheet->cells('A'.$row.':L'.$row, function($cells) {
                                    $cells->setBackground('#FFF2CC');
                                });
                            }
                            //align left data
                            //align left data
                            //align left data
                            $sheet->cells('A'.$row, function($cells) {
                                $cells->setAlignment('left');
                                $cells->setValignment('center');
                            });
                            //align left data
                            $sheet->cells('B'.$row, function($cells) {
                                $cells->setAlignment('left');
                                $cells->setValignment('center');
                            });
                            $sheet->cells('C'.$row, function($cells) {
                                $cells->setAlignment('right');
                                $cells->setValignment('center');
                            });
                            //align left data
                            $sheet->cells('D'.$row.':H'.$row, function($cells) {
                                $cells->setAlignment('left');
                                $cells->setValignment('center');
                            });
                            //align left data
                            $sheet->cells('K'.$row, function($cells) {
                                $cells->setAlignment('right');
                                $cells->setValignment('center');
                            });
                            //align left data
                            $sheet->cells('I'.$row, function($cells) {
                                $cells->setAlignment('right');
                                $cells->setValignment('center');
                            });
                            //align left data
                            $sheet->cells('J'.$row, function($cells) {
                                $cells->setAlignment('left');
                                $cells->setValignment('center');
                            });
                            //align left data
                            $sheet->cells('L'.$row, function($cells) {
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

    /*
    * export report
    * -----------------------------------------------
    * @author      :   ANS831 - 2018/03/19 - create
    * @param       :
    * @return      :
    * @access      :   public
    * @see         :   remark
    */
    public function postExportExcel(Request $request) {
        try {
            $zipFileName            =   '出荷指示書_'.date("YmdHis").'.zip';
            $error_flag             =   false;
            $data                   =   $request->all();
            //parse json to string
            $data['fwd_list']       =   json_encode($data['fwd_list']);
            $data['cre_user_cd']    =   \GetUserInfo::getInfo('user_cd');
            $data['cre_prg_cd']     =   '012_shipment-excel';
            $data['cre_ip']         =   \GetUserInfo::getInfo('user_ip');
            $sql                    =   "SPC_012_SHIPMENT_DETAIL_EXCEL_ACT1"; 
            $result                 =   Dao::call_stored_procedure($sql, $data, true);
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
                    $arrWidthColumns     =   array(
                        'A'=>8,
                        'B'=>4,
                        'C'=>8,
                        'D'=>6,
                        'E'=>5,
                        'F'=>8,
                        'G'=>15,
                        'H'=>5,
                        'I'=>4,
                        'J'=>4,
                        'K'=>4,
                        'L'=>6,
                        'M'=>5,
                        'N'=>6,
                        'O'=>4,
                        'P'=>6,
                        'Q'=>2,
                    );

                    $marginPage         =   [0.4, 0.48, 0.32, 0.48];  

                /*********************************************************************
                *  1. Vòng lặp tạo list file
                *********************************************************************/
                    for ($k = 0; $k < count($result[2]); $k++) {
                        // get data by key
                        $key    = ['fwd_no' => $result[2][$k]['fwd_no']];
                        // get data array buy_h by key
                        $header  = getDataByKey($key, $result[2])[0];
                        // get data array buy_d by key
                        $row_data  = getDataByKey($key, $result[3]);
                        $serial    = isset($result[4]) ? $result[4][0]['ctl_val1'] : array();
                        $line_header    = numLinesDataExcel($this->getDataHeader($header),true);   
                        $line_footer    = numLinesDataExcel($this->getDataFooter(),true);
                        $line_detail    = $this->totalLine - ($line_header + $line_footer);
                        $pagi           = pagiDataExcel($this->getDataDetail($row_data), $line_detail);

                        // get data pagination
                        // Lấy ra địa chỉ phân trang - Cụ thể ntn xem ở file helper
                        $page           = $pagi[0];
                        // get data of every page
                        // lấy data số lượng row của data sẽ đổ vào page nào
                        // $data_pagi      = dataPageExcel($row_data,$page);
                        $data_pagi      = dataPageExcel($row_data,$page);        
                        // if ( !is_null($header)) {
                            $filename    = '出荷指示一覧_'.$header['fwd_no'];
                            // return $filename;
                            \Excel::create($filename, function($excel) use ($data_pagi, $header, $line_detail, $arrWidthColumns, $serial, $marginPage) {
                                $excel->sheet('Sheet 1', function($sheet) use ($data_pagi, $header, $line_detail, $arrWidthColumns, $serial, $marginPage) {
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
                                    $page_size  =   23;
                                    $pos_page   =   0;
                                    // pagination
                                    for ($i = 0; $i < count($data_pagi); $i++) {
                                        $num_page = $i+1;
                                        if ($i == 0) {
                                            $pos    =   $i + 1;
                                        } else {
                                            $pos    =   $pos + $pos_page + 1;
                                        }
                                        //create and format footer
                                        //create text body
                                        /*
                                         * font style
                                        */
                                        $sheet->setStyle(array(
                                                        'font' => array(
                                                            'name'      =>  'ＭＳ Ｐ明朝',
                                                            'size'      =>  11,
                                                            'bold'      =>  false
                                                        )
                                        ));
                                    
                                    /*************************HEADER*************************************/
                                    /********************************************************************/

                                    //-------------------------1---------------------------------------//
                                    //set title
                                    $posMerg    =  $pos + 1;
                                    $sheet->setCellValue('G'.$pos, '出荷指示書');
                                    $sheet->mergeCells('G'.$pos.':K'.$posMerg);
                                    $sheet->cells('G'.$pos.':K'.$posMerg, function($cells) { 
                                        $cells->setAlignment('center');
                                        $cells->setValignment('center');   
                                    });
                                    $sheet->cells('G'.$pos.':K'.$pos, function($cells) {
                                        $cells->setFont(array(
                                            'size'       =>  18,
                                            'bold'       =>  true
                                        ));
                                    });

                                    //-------------------------3---------------------------------------//

                                    //出荷指示書No
                                    $pos_fwd_no        =  $pos + 2;
                                    $sheet->setCellValue('A'.$pos_fwd_no, '出荷指示書No');
                                    $sheet->mergeCells('A'.$pos_fwd_no.':C'.$pos_fwd_no);
                                    $sheet->cells('A'.$pos_fwd_no.':C'.$pos_fwd_no, function($cells) { 
                                        $cells->setAlignment('center');
                                        $cells->setValignment('center');   
                                    });

                                    $sheet->setCellValue('D'.$pos_fwd_no, $header['fwd_no']);
                                    $sheet->mergeCells('D'.$pos_fwd_no.':F'.$pos_fwd_no);
                                    $sheet->cells('D'.$pos_fwd_no.':F'.$pos_fwd_no, function($cells) { 
                                        $cells->setAlignment('left');
                                        $cells->setValignment('center');   
                                    });

                                    $sheet->setCellValue('N'.$pos_fwd_no, $serial);
                                    $sheet->cells('N'.$pos_fwd_no.':F'.$pos_fwd_no, function($cells) { 
                                        $cells->setAlignment('center');
                                        $cells->setValignment('center');   
                                    });

                                    $sheet->cells('N'.$pos_fwd_no, function($cells) {
                                        $cells->setFont(array(
                                            'size'       =>  16,
                                            'bold'       =>  false
                                        ));
                                    });

                                    $sheet->setBorder('A'.$pos_fwd_no.':F'.$pos_fwd_no, \PHPExcel_Style_Border::BORDER_MEDIUM, "#000000");

                                    //-------------------------4---------------------------------------//

                                    //Shipping Invoice No.
                                    $pos_inv_no        =  $pos + 3;
                                    $sheet->setCellValue('A'.$pos_inv_no, 'Shipping Invoice No.');
                                    $sheet->mergeCells('A'.$pos_inv_no.':C'.$pos_inv_no);
                                    $sheet->cells('A'.$pos_inv_no.':C'.$pos_inv_no, function($cells) { 
                                        $cells->setAlignment('center');
                                        $cells->setValignment('center');   
                                    });

                                    $sheet->getRowDimension($pos_inv_no)->setRowHeight(16.5);

                                    $sheet->setCellValue('D'.$pos_inv_no, $header['inv_no']);
                                    $sheet->mergeCells('D'.$pos_inv_no.':Q'.$pos_inv_no);

                                    $sheet->getStyle('D'.$pos_inv_no.':Q'.$pos_inv_no)->applyFromArray(getStyleExcel('styleAllBorderBol'));
                                    $sheet->cells('D'.$pos_inv_no.':Q'.$pos_inv_no, function($cells) { 
                                        $cells->setAlignment('left');
                                        $cells->setValignment('center'); 
                                        $cells->setBorder('medium', 'medium', 'thin', 'thin', \PHPExcel_Style_Border::BORDER_MEDIUM); 
                                    }); 

                                    $sheet->getStyle('A'.$pos_inv_no.':C'.$pos_inv_no)->getBorders()->getLeft()->setBorderStyle(\PHPExcel_Style_Border::BORDER_MEDIUM, "#000000");

                                    //-------------------------5---------------------------------------//

                                    //受注No
                                    $pos_rcv_no        =  $pos + 4;
                                    $sheet->setCellValue('A'.$pos_rcv_no, '受注No');
                                    $sheet->mergeCells('A'.$pos_rcv_no.':C'.$pos_rcv_no);
                                    $sheet->cells('A'.$pos_rcv_no.':C'.$pos_rcv_no, function($cells) { 
                                        $cells->setAlignment('center');
                                        $cells->setValignment('center');   
                                    });
                                    $sheet->setCellValue('D'.$pos_rcv_no, $header['rcv_no']);
                                    $sheet->mergeCells('D'.$pos_rcv_no.':Q'.$pos_rcv_no);

                                    $sheet->setBorder('A'.$pos_rcv_no.':Q'.$pos_rcv_no, \PHPExcel_Style_Border::BORDER_THIN, "#000000");
                                    $sheet->getStyle('A'.$pos_rcv_no.':Q'.$pos_rcv_no)->getBorders()->getLeft()->setBorderStyle(\PHPExcel_Style_Border::BORDER_MEDIUM, "#000000");

                                    $sheet->getStyle('D'.$pos_rcv_no.':Q'.$pos_rcv_no)->applyFromArray(getStyleExcel('styleAllBorderBol'));
                                    $sheet->cells('D'.$pos_rcv_no.':Q'.$pos_rcv_no, function($cells) { 
                                        $cells->setAlignment('left');
                                        $cells->setValignment('center'); 
                                        $cells->setBorder('thin', 'medium', 'thin', 'thin', \PHPExcel_Style_Border::BORDER_MEDIUM); 
                                    }); 

                                    //-------------------------6---------------------------------------//

                                    //PF Invoice No.
                                    $pos_pf_inv_no        =  $pos + 5;
                                    $sheet->setCellValue('A'.$pos_pf_inv_no, 'PF Invoice No.');
                                    $sheet->mergeCells('A'.$pos_pf_inv_no.':C'.$pos_pf_inv_no);
                                    $sheet->cells('A'.$pos_pf_inv_no.':C'.$pos_pf_inv_no, function($cells) { 
                                        $cells->setAlignment('center');
                                        $cells->setValignment('center');   
                                    });
                                    $sheet->setCellValue('D'.$pos_pf_inv_no, $header['pi_no']);
                                    $sheet->mergeCells('D'.$pos_pf_inv_no.':Q'.$pos_pf_inv_no);

                                    $sheet->setBorder('A'.$pos_pf_inv_no.':Q'.$pos_pf_inv_no, \PHPExcel_Style_Border::BORDER_THIN, "#000000");
                                    $sheet->getStyle('A'.$pos_pf_inv_no.':Q'.$pos_pf_inv_no)->getBorders()->getLeft()->setBorderStyle(\PHPExcel_Style_Border::BORDER_MEDIUM, "#000000");

                                    $sheet->getStyle('D'.$pos_pf_inv_no.':Q'.$pos_pf_inv_no)->applyFromArray(getStyleExcel('styleAllBorderBol'));
                                    $sheet->cells('D'.$pos_pf_inv_no.':Q'.$pos_pf_inv_no, function($cells) { 
                                        $cells->setAlignment('left');
                                        $cells->setValignment('center'); 
                                        $cells->setBorder('thin', 'medium', 'thin', 'thin', \PHPExcel_Style_Border::BORDER_MEDIUM); 
                                    }); 

                                    //-------------------------7---------------------------------------//

                                    //販売先業者名
                                    $pos_cust_nm       =  $pos + 6;
                                    $sheet->setCellValue('A'.$pos_cust_nm, '販売先業者名');
                                    $sheet->mergeCells('A'.$pos_cust_nm.':C'.$pos_cust_nm);
                                    $sheet->cells('A'.$pos_cust_nm.':C'.$pos_cust_nm, function($cells) { 
                                        $cells->setAlignment('center');
                                        $cells->setValignment('center');   
                                    });
                                    $sheet->setCellValue('D'.$pos_cust_nm, $header['cust_nm']);
                                    
                                    $sheet->mergeCells('D'.$pos_cust_nm.':I'.$pos_cust_nm);
                                    $sheet->cells('D'.$pos_cust_nm.':I'.$pos_cust_nm, function($cells) { 
                                        $cells->setAlignment('left');
                                        $cells->setValignment('center');   
                                    });

                                    $sheet->setBorder('A'.$pos_cust_nm.':Q'.$pos_cust_nm, \PHPExcel_Style_Border::BORDER_THIN, "#000000");
                                    $sheet->getStyle('A'.$pos_cust_nm.':Q'.$pos_cust_nm)->getBorders()->getLeft()->setBorderStyle(\PHPExcel_Style_Border::BORDER_MEDIUM, "#000000");

                                    //set height  販売先業者名
                                    $height             =   numLineOfRowExcel($header['cust_nm'], 36);
                                    $sheet->getRowDimension($pos_cust_nm)->setRowHeight($height*16.5);
                                    $sheet->getStyle('D'.$pos_cust_nm.':I'.$pos_cust_nm)->getAlignment()->setWrapText(true);

                                    //販売業者国名
                                    $pos_cust_country       =  $pos + 6;
                                    $sheet->setCellValue('J'.$pos_cust_country, '販売業者国名');
                                    $sheet->mergeCells('J'.$pos_cust_country.':L'.$pos_cust_country);
                                    $sheet->cells('J'.$pos_cust_country.':L'.$pos_cust_country, function($cells) { 
                                        $cells->setAlignment('center');
                                        $cells->setValignment('center');   
                                    });

                                    $sheet->setCellValue('M'.$pos_cust_country, $header['cust_country_nm']);
                                    $sheet->mergeCells('M'.$pos_cust_country.':Q'.$pos_cust_country);
                                    $sheet->getStyle('M'.$pos_cust_country.':Q'.$pos_cust_country)->applyFromArray(getStyleExcel('styleAllBorderBol'));
                                    $sheet->cells('M'.$pos_cust_country.':Q'.$pos_cust_country, function($cells) { 
                                        $cells->setAlignment('left');
                                        $cells->setValignment('center'); 
                                        $cells->setBorder('thin', 'medium', 'thin', 'thin', \PHPExcel_Style_Border::BORDER_MEDIUM); 
                                    }); 

                                    //-------------------------8---------------------------------------//

                                    //仕向地
                                    $pos_dest_country_nm       =  $pos + 7;
                                    $sheet->setCellValue('A'.$pos_dest_country_nm, '仕向地');
                                    $sheet->mergeCells('A'.$pos_dest_country_nm.':C'.$pos_dest_country_nm);
                                    $sheet->cells('A'.$pos_dest_country_nm.':C'.$pos_dest_country_nm, function($cells) { 
                                        $cells->setAlignment('center');
                                        $cells->setValignment('center');   
                                    });

                                    $sheet->getRowDimension($pos_dest_country_nm)->setRowHeight(16.5);

                                    $sheet->setCellValue('L'.$pos_dest_country_nm, $header['dest_country_nm']);
                                    $sheet->mergeCells('L'.$pos_dest_country_nm.':Q'.$pos_dest_country_nm);
                                    $sheet->cells('L'.$pos_dest_country_nm.':Q'.$pos_dest_country_nm, function($cells) { 
                                        $cells->setAlignment('center');
                                        $cells->setValignment('center');   
                                    });

                                    //国名
                                    $pos_dest_country       =  $pos + 7;
                                    $sheet->setCellValue('D'.$pos_dest_country, '国名');
                                    $sheet->mergeCells('D'.$pos_dest_country.':E'.$pos_dest_country);
                                    $sheet->cells('D'.$pos_dest_country.':E'.$pos_dest_country, function($cells) { 
                                        $cells->setAlignment('center');
                                        $cells->setValignment('center');   
                                    });
                                    $sheet->setCellValue('F'.$pos_dest_country, $header['dest_country_nm']);
                                    $sheet->mergeCells('F'.$pos_dest_country.':I'.$pos_dest_country);
                                    $sheet->cells('F'.$pos_dest_country.':I'.$pos_dest_country, function($cells) { 
                                        $cells->setAlignment('center');
                                        $cells->setValignment('center');   
                                    });

                                    //都市名
                                    $pos_dest_city       =  $pos + 7;
                                    $sheet->setCellValue('J'.$pos_dest_city, '都市名');
                                    $sheet->mergeCells('J'.$pos_dest_city.':K'.$pos_dest_city);
                                    $sheet->cells('J'.$pos_dest_city.':K'.$pos_dest_city, function($cells) { 
                                        $cells->setAlignment('center');
                                        $cells->setValignment('center');   
                                    });
                                    $sheet->setBorder('A'.$pos_dest_country.':Q'.$pos_dest_country, \PHPExcel_Style_Border::BORDER_THIN, "#000000");

                                    $sheet->getStyle('A'.$pos_dest_country.':C'.$pos_dest_country)->getBorders()->getLeft()->setBorderStyle(\PHPExcel_Style_Border::BORDER_MEDIUM, "#000000");
                                    $sheet->setCellValue('L'.$pos_dest_city, $header['dest_city_nm']);
                                    $sheet->mergeCells('L'.$pos_dest_city.':Q'.$pos_dest_city);

                                    $sheet->getStyle('L'.$pos_dest_city.':Q'.$pos_dest_city)->applyFromArray(getStyleExcel('styleAllBorderBol'));
                                    $sheet->cells('L'.$pos_dest_city.':Q'.$pos_dest_city, function($cells) { 
                                        $cells->setAlignment('left');
                                        $cells->setValignment('center'); 
                                        $cells->setBorder('thin', 'medium', 'thin', 'thin', \PHPExcel_Style_Border::BORDER_MEDIUM); 
                                    }); 
                                     //-------------------------9---------------------------------------//

                                    //発送先       
                                    $pos_consignee_nm       =  $pos + 8;
                                    $sheet->getStyle('A'.$pos_consignee_nm.':C'.$pos_consignee_nm)->getBorders()->getLeft()->setBorderStyle(\PHPExcel_Style_Border::BORDER_MEDIUM, "#000000");
                                    $sheet->setCellValue('A'.$pos_consignee_nm, '発送先');
                                    $sheet->mergeCells('A'.$pos_consignee_nm.':C'.$pos_consignee_nm);
                                    $sheet->cells('A'.$pos_consignee_nm.':C'.$pos_consignee_nm, function($cells) { 
                                        $cells->setAlignment('center');
                                        $cells->setValignment('center');   
                                    });

                                    $sheet->getRowDimension($pos_consignee_nm)->setRowHeight(16.5);

                                    $sheet->setCellValue('D'.$pos_consignee_nm,  $header['consignee_nm']);
                                    $sheet->mergeCells('D'.$pos_consignee_nm.':Q'.$pos_consignee_nm);
                                    $sheet->getStyle('D'.$pos_consignee_nm.':Q'.$pos_consignee_nm)->applyFromArray(getStyleExcel('styleAllBorderBol'));
                                    $sheet->cells('D'.$pos_consignee_nm.':Q'.$pos_consignee_nm, function($cells) { 
                                        $cells->setAlignment('left');
                                        $cells->setValignment('center');   
                                         $cells->setBorder('thin', 'medium', 'thin', 'thin', \PHPExcel_Style_Border::BORDER_MEDIUM); 
                                    });

                                    //-------------------------10--------------------------------------//

                                    //発送先住所       
                                    $pos_consignee_address       =  $pos + 9;
                                    $pos_consignee_address2      =  $pos + 10;
                                    $sheet->setBorder('A'.$pos_consignee_address.':C'.$pos_consignee_address2, \PHPExcel_Style_Border::BORDER_THIN, "#000000");
                                    $sheet->getStyle('A'.$pos_consignee_address.':C'.$pos_consignee_address2)->getBorders()->getLeft()->setBorderStyle(\PHPExcel_Style_Border::BORDER_MEDIUM, "#000000");

                                    $sheet->setCellValue('A'.$pos_consignee_address, '発送先住所');
                                    $sheet->mergeCells('A'.$pos_consignee_address.':C'.$pos_consignee_address2);
                                    $sheet->cells('A'.$pos_consignee_address.':C'.$pos_consignee_address, function($cells) { 
                                        $cells->setAlignment('center');
                                        $cells->setValignment('center');   
                                    });

                                    $sheet->getRowDimension($pos_consignee_address)->setRowHeight(16.5);

                                    $sheet->setCellValue('D'.$pos_consignee_address,  $header['consignee_adr1']);
                                    $sheet->mergeCells('D'.$pos_consignee_address.':Q'.$pos_consignee_address);
                                    $sheet->getStyle('D'.$pos_consignee_address.':Q'.$pos_consignee_address)->applyFromArray(getStyleExcel('styleAllBorderBol'));
                                    $sheet->cells('D'.$pos_consignee_address.':Q'.$pos_consignee_address, function($cells) { 
                                        $cells->setAlignment('left');
                                        $cells->setValignment('center');   
                                        $cells->setBorder('none', 'medium', 'none', 'none', \PHPExcel_Style_Border::BORDER_MEDIUM); 
                                    });

                                    $sheet->setCellValue('D'.$pos_consignee_address2,  $header['consignee_adr2'] . ' ' . $header['consignee_country']);
                                    $sheet->mergeCells('D'.$pos_consignee_address2.':Q'.$pos_consignee_address2);
                                    $sheet->getStyle('D'.$pos_consignee_address2.':Q'.$pos_consignee_address2)->applyFromArray(getStyleExcel('styleAllBorderBol'));
                                    $sheet->cells('D'.$pos_consignee_address2.':Q'.$pos_consignee_address2, function($cells) { 
                                        $cells->setAlignment('left');
                                        $cells->setValignment('center');  
                                         $cells->setBorder('none', 'medium', 'none', 'none', \PHPExcel_Style_Border::BORDER_MEDIUM);  
                                    });

                                    //-------------------------12--------------------------------------//
                                    
                                    //発送先TEL/FAX       
                                    $pos_consignee_tel_fax       =  $pos + 11;
                                    $sheet->setBorder('A'.$pos_consignee_tel_fax.':Q'.$pos_consignee_tel_fax, \PHPExcel_Style_Border::BORDER_THIN, "#000000");
                                    $sheet->getStyle('A'.$pos_consignee_tel_fax.':C'.$pos_consignee_tel_fax)->getBorders()->getLeft()->setBorderStyle(\PHPExcel_Style_Border::BORDER_MEDIUM, "#000000");

                                    $sheet->setCellValue('A'.$pos_consignee_tel_fax, '発送先TEL/FAX');
                                    $sheet->mergeCells('A'.$pos_consignee_tel_fax.':C'.$pos_consignee_tel_fax);
                                    $sheet->cells('A'.$pos_consignee_tel_fax.':C'.$pos_consignee_tel_fax, function($cells) { 
                                        $cells->setAlignment('center');
                                        $cells->setValignment('center');   
                                    });

                                    //TEL       
                                    $sheet->setCellValue('D'.$pos_consignee_tel_fax, 'TEL');
                                    $sheet->mergeCells('D'.$pos_consignee_tel_fax.':D'.$pos_consignee_tel_fax);
                                    $sheet->cells('D'.$pos_consignee_tel_fax.':D'.$pos_consignee_tel_fax, function($cells) { 
                                        $cells->setAlignment('center');
                                        $cells->setValignment('center');   

                                    });

                                    $sheet->setCellValue('E'.$pos_consignee_tel_fax,  $header['consignee_tel']);
                                    $sheet->mergeCells('E'.$pos_consignee_tel_fax.':I'.$pos_consignee_tel_fax);
                                    $sheet->cells('E'.$pos_consignee_tel_fax.':I'.$pos_consignee_tel_fax, function($cells) { 
                                        $cells->setAlignment('center');
                                        $cells->setValignment('center');   
                                    });

                                    //FAX       
                                    $sheet->setCellValue('J'.$pos_consignee_tel_fax, 'FAX');
                                    $sheet->mergeCells('J'.$pos_consignee_tel_fax.':K'.$pos_consignee_tel_fax);
                                    $sheet->cells('J'.$pos_consignee_tel_fax.':K'.$pos_consignee_tel_fax, function($cells) { 
                                        $cells->setAlignment('center');
                                        $cells->setValignment('center');   
                                    });

                                    $sheet->getStyle('L'.$pos_consignee_tel_fax.':Q'.$pos_consignee_tel_fax)->applyFromArray(getStyleExcel('styleAllBorderBol'));
                                    $sheet->setCellValue('L'.$pos_consignee_tel_fax,  $header['consignee_fax']);
                                    $sheet->mergeCells('L'.$pos_consignee_tel_fax.':Q'.$pos_consignee_tel_fax);
                                    $sheet->cells('L'.$pos_consignee_tel_fax.':Q'.$pos_consignee_tel_fax, function($cells) { 
                                        $cells->setAlignment('center');
                                        $cells->setValignment('center');   
                                        $cells->setBorder('thin', 'medium', 'thin', 'thin', \PHPExcel_Style_Border::BORDER_MEDIUM);  

                                    });

                                    $sheet->getRowDimension($pos_consignee_tel_fax)->setRowHeight(16.5);


                                    //-------------------------13---------------------------------------//

                                    //発送手段
                                    $pos_forwarding       =  $pos + 12;
                                    $sheet->setCellValue('A'.$pos_forwarding, '発送手段');
                                    $sheet->mergeCells('A'.$pos_forwarding.':C'.$pos_forwarding);
                                    $sheet->cells('A'.$pos_forwarding.':C'.$pos_forwarding, function($cells) { 
                                        $cells->setAlignment('center');
                                        $cells->setValignment('center');   
                                    });

                                    $sheet->getRowDimension($pos_forwarding)->setRowHeight(16.5);

                                    $sheet->setCellValue('D'.$pos_forwarding, $header['forwarding_way_div']);
                                    $sheet->mergeCells('D'.$pos_forwarding.':I'.$pos_forwarding);
                                    $sheet->cells('D'.$pos_forwarding.':I'.$pos_forwarding, function($cells) { 
                                        $cells->setAlignment('center');
                                        $cells->setValignment('center');   
                                    });

                                    //通関業者（乙仲）
                                    $pos_forwarder       =  $pos + 12;
                                    $sheet->setCellValue('J'.$pos_forwarder, '通関業者（乙仲）');
                                    $sheet->mergeCells('J'.$pos_forwarder.':L'.$pos_forwarder);
                                    $sheet->cells('J'.$pos_forwarder.':L'.$pos_forwarder, function($cells) { 
                                        $cells->setAlignment('center');
                                        $cells->setValignment('center');   
                                    });
                                    $sheet->setCellValue('M'.$pos_forwarder, $header['forwarder_div']);

                                    $sheet->getStyle('M'.$pos_forwarder.':Q'.$pos_forwarder)->getAlignment()->setWrapText(true);

                                    $sheet->setBorder('A'.$pos_forwarder.':Q'.$pos_forwarder, \PHPExcel_Style_Border::BORDER_THIN, "#000000");
                                    $sheet->getStyle('A'.$pos_forwarder.':C'.$pos_forwarder)->getBorders()->getLeft()->setBorderStyle(\PHPExcel_Style_Border::BORDER_MEDIUM, "#000000");

                                    $sheet->mergeCells('M'.$pos_forwarder.':Q'.$pos_forwarder);

                                    $sheet->getStyle('M'.$pos_forwarder.':Q'.$pos_forwarder)->applyFromArray(getStyleExcel('styleAllBorderBol'));
                                    $sheet->cells('M'.$pos_forwarder.':Q'.$pos_forwarder, function($cells) { 
                                        $cells->setAlignment('left');
                                        $cells->setValignment('center'); 
                                        $cells->setBorder('thin', 'medium', 'thin', 'thin', \PHPExcel_Style_Border::BORDER_MEDIUM); 
                                    }); 

                                    //set height  発送手段
                                    $height             =   numLineOfRowExcel($header['forwarder_div'], 20);
                                    $sheet->getRowDimension($pos_forwarder)->setRowHeight($height*16.5);

                                     //-------------------------14---------------------------------------//

                                    //引渡日予定日
                                    $pos_deliver_date       =  $pos + 13;
                                    $sheet->setCellValue('A'.$pos_deliver_date, '引渡日予定日');
                                    $sheet->mergeCells('A'.$pos_deliver_date.':C'.$pos_deliver_date);
                                    $sheet->cells('A'.$pos_deliver_date.':C'.$pos_deliver_date, function($cells) { 
                                        $cells->setAlignment('center');
                                        $cells->setValignment('center');   
                                    });

                                    $sheet->getRowDimension($pos_deliver_date)->setRowHeight(16.5);

                                    $sheet->setCellValue('D'.$pos_deliver_date, $header['deliver_date']);
                                    $sheet->mergeCells('D'.$pos_deliver_date.':F'.$pos_deliver_date);
                                    $sheet->cells('D'.$pos_deliver_date.':F'.$pos_deliver_date, function($cells) { 
                                        $cells->setAlignment('center');
                                        $cells->setValignment('center');   
                                    });

                                    //ユーザートレーニング
                                    $pos_deliver_date       =  $pos + 13;
                                    $sheet->setCellValue('G'.$pos_deliver_date, 'ユーザートレーニング');
                                    $sheet->mergeCells('G'.$pos_deliver_date.':H'.$pos_deliver_date);
                                    $sheet->cells('G'.$pos_deliver_date.':H'.$pos_deliver_date, function($cells) { 
                                        $cells->setAlignment('center');
                                        $cells->setValignment('center');   
                                    });

                                    $sheet->getRowDimension($pos_deliver_date)->setRowHeight(16.5);

                                    $sheet->setCellValue('I'.$pos_deliver_date, $header['user_training_check_div']);
                                    $sheet->cells('I'.$pos_deliver_date.':I'.$pos_deliver_date, function($cells) { 
                                        $cells->setAlignment('center');
                                        $cells->setValignment('center');   
                                    });


                                    //確認事項
                                    $pos_confirmation_div       =  $pos + 13;
                                    $sheet->setCellValue('J'.$pos_confirmation_div, '確認事項');
                                    $sheet->mergeCells('J'.$pos_confirmation_div.':L'.$pos_confirmation_div);
                                    $sheet->cells('J'.$pos_confirmation_div.':L'.$pos_confirmation_div, function($cells) { 
                                        $cells->setAlignment('center');
                                        $cells->setValignment('center');   
                                    });

                                    $sheet->setCellValue('M'.$pos_confirmation_div, 'a）～ｈ）');
                                    $sheet->setCellValue('O'.$pos_confirmation_div, '確認済');

                                    $sheet->setBorder('A'.$pos_confirmation_div.':I'.$pos_confirmation_div, \PHPExcel_Style_Border::BORDER_THIN, "#000000");
                                    $sheet->getStyle('Q'.$pos_confirmation_div.':Q'.$pos_confirmation_div)->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN, "#000000");
                                    $sheet->getStyle('M'.$pos_confirmation_div.':M'.$pos_confirmation_div)->getBorders()->getLeft()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN, "#000000");
                                    $sheet->getStyle('A'.$pos_confirmation_div.':C'.$pos_confirmation_div)->getBorders()->getLeft()->setBorderStyle(\PHPExcel_Style_Border::BORDER_MEDIUM, "#000000");

                                    $sheet->getStyle('Q'.$pos_confirmation_div)->applyFromArray(getStyleExcel('styleAllBorderBol'));
                                    $sheet->cells('Q'.$pos_confirmation_div, function($cells) { 
                                        $cells->setAlignment('left');
                                        $cells->setValignment('center'); 
                                        $cells->setBorder('thin', 'medium', 'thin', 'none', \PHPExcel_Style_Border::BORDER_MEDIUM); 
                                    }); 


                                    //-------------------------15---------------------------------------//

                                    //梱包方法
                                    $pos_packing_method_div       =  $pos + 14;
                                    $sheet->setCellValue('A'.$pos_packing_method_div, '梱包方法');
                                    $sheet->mergeCells('A'.$pos_packing_method_div.':C'.$pos_packing_method_div);
                                    $sheet->cells('A'.$pos_packing_method_div.':C'.$pos_packing_method_div, function($cells) { 
                                        $cells->setAlignment('center');
                                        $cells->setValignment('center');   
                                    });

                                    $sheet->getRowDimension($pos_packing_method_div)->setRowHeight(16.5);

                                    $sheet->setCellValue('D'.$pos_packing_method_div, $header['packing_method_div']);
                                    $sheet->mergeCells('D'.$pos_packing_method_div.':I'.$pos_packing_method_div);
                                    $sheet->cells('D'.$pos_packing_method_div.':I'.$pos_packing_method_div, function($cells) { 
                                        $cells->setAlignment('center');
                                        $cells->setValignment('center');   
                                    });

                                    //通貨
                                    $pos_currency_div       =  $pos + 14;
                                    $sheet->setCellValue('J'.$pos_currency_div, '通貨');
                                    $sheet->mergeCells('J'.$pos_currency_div.':L'.$pos_currency_div);
                                    $sheet->cells('J'.$pos_currency_div.':L'.$pos_currency_div, function($cells) { 
                                        $cells->setAlignment('center');
                                        $cells->setValignment('center');   
                                    });

                                    $sheet->setBorder('A'.$pos_currency_div.':Q'.$pos_currency_div, \PHPExcel_Style_Border::BORDER_THIN, "#000000");
                                    $sheet->getStyle('A'.$pos_currency_div.':Q'.$pos_currency_div)->getBorders()->getBottom()->setBorderStyle(\PHPExcel_Style_Border::BORDER_MEDIUM, "#000000");
                                    $sheet->getStyle('A'.$pos_currency_div.':C'.$pos_currency_div)->getBorders()->getLeft()->setBorderStyle(\PHPExcel_Style_Border::BORDER_MEDIUM, "#000000");

                                    $sheet->setCellValue('M'.$pos_currency_div, $header['currency_div']);
                                    $sheet->mergeCells('M'.$pos_currency_div.':Q'.$pos_currency_div);

                                    $sheet->getStyle('M'.$pos_currency_div.':Q'.$pos_currency_div)->applyFromArray(getStyleExcel('styleAllBorderBol'));
                                    $sheet->cells('M'.$pos_currency_div.':Q'.$pos_currency_div, function($cells) { 
                                        $cells->setAlignment('center');
                                        $cells->setValignment('center'); 
                                        $cells->setBorder('thin', 'medium', 'thin', 'thin', \PHPExcel_Style_Border::BORDER_MEDIUM); 
                                    }); 

                                    /*************************END HEADER*********************************/
                                    /********************************************************************/

                                    /*************************START BODY*********************************/
                                    /********************************************************************/

                                    // Title body

                                    //品名
                                    $pos_title      =  $pos + 15;
                                    $sheet->setCellValue('A'.$pos_title, '品名');
                                    $sheet->mergeCells('A'.$pos_title.':E'.$pos_title);
                                    $sheet->cells('A'.$pos_title.':E'.$pos_title, function($cells) { 
                                        $cells->setAlignment('center');
                                        $cells->setValignment('center');                                   
                                    }); 

                                    $sheet->getRowDimension($pos_title)->setRowHeight(16.5);


                                    $sheet->setBorder('A'.$pos_title.':E'.$pos_title, \PHPExcel_Style_Border::BORDER_THIN, "#000000");
                                     $sheet->getStyle('A'.$pos_title.':C'.$pos_title)->getBorders()->getLeft()->setBorderStyle(\PHPExcel_Style_Border::BORDER_MEDIUM, "#000000");

                                    //数量
                                    $sheet->setCellValue('F'.$pos_title, '数量');
                                    $sheet->cells('F'.$pos_title, function($cells) { 
                                        $cells->setAlignment('center');
                                        $cells->setValignment('center');   
                                    });                           

                                    $sheet->setBorder('F'.$pos_title.':F'.$pos_title, \PHPExcel_Style_Border::BORDER_THIN, "#000000");

                                    //単価
                                    $sheet->setCellValue('G'.$pos_title, '単価');
                                    $sheet->cells('G'.$pos_title, function($cells) { 
                                        $cells->setAlignment('center');
                                        $cells->setValignment('center');   
                                    });                           

                                    $sheet->setBorder('G'.$pos_title.':G'.$pos_title, \PHPExcel_Style_Border::BORDER_THIN, "#000000");

                                     //シリアル番号
                                    $pos_title      =  $pos + 15;
                                    $sheet->setCellValue('H'.$pos_title, 'シリアル番号');
                                    $sheet->mergeCells('H'.$pos_title.':Q'.$pos_title);    

                                    $sheet->setBorder('H'.$pos_title.':Q'.$pos_title, \PHPExcel_Style_Border::BORDER_THIN, "#000000");

                                    $sheet->getStyle('H'.$pos_title.':Q'.$pos_title)->applyFromArray(getStyleExcel('styleAllBorderBol'));
                                    $sheet->cells('H'.$pos_title.':Q'.$pos_title, function($cells) { 
                                        $cells->setAlignment('center');
                                        $cells->setValignment('center'); 
                                        $cells->setBorder('medium', 'medium', 'thin', 'thin', \PHPExcel_Style_Border::BORDER_MEDIUM); 
                                    });

                                    //write data to excel file.
                                    $num_row_page                   =   count($data_pagi[$i]);
                                    $total_line_detail_nm           =   0;
                                    $total_line_detail_serial       =   0;
                                    foreach ($data_pagi[$i] as $k => $v) {
                                        /*
                                         * Render data
                                        */
                                        $row = $pos + 16 + $k;
                                        // for 品名                                    

                                        $sheet->setCellValue('A'.$row, $v['item_nm_j']);
                                        $sheet->mergeCells('A'.$row.':E'.$row);

                                        $sheet->getStyle('A'.$row.':E'.$row)->applyFromArray(getStyleExcel('styleAllBorderDotted'));
                                        $sheet->cells('A'.$row.':E'.$row, function($cells) { 
                                            $cells->setAlignment('left');
                                            $cells->setValignment('center'); 
                                            $cells->setBorder('none', 'none', 'dotted', 'medium', \PHPExcel_Style_Border::BORDER_MEDIUM);  
                                        }); 

                                        //set height  品名
                                        $height             =   numLineOfRowExcel($v['item_nm_j'], 28);
                                        $total_line_detail_nm  =   $total_line_detail_nm + $height;

                                        $sheet->getRowDimension($row)->setRowHeight($height*16.5);
                                        // Wraptext for 品名
                                        $sheet->getStyle('A'.$row.':E'.$row)->getAlignment()->setWrapText(true);
                                        
                                        //数量
                                        $sheet->setCellValue('F'.$row, $v['fwd_qty']);
                                        $sheet->cells('F'.$row, function($cells) { 
                                            $cells->setAlignment('right');
                                            $cells->setValignment('center');  
                                            $cells->setBorder('dotted', 'dotted', 'dotted', 'dotted', \PHPExcel_Style_Border::BORDER_DOTTED);  
                                        });   

                                        //単価
                                        $sheet->setCellValue('G'.$row,$v['unit_price']);
                                        $sheet->cells('G'.$row, function($cells) { 
                                            $cells->setAlignment('right');
                                            $cells->setValignment('center');   
                                            $cells->setBorder('dotted', 'dotted', 'dotted', 'dotted', \PHPExcel_Style_Border::BORDER_DOTTED); 
                                        });  
                                        //シリアル番号
                                        $serial_rs = getHandleString($v['serial_no'],47);

                                        $sheet->setCellValue('H'.$row, $serial_rs);
                                        $sheet->mergeCells('H'.$row.':Q'.$row);
                                        $sheet->getStyle('H'.$row.':Q'.$row)->applyFromArray(getStyleExcel('styleAllBorderBol'));
                                        $sheet->cells('H'.$row.':Q'.$row, function($cells) { 
                                            $cells->setAlignment('left');
                                            $cells->setValignment('center');
                                            $cells->setBorder('dotted', 'medium', 'dotted', 'dotted', \PHPExcel_Style_Border::BORDER_MEDIUM); 
                                        });

                                        //set height  品名
                                        $height                     =   numLineOfRowExcel($serial_rs, 47);
                                        $total_line_detail_serial   =   $total_line_detail_serial + $height;

                                        $sheet->getRowDimension($row)->setRowHeight($height*16.5);
                                        $sheet->getStyle('H'.$row.':Q'.$row)->getAlignment()->setWrapText(true);

                                        $row++;
                                    }

                                    $posNull = $pos + 16 + $num_row_page - 1;
                                    $line   =   0;
                                    if ($line_detail > $total_line_detail_serial) {
                                        $line = $line_detail - $total_line_detail_serial;
                                        for ($j = 0; $j < $line; $j ++) { 
                                            $posNull    =   $posNull + 1;
                                            $sheet->setCellValue('A'.$posNull, '');
                                            $sheet->mergeCells('A'.$posNull.':E'.$posNull);
                                            $sheet->setBorder('A'.$posNull.':E'.$posNull, \PHPExcel_Style_Border::BORDER_DOTTED, "#000000");
                                            $sheet->getStyle('A'.$posNull.':E'.$posNull)->getBorders()->getLeft()->setBorderStyle(\PHPExcel_Style_Border::BORDER_MEDIUM, "#000000");

                                            $sheet->getRowDimension($posNull)->setRowHeight(16.5);

                                            $sheet->setCellValue('F'.$posNull, '');
                                            $sheet->setBorder('F'.$posNull, \PHPExcel_Style_Border::BORDER_DOTTED, "#000000");

                                            $sheet->setCellValue('G'.$posNull, '');
                                            $sheet->setBorder('G'.$posNull, \PHPExcel_Style_Border::BORDER_DOTTED, "#000000");

                                            $sheet->setCellValue('H'.$posNull, '');

                                            $sheet->getStyle('H'.$posNull.':Q'.$posNull)->getBorders()->getTop()->setBorderStyle(\PHPExcel_Style_Border::BORDER_DOTTED, "#000000");
                                            $sheet->getStyle('H'.$posNull.':Q'.$posNull)->getBorders()->getBottom()->setBorderStyle(\PHPExcel_Style_Border::BORDER_DOTTED, "#000000");
                                            $sheet->getStyle('H'.$posNull.':Q'.$posNull)->getBorders()->getLeft()->setBorderStyle(\PHPExcel_Style_Border::BORDER_DOTTED, "#000000");
                                            $sheet->getStyle('H'.$posNull.':Q'.$posNull)->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_MEDIUM, "#000000");
                                        }
                                    }

                                    /*************************END BODY*********************************/
                                    /********************************************************************/

                                    /**************************** FOOTER*********************************/
                                    /********************************************************************/

                                    //社 長
                                    $posFT      =  $line + 16 + $num_row_page;
                                    $pos_man    =  $pos + $posFT;
                                    $sheet->setCellValue('A'.$pos_man, '入金：');
                                    $sheet->cells('A'.$pos_man.':A'.$pos_man, function($cells) { 
                                        $cells->setAlignment('right');
                                        $cells->setValignment('center');   
                                    });

                                    $sheet->getRowDimension($pos_man)->setRowHeight(16.5);

                                    //40-41
                                    $sheet->getStyle('A'.$pos_man.':Q'.$pos_man)->getBorders()->getTop()->setBorderStyle(\PHPExcel_Style_Border::BORDER_MEDIUM, "#000000");
                                    $sheet->getStyle('A'.$pos_man.':A'.$pos_man)->getBorders()->getLeft()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN, "#000000");
                                    $sheet->getStyle('A'.$pos_man.':A'.$pos_man)->getBorders()->getLeft()->setBorderStyle(\PHPExcel_Style_Border::BORDER_MEDIUM, "#000000");

                                    $pos_mans           =   $pos + $posFT;

                                    $sheet->setCellValue('B'.$pos_mans, $header['deposit_no']);
                                    $sheet->mergeCells('B'.$pos_mans.':D'.$pos_mans);
                                    $sheet->cells('B'.$pos_mans.':D'.$pos_mans, function($cells) { 
                                        $cells->setAlignment('left');
                                        $cells->setValignment('center');   
                                    });

                                    $sheet->setCellValue('F'.$pos_mans, $header['deposit_date']);
                                    $sheet->mergeCells('F'.$pos_mans.':G'.$pos_mans);
                                    $sheet->cells('F'.$pos_mans.':G'.$pos_mans, function($cells) { 
                                        $cells->setAlignment('center');
                                        $cells->setValignment('center');   
                                    });                                    

                                    $sheet->getStyle('Q'.$pos_mans)->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_MEDIUM, "#000000");
                                    $pos_border1 =  $pos_mans + 1;
                                    $sheet->getStyle('Q'.$pos_border1)->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_MEDIUM, "#000000");

                                    //--------------------------------------------------------------//
                                    $pos_man_top = $pos_mans + 1;
                                    $sheet->getStyle('A'.$pos_man_top)->getBorders()->getLeft()->setBorderStyle(\PHPExcel_Style_Border::BORDER_MEDIUM, "#000000");
                                    $sheet->getStyle('A'.$pos_man_top.':Q'.$pos_man_top)->getBorders()->getBottom()->setBorderStyle(\PHPExcel_Style_Border::BORDER_MEDIUM, "#000000");

                                    //-----------------------------------------------------------------
                                    //社 長
                                    $pos_purchasing = $pos_mans + 2;

                                    $sheet->setCellValue('A'.$pos_purchasing, '社 長');
                                    $sheet->mergeCells('A'.$pos_purchasing.':D'.$pos_purchasing);
                                    $sheet->cells('A'.$pos_purchasing.':D'.$pos_purchasing, function($cells) { 
                                        $cells->setAlignment('center');
                                        $cells->setValignment('center');   
                                    });

                                    $sheet->getRowDimension($pos_purchasing)->setRowHeight(16.5);

                                    $sheet->getStyle('A'.$pos_purchasing.':D'.$pos_purchasing)->getBorders()->getBottom()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN, "#000000"); 
                                    $sheet->getStyle('A'.$pos_purchasing.':D'.$pos_purchasing)->getBorders()->getLeft()->setBorderStyle(\PHPExcel_Style_Border::BORDER_MEDIUM, "#000000");

                                    //社 長
                                    $pos_purchasing               =  $pos_mans + 2;
                                    $pos_purchasing_merges        =  $pos_mans + 4;

                                    $sheet->setCellValue('E'.$pos_purchasing, '印');
                                    $sheet->mergeCells('E'.$pos_purchasing.':F'.$pos_purchasing_merges);
                                    $sheet->cells('E'.$pos_purchasing.':F'.$pos_purchasing_merges, function($cells) { 
                                        $cells->setAlignment('center');
                                        $cells->setValignment('center');   
                                    }); 

                                    $sheet->getStyle('E'.$pos_purchasing.':F'.$pos_purchasing_merges)->getBorders()->getLeft()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN, "#000000");
                                    $sheet->getStyle('E'.$pos_purchasing.':F'.$pos_purchasing_merges)->getBorders()->getTop()->setBorderStyle(\PHPExcel_Style_Border::BORDER_MEDIUM, "#000000");

                                    $sheet->getStyle('G'.$pos_purchasing.':G'.$pos_purchasing_merges)->getBorders()->getLeft()->setBorderStyle(\PHPExcel_Style_Border::BORDER_MEDIUM, "#000000");

                                    $sheet->setCellValue('G'.$pos_purchasing, '品質保証責任者');
                                    $sheet->mergeCells('G'.$pos_purchasing.':I'.$pos_purchasing);
                                    $sheet->cells('G'.$pos_purchasing.':I'.$pos_purchasing, function($cells) { 
                                        $cells->setAlignment('center');
                                        $cells->setValignment('center');   
                                    }); 
                                    $sheet->getStyle('G'.$pos_purchasing.':I'.$pos_purchasing)->getBorders()->getBottom()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN, "#000000");

                                    $sheet->setCellValue('J'.$pos_purchasing, '印');
                                    $sheet->mergeCells('J'.$pos_purchasing.':K'.$pos_purchasing_merges);
                                    $sheet->cells('J'.$pos_purchasing.':K'.$pos_purchasing_merges, function($cells) { 
                                        $cells->setAlignment('center');
                                        $cells->setValignment('center');   
                                    }); 
                                    $sheet->getStyle('J'.$pos_purchasing.':K'.$pos_purchasing_merges)->getBorders()->getLeft()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN, "#000000");
                                    $sheet->getStyle('L'.$pos_purchasing.':L'.$pos_purchasing_merges)->getBorders()->getLeft()->setBorderStyle(\PHPExcel_Style_Border::BORDER_MEDIUM, "#000000");

                                     $sheet->setCellValue('L'.$pos_purchasing, '貯蔵管理責任者');
                                    $sheet->mergeCells('L'.$pos_purchasing.':O'.$pos_purchasing);
                                    $sheet->cells('L'.$pos_purchasing.':O'.$pos_purchasing, function($cells) { 
                                        $cells->setAlignment('center');
                                        $cells->setValignment('center');   
                                    }); 
                                    $sheet->getStyle('L'.$pos_purchasing.':O'.$pos_purchasing)->getBorders()->getBottom()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN, "#000000");

                                    $sheet->setCellValue('P'.$pos_purchasing, '印');
                                    $sheet->mergeCells('P'.$pos_purchasing.':Q'.$pos_purchasing_merges);
                                    $sheet->cells('P'.$pos_purchasing.':Q'.$pos_purchasing_merges, function($cells) { 
                                        $cells->setAlignment('center');
                                        $cells->setValignment('center');

                                    }); 
                                    $sheet->getStyle('P'.$pos_purchasing.':Q'.$pos_purchasing_merges)->applyFromArray(getStyleExcel('styleAllBorderBol'));

                                    $sheet->getStyle('P'.$pos_purchasing.':Q'.$pos_purchasing_merges)->getBorders()->getLeft()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN, "#000000");

                                    $pos_purchasing_1   =   $pos_purchasing + 1;
                                    $sheet->getStyle('A'.$pos_purchasing_1.':D'.$pos_purchasing_1)->getBorders()->getLeft()->setBorderStyle(\PHPExcel_Style_Border::BORDER_MEDIUM, "#000000");

                                    //承認
                                    $pos_confirm         =  $pos_mans + 4;

                                    $sheet->setCellValue('A'.$pos_confirm, '承認');
                                    $sheet->mergeCells('A'.$pos_confirm.':D'.$pos_confirm);
                                    $sheet->cells('A'.$pos_confirm.':D'.$pos_confirm, function($cells) { 
                                        $cells->setAlignment('center');
                                        $cells->setValignment('center');   
                                    });

                                    $sheet->getStyle('A'.$pos_confirm.':D'.$pos_confirm)->getBorders()->getLeft()->setBorderStyle(\PHPExcel_Style_Border::BORDER_MEDIUM, "#000000");

                                    $sheet->setCellValue('G'.$pos_confirm, '出荷許可');
                                    $sheet->mergeCells('G'.$pos_confirm.':I'.$pos_confirm);
                                    $sheet->cells('G'.$pos_confirm.':I'.$pos_confirm, function($cells) { 
                                        $cells->setAlignment('center');
                                        $cells->setValignment('center');   
                                    }); 

                                    $sheet->setCellValue('L'.$pos_confirm, '出荷完了');
                                    $sheet->mergeCells('L'.$pos_confirm.':O'.$pos_confirm);
                                    $sheet->cells('L'.$pos_confirm.':O'.$pos_confirm, function($cells) { 
                                        $cells->setAlignment('center');
                                        $cells->setValignment('center');   
                                    }); 

                                    //備考：
                                    $pos_inside_remarks                     =  $pos_mans + 5;
                                    $pos_inside_remarks_value               =  $pos_mans + 6;                       
                                    $pos_pos_inside_remarks_merge           =  $pos_mans + 8;

                                    $sheet->setCellValue('A'.$pos_inside_remarks, '備考：');
                                    $sheet->setCellValue('A'.$pos_inside_remarks_value, $header['inside_remarks']);
                                    $sheet->cells('A'.$pos_inside_remarks_value, function($cells) { 
                                        $cells->setAlignment('left');
                                        $cells->setValignment('top');   
                                    });

                                    $sheet->getRowDimension($pos_inside_remarks)->setRowHeight(16.5);

                                    $sheet->getStyle('A'.$pos_inside_remarks.':Q'.$pos_inside_remarks)->getBorders()->getTop()->setBorderStyle(\PHPExcel_Style_Border::BORDER_MEDIUM, "#000000");
                                    $sheet->getStyle('A'.$pos_inside_remarks.':Q'.$pos_inside_remarks)->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_MEDIUM, "#000000");
                                    $sheet->getStyle('A'.$pos_inside_remarks.':Q'.$pos_inside_remarks)->getBorders()->getLeft()->setBorderStyle(\PHPExcel_Style_Border::BORDER_MEDIUM, "#000000");

                                    $sheet->mergeCells('A'.$pos_inside_remarks_value.':Q'.$pos_pos_inside_remarks_merge);

                                    $sheet->getStyle('A'.$pos_inside_remarks_value.':Q'.$pos_pos_inside_remarks_merge)->applyFromArray(getStyleExcel('styleAllBorderBol'));
                                    $sheet->getStyle('A'.$pos_inside_remarks_value.':Q'.$pos_pos_inside_remarks_merge)->getBorders()->getTop()->setBorderStyle(\PHPExcel_Style_Border::BORDER_NONE, "#AAAAAA");

                                    $sheet->cells('Q'.$pos_inside_remarks_value.':Q'.$pos_pos_inside_remarks_merge, function($cells) { 
                                        $cells->setAlignment('center');
                                        $cells->setValignment('center');
                                    });

                                    $pos_comment = $pos_pos_inside_remarks_merge + 1;
                                    $sheet->setCellValue('A'.$pos_comment, '*品質記録は修正液・修正テープ等で修正しない事。');
                                    $sheet->getStyle('A'.$pos_comment.':Q'.$pos_comment)->getBorders()->getTop()->setBorderStyle(\PHPExcel_Style_Border::BORDER_MEDIUM, "#000000"); 

                                    $pos_page = $pos_pos_inside_remarks_merge + 2;
                                    $sheet->setCellValue('A'.$pos_page, $num_page.'/'.count($data_pagi));
                                    $sheet->mergeCells('A'.$pos_page.':Q'.$pos_page);
                                    $sheet->cells('A'.$pos_page.':Q'.$pos_page, function($cells) { 
                                        $cells->setAlignment('center');
                                        $cells->setValignment('bottom');   
                                    }); 

                                    $sheet->getRowDimension($pos_page)->setRowHeight(16.5);
 
                                    /************************END FOOTER*********************************/
                                    /********************************************************************/
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
            $zipFileName    =   '出荷指示書_'.date("YmdHis").'.zip';

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

    /*
    * getDataHeader
    * -----------------------------------------------
    * @author      :   ANS831 - 2018/02/06 - create
    * @param       :   
    * @return      :   format data header excel
    * @access      :   protected
    * @see         :   remark
    */
    protected function getDataHeader($data) {
        try {
            $data_Header = [
                '1',
                '1',
                '1',
                '1',
                '1',
                '1',
                [['value' => $data['cust_nm'] , 'leng' => '36']],
                '1',
                [['value' => $data['forwarding_way_div'] , 'leng' => '20']],
                '1',
                '1',
                '1',
                '1'
            ];
            return $data_Header;
        } catch (\Exception $e) {
            return response()->json(['response' => false]);
        }
    }

    /*
    * getDataFooter
    * -----------------------------------------------
    * @author      :   ANS831 - 2018/02/06 - create
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
            ];
            return $data_Footer;
        } catch (\Exception $e) {
            return response()->json(['response' => false]);
        }
    }

    /**
    * get data detail
    * -----------------------------------------------
    * @author      :   ANS831 - 2018/02/06 - create
    * @param       :   data array of deail
    * @return      :   return data detail of excel
    * @access      :   public
    * @see         :   remark
    */
    protected function getDataDetail($data) {
        try {
            $data_Detail = [];
            if (!empty($data)) {
                foreach ($data as $key => $value) {
                    $data_Detail[] =  [['value' => $value['item_nm_j'] , 'leng' => '28'],['value' => getHandleString($value['serial_no'],47) , 'leng' => '47']];
                }
            }
            return $data_Detail;
        } catch (\Exception $e) {
            return response()->json(['response' => false]);
        }
    }
}
