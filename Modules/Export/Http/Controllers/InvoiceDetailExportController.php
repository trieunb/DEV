<?php
/**
 *|--------------------------------------------------------------------------
 *| Invoice Export
 *|--------------------------------------------------------------------------
 *| Package       : Apel
 *| @author       : ANS804 - daonx@ans-asia.com
 *| @created date : 2018/03/14
 *| 
 */
namespace Modules\Export\Http\Controllers;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Session, DB, Dao, Button;
use Excel, PHPExcel_Style_Border;
use Modules\Common\Http\Controllers\CommonController as common;

class InvoiceDetailExportController extends Controller {
    public $title           = 'Invoice';
    public $company         = 'Apel';
    public $description     = 'Invoice一覧';

    private $header = [
        'Shipping Mark1',
        'Shipping Mark2',
        'Shipping Mark3',
        'Shipping Mark4',
    ];
    /*
     * Create Shipping Mark Excel
     * -----------------------------------------------
     * @author      :   ANS804 - 2018/03/02 - create
     * @param       :
     * @return      :
     * @access      :   public
     * @see         :   remark
     */
    public function postPrintMark(Request $request) {
        try {
            $data     = $request->all();
            $data['cre_user_cd']    =  \GetUserInfo::getInfo('user_cd');
            $data['cre_prg_cd']     =  '019_shipping_mark_excel';
            $data['cre_ip']         =  \GetUserInfo::getInfo('user_ip');
            $sql      = "SPC_019_SHIPPING_MARK_EXPORT_FND1";
            $result   = Dao::call_stored_procedure($sql, $data, true);
            // return $result;
            
            $fileName = 'mark_'.$data['inv_no'];
            $response = true;

            if (!isset($result[1]) && empty($result[1])) {
                 return response(array(
                                    'response'      => true,
                                    'error_cd'      => 'E005'
                                )
                        );
            }

            if(isset($result[0][0]['Data']) && strtoupper($result[0][0]['Data']) == 'EXCEPTION') {
                return response(array(
                                    'response'  => false,
                                    'error'     => $result[0][0]['Message']
                                )
                        );
            }
            if ( !is_null($result) ) {
                //width of columns of mark data
                $arrWidthColumnMarkData     =   [
                    'A'     =>  22,
                    'B'     =>  22,
                    'C'     =>  22,
                    'D'     =>  22,
                ];

                //width of columns of mark body
                $arrWidthColumnMarkBody     =   [
                    'A'     =>  38.87,
                    'B'     =>  26.12,
                    'C'     =>  25.5,
                ];

                $marginPage =   [0.6, 0.6, 0.6, 0.6];

                $data       = $result[1];
                // **********************************************************************
                //      Export Excel
                // **********************************************************************
                
                Excel::create($fileName, function($excel) use ($data, $fileName, $arrWidthColumnMarkData, $arrWidthColumnMarkBody, $marginPage) {
                    $excel->sheet('Markデータ', function($sheet) use ($data, $arrWidthColumnMarkData) {
                        $sheet->setWidth($arrWidthColumnMarkData);
                         // set font for excel
                        $sheet->getDefaultStyle()->getFont()->setName('ＭＳ Ｐゴシック');

                        $row = 1;
                        //create and format header
                        $sheet->row($row, $this->header);
                        $sheet->cells('A1:D1', function($cells) {
                            $cells->setAlignment('left');
                        });
                        //write data to excel file.
                        $row++;
                        $sheet->row($row, array(
                            $data[0]['shipping_mark1'], 
                            $data[0]['shipping_mark2'], 
                            $data[0]['total_carton_num'], 
                            $data[0]['shipping_mark4'], 
                        ));
                        
                        $sheet->setOrientation('portrait');
                        //focus on A1 cell
                        $sheet->setSelectedCells('A1');
                    });

                    $excel->sheet('Mark本体', function($sheet) use ($data, $fileName, $arrWidthColumnMarkBody, $marginPage) {
                        // set font for excel
                        $sheet->getDefaultStyle()->getFont()->setName('Century');
                        // set width for colum excel
                        $sheet->setWidth($arrWidthColumnMarkBody);
                        // set Gridlines
                        $sheet->setShowGridlines(false);
                        //set margin for page
                        $sheet->setPageMargin($marginPage);

                        $pos = 0;
                        // pagination
                        for ($i = 0; $i < count($data); $i++) { 
                            //
                                $page = $i + 1;
                                ////////////////////////////////////////////////////////////////////////////////////////
                                $pos1 = $pos + 1; 
                                // Shipping Mark1
                                $sheet->setCellValue('A'.$pos1, $data[0]['shipping_mark1']);
                                $sheet->mergeCells('A'.$pos1.':C'.$pos1);
                                $sheet->cells('A'.$pos1.':C'.$pos1, function($cells){
                                    $cells->setAlignment('center');  
                                    $cells->setValignment('bottom');   
                                    $cells->setFont(array(
                                        'size' => 80,
                                        'bold' => true
                                    ));
                                });

                                ////////////////////////////////////////////////////////////////////////////////////////
                                $pos2 = $pos + 2;                 
                                // Shipping Mark2
                                $sheet->setCellValue('A'.$pos2, $data[0]['shipping_mark2']);
                                $sheet->mergeCells('A'.$pos2.':C'.$pos2);
                                $sheet->cells('A'.$pos2.':C'.$pos2, function($cells){
                                    $cells->setAlignment('center');  
                                    $cells->setValignment('bottom');
                                    $cells->setFont(array(
                                        'size' => 70,
                                    ));
                                });

                                ////////////////////////////////////////////////////////////////////////////////////////
                                $pos3 = $pos + 3;                   
                                // Shipping Mark3
                                $sheet->setCellValue('A'.$pos3, 'C/NO.');
                                $sheet->cells('A'.$pos3.':C'.$pos3, function($cells){
                                    $cells->setAlignment('right');  
                                    $cells->setValignment('bottom');   
                                    $cells->setFont(array(
                                        'size' => 70,
                                        'bold' => true
                                    ));
                                });

                                // Shipping Mark4 (pagi)
                                $sheet->setCellValue('B'.$pos3, $page." /");
                                // $sheet->setCellValue('B'.$pos3, $data[$i]['carton_number']." /");
                                $sheet->cells('B'.$pos3.':B'.$pos3, function($cells){
                                    $cells->setAlignment('right');  
                                    $cells->setValignment('bottom');   
                                    $cells->setFont(array(
                                        'size' => 70,
                                        'bold' => true
                                    ));
                                });
                                // Shipping Mark4 (total)
                                $sheet->setCellValue('C'.$pos3, $data[0]['total_carton_num']);
                                $sheet->cells('C'.$pos3, function($cells){
                                    $cells->setAlignment('left');  
                                    $cells->setValignment('bottom');   
                                    $cells->setFont(array(
                                        'size' => 70,
                                        'bold' => true
                                    ));
                                });

                                ////////////////////////////////////////////////////////////////////////////////////////
                                $pos4 = $pos + 4;
                                // Shipping Mark5
                                $sheet->setCellValue('A'.$pos4, $data[0]['shipping_mark4']);
                                $sheet->mergeCells('A'.$pos4.':C'.$pos4);
                                $sheet->cells('A'.$pos4.':C'.$pos4, function($cells){
                                    $cells->setAlignment('center');  
                                    $cells->setValignment('bottom');   
                                    $cells->setFont(array(
                                        'size' => 56,
                                        'bold' => true
                                    ));
                                });

                                ////////////////////////////////////////////////////////////////////////////////////////
                                $pos5 = $pos + 5;
                                // $sheet->setBorder('A'.$pos5.':C'.$pos5, \PHPExcel_Style_Border::BORDER_MEDIUM, "#000000");
                                $sheet->cell('A'.$pos5.':C'.$pos5, function($cell){
                                    $cell->setBorder('none','none','medium','none');
                                });
                                ////////////////////////////////////////////////////////////////////////////////////////
                                $pos6 = $pos + 6;

                                ////////////////////////////////////////////////////////////////////////////////////////
                                $pos7 = $pos + 7;
                                // Shipping Mark1
                                $sheet->setCellValue('A'.$pos7, $data[0]['shipping_mark1']);
                                $sheet->mergeCells('A'.$pos7.':C'.$pos7);
                                $sheet->cells('A'.$pos7.':C'.$pos7, function($cells){
                                    $cells->setAlignment('center');  
                                    $cells->setValignment('bottom');   
                                    $cells->setFont(array(
                                        'size' => 80,
                                        'bold' => true
                                    ));
                                });
                                $sheet->getRowDimension($pos4)->setRowHeight(105);

                                ////////////////////////////////////////////////////////////////////////////////////////
                                $pos8 = $pos + 8;                 
                                // Shipping Mark2
                                $sheet->setCellValue('A'.$pos8, $data[0]['shipping_mark2']);
                                $sheet->mergeCells('A'.$pos8.':C'.$pos8);
                                $sheet->cells('A'.$pos8.':C'.$pos8, function($cells){
                                    $cells->setAlignment('center');  
                                    $cells->setValignment('bottom');
                                    $cells->setFont(array(
                                        'size' => 70,
                                    ));
                                });

                                ////////////////////////////////////////////////////////////////////////////////////////
                                $pos9 = $pos + 9;                   
                                // Shipping Mark9
                                $sheet->setCellValue('A'.$pos9, 'C/NO.');
                                $sheet->cells('A'.$pos9.':C'.$pos9, function($cells){
                                    $cells->setAlignment('right');  
                                    $cells->setValignment('bottom');   
                                    $cells->setFont(array(
                                        'size' => 70,
                                        'bold' => true
                                    ));
                                });

                                // Shipping Mark4 (pagi)
                                $sheet->setCellValue('B'.$pos9, $page." /");
                                // $sheet->setCellValue('B'.$pos9, $data[$i]['carton_number']." /");
                                $sheet->cells('B'.$pos9.':B'.$pos9, function($cells){
                                    $cells->setAlignment('right');  
                                    $cells->setValignment('bottom');   
                                    $cells->setFont(array(
                                        'size' => 70,
                                        'bold' => true
                                    ));
                                });
                                // Shipping Mark4 (total)
                                $sheet->setCellValue('C'.$pos9, $data[0]['total_carton_num']);
                                $sheet->cells('C'.$pos9, function($cells){
                                    $cells->setAlignment('left');  
                                    $cells->setValignment('bottom');   
                                    $cells->setFont(array(
                                        'size' => 70,
                                        'bold' => true
                                    ));
                                });

                                ////////////////////////////////////////////////////////////////////////////////////////
                                $pos10 = $pos + 10;
                                // Shipping Mark5
                                $sheet->setCellValue('A'.$pos10, $data[0]['shipping_mark4']);
                                $sheet->mergeCells('A'.$pos10.':C'.$pos10);
                                $sheet->cells('A'.$pos10.':C'.$pos10, function($cells){
                                    $cells->setAlignment('center');  
                                    $cells->setValignment('bottom');   
                                    $cells->setFont(array(
                                        'size' => 56,
                                        'bold' => true
                                    ));
                                });

                                $sheet->getRowDimension($pos1)->setRowHeight(105);
                                $sheet->getRowDimension($pos2)->setRowHeight(85);
                                $sheet->getRowDimension($pos3)->setRowHeight(85);
                                $sheet->getRowDimension($pos4)->setRowHeight(80);

                                $sheet->getRowDimension($pos5)->setRowHeight(45);
                                $sheet->getRowDimension($pos6)->setRowHeight(45);

                                $sheet->getRowDimension($pos7)->setRowHeight(105);
                                $sheet->getRowDimension($pos8)->setRowHeight(85);
                                $sheet->getRowDimension($pos9)->setRowHeight(85);
                                $sheet->getRowDimension($pos10)->setRowHeight(80);


                                $sheet->getStyle('A'.$pos1)->getAlignment()->setShrinkToFit(true);
                                $sheet->getStyle('A'.$pos2)->getAlignment()->setShrinkToFit(true);
                                $sheet->getStyle('B'.$pos3)->getAlignment()->setShrinkToFit(true);
                                $sheet->getStyle('C'.$pos3)->getAlignment()->setShrinkToFit(true);
                                $sheet->getStyle('A'.$pos4)->getAlignment()->setShrinkToFit(true);

                                $sheet->getStyle('A'.$pos7)->getAlignment()->setShrinkToFit(true);
                                $sheet->getStyle('A'.$pos8)->getAlignment()->setShrinkToFit(true);
                                $sheet->getStyle('B'.$pos9)->getAlignment()->setShrinkToFit(true);
                                $sheet->getStyle('C'.$pos9)->getAlignment()->setShrinkToFit(true);
                                $sheet->getStyle('A'.$pos10)->getAlignment()->setShrinkToFit(true);
                            //
                                ////////////////////////////////////////////////////////////////////////////////////////
                                $pos = $pos + 10;
                                $sheet->setSelectedCells('A1');
                        }
                        $sheet->getDefaultStyle()->getFont()->setName('ＭＳ Ｐゴシック');
                    });
                    $excel->setActiveSheetIndex(0);
                })->store('xlsx', DOWNLOAD_EXCEL_PUBLIC);

                $fileName       =   $fileName.'.xlsx'; 

                return response([
                    'response'  =>  $response, 
                    'fileName'  =>  DOWNLOAD_EXCEL.$fileName,
                    'error_cd'  => ''
                ]); 
            } else {
                return response(array('response'=> false));
            }

            
        } catch (\Exception $e) {
            return response(array('response'=> $e->getMessage()));
        }
    }
}
