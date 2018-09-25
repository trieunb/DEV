<?php
/**
 *|--------------------------------------------------------------------------
 *| Invoice Export
 *|--------------------------------------------------------------------------
 *| Package       : Apel
 *| @author       : ANS804 - daonx@ans-asia.com
 *| @created date : 2018/03/01
 *| 
 */
namespace Modules\Export\Http\Controllers;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Session, DB, Dao, Button;
use Excel, PHPExcel_Worksheet_Drawing;
use Modules\Common\Http\Controllers\CommonController as common;

class InvoiceSearchExportController extends Controller {
    public $title           = 'Invoice';
    public $company         = 'Apel';
    protected $totalLine    = '67';
    public $description     = 'Invoice一覧';
    /*
     * Header
     * @var array
     */
    private $header = [
        '区分',
        'Invoice Date',
        'Invoice No',
        '行番号',
        '受注No',
        'PINo',
        '取引先名',
        '国',
        'Code',
        'Item Name',
        'Unit Price',
        'Q\'ty',
        'Cur',
        'Amount',
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
            $sql    = "SPC_015_INVOICE_SEARCH_FND1";//name stored
            $result = Dao::call_stored_procedure($sql, $param,true);
            $data   = isset($result[0]) ? $result[0] : NULL;
            
            //width of columns
            $arrWidthColumns     =   array(
                'A'     =>  15,
                'B'     =>  15,
                'C'     =>  15,
                'D'     =>  10,
                'E'     =>  15,
                'F'     =>  16,
                'G'     =>  40,
                'H'     =>  15,
                'I'     =>  10,
                'J'     =>  40,
                'K'     =>  20,
                'L'     =>  15,
                'M'     =>  10,
                'N'     =>  20
            );

            if (!is_null($data)) {
                $filename    = 'Invoice一覧_'.date("YmdHis");
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
                        $sheet->getStyle('A1:N1')->applyFromArray($styleAllBorder);
                        $sheet->getStyle('A1:N1')->getFont()->getColor()->setRGB('ffffff');
                        $sheet->cells('A1:N1', function($cells) {
                            $cells->setBackground('#248af0');
                            $cells->setAlignment('center');
                            $cells->setValignment('center');
                        });
                        //write data to excel file.
                        foreach ($data as $k1 => $v1) {
                            $row ++;
                            $sheet->getStyle('A'.$row.':N'.$row)->applyFromArray($styleAllBorder);
                            $sheet->getStyle('A'.$row.':N'.$row)->getAlignment()->setWrapText(true);
                            $sheet->row($row, array(
                                $v1['inv_data_div_nm'], 
                                $v1['inv_date'], 
                                $v1['inv_no'], 
                                $v1['inv_detail_no'], 
                                $v1['rcv_no'], 
                                $v1['pi_no'],                                
                                $v1['cust_nm'], 
                                $v1['country_nm'],
                                $v1['product_cd'],
                                $v1['description'],
                                $v1['unit_price'],
                                $v1['qty'],                                
                                $v1['currency_div'],
                                $v1['detail_amt'],
                            ));

                            //even odd row background color
                            if($row % 2 != 0){
                                $sheet->cells('A'.$row.':N'.$row, function($cells) {
                                    $cells->setBackground('#FFF2CC');
                                });
                            }
                            
                            //区分
                            $sheet->cells('A'.$row.':A'.$row, function($cells) {
                                $cells->setAlignment('left');
                                $cells->setValignment('center');
                            });
                            
                            //Invoice Date
                            $sheet->cells('B'.$row.':B'.$row, function($cells) {
                                $cells->setAlignment('center');
                                $cells->setValignment('center');
                            });
                            
                            //Invoice No
                            $sheet->cells('C'.$row.':C'.$row, function($cells) {
                                $cells->setAlignment('left');
                                $cells->setValignment('center');
                            });
                            
                            //行番号
                            $sheet->cells('D'.$row.':D'.$row, function($cells) {
                                $cells->setAlignment('left');
                                $cells->setValignment('center');
                            });
                            
                            //受注No
                            $sheet->cells('E'.$row.':E'.$row, function($cells) {
                                $cells->setAlignment('left');
                                $cells->setValignment('center');
                            });
                            
                            //PINo
                            $sheet->cells('F'.$row.':F'.$row, function($cells) {
                                $cells->setAlignment('left');
                                $cells->setValignment('center');
                            });

                            //取引先名
                            $sheet->cells('G'.$row.':G'.$row, function($cells) {
                                $cells->setAlignment('left');
                                $cells->setValignment('center');
                            });

                            //国
                            $sheet->cells('H'.$row.':H'.$row, function($cells) {
                                $cells->setAlignment('left');
                                $cells->setValignment('center');
                            });

                            //Code
                            $sheet->cells('I'.$row.':I'.$row, function($cells) {
                                $cells->setAlignment('left');
                                $cells->setValignment('center');
                            });

                            //Item Name 
                            $sheet->cells('J'.$row.':J'.$row, function($cells) {
                                $cells->setAlignment('left');
                                $cells->setValignment('center');
                            });

                            //Unit Price
                            $sheet->cells('K'.$row.':K'.$row, function($cells) {
                                $cells->setAlignment('right');
                                $cells->setValignment('center');
                            });

                            //Q'ty
                            $sheet->cells('L'.$row.':L'.$row, function($cells) {
                                $cells->setAlignment('right');
                                $cells->setValignment('center');
                            });

                            //Cur
                            $sheet->cells('M'.$row.':M'.$row, function($cells) {
                                $cells->setAlignment('left');
                                $cells->setValignment('center');
                            });

                            //Amount
                            $sheet->cells('N'.$row.':N'.$row, function($cells) {
                                $cells->setAlignment('right');
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
     * Download Invoice Excel
     * -----------------------------------------------
     * @author      :   ANS804 - 2018/03/02 - create
     * @param       :
     * @return      :
     * @access      :   public
     * @see         :   remark
     */
    public function postInvoiceExport(Request $request) {
        try {
            $header              = common::getDataHeaderExcel();
            $data                = $request->all();
            $data['inv_no']      = json_encode($data['inv_no']);//parse json to string           
            $data['cre_user_cd'] = \GetUserInfo::getInfo('user_cd');
            $data['cre_prg_cd']  = '016-invoice-excel';
            $data['cre_ip']      = \GetUserInfo::getInfo('user_ip');
            $sql                 = 'SPC_016_INVOICE_EXPORT_ACT1';
            $file_excel          = $data['file_excel'];

            unset($data['sql']);
            unset($data['file_excel']);

            $result              = Dao::call_stored_procedure($sql, $data, true);
            
            $response            = true;
            $error               = '';
            $error_cd            = '';
            $zip_array           = '';
            $error_flag          = false;

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
                    $arrWidthColumns     =   [
                        'A'     =>  9,
                        'B'     =>  7,
                        'C'     =>  7,
                        'D'     =>  6,
                        'E'     =>  14,
                        'F'     =>  9,
                        'G'     =>  12,
                        'H'     =>  6,
                        'I'     =>  12,
                        'J'     =>  9,
                        'K'     =>  4.5,
                        'L'     =>  4,
                        'M'     =>  6,
                        'N'     =>  20,
                    ];
                    $marginPage =   [0.4, 0.3, 0.4, 0.4];
                    $zip_array  =   '';
                    $error_flag =   false;
                    $error      =   '';

                    for ($k = 0; $k < count($result[2]); $k++) {
                        // get data by key
                        $key         = ['inv_no' => $result[2][$k]['inv_no']];
                        // get data header by key
                        $inv_h       = getDataByKey($key, $result[2])[0];
                        // get data data_detail by key
                        $inv_d       = getDataByKey($key, $result[3]);
                        // calculate line of top, header, footer 
                        $line_top    = numLinesDataExcel($this->getDataTop($header), true);
                        $line_header = numLinesDataExcel($this->getDataHeader($inv_h), true);
                        $line_footer = numLinesDataExcel($this->getDataFooter($inv_h), true);
                        // calculate line detail
                        $line_detail = $this->totalLine - ($line_top + $line_header + $line_footer);
                        // return $line_top.'-'.$line_header.'-'.$line_detail.'-'.$line_footer;die;
                        $pagi        = pagiDataExcel($this->getDataDetail($inv_d), $line_detail);
                        
                        $page        = $pagi[0];
                        // get data pagination of each page
                        $data_pagi   = dataPageExcel($inv_d,  $page);
                        // file name
                        $file_name   = $file_excel.$key['inv_no'];
                        // **********************************************************************
                        //      Export Excel
                        // **********************************************************************
                        Excel::create($file_name, function($excel) use ($file_name, $inv_h, $header, 
                                                                        $data_pagi, $pagi, $line_detail, $line_top, 
                                                                        $line_header, $line_footer, $arrWidthColumns, $marginPage) {
                            $excel->sheet($file_name, function($sheet) use ($inv_h, $header, $data_pagi, 
                                                                            $pagi, $line_detail, $line_top, 
                                                                            $line_header, $line_footer, $arrWidthColumns, $marginPage) {                                
                                // set font for excel
                                $sheet->getDefaultStyle()->getFont()->setName('Arial')->setSize(10.5);
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
                                    $sheet->setCellValue('H'.$pos, $header['company_zip_address']);
                                    // set height of company_zip_address
                                    $height  =   numLineOfRowExcel($header['company_zip_address'], 62);
                                    $sheet->getRowDimension($pos)->setRowHeight($height*15);
                                    $sheet->getStyle('H'.$pos.':N'.$pos)->getAlignment()->setWrapText(true);

                                    ////////////////////////////////////////////////////////////////////////////////////////
                                    // set Tel
                                    $posTF     =   $pos+1;
                                    $sheet->setCellValue('H'.$posTF, 'Tel: ');
                                    $sheet->getStyle('H'.$posTF)->applyFromArray(getStyleExcel('fontBold'));
                                    $sheet->mergeCells('I'.$posTF.':J'.$posTF);
                                    $sheet->setCellValue('I'.$posTF, $header['company_tel']);
                                    // set Fax
                                    $sheet->setCellValue('K'.$posTF, 'Fax: ');
                                    $sheet->getStyle('K'.$posTF)->applyFromArray(getStyleExcel('fontBold'));
                                    $sheet->mergeCells('L'.$posTF.':N'.$posTF);
                                    $sheet->setCellValue('L'.$posTF, $header['company_fax']);

                                    ////////////////////////////////////////////////////////////////////////////////////////
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
                                    $sheet->setCellValue('L'.$posEU, $header['company_url']);

                                    ////////////////////////////////////////////////////////////////////////////////////////
                                    $posL     =   $pos+3;
                                    $sheet->getRowDimension($posL)->setRowHeight(1);
                                    // **********************************************************************
                                    //      Set Content Header
                                    // **********************************************************************
                                    $posTitle   =   $pos+4;
                                    $sheet->setCellValue('B'.$posTitle, 'Invoice');
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

                                    ////////////////////////////////////////////////////////////////////////////////////////
                                    //set To
                                    $posTo     =  $pos+5;
                                    $posMerg   =  $posTo + 4;
                                    $sheet->setCellValue('A'.$posTo, 'To');
                                    $sheet->mergeCells('A'.$posTo.':B'.$posMerg);
                                    $sheet->cells('A'.$posTo.':B'.$posMerg, function($cells) { 
                                        $cells->setAlignment('left');
                                        $cells->setValignment('top');   
                                    });
                                    $sheet->getStyle('A'.$posTo.':B'.$posMerg)->applyFromArray(getStyleExcel('fontBold'));
                                    $sheet->getStyle('A'.$posTo.':B'.$posMerg)->applyFromArray(getStyleExcel('styleAllBorder'));
                                    //set value cust_nm
                                    $sheet->setCellValue('C'.$posTo, $inv_h['cust_nm']);
                                    $sheet->mergeCells('C'.$posTo.':J'.$posTo);
                                    $sheet->cells('C'.$posTo.':J'.$posTo, function($cells) { 
                                        $cells->setAlignment('left');
                                        $cells->setValignment('top');   
                                    });
                                    //set height of cust_adr1
                                    $height  =   numLineOfRowExcel($inv_h['cust_nm'], 72);
                                    $sheet->getRowDimension($posTo)->setRowHeight($height*15);
                                    $sheet->getStyle('C'.$posTo.':J'.$posTo)->getAlignment()->setWrapText(true);

                                    ////////////////////////////////////////////////////////////////////////////////////////
                                    $posCustAdr1     =  $pos+6;
                                    //set value cust_adr1
                                    $sheet->setCellValue('C'.$posCustAdr1, $inv_h['cust_adr1']);
                                    $sheet->mergeCells('C'.$posCustAdr1.':J'.$posCustAdr1);
                                    $sheet->cells('C'.$posCustAdr1.':J'.$posCustAdr1, function($cells) { 
                                        $cells->setAlignment('left');
                                        $cells->setValignment('top');   
                                    });
                                    //set height of cust_adr1
                                    $height  =   numLineOfRowExcel($inv_h['cust_adr1'], 72);
                                    $sheet->getRowDimension($posCustAdr1)->setRowHeight($height*15);
                                    $sheet->getStyle('C'.$posCustAdr1.':J'.$posCustAdr1)->getAlignment()->setWrapText(true);
                                    //set Date.
                                    $sheet->setCellValue('K'.$posCustAdr1, 'Date');
                                    $sheet->mergeCells('K'.$posCustAdr1.':L'.$posCustAdr1);
                                    $sheet->cells('K'.$posCustAdr1.':L'.$posCustAdr1, function($cells) { 
                                        $cells->setAlignment('center');
                                        $cells->setValignment('center');   
                                    });
                                    $sheet->getStyle('K'.$posCustAdr1.':L'.$posCustAdr1)->applyFromArray(getStyleExcel('fontBold'));
                                    $sheet->getStyle('K'.$posCustAdr1.':L'.$posCustAdr1)->applyFromArray(getStyleExcel('styleAllBorder'));
                                    //set Inv Date Value
                                    $sheet->setCellValue('M'.$posCustAdr1, strftime("%B %d,%Y", strtotime($inv_h['inv_date'])));
                                    // $sheet->setCellValue('M'.$posCustAdr1, $inv_h['inv_date']);
                                    $sheet->mergeCells('M'.$posCustAdr1.':N'.$posCustAdr1);
                                    $sheet->cells('M'.$posCustAdr1.':N'.$posCustAdr1, function($cells) { 
                                        $cells->setAlignment('center');
                                        $cells->setValignment('center');   
                                    });
                                    $sheet->getStyle('M'.$posCustAdr1.':N'.$posCustAdr1)->applyFromArray(getStyleExcel('styleAllBorder'));

                                    ////////////////////////////////////////////////////////////////////////////////////////
                                    $posCustAdr2     =  $pos+7;
                                    //set value cust_adr2
                                    $sheet->setCellValue('C'.$posCustAdr2, $inv_h['cust_adr2']);
                                    $sheet->mergeCells('C'.$posCustAdr2.':J'.$posCustAdr2);
                                    $sheet->cells('C'.$posCustAdr2.':J'.$posCustAdr2, function($cells) { 
                                        $cells->setAlignment('left');
                                        $cells->setValignment('top');   
                                    });
                                    //set height of cust_adr2
                                    $height  =   numLineOfRowExcel($inv_h['cust_adr2'], 72);
                                    $sheet->getRowDimension($posCustAdr2)->setRowHeight($height*15);
                                    $sheet->getStyle('C'.$posCustAdr2.':J'.$posCustAdr2)->getAlignment()->setWrapText(true);

                                    ////////////////////////////////////////////////////////////////////////////////////////
                                    //set Tel
                                    $posCustTel     =  $pos+8;
                                    $sheet->setCellValue('C'.$posCustTel, 'Tel');
                                    $sheet->setCellValue('D'.$posCustTel, $inv_h['cust_tel']);
                                    $sheet->mergeCells('D'.$posCustTel.':E'.$posCustTel);
                                    $sheet->getStyle('C'.$posCustTel)->applyFromArray(getStyleExcel('fontBold'));
                                    //set Fax
                                    $sheet->setCellValue('F'.$posCustTel, 'Fax');
                                    $sheet->setCellValue('G'.$posCustTel, $inv_h['cust_fax']);
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
                                    //set Inv No Value
                                    $sheet->setCellValue('M'.$posTo, $inv_h['inv_no']);
                                    $sheet->mergeCells('M'.$posTo.':N'.$posTo);
                                    $sheet->cells('M'.$posTo.':N'.$posTo, function($cells) { 
                                        $cells->setAlignment('center');
                                        $cells->setValignment('center');   
                                    });
                                    $sheet->getStyle('M'.$posTo.':N'.$posTo)->applyFromArray(getStyleExcel('styleAllBorder'));

                                    ////////////////////////////////////////////////////////////////////////////////////////
                                    //set Consignee
                                    $posCons       =  $pos+10;
                                    $posConsMer    =  $posCons + 4;
                                    $sheet->setCellValue('A'.$posCons, 'Consignee');
                                    $sheet->mergeCells('A'.$posCons.':B'.$posConsMer);
                                    $sheet->cells('A'.$posCons.':B'.$posConsMer, function($cells) { 
                                        $cells->setAlignment('left');
                                        $cells->setValignment('top');   
                                    });
                                    $sheet->getStyle('A'.$posCons.':B'.$posConsMer)->applyFromArray(getStyleExcel('fontBold'));
                                    $sheet->getStyle('A'.$posCons.':B'.$posConsMer)->applyFromArray(getStyleExcel('styleAllBorder'));
                                    //set value consignee_nm
                                    $sheet->setCellValue('C'.$posCons, $inv_h['consignee_nm']);
                                    $sheet->mergeCells('C'.$posCons.':J'.$posCons);
                                    $sheet->cells('C'.$posCons.':J'.$posCons, function($cells) { 
                                        $cells->setAlignment('left');
                                        $cells->setValignment('top');   
                                    });
                                    //set height of consignee_nm
                                    $height  =   numLineOfRowExcel($inv_h['consignee_nm'], 72);
                                    $sheet->getRowDimension($posCons)->setRowHeight($height*15);
                                    $sheet->getStyle('C'.$posCons.':J'.$posCons)->getAlignment()->setWrapText(true);

                                    ////////////////////////////////////////////////////////////////////////////////////////
                                    //set value consignee_adr1
                                    $posConsAdr1     =  $pos+11;
                                    $sheet->setCellValue('C'.$posConsAdr1, $inv_h['consignee_adr1']);
                                    $sheet->mergeCells('C'.$posConsAdr1.':J'.$posConsAdr1);
                                    $sheet->cells('C'.$posConsAdr1.':J'.$posConsAdr1, function($cells) { 
                                        $cells->setAlignment('left');
                                        $cells->setValignment('top');   
                                    });
                                    //set height of consignee_adr1
                                    $height  =   numLineOfRowExcel($inv_h['consignee_adr1'], 72);
                                    $sheet->getRowDimension($posConsAdr1)->setRowHeight($height*15);
                                    $sheet->getStyle('C'.$posConsAdr1.':J'.$posConsAdr1)->getAlignment()->setWrapText(true);

                                    ////////////////////////////////////////////////////////////////////////////////////////
                                    //set value consignee_adr2
                                    $posConsAdr2     =  $pos+12;
                                    $sheet->setCellValue('C'.$posConsAdr2, $inv_h['consignee_adr2']);
                                    $sheet->mergeCells('C'.$posConsAdr2.':J'.$posConsAdr2);
                                    $sheet->cells('C'.$posConsAdr2.':J'.$posConsAdr2, function($cells) { 
                                        $cells->setAlignment('left');
                                        $cells->setValignment('top');   
                                    });
                                    //set height of consignee_adr2
                                    $height  =   numLineOfRowExcel($inv_h['consignee_adr2'], 72);
                                    $sheet->getRowDimension($posConsAdr2)->setRowHeight($height*15);
                                    $sheet->getStyle('C'.$posConsAdr2.':J'.$posConsAdr2)->getAlignment()->setWrapText(true);

                                    ////////////////////////////////////////////////////////////////////////////////////////
                                    //set Tel
                                    $posConsTel     =  $pos+13;
                                    $sheet->setCellValue('C'.$posConsTel, 'Tel');
                                    $sheet->setCellValue('D'.$posConsTel, $inv_h['consignee_tel']);
                                    $sheet->mergeCells('D'.$posConsTel.':E'.$posConsTel);
                                    $sheet->getStyle('C'.$posConsTel)->applyFromArray(getStyleExcel('fontBold'));
                                    //set Fax
                                    $sheet->setCellValue('F'.$posConsTel, 'Fax');
                                    $sheet->setCellValue('G'.$posConsTel, $inv_h['consignee_fax']);
                                    $sheet->mergeCells('G'.$posConsTel.':J'.$posConsTel);
                                    $sheet->getStyle('F'.$posConsTel)->applyFromArray(getStyleExcel('fontBold'));
                                    $sheet->getStyle('C'.$posCons.':J'.$posConsMer)->applyFromArray(getStyleExcel('styleOutlineBorder'));

                                    ////////////////////////////////////////////////////////////////////////////////////////
                                    //set Mark
                                    $postMark1  =   $pos+7;
                                    $sheet->setCellValue('K'.$postMark1, $inv_h['mark1']);
                                    $sheet->mergeCells('K'.$postMark1.':N'.$postMark1);
                                    $sheet->getStyle('K'.$postMark1.':N'.$postMark1)->applyFromArray(getStyleExcel('styleAllBorder'));
                                    $sheet->cells('K'.$postMark1.':N'.$postMark1, function($cells) { 
                                        $cells->setAlignment('center');
                                        $cells->setValignment('center'); 
                                        $cells->setBorder('none', 'thin', 'none', 'thin', \PHPExcel_Style_Border::BORDER_THIN);
                                    });
                                    //set height of mark1
                                    $height  =   numLineOfRowExcel($inv_h['mark1'], 33);
                                    $sheet->getRowDimension($postMark1)->setRowHeight($height*15);
                                    $sheet->getStyle('K'.$postMark1.':N'.$postMark1)->getAlignment()->setWrapText(true);

                                    ////////////////////////////////////////////////////////////////////////////////////////
                                    $postMark2  =   $pos+8;
                                    $sheet->setCellValue('K'.$postMark2, $inv_h['mark2']);
                                    $sheet->mergeCells('K'.$postMark2.':N'.$postMark2);
                                    $sheet->getStyle('K'.$postMark2.':N'.$postMark2)->applyFromArray(getStyleExcel('styleAllBorder'));
                                    $sheet->cells('K'.$postMark2.':N'.$postMark2, function($cells) { 
                                        $cells->setAlignment('center');
                                        $cells->setValignment('center');
                                        $cells->setBorder('none', 'thin', 'none', 'thin', \PHPExcel_Style_Border::BORDER_THIN);
                                    });
                                    //set height of mark1
                                    $height  =   numLineOfRowExcel($inv_h['mark2'], 33);
                                    $sheet->getRowDimension($postMark2)->setRowHeight($height*15);
                                    $sheet->getStyle('K'.$postMark2.':N'.$postMark2)->getAlignment()->setWrapText(true);

                                    ////////////////////////////////////////////////////////////////////////////////////////
                                    $postMark3  =   $pos+9;
                                    $sheet->setCellValue('K'.$postMark3, $inv_h['mark3']);
                                    $sheet->mergeCells('K'.$postMark3.':N'.$postMark3);
                                    $sheet->getStyle('K'.$postMark3.':N'.$postMark3)->applyFromArray(getStyleExcel('styleAllBorder'));
                                    $sheet->cells('K'.$postMark3.':N'.$postMark3, function($cells) { 
                                        $cells->setAlignment('center');
                                        $cells->setValignment('center');
                                        $cells->setBorder('none', 'thin', 'none', 'thin', \PHPExcel_Style_Border::BORDER_THIN);
                                    });
                                    //set height of mark1
                                    $height  =   numLineOfRowExcel($inv_h['mark3'], 33);
                                    $sheet->getRowDimension($postMark3)->setRowHeight($height*15);
                                    $sheet->getStyle('K'.$postMark3.':N'.$postMark3)->getAlignment()->setWrapText(true);

                                    ////////////////////////////////////////////////////////////////////////////////////////
                                    $postMark4  =   $pos+10;
                                    $sheet->setCellValue('K'.$postMark4, $inv_h['mark4']);
                                    $sheet->mergeCells('K'.$postMark4.':N'.$postMark4);
                                    $sheet->getStyle('K'.$postMark4.':N'.$postMark4)->applyFromArray(getStyleExcel('styleAllBorder'));
                                    $sheet->cells('K'.$postMark4.':N'.$postMark4, function($cells) { 
                                        $cells->setAlignment('center');
                                        $cells->setValignment('center');
                                        $cells->setBorder('none', 'thin', 'none', 'thin', \PHPExcel_Style_Border::BORDER_THIN);
                                    });
                                    //set height of mark1
                                    $height  =   numLineOfRowExcel($inv_h['mark4'], 33);
                                    $sheet->getRowDimension($postMark4)->setRowHeight($height*15);
                                    $sheet->getStyle('K'.$postMark4.':N'.$postMark4)->getAlignment()->setWrapText(true);

                                    ////////////////////////////////////////////////////////////////////////////////////////
                                    $postMark     =   $pos+11;
                                    $postMarkMer  =   $pos+14;
                                    $sheet->mergeCells('K'.$postMark.':N'.$postMarkMer);
                                    $sheet->getStyle('K'.$postMark.':N'.$postMarkMer)->applyFromArray(getStyleExcel('styleAllBorder'));
                                    $sheet->cells('K'.$postMark.':N'.$postMarkMer, function($cells) { 
                                        $cells->setAlignment('center');
                                        $cells->setValignment('center');
                                        $cells->setBorder('none', 'thin', 'thin', 'thin', \PHPExcel_Style_Border::BORDER_THIN);
                                    });

                                    ////////////////////////////////////////////////////////////////////////////////////////
                                    //Date of Shipment (on or about)
                                    $posPacking    =  $pos+15;
                                    $sheet->getRowDimension($posPacking)->setRowHeight(25.5);

                                    $sheet->setCellValue('A'.$posPacking, 'Date of Shipment (on or about)');
                                    $sheet->mergeCells('A'.$posPacking.':B'.$posPacking);
                                    $sheet->getStyle('A'.$posPacking.':B'.$posPacking)->getAlignment()->setWrapText(true);
                                    $sheet->cells('A'.$posPacking.':B'.$posPacking, function($cells) { 
                                        $cells->setAlignment('center');
                                        $cells->setValignment('center');   
                                    });
                                    $sheet->getStyle('A'.$posPacking.':B'.$posPacking)->applyFromArray(getStyleExcel('fontBold'));
                                    $sheet->getStyle('A'.$posPacking.':B'.$posPacking)->applyFromArray(getStyleExcel('styleAllBorder'));
                                    //set Packing value
                                    $sheet->setCellValue('C'.$posPacking,strftime("%B %d,%Y", strtotime($inv_h['shipment_date'])));
                                    $sheet->mergeCells('C'.$posPacking.':F'.$posPacking);
                                    $sheet->getStyle('C'.$posPacking.':F'.$posPacking)->applyFromArray(getStyleExcel('styleAllBorder'));
                                    $sheet->cells('C'.$posPacking.':F'.$posPacking, function($cells) { 
                                        $cells->setAlignment('left');
                                        $cells->setValignment('center');   
                                    });
                                    //set Shipment Title
                                    $sheet->setCellValue('G'.$posPacking, 'Shipment');
                                    $sheet->mergeCells('G'.$posPacking.':I'.$posPacking);
                                    $sheet->getStyle('G'.$posPacking.':I'.$posPacking)->applyFromArray(getStyleExcel('fontBold'));
                                    $sheet->getStyle('G'.$posPacking.':I'.$posPacking)->applyFromArray(getStyleExcel('styleAllBorder'));
                                    $sheet->cells('G'.$posPacking.':I'.$posPacking, function($cells) { 
                                        $cells->setAlignment('left');
                                        $cells->setValignment('center');   
                                    });
                                    //set Shipment Value
                                    $sheet->setCellValue('J'.$posPacking, $inv_h['shipment_div']);
                                    $sheet->mergeCells('J'.$posPacking.':N'.$posPacking);
                                    $sheet->getStyle('J'.$posPacking.':N'.$posPacking)->applyFromArray(getStyleExcel('styleAllBorder'));
                                    $sheet->cells('J'.$posPacking.':N'.$posPacking, function($cells) { 
                                        $cells->setAlignment('left');
                                        $cells->setValignment('center');   
                                    });

                                    ////////////////////////////////////////////////////////////////////////////////////////
                                    //*********set Port of Shipmenｔ Title*********
                                    $posPort    =  $pos+16;
                                    $sheet->getRowDimension($posPort)->setRowHeight(22);

                                    $sheet->setCellValue('A'.$posPort, 'Port of Shipment');
                                    $sheet->mergeCells('A'.$posPort.':B'.$posPort);
                                    $sheet->cells('A'.$posPort.':B'.$posPort, function($cells) { 
                                        $cells->setAlignment('center');
                                        $cells->setValignment('center');
                                        $cells->setFont(array(
                                            'size' => 10
                                        ));
                                    });
                                    $sheet->getStyle('A'.$posPort.':B'.$posPort)->applyFromArray(getStyleExcel('fontBold'));
                                    $sheet->getStyle('A'.$posPort.':B'.$posPort)->applyFromArray(getStyleExcel('styleAllBorder'));
                                    //*********set Port of Shipment value*********
                                    $sheet->setCellValue('C'.$posPort, $inv_h['port_nm']);
                                    $sheet->mergeCells('C'.$posPort.':F'.$posPort);
                                    $sheet->getStyle('C'.$posPort.':F'.$posPort)->applyFromArray(getStyleExcel('styleAllBorder'));
                                    $sheet->cells('C'.$posPort.':F'.$posPort, function($cells) { 
                                        $cells->setAlignment('left');
                                        $cells->setValignment('center');
                                    });
                                    //*********set Destination Title*********
                                    $sheet->setCellValue('G'.$posPort, 'Destination');
                                    $sheet->mergeCells('G'.$posPort.':I'.$posPort);
                                    $sheet->getStyle('G'.$posPort.':I'.$posPort)->applyFromArray(getStyleExcel('fontBold'));
                                    $sheet->getStyle('G'.$posPort.':I'.$posPort)->applyFromArray(getStyleExcel('styleAllBorder'));
                                    $sheet->cells('G'.$posPort.':I'.$posPort, function($cells) { 
                                        $cells->setAlignment('left');
                                        $cells->setValignment('center');   
                                    });
                                    //*********set Destination Value*********
                                    $sheet->setCellValue('J'.$posPort, $inv_h['dest_nm']);
                                    $sheet->mergeCells('J'.$posPort.':N'.$posPort);
                                    $sheet->getStyle('J'.$posPort.':N'.$posPort)->applyFromArray(getStyleExcel('styleAllBorder'));
                                    $sheet->cells('J'.$posPort.':N'.$posPort, function($cells) { 
                                        $cells->setAlignment('left');
                                        $cells->setValignment('center');
                                    });

                                    ////////////////////////////////////////////////////////////////////////////////////////
                                    //*********set Payment Title*********
                                    $posPayment    =  $pos+17;
                                    $sheet->getRowDimension($posPayment)->setRowHeight(23.25);

                                    $sheet->setCellValue('A'.$posPayment, 'Payment');
                                    $sheet->mergeCells('A'.$posPayment.':B'.$posPayment);
                                    $sheet->cells('A'.$posPayment.':B'.$posPayment, function($cells) { 
                                        $cells->setAlignment('center');
                                        $cells->setValignment('center');
                                        $cells->setFont(array(
                                            'size' => 10
                                        )); 
                                    });
                                    $sheet->getStyle('A'.$posPayment.':B'.$posPayment)->applyFromArray(getStyleExcel('fontBold'));
                                    $sheet->getStyle('A'.$posPayment.':B'.$posPayment)->applyFromArray(getStyleExcel('styleAllBorder'));
                                    //*********set Payment value*********
                                    $sheet->setCellValue('C'.$posPayment, $inv_h['payment']);
                                    $sheet->mergeCells('C'.$posPayment.':I'.$posPayment);
                                    $sheet->getStyle('C'.$posPayment.':I'.$posPayment)->applyFromArray(getStyleExcel('styleAllBorder'));
                                    $sheet->cells('C'.$posPayment.':I'.$posPayment, function($cells) {
                                        $cells->setAlignment('left');
                                        $cells->setValignment('center');
                                    });

                                    ////////////////////////////////////////////////////////////////////////////////////////
                                    //*********set Trade Terms Title*********
                                    $posPayment    =  $pos+17;
                                    $sheet->setCellValue('J'.$posPayment, 'Trade Terms');
                                    $sheet->cells('J'.$posPayment, function($cells) { 
                                        $cells->setAlignment('center');
                                        $cells->setValignment('center');
                                        $cells->setFont(array(
                                            'size' => 9
                                        ));
                                    });
                                    $sheet->getStyle('J'.$posPayment)->applyFromArray(getStyleExcel('fontBold'));
                                    $sheet->getStyle('J'.$posPayment)->applyFromArray(getStyleExcel('styleAllBorder'));
                                    $sheet->getStyle('J'.$posPayment)->getAlignment()->setWrapText(true);
                                    //*********set Trade Terms value*********
                                    //*********set Trade Terms value termsname_nm*********
                                    $sheet->setCellValue('K'.$posPayment, $inv_h['termsname_nm']);
                                    $sheet->mergeCells('K'.$posPayment.':M'.$posPayment);
                                    $sheet->getStyle('K'.$posPayment.':M'.$posPayment)->applyFromArray(getStyleExcel('styleOutlineBorder'));
                                    $sheet->cells('K'.$posPayment, function($cells) {
                                        $cells->setAlignment('center');
                                        $cells->setValignment('center');
                                    });
                                    //*********set Trade Terms value DSP_country_nm*********
                                    $sheet->setCellValue('N'.$posPayment, $inv_h['country_nm']);
                                    $sheet->cells('N'.$posPayment, function($cells) {
                                        $cells->setAlignment('center');
                                        $cells->setValignment('center');
                                        $cells->setBorder('none', 'thin', 'thin', 'none', \PHPExcel_Style_Border::BORDER_THIN);
                                    });

                                    ////////////////////////////////////////////////////////////////////////////////////////
                                    //*********set Code Title*********
                                    $posCode    =  $pos+18;
                                    $sheet->getRowDimension($posCode)->setRowHeight(17.25);

                                    $posCodeMer =  $pos+19;
                                    $sheet->getRowDimension($posCodeMer)->setRowHeight(17.25);

                                    $sheet->setCellValue('A'.$posCode, 'Code');
                                    $sheet->mergeCells('A'.$posCode.':B'.$posCodeMer);
                                    $sheet->cells('A'.$posCode.':B'.$posCodeMer, function($cells) { 
                                        $cells->setAlignment('center');
                                        $cells->setValignment('center');   
                                    });
                                    $sheet->getStyle('A'.$posCode.':B'.$posCodeMer)->applyFromArray(getStyleExcel('fontBold'));
                                    $sheet->getStyle('A'.$posCode.':B'.$posCodeMer)->applyFromArray(getStyleExcel('styleAllBorder'));
                                    //*********set Description Title*********
                                    $sheet->setCellValue('C'.$posCode, 'Description');
                                    $sheet->mergeCells('C'.$posCode.':H'.$posCodeMer);
                                    $sheet->cells('C'.$posCode.':H'.$posCodeMer, function($cells) { 
                                        $cells->setAlignment('center');
                                        $cells->setValignment('center');   
                                    });
                                    $sheet->getStyle('C'.$posCode.':H'.$posCodeMer)->applyFromArray(getStyleExcel('fontBold'));
                                    $sheet->getStyle('C'.$posCode.':H'.$posCodeMer)->applyFromArray(getStyleExcel('styleAllBorder'));
                                    //*********set Q'ty Title*********
                                    $sheet->setCellValue('I'.$posCode, "Q'ty");
                                    $sheet->mergeCells('I'.$posCode.':I'.$posCodeMer);
                                    $sheet->cells('I'.$posCode.':I'.$posCodeMer, function($cells) { 
                                        $cells->setAlignment('center');
                                        $cells->setValignment('center');
                                        $cells->setFont(array(
                                            'size' => 10
                                        ));
                                    });
                                    $sheet->getStyle('I'.$posCode.':I'.$posCodeMer)->applyFromArray(getStyleExcel('fontBold'));
                                    $sheet->getStyle('I'.$posCode.':I'.$posCodeMer)->applyFromArray(getStyleExcel('styleAllBorder'));
                                    //*********Unit of Measure*********
                                    $sheet->setCellValue('J'.$posCode, "Unit of Measure");
                                    $sheet->mergeCells('J'.$posCode.':J'.$posCodeMer);
                                    $sheet->cells('J'.$posCode.':J'.$posCodeMer, function($cells) { 
                                        $cells->setAlignment('center');
                                        $cells->setValignment('center');
                                        $cells->setValignment('center');
                                        $cells->setFont(array(
                                            'size' => 9
                                        ));
                                    });
                                    $sheet->getStyle('J'.$posCode.':J'.$posCodeMer)->applyFromArray(getStyleExcel('fontBold'));
                                    $sheet->getStyle('J'.$posCode.':J'.$posCodeMer)->applyFromArray(getStyleExcel('styleAllBorder'));
                                    $sheet->getStyle('J'.$posCode.':J'.$posCodeMer)->getAlignment()->setWrapText(true);
                                    //*********set currency div*********
                                    $sheet->setCellValue('K'.$posCode, $inv_h['currency_div']);
                                    $sheet->mergeCells('K'.$posCode.':N'.$posCode);
                                    $sheet->cells('K'.$posCode.':N'.$posCode, function($cells) { 
                                        $cells->setAlignment('center');
                                        $cells->setValignment('center');   
                                    });
                                    $sheet->getStyle('K'.$posCode.':N'.$posCode)->applyFromArray(getStyleExcel('styleAllBorder'));
                                    //*********set Unit Price*********
                                    $sheet->setCellValue('K'.$posCodeMer, 'Unit Price');
                                    $sheet->mergeCells('K'.$posCodeMer.':M'.$posCodeMer);
                                    $sheet->cells('K'.$posCodeMer.':M'.$posCodeMer, function($cells) { 
                                        $cells->setAlignment('center');
                                        $cells->setValignment('center');   
                                    });
                                    $sheet->getStyle('K'.$posCodeMer.':M'.$posCodeMer)->applyFromArray(getStyleExcel('fontBold'));
                                    $sheet->getStyle('K'.$posCodeMer.':M'.$posCodeMer)->applyFromArray(getStyleExcel('styleAllBorder'));
                                    //*********set Amount*********
                                    $sheet->setCellValue('N'.$posCodeMer, 'Amount');
                                    $sheet->cells('N'.$posCodeMer, function($cells) { 
                                        $cells->setAlignment('center');
                                        $cells->setValignment('center');   
                                    });
                                    $sheet->getStyle('N'.$posCodeMer)->applyFromArray(getStyleExcel('fontBold'));
                                    $sheet->getStyle('N'.$posCodeMer)->applyFromArray(getStyleExcel('styleAllBorder'));
                                    // **********************************************************************
                                    //      Show Detail
                                    // **********************************************************************
                                    $row               =   '';
                                    $count             =   count($data_pagi[$i]);
                                    $line_row_detail   =   1;
                                    $line_bottom       =   1;
                                    $total_line_detail =   0;
                                    foreach ($data_pagi[$i] as $key => $value) {
                                        $row        =   $pos+20+$key;
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
                                        $line_row_detail    =   numLineOfRowExcel($value['description'], 50);
                                        // $height_row_detail = 14.25;
                                        // if ($line_row_detail == 1) {
                                        //     $height_row_detail = 28.5;
                                        // }
                                        $sheet->getRowDimension($row)->setRowHeight($line_row_detail*14.25);
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
                                        $sheet->setCellValue('J'.$row, $value['unit_of_measure']);
                                        $sheet->cells('J'.$row, function($cells) use ($count, $key){
                                            $cells->setAlignment('center');
                                            $cells->setValignment('center');
                                            $cells->setBorder('none', 'thin', 'none', 'thin', \PHPExcel_Style_Border::BORDER_THIN);
                                            if ($key == ($count -1)) {
                                                $cells->setBorder('none', 'thin', 'thin', 'none', \PHPExcel_Style_Border::BORDER_THIN);
                                            }
                                        });
                                        //********* set Unit Price value*********
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
                                        $sheet->setCellValue('N'.$row, $value['amount']);
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
                                    $total_title             =   '';
                                    $qty                     =   '';
                                    $unit_of_measure         =   '';
                                    $sub_total_title_div     =   '';
                                    $sub_total_title_country =   '';
                                    $total_detail_amt        =   '';
                                    $freight_amount          =   '';
                                    $freight_title           =   '';
                                    $insurance_title         =   '';
                                    $insurance_amount        =   '';
                                    $total_tax_title         =   '';
                                    $total_detail_tax        =   '';
                                    $total_title_div         =   '';
                                    $total_title_country     =   '';
                                    $total_amt               =   '';
                                    $tax_value                          =   '';
                                    // ouput data at page last
                                    if ($i == (count($data_pagi) - 1)) {
                                        $total_title             =   'Total';
                                        $qty                     =   $inv_h['qty'];
                                        $unit_of_measure         =   $inv_h['unit_of_measure'];
                                        $sub_total_title_div     =   $inv_h['sub_total_title_div'];
                                        $sub_total_title_country =   $inv_h['sub_total_title_country'];
                                        $total_detail_amt        =   $inv_h['total_detail_amt'];
                                        $freight_title           =   $inv_h['freight_title'];
                                        $freight_amount          =   $inv_h['freight_amount'];
                                        $insurance_title         =   $inv_h['insurance_title'];
                                        $insurance_amount        =   $inv_h['insurance_amount'];
                                        $total_tax_title         =   $inv_h['total_tax_title'];
                                        $total_detail_tax        =   $inv_h['total_detail_tax'];
                                        $total_title_div         =   $inv_h['total_title_div'];
                                        $total_title_country     =   $inv_h['total_title_country'];
                                        $total_amt               =   $inv_h['total_amt'];
                                    }

                                    ////////////////////////////////////////////////////////////////////////////////////////
                                    $posFooter      = $pos+20+$count;
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
                                        $sheet->setCellValue('I'.$posFooter, $qty);
                                        $sheet->mergeCells('I'.$posFooter.':I'.$posFooterMer);
                                        $sheet->getStyle('I'.$posFooter.':I'.$posFooterMer)->applyFromArray(getStyleExcel('styleAllBorder'));
                                        $sheet->cells('I'.$posFooter.':I'.$posFooterMer, function($cells){ 
                                            $cells->setAlignment('right');
                                            $cells->setValignment('top');
                                            $cells->setBorder('thin', 'thin', 'thin', 'thin', \PHPExcel_Style_Border::BORDER_THIN);
                                        });
                                        //********* set unit_of_measure_nm *********
                                        $sheet->setCellValue('J'.$posFooter, $unit_of_measure);
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
                                    ///////////////////////////////////////////////////////////////////
                                    $posFooterTotal   = $posFooterMer+1;

                                    $sheet->setCellValue('J'.$posFooterTotal, $sub_total_title_div);
                                    $sheet->getStyle('J'.$posFooterTotal)->applyFromArray(getStyleExcel('styleAllBorder'));
                                    $sheet->cells('J'.$posFooterTotal, function($cells){ 
                                        $cells->setAlignment('left');
                                        $cells->setValignment('center');
                                        $cells->setBorder('thin', 'none', 'thin', 'thin', \PHPExcel_Style_Border::BORDER_THIN);
                                    });
                                    $sheet->setCellValue('K'.$posFooterTotal, $sub_total_title_country);
                                    $sheet->mergeCells('K'.$posFooterTotal.':M'.$posFooterTotal);
                                    $sheet->getStyle('K'.$posFooterTotal.':M'.$posFooterTotal)->applyFromArray(getStyleExcel('styleAllBorder'));
                                    $sheet->cells('K'.$posFooterTotal.':M'.$posFooterTotal, function($cells){ 
                                        $cells->setAlignment('center');
                                        $cells->setValignment('center');
                                        $cells->setBorder('thin', 'thin', 'thin', 'none', \PHPExcel_Style_Border::BORDER_THIN);
                                    });
                                    $sheet->setCellValue('N'.$posFooterTotal, $total_detail_amt);
                                    $sheet->getStyle('N'.$posFooterTotal)->applyFromArray(getStyleExcel('styleAllBorder'));
                                    $sheet->cells('N'.$posFooterTotal, function($cells){ 
                                        $cells->setAlignment('right');
                                        $cells->setValignment('center');
                                        $cells->setBorder('thin', 'thin', 'thin', 'thin', \PHPExcel_Style_Border::BORDER_THIN);
                                    });
                                    
                                    ///////////////////////////////////////////////////////////////////
                                    $posFooterTotal   = $posFooterMer+2;
                                    //********* Proforma invoice No: *********
                                    $sheet->setCellValue('A'.$posFooterTotal, 'Proforma invoice No. :');
                                    $sheet->mergeCells('A'.$posFooterTotal.':C'.$posFooterTotal);
                                    $sheet->getStyle('A'.$posFooter.':C'.$posFooterMer)->applyFromArray(getStyleExcel('styleAllBorder'));
                                    $sheet->cells('A'.$posFooterTotal.':C'.$posFooterTotal, function($cells){ 
                                        $cells->setAlignment('left');
                                        $cells->setValignment('center');
                                        $cells->setBorder('thin', 'none', 'none', 'thin', \PHPExcel_Style_Border::BORDER_THIN);
                                    });
                                    //*********  total title div  *********
                                    $sheet->setCellValue('J'.$posFooterTotal, $total_title_div);
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
                                    //*********  total amt  *********
                                    $sheet->setCellValue('N'.$posFooterTotal, $total_amt);
                                    $sheet->getStyle('N'.$posFooterTotal)->applyFromArray(getStyleExcel('styleAllBorder'));
                                    $sheet->cells('N'.$posFooterTotal, function($cells){ 
                                        $cells->setAlignment('right');
                                        $cells->setValignment('center');
                                        $cells->setBorder('none', 'thin', 'thin', 'thin', \PHPExcel_Style_Border::BORDER_THIN);
                                    });
                                    $sheet->setCellValue('D'.$posFooterTotal, $inv_h['pi_no']);
                                    $sheet->mergeCells('D'.$posFooterTotal.':H'.$posFooterTotal);
                                    $sheet->getStyle('D'.$posFooterTotal.':H'.$posFooterTotal)->applyFromArray(getStyleExcel('styleAllBorder'));
                                    $sheet->cells('D'.$posFooterTotal.':H'.$posFooterTotal, function($cells){ 
                                        $cells->setAlignment('left');
                                        $cells->setValignment('center');
                                        $cells->setBorder('thin', 'thin', 'none', 'none', \PHPExcel_Style_Border::BORDER_THIN);
                                    });

                                    //********* set value Freight *********
                                    $sheet->setCellValue('J'.$posFooterTotal, $freight_title);
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
                                    $sheet->setCellValue('N'.$posFooterTotal, $freight_amount);
                                    $sheet->getStyle('N'.$posFooterTotal)->applyFromArray(getStyleExcel('styleAllBorder'));
                                    $sheet->cells('N'.$posFooterTotal, function($cells){ 
                                        $cells->setAlignment('right');
                                        $cells->setValignment('center');
                                        $cells->setBorder('none', 'thin', 'thin', 'thin', \PHPExcel_Style_Border::BORDER_THIN);
                                    });
                                    
                                    ///////////////////////////////////////////////////////////////////
                                    $posFooterTotal   = $posFooterMer+3;

                                    $sheet->mergeCells('A'.$posFooterTotal.':H'.$posFooterTotal);
                                    $sheet->getStyle('A'.$posFooterTotal.':H'.$posFooterTotal)->applyFromArray(getStyleExcel('styleAllBorder'));
                                    $sheet->cells('A'.$posFooterTotal.':H'.$posFooterTotal, function($cells){ 
                                        $cells->setAlignment('left');
                                        $cells->setValignment('center');
                                        $cells->setBorder('none', 'thin', 'thin', 'thin', \PHPExcel_Style_Border::BORDER_THIN);
                                    });
                                    //********* set value Insurance *********
                                    $sheet->setCellValue('J'.$posFooterTotal, $insurance_title);
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
                                    $sheet->setCellValue('N'.$posFooterTotal, $insurance_amount);
                                    $sheet->getStyle('N'.$posFooterTotal)->applyFromArray(getStyleExcel('styleAllBorder'));
                                    $sheet->cells('N'.$posFooterTotal, function($cells){ 
                                        $cells->setAlignment('right');
                                        $cells->setValignment('center');
                                        $cells->setBorder('none', 'thin', 'thin', 'thin', \PHPExcel_Style_Border::BORDER_THIN);
                                    });
                                    
                                    ///////////////////////////////////////////////////////////////////
                                    $posFooterTotal   = $posFooterMer+4;

                                    //*********  L/C No. :  *********
                                    $sheet->setCellValue('A'.$posFooterTotal, 'L/C No.                   :');
                                    $sheet->mergeCells('A'.$posFooterTotal.':C'.$posFooterTotal);
                                    $sheet->getStyle('A'.$posFooter.':C'.$posFooterMer)->applyFromArray(getStyleExcel('styleAllBorder'));
                                    $sheet->cells('A'.$posFooterTotal.':C'.$posFooterTotal, function($cells){ 
                                        $cells->setAlignment('left');
                                        $cells->setValignment('center');
                                        $cells->setBorder('thin', 'none', 'thin', 'thin', \PHPExcel_Style_Border::BORDER_THIN);
                                    });
                                    $sheet->setCellValue('D'.$posFooterTotal, $inv_h['lc_number']);
                                    $sheet->mergeCells('D'.$posFooterTotal.':H'.$posFooterTotal);
                                    $sheet->getStyle('D'.$posFooterTotal.':H'.$posFooterTotal)->applyFromArray(getStyleExcel('styleAllBorder'));
                                    $sheet->cells('D'.$posFooterTotal.':H'.$posFooterTotal, function($cells){ 
                                        $cells->setAlignment('left');
                                        $cells->setValignment('center');
                                        $cells->setBorder('thin', 'thin', 'thin', 'none', \PHPExcel_Style_Border::BORDER_THIN);
                                    });
                                    if ($total_tax_title != NULL) {
                                        //*********  total tax title  *********
                                        $sheet->setCellValue('J'.$posFooterTotal, $total_tax_title);
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
                                        //*********  total detail tax  *********
                                        $sheet->setCellValue('N'.$posFooterTotal, $total_detail_tax);
                                        $sheet->getStyle('N'.$posFooterTotal)->applyFromArray(getStyleExcel('styleAllBorder'));
                                        $sheet->cells('N'.$posFooterTotal, function($cells){ 
                                            $cells->setAlignment('right');
                                            $cells->setValignment('center');
                                            $cells->setBorder('none', 'thin', 'thin', 'thin', \PHPExcel_Style_Border::BORDER_THIN);
                                        });

                                        $posTitleDiv = $posFooterTotal + 1;
                                        //*********  total title div  *********
                                        $sheet->setCellValue('J'.$posTitleDiv, $total_title_div);
                                        $sheet->getStyle('J'.$posTitleDiv)->applyFromArray(getStyleExcel('styleAllBorder'));
                                        $sheet->cells('J'.$posTitleDiv, function($cells){ 
                                            $cells->setAlignment('left');
                                            $cells->setValignment('center');
                                            $cells->setBorder('none', 'none', 'thin', 'thin', \PHPExcel_Style_Border::BORDER_THIN);
                                        });
                                        $sheet->setCellValue('K'.$posTitleDiv, $total_title_country);
                                        $sheet->mergeCells('K'.$posTitleDiv.':M'.$posTitleDiv);
                                        $sheet->getStyle('K'.$posTitleDiv.':M'.$posTitleDiv)->applyFromArray(getStyleExcel('styleAllBorder'));
                                        $sheet->cells('K'.$posTitleDiv.':M'.$posTitleDiv, function($cells){ 
                                            $cells->setAlignment('center');
                                            $cells->setValignment('center');
                                            $cells->setBorder('none', 'thin', 'thin', 'none', \PHPExcel_Style_Border::BORDER_THIN);
                                        });
                                        //*********  total amt  *********
                                        $sheet->setCellValue('N'.$posTitleDiv, $total_amt);
                                        $sheet->getStyle('N'.$posTitleDiv)->applyFromArray(getStyleExcel('styleAllBorder'));
                                        $sheet->cells('N'.$posTitleDiv, function($cells){ 
                                            $cells->setAlignment('right');
                                            $cells->setValignment('center');
                                            $cells->setBorder('none', 'thin', 'thin', 'thin', \PHPExcel_Style_Border::BORDER_THIN);
                                        });
                                    } else {
                                        //*********  total title div  *********
                                        $sheet->setCellValue('J'.$posFooterTotal, $total_title_div);
                                        $sheet->getStyle('J'.$posFooterTotal)->applyFromArray(getStyleExcel('styleAllBorder'));
                                        $sheet->cells('J'.$posFooterTotal, function($cells){ 
                                            $cells->setAlignment('left');
                                            $cells->setValignment('center');
                                            $cells->setBorder('none', 'none', 'thin', 'thin', \PHPExcel_Style_Border::BORDER_THIN);
                                        });
                                        $sheet->setCellValue('K'.$posFooterTotal, $total_title_country);
                                        $sheet->mergeCells('K'.$posFooterTotal.':M'.$posFooterTotal);
                                        $sheet->getStyle('K'.$posFooterTotal.':M'.$posFooterTotal)->applyFromArray(getStyleExcel('styleAllBorder'));
                                        $sheet->cells('K'.$posFooterTotal.':M'.$posFooterTotal, function($cells){ 
                                            $cells->setAlignment('center');
                                            $cells->setValignment('center');
                                            $cells->setBorder('none', 'thin', 'thin', 'none', \PHPExcel_Style_Border::BORDER_THIN);
                                        });
                                        //*********  total amt  *********
                                        $sheet->setCellValue('N'.$posFooterTotal, $total_amt);
                                        $sheet->getStyle('N'.$posFooterTotal)->applyFromArray(getStyleExcel('styleAllBorder'));
                                        $sheet->cells('N'.$posFooterTotal, function($cells){ 
                                            $cells->setAlignment('right');
                                            $cells->setValignment('center');
                                            $cells->setBorder('none', 'thin', 'thin', 'thin', \PHPExcel_Style_Border::BORDER_THIN);
                                        });
                                    }
                                    ///////////////////////////////////////////////////////////////////
                                    $posFooterTotal   = $posFooterMer+5;

                                    //*********  P/O No. :  *********
                                    $sheet->setCellValue('A'.$posFooterTotal, 'P/O No.                   :');
                                    $sheet->mergeCells('A'.$posFooterTotal.':C'.$posFooterTotal);
                                    $sheet->getStyle('A'.$posFooter.':C'.$posFooterMer)->applyFromArray(getStyleExcel('styleAllBorder'));
                                    $sheet->cells('A'.$posFooterTotal.':C'.$posFooterTotal, function($cells){ 
                                        $cells->setAlignment('left');
                                        $cells->setValignment('center');
                                        $cells->setBorder('thin', 'none', 'thin', 'thin', \PHPExcel_Style_Border::BORDER_THIN);
                                    });
                                    $sheet->setCellValue('D'.$posFooterTotal, $inv_h['po_number']);
                                    $sheet->mergeCells('D'.$posFooterTotal.':H'.$posFooterTotal);
                                    $sheet->getStyle('D'.$posFooterTotal.':H'.$posFooterTotal)->applyFromArray(getStyleExcel('styleAllBorder'));
                                    $sheet->cells('D'.$posFooterTotal.':H'.$posFooterTotal, function($cells){ 
                                        $cells->setAlignment('left');
                                        $cells->setValignment('center');
                                        $cells->setBorder('thin', 'thin', 'thin', 'none', \PHPExcel_Style_Border::BORDER_THIN);
                                    });
                                    //*********  total title div  *********
                                    // $sheet->setCellValue('J'.$posFooterTotal, $total_title_div);
                                    // $sheet->getStyle('J'.$posFooterTotal)->applyFromArray(getStyleExcel('styleAllBorder'));
                                    // $sheet->cells('J'.$posFooterTotal, function($cells){ 
                                    //     $cells->setAlignment('left');
                                    //     $cells->setValignment('center');
                                    //     $cells->setBorder('none', 'none', 'thin', 'thin', \PHPExcel_Style_Border::BORDER_THIN);
                                    // });
                                    // $sheet->setCellValue('K'.$posFooterTotal, $total_title_country);
                                    // $sheet->mergeCells('K'.$posFooterTotal.':M'.$posFooterTotal);
                                    // $sheet->getStyle('K'.$posFooterTotal.':M'.$posFooterTotal)->applyFromArray(getStyleExcel('styleAllBorder'));
                                    // $sheet->cells('K'.$posFooterTotal.':M'.$posFooterTotal, function($cells){ 
                                    //     $cells->setAlignment('center');
                                    //     $cells->setValignment('center');
                                    //     $cells->setBorder('none', 'thin', 'thin', 'none', \PHPExcel_Style_Border::BORDER_THIN);
                                    // });
                                    // //*********  total amt  *********
                                    // $sheet->setCellValue('N'.$posFooterTotal, $total_amt);
                                    // $sheet->getStyle('N'.$posFooterTotal)->applyFromArray(getStyleExcel('styleAllBorder'));
                                    // $sheet->cells('N'.$posFooterTotal, function($cells){ 
                                    //     $cells->setAlignment('right');
                                    //     $cells->setValignment('center');
                                    //     $cells->setBorder('none', 'thin', 'thin', 'thin', \PHPExcel_Style_Border::BORDER_THIN);
                                    // });
                                    ///////////////////////////////////////////////////////////////////
                                    $posFooterTotal   = $posFooterMer+6;

                                    //*********  Packing :  *********
                                    $sheet->setCellValue('A'.$posFooterTotal, 'Packing : ');
                                    $sheet->getStyle('A'.$posFooter.':C'.$posFooterMer)->applyFromArray(getStyleExcel('styleAllBorder'));
                                    $sheet->cells('A'.$posFooterTotal.':C'.$posFooterTotal, function($cells){ 
                                        $cells->setAlignment('left');
                                        $cells->setValignment('center');
                                        $cells->setBorder('thin', 'none', 'thin', 'thin', \PHPExcel_Style_Border::BORDER_THIN);
                                    });
                                    $sheet->setCellValue('B'.$posFooterTotal, $inv_h['packing']);
                                    $sheet->mergeCells('B'.$posFooterTotal.':D'.$posFooterTotal);
                                    $sheet->getStyle('B'.$posFooterTotal.':H'.$posFooterTotal)->applyFromArray(getStyleExcel('styleAllBorder'));
                                    $sheet->cells('B'.$posFooterTotal.':H'.$posFooterTotal, function($cells){ 
                                        $cells->setAlignment('left');
                                        $cells->setValignment('center');
                                        $cells->setBorder('thin', 'thin', 'thin', 'none', \PHPExcel_Style_Border::BORDER_THIN);
                                    });
                                    //*********  Total Gross Weight :  *********
                                    $sheet->setCellValue('E'.$posFooterTotal, 'Total Gross Weight :');
                                    $sheet->getStyle('E'.$posFooterTotal)->applyFromArray(getStyleExcel('styleAllBorder'));
                                    $sheet->cells('E'.$posFooterTotal, function($cells){ 
                                        $cells->setAlignment('left');
                                        $cells->setValignment('center');
                                        $cells->setBorder('thin', 'none', 'thin', 'none', \PHPExcel_Style_Border::BORDER_THIN);
                                    });
                                    $sheet->setCellValue('G'.$posFooterTotal, $inv_h['total_gross_weight']);
                                    $sheet->getStyle('G'.$posFooterTotal)->applyFromArray(getStyleExcel('styleAllBorder'));
                                    $sheet->cells('G'.$posFooterTotal.':G'.$posFooterTotal, function($cells){ 
                                        $cells->setAlignment('center');
                                        $cells->setValignment('center');
                                        $cells->setBorder('thin', 'none', 'thin', 'none', \PHPExcel_Style_Border::BORDER_THIN);
                                    });
                                    $sheet->setCellValue('H'.$posFooterTotal, $inv_h['unit_total_gross_weight_div']);
                                    $sheet->getStyle('H'.$posFooterTotal)->applyFromArray(getStyleExcel('styleAllBorder'));
                                    $sheet->cells('H'.$posFooterTotal.':H'.$posFooterTotal, function($cells){ 
                                        $cells->setAlignment('center');
                                        $cells->setValignment('center');
                                        $cells->setBorder('thin', 'thin', 'thin', 'none', \PHPExcel_Style_Border::BORDER_THIN);
                                    });

                                    ///////////////////////////////////////////////////////////////////
                                    $posFooterTotal   = $posFooterMer+7;

                                    //*********  Country of Origin  *********
                                    $sheet->setCellValue('A'.$posFooterTotal, 'Country of Origin :');
                                    $sheet->mergeCells('A'.$posFooterTotal.':B'.$posFooterTotal);
                                    $sheet->cells('A'.$posFooterTotal.':B'.$posFooterTotal, function($cells){ 
                                        $cells->setAlignment('left');
                                        $cells->setValignment('center');
                                        $cells->setBorder('thin', 'none', 'thin', 'thin', \PHPExcel_Style_Border::BORDER_THIN);
                                    });
                                    $sheet->setCellValue('C'.$posFooterTotal, $inv_h['country_of_origin']);
                                    $sheet->mergeCells('C'.$posFooterTotal.':D'.$posFooterTotal);
                                    $sheet->cells('C'.$posFooterTotal.':D'.$posFooterTotal, function($cells){ 
                                        $cells->setAlignment('left');
                                        $cells->setValignment('center');
                                        $cells->setBorder('thin', 'thin', 'thin', 'none', \PHPExcel_Style_Border::BORDER_THIN);
                                    });
                                    //*********  Manufacturer  *********
                                    $sheet->setCellValue('E'.$posFooterTotal, 'Manufacturer');
                                    $sheet->mergeCells('E'.$posFooterTotal.':E'.$posFooterTotal);
                                    $sheet->getStyle('E'.$posFooterTotal.':E'.$posFooterTotal)->applyFromArray(getStyleExcel('styleAllBorder'));
                                    $sheet->cells('E'.$posFooterTotal.':E'.$posFooterTotal, function($cells){ 
                                        $cells->setAlignment('left');
                                        $cells->setValignment('center');
                                        $cells->setBorder('thin', 'none', 'thin', 'thin', \PHPExcel_Style_Border::BORDER_THIN);
                                    });
                                    $sheet->setCellValue('F'.$posFooterTotal, $inv_h['manufacture']);
                                    $sheet->mergeCells('F'.$posFooterTotal.':H'.$posFooterTotal);
                                    $sheet->getStyle('F'.$posFooterTotal.':H'.$posFooterTotal)->applyFromArray(getStyleExcel('styleAllBorder'));
                                    $sheet->cells('F'.$posFooterTotal.':H'.$posFooterTotal, function($cells){ 
                                        $cells->setAlignment('center');
                                        $cells->setValignment('center');
                                        $cells->setBorder('thin', 'thin', 'thin', 'none', \PHPExcel_Style_Border::BORDER_THIN);
                                    });

                                    ///////////////////////////////////////////////////////////////////
                                    $posFooterTotal   = $posFooterMer+8;

                                    //*********  sign_nm  *********
                                    $sheet->setCellValue('K'.$posFooterTotal, $inv_h['sign_nm']);
                                    $sheet->mergeCells('K'.$posFooterTotal.':N'.$posFooterTotal);
                                    $sheet->cells('K'.$posFooterTotal.':N'.$posFooterTotal, function($cells){ 
                                        $cells->setAlignment('left');
                                        $cells->setValignment('top');
                                    });

                                    ///////////////////////////////////////////////////////////////////
                                    $posFooterTotal   = $posFooterMer+9;
                                    //*********  position_div  *********
                                    $sheet->setCellValue('K'.$posFooterTotal, $inv_h['position_div']);
                                    $sheet->mergeCells('K'.$posFooterTotal.':N'.$posFooterTotal);
                                    $sheet->cells('K'.$posFooterTotal.':N'.$posFooterTotal, function($cells){ 
                                        $cells->setAlignment('left');
                                        $cells->setValignment('top');
                                    });
                                    ///////////////////////////////////////////////////////////////////
                                    $posFooterTotal   = $posFooterMer+10;

                                    //*********  belong_div  *********
                                    $sheet->setCellValue('K'.$posFooterTotal, $inv_h['belong_div']);
                                    $sheet->mergeCells('K'.$posFooterTotal.':N'.$posFooterTotal);
                                    $sheet->cells('K'.$posFooterTotal.':N'.$posFooterTotal, function($cells){ 
                                        $cells->setAlignment('left');
                                        $cells->setValignment('top');
                                    });

                                    $posFooterTotal   = $posFooterMer+11;
                                    // Set height for line bottom
                                    if ($i !== (count($data_pagi) -1)) {
                                        $line_bottom    =   $line_detail - $total_line_detail + 1;
                                        $sheet->getRowDimension($posFooterTotal)->setRowHeight($line_bottom*40);
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

            /*********************************************************************
            *  2. Xuất file xlsx or zip
            *  2. Export file xlsx or zip
            *********************************************************************/
            // 
            $zipFileName    =   $file_excel.date("YmdHis").'.zip';

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
    /**
     * get data top
     * -----------------------------------------------
     * @author      :   ANS804 - 2018/03/07 - create
     * @param       :
     * @return      :
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
     * get data header
     * -----------------------------------------------
     * @author      :   ANS804 - 2018/03/07 - create
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
                    [['value' => $data['cust_adr2'] , 'leng' => '72'], ['value' => $data['mark1'] , 'leng' => '33']],
                    [['value' => $data['mark2'] , 'leng' => '33']],
                    [['value' => $data['mark3'] , 'leng' => '33']],
                    [['value' => $data['consignee_nm'], 'leng' => '72'], ['value' => $data['mark4'] , 'leng' => '33']],
                    [['value' => $data['consignee_adr1'] , 'leng' => '72']],
                    [['value' => $data['consignee_adr2'] , 'leng' => '72']],
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
     * @author      :   ANS804 - 2018/03/07 - create
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
                    [['value' => $data['pi_no'] , 'leng' => '12']],
                    [['value' => $data['lc_number'] , 'leng' => '35']],
                    [['value' => $data['packing'] , 'leng' => '32']],
                    [['value' => $data['country_of_origin'], 'leng' => '30']],
                    [['value' => $data['manufacture'], 'leng' => '30']],
                    1,
                    1,
                    1,
                    [['value' => $data['sign_nm'], 'leng' => '30']],
                    [['value' => $data['position_div'], 'leng' => '30']],
                    [['value' => $data['belong_div'], 'leng' => '30']],
                    1
                ];
            }
            return $data_Footer;
        } catch (\Exception $e) {
            return response()->json(['response' => false]);
        }
    }
    /**
     * get data detail
     * -----------------------------------------------
     * @author      :   ANS804 - 2018/03/07 - create
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
                    $data_Detail[] =  [['value' => $value['description'] , 'leng' => '50']];
                }
            }
            return $data_Detail;
        } catch (\Exception $e) {
            return response()->json(['response' => false]);
        }
    }
}
