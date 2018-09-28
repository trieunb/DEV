<?php
/**
*|--------------------------------------------------------------------------
*| Packing List Report Export
*|--------------------------------------------------------------------------
*| Package       : Apel
*| @author       : ANS810 - dungnn@ans-asia.com
*| @created date : 2018/03/06
*| 
*/
namespace Modules\Export\Http\Controllers;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Session, DB, Dao, Button;
use Excel, PHPExcel_Worksheet_Drawing;
use Modules\Common\Http\Controllers\CommonController as common;
class PackingListExportController extends Controller
{
    protected $file_excel   = 'PackingList_';
    public $title           = 'Packing List Report';
    public $company         = 'Apel';
    public $description     = 'PL一覧';
    protected $totalLine    = '54';

    /*
     * Header
     * @var array
     */
    private $header = [
        'InvoiceNo',
        'Invoice日付',
        '取引先名',
        '住所１',
        '住所２',
        '都市名',
        '〒',
        '国名',
        'Tel',
        'Fax',
        'Consignee',
        'C住所1',
        'C住所2',
        'C都市名',
        'C〒',
        'C国名',
        'CTel',
        'CFax',
        'Date of shipment',
        'Shipped per',
        '船積地都市名',
        '船積地国名',
        '仕向地都市名',
        '仕向地国名',
        'Shipping Mark1',
        'Shipping Mark2',
        'Shipping Mark3',
        'Shipping Mark4',
        '署名者',
        '総カートン数',
        'カートンNo',
        'カートン行No',
        '製品コード',
        '製品名',
        '社外用備考',
        '数量',
        '数量単位',
        'Net重量',
        'Gross重量',
        '重量単位',
        '容積',
        '容積単位'
    ];
   
    /*
    * postPackingListReportOutput
    * -----------------------------------------------
    * @author      :   ANS810 - 2018/03/06 - create
    * @param       :
    * @return      :
    * @access      :   public
    * @see         :   remark
    */
    public function postPackingListReportOutput(Request $request) {
        try {
            $param          = \Input::all();

            $sql            = "SPC_017_PACKING_LIST_SEARCH_FND1";    //name stored

            $result         = Dao::call_stored_procedure($sql, $param,true);

            $result[0]      = isset($result[0]) ? $result[0] : NULL;

            //width of columns
            $arrWidthColumns     =   array(
                'A'     =>  18,
                'B'     =>  15,
                'C'     =>  20,
                'D'     =>  30,
                'E'     =>  15,
                'F'     =>  15,
                'G'     =>  15,
                'H'     =>  15,
                'I'     =>  20,
                'J'     =>  20,
                'K'     =>  15,
                'L'     =>  15,
                'M'     =>  15,
                'N'     =>  10,
                'O'     =>  10,
                'P'     =>  15,
                'Q'     =>  15,
                'R'     =>  10,
                'S'     =>  20,
                'T'     =>  12,
                'U'     =>  20,
                'V'     =>  12,
                'W'     =>  20,
                'X'     =>  20,
                'Y'     =>  20,
                'Z'     =>  20,
                'AA'     =>  20,
                'AB'     =>  20,
                'AC'     =>  30,
                'AD'     =>  18,
                'AE'     =>  18,
                'AF'     =>  18,
                'AG'     =>  15,
                'AH'     =>  25,
                'AI'     =>  12,
                'AJ'     =>  12,
                'AK'     =>  12,
                'AL'     =>  12,
                'AM'     =>  12,
                'AN'     =>  12,
                'AO'     =>  12,
                'AP'     =>  12,
            );
            if ( !is_null($result[0])) {
                $filename    = 'PL一覧_'.date("YmdHis");
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
                        $sheet->getStyle('A1:AP1')->applyFromArray($styleAllBorder);
                        $sheet->getStyle('A1:AP1')->getFont()->getColor()->setRGB('ffffff');
                        $sheet->cells('A1:AP1', function($cells) {
                            $cells->setBackground('#248af0');
                            $cells->setAlignment('center');
                            $cells->setValignment('center');
                        });
                        //write data to excel file.
                        foreach ($result[0] as $k1 => $v1) {
                            $row ++;
                            $sheet->getStyle('A'.$row.':AP'.$row)->applyFromArray($styleAllBorder);
                            $sheet->getStyle('A'.$row.':AP'.$row)->getAlignment()->setWrapText(true);
                            $sheet->row($row, array(
                                $v1['inv_no'], 
                                $v1['inv_date'], 
                                $v1['client_nm'],                               
                                $v1['client_adr1'],                               
                                $v1['client_adr2'],                               
                                $v1['client_city_div'],         
                                $v1['client_zip'],                               
                                $v1['client_country_div'],                               
                                $v1['client_tel'],                               
                                $v1['client_fax'],                               
                                $v1['consignee_nm'],                               
                                $v1['consignee_adr1'],                               
                                $v1['consignee_adr2'],                               
                                $v1['consignee_city_div'],                               
                                $v1['consignee_zip'],                               
                                $v1['consignee_country_div'],                               
                                $v1['consignee_tel'],                               
                                $v1['consignee_fax'],                               
                                $v1['shipment_date'],                               
                                $v1['shipment_nm'],                               
                                $v1['port_city_div'],                               
                                $v1['port_country_div'],                               
                                $v1['dest_city_div'],                               
                                $v1['dest_country_div'],                               
                                $v1['mark1'],                               
                                $v1['mark2'],                               
                                $v1['mark3'],                               
                                $v1['mark4'],                               
                                $v1['user_nm_e'],                               
                                $v1['total_carton_number'],                               
                                $v1['carton_number'],                               
                                $v1['inv_carton_no'],                               
                                $v1['product_cd'],                               
                                $v1['description'],                               
                                $v1['outside_remark'],                               
                                $v1['qty'],                               
                                $v1['unit_q_div'],                               
                                $v1['net_weight'],
                                $v1['gross_weight'],
                                $v1['unit_net_weight_div'],
                                $v1['measure'],                               
                                $v1['unit_measure_div'],                               
                            ));
                            //even odd row background color
                            if($row % 2 != 0){
                                $sheet->cells('A'.$row.':AP'.$row, function($cells) {
                                    $cells->setBackground('#FFF2CC');
                                });
                            }

                            $sheet->cells('A'.$row.':AP'.$row, function($cells) {
                                $cells->setValignment('center');
                            });

                            $sheet->cells('B'.$row.':B'.$row, function($cells) {
                                $cells->setAlignment('center');
                            });                            

                            $sheet->cells('S'.$row.':S'.$row, function($cells) {
                                $cells->setAlignment('center');
                            });

                            $sheet->cells('AD'.$row.':AD'.$row, function($cells) {
                                $cells->setAlignment('right');
                            });

                            $sheet->cells('AG'.$row.':AG'.$row, function($cells) {
                                $cells->setAlignment('right');
                            });

                            $sheet->cells('AJ'.$row.':AJ'.$row, function($cells) {
                                $cells->setAlignment('right');
                            });

                            $sheet->cells('AL'.$row.':AL'.$row, function($cells) {
                                $cells->setAlignment('right');
                            });

                            $sheet->cells('AM'.$row.':AM'.$row, function($cells) {
                                $cells->setAlignment('right');
                            });

                            $sheet->cells('AO'.$row.':AO'.$row, function($cells) {
                                $cells->setAlignment('right');
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
    * @author      :   ANS810 - 2018/03/09 - create
    * @param       :
    * @return      :
    * @access      :   public
    * @see         :   remark
    */
    public function postPackingListReportExport(Request $request) {
        try {
            //data master layout
            $header                 =  common::getDataHeaderExcel();
            $data                   =  $request->all();
            $data['update_list']    =  json_encode($data['update_list']);//parse json to string
            $data['cre_user_cd']    = \GetUserInfo::getInfo('user_cd');
            $data['cre_prg_cd']     = '018_packing-list-excel';
            $data['cre_ip']         = \GetUserInfo::getInfo('user_ip');

            $sql        =   "SPC_018_PACKING_LIST_SEARCH_ACT1";
            $result     =   Dao::call_stored_procedure($sql, $data, true);
            // return $result;
            $response   =   true;
            $error      =   '';
            $error_cd   =   '';
            $zip_array  =   '';
            $error_flag =   false;

            if (isset($result[1][0]) && !empty($result[1][0]['error_cd'])) {
                return response(array(
                    'response'  =>  true, 
                    'filename'  =>  '',
                    'error_cd'  =>  'E005'
                ));
            }

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
                        'A'     =>  5,
                        'B'     =>  5,
                        'C'     =>  8,
                        'D'     =>  4,
                        'E'     =>  5,
                        'F'     =>  9,
                        'G'     =>  5,
                        'H'     =>  6,
                        'I'     =>  5,
                        'J'     =>  5,
                        'K'     =>  7,
                        'L'     =>  7,
                        'M'     =>  4,
                        'N'     =>  7,
                        'O'     =>  4,
                        'P'     =>  7,
                        'Q'     =>  8,
                    ];
                    $marginPage         =   [0.4, 0.3, 0.4, 0.4];
                    $zip_array      =   '';
                    $error_flag     =   false;
                    $error          =   '';
                    for ($k = 0; $k < count($result[2]); $k++) {
                        // get data by key
                        $key    = ['inv_no' => $result[2][$k]['inv_no']];
                        // 
                        $file_name  =   $this->file_excel.$key['inv_no'];
                        // get data array buy_h by key
                        $inv_no_h  = getDataByKey($key, $result[2])[0];

                        // get data array buy_d by key
                        $inv_no_d  = getDataByKey($key, $result[3]);

                        // data footer
                        $inv_no_footer = getDataByKey($key, $result[4])[0];

                        $inv_no_more_d       =   $this->getDataMorePage($inv_no_d);

                        $inv_no_more_d       =   count($inv_no_more_d) > 0 ? $inv_no_more_d : array(1);

                        // calculate line of top, header, footer 
                        $line_top       = numLinesDataExcel($this->getDataTop($header), true);
                        $line_header    = numLinesDataExcel($this->getDataHeader($inv_no_h), true);
                        $line_footer    = numLinesDataExcel($this->getDataFooter($inv_no_d), true);
                        $line_detail    = $this->totalLine - ($line_header + $line_footer);
                        $pagi           = pagiDataExcel($this->getDataDetail($inv_no_more_d), $line_detail);
                        $page           = $pagi[0];
                        // get data pagination first page
                        $data_pagi_first      = dataPageExcel($inv_no_d,18);

                        // get data pagination remaining page
                        $data_pagi      = dataPageExcel($inv_no_more_d,27);
                        $countPage      = count($inv_no_more_d) > 1 ? 1 + count($data_pagi) : 1;

                        // Caculator page for two page

                        if(count($inv_no_more_d) == 1 && count($data_pagi_first[0]) > 14 && count($data_pagi_first[0]) <= 18){
                            $countPage = $countPage + 1;
                        }

                        // Caculator page more page
                        if(count($data_pagi_first[0]) > 14){
                            for ($i = 1; $i <= count($data_pagi); $i++) {
                                if(count($data_pagi[$i-1]) >= 24 && count($data_pagi[$i-1]) <= 26){
                                    $countPage = $countPage + 1;
                                }
                            }
                        }

                        // **********************************************************************
                        //      Export Excel
                        // **********************************************************************
                        Excel::create($file_name, function($excel) use ($countPage,$inv_no_footer,$data_pagi_first,$file_name, $inv_no_h, $inv_no_d, $header, 
                                                                        $data_pagi, $pagi, $line_detail, $line_top, 
                                                                        $line_header, $line_footer, $arrWidthColumns, $marginPage) {
                            $excel->sheet('Sheet 1', function($sheet) use ($countPage,$inv_no_footer,$data_pagi_first,$inv_no_h, $inv_no_d, $header, $data_pagi, 
                                                                            $pagi, $line_detail, $line_top, 
                                                                            $line_header, $line_footer, $arrWidthColumns, $marginPage) {                                
                                // set font for excel
                                $sheet->getDefaultStyle()->getFont()->setName('Arial')->setSize(9);
                                // set width for colum excel
                                $sheet->setWidth($arrWidthColumns);
                                // set Gridlines
                                $sheet->setShowGridlines(false);
                                $pos        =   0;
                                $page_size  =   37;
                                // pagination
                                $pos = 1;
                                //create and format footer
                                //create text body
                                /*
                                 * font style
                                */
                                $sheet->setStyle(array(
                                                'font' => array(
                                                    'name'      =>  'Arial',
                                                    'size'      =>  11,
                                                    'bold'      =>  false
                                                )
                                ));

                                $sheet->setOrientation('portrait');
                                //focus on A1 cell
                                $sheet->setSelectedCells('A1');

                                $sheet->setPageMargin($marginPage);

                                // **********************************************************************
                                //      Set Info Company
                                // **********************************************************************
                                // set logo
                                $objDrawing = new PHPExcel_Worksheet_Drawing;
                                $objDrawing->setPath(public_path('images/logo_excel.png'));
                                $objDrawing->setCoordinates('A'.$pos);
                                $objDrawing->setWorksheet($sheet);
                                // set  company address
                                $sheet->mergeCells('I'.$pos.':Q'.$pos);
                                $sheet->setCellValue('I'.$pos, $header['company_zip_address']);
                                // set height of company_zip_address
                                $height  =   numLineOfRowExcel($header['company_zip_address'], 62);
                                $sheet->getRowDimension($pos)->setRowHeight($height*15);
                                $sheet->getStyle('J'.$pos.':Q'.$pos)->getAlignment()->setWrapText(true);
                                // set Tel
                                $posTF     =   $pos+1;
                                $sheet->setCellValue('I'.$posTF, 'Tel: ');
                                $sheet->getStyle('I'.$posTF)->applyFromArray(getStyleExcel('fontBold'));
                                $sheet->mergeCells('J'.$posTF.':K'.$posTF);
                                //$sheet->setCellValue('J'.$posTF, $header['company_tel']);
                                $sheet->getCell('J'.$posTF)->setValueExplicit($header['company_tel'], \PHPExcel_Cell_DataType::TYPE_STRING);
                                $sheet->getStyle('J'.$posTF)->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
                                // set Fax
                                $sheet->setCellValue('L'.$posTF, 'Fax: ');
                                $sheet->getStyle('L'.$posTF)->applyFromArray(getStyleExcel('fontBold'));
                                $sheet->mergeCells('M'.$posTF.':Q'.$posTF);
                                //$sheet->setCellValue('M'.$posTF, $header['company_fax']);
                                $sheet->getCell('M'.$posTF)->setValueExplicit($header['company_fax'], \PHPExcel_Cell_DataType::TYPE_STRING);
                                $sheet->getStyle('M'.$posTF)->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
                                // set Email
                                $posEU   =   $pos+2;
                                $sheet->setCellValue('I'.$posEU, 'Email : ');
                                $sheet->getStyle('I'.$posEU)->applyFromArray(getStyleExcel('fontBold'));
                                $sheet->mergeCells('J'.$posEU.':K'.$posEU);
                                $sheet->setCellValue('J'.$posEU, $header['company_mail']);
                                // set url
                                $sheet->setCellValue('L'.$posEU, 'URL: ');
                                $sheet->getStyle('L'.$posEU)->applyFromArray(getStyleExcel('fontBold'));
                                $sheet->mergeCells('M'.$posEU.':Q'.$posEU);
                                $sheet->setCellValue('M'.$posEU, $header['company_url']);

                                $posL     =   $pos+3;
                                $sheet->getRowDimension($posL)->setRowHeight(5);

                                $sheet->cells('A1:Q4', function($cells) {
                                    $cells->setFont(array(
                                        'name'      =>  'Century',
                                        'size'      =>  8,
                                        'bold'      =>  false
                                    ));
                                });

                                $sheet->cells('A6:Q17', function($cells) {
                                    $cells->setFont(array(
                                        'size'      =>  10.5
                                    ));
                                });

                                // **********************************************************************
                                //   END Set Info Company
                                // **********************************************************************


                                // **********************************************************************
                                //      Set Content Header First
                                // **********************************************************************

                                $posTitle   =   $pos+4;
                                $sheet->setCellValue('C'.$posTitle, 'Packing List');
                                $sheet->mergeCells('C'.$posTitle.':O'.$posTitle);
                                $sheet->cells('C'.$posTitle.':O'.$posTitle, function($cells) { 
                                    $cells->setAlignment('center');
                                    $cells->setValignment('center');   
                                });

                                $sheet->getStyle('C'.$posTitle.':O'.$posTitle)->applyFromArray(getStyleExcel('fontTitle'));
                                $sheet->getStyle('A'.$posTitle.':Q'.$posTitle)->applyFromArray(getStyleExcel('styleOutlineBorder'));
                                //set page number / page total
                                $sheet->setCellValue('Q'.$posTitle, (1).'/'.$countPage);
                                $sheet->cells('Q'.$posTitle, function($cells) { 
                                    $cells->setAlignment('center');
                                    $cells->setValignment('center');
                                });

                                //set To
                                $posTo     =  $pos+5;
                                $posMerg   =  $posTo + 4;
                                $sheet->setCellValue('A'.$posTo, 'To');
                                $sheet->mergeCells('A'.$posTo.':C'.$posMerg);
                                $sheet->cells('A'.$posTo.':C'.$posMerg, function($cells) { 
                                    $cells->setAlignment('left');
                                    $cells->setValignment('top');
                                });

                                $sheet->getStyle('A'.$posTo.':C'.$posMerg)->applyFromArray(getStyleExcel('fontBold'));
                                $sheet->getStyle('A'.$posTo.':C'.$posMerg)->applyFromArray(getStyleExcel('styleAllBorder'));

                                //set value cust_nm
                                $sheet->setCellValue('D'.$posTo, $inv_no_h['cust_nm']);
                                $sheet->mergeCells('D'.$posTo.':K'.$posTo);
                                $sheet->cells('D'.$posTo.':K'.$posTo, function($cells) { 
                                    $cells->setAlignment('left');
                                    $cells->setValignment('top');   
                                });

                                $posCustAdr1     =  $pos+6;
                                //set value cust_adr1
                                $sheet->setCellValue('D'.$posCustAdr1, $inv_no_h['cust_adr1']);
                                $sheet->mergeCells('D'.$posCustAdr1.':K'.$posCustAdr1);
                                $sheet->cells('D'.$posCustAdr1.':K'.$posCustAdr1, function($cells) { 
                                    $cells->setAlignment('left');
                                    $cells->setValignment('top');   
                                });
                                //set height of cust_adr1
                                $height  =   numLineOfRowExcel($inv_no_h['cust_adr1'], 90);
                                $sheet->getRowDimension($posCustAdr1)->setRowHeight(15);
                                $sheet->getStyle('D'.$posCustAdr1.':K'.$posCustAdr1)->getAlignment()->setWrapText(true);

                                $posCustAdr2     =  $pos+7;
                                //set value cust_adr2
                                $sheet->setCellValue('D'.$posCustAdr2, $inv_no_h['cust_adr2'] . ' , ' . $inv_no_h['cust_country_nm']);
                                $sheet->mergeCells('D'.$posCustAdr2.':K'.$posCustAdr2);
                                $sheet->cells('d'.$posCustAdr2.':K'.$posCustAdr2, function($cells) { 
                                    $cells->setAlignment('left');
                                    $cells->setValignment('top');   
                                });
                                //set height of cust_adr2
                                $height  =   numLineOfRowExcel($inv_no_h['cust_adr2'],72);
                                $sheet->getRowDimension($posCustAdr2)->setRowHeight(15);
                                $sheet->getStyle('D'.$posCustAdr2.':K'.$posCustAdr2)->getAlignment()->setWrapText(true);

                                //set Tel
                                $posCustTel     =  $pos+8;
                                $sheet->setCellValue('D'.$posCustTel, 'Tel');
                                //$sheet->setCellValue('E'.$posCustTel, $inv_no_h['cust_tel']);
                                $sheet->getCell('E'.$posCustTel)->setValueExplicit($inv_no_h['cust_tel'], \PHPExcel_Cell_DataType::TYPE_STRING);
                                $sheet->getStyle('E'.$posCustTel)->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
                                $sheet->mergeCells('E'.$posCustTel.':F'.$posCustTel);
                                $sheet->getStyle('D'.$posCustTel)->applyFromArray(getStyleExcel('fontBold'));
                                $sheet->cells('E'.$posCustTel.':F'.$posCustTel, function($cells) {
                                    $cells->setFont(array(
                                        'size'       =>  10.5
                                    ));
                                });

                                //set Fax
                                $sheet->setCellValue('G'.$posCustTel, 'Fax');
                                //$sheet->setCellValue('H'.$posCustTel, $inv_no_h['cust_fax']);
                                $sheet->getCell('H'.$posCustTel)->setValueExplicit($inv_no_h['cust_fax'], \PHPExcel_Cell_DataType::TYPE_STRING);
                                $sheet->getStyle('H'.$posCustTel)->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
                                $sheet->mergeCells('H'.$posCustTel.':J'.$posCustTel);
                                $sheet->getStyle('G'.$posCustTel)->applyFromArray(getStyleExcel('fontBold'));
                                $sheet->getStyle('D'.$posTo.':K'.$posMerg)->applyFromArray(getStyleExcel('styleOutlineBorder'));
                                $sheet->cells('H'.$posCustTel.':J'.$posCustTel, function($cells) {
                                    $cells->setFont(array(
                                        'size'       =>  10.5
                                    ));
                                });

                                //set No.
                                $sheet->setCellValue('L'.$posTo, 'No.');
                                $sheet->mergeCells('L'.$posTo.':M'.$posTo);
                                $sheet->cells('L'.$posTo.':M'.$posTo, function($cells) { 
                                    $cells->setAlignment('center');
                                    $cells->setValignment('center');
                                });
                                $sheet->getStyle('L'.$posTo.':L'.$posTo)->applyFromArray(getStyleExcel('fontBold'));
                                $sheet->getStyle('L'.$posTo.':Q'.$posTo)->applyFromArray(getStyleExcel('styleAllBorder'));
                                //set inv_no  Value
                                $sheet->setCellValue('N'.$posTo, $inv_no_h['inv_no']);
                                $sheet->mergeCells('N'.$posTo.':Q'.$posTo);
                                $sheet->cells('N'.$posTo.':Q'.$posTo, function($cells) { 
                                    $cells->setAlignment('center');
                                    $cells->setValignment('center');   
                                });
                                $sheet->getStyle('N'.$posTo.':Q'.$posTo)->applyFromArray(getStyleExcel('styleAllBorder'));

                                //set Date.
                                $sheet->setCellValue('L'.$posCustAdr1, 'Date');
                                $sheet->mergeCells('L'.$posCustAdr1.':M'.$posCustAdr1);
                                $sheet->cells('L'.$posCustAdr1.':M'.$posCustAdr1, function($cells) { 
                                    $cells->setAlignment('center');
                                    $cells->setValignment('center');   
                                });
                                $sheet->getStyle('L'.$posCustAdr1.':M'.$posCustAdr1)->applyFromArray(getStyleExcel('fontBold'));
                                $sheet->getStyle('L'.$posCustAdr1.':M'.$posCustAdr1)->applyFromArray(getStyleExcel('styleAllBorder'));

                                //set inv_date Value
                                $sheet->setCellValue('N'.$posCustAdr1, strftime("%B %d,%Y", strtotime($inv_no_h['inv_date'])));
                                $sheet->mergeCells('N'.$posCustAdr1.':Q'.$posCustAdr1);
                                $sheet->cells('N'.$posCustAdr1.':Q'.$posCustAdr1, function($cells) { 
                                    $cells->setAlignment('center');
                                    $cells->setValignment('center');   
                                });
                                $sheet->getStyle('N'.$posCustAdr1.':Q'.$posCustAdr1)->applyFromArray(getStyleExcel('styleAllBorder'));

                                //set Consignee
                                $posCons       =  $pos+10;
                                $posConsMer    =  $posCons + 4;
                                $sheet->setCellValue('A'.$posCons, 'Consignee');
                                $sheet->mergeCells('A'.$posCons.':C'.$posConsMer);
                                $sheet->cells('A'.$posCons.':C'.$posConsMer, function($cells) { 
                                    $cells->setAlignment('left');
                                    $cells->setValignment('top');   
                                });
                                $sheet->getStyle('A'.$posCons.':C'.$posConsMer)->applyFromArray(getStyleExcel('fontBold'));
                                $sheet->getStyle('A'.$posCons.':C'.$posConsMer)->applyFromArray(getStyleExcel('styleAllBorder'));

                                //set value consignee_nm
                                $sheet->setCellValue('D'.$posCons, $inv_no_h['consignee_nm']);
                                $sheet->mergeCells('D'.$posCons.':K'.$posCons);
                                $sheet->cells('D'.$posCons.':K'.$posCons, function($cells) { 
                                    $cells->setAlignment('left');
                                    $cells->setValignment('top');   
                                });
                                //set height of consignee_nm
                                $height  =   numLineOfRowExcel($inv_no_h['consignee_nm'], 72);
                                $sheet->getRowDimension($posCons)->setRowHeight(15);
                                $sheet->getStyle('D'.$posCons.':K'.$posCons)->getAlignment()->setWrapText(true);

                                //set value consignee_adr1
                                $posConsAdr1     =  $pos+11;
                                $sheet->setCellValue('D'.$posConsAdr1, $inv_no_h['consignee_adr1']);
                                $sheet->mergeCells('D'.$posConsAdr1.':K'.$posConsAdr1);
                                $sheet->cells('D'.$posConsAdr1.':K'.$posConsAdr1, function($cells) { 
                                    $cells->setAlignment('left');
                                    $cells->setValignment('top');   
                                });
                                //set height of consignee_adr1
                                $height  =   numLineOfRowExcel($inv_no_h['consignee_adr1'], 72);
                                $sheet->getRowDimension($posConsAdr1)->setRowHeight(15);
                                $sheet->getStyle('D'.$posConsAdr1.':K'.$posConsAdr1)->getAlignment()->setWrapText(true);

                                //set value consignee_adr2
                                $posConsAdr2     =  $pos+12;
                                $sheet->setCellValue('D'.$posConsAdr2, $inv_no_h['consignee_adr2'] . ' , ' . $inv_no_h['consignee_country_nm'], 72);
                                $sheet->mergeCells('D'.$posConsAdr2.':K'.$posConsAdr2);
                                $sheet->cells('D'.$posConsAdr2.':K'.$posConsAdr2, function($cells) { 
                                    $cells->setAlignment('left');
                                    $cells->setValignment('top');   
                                });
                                //set height of consignee_adr2
                                $height  =   numLineOfRowExcel($inv_no_h['consignee_adr2'], 72);
                                $sheet->getRowDimension($posConsAdr2)->setRowHeight(15);
                                $sheet->getStyle('D'.$posConsAdr2.':K'.$posConsAdr2)->getAlignment()->setWrapText(true);
                                //set Tel
                                $posConsTel     =  $pos+13;
                                $sheet->setCellValue('D'.$posConsTel, 'Tel');
                                //$sheet->setCellValue('E'.$posConsTel, $inv_no_h['consignee_tel']);
                                $sheet->getCell('E'.$posConsTel)->setValueExplicit($inv_no_h['consignee_tel'], \PHPExcel_Cell_DataType::TYPE_STRING);
                                $sheet->getStyle('E'.$posConsTel)->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
                                $sheet->mergeCells('E'.$posConsTel.':F'.$posConsTel);
                                $sheet->getStyle('D'.$posConsTel)->applyFromArray(getStyleExcel('fontBold'));

                                //set Fax
                                $sheet->setCellValue('G'.$posConsTel, 'Fax');
                                //$sheet->setCellValue('H'.$posConsTel, $inv_no_h['consignee_fax']);
                                $sheet->getCell('H'.$posConsTel)->setValueExplicit($inv_no_h['consignee_fax'], \PHPExcel_Cell_DataType::TYPE_STRING);
                                $sheet->getStyle('H'.$posConsTel)->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
                                $sheet->mergeCells('H'.$posConsTel.':K'.$posConsTel);
                                $sheet->getStyle('G'.$posConsTel)->applyFromArray(getStyleExcel('fontBold'));
                                $sheet->getStyle('D'.$posCons.':K'.$posConsMer)->applyFromArray(getStyleExcel('styleOutlineBorder'));

                                //set Mark
                                $postMark1  =   $pos+7;
                                $sheet->setCellValue('L'.$postMark1, $inv_no_h['mark1']);
                                $sheet->mergeCells('L'.$postMark1.':Q'.$postMark1);
                                $sheet->getStyle('L'.$postMark1.':Q'.$postMark1)->applyFromArray(getStyleExcel('styleAllBorder'));
                                $sheet->cells('L'.$postMark1.':Q'.$postMark1, function($cells) { 
                                    $cells->setAlignment('center');
                                    $cells->setValignment('center'); 
                                    $cells->setBorder('none', 'thin', 'none', 'thin', \PHPExcel_Style_Border::BORDER_THIN);
                                });

                                 //set height of mark1
                                $height  =   numLineOfRowExcel($inv_no_h['mark1'], 33);
                                $sheet->getRowDimension($postMark1)->setRowHeight(15);
                                $sheet->getStyle('L'.$postMark1.':Q'.$postMark1)->getAlignment()->setWrapText(true);


                                $postMark2  =   $pos+8;
                                $sheet->setCellValue('L'.$postMark2, $inv_no_h['mark2']);
                                $sheet->mergeCells('L'.$postMark2.':Q'.$postMark2);
                                $sheet->getStyle('L'.$postMark2.':Q'.$postMark2)->applyFromArray(getStyleExcel('styleAllBorder'));
                                $sheet->cells('L'.$postMark2.':Q'.$postMark2, function($cells) { 
                                    $cells->setAlignment('center');
                                    $cells->setValignment('center');
                                    $cells->setBorder('none', 'thin', 'none', 'thin', \PHPExcel_Style_Border::BORDER_THIN);
                                });
                                //set height of mark2
                                $height  =   numLineOfRowExcel($inv_no_h['mark2'], 33);
                                $sheet->getRowDimension($postMark2)->setRowHeight($height*15);
                                $sheet->getStyle('L'.$postMark2.':Q'.$postMark2)->getAlignment()->setWrapText(true);

                                $postMark3  =   $pos+9;
                                $sheet->setCellValue('L'.$postMark3, $inv_no_h['mark3']);
                                $sheet->mergeCells('L'.$postMark3.':Q'.$postMark3);
                                $sheet->getStyle('L'.$postMark3.':Q'.$postMark3)->applyFromArray(getStyleExcel('styleAllBorder'));
                                $sheet->cells('L'.$postMark3.':Q'.$postMark3, function($cells) { 
                                    $cells->setAlignment('center');
                                    $cells->setValignment('center');
                                    $cells->setBorder('none', 'thin', 'none', 'thin', \PHPExcel_Style_Border::BORDER_THIN);
                                });
                                //set height of mark3
                                $height  =   numLineOfRowExcel($inv_no_h['mark3'], 33);
                                $sheet->getRowDimension($postMark3)->setRowHeight($height*15);
                                $sheet->getStyle('L'.$postMark3.':Q'.$postMark3)->getAlignment()->setWrapText(true);

                                $postMark4  =   $pos+10;
                                $sheet->setCellValue('L'.$postMark4, $inv_no_h['mark4']);
                                $sheet->mergeCells('L'.$postMark4.':Q'.$postMark4);
                                $sheet->getStyle('L'.$postMark4.':Q'.$postMark4)->applyFromArray(getStyleExcel('styleAllBorder'));
                                $sheet->cells('L'.$postMark4.':Q'.$postMark4, function($cells) { 
                                    $cells->setAlignment('center');
                                    $cells->setValignment('center');
                                    $cells->setBorder('none', 'thin', 'none', 'thin', \PHPExcel_Style_Border::BORDER_THIN);
                                });
                                //set height of mark4
                                $height  =   numLineOfRowExcel($inv_no_h['mark4'], 33);
                                $sheet->getRowDimension($postMark4)->setRowHeight($height*15);
                                $sheet->getStyle('L'.$postMark4.':Q'.$postMark4)->getAlignment()->setWrapText(true);

                                $postMark     =   $pos+11;
                                $postMarkMer  =   $pos+14;
                                $sheet->mergeCells('L'.$postMark.':Q'.$postMarkMer);
                                $sheet->getStyle('L'.$postMark.':Q'.$postMarkMer)->applyFromArray(getStyleExcel('styleAllBorder'));
                                $sheet->cells('L'.$postMark.':Q'.$postMarkMer, function($cells) { 
                                    $cells->setAlignment('center');
                                    $cells->setValignment('center');
                                    $cells->setBorder('none', 'thin', 'thin', 'thin', \PHPExcel_Style_Border::BORDER_THIN);
                                });
                                //set Packing Title
                                $posPacking    =  $pos+15;
                                $sheet->setCellValue('A'.$posPacking, 'Date of Shipment (on or about)');
                                $sheet->mergeCells('A'.$posPacking.':C'.$posPacking);
                                $sheet->cells('A'.$posPacking.':C'.$posPacking, function($cells) { 
                                    $cells->setAlignment('center');
                                    $cells->setValignment('center');   
                                });
                                $sheet->getStyle('A'.$posPacking.':C'.$posPacking)->applyFromArray(getStyleExcel('fontBold'));
                                $sheet->getStyle('A'.$posPacking.':C'.$posPacking)->applyFromArray(getStyleExcel('styleAllBorder'));
                                //set Packing value
                                $sheet->setCellValue('D'.$posPacking, strftime("%B %d,%Y", strtotime($inv_no_h['shipment_date'])));
                                $sheet->mergeCells('D'.$posPacking.':G'.$posPacking);
                                $sheet->getStyle('D'.$posPacking.':G'.$posPacking)->applyFromArray(getStyleExcel('styleAllBorder'));
                                $sheet->getStyle('A'.$posPacking.':C'.$posPacking)->getAlignment()->setWrapText(true);
                                $sheet->getRowDimension($posPacking)->setRowHeight(29);
                                $sheet->cells('A'.$posPacking.':C'.$posPacking, function($cells) {
                                    $cells->setFont(array(
                                        'size'       =>  10
                                    ));
                                });
                                $sheet->cells('D'.$posPacking.':G'.$posPacking, function($cells) { 
                                    $cells->setValignment('center');   
                                });
                                //set Shipment Title

                                $sheet->setCellValue('H'.$posPacking, 'Shipped per');
                                $sheet->mergeCells('H'.$posPacking.':J'.$posPacking);
                                $sheet->getStyle('H'.$posPacking.':J'.$posPacking)->applyFromArray(getStyleExcel('fontBold'));
                                $sheet->getStyle('H'.$posPacking.':J'.$posPacking)->applyFromArray(getStyleExcel('styleAllBorder'));
                                $sheet->cells('H'.$posPacking.':J'.$posPacking, function($cells) { 
                                    $cells->setValignment('center');   
                                });
                                //set Shipment Value
                                $sheet->setCellValue('K'.$posPacking, $inv_no_h['shipment_div']);
                                $sheet->mergeCells('K'.$posPacking.':Q'.$posPacking);
                                $sheet->getStyle('K'.$posPacking.':Q'.$posPacking)->applyFromArray(getStyleExcel('styleAllBorder'));
                                $sheet->cells('K'.$posPacking.':Q'.$posPacking, function($cells) { 
                                    $cells->setValignment('center');   
                                });

                                // set Port of Shipmenｔ Title
                                $posPort    =  $pos+16;
                                $sheet->setCellValue('A'.$posPort, 'Port of Shipment');
                                $sheet->mergeCells('A'.$posPort.':C'.$posPort);
                                $sheet->cells('A'.$posPort.':C'.$posPort, function($cells) { 
                                    $cells->setAlignment('center');
                                    $cells->setValignment('center');   
                                });
                                $sheet->getStyle('A'.$posPort.':C'.$posPort)->applyFromArray(getStyleExcel('fontBold'));
                                $sheet->getStyle('A'.$posPort.':C'.$posPort)->applyFromArray(getStyleExcel('styleAllBorder'));
                                // set Port of Shipment value
                                $sheet->setCellValue('D'.$posPort, $inv_no_h['port_nm']);
                                $sheet->mergeCells('D'.$posPort.':G'.$posPort);
                                $sheet->getStyle('D'.$posPort.':G'.$posPort)->applyFromArray(getStyleExcel('styleAllBorder'));
                                $sheet->getRowDimension($posPort)->setRowHeight(22);
                                $sheet->cells('A'.$posPort.':Q'.$posPort, function($cells) { 
                                    $cells->setValignment('center');   
                                });

                                // set Destination Title
                                $sheet->setCellValue('H'.$posPort, 'Destination');
                                $sheet->mergeCells('H'.$posPort.':J'.$posPort);
                                $sheet->getStyle('H'.$posPort.':J'.$posPort)->applyFromArray(getStyleExcel('fontBold'));
                                $sheet->getStyle('H'.$posPort.':J'.$posPort)->applyFromArray(getStyleExcel('styleAllBorder'));
                                // set Destination Value
                                $sheet->setCellValue('K'.$posPort, $inv_no_h['dest_nm']);
                                $sheet->mergeCells('K'.$posPort.':Q'.$posPort);
                                $sheet->getStyle('K'.$posPort.':Q'.$posPort)->applyFromArray(getStyleExcel('styleAllBorder'));


                                //
                                $posRow18 = $pos+17;
                                // C/No
                                $sheet->setCellValue('A'.$posRow18, 'C/No');
                                $sheet->mergeCells('A'.$posRow18.':B'.$posRow18);
                                $sheet->cells('A'.$posRow18.':B'.$posRow18, function($cells) { 
                                    $cells->setAlignment('center');
                                    $cells->setValignment('center');   
                                });
                                $sheet->getStyle('A'.$posRow18.':B'.$posRow18)->applyFromArray(getStyleExcel('fontBold'));
                                $sheet->getStyle('A'.$posRow18.':B'.$posRow18)->applyFromArray(getStyleExcel('styleAllBorder'));

                                // Description
                                $sheet->setCellValue('C'.$posRow18, 'Description');
                                $sheet->mergeCells('C'.$posRow18.':I'.$posRow18);
                                $sheet->cells('C'.$posRow18.':I'.$posRow18, function($cells) { 
                                    $cells->setAlignment('center');
                                    $cells->setValignment('center');   
                                });
                                $sheet->getStyle('C'.$posRow18.':I'.$posRow18)->applyFromArray(getStyleExcel('fontBold'));
                                $sheet->getStyle('C'.$posRow18.':I'.$posRow18)->applyFromArray(getStyleExcel('styleAllBorder'));

                                // Qty
                                $sheet->setCellValue('J'.$posRow18, 'Qty');
                                $sheet->cells('J'.$posRow18, function($cells) { 
                                    $cells->setAlignment('center');
                                    $cells->setValignment('center');   
                                });
                                $sheet->getStyle('J'.$posRow18)->applyFromArray(getStyleExcel('fontBold'));
                                $sheet->getStyle('J'.$posRow18)->applyFromArray(getStyleExcel('styleAllBorder'));

                                // Unit of measure
                                $sheet->setCellValue('K'.$posRow18, 'Unit of measure');
                                $sheet->cells('K'.$posRow18, function($cells) { 
                                    $cells->setAlignment('center');
                                    $cells->setValignment('center');   
                                });
                                $sheet->getStyle('K'.$posRow18)->applyFromArray(getStyleExcel('fontBold'));
                                $sheet->getStyle('K'.$posRow18)->applyFromArray(getStyleExcel('styleAllBorder'));

                                $sheet->cells('K'.$posRow18, function($cells) {
                                    $cells->setFont(array(
                                        'size'       =>  7
                                    ));
                                });

                                $sheet->getStyle('K'.$posRow18)->getAlignment()->setWrapText(true);

                                // N/W
                                $sheet->setCellValue('L'.$posRow18, 'N/W');
                                $sheet->mergeCells('L'.$posRow18.':M'.$posRow18);
                                $sheet->cells('L'.$posRow18.':M'.$posRow18, function($cells) { 
                                    $cells->setAlignment('center');
                                    $cells->setValignment('center');   
                                });
                                $sheet->getStyle('L'.$posRow18.':M'.$posRow18)->applyFromArray(getStyleExcel('fontBold'));
                                $sheet->getStyle('L'.$posRow18.':M'.$posRow18)->applyFromArray(getStyleExcel('styleAllBorder'));

                                // G/W
                                $sheet->setCellValue('N'.$posRow18, 'G/W');
                                $sheet->mergeCells('N'.$posRow18.':O'.$posRow18);
                                $sheet->cells('N'.$posRow18.':O'.$posRow18, function($cells) { 
                                    $cells->setAlignment('center');
                                    $cells->setValignment('center');   
                                });
                                $sheet->getStyle('N'.$posRow18.':O'.$posRow18)->applyFromArray(getStyleExcel('fontBold'));
                                $sheet->getStyle('N'.$posRow18.':O'.$posRow18)->applyFromArray(getStyleExcel('styleAllBorder'));

                                // Measurement
                                $sheet->setCellValue('P'.$posRow18, 'Measurement');
                                $sheet->mergeCells('P'.$posRow18.':Q'.$posRow18);
                                $sheet->cells('P'.$posRow18.':Q'.$posRow18, function($cells) { 
                                    $cells->setAlignment('center');
                                    $cells->setValignment('center');
                                });
                                $sheet->getStyle('P'.$posRow18.':Q'.$posRow18)->applyFromArray(getStyleExcel('fontBold'));
                                $sheet->getStyle('P'.$posRow18.':Q'.$posRow18)->applyFromArray(getStyleExcel('styleAllBorder'));

                                $sheet->getRowDimension($posRow18)->setRowHeight(25);                                
                                //**********************************************************************
                                //     Set Content Header More page
                                //**********************************************************************
                                if(count($data_pagi_first[0]) > 14){
                                    // Create more page excel and diff header
                                    $posFooterMorePage     = 0;
                                    $flag_draw             = false;
                                    for ($i = 1; $i <= count($data_pagi); $i++) {
                                        $posSecond    =   $page_size*$i + 1;
                                        $posSecond1   =   $posSecond + 1;
                                        $sheet->mergeCells('A'.$posSecond.':H'.$posSecond1);
                                        $sheet->setCellValue('A'.$posSecond, ' APEL CO.,LTD.');
                                        $sheet->getRowDimension($posSecond)->setRowHeight(20);
                                        $sheet->getRowDimension($posSecond1)->setRowHeight(20);
                                        $sheet->cells('A'.$posSecond.':H'.$posSecond1, function($cells) {
                                            $cells->setFont(array(
                                                'size'       =>  22
                                            ));
                                        });

                                        // posSecond
                                        $sheet->setCellValue('N'.$posSecond, 'No.');
                                        $sheet->mergeCells('O'.$posSecond.':Q'.$posSecond);
                                        $sheet->setCellValue('O'.$posSecond, $inv_no_h['inv_no']);
                                        
                                        $sheet->cells('O'.$posSecond.':Q'.$posSecond, function($cells) { 
                                            $cells->setAlignment('center');
                                        });                                        
                                        $sheet->getStyle('N'.$posSecond)->applyFromArray(getStyleExcel('styleAllBorder'));
                                        $sheet->getStyle('O'.$posSecond.':Q'.$posSecond)->applyFromArray(getStyleExcel('styleAllBorder'));

                                        // posSecond1
                                        $sheet->setCellValue('N'.$posSecond1, 'Date ');
                                        $sheet->mergeCells('O'.$posSecond1.':Q'.$posSecond1);
                                        $sheet->setCellValue('O'.$posSecond1, strftime("%B %d,%Y", strtotime($inv_no_h['inv_date'])));
                                        
                                        $sheet->cells('O'.$posSecond1.':Q'.$posSecond1, function($cells) { 
                                            $cells->setAlignment('center');
                                        });                                        
                                        $sheet->getStyle('N'.$posSecond1)->applyFromArray(getStyleExcel('styleAllBorder'));
                                        $sheet->getStyle('O'.$posSecond1.':Q'.$posSecond1)->applyFromArray(getStyleExcel('styleAllBorder'));

                                        // posSecond2
                                        $posSecond2 = $posSecond1 + 1;
                                        $sheet->getRowDimension($posSecond2)->setRowHeight(6);

                                        // posSecond3
                                        $posSecond3 = $posSecond2 + 1;
                                        $sheet->setCellValue('C'.$posSecond3, 'Packing List');
                                        $sheet->mergeCells('C'.$posSecond3.':O'.$posSecond3);
                                        $sheet->cells('C'.$posSecond3.':O'.$posSecond3, function($cells) { 
                                            $cells->setAlignment('center');
                                            $cells->setValignment('center');
                                        });

                                        $sheet->getStyle('C'.$posSecond3.':O'.$posSecond3)->applyFromArray(getStyleExcel('fontTitle'));
                                        $sheet->getStyle('A'.$posSecond3.':Q'.$posSecond3)->applyFromArray(getStyleExcel('styleOutlineBorder'));
                                        //set page number / page total
                                        $sheet->setCellValue('Q'.$posSecond3, (1 + $i).'/'.$countPage);
                                        $sheet->cells('Q'.$posSecond3, function($cells) {
                                            $cells->setAlignment('center');
                                            $cells->setValignment('center');
                                        });

                                        // posSecond4
                                        $posSecond4 = $posSecond3 + 1;

                                        // Loop for data detail
                                        foreach ($data_pagi[$i-1] as $key => $value) {
                                            $row        =   $posSecond4+ $key;                                    
                                            // carton_number
                                            $sheet->setCellValue('A'.$row, $value['carton_number']);
                                            
                                            // inv_carton_no
                                            $sheet->setCellValue('B'.$row,'('.$value['inv_carton_no'].')');
                                            
                                            // description
                                            $sheet->setCellValue('C'.$row,$value['description']);
                                            
                                            // qty
                                            $sheet->setCellValue('J'.$row,$value['qty']);
                                            
                                            // unit_qty_div
                                            $sheet->setCellValue('K'.$row,$value['unit_qty_div']);
                                            
                                            // unit_price
                                            $sheet->setCellValue('L'.$row,$value['unit_price']);
                                            
                                            // unit_w_div
                                            $sheet->setCellValue('M'.$row,$value['unit_w_div']);
                                            
                                            // amount
                                            $sheet->setCellValue('N'.$row,$value['amount']);
                                            
                                            // unit_w_div_1
                                            $sheet->setCellValue('O'.$row,$value['unit_w_div_1']);
                                            
                                            // unit_m_div
                                            $sheet->setCellValue('Q'.$row,$value['unit_m_div']);
                                        }
                                        // End Loop for data detail

                                        // Format style
                                        $total_line_draw_more  = 0;
                                        $flagPositionFooter    = false;
                                        
                                        if(count($data_pagi[$i-1]) <= 23){
                                            $total_line_draw_more = $posSecond4 + 22;
                                            $posFooterMorePage    = $page_size*$i + 28;
                                        }
                                        else{
                                            if(count($data_pagi[$i-1]) >= 24 && count($data_pagi[$i-1]) <= 26){
                                                $total_line_draw_more = $posSecond4 + 26;
                                                $posFooterMorePage    = $page_size*($i+1) + 28;
                                                $flag_draw            = true;
                                                $newPos               = $page_size*($i+1);
                                            }
                                            else{
                                                $total_line_draw_more = $posSecond4 + 26;
                                            }
                                        }

                                        for($j = $posSecond4; $j<=$total_line_draw_more ; $j++){
                                            $sheet->getRowDimension($j)->setRowHeight(28);
                                            $sheet->cells('A'.$j, function($cells){
                                                $cells->setAlignment('center');
                                                $cells->setValignment('center');
                                                $cells->setBorder('none', 'none', 'none', 'thin', \PHPExcel_Style_Border::BORDER_THIN);
                                            });
                                            $sheet->cells('B'.$j, function($cells){
                                                $cells->setAlignment('center');
                                                $cells->setValignment('center');
                                                $cells->setBorder('none', 'thin', 'none', 'none', \PHPExcel_Style_Border::BORDER_THIN);
                                            });
                                            $sheet->mergeCells('C'.$j.':I'.$j);
                                            $sheet->cells('C'.$j.':I'.$j, function($cells){
                                                $cells->setAlignment('left');
                                                $cells->setValignment('center');
                                                $cells->setBorder('none', 'thin', 'none', 'thin', \PHPExcel_Style_Border::BORDER_THIN);
                                            });
                                            $sheet->getStyle('C'.$j.':I'.$j)->getAlignment()->setWrapText(true);
                                            $sheet->cells('C'.$j.':I'.$j, function($cells) {
                                                $cells->setFont(array(
                                                    'size'       =>  9
                                                ));
                                            });

                                            $sheet->cells('J'.$j, function($cells){
                                                $cells->setAlignment('center');
                                                $cells->setValignment('center');
                                                $cells->setBorder('none', 'thin', 'none', 'thin', \PHPExcel_Style_Border::BORDER_THIN);
                                            });
                                            $sheet->cells('K'.$j, function($cells){
                                                $cells->setAlignment('left');
                                                $cells->setValignment('center');
                                                $cells->setBorder('none', 'thin', 'none', 'thin', \PHPExcel_Style_Border::BORDER_THIN);
                                            });
                                            $sheet->cells('L'.$j, function($cells){
                                                $cells->setAlignment('center');
                                                $cells->setValignment('center');
                                                $cells->setBorder('none', 'none', 'none', 'thin', \PHPExcel_Style_Border::BORDER_THIN);
                                            });
                                            $sheet->getStyle('L'.$j)->getAlignment()->setWrapText(true);

                                            $sheet->cells('M'.$j, function($cells){
                                                $cells->setAlignment('left');
                                                $cells->setValignment('center');
                                                $cells->setBorder('none', 'thin', 'none', 'none', \PHPExcel_Style_Border::BORDER_THIN);
                                            });
                                            $sheet->cells('N'.$j, function($cells){
                                                $cells->setAlignment('center');
                                                $cells->setValignment('center');
                                                $cells->setBorder('none', 'none', 'none', 'thin', \PHPExcel_Style_Border::BORDER_THIN);
                                            });
                                            $sheet->getStyle('N'.$j)->getAlignment()->setWrapText(true);

                                            $sheet->cells('O'.$j, function($cells){
                                                $cells->setAlignment('left');
                                                $cells->setValignment('center');
                                                $cells->setBorder('none', 'thin', 'none', 'none', \PHPExcel_Style_Border::BORDER_THIN);
                                            });
                                            $sheet->cells('Q'.$j, function($cells){
                                                $cells->setAlignment('left');
                                                $cells->setValignment('center');
                                                $cells->setBorder('none', 'thin', 'none', 'none', \PHPExcel_Style_Border::BORDER_THIN);
                                            });                                            
                                        }

                                        // Format end row of more page
                                        if($j=$total_line_draw_more){
                                            $sheet->cells('A'.$j, function($cells){
                                                $cells->setBorder('none', 'none', 'thin', 'thin', \PHPExcel_Style_Border::BORDER_THIN);
                                            });
                                            $sheet->cells('B'.$j, function($cells){
                                                $cells->setBorder('none', 'thin', 'thin', 'none', \PHPExcel_Style_Border::BORDER_THIN);
                                            });
                                            $sheet->mergeCells('C'.$j.':I'.$j);
                                            $sheet->cells('C'.$j.':I'.$j, function($cells){
                                                $cells->setBorder('none', 'thin', 'thin', 'thin', \PHPExcel_Style_Border::BORDER_THIN);
                                            });
                                            $sheet->cells('J'.$j, function($cells){
                                                $cells->setBorder('none', 'thin', 'thin', 'thin', \PHPExcel_Style_Border::BORDER_THIN);
                                            });
                                            $sheet->cells('K'.$j, function($cells){
                                                $cells->setBorder('none', 'thin', 'thin', 'thin', \PHPExcel_Style_Border::BORDER_THIN);
                                            });
                                            $sheet->cells('L'.$j, function($cells){
                                                $cells->setBorder('none', 'none', 'thin', 'thin', \PHPExcel_Style_Border::BORDER_THIN);
                                            });
                                            $sheet->cells('M'.$j, function($cells){
                                                $cells->setBorder('none', 'thin', 'thin', 'none', \PHPExcel_Style_Border::BORDER_THIN);
                                            });
                                            $sheet->cells('N'.$j, function($cells){
                                                $cells->setBorder('none', 'none', 'thin', 'thin', \PHPExcel_Style_Border::BORDER_THIN);
                                            });
                                            $sheet->cells('O'.$j, function($cells){
                                                $cells->setBorder('none', 'thin', 'thin', 'none', \PHPExcel_Style_Border::BORDER_THIN);
                                            });
                                            $sheet->cells('P'.$j, function($cells){
                                                $cells->setBorder('none', 'none', 'thin', 'none', \PHPExcel_Style_Border::BORDER_THIN);
                                            });
                                            $sheet->cells('Q'.$j, function($cells){
                                                $cells->setBorder('none', 'thin', 'thin', 'none', \PHPExcel_Style_Border::BORDER_THIN);
                                            });
                                        }

                                        // End format style

                                        // Style footer more page
                                        $posSecond5        = $posSecond4 + 1;

                                        // Reset page size
                                        $page_size  =   36 - $i;
                                    }
                                    // Set footer more page
                                    // Total
                                    $sheet->cells('A'.$posFooterMorePage.':Q'.$posFooterMorePage, function($cells){
                                        $cells->setAlignment('center');
                                        $cells->setValignment('center');
                                        $cells->setBorder('thin', 'thin', 'thin', 'thin', \PHPExcel_Style_Border::BORDER_THIN);
                                    });
                                    $sheet->getRowDimension($posFooterMorePage)->setRowHeight(32);
                                    $sheet->cells('A'.$posFooterMorePage.':B'.$posFooterMorePage, function($cells){
                                        $cells->setAlignment('center');
                                        $cells->setValignment('center');
                                    });
                                    $sheet->mergeCells('A'.$posFooterMorePage.':B'.$posFooterMorePage);
                                    $sheet->setCellValue('A'.$posFooterMorePage, 'Total');
                                    $sheet->getStyle('A'.$posFooterMorePage.':B'.$posFooterMorePage)->applyFromArray(getStyleExcel('styleAllBorder'));
                                    // total_carton_num
                                    $sheet->setCellValue('G'.$posFooterMorePage, $inv_no_footer['total_carton_num']);
                                    // total_carton_num
                                    $sheet->setCellValue('H'.$posFooterMorePage, 'cartons');
                                    $sheet->getStyle('I'.$posFooterMorePage)->applyFromArray(getStyleExcel('styleAllBorder'));
                                    $sheet->getStyle('J'.$posFooterMorePage)->applyFromArray(getStyleExcel('styleAllBorder'));
                                    $sheet->getStyle('K'.$posFooterMorePage)->applyFromArray(getStyleExcel('styleAllBorder'));
                                    // total_carton_net_weight
                                    $sheet->setCellValue('L'.$posFooterMorePage, $inv_no_footer['total_carton_net_weight']);
                                    $sheet->setCellValue('M'.$posFooterMorePage, $inv_no_footer['unit_total_net_weight_div']);
                                    $sheet->cells('M'.$posFooterMorePage, function($cells){
                                        $cells->setAlignment('center');
                                        $cells->setValignment('center');
                                        $cells->setBorder('thin', 'thin', 'thin', 'none', \PHPExcel_Style_Border::BORDER_THIN);
                                    });
                                    // total_carton_gross_weight
                                    $sheet->setCellValue('N'.$posFooterMorePage, $inv_no_footer['total_carton_gross_weight']);
                                    $sheet->setCellValue('O'.$posFooterMorePage, $inv_no_footer['unit_total_gross_weight_div']);
                                    $sheet->cells('O'.$posFooterMorePage, function($cells){
                                        $cells->setAlignment('center');
                                        $cells->setValignment('center');
                                        $cells->setBorder('thin', 'thin', 'thin', 'none', \PHPExcel_Style_Border::BORDER_THIN);
                                    });

                                    // unit_total_measure_div
                                    $sheet->setCellValue('Q'.$posFooterMorePage, $inv_no_footer['unit_total_measure_div']);

                                    $posFooterMorePageSign      = $posFooterMorePage + 2;
                                    $posFooterMorePagePosition  = $posFooterMorePage + 3;
                                    $posFooterMorePageBelong    = $posFooterMorePage + 4;
                                    // sign_nm
                                    $sheet->setCellValue('L'.$posFooterMorePageSign, $inv_no_footer['sign_nm']);
                                    // position_div
                                    $sheet->setCellValue('L'.$posFooterMorePagePosition, $inv_no_footer['position_div']);
                                    // belong_div
                                    $sheet->setCellValue('L'.$posFooterMorePageBelong, $inv_no_footer['belong_div']);
                                    // __________________new position__________________
                                    // Draw new position
                                    if($flag_draw){
                                        $posNew1   =   $newPos + 1;
                                        $sheet->mergeCells('A'.$newPos.':H'.$posNew1);
                                        $sheet->setCellValue('A'.$newPos, ' APEL CO.,LTD.');
                                        $sheet->getRowDimension($newPos)->setRowHeight(20);
                                        $sheet->getRowDimension($posNew1)->setRowHeight(20);
                                        $sheet->cells('A'.$newPos.':H'.$posNew1, function($cells) {
                                            $cells->setFont(array(
                                                'size'       =>  22
                                            ));
                                        });

                                        // newPos
                                        $sheet->setCellValue('N'.$newPos, 'No.');
                                        $sheet->mergeCells('O'.$newPos.':Q'.$newPos);
                                        $sheet->setCellValue('O'.$newPos, $inv_no_h['inv_no']);
                                        
                                        $sheet->cells('O'.$newPos.':Q'.$newPos, function($cells) { 
                                            $cells->setAlignment('center');
                                        });                                        
                                        $sheet->getStyle('N'.$newPos)->applyFromArray(getStyleExcel('styleAllBorder'));
                                        $sheet->getStyle('O'.$newPos.':Q'.$newPos)->applyFromArray(getStyleExcel('styleAllBorder'));

                                        // posNew1
                                        $sheet->setCellValue('N'.$posNew1, 'Date ');
                                        $sheet->mergeCells('O'.$posNew1.':Q'.$posNew1);
                                        $sheet->setCellValue('O'.$posNew1, strftime("%B %d,%Y", strtotime($inv_no_h['inv_date'])));
                                        
                                        $sheet->cells('O'.$posNew1.':Q'.$posNew1, function($cells) { 
                                            $cells->setAlignment('center');
                                        });                                        
                                        $sheet->getStyle('N'.$posNew1)->applyFromArray(getStyleExcel('styleAllBorder'));
                                        $sheet->getStyle('O'.$posNew1.':Q'.$posNew1)->applyFromArray(getStyleExcel('styleAllBorder'));

                                        // posNew2
                                        $posNew2 = $posNew1 + 1;
                                        $sheet->getRowDimension($posNew2)->setRowHeight(6);

                                        // posNew3
                                        $posNew3 = $posNew2 + 1;
                                        $sheet->setCellValue('C'.$posNew3, 'Packing List');
                                        $sheet->mergeCells('C'.$posNew3.':O'.$posNew3);
                                        $sheet->cells('C'.$posNew3.':O'.$posNew3, function($cells) { 
                                            $cells->setAlignment('center');
                                            $cells->setValignment('center');
                                        });

                                        $sheet->getStyle('C'.$posNew3.':O'.$posNew3)->applyFromArray(getStyleExcel('fontTitle'));
                                        $sheet->getStyle('A'.$posNew3.':Q'.$posNew3)->applyFromArray(getStyleExcel('styleOutlineBorder'));
                                        //set page number / page total
                                        $sheet->setCellValue('Q'.$posNew3, (1 + $i).'/'.$countPage);
                                        $sheet->cells('Q'.$posNew3, function($cells) {
                                            $cells->setAlignment('center');
                                            $cells->setValignment('center');
                                        });

                                        // posNew4
                                        $posNew4 = $posNew3 + 1;

                                        for($k = $posNew4; $k<=$posNew4+23 ; $k++){
                                            $sheet->getRowDimension($k)->setRowHeight(28);
                                            $sheet->cells('A'.$k, function($cells){
                                                $cells->setAlignment('center');
                                                $cells->setValignment('center');
                                                $cells->setBorder('none', 'none', 'none', 'thin', \PHPExcel_Style_Border::BORDER_THIN);
                                            });
                                            $sheet->cells('B'.$k, function($cells){
                                                $cells->setAlignment('center');
                                                $cells->setValignment('center');
                                                $cells->setBorder('none', 'thin', 'none', 'none', \PHPExcel_Style_Border::BORDER_THIN);
                                            });
                                            $sheet->mergeCells('C'.$k.':I'.$k);
                                            $sheet->cells('C'.$k.':I'.$k, function($cells){
                                                $cells->setAlignment('left');
                                                $cells->setValignment('center');
                                                $cells->setBorder('none', 'thin', 'none', 'thin', \PHPExcel_Style_Border::BORDER_THIN);
                                            });
                                            $sheet->cells('J'.$k, function($cells){
                                                $cells->setAlignment('center');
                                                $cells->setValignment('center');
                                                $cells->setBorder('none', 'thin', 'none', 'thin', \PHPExcel_Style_Border::BORDER_THIN);
                                            });
                                            $sheet->cells('K'.$k, function($cells){
                                                $cells->setAlignment('left');
                                                $cells->setValignment('center');
                                                $cells->setBorder('none', 'thin', 'none', 'thin', \PHPExcel_Style_Border::BORDER_THIN);
                                            });
                                            $sheet->cells('L'.$k, function($cells){
                                                $cells->setAlignment('center');
                                                $cells->setValignment('center');
                                                $cells->setBorder('none', 'none', 'none', 'thin', \PHPExcel_Style_Border::BORDER_THIN);
                                            });
                                            $sheet->cells('M'.$k, function($cells){
                                                $cells->setAlignment('left');
                                                $cells->setValignment('center');
                                                $cells->setBorder('none', 'thin', 'none', 'none', \PHPExcel_Style_Border::BORDER_THIN);
                                            });
                                            $sheet->cells('N'.$k, function($cells){
                                                $cells->setAlignment('center');
                                                $cells->setValignment('center');
                                                $cells->setBorder('none', 'none', 'none', 'thin', \PHPExcel_Style_Border::BORDER_THIN);
                                            });
                                            $sheet->cells('O'.$k, function($cells){
                                                $cells->setAlignment('left');
                                                $cells->setValignment('center');
                                                $cells->setBorder('none', 'thin', 'none', 'none', \PHPExcel_Style_Border::BORDER_THIN);
                                            });
                                            $sheet->cells('Q'.$k, function($cells){
                                                $cells->setAlignment('left');
                                                $cells->setValignment('center');
                                                $cells->setBorder('none', 'thin', 'none', 'none', \PHPExcel_Style_Border::BORDER_THIN);
                                            });
                                        }
                                        // __________________end new position__________________
                                    }
                                }
                                //**********************************************************************
                                //     End Set Content Header More page
                                //**********************************************************************

                                //**********************************************************************
                                //     END Set Content Header
                                //**********************************************************************

                                //**********************************************************************
                                //     Set Data Detail
                                //**********************************************************************
                                foreach ($data_pagi_first[0] as $key => $value) {
                                    $row        =   $pos+18+$key;

                                    // carton_number
                                    $sheet->setCellValue('A'.$row, $value['carton_number']);
                                    
                                    // inv_carton_no
                                    $sheet->setCellValue('B'.$row,'('.$value['inv_carton_no'].')');
                                    
                                    // description
                                    $sheet->setCellValue('C'.$row,$value['description']);
                                    
                                    // qty
                                    $sheet->setCellValue('J'.$row,$value['qty']);
                                    
                                    // unit_qty_div
                                    $sheet->setCellValue('K'.$row,$value['unit_qty_div']);
                                    
                                    // unit_price
                                    $sheet->setCellValue('L'.$row,$value['unit_price']);
                                    
                                    // unit_w_div
                                    $sheet->setCellValue('M'.$row,$value['unit_w_div']);
                                    
                                    // amount
                                    $sheet->setCellValue('N'.$row,$value['amount']);
                                    
                                    // unit_w_div_1
                                    $sheet->setCellValue('O'.$row,$value['unit_w_div_1']);
                                    
                                    // unit_m_div
                                    $sheet->setCellValue('Q'.$row,$value['unit_m_div']);
                                }

                                // Format style
                                $total_line_draw  = 0;
                                $flag_first_page  = false;
                                if(count($data_pagi_first[0]) <= 14){
                                    $total_line_draw = 32;
                                    $flag_first_page = true;
                                }
                                else{
                                    $total_line_draw = 36;
                                }

                                for($i = 19; $i<=$total_line_draw ; $i++){
                                    $sheet->getRowDimension($i)->setRowHeight(30);
                                    $sheet->cells('A'.$i, function($cells){
                                        $cells->setAlignment('center');
                                        $cells->setValignment('center');
                                        $cells->setBorder('none', 'none', 'none', 'thin', \PHPExcel_Style_Border::BORDER_THIN);
                                    });
                                    $sheet->cells('B'.$i, function($cells){
                                        $cells->setAlignment('center');
                                        $cells->setValignment('center');
                                        $cells->setBorder('none', 'thin', 'none', 'none', \PHPExcel_Style_Border::BORDER_THIN);
                                    });
                                    $sheet->mergeCells('C'.$i.':I'.$i);
                                    $sheet->cells('C'.$i.':I'.$i, function($cells){
                                        $cells->setAlignment('left');
                                        $cells->setValignment('center');
                                        $cells->setBorder('none', 'thin', 'none', 'thin', \PHPExcel_Style_Border::BORDER_THIN);
                                    });
                                    $sheet->getStyle('C'.$i.':I'.$i)->getAlignment()->setWrapText(true);
                                    $sheet->cells('C'.$i.':I'.$i, function($cells) {
                                        $cells->setFont(array(
                                            'size'       =>  9
                                        ));
                                    });

                                    $sheet->cells('J'.$i, function($cells){
                                        $cells->setAlignment('center');
                                        $cells->setValignment('center');
                                        $cells->setBorder('none', 'thin', 'none', 'thin', \PHPExcel_Style_Border::BORDER_THIN);
                                    });
                                    $sheet->cells('K'.$i, function($cells){
                                        $cells->setAlignment('left');
                                        $cells->setValignment('center');
                                        $cells->setBorder('none', 'thin', 'none', 'thin', \PHPExcel_Style_Border::BORDER_THIN);
                                    });
                                    $sheet->cells('L'.$i, function($cells){
                                        $cells->setAlignment('center');
                                        $cells->setValignment('center');
                                        $cells->setBorder('none', 'none', 'none', 'thin', \PHPExcel_Style_Border::BORDER_THIN);
                                    });
                                    $sheet->getStyle('L'.$i)->getAlignment()->setWrapText(true);

                                    $sheet->cells('M'.$i, function($cells){
                                        $cells->setAlignment('left');
                                        $cells->setValignment('center');
                                        $cells->setBorder('none', 'thin', 'none', 'none', \PHPExcel_Style_Border::BORDER_THIN);
                                    });
                                    $sheet->cells('N'.$i, function($cells){
                                        $cells->setAlignment('center');
                                        $cells->setValignment('center');
                                        $cells->setBorder('none', 'none', 'none', 'thin', \PHPExcel_Style_Border::BORDER_THIN);
                                    });
                                    $sheet->getStyle('N'.$i)->getAlignment()->setWrapText(true);

                                    $sheet->cells('O'.$i, function($cells){
                                        $cells->setAlignment('left');
                                        $cells->setValignment('center');
                                        $cells->setBorder('none', 'thin', 'none', 'none', \PHPExcel_Style_Border::BORDER_THIN);
                                    });
                                    $sheet->cells('Q'.$i, function($cells){
                                        $cells->setAlignment('left');
                                        $cells->setValignment('center');
                                        $cells->setBorder('none', 'thin', 'none', 'none', \PHPExcel_Style_Border::BORDER_THIN);
                                    });
                                }
                                // Format style and draw footer with one page
                                if($flag_first_page){
                                    // Total
                                    $sheet->cells('A33:Q33', function($cells){
                                        $cells->setAlignment('center');
                                        $cells->setValignment('center');
                                        $cells->setBorder('thin', 'thin', 'thin', 'thin', \PHPExcel_Style_Border::BORDER_THIN);
                                    });
                                    $sheet->getRowDimension(33)->setRowHeight(32);
                                    $sheet->cells('A33:B33', function($cells){
                                        $cells->setAlignment('center');
                                        $cells->setValignment('center');
                                    });
                                    $sheet->mergeCells('A33:B33');
                                    $sheet->setCellValue('A33', 'Total');
                                    $sheet->getStyle('A33:B33')->applyFromArray(getStyleExcel('styleAllBorder'));
                                    // total_carton_num
                                    $sheet->setCellValue('G33', $inv_no_footer['total_carton_num']);
                                    // total_carton_num
                                    $sheet->setCellValue('H33', 'cartons');
                                    $sheet->getStyle('I33')->applyFromArray(getStyleExcel('styleAllBorder'));
                                    $sheet->getStyle('J33')->applyFromArray(getStyleExcel('styleAllBorder'));
                                    $sheet->getStyle('K33')->applyFromArray(getStyleExcel('styleAllBorder'));
                                    // total_carton_net_weight
                                    $sheet->setCellValue('L33', $inv_no_footer['total_carton_net_weight']);
                                    $sheet->setCellValue('M33', $inv_no_footer['unit_total_net_weight_div']);
                                    $sheet->cells('M33', function($cells){
                                        $cells->setAlignment('center');
                                        $cells->setValignment('center');
                                        $cells->setBorder('thin', 'thin', 'thin', 'none', \PHPExcel_Style_Border::BORDER_THIN);
                                    });
                                    // total_carton_gross_weight
                                    $sheet->setCellValue('N33', $inv_no_footer['total_carton_gross_weight']);
                                    $sheet->setCellValue('O33', $inv_no_footer['unit_total_gross_weight_div']);
                                    $sheet->cells('O33', function($cells){
                                        $cells->setAlignment('center');
                                        $cells->setValignment('center');
                                        $cells->setBorder('thin', 'thin', 'thin', 'none', \PHPExcel_Style_Border::BORDER_THIN);
                                    });
                                    // unit_total_measure_div
                                    $sheet->setCellValue('Q33', $inv_no_footer['unit_total_measure_div']);
                                    // sign_nm
                                    $sheet->setCellValue('L36', $inv_no_footer['sign_nm']);
                                    // position_div
                                    $sheet->setCellValue('L37', $inv_no_footer['position_div']);
                                    // belong_div
                                    $sheet->setCellValue('L38', $inv_no_footer['belong_div']);                                    
                                }
                                else{
                                    $sheet->cells('A37:Q37', function($cells){
                                    $cells->setAlignment('center');
                                    $cells->setValignment('center');
                                    $cells->setBorder('thin', 'none', 'none', 'none', \PHPExcel_Style_Border::BORDER_THIN);
                                    });
                                }                                
                                //**********************************************************************
                                //     End Set Data Detail
                                //**********************************************************************                            
                            });
                        })->store('xlsx', DOWNLOAD_EXCEL_PUBLIC);
                        $fileName       =   $file_name.'.xlsx';
                        $zip_array[]    =   $fileName;
                        $error_flag     =   true;
                    }
                }
            }
            // file name zip
            $zipFileName    =   'PackingList_'.date("YmdHis").'.zip';
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
    * @author      :   ANS810 - 2018/03/13 - create
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
    * get data top
    * -----------------------------------------------
    * @author      :   ANS810 - 2018/03/13 - create
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
                    '1',
                    '1',
                    '1',
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
    * @author      :   ANS810 - 2018/03/13 - create
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
                    $data_Detail[] =  '3';
                }
            }
            return $data_Detail;
        } catch (\Exception $e) {
            return response()->json(['response' => false]);
        }
    }

    /*
    * getDataFooter
    * -----------------------------------------------
    * @author      :   ANS810 - 2018/03/13 - create
    * @param       :   
    * @return      :   format data footer excel
    * @access      :   protected
    * @see         :   remark
    */
    protected function getDataFooter() {
        try {
            $data_Footer = [
                '3',
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
    * get data more page
    * -----------------------------------------------
    * @author      :   ANS810 - 2018/03/27 - create
    * @param       :   
    * @return      :   
    * @access      :   public
    * @see         :   remark
    */
    protected function getDataMorePage($data) {
        try {
            $data_MorePage = [];
            if (!empty($data)) {
                for ($i = 0; $i < count($data); $i++) { 
                    if ($i >= 17) {
                        $data_MorePage[] = $data[$i];
                    }
                }
            }
            return $data_MorePage;
        } catch (\Exception $e) {
            return response()->json(['response' => false]);
        }
    }

}
