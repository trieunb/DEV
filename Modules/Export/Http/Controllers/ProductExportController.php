<?php
/**
*|--------------------------------------------------------------------------
*| shipmnet export
*|--------------------------------------------------------------------------
*| Package       : Apel
*| @author       : ANS342 - khadvt@ans-asi9a.com
*| @created date : 2018/05/29
*| 
*/
namespace Modules\Export\Http\Controllers;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Excel, PHPExcel_Worksheet_Drawing;
use Session, DB, Dao, Button;
use Modules\Common\Http\Controllers\CommonController as common;
class ProductExportController extends Controller
{
    protected $file_excel   = '製品マスタ一覧_';
    /*
     * Header
     * @var array
     */
    private $header = [
        '製品コード ',
        '製品名和文',
        '製品名英文',
        '規格名',
        '単位',
        '内製／外注',
        '在庫管理有無',
        '最終シリアル番号',
        'JANコード',
        'Net Weight',
        'NW単位',
        'Gross Weight',
        'GW単位',
        'Measurement',
        'Measurement単位',
        '備考',
    ];
    /**
    * Shipment output
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
            $sql                =   "SPC_063_PRODUCT_MASTER_SEARCH_FND1"; 
            $result             =   Dao::call_stored_procedure($sql, $param, true);
            $result[0]          =   isset($result[0]) ? $result[0] : NULL;
            //width of columns
            $arrWidthColumns    =   array(
                'A'     =>  15,
                'B'     =>  35,
                'C'     =>  35,
                'D'     =>  30,
                'E'     =>  10,
                'F'     =>  15,
                'G'     =>  20,
                'H'     =>  20,
                'I'     =>  20,
                'J'     =>  12,
                'K'     =>  10,
                'L'     =>  15,
                'M'     =>  10,
                'N'     =>  15,
                'O'     =>  18,
                'P'     =>  20,
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
                        $sheet->getStyle('A1:P1')->applyFromArray($styleAllBorder);
                        $sheet->getStyle('A1:P1')->getFont()->getColor()->setRGB('ffffff');
                        $sheet->cells('A1:P1', function($cells) {
                            $cells->setBackground('#248af0');
                            $cells->setAlignment('center');
                            $cells->setValignment('center');
                        });
                        //write data to excel file
                        foreach ($result[0] as $k1 => $v1) {
                            $row ++;
                            $sheet->getStyle('A'.$row.':P'.$row)->applyFromArray($styleAllBorder);
                            $sheet->getStyle('A'.$row.':P'.$row)->getAlignment()->setWrapText(true);
                            $sheet->row($row, array(
                                $v1['product_cd'], 
                                $v1['item_nm_j'], 
                                $v1['item_nm_e'], 
                                $v1['specification'], 
                                $v1['unit_qty_div_nm_j'],
                                $v1['outsourcing_div_nm_j'],
                                $v1['stock_management_div_nm_j'],
                                $v1['last_serial_no'],
                                $v1['jan_code'],
                                $v1['net_weight'],
                                $v1['unit_net_weight_div_nm_j'],
                                $v1['gross_weight'],
                                $v1['unit_gross_weight_div_nm_j'],
                                $v1['measure'],
                                $v1['unit_measure_div_nm_j'],
                                $v1['remarks'],
                            ));

                            //even odd row background color
                            if($row % 2 != 0){
                                $sheet->cells('A'.$row.':P'.$row, function($cells) {
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
                                $cells->setAlignment('left');
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
                                $cells->setAlignment('left');
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
                            //align left data
                            $sheet->cells('N'.$row, function($cells) {
                                $cells->setAlignment('right');
                                $cells->setValignment('center');
                            });
                            //align left data
                            $sheet->cells('O'.$row, function($cells) {
                                $cells->setAlignment('left');
                                $cells->setValignment('center');
                            });
                            //align left data
                            $sheet->cells('P'.$row, function($cells) {
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
}
