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

class StockingSearchExportController extends Controller {
    public $title           = 'Stocking Search';
    public $company         = 'Apel';
    public $description     = '仕入一覧';
    protected $file_excel   = '仕入一覧_';
    /*
     * Header
     * @var array
     */
    private $header = [
        '部品発注書番号',
        '仕入番号',
        '仕入日',
        '仕入先コード',
        '仕入先名',
        '明細No',
        '部品コード',
        '部品名',
        '規格',
        '仕入数量',
        '仕入単価',
        '仕入金額',
        '備考'
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
            $sql    = "SPC_105_STOCKING_SEARCH_FND1";//name stored
            $result = Dao::call_stored_procedure($sql, $param,true);
            $data   = isset($result[0]) ? $result[0] : NULL;
            //width of columns
            $arrWidthColumns     =   array(
                'A'     =>  18,//parts_order_no
                'B'     =>  17,//purchase_no
                'C'     =>  15,//purchase_date
                'D'     =>  17,//supplier_cd
                'E'     =>  35,//supplier_nm
                'F'     =>  10,//purchase_detail_no
                'G'     =>  15,//parts_cd
                'H'     =>  30,//part_nm
                'I'     =>  30,//specification
                'J'     =>  10,//purchase_qty
                'K'     =>  15,//purchase_unit_price
                'L'     =>  15,//purchase_amt
                'M'     =>  35,//remarks
            );

            if (!is_null($data)) {
                $filename    = '仕入一覧_'.date("YmdHis");
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
                        $sheet->getStyle('A1:M1')->applyFromArray($styleAllBorder);
                        $sheet->getStyle('A1:M1')->getFont()->getColor()->setRGB('ffffff');
                        $sheet->cells('A1:M1', function($cells) {
                            $cells->setBackground('#248af0');
                            $cells->setAlignment('center');
                            $cells->setValignment('center');
                        });
                        //write data to excel file.
                        foreach ($data as $k1 => $v1) {
                            $row ++;
                            $sheet->getStyle('A'.$row.':M'.$row)->applyFromArray($styleAllBorder);
                            $sheet->getStyle('A'.$row.':M'.$row)->getAlignment()->setWrapText(true);
                            $sheet->row($row, array(
                                $v1['parts_order_no'], 
                                $v1['purchase_no'], 
                                $v1['purchase_date'], 
                                $v1['supplier_cd'],                               
                                $v1['supplier_nm'], 
                                $v1['purchase_detail_no'],
                                $v1['parts_cd'],
                                $v1['parts_nm'],
                                $v1['specification'],
                                $v1['purchase_qty'],                                
                                $v1['purchase_unit_price'],
                                $v1['purchase_amt'],
                                $v1['remarks']
                            ));
                            //even odd row background color
                            if($row % 2 != 0){
                                $sheet->cells('A'.$row.':M'.$row, function($cells) {
                                    $cells->setBackground('#FFF2CC');
                                });
                            }
                            $sheet->cells('A'.$row.':B'.$row, function($cells) {
                                $cells->setAlignment('left');
                                $cells->setValignment('center');
                            });
                            $sheet->cells('C'.$row.':C'.$row, function($cells) {
                                $cells->setAlignment('center');
                                $cells->setValignment('center');
                            });
                            $sheet->cells('D'.$row.':E'.$row, function($cells) {
                                $cells->setAlignment('left');
                                $cells->setValignment('center');
                            });
                            $sheet->cells('F'.$row.':F'.$row, function($cells) {
                                $cells->setAlignment('right');
                                $cells->setValignment('center');
                            });
                            $sheet->cells('G'.$row.':I'.$row, function($cells) {
                                $cells->setAlignment('left');
                                $cells->setValignment('center');
                            });
                            $sheet->cells('J'.$row.':L'.$row, function($cells) {
                                $cells->setAlignment('right');
                                $cells->setValignment('center');
                            });
                            $sheet->cells('M'.$row.':M'.$row, function($cells) {
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
            return response(array('response'=> false, 'edd'=>$e->getMessage()));
        }
    }
}
