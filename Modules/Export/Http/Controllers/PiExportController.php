<?php
/**
*|--------------------------------------------------------------------------
*| PiExportController - Pi Export Excel
*|--------------------------------------------------------------------------
*| Package       : Pi Export Excel
*| @author       : Trieunb - ANS806 - trieunb@ans-asia.com
*| @created date : 2017/12/20
*| 
*/
namespace Modules\Export\Http\Controllers;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Session, DB, Dao, Button;
use Excel, PHPExcel_Worksheet_Drawing;
use Modules\Common\Http\Controllers\CommonController as common;
class PiExportController extends Controller
{
    protected $file_excel   = 'PI(見積)伝票一覧_';
    protected $totalLine    = '58';
    /*
     * Header
     * @var array
     */
    private $header = [
        'PI No ',
        '受注 No',
        '見積日',
        '取引先コード',
        '取引先名',
        '国',
        'Code',
        'Item Name',
        'Unit Price',
        'Q\'ty',
        'Cur',
        'Amount',
        'ステータス',
    ];
    /**
    * Pi output
    * -----------------------------------------------
    * @author      :   ANS342- 2018/05/29 - create
    * @param       :
    * @return      :   mixed
    * @access      :   public
    * @see         :   remark
    */
    public function postOutput(Request $request) {
        try {
            $param              =   $request->all();
            $sql                =   "SPC_002_PI_SEARCH_FND1"; 
            $result             =   Dao::call_stored_procedure($sql, $param, true);
            $result[0]          =   isset($result[0]) ? $result[0] : NULL;
            //width of columns
            $arrWidthColumns    =   array(
                'A'     =>  15,
                'B'     =>  15,
                'C'     =>  15,
                'D'     =>  15,
                'E'     =>  30,
                'F'     =>  10,
                'G'     =>  10,
                'H'     =>  50,
                'I'     =>  15,
                'J'     =>  15,
                'K'     =>  15,
                'L'     =>  15,
                'M'     =>  15,
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
                        $sheet->getStyle('A1:M1')->applyFromArray($styleAllBorder);
                        $sheet->getStyle('A1:M1')->getFont()->getColor()->setRGB('ffffff');
                        $sheet->cells('A1:M1', function($cells) {
                            $cells->setBackground('#248af0');
                            $cells->setAlignment('center');
                            $cells->setValignment('center');
                        });
                        //write data to excel file
                        foreach ($result[0] as $k1 => $v1) {
                            $row ++;
                            $sheet->getStyle('A'.$row.':M'.$row)->applyFromArray($styleAllBorder);
                            $sheet->getStyle('A'.$row.':M'.$row)->getAlignment()->setWrapText(true);
                            $sheet->row($row, array(
                                $v1['pi_no'], 
                                $v1['rcv_no'], 
                                $v1['pi_date'], 
                                $v1['cust_cd'], 
                                $v1['cust_nm'],
                                $v1['cust_country_div'],
                                $v1['product_cd'],
                                $v1['description'],
                                $v1['unit_price'],
                                $v1['qty'],
                                $v1['currency_div'],
                                $v1['qty_unit_price'],
                                $v1['pi_status_nm'],
                            ));

                            //even odd row background color
                            if($row % 2 != 0){
                                $sheet->cells('A'.$row.':M'.$row, function($cells) {
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
                                $cells->setAlignment('center');
                                $cells->setValignment('center');
                            });
                            //align left data
                            $sheet->cells('D'.$row.':H'.$row, function($cells) {
                                $cells->setAlignment('left');
                                $cells->setValignment('center');
                            });
                            //align left data
                            $sheet->cells('E'.$row, function($cells) {
                                $cells->setAlignment('left');
                                $cells->setValignment('center');
                            }); 
                            //align left data
                            $sheet->cells('F'.$row, function($cells) {
                                $cells->setAlignment('left');
                                $cells->setValignment('center');
                            });
                            //align left data
                            $sheet->cells('G'.$row, function($cells) {
                                $cells->setAlignment('left');
                                $cells->setValignment('center');
                            });
                            //align left data
                            $sheet->cells('H'.$row, function($cells) {
                                $cells->setAlignment('left');
                                $cells->setValignment('center');
                            });
                            //align left data
                            $sheet->cells('I'.$row, function($cells) {
                                $cells->setAlignment('right');
                                $cells->setValignment('center');
                            });
                            //align left data
                            $sheet->cells('J'.$row, function($cells) {
                                $cells->setAlignment('right');
                                $cells->setValignment('center');
                            });
                            //align left data
                            $sheet->cells('K'.$row, function($cells) {
                                $cells->setAlignment('left');
                                $cells->setValignment('center');
                            });
                            //align left data
                            $sheet->cells('L'.$row, function($cells) {
                                $cells->setAlignment('right');
                                $cells->setValignment('center');
                            });
                            //align left data
                            $sheet->cells('M'.$row, function($cells) {
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
    * post Print pi detail
    * -----------------------------------------------
    * @author      :   ANS806 - 2017/12/05 - create
    * @param       :
    * @return      :   mixed
    * @access      :   public
    * @see         :   remark
    */
    public function postPiPrint(Request $request) {
        try {
            //data master layout
            $header                 =   common::getDataHeaderExcel();
            $header_jp              =   common::getDataHeaderExcel('JP');
            // $header                 =   [];
            $data                   = $request->all();
            $data['pi_list']        =  json_encode($data['pi_list']);//parse json to string
            $data['cre_user_cd']    = \GetUserInfo::getInfo('user_cd');
            $data['cre_prg_cd']     = '003_pi-excel';
            $data['cre_ip']         = \GetUserInfo::getInfo('user_ip');

            $sql        =   "SPC_003_PI_EXCEL_ACT1"; 
            $result     =   Dao::call_stored_procedure($sql, $data, true);
            // return $result; die;
            $response   =   true;
            $error      =   '';
            $error_cd   =   '';
            $zip_array  =   '';
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
                        'A'     =>  9,
                        'B'     =>  7,
                        'C'     =>  7,
                        'D'     =>  5,
                        'E'     =>  14,
                        'F'     =>  6,
                        'G'     =>  2,
                        'H'     =>  6,
                        'I'     =>  9,
                        'J'     =>  9,
                        'K'     =>  4,
                        'L'     =>  4,
                        'M'     =>  3,
                        'N'     =>  17,
                    ];
                    $marginPage         =   [0.4, 0.3, 0.4, 0.4];
                    $zip_array      =   '';
                    $error_flag     =   false;
                    $error          =   '';
                    for ($k = 0; $k < count($result[2]); $k++) {
                        // get data by key
                        $key    = ['pi_no' => $result[2][$k]['pi_no']];
                        // get data array buy_h by key
                        $pi_h  = getDataByKey($key, $result[2])[0];
                        // get data array buy_d by key
                        $pi_d  = getDataByKey($key, $result[3]);
                        // calculate line of top, header, footer 
                        $line_top       = numLinesDataExcel($this->getDataTop($header), false);
                        $line_header    = numLinesDataExcel($this->getDataHeader($pi_h), false);
                        $line_footer    = numLinesDataExcel($this->getDataFooter($pi_h), false);
                        // calculate line detail
                        $line_detail    = $this->totalLine - ($line_top + $line_header + $line_footer);
                        // return $line_top.'-'.$line_header.'-'.$line_detail.'-'.$line_footer;die;
                        $pagi           = pagiDataExcel($this->getDataDetail($pi_d), $line_detail);
                        
                        $page           = $pagi[0];
                        // get data pagination of each page
                        $data_pagi      = dataPageExcel($pi_d,  $page);
                        // $file_name  =   'PI_'.$request->pi_no.$i;
                        $file_name  =   $this->file_excel.$key['pi_no'];
                        // **********************************************************************
                        //      Export Excel
                        // **********************************************************************
                        Excel::create($file_name, function($excel) use ($file_name, $pi_h, $pi_d, $header, $header_jp, 
                                                                        $data_pagi, $pagi, $line_detail, $line_top, 
                                                                        $line_header, $line_footer, $arrWidthColumns, $marginPage) {
                            $excel->sheet($file_name, function($sheet) use ($pi_h, $pi_d, $header, $header_jp, $data_pagi, 
                                                                            $pagi, $line_detail, $line_top, 
                                                                            $line_header, $line_footer, $arrWidthColumns, $marginPage) {
                                //set default height
                                $height = 1;
                                // set font for excel
                                $sheet->getDefaultStyle()->getFont()->setName('Arial')->setSize(9);
                                // set width for colum excel
                                $sheet->setWidth($arrWidthColumns);
                                // set Gridlines
                                $sheet->setShowGridlines(false);
                                //set margin for page
                                $sheet->setPageMargin($marginPage);
                                $pos        =   0;
                                $page_size  =   37;
                                // pagination
                                for ($i = 0; $i < count($data_pagi); $i++) {
                                    if ($i == 0) {
                                        $pos    =   $i + 1;
                                    } else {
                                        $pos    =   $pos + ($page_size+count($data_pagi[$i-1])) + 1;
                                    }
                                    // **********************************************************************
                                    //      Set Info Company
                                    // **********************************************************************
                                    // set logo
                                    $objDrawing = new PHPExcel_Worksheet_Drawing;
                                    $objDrawing->setPath(public_path('images/logo_excel.png'));
                                    $objDrawing->setCoordinates('A'.$pos);
                                    $objDrawing->setWorksheet($sheet);
                                    // set  company address
                                    $sheet->mergeCells('H'.$pos.':N'.$pos);
                                    if ($pi_h['cust_country_div'] == 'JP') {
                                        $sheet->setCellValue('H'.$pos, $header_jp['company_zip_address']);
                                    } else {
                                        $sheet->setCellValue('H'.$pos, $header['company_zip_address']);
                                    }
                                    // set height of company_zip_address
                                    // $height  =   numLineOfRowExcel($header['company_zip_address'], 62);
                                    $sheet->getRowDimension($pos)->setRowHeight($height*15);
                                    $sheet->getStyle('H'.$pos.':N'.$pos)->getAlignment()->setWrapText(true);
                                    // set Tel
                                    $posTF     =   $pos+1;
                                    $sheet->setCellValue('H'.$posTF, 'Tel: ');
                                    $sheet->getStyle('H'.$posTF)->applyFromArray(getStyleExcel('fontBold'));
                                    $sheet->mergeCells('I'.$posTF.':J'.$posTF);
                                    if ($pi_h['cust_country_div'] == 'JP') {
                                        //$sheet->setCellValue('I'.$posTF, $header_jp['company_tel']);
                                        $sheet->getCell('I'.$posTF)->setValueExplicit($header_jp['company_tel'], \PHPExcel_Cell_DataType::TYPE_STRING);
                                    } else {
                                        //$sheet->setCellValue('I'.$posTF, $header['company_tel']);
                                        $sheet->getCell('I'.$posTF)->setValueExplicit($header['company_tel'], \PHPExcel_Cell_DataType::TYPE_STRING);
                                    }
                                    $sheet->getStyle('I'.$posTF)->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);

                                    // set Fax
                                    $sheet->setCellValue('K'.$posTF, 'Fax: ');
                                    $sheet->getStyle('K'.$posTF)->applyFromArray(getStyleExcel('fontBold'));
                                    $sheet->mergeCells('L'.$posTF.':N'.$posTF);
                                    if ($pi_h['cust_country_div'] == 'JP') {
                                        //$sheet->setCellValue('L'.$posTF, $header_jp['company_fax']);
                                        $sheet->getCell('L'.$posTF)->setValueExplicit($header_jp['company_fax'], \PHPExcel_Cell_DataType::TYPE_STRING);
                                    } else {
                                        //$sheet->setCellValue('L'.$posTF, $header['company_fax']);
                                        $sheet->getCell('L'.$posTF)->setValueExplicit($header['company_fax'], \PHPExcel_Cell_DataType::TYPE_STRING);
                                    }
                                    $sheet->getStyle('L'.$posTF)->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);

                                    // set Email
                                    $posEU   =   $pos+2;
                                    $sheet->setCellValue('H'.$posEU, 'Email : ');
                                    $sheet->getStyle('H'.$posEU)->applyFromArray(getStyleExcel('fontBold'));
                                    $sheet->mergeCells('I'.$posEU.':J'.$posEU);
                                    $sheet->setCellValue('I'.$posEU, $header['company_mail']);
                                    // set url
                                    $sheet->setCellValue('K'.$posEU, 'URL: ');
                                    $sheet->getStyle('K'.$posEU)->applyFromArray(getStyleExcel('fontBold'));
                                    $sheet->mergeCells('L'.$posEU.':N'.$posEU);
                                    if ($pi_h['cust_country_div'] == 'JP') {
                                        $sheet->setCellValue('L'.$posEU, $header_jp['company_url']);
                                    } else {
                                        $sheet->setCellValue('L'.$posEU, $header['company_url']);
                                    }

                                    $posL     =   $pos+3;
                                    $sheet->getRowDimension($posL)->setRowHeight(1);
                                    // **********************************************************************
                                    //      Set Content Header
                                    // **********************************************************************
                                    $posTitle   =   $pos+4;
                                    // $sheet->setCellValue('B'.$posTitle, 'Proforma Invoice');
                                    $sheet->setCellValue('B'.$posTitle, $this->getTitle($pi_h['cust_country_div'])['title_head']);
                                    $sheet->mergeCells('B'.$posTitle.':M'.$posTitle);
                                    $sheet->cells('B'.$posTitle.':M'.$posTitle, function($cells) { 
                                        $cells->setAlignment('center');
                                        $cells->setValignment('center');   
                                    });
                                    $sheet->getStyle('B'.$posTitle.':M'.$posTitle)->applyFromArray(getStyleExcel('fontTitle'));
                                    $sheet->getStyle('A'.$posTitle.':N'.$posTitle)->applyFromArray(getStyleExcel('styleOutlineBorder'));
                                    //set page number / page total
                                    $sheet->setCellValue('N'.$posTitle, ($i+1).'/'.$pagi[1]);
                                    $sheet->cells('N'.$posTitle, function($cells) { 
                                        $cells->setAlignment('center');
                                        $cells->setValignment('center');   
                                    });
                                    //set To
                                    $posTo     =  $pos+5;
                                    $posMerg   =  $posTo + 3;
                                    $sheet->setCellValue('A'.$posTo, $this->getTitle($pi_h['cust_country_div'])['title_To']);
                                    $sheet->mergeCells('A'.$posTo.':B'.$posMerg);
                                    $sheet->cells('A'.$posTo.':B'.$posMerg, function($cells) { 
                                        $cells->setAlignment('left');
                                        $cells->setValignment('top');   
                                    });
                                    $sheet->getStyle('A'.$posTo.':B'.$posMerg)->applyFromArray(getStyleExcel('fontBold'));
                                    $sheet->getStyle('A'.$posTo.':B'.$posMerg)->applyFromArray(getStyleExcel('styleAllBorder'));
                                    //set value cust_nm
                                    $sheet->setCellValue('C'.$posTo, $pi_h['cust_nm']);
                                    $sheet->mergeCells('C'.$posTo.':J'.$posTo);
                                    $sheet->cells('C'.$posTo.':J'.$posTo, function($cells) { 
                                        $cells->setAlignment('left');
                                        $cells->setValignment('top');   
                                    });
                                    //set height of cust_adr1
                                    // $height  =   numLineOfRowExcel($pi_h['cust_nm'], 72);
                                    $sheet->getRowDimension($posTo)->setRowHeight($height*15);
                                    $sheet->getStyle('C'.$posTo.':J'.$posTo)->getAlignment()->setWrapText(true);

                                    $posCustAdr1     =  $pos+6;
                                    //set value cust_adr1
                                    $sheet->setCellValue('C'.$posCustAdr1, $pi_h['cust_adr1']);
                                    $sheet->mergeCells('C'.$posCustAdr1.':J'.$posCustAdr1);
                                    $sheet->cells('C'.$posCustAdr1.':J'.$posCustAdr1, function($cells) { 
                                        $cells->setAlignment('left');
                                        $cells->setValignment('top');   
                                    });
                                    //set height of cust_adr1
                                    // $height  =   numLineOfRowExcel($pi_h['cust_adr1'], 72);
                                    $sheet->getRowDimension($posCustAdr1)->setRowHeight($height*15);
                                    $sheet->getStyle('C'.$posCustAdr1.':J'.$posCustAdr1)->getAlignment()->setWrapText(true);

                                    $posCustAdr2     =  $pos+7;
                                    //set value cust_adr2
                                    $sheet->setCellValue('C'.$posCustAdr2, $pi_h['cust_adr2'] . ' ' . $pi_h['cust_country_nm']);
                                    $sheet->mergeCells('C'.$posCustAdr2.':J'.$posCustAdr2);
                                    $sheet->cells('C'.$posCustAdr2.':J'.$posCustAdr2, function($cells) { 
                                        $cells->setAlignment('left');
                                        $cells->setValignment('top');   
                                    });
                                    //set height of cust_adr2
                                    // $height  =   numLineOfRowExcel($pi_h['cust_adr2'] . ' ' . $pi_h['cust_country_nm'], 72);
                                    $sheet->getRowDimension($posCustAdr2)->setRowHeight($height*15);
                                    $sheet->getStyle('C'.$posCustAdr2.':J'.$posCustAdr2)->getAlignment()->setWrapText(true);
                                    //set Tel
                                    $posCustTel     =  $pos+8;
                                    $sheet->setCellValue('C'.$posCustTel, 'Tel');
                                    //$sheet->setCellValue('D'.$posCustTel, $pi_h['cust_tel']);
                                    $sheet->getCell('D'.$posCustTel)->setValueExplicit($pi_h['cust_tel'], \PHPExcel_Cell_DataType::TYPE_STRING);
                                    $sheet->getStyle('D'.$posCustTel)->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
                                    $sheet->mergeCells('D'.$posCustTel.':E'.$posCustTel);
                                    $sheet->getStyle('C'.$posCustTel)->applyFromArray(getStyleExcel('fontBold'));
                                    //set Fax
                                    $sheet->setCellValue('F'.$posCustTel, 'Fax');
                                    //$sheet->setCellValue('G'.$posCustTel, $pi_h['cust_fax']);
                                    $sheet->getCell('G'.$posCustTel)->setValueExplicit($pi_h['cust_fax'], \PHPExcel_Cell_DataType::TYPE_STRING);
                                    $sheet->getStyle('G'.$posCustTel)->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
                                    $sheet->mergeCells('G'.$posCustTel.':J'.$posCustTel);
                                    $sheet->getStyle('F'.$posCustTel)->applyFromArray(getStyleExcel('fontBold'));
                                    $sheet->getStyle('C'.$posTo.':J'.$posMerg)->applyFromArray(getStyleExcel('styleOutlineBorder'));
                                    //set No.
                                    $sheet->setCellValue('K'.$posTo, 'No.');
                                    $sheet->mergeCells('K'.$posTo.':L'.$posTo);
                                    $sheet->cells('K'.$posTo.':L'.$posTo, function($cells) { 
                                        $cells->setAlignment('center');
                                        $cells->setValignment('center');   
                                    });
                                    $sheet->getStyle('K'.$posTo.':L'.$posTo)->applyFromArray(getStyleExcel('fontBold'));
                                    $sheet->getStyle('K'.$posTo.':L'.$posTo)->applyFromArray(getStyleExcel('styleAllBorder'));
                                    //set Pi No Value
                                    $sheet->setCellValue('M'.$posTo, $pi_h['pi_no']);
                                    $sheet->mergeCells('M'.$posTo.':N'.$posTo);
                                    $sheet->cells('M'.$posTo.':N'.$posTo, function($cells) { 
                                        $cells->setAlignment('center');
                                        $cells->setValignment('center');   
                                    });
                                    $sheet->getStyle('M'.$posTo.':N'.$posTo)->applyFromArray(getStyleExcel('styleAllBorder'));
                                    //set Date.
                                    $sheet->setCellValue('K'.$posCustAdr1, 'Date');
                                    $sheet->mergeCells('K'.$posCustAdr1.':L'.$posCustAdr1);
                                    $sheet->cells('K'.$posCustAdr1.':L'.$posCustAdr1, function($cells) { 
                                        $cells->setAlignment('center');
                                        $cells->setValignment('center');   
                                    });
                                    $sheet->getStyle('K'.$posCustAdr1.':L'.$posCustAdr1)->applyFromArray(getStyleExcel('fontBold'));
                                    $sheet->getStyle('K'.$posCustAdr1.':L'.$posCustAdr1)->applyFromArray(getStyleExcel('styleAllBorder'));
                                    //set Pi Date Value
                                    $sheet->setCellValue('M'.$posCustAdr1, strftime("%B %d,%Y", strtotime($pi_h['pi_date'])));
                                    // $sheet->setCellValue('M'.$posCustAdr1, $pi_h['pi_date']);
                                    $sheet->mergeCells('M'.$posCustAdr1.':N'.$posCustAdr1);
                                    $sheet->cells('M'.$posCustAdr1.':N'.$posCustAdr1, function($cells) { 
                                        $cells->setAlignment('center');
                                        $cells->setValignment('center');   
                                    });
                                    $sheet->getStyle('M'.$posCustAdr1.':N'.$posCustAdr1)->applyFromArray(getStyleExcel('styleAllBorder'));
                                    //set Consignee
                                    $posCons       =  $pos+9;
                                    $posConsMer    =  $posCons + 3;
                                    // $sheet->setCellValue('A'.$posCons, 'Consignee');
                                    $sheet->setCellValue('A'.$posCons, $this->getTitle($pi_h['cust_country_div'])['title_Consignee']);
                                    $sheet->mergeCells('A'.$posCons.':B'.$posConsMer);
                                    $sheet->cells('A'.$posCons.':B'.$posConsMer, function($cells) { 
                                        $cells->setAlignment('left');
                                        $cells->setValignment('top');   
                                    });
                                    $sheet->getStyle('A'.$posCons.':B'.$posConsMer)->applyFromArray(getStyleExcel('fontBold'));
                                    $sheet->getStyle('A'.$posCons.':B'.$posConsMer)->applyFromArray(getStyleExcel('styleAllBorder'));
                                    //set value consignee_nm
                                    $sheet->setCellValue('C'.$posCons, $pi_h['consignee_nm']);
                                    $sheet->mergeCells('C'.$posCons.':J'.$posCons);
                                    $sheet->cells('C'.$posCons.':J'.$posCons, function($cells) { 
                                        $cells->setAlignment('left');
                                        $cells->setValignment('top');   
                                    });
                                    //set height of consignee_nm
                                    // $height  =   numLineOfRowExcel($pi_h['consignee_nm'], 72);
                                    $sheet->getRowDimension($posCons)->setRowHeight($height*15);
                                    $sheet->getStyle('C'.$posCons.':J'.$posCons)->getAlignment()->setWrapText(true);
                                    //set value consignee_adr1
                                    $posConsAdr1     =  $pos+10;
                                    $sheet->setCellValue('C'.$posConsAdr1, $pi_h['consignee_adr1']);
                                    $sheet->mergeCells('C'.$posConsAdr1.':J'.$posConsAdr1);
                                    $sheet->cells('C'.$posConsAdr1.':J'.$posConsAdr1, function($cells) { 
                                        $cells->setAlignment('left');
                                        $cells->setValignment('top');   
                                    });
                                    //set height of consignee_adr1
                                    // $height  =   numLineOfRowExcel($pi_h['consignee_adr1'], 72);
                                    $sheet->getRowDimension($posConsAdr1)->setRowHeight($height*15);
                                    $sheet->getStyle('C'.$posConsAdr1.':J'.$posConsAdr1)->getAlignment()->setWrapText(true);
                                    //set value consignee_adr2
                                    $posConsAdr2     =  $pos+11;
                                    $sheet->setCellValue('C'.$posConsAdr2, $pi_h['consignee_adr2'] . ',' . $pi_h['consignee_country_nm']);
                                    $sheet->mergeCells('C'.$posConsAdr2.':J'.$posConsAdr2);
                                    $sheet->cells('C'.$posConsAdr2.':J'.$posConsAdr2, function($cells) { 
                                        $cells->setAlignment('left');
                                        $cells->setValignment('top');   
                                    });
                                    //set height of consignee_adr2
                                    // $height  =   numLineOfRowExcel($pi_h['consignee_adr2'] . ' ' . $pi_h['consignee_country_nm'], 72);
                                    $sheet->getRowDimension($posConsAdr2)->setRowHeight($height*15);
                                    $sheet->getStyle('C'.$posConsAdr2.':J'.$posConsAdr2)->getAlignment()->setWrapText(true);

                                    //set Tel
                                    $posConsTel     =  $pos+12;
                                    $sheet->setCellValue('C'.$posConsTel, 'Tel');
                                    //$sheet->setCellValue('D'.$posConsTel, $pi_h['consignee_tel']);
                                    $sheet->getCell('D'.$posConsTel)->setValueExplicit($pi_h['consignee_tel'], \PHPExcel_Cell_DataType::TYPE_STRING);
                                    $sheet->getStyle('D'.$posConsTel)->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
                                    $sheet->mergeCells('D'.$posConsTel.':E'.$posConsTel);
                                    $sheet->getStyle('C'.$posConsTel)->applyFromArray(getStyleExcel('fontBold'));
                                    //set Fax
                                    $sheet->setCellValue('F'.$posConsTel, 'Fax');
                                    //$sheet->setCellValue('G'.$posConsTel, $pi_h['consignee_fax']);
                                    $sheet->getCell('G'.$posConsTel)->setValueExplicit($pi_h['consignee_fax'], \PHPExcel_Cell_DataType::TYPE_STRING);
                                    $sheet->getStyle('G'.$posConsTel)->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
                                    $sheet->mergeCells('G'.$posConsTel.':J'.$posConsTel);
                                     $sheet->getStyle('F'.$posConsTel)->applyFromArray(getStyleExcel('fontBold'));
                                    $sheet->getStyle('C'.$posCons.':J'.$posConsMer)->applyFromArray(getStyleExcel('styleOutlineBorder'));

                                    //set Mark 1
                                    $postMark1  =   $pos+7;
                                    //set height of mark1
                                    $mark1      =   '';
                                    $height     =   1;
                                    if ($pi_h['cust_country_div'] !== 'JP') {
                                        // $height  =   numLineOfRowExcel($pi_h['mark1'], 33);
                                        $mark1   =   $pi_h['mark1'];
                                    }
                                    $sheet->setCellValue('K'.$postMark1, $mark1);
                                    $sheet->mergeCells('K'.$postMark1.':N'.$postMark1);
                                    $sheet->getStyle('K'.$postMark1.':N'.$postMark1)->applyFromArray(getStyleExcel('styleAllBorder'));
                                    $sheet->cells('K'.$postMark1.':N'.$postMark1, function($cells) { 
                                        $cells->setAlignment('center');
                                        $cells->setValignment('center'); 
                                        $cells->setBorder('none', 'thin', 'none', 'thin', \PHPExcel_Style_Border::BORDER_THIN);
                                    });
                                    //set height of mark2
                                    $mark2      =   '';
                                    $height     =   1;
                                    if ($pi_h['cust_country_div'] !== 'JP') {
                                        // $height  =   numLineOfRowExcel($pi_h['mark2'], 33);
                                        $mark2   =   $pi_h['mark2'];
                                    }
                                    $sheet->getRowDimension($postMark1)->setRowHeight($height*15);
                                    $sheet->getStyle('K'.$postMark1.':N'.$postMark1)->getAlignment()->setWrapText(true);

                                    $postMark2  =   $pos+8;
                                    $sheet->setCellValue('K'.$postMark2, $mark2);
                                    $sheet->mergeCells('K'.$postMark2.':N'.$postMark2);
                                    $sheet->getStyle('K'.$postMark2.':N'.$postMark2)->applyFromArray(getStyleExcel('styleAllBorder'));
                                    $sheet->cells('K'.$postMark2.':N'.$postMark2, function($cells) { 
                                        $cells->setAlignment('center');
                                        $cells->setValignment('center');
                                        $cells->setBorder('none', 'thin', 'none', 'thin', \PHPExcel_Style_Border::BORDER_THIN);
                                    });
                                    
                                    $sheet->getRowDimension($postMark2)->setRowHeight($height*15);
                                    $sheet->getStyle('K'.$postMark2.':N'.$postMark2)->getAlignment()->setWrapText(true);
                                                                        //set height of mark3
                                    // $mark3      =    '';
                                    // $height     =   1;
                                    // if ($pi_h['cust_country_div'] !== 'JP') {
                                    //     $height  =   numLineOfRowExcel($pi_h['mark3'], 33);
                                    //     $mark3   =   $pi_h['mark3'];
                                    // }
                                    // $postMark3  =   $pos+9;
                                    // $sheet->setCellValue('K'.$postMark3, $mark3);
                                    // $sheet->mergeCells('K'.$postMark3.':N'.$postMark3);
                                    // $sheet->getStyle('K'.$postMark3.':N'.$postMark3)->applyFromArray(getStyleExcel('styleAllBorder'));
                                    // $sheet->cells('K'.$postMark3.':N'.$postMark3, function($cells) { 
                                    //     $cells->setAlignment('center');
                                    //     $cells->setValignment('center');
                                    //     $cells->setBorder('none', 'thin', 'none', 'thin', \PHPExcel_Style_Border::BORDER_THIN);
                                    // });
                                    // $sheet->getRowDimension($postMark3)->setRowHeight($height*15);
                                    // $sheet->getStyle('K'.$postMark3.':N'.$postMark3)->getAlignment()->setWrapText(true);
                                    //set height of mark4
                                    $mark4      =    '';
                                    $height     =   1;
                                    if ($pi_h['cust_country_div'] !== 'JP') {
                                        // $height  =   numLineOfRowExcel($pi_h['mark4'], 33);
                                        $mark4   =   $pi_h['mark4'];
                                    }
                                    $postMark4  =   $pos+9;
                                    $sheet->setCellValue('K'.$postMark4, $mark4);
                                    $sheet->mergeCells('K'.$postMark4.':N'.$postMark4);
                                    $sheet->getStyle('K'.$postMark4.':N'.$postMark4)->applyFromArray(getStyleExcel('styleAllBorder'));
                                    $sheet->cells('K'.$postMark4.':N'.$postMark4, function($cells) { 
                                        $cells->setAlignment('center');
                                        $cells->setValignment('center');
                                        $cells->setBorder('none', 'thin', 'none', 'thin', \PHPExcel_Style_Border::BORDER_THIN);
                                    });
                                    $sheet->getRowDimension($postMark4)->setRowHeight($height*15);
                                    $sheet->getStyle('K'.$postMark4.':N'.$postMark4)->getAlignment()->setWrapText(true);

                                    $postMark     =   $pos+10;
                                    $postMarkMer  =   $pos+12;
                                    $sheet->mergeCells('K'.$postMark.':N'.$postMarkMer);
                                    $sheet->getStyle('K'.$postMark.':N'.$postMarkMer)->applyFromArray(getStyleExcel('styleAllBorder'));
                                    $sheet->cells('K'.$postMark.':N'.$postMarkMer, function($cells) { 
                                        $cells->setAlignment('center');
                                        $cells->setValignment('center');
                                        $cells->setBorder('none', 'thin', 'thin', 'thin', \PHPExcel_Style_Border::BORDER_THIN);
                                    });
                                    //set Packing Title
                                    $posPacking    =  $pos+13;
                                    // $sheet->setCellValue('A'.$posPacking, 'Packing');
                                    $sheet->getRowDimension($posPacking)->setRowHeight(15);
                                    $sheet->setCellValue('A'.$posPacking, $this->getTitle($pi_h['cust_country_div'])['title_Packing']);
                                    $sheet->mergeCells('A'.$posPacking.':B'.$posPacking);
                                    $sheet->cells('A'.$posPacking.':B'.$posPacking, function($cells) { 
                                        $cells->setAlignment('center');
                                        $cells->setValignment('center');   
                                    });
                                    $sheet->getStyle('A'.$posPacking.':B'.$posPacking)->applyFromArray(getStyleExcel('fontBold'));
                                    $sheet->getStyle('A'.$posPacking.':B'.$posPacking)->applyFromArray(getStyleExcel('styleAllBorder'));
                                    //set Packing value
                                    $sheet->setCellValue('C'.$posPacking, $pi_h['packing']);
                                    $sheet->mergeCells('C'.$posPacking.':F'.$posPacking);
                                    $sheet->getStyle('C'.$posPacking.':F'.$posPacking)->applyFromArray(getStyleExcel('styleAllBorder'));
                                    $sheet->cells('C'.$posPacking.':F'.$posPacking, function($cells) { 
                                        $cells->setAlignment('left');
                                        $cells->setValignment('center');   
                                    });
                                    //set Shipment Title
                                    // $sheet->setCellValue('G'.$posPacking, 'Shipment');
                                    $sheet->setCellValue('G'.$posPacking, $this->getTitle($pi_h['cust_country_div'])['title_Shipment']);
                                    $sheet->mergeCells('G'.$posPacking.':I'.$posPacking);
                                    $sheet->getStyle('G'.$posPacking.':I'.$posPacking)->applyFromArray(getStyleExcel('fontBold'));
                                    $sheet->getStyle('G'.$posPacking.':I'.$posPacking)->applyFromArray(getStyleExcel('styleAllBorder'));
                                    $sheet->cells('G'.$posPacking.':I'.$posPacking, function($cells) { 
                                        $cells->setAlignment('left');
                                        $cells->setValignment('center');   
                                    });
                                    //set Shipment Value
                                    $sheet->setCellValue('J'.$posPacking, $pi_h['shipment_nm']);
                                    $sheet->mergeCells('J'.$posPacking.':N'.$posPacking);
                                    $sheet->getStyle('J'.$posPacking.':N'.$posPacking)->applyFromArray(getStyleExcel('styleAllBorder'));
                                    $sheet->cells('J'.$posPacking.':N'.$posPacking, function($cells) { 
                                        $cells->setAlignment('left');
                                        $cells->setValignment('center');   
                                    });
                                    //*********set Port of Shipmenｔ Title*********
                                    $posPort    =  $pos+14;
                                    $sheet->getRowDimension($posPort)->setRowHeight(15);
                                    // $sheet->setCellValue('A'.$posPort, 'Port of Shipment');
                                    $sheet->setCellValue('A'.$posPort, $this->getTitle($pi_h['cust_country_div'])['title_Port_of_Shipment']);
                                    $sheet->mergeCells('A'.$posPort.':B'.$posPort);
                                    $sheet->cells('A'.$posPort.':B'.$posPort, function($cells) { 
                                        $cells->setAlignment('center');
                                        $cells->setValignment('center');   
                                    });
                                    $sheet->getStyle('A'.$posPort.':B'.$posPort)->applyFromArray(getStyleExcel('fontBold'));
                                    $sheet->getStyle('A'.$posPort.':B'.$posPort)->applyFromArray(getStyleExcel('styleAllBorder'));
                                    //*********set Port of Shipment value*********
                                    $sheet->setCellValue('C'.$posPort, $pi_h['port_country_nm'].', '.$pi_h['port_city_nm']);
                                    $sheet->mergeCells('C'.$posPort.':F'.$posPort);
                                    $sheet->getStyle('C'.$posPort.':F'.$posPort)->applyFromArray(getStyleExcel('styleAllBorder'));
                                    $sheet->cells('C'.$posPort.':F'.$posPort, function($cells) { 
                                        $cells->setAlignment('left');
                                        $cells->setValignment('center');   
                                    });
                                    //*********set Destination Title*********
                                    // $sheet->setCellValue('G'.$posPort, 'Destination');
                                    $sheet->setCellValue('G'.$posPort, $this->getTitle($pi_h['cust_country_div'])['title_Destination']);
                                    $sheet->mergeCells('G'.$posPort.':I'.$posPort);
                                    $sheet->getStyle('G'.$posPort.':I'.$posPort)->applyFromArray(getStyleExcel('fontBold'));
                                    $sheet->getStyle('G'.$posPort.':I'.$posPort)->applyFromArray(getStyleExcel('styleAllBorder'));
                                    $sheet->cells('G'.$posPort.':I'.$posPort, function($cells) { 
                                        $cells->setAlignment('left');
                                        $cells->setValignment('center');   
                                    });
                                    //*********set Destination Value*********
                                    $dest_country_nm    =   '';
                                    if ($pi_h['cust_country_div'] !== 'JP') {
                                        $dest_country_nm    =   (!empty($pi_h['dest_country_nm'])) ? 
                                                                    $pi_h['dest_country_nm'].', ' :'';
                                    }
                                    $sheet->setCellValue('J'.$posPort, $dest_country_nm.''.$pi_h['dest_city_nm']);
                                    $sheet->mergeCells('J'.$posPort.':N'.$posPort);
                                    $sheet->getStyle('J'.$posPort.':N'.$posPort)->applyFromArray(getStyleExcel('styleAllBorder'));
                                    $sheet->cells('J'.$posPort.':N'.$posPort, function($cells) { 
                                        $cells->setAlignment('left');
                                        $cells->setValignment('center');   
                                    });
                                    //*********set Payment Title*********
                                    $posPayment    =  $pos+15;
                                    // $sheet->setCellValue('A'.$posPayment, 'Payment');
                                    $sheet->setCellValue('A'.$posPayment, $this->getTitle($pi_h['cust_country_div'])['title_Payment']);
                                    $sheet->mergeCells('A'.$posPayment.':B'.$posPayment);
                                    $sheet->cells('A'.$posPayment.':B'.$posPayment, function($cells) { 
                                        $cells->setAlignment('center');
                                        $cells->setValignment('center');   
                                    });
                                    $sheet->getStyle('A'.$posPayment.':B'.$posPayment)->applyFromArray(getStyleExcel('fontBold'));
                                    $sheet->getStyle('A'.$posPayment.':B'.$posPayment)->applyFromArray(getStyleExcel('styleAllBorder'));
                                    //*********set Payment value*********
                                    $sheet->setCellValue('C'.$posPayment, $pi_h['payment_conditions_nm']);
                                    $sheet->mergeCells('C'.$posPayment.':I'.$posPayment);
                                    $sheet->getStyle('C'.$posPayment.':I'.$posPayment)->applyFromArray(getStyleExcel('styleAllBorder'));
                                    $sheet->cells('C'.$posPayment.':I'.$posPayment, function($cells) {
                                        $cells->setAlignment('left');
                                        $cells->setValignment('center');
                                    });
                                    //*********set Trade Terms Title*********
                                    $posPayment    =  $pos+15;
                                    // $sheet->setCellValue('J'.$posPayment, 'Trade Terms');
                                    $sheet->setCellValue('J'.$posPayment, $this->getTitle($pi_h['cust_country_div'])['title_Trade_Terms']);
                                    $sheet->cells('J'.$posPayment, function($cells) { 
                                        $cells->setAlignment('center');
                                        $cells->setValignment('center');   
                                    });
                                    $sheet->getStyle('J'.$posPayment)->applyFromArray(getStyleExcel('fontBold'));
                                    $sheet->getStyle('J'.$posPayment)->applyFromArray(getStyleExcel('styleAllBorder'));
                                    $sheet->getStyle('J'.$posPayment)->getAlignment()->setWrapText(true);
                                    //*********set Trade Terms value*********
                                    //*********set Trade Terms value trade_terms_lib_val_ctl1*********
                                    $sheet->setCellValue('K'.$posPayment, ($pi_h['cust_country_div'] !== 'JP') ? $pi_h['trade_terms_lib_val_ctl1'] : '');
                                    $sheet->mergeCells('K'.$posPayment.':M'.$posPayment);
                                    $sheet->getStyle('K'.$posPayment.':M'.$posPayment)->applyFromArray(getStyleExcel('styleOutlineBorder'));
                                    $sheet->cells('K'.$posPayment, function($cells) {
                                        $cells->setAlignment('center');
                                        $cells->setValignment('center');
                                    });
                                    //*********set Trade Terms value trade_terms_lib_val_ctl2*********
                                    $sheet->setCellValue('N'.$posPayment, ($pi_h['cust_country_div'] !== 'JP') ? $pi_h['trade_terms_lib_val_ctl2'] : '');
                                    $sheet->cells('N'.$posPayment, function($cells) {
                                        $cells->setAlignment('center');
                                        $cells->setValignment('center');
                                        $cells->setBorder('none', 'thin', 'thin', 'none', \PHPExcel_Style_Border::BORDER_THIN);
                                    });
                                    //*********set Code Title*********
                                    $posCode    =  $pos+16;
                                    $posCodeMer =  $pos+17;
                                    // $sheet->setCellValue('A'.$posCode, 'Code');
                                    $sheet->setCellValue('A'.$posCode, $this->getTitle($pi_h['cust_country_div'])['title_Code']);
                                    $sheet->mergeCells('A'.$posCode.':B'.$posCodeMer);
                                    $sheet->cells('A'.$posCode.':B'.$posCodeMer, function($cells) { 
                                        $cells->setAlignment('center');
                                        $cells->setValignment('center');   
                                    });
                                    $sheet->getStyle('A'.$posCode.':B'.$posCodeMer)->applyFromArray(getStyleExcel('fontBold'));
                                    $sheet->getStyle('A'.$posCode.':B'.$posCodeMer)->applyFromArray(getStyleExcel('styleAllBorder'));
                                    //*********set Description Title*********
                                    // $sheet->setCellValue('C'.$posCode, 'Description');
                                    $sheet->setCellValue('C'.$posCode, $this->getTitle($pi_h['cust_country_div'])['title_Description']);
                                    $sheet->mergeCells('C'.$posCode.':H'.$posCodeMer);
                                    $sheet->cells('C'.$posCode.':H'.$posCodeMer, function($cells) { 
                                        $cells->setAlignment('center');
                                        $cells->setValignment('center');   
                                    });
                                    $sheet->getStyle('C'.$posCode.':H'.$posCodeMer)->applyFromArray(getStyleExcel('fontBold'));
                                    $sheet->getStyle('C'.$posCode.':H'.$posCodeMer)->applyFromArray(getStyleExcel('styleAllBorder'));
                                    //*********set Q'ty Title*********
                                    // $sheet->setCellValue('I'.$posCode, "Q'ty");
                                    $sheet->setCellValue('I'.$posCode, $this->getTitle($pi_h['cust_country_div'])['title_Qty']);
                                    $sheet->mergeCells('I'.$posCode.':I'.$posCodeMer);
                                    $sheet->cells('I'.$posCode.':I'.$posCodeMer, function($cells) { 
                                        $cells->setAlignment('center');
                                        $cells->setValignment('center');   
                                    });
                                    $sheet->getStyle('I'.$posCode.':I'.$posCodeMer)->applyFromArray(getStyleExcel('fontBold'));
                                    $sheet->getStyle('I'.$posCode.':I'.$posCodeMer)->applyFromArray(getStyleExcel('styleAllBorder'));
                                    //*********Unit of Measure*********
                                    // $sheet->setCellValue('J'.$posCode, "Unit of Measure");
                                    $sheet->setCellValue('J'.$posCode, $this->getTitle($pi_h['cust_country_div'])['title_Unit_of_Measure']);
                                    $sheet->mergeCells('J'.$posCode.':J'.$posCodeMer);
                                    $sheet->cells('J'.$posCode.':J'.$posCodeMer, function($cells) { 
                                        $cells->setAlignment('center');
                                        $cells->setValignment('center');   
                                    });
                                    $sheet->getStyle('J'.$posCode.':J'.$posCodeMer)->applyFromArray(getStyleExcel('fontBold'));
                                    $sheet->getStyle('J'.$posCode.':J'.$posCodeMer)->applyFromArray(getStyleExcel('styleAllBorder'));
                                    $sheet->getStyle('J'.$posCode.':J'.$posCodeMer)->getAlignment()->setWrapText(true);
                                    //*********set currency div*********
                                    $sheet->setCellValue('K'.$posCode, $pi_h['currency_nm']);
                                    $sheet->mergeCells('K'.$posCode.':N'.$posCode);
                                    $sheet->cells('K'.$posCode.':N'.$posCode, function($cells) { 
                                        $cells->setAlignment('center');
                                        $cells->setValignment('center');   
                                    });
                                    $sheet->getStyle('K'.$posCode.':N'.$posCode)->applyFromArray(getStyleExcel('styleAllBorder'));
                                    //*********set Unit Price*********
                                    // $sheet->setCellValue('K'.$posCodeMer, 'Unit Price');
                                    $sheet->setCellValue('K'.$posCodeMer, $this->getTitle($pi_h['cust_country_div'])['title_Unit_Price']);
                                    $sheet->mergeCells('K'.$posCodeMer.':M'.$posCodeMer);
                                    $sheet->cells('K'.$posCodeMer.':M'.$posCodeMer, function($cells) { 
                                        $cells->setAlignment('center');
                                        $cells->setValignment('center');   
                                    });
                                    $sheet->getStyle('K'.$posCodeMer.':M'.$posCodeMer)->applyFromArray(getStyleExcel('fontBold'));
                                    $sheet->getStyle('K'.$posCodeMer.':M'.$posCodeMer)->applyFromArray(getStyleExcel('styleAllBorder'));
                                    //*********set Amount*********
                                    // $sheet->setCellValue('N'.$posCodeMer, 'Amount');
                                    $sheet->setCellValue('N'.$posCodeMer, $this->getTitle($pi_h['cust_country_div'])['title_Amount']);
                                    $sheet->cells('N'.$posCodeMer, function($cells) { 
                                        $cells->setAlignment('center');
                                        $cells->setValignment('center');   
                                    });
                                    $sheet->getStyle('N'.$posCodeMer)->applyFromArray(getStyleExcel('fontBold'));
                                    $sheet->getStyle('N'.$posCodeMer)->applyFromArray(getStyleExcel('styleAllBorder'));
                                    // **********************************************************************
                                    //      Show Detail
                                    // **********************************************************************
                                    $row      =   '';
                                    $count    =   count($data_pagi[$i]);
                                    $line_row_detail    =   1;
                                    $line_bottom        =   1;
                                    $total_line_detail  =   0;
                                    foreach ($data_pagi[$i] as $key => $value) {
                                        $row        =   $pos+18+$key;
                                        //********* set product code*********
                                        $sheet->setCellValue('A'.$row, $value['product_cd']);
                                        $sheet->mergeCells('A'.$row.':B'.$row);
                                        $sheet->getStyle('A'.$row.':B'.$row)->applyFromArray(getStyleExcel('styleAllBorder'));
                                        $sheet->cells('A'.$row.':B'.$row, function($cells) use ($count, $key){ 
                                            $cells->setAlignment('center');
                                            $cells->setValignment('center');
                                            $cells->setBorder('none', 'thin', 'none', 'thin', \PHPExcel_Style_Border::BORDER_THIN);
                                            if ($key == ($count -1)) {
                                                $cells->setBorder('none', 'thin', 'thin', 'thin', \PHPExcel_Style_Border::BORDER_THIN);
                                            }
                                        });
                                        //********* set product description*********
                                        //set height of each row detail
                                        $line_row_detail    =   numLineOfRowExcel($value['description'], 45);
                                        $sheet->getRowDimension($row)->setRowHeight($line_row_detail*15);
                                        $total_line_detail  =   $total_line_detail + $line_row_detail;
                                        //set data for detail
                                        $sheet->setCellValue('C'.$row, $value['description']);
                                        $sheet->mergeCells('C'.$row.':H'.$row);
                                        $sheet->getStyle('C'.$row.':H'.$row)->applyFromArray(getStyleExcel('styleAllBorder'));
                                        $sheet->cells('C'.$row.':H'.$row, function($cells) use ($count, $key){
                                            $cells->setAlignment('left');
                                            $cells->setValignment('center');
                                            $cells->setBorder('none', 'thin', 'none', 'thin', \PHPExcel_Style_Border::BORDER_THIN);
                                            if ($key == ($count -1)) {
                                                $cells->setBorder('none', 'thin', 'thin', 'none', \PHPExcel_Style_Border::BORDER_THIN);
                                            }
                                        });
                                        $sheet->getStyle('C'.$row.':H'.$row)->getAlignment()->setWrapText(true);
                                        
                                        //********* set Q'ty value*********
                                        $sheet->setCellValue('I'.$row, $value['qty']);
                                        $sheet->cells('I'.$row, function($cells) use ($count, $key){
                                            $cells->setAlignment('right');
                                            $cells->setValignment('center');
                                            $cells->setBorder('none', 'thin', 'none', 'thin', \PHPExcel_Style_Border::BORDER_THIN);
                                            if ($key == ($count -1)) {
                                                $cells->setBorder('none', 'thin', 'thin', 'none', \PHPExcel_Style_Border::BORDER_THIN);
                                            }
                                        });
                                        //********* set Unit of Measure value*********
                                        $sheet->setCellValue('J'.$row, $value['unit_measure_nm']);
                                        $sheet->cells('J'.$row, function($cells) use ($count, $key){
                                            $cells->setAlignment('center');
                                            $cells->setValignment('center');
                                            $cells->setBorder('none', 'thin', 'none', 'thin', \PHPExcel_Style_Border::BORDER_THIN);
                                            if ($key == ($count -1)) {
                                                $cells->setBorder('none', 'thin', 'thin', 'none', \PHPExcel_Style_Border::BORDER_THIN);
                                            }
                                        });
                                        //********* set Unit Price value*********
                                        if($pi_h['currency_nm'] !== 'JPY'){
                                            $sheet->getStyle('K'.$row.':M'.$row)->getNumberFormat()->setFormatCode('##0.00');
                                        }
                                        $sheet->setCellValue('K'.$row, $value['unit_price']);
                                        $sheet->mergeCells('K'.$row.':M'.$row);
                                        $sheet->getStyle('K'.$row.':M'.$row)->applyFromArray(getStyleExcel('styleAllBorder'));
                                        $sheet->cells('K'.$row.':M'.$row, function($cells) use ($count, $key){
                                            $cells->setAlignment('right');
                                            $cells->setValignment('center');
                                            $cells->setBorder('none', 'thin', 'none', 'thin', \PHPExcel_Style_Border::BORDER_THIN);
                                            if ($key == ($count -1)) {
                                                $cells->setBorder('none', 'thin', 'thin', 'none', \PHPExcel_Style_Border::BORDER_THIN);
                                            }
                                        });
                                        //********* set Amount value*********
                                        if($pi_h['currency_nm'] !== 'JPY'){
                                            $sheet->getStyle('N'.$row)->getNumberFormat()->setFormatCode('##0.00');
                                        }
                                        $sheet->setCellValue('N'.$row, $value['detail_amt']);
                                        $sheet->cells('N'.$row, function($cells) use ($count, $key){
                                            $cells->setAlignment('right');
                                            $cells->setValignment('center');
                                            $cells->setBorder('none', 'thin', 'none', 'thin', \PHPExcel_Style_Border::BORDER_THIN);
                                            if ($key == ($count -1)) {
                                                $cells->setBorder('none', 'thin', 'thin', 'none', \PHPExcel_Style_Border::BORDER_THIN);
                                            }
                                        });
                                    }
                                    // **********************************************************************
                                    //      Show Footer 
                                    // **********************************************************************
                                    $total_title                =   '';
                                    $total_qty                  =   '';
                                    $unit_total_nm              =   '';
                                    $trade_terms_lib_val_ctl3   =   '';
                                    $trade_terms_lib_val_ctl4   =   '';
                                    $trade_terms_lib_val_ctl7   =   '';
                                    $trade_terms_lib_val_ctl8   =   '';
                                    $total_detail_amt           =   '';
                                    $trade_terms_freigt_amt     =   '';
                                    $trade_terms_freigt_amt_nm          =   '';
                                    $trade_terms_insurance_amt          =   '';
                                    $trade_terms_insurance_amt_nm       =   '';
                                    $total_amt                          =   '';
                                    $tax_title                          =   '';
                                    $tax_value                          =   '';
                                    // ouput data at page last
                                    if ($i == (count($data_pagi) - 1)) {
                                        $total_title                    =   'Total';
                                        $total_qty                      =   $pi_h['total_qty'];
                                        $unit_total_nm                  =   $pi_h['unit_total_measure_nm'];
                                        $trade_terms_lib_val_ctl3       =   $pi_h['trade_terms_lib_val_ctl3'];
                                        $trade_terms_lib_val_ctl4       =   $pi_h['trade_terms_lib_val_ctl4'];
                                        $trade_terms_lib_val_ctl7       =   $pi_h['trade_terms_lib_val_ctl7'];
                                        $trade_terms_lib_val_ctl8       =   $pi_h['trade_terms_lib_val_ctl8'];
                                        $total_detail_amt               =   $pi_h['total_detail_amt'];
                                        $trade_terms_freigt_amt         =   $pi_h['trade_terms_freigt_amt'];
                                        $trade_terms_freigt_amt_nm      =   $pi_h['trade_terms_freigt_amt_nm'];
                                        $trade_terms_insurance_amt      =   $pi_h['trade_terms_insurance_amt'];
                                        $trade_terms_insurance_amt_nm   =   $pi_h['trade_terms_insurance_amt_nm'];
                                        $total_amt                      =   $pi_h['total_amt'];
                                        if ($pi_h['cust_country_div'] == 'JP') {
                                            $tax_title                  =   '消費税';
                                            $tax_value                  =   $pi_h['total_detail_tax'];

                                            $trade_terms_lib_val_ctl3   =   '合計';
                                            $trade_terms_lib_val_ctl4   =   '';
                                            $trade_terms_lib_val_ctl7   =   '総計';
                                            $trade_terms_lib_val_ctl8   =   '';
                                        }
                                    }
                                    $posFooter      = $pos+18+$count;
                                    //******************************* Show Total *******************************
                                    $row_total_footer   =   1;
                                    if ($i == (count($data_pagi) - 1)) {
                                        $row_total_footer    = $line_detail - ($total_line_detail + 1);
                                        $posFooterMer        = $posFooter + $row_total_footer;
                                        //********* merge cells *********
                                        $sheet->mergeCells('A'.$posFooter.':B'.$posFooterMer);
                                        $sheet->getStyle('A'.$posFooter.':B'.$posFooterMer)->applyFromArray(getStyleExcel('styleAllBorder'));
                                        $sheet->cells('A'.$posFooter.':B'.$posFooterMer, function($cells){ 
                                            $cells->setBorder('thin', 'thin', 'thin', 'thin', \PHPExcel_Style_Border::BORDER_THIN);
                                        });
                                        //********* set total title *********
                                        $sheet->setCellValue('C'.$posFooter, $total_title);
                                        $sheet->mergeCells('C'.$posFooter.':H'.$posFooterMer);
                                        $sheet->getStyle('C'.$posFooter.':H'.$posFooterMer)->applyFromArray(getStyleExcel('styleAllBorder'));
                                        $sheet->cells('C'.$posFooter.':H'.$posFooterMer, function($cells){ 
                                            $cells->setAlignment('left');
                                            $cells->setValignment('top');
                                            $cells->setBorder('thin', 'thin', 'thin', 'thin', \PHPExcel_Style_Border::BORDER_THIN);
                                        });
                                        $sheet->getStyle('C'.$posFooter.':H'.$posFooterMer)->applyFromArray(getStyleExcel('fontBold'));
                                        //********* set total qty *********
                                        $sheet->setCellValue('I'.$posFooter, $total_qty);
                                        $sheet->mergeCells('I'.$posFooter.':I'.$posFooterMer);
                                        $sheet->getStyle('I'.$posFooter.':I'.$posFooterMer)->applyFromArray(getStyleExcel('styleAllBorder'));
                                        $sheet->cells('I'.$posFooter.':I'.$posFooterMer, function($cells){ 
                                            $cells->setAlignment('right');
                                            $cells->setValignment('top');
                                            $cells->setBorder('thin', 'thin', 'thin', 'thin', \PHPExcel_Style_Border::BORDER_THIN);
                                        });
                                        //********* set unit_total_measure_nm qty *********
                                        $sheet->setCellValue('J'.$posFooter, $unit_total_nm);
                                        $sheet->mergeCells('J'.$posFooter.':J'.$posFooterMer);
                                        $sheet->getStyle('J'.$posFooter.':J'.$posFooterMer)->applyFromArray(getStyleExcel('styleAllBorder'));
                                        $sheet->cells('J'.$posFooter.':J'.$posFooterMer, function($cells){ 
                                            $cells->setAlignment('center');
                                            $cells->setValignment('top');
                                            $cells->setBorder('thin', 'thin', 'thin', 'thin', \PHPExcel_Style_Border::BORDER_THIN);
                                        });
                                        //********* merge cells *********
                                        $sheet->mergeCells('K'.$posFooter.':M'.$posFooterMer);
                                        $sheet->getStyle('K'.$posFooter.':M'.$posFooterMer)->applyFromArray(getStyleExcel('styleAllBorder'));
                                        $sheet->cells('K'.$posFooter.':M'.$posFooterMer, function($cells){ 
                                            $cells->setBorder('thin', 'thin', 'thin', 'thin', \PHPExcel_Style_Border::BORDER_THIN);
                                        });
                                        //********* merge cells *********
                                        $sheet->mergeCells('N'.$posFooter.':N'.$posFooterMer);
                                        $sheet->getStyle('N'.$posFooter.':N'.$posFooterMer)->applyFromArray(getStyleExcel('styleAllBorder'));
                                        $sheet->cells('N'.$posFooter.':N'.$posFooterMer, function($cells){ 
                                            $cells->setBorder('thin', 'thin', 'thin', 'thin', \PHPExcel_Style_Border::BORDER_THIN);
                                        });
                                    } else {
                                        $posFooterMer   = $posFooter-1;
                                    }
                                    //******************************* End Show Total *******************************
                                    //********* set value footer *********
                                    $posFooterTotal   = $posFooterMer+1;
                                    $sheet->setCellValue('J'.$posFooterTotal, $trade_terms_lib_val_ctl3);
                                    $sheet->getStyle('J'.$posFooterTotal)->applyFromArray(getStyleExcel('styleAllBorder'));
                                    $sheet->cells('J'.$posFooterTotal, function($cells){ 
                                        $cells->setAlignment('left');
                                        $cells->setValignment('center');
                                        $cells->setBorder('thin', 'none', 'thin', 'thin', \PHPExcel_Style_Border::BORDER_THIN);
                                    });

                                    $sheet->setCellValue('K'.$posFooterTotal, $trade_terms_lib_val_ctl4);
                                    $sheet->mergeCells('K'.$posFooterTotal.':M'.$posFooterTotal);
                                    $sheet->getStyle('K'.$posFooterTotal.':M'.$posFooterTotal)->applyFromArray(getStyleExcel('styleAllBorder'));
                                    $sheet->cells('K'.$posFooterTotal.':M'.$posFooterTotal, function($cells){ 
                                        $cells->setAlignment('center');
                                        $cells->setValignment('center');
                                        $cells->setBorder('thin', 'thin', 'thin', 'none', \PHPExcel_Style_Border::BORDER_THIN);
                                    });
                                    if($pi_h['currency_nm'] !== 'JPY'){
                                        $sheet->getStyle('N'.$posFooterTotal)->getNumberFormat()->setFormatCode('##0.00');
                                    }
                                    $sheet->setCellValue('N'.$posFooterTotal, $total_detail_amt);
                                    $sheet->getStyle('N'.$posFooterTotal)->applyFromArray(getStyleExcel('styleAllBorder'));
                                    $sheet->cells('N'.$posFooterTotal, function($cells){ 
                                        $cells->setAlignment('right');
                                        $cells->setValignment('center');
                                        $cells->setBorder('thin', 'thin', 'thin', 'thin', \PHPExcel_Style_Border::BORDER_THIN);
                                    });
                                    
                                    $posFooterTotal   = $posFooterMer+2;
                                    //********* Time of shipment: *********
                                    // $sheet->setCellValue('A'.$posFooterTotal, 'Time of shipment:');
                                    $sheet->setCellValue('A'.$posFooterTotal, $this->getTitle($pi_h['cust_country_div'])['title_Time_of_shipment'].':');
                                    $sheet->mergeCells('A'.$posFooterTotal.':B'.$posFooterTotal);
                                    $sheet->cells('A'.$posFooterTotal.':B'.$posFooterTotal, function($cells){ 
                                        $cells->setAlignment('left');
                                        $cells->setValignment('center');
                                    });
                                    $sheet->getStyle('A'.$posFooterTotal.':B'.$posFooterTotal)->applyFromArray(getStyleExcel('fontBold'));
                                    //********* Time of shipment value *********
                                    $sheet->setCellValue('C'.$posFooterTotal, $pi_h['time_of_shipment']);
                                    $sheet->mergeCells('C'.$posFooterTotal.':I'.$posFooterTotal);
                                    $sheet->cells('C'.$posFooterTotal.':I'.$posFooterTotal, function($cells){ 
                                        $cells->setAlignment('left');
                                        $cells->setValignment('center');
                                    });
                                    //********* set value Freight *********
                                    $sheet->setCellValue('J'.$posFooterTotal, $trade_terms_freigt_amt_nm);
                                    $sheet->getStyle('J'.$posFooterTotal)->applyFromArray(getStyleExcel('styleAllBorder'));
                                    $sheet->cells('J'.$posFooterTotal, function($cells){ 
                                        $cells->setAlignment('left');
                                        $cells->setValignment('center');
                                        $cells->setBorder('none', 'none', 'thin', 'thin', \PHPExcel_Style_Border::BORDER_THIN);
                                    });

                                    $sheet->mergeCells('K'.$posFooterTotal.':M'.$posFooterTotal);
                                    $sheet->getStyle('K'.$posFooterTotal.':M'.$posFooterTotal)->applyFromArray(getStyleExcel('styleAllBorder'));
                                    $sheet->cells('K'.$posFooterTotal.':M'.$posFooterTotal, function($cells){ 
                                        $cells->setAlignment('center');
                                        $cells->setValignment('center');
                                        $cells->setBorder('none', 'thin', 'thin', 'none', \PHPExcel_Style_Border::BORDER_THIN);
                                    });
                                    if($pi_h['currency_nm'] !== 'JPY'){
                                        $sheet->getStyle('N'.$posFooterTotal)->getNumberFormat()->setFormatCode('##0.00');
                                    }
                                    $sheet->setCellValue('N'.$posFooterTotal, $trade_terms_freigt_amt);
                                    $sheet->getStyle('N'.$posFooterTotal)->applyFromArray(getStyleExcel('styleAllBorder'));
                                    $sheet->cells('N'.$posFooterTotal, function($cells){ 
                                        $cells->setAlignment('right');
                                        $cells->setValignment('center');
                                        $cells->setBorder('none', 'thin', 'thin', 'thin', \PHPExcel_Style_Border::BORDER_THIN);
                                    });
                                    
                                    $posFooterTotal   = $posFooterMer+3;
                                    //********* Bank: *********
                                    $title_bank     =   '';
                                    $bank_value    =   '';
                                    if ($pi_h['cust_country_div'] !== 'JP') {
                                        $title_bank =   'Bank: ';
                                        $bank_value =   $pi_h['bank_nm'];
                                    }
                                    $sheet->setCellValue('A'.$posFooterTotal, $title_bank);
                                    $sheet->mergeCells('A'.$posFooterTotal.':B'.$posFooterTotal);
                                    $sheet->cells('A'.$posFooterTotal.':B'.$posFooterTotal, function($cells){ 
                                        $cells->setAlignment('left');
                                        $cells->setValignment('center');
                                    });
                                    $sheet->getStyle('A'.$posFooterTotal.':B'.$posFooterTotal)->applyFromArray(getStyleExcel('fontBold'));
                                    //*********  Bank 1 value *********
                                    $sheet->setCellValue('C'.$posFooterTotal, $bank_value);
                                    $sheet->mergeCells('C'.$posFooterTotal.':I'.$posFooterTotal);
                                    $sheet->cells('C'.$posFooterTotal.':I'.$posFooterTotal, function($cells){ 
                                        $cells->setAlignment('left');
                                        $cells->setValignment('center');
                                    });
                                    //********* set value Insurance *********
                                    $sheet->setCellValue('J'.$posFooterTotal, $trade_terms_insurance_amt_nm);
                                    $sheet->getStyle('J'.$posFooterTotal)->applyFromArray(getStyleExcel('styleAllBorder'));
                                    $sheet->cells('J'.$posFooterTotal, function($cells){ 
                                        $cells->setAlignment('left');
                                        $cells->setValignment('center');
                                        $cells->setBorder('none', 'none', 'thin', 'thin', \PHPExcel_Style_Border::BORDER_THIN);
                                    });

                                    $sheet->mergeCells('K'.$posFooterTotal.':M'.$posFooterTotal);
                                    $sheet->getStyle('K'.$posFooterTotal.':M'.$posFooterTotal)->applyFromArray(getStyleExcel('styleAllBorder'));
                                    $sheet->cells('K'.$posFooterTotal.':M'.$posFooterTotal, function($cells){ 
                                        $cells->setAlignment('center');
                                        $cells->setValignment('center');
                                        $cells->setBorder('none', 'thin', 'thin', 'none', \PHPExcel_Style_Border::BORDER_THIN);
                                    });
                                    if($pi_h['currency_nm'] !== 'JPY'){
                                        $sheet->getStyle('N'.$posFooterTotal)->getNumberFormat()->setFormatCode('##0.00');
                                    }
                                    $sheet->setCellValue('N'.$posFooterTotal, $trade_terms_insurance_amt);
                                    $sheet->getStyle('N'.$posFooterTotal)->applyFromArray(getStyleExcel('styleAllBorder'));
                                    $sheet->cells('N'.$posFooterTotal, function($cells){ 
                                        $cells->setAlignment('right');
                                        $cells->setValignment('center');
                                        $cells->setBorder('none', 'thin', 'thin', 'thin', \PHPExcel_Style_Border::BORDER_THIN);
                                    });
                                    
                                    $posFooterTotal   = $posFooterMer+4;
                                    //*********  Bank 2 value *********
                                    $sheet->setCellValue('C'.$posFooterTotal, $pi_h['bank_ctl1_nm']);
                                    $sheet->mergeCells('C'.$posFooterTotal.':I'.$posFooterTotal);
                                    $sheet->cells('C'.$posFooterTotal.':I'.$posFooterTotal, function($cells){ 
                                        $cells->setAlignment('left');
                                        $cells->setValignment('center');
                                    });

                                    //********* set value total detail *********
                                    if ($pi_h['cust_country_div'] == 'JP') {
                                        $posFooterTotalTax   =   $posFooterMer + 5;
                                        //********* set total tax *********
                                        $sheet->setCellValue('J'.$posFooterTotal, $tax_title);
                                        $sheet->getStyle('J'.$posFooterTotal)->applyFromArray(getStyleExcel('styleAllBorder'));
                                        $sheet->cells('J'.$posFooterTotal, function($cells){ 
                                            $cells->setAlignment('left');
                                            $cells->setValignment('center');
                                            $cells->setBorder('none', 'none', 'thin', 'thin', \PHPExcel_Style_Border::BORDER_THIN);
                                        });

                                        $sheet->mergeCells('K'.$posFooterTotal.':M'.$posFooterTotal);
                                        $sheet->getStyle('K'.$posFooterTotal.':M'.$posFooterTotal)->applyFromArray(getStyleExcel('styleAllBorder'));
                                        $sheet->cells('K'.$posFooterTotal.':M'.$posFooterTotal, function($cells){ 
                                            $cells->setAlignment('center');
                                            $cells->setValignment('center');
                                            $cells->setBorder('none', 'thin', 'thin', 'none', \PHPExcel_Style_Border::BORDER_THIN);
                                        });
                                        if($pi_h['currency_nm'] !== 'JPY'){
                                            $sheet->getStyle('N'.$posFooterTotal)->getNumberFormat()->setFormatCode('##0.00');
                                        }
                                        $sheet->setCellValue('N'.$posFooterTotal, $tax_value);
                                        $sheet->getStyle('N'.$posFooterTotal)->applyFromArray(getStyleExcel('styleAllBorder'));
                                        $sheet->cells('N'.$posFooterTotal, function($cells){ 
                                            $cells->setAlignment('right');
                                            $cells->setValignment('center');
                                            $cells->setBorder('none', 'thin', 'thin', 'thin', \PHPExcel_Style_Border::BORDER_THIN);
                                        });
                                    } else {
                                        $posFooterTotalTax   = $posFooterMer+4;
                                    }
                                    // if ($pi_h['cust_country_div'] == 'JP') {
                                    //     $trade_terms_lib_val_ctl7   =   '総計';
                                    //     $trade_terms_lib_val_ctl8   =   '';
                                    // }
                                    $sheet->setCellValue('J'.$posFooterTotalTax, $trade_terms_lib_val_ctl7);
                                    $sheet->getStyle('J'.$posFooterTotalTax)->applyFromArray(getStyleExcel('styleAllBorder'));
                                    $sheet->cells('J'.$posFooterTotalTax, function($cells){ 
                                        $cells->setAlignment('left');
                                        $cells->setValignment('center');
                                        $cells->setBorder('none', 'none', 'thin', 'thin', \PHPExcel_Style_Border::BORDER_THIN);
                                    });
                                    $sheet->setCellValue('K'.$posFooterTotalTax, $trade_terms_lib_val_ctl8);
                                    $sheet->mergeCells('K'.$posFooterTotalTax.':M'.$posFooterTotalTax);
                                    $sheet->getStyle('K'.$posFooterTotalTax.':M'.$posFooterTotalTax)->applyFromArray(getStyleExcel('styleAllBorder'));
                                    $sheet->cells('K'.$posFooterTotalTax.':M'.$posFooterTotalTax, function($cells){ 
                                        $cells->setAlignment('center');
                                        $cells->setValignment('center');
                                        $cells->setBorder('none', 'thin', 'thin', 'none', \PHPExcel_Style_Border::BORDER_THIN);
                                    });
                                    if($pi_h['currency_nm'] !== 'JPY'){
                                        $sheet->getStyle('N'.$posFooterTotalTax)->getNumberFormat()->setFormatCode('##0.00');
                                    }
                                    $sheet->setCellValue('N'.$posFooterTotalTax, $total_amt);
                                    $sheet->getStyle('N'.$posFooterTotalTax)->applyFromArray(getStyleExcel('styleAllBorder'));
                                    $sheet->cells('N'.$posFooterTotalTax, function($cells){ 
                                        $cells->setAlignment('right');
                                        $cells->setValignment('center');
                                        $cells->setBorder('none', 'thin', 'thin', 'thin', \PHPExcel_Style_Border::BORDER_THIN);
                                    });

                                    $posFooterTotal   = $posFooterMer+5;
                                    //*********  A/C No.  *********
                                    $title_ac_no    =   '';
                                    $ac_no_value    =   '';
                                    if ($pi_h['cust_country_div'] !== 'JP') {
                                        $title_ac_no =   'A/C No.';
                                        $ac_no_value =   $pi_h['bank_ctl2_nm'] .', '. $pi_h['bank_ctl3_nm'];
                                    }
                                    $sheet->setCellValue('B'.$posFooterTotal, $title_ac_no);
                                    $sheet->getStyle('B'.$posFooterTotal)->applyFromArray(getStyleExcel('fontBold'));
                                    $sheet->cells('B'.$posFooterTotal, function($cells){ 
                                        $cells->setAlignment('left');
                                        $cells->setValignment('center');
                                    });
                                    //*********  A/C No. value  *********
                                    $sheet->setCellValue('C'.$posFooterTotal, $ac_no_value);
                                    $sheet->mergeCells('C'.$posFooterTotal.':E'.$posFooterTotal);
                                    $sheet->cells('C'.$posFooterTotal.':E'.$posFooterTotal, function($cells){ 
                                        $cells->setAlignment('left');
                                        $cells->setValignment('center');
                                    });
                                    //*********  SWIFT:  *********
                                    $title_swift    =   '';
                                    $swift_value    =   '';
                                    if ($pi_h['cust_country_div'] !== 'JP') {
                                        $title_swift =   'SWIFT:';
                                        $swift_value =   $pi_h['bank_ctl4_nm'];
                                    }
                                    $sheet->setCellValue('F'.$posFooterTotal, $title_swift);
                                     $sheet->getStyle('F'.$posFooterTotal)->applyFromArray(getStyleExcel('fontBold'));
                                    $sheet->cells('F'.$posFooterTotal, function($cells){ 
                                        $cells->setAlignment('left');
                                        $cells->setValignment('center');
                                    });
                                    //*********  SWIFT: value  *********
                                    $sheet->setCellValue('G'.$posFooterTotal, $swift_value);
                                    $sheet->mergeCells('G'.$posFooterTotal.':I'.$posFooterTotal);
                                    $sheet->cells('G'.$posFooterTotal.':I'.$posFooterTotal, function($cells){ 
                                        $cells->setAlignment('left');
                                        $cells->setValignment('center');
                                    });

                                    $posFooterTotal   = $posFooterMer+6;
                                    //********* Country of Origin: *********
                                    $title_origin    =   '';
                                    $origin_value    =   '';
                                    if ($pi_h['cust_country_div'] !== 'JP') {
                                        $title_origin =   'Country of Origin: ';
                                        $origin_value =   $pi_h['country_of_origin'];
                                    }
                                    $sheet->setCellValue('A'.$posFooterTotal, $title_origin);
                                    $sheet->mergeCells('A'.$posFooterTotal.':B'.$posFooterTotal);
                                    $sheet->cells('A'.$posFooterTotal.':B'.$posFooterTotal, function($cells){ 
                                        $cells->setAlignment('left');
                                        $cells->setValignment('center');
                                    });
                                    $sheet->getStyle('A'.$posFooterTotal.':B'.$posFooterTotal)->applyFromArray(getStyleExcel('fontBold'));
                                    //*********  Country of Origin value  *********
                                    $sheet->setCellValue('C'.$posFooterTotal, $origin_value);
                                    $sheet->mergeCells('C'.$posFooterTotal.':E'.$posFooterTotal);
                                    $sheet->cells('C'.$posFooterTotal.':E'.$posFooterTotal, function($cells){ 
                                        $cells->setAlignment('left');
                                        $cells->setValignment('center');
                                    });
                                    //********* Manufacturer: *********
                                    $title_manufacturer    =   '';
                                    $manufacturer_value    =   '';
                                    if ($pi_h['cust_country_div'] !== 'JP') {
                                        $title_manufacturer =   'Manufacturer: ';
                                        $manufacturer_value =   $pi_h['manufacture'];
                                    }
                                    $sheet->setCellValue('F'.$posFooterTotal, $title_manufacturer);
                                    $sheet->mergeCells('F'.$posFooterTotal.':H'.$posFooterTotal);
                                    $sheet->cells('F'.$posFooterTotal.':H'.$posFooterTotal, function($cells){ 
                                        $cells->setAlignment('left');
                                        $cells->setValignment('center');
                                    });
                                    $sheet->getStyle('F'.$posFooterTotal)->applyFromArray(getStyleExcel('fontBold'));
                                    //*********  Manufacturer value  *********
                                    $sheet->setCellValue('I'.$posFooterTotal, $manufacturer_value);
                                    $sheet->mergeCells('I'.$posFooterTotal.':N'.$posFooterTotal);
                                    $sheet->cells('I'.$posFooterTotal.':N'.$posFooterTotal, function($cells){ 
                                        $cells->setAlignment('left');
                                        $cells->setValignment('center');
                                    });

                                    $posFooterTotal   = $posFooterMer+7;
                                    //********* Validity : *********
                                    // $sheet->setCellValue('A'.$posFooterTotal, 'Validity: ');
                                    $sheet->setCellValue('A'.$posFooterTotal, $this->getTitle($pi_h['cust_country_div'])['title_Validity'].': ');
                                    $sheet->mergeCells('A'.$posFooterTotal.':B'.$posFooterTotal);
                                    $sheet->cells('A'.$posFooterTotal.':B'.$posFooterTotal, function($cells){ 
                                        $cells->setAlignment('left');
                                        $cells->setValignment('center');
                                    });
                                    $sheet->getStyle('A'.$posFooterTotal.':B'.$posFooterTotal)->applyFromArray(getStyleExcel('fontBold'));
                                    //*********  Validity value *********
                                    $sheet->setCellValue('C'.$posFooterTotal, $pi_h['pi_validity']);
                                    $sheet->mergeCells('C'.$posFooterTotal.':N'.$posFooterTotal);
                                    $sheet->cells('C'.$posFooterTotal.':N'.$posFooterTotal, function($cells){ 
                                        $cells->setAlignment('left');
                                        $cells->setValignment('center');
                                    });

                                    $posFooterTotal   = $posFooterMer+8;
                                    //********* Other conditions : *********
                                    $sheet->setCellValue('A'.$posFooterTotal, 'Other conditions: ');
                                    $sheet->setCellValue('A'.$posFooterTotal, $this->getTitle($pi_h['cust_country_div'])['title_Other_conditions'].': ');
                                    $sheet->mergeCells('A'.$posFooterTotal.':N'.$posFooterTotal);
                                    $sheet->cells('A'.$posFooterTotal.':B'.$posFooterTotal, function($cells){ 
                                        $cells->setAlignment('left');
                                        $cells->setValignment('center');
                                    });
                                    $sheet->getStyle('A'.$posFooterTotal.':B'.$posFooterTotal)->applyFromArray(getStyleExcel('fontBold'));

                                    //********* Other conditions 1 value *********
                                    $posFooterTotal   = $posFooterMer+9;
                                    //set height of each row other_conditions1
                                    // $line_row_footer  =   numLineOfRowExcel($pi_h['other_conditions1'], 91);
                                    $sheet->getRowDimension($posFooterTotal)->setRowHeight(15);

                                    $sheet->setCellValue('A'.$posFooterTotal, $pi_h['other_conditions1']);
                                    $sheet->mergeCells('A'.$posFooterTotal.':J'.$posFooterTotal);
                                    $sheet->cells('A'.$posFooterTotal.':J'.$posFooterTotal, function($cells){ 
                                        $cells->setAlignment('left');
                                        $cells->setValignment('top');
                                    });
                                    $sheet->getStyle('A'.$posFooterTotal.':J'.$posFooterTotal)->getAlignment()->setWrapText(true);

                                    //********* Other conditions 2 value *********
                                    $posFooterTotal   = $posFooterMer+10;
                                    //set height of each row other_conditions2
                                    // $line_row_footer  =   numLineOfRowExcel($pi_h['other_conditions2'], 91);
                                    $sheet->getRowDimension($posFooterTotal)->setRowHeight(15);

                                    $sheet->setCellValue('A'.$posFooterTotal, $pi_h['other_conditions2']);
                                    $sheet->mergeCells('A'.$posFooterTotal.':J'.$posFooterTotal);
                                    $sheet->cells('A'.$posFooterTotal.':J'.$posFooterTotal, function($cells){ 
                                        $cells->setAlignment('left');
                                        $cells->setValignment('top');
                                    });
                                    $sheet->getStyle('A'.$posFooterTotal.':J'.$posFooterTotal)->getAlignment()->setWrapText(true);

                                    //********* Other conditions 3 value *********
                                    $posFooterTotal   = $posFooterMer+11;
                                    //set height of each row other_conditions3
                                    // $line_row_footer  =   numLineOfRowExcel($pi_h['other_conditions3'], 91);
                                    $sheet->getRowDimension($posFooterTotal)->setRowHeight(15);

                                    $sheet->setCellValue('A'.$posFooterTotal, $pi_h['other_conditions3']);
                                    $sheet->mergeCells('A'.$posFooterTotal.':J'.$posFooterTotal);
                                    $sheet->cells('A'.$posFooterTotal.':J'.$posFooterTotal, function($cells){ 
                                        $cells->setAlignment('left');
                                        $cells->setValignment('top');
                                    });
                                    $sheet->getStyle('A'.$posFooterTotal.':J'.$posFooterTotal)->getAlignment()->setWrapText(true);
                                    //********* Company name *********
                                    $company_nm    =   '';
                                    if ($pi_h['cust_country_div'] !== 'JP') {
                                        $company_nm =   $header['company_nm'];
                                    }
                                    $sheet->setCellValue('K'.$posFooterTotal, $company_nm);
                                    $sheet->mergeCells('K'.$posFooterTotal.':N'.$posFooterTotal);
                                    $sheet->cells('K'.$posFooterTotal.':N'.$posFooterTotal, function($cells){ 
                                        $cells->setAlignment('left');
                                        $cells->setValignment('top');
                                    });
                                    //********* Other conditions 4 value *********
                                    $posFooterTotal   = $posFooterMer+12;
                                    //set height of each row other_conditions4
                                    // $line_row_footer  =   numLineOfRowExcel($pi_h['other_conditions4'], 91);
                                    $sheet->getRowDimension($posFooterTotal)->setRowHeight(15);

                                    $sheet->setCellValue('A'.$posFooterTotal, $pi_h['other_conditions4']);
                                    $sheet->mergeCells('A'.$posFooterTotal.':J'.$posFooterTotal);
                                    $sheet->cells('A'.$posFooterTotal.':J'.$posFooterTotal, function($cells){ 
                                        $cells->setAlignment('left');
                                        $cells->setValignment('top');
                                    });
                                    $sheet->getStyle('A'.$posFooterTotal.':J'.$posFooterTotal)->getAlignment()->setWrapText(true);

                                    //********* Other conditions 5 value *********
                                    $posFooterTotal   = $posFooterMer+13;
                                    //set height of each row other_conditions5
                                    // $line_row_footer  =   numLineOfRowExcel($pi_h['other_conditions5'], 91);
                                    $sheet->getRowDimension($posFooterTotal)->setRowHeight(15);

                                    $sheet->setCellValue('A'.$posFooterTotal, $pi_h['other_conditions5']);
                                    $sheet->mergeCells('A'.$posFooterTotal.':J'.$posFooterTotal);
                                    $sheet->cells('A'.$posFooterTotal.':J'.$posFooterTotal, function($cells){ 
                                        $cells->setAlignment('left');
                                        $cells->setValignment('top');
                                    });
                                    $sheet->getStyle('A'.$posFooterTotal.':J'.$posFooterTotal)->getAlignment()->setWrapText(true);

                                    //********* Other conditions 6 value *********
                                    $posFooterTotal   = $posFooterMer+14;
                                    //set height of each row other_conditions6
                                    // $line_row_footer  =   numLineOfRowExcel($pi_h['other_conditions6'], 91);
                                    $sheet->getRowDimension($posFooterTotal)->setRowHeight(15);

                                    $sheet->setCellValue('A'.$posFooterTotal, $pi_h['other_conditions6']);
                                    $sheet->mergeCells('A'.$posFooterTotal.':J'.$posFooterTotal);
                                    $sheet->cells('A'.$posFooterTotal.':J'.$posFooterTotal, function($cells){ 
                                        $cells->setAlignment('left');
                                        $cells->setValignment('top');
                                    });
                                    $sheet->getStyle('A'.$posFooterTotal.':J'.$posFooterTotal)->getAlignment()->setWrapText(true);

                                    //********* Other conditions 7 value *********
                                    $posFooterTotal   = $posFooterMer+15;
                                    //set height of each row other_conditions7
                                    // $line_row_footer  =   numLineOfRowExcel($pi_h['other_conditions7'], 91);
                                    $sheet->getRowDimension($posFooterTotal)->setRowHeight(15);

                                    $sheet->setCellValue('A'.$posFooterTotal, $pi_h['other_conditions7']);
                                    $sheet->mergeCells('A'.$posFooterTotal.':J'.$posFooterTotal);
                                    $sheet->cells('A'.$posFooterTotal.':J'.$posFooterTotal, function($cells){ 
                                        $cells->setAlignment('left');
                                        $cells->setValignment('top');
                                    });
                                    $sheet->getStyle('A'.$posFooterTotal.':J'.$posFooterTotal)->getAlignment()->setWrapText(true);
                                    //********* sign user nm *********
                                    $sheet->setCellValue('K'.$posFooterTotal, $pi_h['sign_user_nm']);
                                    $sheet->mergeCells('K'.$posFooterTotal.':N'.$posFooterTotal);
                                    $sheet->cells('K'.$posFooterTotal.':N'.$posFooterTotal, function($cells){ 
                                        $cells->setAlignment('left');
                                        $cells->setValignment('top');
                                    });
                                    //********* Other conditions 8 value *********
                                    $posFooterTotal   = $posFooterMer+16;
                                    //set height of each row other_conditions8
                                    // $line_row_footer  =   numLineOfRowExcel($pi_h['other_conditions8'], 91);
                                    $sheet->getRowDimension($posFooterTotal)->setRowHeight(15);

                                    $sheet->setCellValue('A'.$posFooterTotal, $pi_h['other_conditions8']);
                                    $sheet->mergeCells('A'.$posFooterTotal.':J'.$posFooterTotal);
                                    $sheet->cells('A'.$posFooterTotal.':J'.$posFooterTotal, function($cells){ 
                                        $cells->setAlignment('left');
                                        $cells->setValignment('top');
                                    });
                                    $sheet->getStyle('A'.$posFooterTotal.':J'.$posFooterTotal)->getAlignment()->setWrapText(true);
                                    //********* user position nm *********
                                    $user_position_nm    =   '';
                                    if ($pi_h['cust_country_div'] !== 'JP') {
                                        $user_position_nm =   $pi_h['user_position_nm'];
                                    }
                                    $sheet->setCellValue('K'.$posFooterTotal, $user_position_nm);
                                    $sheet->mergeCells('K'.$posFooterTotal.':N'.$posFooterTotal);
                                    $sheet->cells('K'.$posFooterTotal.':N'.$posFooterTotal, function($cells){ 
                                        $cells->setAlignment('left');
                                        $cells->setValignment('top');
                                    });
                                    //********* Other conditions 9 value *********
                                    $posFooterTotal   = $posFooterMer+17;
                                    //set height of each row other_conditions9
                                    // $line_row_footer  =   numLineOfRowExcel($pi_h['other_conditions9'], 91);
                                    $sheet->getRowDimension($posFooterTotal)->setRowHeight(15);

                                    $sheet->setCellValue('A'.$posFooterTotal, $pi_h['other_conditions9']);
                                    $sheet->mergeCells('A'.$posFooterTotal.':J'.$posFooterTotal);
                                    $sheet->cells('A'.$posFooterTotal.':J'.$posFooterTotal, function($cells){ 
                                        $cells->setAlignment('left');
                                        $cells->setValignment('top');
                                    });
                                    $sheet->getStyle('A'.$posFooterTotal.':J'.$posFooterTotal)->getAlignment()->setWrapText(true);
                                    //********* user belong nm *********
                                    $user_belong_nm    =   '';
                                    if ($pi_h['cust_country_div'] !== 'JP') {
                                        $user_belong_nm =   $pi_h['user_belong_nm'];
                                    }
                                    $sheet->setCellValue('K'.$posFooterTotal, $user_belong_nm);
                                    $sheet->mergeCells('K'.$posFooterTotal.':N'.$posFooterTotal);
                                    $sheet->cells('K'.$posFooterTotal.':N'.$posFooterTotal, function($cells){ 
                                        $cells->setAlignment('left');
                                        $cells->setValignment('top');
                                    });
                                    //********* Other conditions 10 value *********
                                    $posFooterTotal   = $posFooterMer+18;
                                    //set height of each row other_conditions10
                                    // $line_row_footer  =   numLineOfRowExcel($pi_h['other_conditions10'], 91);
                                    $sheet->getRowDimension($posFooterTotal)->setRowHeight(15);

                                    $sheet->setCellValue('A'.$posFooterTotal, $pi_h['other_conditions10']);
                                    $sheet->mergeCells('A'.$posFooterTotal.':J'.$posFooterTotal);
                                    $sheet->cells('A'.$posFooterTotal.':J'.$posFooterTotal, function($cells){ 
                                        $cells->setAlignment('left');
                                        $cells->setValignment('top');
                                    });
                                    $sheet->getStyle('A'.$posFooterTotal.':J'.$posFooterTotal)->getAlignment()->setWrapText(true);
                                    // Set height for line bottom
                                    if ($i !== (count($data_pagi) -1)) {
                                        $line_bottom    =   $line_detail - $total_line_detail + 1;
                                        $sheet->getRowDimension($posFooterTotal)->setRowHeight($line_bottom*15);
                                    }
                                    // **********************************************************************
                                    //      End Show Footer
                                    // **********************************************************************
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
            $zipFileName    =   'PI_'.date("YmdHis").'.zip';
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
     /**
    * get data header
    * -----------------------------------------------
    * @author      :   ANS806 - 2017/12/05 - create
    * @param       :   array data of header
    * @return      :   return data header of excel
    * @access      :   public
    * @see         :   remark
    */
    protected function getDataHeader($data) {
        try {
            $data_Header = '';
            if (!empty($data)) {
                $data_Header = [
                    '1',
                    [['value' => $data['cust_nm'] , 'leng' => '72']],
                    [['value' => $data['cust_adr1'] , 'leng' => '72']],
                    [['value' => $data['cust_adr2'].','.$data['cust_country_nm'] , 'leng' => '72'], ['value' => $data['mark1'] , 'leng' => '33']],
                    [['value' => $data['mark2'] , 'leng' => '33']],
                    [['value' => $data['mark3'] , 'leng' => '33']],
                    [['value' => $data['consignee_nm'], 'leng' => '72'], ['value' => $data['mark4'] , 'leng' => '33']],
                    [['value' => $data['consignee_adr1'] , 'leng' => '72']],
                    [['value' => $data['consignee_adr2'].','.$data['consignee_country_nm'] , 'leng' => '72']],
                    '1',
                    '1',
                    '1',
                    '1',
                    '1',
                    '1',
                    '1',
                    '1',
                ];
            }
            return $data_Header;
        } catch (\Exception $e) {
            return response()->json(['response' => false]);
        }
    }
     /**
    * get data footer
    * -----------------------------------------------
    * @author      :   ANS806 - 2017/12/05 - create
    * @param       :   array data of footer
    * @return      :   return data footer of excel
    * @access      :   public
    * @see         :   remark
    */
    protected function getDataFooter($data) {
        try {
            $data_Footer = '';
            if (!empty($data)) {
                $data_Footer = [
                    '1',
                    [['value' => $data['time_of_shipment'] , 'leng' => '55']],
                    [['value' => $data['bank_nm'] , 'leng' => '55']],
                    [['value' => $data['bank_ctl1_nm'] , 'leng' => '55']],
                    [['value' => $data['bank_ctl2_nm'].','.$data['bank_ctl2_nm'] , 'leng' => '30']],
                    '1',
                    [['value' => $data['pi_validity'] , 'leng' => '120']],
                    '1',
                    [['value' => $data['other_conditions1'] , 'leng' => '91']],
                    [['value' => $data['other_conditions2'] , 'leng' => '91']],
                    [['value' => $data['other_conditions3'] , 'leng' => '91']],
                    [['value' => $data['other_conditions4'] , 'leng' => '91']],
                    [['value' => $data['other_conditions5'] , 'leng' => '91']],
                    [['value' => $data['other_conditions6'] , 'leng' => '91']],
                    [['value' => $data['other_conditions7'] , 'leng' => '91']],
                    [['value' => $data['other_conditions8'] , 'leng' => '91']],
                    [['value' => $data['other_conditions9'] , 'leng' => '91']],
                    [['value' => $data['other_conditions10'] , 'leng' => '91']],
                ];
            }
            return $data_Footer;
        } catch (\Exception $e) {
            return response()->json(['response' => false]);
        }
    }
     /**
    * get data top
    * -----------------------------------------------
    * @author      :   ANS806 - 2017/12/05 - create
    * @param       :   array data of top
    * @return      :   return data top of excel
    * @access      :   public
    * @see         :   remark
    */
    protected function getDataTop($data) {
        try {
            $data_Top = '';
            if (!empty($data)) {
                $data_Top = [
                    [['value' => $data['company_zip_address'] , 'leng' => '62']],
                    '1',
                    [['value' => $data['company_mail'] , 'leng' => '25'], ['value' => $data['company_url'] , 'leng' => '25']],
                ];
            }
            return $data_Top;
        } catch (\Exception $e) {
            return response()->json(['response' => false]);
        }
    }
     /**
    * get data detail
    * -----------------------------------------------
    * @author      :   ANS806 - 2017/12/05 - create
    * @param       :   data array of deail
    * @return      :   return data detail of excel
    * @access      :   public
    * @see         :   remark
    */
    protected function getDataDetail($data) {
        try {
            $data_Detail = '';
            if (!empty($data)) {
                foreach ($data as $key => $value) {
                    $data_Detail[] =  [['value' => $value['description'] , 'leng' => '45']];
                }
            }
            return $data_Detail;
        } catch (\Exception $e) {
            return response()->json(['response' => false]);
        }
    }

    protected function getTitle($cust_country_div) {
        try {
            $data = [
                'title_head'                =>  ($cust_country_div !== 'JP') ? 'Proforma Invoice' : '御見積書',
                'title_To'                  =>  ($cust_country_div !== 'JP') ? 'To' : '御見積り',
                'title_Consignee'           =>  ($cust_country_div !== 'JP') ? 'Consignee' : '発送先',
                'title_Packing'             =>  ($cust_country_div !== 'JP') ? 'Packing' : '梱包種類',
                'title_Shipment'            =>  ($cust_country_div !== 'JP') ? 'Shipment' : '発送方法',
                'title_Port_of_Shipment'    =>  ($cust_country_div !== 'JP') ? 'Port of Shipment' : '船積港(輸出時）',
                'title_Destination'         =>  ($cust_country_div !== 'JP') ? 'Destination' : '発送先地域',
                'title_Payment'             =>  ($cust_country_div !== 'JP') ? 'Payment' : 'お支払方法',
                'title_Trade_Terms'         =>  ($cust_country_div !== 'JP') ? 'Trade Terms' : '発送条件(輸出時)',
                'title_Code'                =>  ($cust_country_div !== 'JP') ? 'Code' : 'コード',
                'title_Description'         =>  ($cust_country_div !== 'JP') ? 'Description' : '商品名',
                'title_Qty'                 =>  ($cust_country_div !== 'JP') ? 'Q\'ty' : '個数',
                'title_Unit_of_Measure'     =>  ($cust_country_div !== 'JP') ? 'Unit of Measure' : '単位',
                'title_Unit_Price'          =>  ($cust_country_div !== 'JP') ? 'Unit Price' : '単価',
                'title_Amount'              =>  ($cust_country_div !== 'JP') ? 'Amount' : '合計',
                'title_Time_of_shipment'    =>  ($cust_country_div !== 'JP') ? 'Time of shipment' : '納期',
                'title_Validity'            =>  ($cust_country_div !== 'JP') ? 'Validity' : 'お見積り期限',
                'title_Other_conditions'    =>  ($cust_country_div !== 'JP') ? 'Other conditions' : '備考',
            ];
            return $data;
        } catch (\Exception $e) {
            return response()->json(['response' => false]);
        }
    }
    // protected function getDataHeaderExcel($leng = '', $ctl_val = '') {
    //     try {
    //         if (empty($ctl_val)) {
    //             $ctl_val    =   'ctl_val2';
    //         }
    //         if ($leng === 'JP') {
    //             $ctl_val    =   'ctl_val1';
    //         }
    //         $CONSTANT = self::getConstant();
    //         // return $CONSTANT; die;
    //         $header     =   [
    //                         'company_nm'            =>  $CONSTANT[4][$ctl_val],
    //                         'company_zip'           =>  $CONSTANT[7][$ctl_val],
    //                         'company_zip_address'   =>  $CONSTANT[8][$ctl_val],
    //                         'company_address'       =>  $CONSTANT[0][$ctl_val].$CONSTANT[1][$ctl_val],
    //                         'company_tel'           =>  $CONSTANT[5][$ctl_val],
    //                         'company_fax'           =>  $CONSTANT[2][$ctl_val],
    //                         'company_mail'          =>  $CONSTANT[3][$ctl_val],
    //                         'company_url'           =>  $CONSTANT[6][$ctl_val],
    //                     ];
    //         return $header;
    //     } catch(\Exception $e) {
    //         return response()->json(array('response' => false, 'status' => 'ng'));
    //     }
    // }
    // private static function getConstant(){
    //     //execute store procedure
    //     $data = Dao::call_stored_procedure('SPC_GET_CONSTANT',array(),'default');
    //     if(isset($data[0])){
    //         return $data[0];
    //     }

    // }
}
