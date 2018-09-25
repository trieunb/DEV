<?php
/**
*|--------------------------------------------------------------------------
*| Shifting Request Search Export
*|--------------------------------------------------------------------------
*| Package       : Apel
*| @author       : ANS810 - dungnn@ans-asia.com
*| @created date : 2018/03/28
*| 
*/
namespace Modules\Export\Http\Controllers;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Session, DB, Dao, Button;
use Excel, PHPExcel_Worksheet_Drawing;
use Modules\Common\Http\Controllers\CommonController as common;
class ShiftingRequestSearchExportController extends Controller
{
    protected $file_excel   = 'ShiftingRequest';
    public $title           = 'Shifting Request Search Report';
    public $company         = 'Apel';
    public $description     = '移動依頼一覧';
    protected $totalLine    = '54';

    /*
     * Header
     * @var array
     */
    private $header = [
        '移動依頼No',
        '製造指示書番号',
        '登録日',
        '移動希望日',
        '品目コード',
        '品目名',
        '規格名',
        '移動依頼数',
        'ステータス',  
    ];
   
    /*
     * postShiftingRequestSearchOutput
     * -----------------------------------------------
     * @author      :   ANS831 - 2018/03/28 - create
     * @param       :
     * @return      :
     * @access      :   public
     * @see         :   remark
     */
    public function postShiftingRequestSearchOutput(Request $request) {
        try {
            $param         = $request->all();

            $sql            = "SPC_042_SHIFTING_REQUEST_LIST_SEARCH_FND1";    //name stored

            $result         = Dao::call_stored_procedure($sql, $param,true);

            $result[0]      = isset($result[0]) ? $result[0] : NULL;

            //width of columns
            $arrWidthColumns     =   array(
                'A'     =>  18,
                'B'     =>  20,
                'C'     =>  15,
                'D'     =>  15,
                'E'     =>  15,
                'F'     =>  25,
                'G'     =>  55,
                'H'     =>  15,
                'I'     =>  20,
            );
            if ( !is_null($result[0])) {
                $filename    = '移動依頼一覧_'.date("YmdHis");
                \Excel::create($filename, function($excel) use ($result, $arrWidthColumns) {
                    $excel->sheet('Sheet 1', function($sheet) use ($result, $arrWidthColumns) {
                        $sheet->setAutoSize(true);

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
                        $sheet->getStyle('A1:I1')->applyFromArray($styleAllBorder);
                        $sheet->getStyle('A1:I1')->getFont()->getColor()->setRGB('ffffff');
                        $sheet->cells('A1:I1', function($cells) {
                            $cells->setBackground('#248af0');
                            $cells->setAlignment('center');
                            $cells->setValignment('center');
                        });
                        //write data to excel file.
                        foreach ($result[0] as $k1 => $v1) {
                            $row ++;
                            $sheet->getStyle('A'.$row.':I'.$row)->applyFromArray($styleAllBorder);
                            $sheet->getStyle('A'.$row.':I'.$row)->getAlignment()->setWrapText(true);
                            $sheet->row($row, array(
                                $v1['move_no'],
                                $v1['manufacture_no'],
                                $v1['cre_datetime'],
                                $v1['move_preferred_date'],
                                $v1['item_cd'],
                                $v1['item_nm_j'],
                                $v1['specification'],
                                number_format($v1['move_qty']),                          
                                $v1['move_status_name'],
                            ));
                            //even odd row background color
                            if($row % 2 != 0){
                                $sheet->cells('A'.$row.':I'.$row, function($cells) {
                                    $cells->setBackground('#FFF2CC');
                                });
                            }

                            $sheet->cells('A'.$row.':I'.$row, function($cells) {
                                $cells->setValignment('center');
                            });

                            $sheet->cells('A'.$row, function($cells) {
                                $cells->setAlignment('left');
                            });

                            $sheet->cells('B'.$row, function($cells) {
                                $cells->setAlignment('left');
                            });

                            $sheet->cells('C'.$row, function($cells) {
                                $cells->setAlignment('center');
                            });

                            $sheet->cells('D'.$row, function($cells) {
                                $cells->setAlignment('center');
                            });

                            $sheet->cells('E'.$row, function($cells) {
                                $cells->setAlignment('left');
                            });

                            $sheet->cells('F'.$row, function($cells) {
                                $cells->setAlignment('left');
                            });

                            $sheet->cells('G'.$row, function($cells) {
                                $cells->setAlignment('left');
                            });

                            $sheet->cells('H'.$row, function($cells) {
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
     * download export Excel (Shifting Request)
     * -----------------------------------------------
     * @author      :   ANS831 - 2018/03/29 - create
     * @param       :
     * @return      :
     * @access      :   public
     * @see         :   remark
     */
    public function postShiftingRequestSearchIssue(Request $request) {
        try {
            $data                   =  $request->all();
            //parse json to string
            $data['update_list']    =  json_encode($data['update_list']);
            $data['cre_user_cd']    =  \GetUserInfo::getInfo('user_cd');
            $data['cre_prg_cd']     =  '043_shiting-request-list-excel';
            $data['cre_ip']         =  \GetUserInfo::getInfo('user_ip');
            $sql                    =   "SPC_043_SHIFTING_REQUEST_SEARCH_ACT1";
            $result                 =   Dao::call_stored_procedure($sql, $data, true);
            $response               =   true;
            $error                  =   '';
            $error_cd               =   '';
            $zip_array              =   '';
            $error_flag             =   false;
            $user_create            =   \GetUserInfo::getInfo('user_cd');
            $user_nm_j              =   \GetUserInfo::getInfo('user_nm_j');
            // Do something here
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
                } else {
                    for ($k = 0; $k < count($result[2]); $k++) {
                        //width of columns
                        $arrWidthColumns     =   [
                        'A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z',
                        'AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ',
                        'BA','BB','BC','BD','BE','BF','BG','BH','BI','BJ','BK','BL','BM','BN','BO','BP','BQ','BR','BS','BT','BU','BV','BW','BX','BY','BZ',
                        'CA','CB','CC','CD','CE','CF','CG',
                        ];
                        $arrWidthColumns    = array_fill_keys($arrWidthColumns, 1.57);
                        // get data by key
                        $key            = ['move_no' => $result[2][$k]['move_no']];
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
                            $filename    = '移動依頼票_'.$key['move_no'];

                             \Excel::create($filename, function($excel) use ($data_pagi,$row_data, $header, $arrWidthColumns, $user_create, $user_nm_j) {
                                $excel->sheet('Sheet 1', function($sheet) use ($data_pagi,$row_data, $header, $arrWidthColumns, $user_create, $user_nm_j) {
                                    // Init 
                                    // Vị trí mỗi page ( First postition of every page)
                                    $pos        =   0;
                                    // Sum height header and footer
                                    $page_size  =   12;

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

                                        $sheet->setPageMargin(array(
                                            0.354330708661417, 0.511811023622047, 0.354330708661417, 0.511811023622047
                                        ));
                                        /*********************************************************************
                                        *  HEADER
                                        *********************************************************************/
                                        /*
                                         * create and format header
                                         * row 1 -> 4
                                        */
                                        //1. Set value for shifting request (社内発注書)
                                        //create text header
                                        $textHeaderFormTitle = new \PHPExcel_RichText();
                                        $objBold = $textHeaderFormTitle->createTextRun('移動依頼票');
                                        $objBold->getFont()->setName('ＭＳ Ｐゴシック')
                                                           ->setSize(20)
                                                           ->setBold(true);

                                        $sheet->setCellValue('A'.$pos, $textHeaderFormTitle);
                                        $sheet->mergeCells('A'.$pos.':CG'.$pos);
                                        $sheet->cells('A'.$pos.':H'.$pos, function($cells){
                                            $cells->setValignment('center');
                                            $cells->setAlignment('center');   
                                        });
                                       
                                        $posR2 = $pos+1;
                                        $sheet->getRowDimension($posR2)->setRowHeight(18);

                                        //移動依頼番号:
                                        $sheet->setCellValue('A'.$posR2,'移動依頼番号:');
                                        $sheet->mergeCells('A'.$posR2.':H'.$posR2);
                                        $sheet->cells('A'.$posR2.':H'.$posR2, function($cells){
                                            $cells->setValignment('center');   
                                        });

                                        $sheet->setCellValue('I'.$posR2,$header['move_no']);
                                        $sheet->mergeCells('I'.$posR2.':S'.$posR2);
                                        $sheet->cells('I'.$posR2.':S'.$posR2, function($cells){
                                            $cells->setValignment('center');   
                                        });
                                        $sheet->cells('A'.$posR2.':H'.$posR2, function($cells) {
                                            $cells->setFont(array(
                                                'size'       =>  11,
                                                'bold'       =>  true
                                            ));
                                        });
                                        $sheet->cells('I'.$posR2.':S'.$posR2, function($cells) {
                                            $cells->setFont(array(
                                                'size'       =>  11,
                                                'bold'       =>  false
                                            ));
                                        });

                                        //移動希望日:
                                        $sheet->setCellValue('T'.$posR2,'移動希望日:');
                                        $sheet->mergeCells('T'.$posR2.':Z'.$posR2);
                                        $sheet->cells('T'.$posR2.':Z'.$posR2, function($cells){
                                            $cells->setValignment('center');   
                                        });

                                        $sheet->setCellValue('AA'.$posR2,$header['move_preferred_date']);
                                        $sheet->mergeCells('AA'.$posR2.':AH'.$posR2);
                                        $sheet->cells('AA'.$posR2.':AH'.$posR2, function($cells){
                                            $cells->setValignment('center');   
                                        });
                                        $sheet->cells('T'.$posR2.':Z'.$posR2, function($cells) {
                                            $cells->setFont(array(
                                                'size'       =>  11,
                                                'bold'       =>  true
                                            ));
                                        });
                                        $sheet->cells('AA'.$posR2.':AH'.$posR2, function($cells) {
                                            $cells->setFont(array(
                                                'size'       =>  11,
                                                'bold'       =>  false
                                            ));
                                        });

                                        //発行日:
                                        $sheet->setCellValue('BS'.$posR2,'発行日:');
                                        $sheet->cells('BS'.$posR2, function($cells){
                                            $cells->setValignment('center');  
                                        });
                                        $sheet->setCellValue('BW'.$posR2,$header['sysdatetime']);
                                        $sheet->mergeCells('BW'.$posR2.':CG'.$posR2);
                                        $sheet->cells('BW'.$posR2.':CG'.$posR2, function($cells){
                                            $cells->setValignment('center');   
                                        });
                                        $sheet->cells('BS'.$posR2, function($cells) {
                                            $cells->setFont(array(
                                                'size'       =>  11,
                                                'bold'       =>  true
                                            ));
                                        });
                                        $sheet->cells('BW'.$posR2.':CG'.$posR2, function($cells) {
                                            $cells->setFont(array(
                                                'size'       =>  11,
                                                'bold'       =>  false
                                            ));
                                        });
                                        $posR3 = $pos+2;
                                        $posR4 = $pos+3;
                                        $sheet->getRowDimension($posR3)->setRowHeight(18);
                                        
                                        //製造指示番号:
                                        $sheet->setCellValue('A'.$posR3,'製造指示番号:');
                                        $sheet->mergeCells('A'.$posR3.':H'.$posR3);
                                        $sheet->cells('A'.$posR3.':H'.$posR3, function($cells){
                                            $cells->setValignment('center');   
                                        });

                                        $sheet->setCellValue('I'.$posR3,$header['manufacture_no']);
                                        $sheet->mergeCells('I'.$posR3.':S'.$posR3);
                                        $sheet->cells('I'.$posR3.':S'.$posR3, function($cells){
                                            $cells->setValignment('center');   
                                        });
                                        $sheet->cells('A'.$posR3.':H'.$posR3, function($cells) {
                                            $cells->setFont(array(
                                                'size'       =>  11,
                                                'bold'       =>  true
                                            ));
                                        });
                                        $sheet->cells('I'.$posR3.':S'.$posR3, function($cells) {
                                            $cells->setFont(array(
                                                'size'       =>  11,
                                                'bold'       =>  false
                                            ));
                                        });

                                        //製品名:
                                        $sheet->setCellValue('T'.$posR3,'製品名:');
                                        $sheet->mergeCells('T'.$posR3.':Z'.$posR3);
                                        $sheet->cells('T'.$posR3.':Z'.$posR3, function($cells){
                                            $cells->setValignment('center');  
                                        });

                                        $sheet->setCellValue('AA'.$posR3,$header['item_nm_j']);
                                        $sheet->mergeCells('AA'.$posR3.':BR'.$posR3);
                                        $sheet->getStyle('AA'.$posR3.':BR'.$posR3)->getAlignment()->setWrapText(true);
                                        $sheet->cells('AA'.$posR3.':BR'.$posR3, function($cells){
                                            $cells->setValignment('center');   
                                        });
                                        $sheet->cells('T'.$posR3.':Z'.$posR3, function($cells) {
                                            $cells->setFont(array(
                                                'size'       =>  11,
                                                'bold'       =>  true
                                            ));
                                        });
                                        $sheet->cells('AA'.$posR3.':BR'.$posR3, function($cells) {
                                            $cells->setFont(array(
                                                'size'       =>  11,
                                                'bold'       =>  false
                                            ));
                                        });

                                        //担当者:
                                        $sheet->setCellValue('BS'.$posR3,'担当者:');
                                        $sheet->mergeCells('BS'.$posR3.':BV'.$posR3);
                                        $sheet->cells('BS'.$posR3.':BV'.$posR3, function($cells){
                                            $cells->setValignment('center');   
                                        });

                                        $sheet->setCellValue('BW'.$posR3,$user_nm_j);
                                        $sheet->mergeCells('BW'.$posR3.':CG'.$posR3);
                                        $sheet->cells('BW'.$posR3.':CG'.$posR3, function($cells){
                                            $cells->setValignment('center');   
                                        });
                                        $sheet->cells('BS'.$posR3, function($cells) {
                                            $cells->setFont(array(
                                                'size'       =>  11,
                                                'bold'       =>  true
                                            ));
                                        });
                                        $sheet->cells('CG'.$posR3.':CG'.$posR3, function($cells) {
                                            $cells->setFont(array(
                                                'size'       =>  11,
                                                'bold'       =>  false
                                            ));
                                        });

                                        $sheet->getRowDimension($posR4)->setRowHeight(5);
                                        /*********************************************************************
                                        *  END - HEADER
                                        *********************************************************************/
                                        $posR5 = $pos + 4;

                                        // No
                                        $sheet->setCellValue('A'.$posR5,'No');
                                        $sheet->mergeCells('A'.$posR5.':C'.$posR5);                        
                                        $sheet->cells('A'.$posR5.':C'.$posR5, function($cells){
                                            $cells->setAlignment('center');  
                                            $cells->setValignment('center');   
                                        });
                                        //コード
                                        $sheet->setCellValue('D'.$posR5,'コード');
                                        $sheet->mergeCells('D'.$posR5.':G'.$posR5);                        
                                        $sheet->cells('D'.$posR5.':G'.$posR5, function($cells){
                                            $cells->setAlignment('center');  
                                            $cells->setValignment('center');   
                                        });
                                        //品名
                                        $sheet->setCellValue('H'.$posR5,'品名');
                                        $sheet->mergeCells('H'.$posR5.':AB'.$posR5);                        
                                        $sheet->cells('H'.$posR5.':AB'.$posR5, function($cells){
                                            $cells->setAlignment('left');  
                                            $cells->setValignment('center');   
                                        });
                                        //規格
                                        $sheet->setCellValue('AC'.$posR5,'規格');
                                        $sheet->mergeCells('AC'.$posR5.':AW'.$posR5);                        
                                        $sheet->cells('AC'.$posR5.':AW'.$posR5, function($cells){
                                            $cells->setAlignment('left');  
                                            $cells->setValignment('center');   
                                        });
                                        //数量
                                        $sheet->setCellValue('AX'.$posR5,'数量');
                                        $sheet->mergeCells('AX'.$posR5.':BB'.$posR5);                        
                                        $sheet->cells('AX'.$posR5.':BB'.$posR5, function($cells){
                                            $cells->setAlignment('center');  
                                            $cells->setValignment('center');   
                                        });
                                        //単位
                                        $sheet->setCellValue('BC'.$posR5,'単位');
                                        $sheet->mergeCells('BC'.$posR5.':BF'.$posR5);                        
                                        $sheet->cells('BC'.$posR5.':BF'.$posR5, function($cells){
                                            $cells->setAlignment('center');  
                                            $cells->setValignment('center');   
                                        });
                                        //備考
                                        $sheet->setCellValue('BG'.$posR5,'備考');
                                        $sheet->mergeCells('BG'.$posR5.':CG'.$posR5);  
                                        $sheet->getStyle('BG'.$posR5.':CG'.$posR5)->getAlignment()->setWrapText(true);                      
                                        $sheet->cells('BG'.$posR5.':CG'.$posR5, function($cells){
                                            $cells->setAlignment('center');  
                                            $cells->setValignment('center');   
                                        });

                                        $sheet->cells('A'.$posR5.':CG'.$posR5, function($cells) {
                                                $cells->setFont(array(
                                                    'size'       =>  9,
                                                    'bold'       =>  true
                                                ));
                                            });
                                        /*********************************************************************
                                        *  START - BODY
                                        *********************************************************************/
                                        $posR6 = $pos+5;
                                        //write data to excel file.
                                        foreach ($data_pagi[$i] as $k => $v) {
                                            //     /*
                                            //      * Render data
                                            //     */
                                            $row = $posR6 + $k;
                                            $sheet->getRowDimension($row)->setRowHeight(28);
                                            // for No
                                            $sheet->setCellValue('A'.$row,$v['move_detail_no']);
                                            $sheet->mergeCells('A'.$row.':'.'C'.$row);
                                            $sheet->cells('A'.$row.':'.'C'.$row, function($cells) use($v){
                                                $cells->setAlignment('center');
                                                $cells->setValignment('center');
                                            });

                                            //コード
                                            $sheet->setCellValue('D'.$row,$v['item_cd']);
                                            $sheet->mergeCells('D'.$row.':G'.$row);                        
                                            $sheet->cells('D'.$row.':G'.$row, function($cells) use($v){
                                                $cells->setAlignment('left');  
                                                $cells->setValignment('center');   
                                            });
                                            
                                            //品名
                                            $sheet->setCellValue('H'.$row,$v['item_nm_j']);
                                            $sheet->mergeCells('H'.$row.':AB'.$row);                        
                                            $sheet->cells('H'.$row.':AB'.$row, function($cells) use($v){
                                                $cells->setValignment('center');   
                                            });
                                            
                                             //規格
                                            $sheet->setCellValue('AC'.$row,$v['specification']);
                                            $sheet->mergeCells('AC'.$row.':AW'.$row);                        
                                            $sheet->cells('AC'.$row.':AW'.$row, function($cells) use($v){
                                                $cells->setValignment('center');   
                                            });
                                            
                                            //数量
                                            $sheet->setCellValue('AX'.$row,$v['move_qty']);
                                            $sheet->mergeCells('AX'.$row.':BB'.$row);                        
                                            $sheet->cells('AX'.$row.':BB'.$row, function($cells) use($v){
                                                $cells->setAlignment('right');  
                                                $cells->setValignment('center');   
                                            });

                                            //単位
                                            $sheet->setCellValue('BC'.$row,$v['lib_val_nm_j']);
                                            $sheet->mergeCells('BC'.$row.':BF'.$row);                        
                                            $sheet->cells('BC'.$row.':BF'.$row, function($cells) use($v){
                                                $cells->setAlignment('center');  
                                                $cells->setValignment('center');   
                                            });

                                            //備考
                                            $sheet->setCellValue('BG'.$row,$v['detail_remarks']);
                                            $sheet->mergeCells('BG'.$row.':CG'.$row);                        
                                            $sheet->cells('BG'.$row.':CG'.$row, function($cells) use($v){
                                                $cells->setAlignment('left');  
                                                $cells->setValignment('center');   
                                            });

                                            $sheet->cells('A'.$row.':CG'.$row, function($cells) {
                                                $cells->setFont(array(
                                                    'size'       =>  11,
                                                    'bold'       =>  false
                                                ));
                                            });

                                            // Wraptext for 備考
                                            $sheet->getStyle('H'.$row.':'.'AB'.$row)->getAlignment()->setWrapText(true);
                                            $sheet->getStyle('AC'.$row.':'.'AW'.$row)->getAlignment()->setWrapText(true);
                                            $sheet->getStyle('BG'.$row.':'.'CG'.$row)->getAlignment()->setWrapText(true);

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
                                            $sheet->getRowDimension($j)->setRowHeight(28);
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
                                            $sheet->mergeCells('H'.$j.':'.'AB'.$j);
                                            $sheet->cells('H'.$j.':'.'AB'.$j, function($cells) use($v){
                                                $cells->setValignment('center');   
                                            });
                                            $sheet->mergeCells('AC'.$j.':'.'AW'.$j);
                                            $sheet->cells('AC'.$j.':'.'AW'.$j, function($cells) use($v){
                                                $cells->setValignment('center');   
                                            });
                                            $sheet->mergeCells('AX'.$j.':'.'BB'.$j);
                                            $sheet->cells('AX'.$j.':'.'BB'.$j, function($cells) use($v){
                                                $cells->setAlignment('right');  
                                                $cells->setValignment('center');   
                                            });
                                            $sheet->mergeCells('BC'.$j.':'.'BF'.$j);
                                            $sheet->cells('BC'.$j.':'.'BF'.$j, function($cells) use($v){
                                                $cells->setAlignment('center');
                                                $cells->setValignment('center');
                                            });
                                            $sheet->mergeCells('BG'.$j.':'.'CG'.$j);
                                            $sheet->cells('BG'.$j.':'.'CG'.$j, function($cells) use($v){
                                                $cells->setAlignment('left');
                                                $cells->setValignment('center');
                                            });
                                        }

                                        //set border
                                        $sheet->setBorder('A'.$posB1.':CG'.$posB2, \PHPExcel_Style_Border::BORDER_THIN, "#000000");                      
                                        $sheet->setOrientation('portrait');
                                        //focus on A1 cell
                                        $sheet->setSelectedCells('A1');
                                        /*********************************************************************
                                        *  END - BORDER STYLE
                                        *********************************************************************/   
                                        /*********************************************************************
                                        *  END - BODY
                                        *********************************************************************/
                                        /*********************************************************************
                                        *  START - BORDER STYLE
                                        *********************************************************************/
                                        $posF0  = $pos+20; 
                                        $posF00 = $pos+21; 
                                        $posF1  = $pos+22;
                                        $posF2  = $pos+23;
                                        $posF3  = $pos+25;

                                        // Set row height
                                        $sheet->getRowDimension($posF1)->setRowHeight(18);
                                        $sheet->getRowDimension($posF2)->setRowHeight(18);
                                        $sheet->getRowDimension($posF2+1)->setRowHeight(18);
                                        $sheet->getRowDimension($posF3)->setRowHeight(18);

                                        // 備考
                                        $sheet->setCellValue('A'.$posF0,'備考');
                                        $sheet->mergeCells('A'.$posF0.':C'.$posF0);                        
                                        $sheet->mergeCells('A'.$posF0.':C'.$posF0);                        
                                        $sheet->cells('A'.$posF0.':C'.$posF0, function($cells){
                                            $cells->setAlignment('center');  
                                            $cells->setValignment('center');   
                                        });
                                        $sheet->cells('A'.$posF0.':C'.$posF0, function($cells) {
                                            $cells->setFont(array(
                                                'size'       =>  11,
                                                'bold'       =>  true,                                              
                                            ));
                                        });
                                        $sheet->setCellValue('D'.$posF0,$header['remarks']);
                                        $sheet->mergeCells('D'.$posF0.':CG'.$posF0);                        
                                        $sheet->getStyle('D'.$posF0.':CG'.$posF0)->getAlignment()->setWrapText(true);                  
                                        $sheet->cells('D'.$posF0.':CG'.$posF0, function($cells){
                                            $cells->setAlignment('left');  
                                            $cells->setValignment('top');   
                                        });
                                        $sheet->cells('D'.$posF0.':CG'.$posF0, function($cells) {
                                            $cells->setFont(array(
                                                'size'       =>  11,
                                                'bold'       =>  false,                                              
                                            ));
                                        });

                                        $sheet->getRowDimension($posF00)->setRowHeight(5);
                                        $sheet->setBorder('A'.$posF0.':CG'.$posF0, \PHPExcel_Style_Border::BORDER_THIN, "#000000");
                                        $sheet->getRowDimension($posF0)->setRowHeight(28);

                                        //(倉庫Ａ ⇒ 倉庫Ｂ）
                                        $sheet->setCellValue('A'.$posF2,'（' .$header['out_warehouse_div'].' ⇒ '.$header['in_warehouse_div']. '）');
                                        $sheet->mergeCells('A'.$posF2.':BB'.$posF2);                        
                                        $sheet->mergeCells('A'.$posF2.':BB'.$posF2);                        
                                        $sheet->cells('A'.$posF1.':BB'.$posF1, function($cells){
                                            $cells->setAlignment('left');  
                                            $cells->setValignment('bottom');   
                                        });
                                        $sheet->cells('A'.$posF2.':BB'.$posF2, function($cells) {
                                            $cells->setFont(array(
                                                'size'       =>  12,
                                                'bold'       =>  true,                                              
                                            ));
                                        });

                                        // 印 1
                                        $sheet->setCellValue('BG'.$posF2,'印');
                                        $sheet->mergeCells('BG'.$posF1.':BM'.$posF1);                        
                                        $sheet->mergeCells('BG'.$posF2.':BM'.$posF3);                        
                                        $sheet->cells('BG'.$posF1.':BM'.$posF1, function($cells){
                                            $cells->setAlignment('center');  
                                            $cells->setValignment('center');   
                                        });
                                        $sheet->cells('BG'.$posF2.':BM'.$posF3, function($cells) use($v){
                                            $cells->setAlignment('center');  
                                            $cells->setValignment('center');   
                                        });
                                        $sheet->cells('BG'.$posF2.':BM'.$posF3, function($cells) {
                                            $cells->setFont(array(
                                                'size'       =>  12,
                                                'bold'       =>  true,    
                                                'color' => array('rgb' => 'C0C0C0'),                                          
                                            ));
                                        });
                                        $sheet->setBorder('BG'.$posF1.':BM'.$posF3, \PHPExcel_Style_Border::BORDER_THIN, "#000000");

                                        //印 2
                                        $sheet->setCellValue('BQ'.$posF2,'印');
                                        $sheet->mergeCells('BQ'.$posF1.':BW'.$posF1);                        
                                        $sheet->mergeCells('BQ'.$posF2.':BW'.$posF3);        
                                        $sheet->cells('BQ'.$posF1.':BW'.$posF1, function($cells){
                                            $cells->setAlignment('center');
                                            $cells->setValignment('center');
                                        });
                                        $sheet->cells('BQ'.$posF2.':BW'.$posF3, function($cells) use($v){
                                            $cells->setAlignment('center');  
                                            $cells->setValignment('center');   
                                        });
                                        $sheet->cells('BQ'.$posF2.':BW'.$posF3, function($cells) {
                                            $cells->setFont(array(
                                                'size'       =>  12,
                                                'bold'       =>  false, 
                                                'color' => array('rgb' => 'C0C0C0'),                                             
                                            ));
                                        });
                                        $sheet->setBorder('BQ'.$posF1.':BW'.$posF3, \PHPExcel_Style_Border::BORDER_THIN, "#000000");

                                        //印 3
                                        $sheet->setCellValue('CA'.$posF2,'印');
                                        $sheet->mergeCells('CA'.$posF1.':CG'.$posF1); 
                                        $sheet->mergeCells('CA'.$posF2.':CG'.$posF3);
                                        $sheet->cells('CA'.$posF2.':CG'.$posF3, function($cells) use($v){
                                            $cells->setAlignment('center');  
                                            $cells->setValignment('center');   
                                        });
                                        $sheet->cells('CA'.$posF2.':CG'.$posF3, function($cells) {
                                            $cells->setFont(array(
                                                'size'       =>  12,
                                                'bold'       =>  true,  
                                                'color' => array('rgb' => 'C0C0C0'),                                           
                                            ));
                                        });
                                        $sheet->setBorder('CA'.$posF1.':CG'.$posF3, \PHPExcel_Style_Border::BORDER_THIN, "#000000");

                                        // Page1/total page
                                        $sheet->setCellValue('AM26','1/'.count($data_pagi));
                                        $sheet->cells('AM26', function($cells) {
                                            $cells->setFont(array(
                                                'size'       =>  12,
                                                'bold'       =>  false
                                            ));
                                        });
                                        if(count($data_pagi) == 2){
                                             // Page2/total page
                                            $sheet->setCellValue('AM54','2/'.count($data_pagi));
                                            $sheet->cells('AM54', function($cells) {
                                                $cells->setFont(array(
                                                    'size'       =>  12,
                                                    'bold'       =>  false
                                                ));
                                            });
                                        }
                                        /*********************************************************************
                                        *  END - BORDER STYLE
                                        *********************************************************************/ 
                                        $sheet->setOrientation('landscape');
                                    }
                                });
                            })->store('xlsx', DOWNLOAD_EXCEL_PUBLIC);    
                            $filename       =   $filename.'.xlsx';   
                            // var_dump($filename)  ;
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
            $zipFileName    =   '移動依頼票_'.date("YmdHis").'.zip';

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
     * @author      :   ANS810 - 2018/03/29 - create
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
     * @author      :   ANS831 - 2018/03/29 - create
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
            ];
            return $data_Footer;
        } catch (\Exception $e) {
            return response()->json(['response' => false]);
        }
    }
    /*
     * getDataDetail
     * -----------------------------------------------
     * @author      :   ANS831 - 2018/03/29 - create
     * @param       :   
     * @return      :   
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