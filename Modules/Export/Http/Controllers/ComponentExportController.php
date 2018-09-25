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
class ComponentExportController extends Controller
{
    protected $file_excel   = '部品マスタ一覧_';
    /*
     * Header
     * @var array
     */
    private $header = [
        '部品コード ',
        '部品名和文',
        '部品名英文',
        '規格名',
        '単位',
        '入数',
        '分類',
        '在庫管理有無',
        '管理方法',
        '発注点',
        'EOQ',
        'メイン発注先コード',
        'メイン発注先名',
        '単価(JPY)',
        '単価(USD)',
        '単価(EUR)',
        '発注ロットサイズ',
        '下限ロットサイズ',
        '上限ロットサイズ',
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
            $sql                =   "SPC_069_COMPONENT_MASTER_SEARCH_FND1"; 
            $result             =   Dao::call_stored_procedure($sql, $param, true);
            $result[0]          =   isset($result[0]) ? $result[0] : NULL;
            //width of columns
            $arrWidthColumns    =   array(
                'A'     =>  15,
                'B'     =>  45,
                'C'     =>  45,
                'D'     =>  30,
                'E'     =>  10,
                'F'     =>  15,
                'G'     =>  10,
                'H'     =>  15,
                'I'     =>  15,
                'J'     =>  15,
                'K'     =>  10,
                'L'     =>  22,
                'M'     =>  30,
                'N'     =>  20,
                'O'     =>  20,
                'P'     =>  20,
                'Q'     =>  20,
                'R'     =>  20,
                'S'     =>  20,
                'T'     =>  30,
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
                        $sheet->getStyle('A1:T1')->applyFromArray($styleAllBorder);
                        $sheet->getStyle('A1:T1')->getFont()->getColor()->setRGB('ffffff');
                        $sheet->cells('A1:T1', function($cells) {
                            $cells->setBackground('#248af0');
                            $cells->setAlignment('center');
                            $cells->setValignment('center');
                        });
                        //write data to excel file
                        foreach ($result[0] as $k1 => $v1) {
                            $row ++;
                            $sheet->getStyle('A'.$row.':T'.$row)->applyFromArray($styleAllBorder);
                            $sheet->getStyle('A'.$row.':T'.$row)->getAlignment()->setWrapText(true);
                            $sheet->row($row, array(
                                $v1['parts_cd'], 
                                $v1['item_nm_j'], 
                                $v1['item_nm_e'], 
                                $v1['specification'], 
                                $v1['unit_qty_div_nm_j'],
                                $v1['contained_qty'],
                                $v1['parts_kind_div_nm_j'],
                                $v1['stock_management_div_nm_j'],
                                $v1['parts_order_div_nm_j'],
                                $v1['order_point_qty'],
                                $v1['economic_order_qty'],
                                $v1['supplier_cd'],
                                $v1['client_nm'],
                                $v1['purchase_unit_price_JPY'],
                                $v1['purchase_unit_price_USD'],
                                $v1['purchase_unit_price_EUR'],
                                $v1['order_lot_qty'],
                                $v1['lower_limit_lot_qty'],
                                $v1['upper_limit_lot_qty'],
                                $v1['remarks'],
                            ));

                            //even odd row background color
                            if($row % 2 != 0){
                                $sheet->cells('A'.$row.':T'.$row, function($cells) {
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
                                $cells->setAlignment('right');
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
                                $cells->setAlignment('right');
                                $cells->setValignment('center');
                            });
                            //align left data
                            $sheet->cells('L'.$row, function($cells) {
                                $cells->setAlignment('left');
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
                                $cells->setAlignment('right');
                                $cells->setValignment('center');
                            });
                            //align left data
                            $sheet->cells('P'.$row, function($cells) {
                                $cells->setAlignment('right');
                                $cells->setValignment('center');
                            });
                             //align left data
                            $sheet->cells('Q'.$row, function($cells) {
                                $cells->setAlignment('right');
                                $cells->setValignment('center');
                            }); //align left data
                            $sheet->cells('R'.$row, function($cells) {
                                $cells->setAlignment('right');
                                $cells->setValignment('center');
                            });
                             //align left data
                            $sheet->cells('S'.$row, function($cells) {
                                $cells->setAlignment('right');
                                $cells->setValignment('center');
                            }); 
                            //align left data
                            $sheet->cells('T'.$row, function($cells) {
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
