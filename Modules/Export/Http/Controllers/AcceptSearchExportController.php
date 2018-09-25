<?php
/**
 *|--------------------------------------------------------------------------
 *| Internal Order Export
 *|--------------------------------------------------------------------------
 *| Package       : Apel
 *| @author       : ANS804 - daonx@ans-asia.com
 *| @created date : 2018/01/17
 *| 
 */
namespace Modules\Export\Http\Controllers;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Common\Http\Controllers\CommonController as common;
use Excel;
use Session, DB, Dao, Button;

class AcceptSearchExportController extends Controller {
    public $title           = 'Accept';
    public $company         = 'Apel';
    public $description     = '受注一覧';
    /*
     * Header
     * @var array
     */
    private $header = [
        '受注No',
        '受注日',
        '行番号',
        '取引先名',
        '国',
        'Code',
        'Item Name',
        'Unit Price',
        'Q\'ty',
        'Cur',
        'Amount',
        'ステータス'
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
            $sql    = "SPC_006_ACCEPT_SEARCH_FND1";//name stored
            $result = Dao::call_stored_procedure($sql, $param,true);
            $data   = isset($result[0]) ? $result[0] : NULL;
            
            //width of columns
            $arrWidthColumns     =   array(
                'A'     =>  15,
                'B'     =>  15,
                'C'     =>  10,
                'D'     =>  37,
                'E'     =>  7,
                'F'     =>  8,
                'G'     =>  120,
                'H'     =>  15,
                'I'     =>  10,
                'J'     =>  5,
                'K'     =>  20,
                'L'     =>  15,
            );

            if (!is_null($data)) {
                $filename    = '受注一覧'.date("YmdHis");
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
                        $sheet->getStyle('A1:L1')->applyFromArray($styleAllBorder);
                        $sheet->getStyle('A1:L1')->getFont()->getColor()->setRGB('ffffff');
                        $sheet->cells('A1:L1', function($cells) {
                            $cells->setBackground('#248af0');
                            $cells->setAlignment('center');
                            $cells->setValignment('center');
                        });
                        //write data to excel file.
                        foreach ($data as $k1 => $v1) {
                            $row ++;
                            $sheet->getStyle('A'.$row.':L'.$row)->applyFromArray($styleAllBorder);
                            $sheet->getStyle('A'.$row.':L'.$row)->getAlignment()->setWrapText(true);
                            $sheet->row($row, array(
                                $v1['rcv_no'], 
                                $v1['rcv_date'], 
                                $v1['rcv_detail_no'], 
                                $v1['cust_nm'], 
                                $v1['cust_country_div'],                                
                                $v1['product_cd'], 
                                $v1['description'],
                                $v1['unit_price'],
                                $v1['qty'],
                                $v1['currency_div'],
                                $v1['detail_amt'],                                
                                $v1['rcv_status_div_nm']
                            ));

                            //even odd row background color
                            if($row % 2 != 0){
                                $sheet->cells('A'.$row.':L'.$row, function($cells) {
                                    $cells->setBackground('#FFF2CC');
                                });
                            }
                            
                            $sheet->cells('A'.$row.':A'.$row, function($cells) {
                                $cells->setAlignment('left');
                                $cells->setValignment('center');
                            });
                            
                            $sheet->cells('B'.$row.':B'.$row, function($cells) {
                                $cells->setAlignment('center');
                                $cells->setValignment('center');
                            });
                            
                            $sheet->cells('C'.$row.':C'.$row, function($cells) {
                                $cells->setAlignment('right');
                                $cells->setValignment('center');
                            });
                            
                            $sheet->cells('E'.$row.':E'.$row, function($cells) {
                                $cells->setAlignment('left');
                                $cells->setValignment('center');
                            });
                            
                            $sheet->cells('F'.$row.':F'.$row, function($cells) {
                                $cells->setAlignment('left');
                                $cells->setValignment('center');
                            });

                            $sheet->cells('G'.$row.':G'.$row, function($cells) {
                                $cells->setAlignment('left');
                                $cells->setValignment('center');
                            });

                            $sheet->cells('H'.$row.':I'.$row, function($cells) {
                                $cells->setAlignment('right');
                                $cells->setValignment('center');
                            });

                            $sheet->cells('J'.$row.':J'.$row, function($cells) {
                                $cells->setAlignment('left');
                                $cells->setValignment('center');
                            });

                            $sheet->cells('K'.$row.':K'.$row, function($cells) {
                                $cells->setAlignment('right');
                                $cells->setValignment('center');
                            });

                            $sheet->cells('L'.$row.':L'.$row, function($cells) {
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
}
