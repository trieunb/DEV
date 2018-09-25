<?php
/**
*|--------------------------------------------------------------------------
*| Stock Export
*|--------------------------------------------------------------------------
*| Package       : Apel
*| @author       : ANS796 - tuannt@ans-asia.com
*| @created date : 2018/01/11
*| 
*/
namespace Modules\Export\Http\Controllers;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Excel, PHPExcel_Worksheet_Drawing;
use Session, DB, Dao, Button;
use Modules\Common\Http\Controllers\CommonController as common;
class StockIputOutputSearchExportController extends Controller
{
    /*
     * Header
     * @var array
     */
    private $header = [
        '入出庫No',
        '入出庫区分',
        '入出庫日',
        '入力種別',
        '倉庫コード',
        '倉庫名',
        '品目コード',
        '品目名',
        '規格',
        'シリアル',
        '数量',
        '摘要'
    ];
    /**
    * stock input ouput export
    * -----------------------------------------------
    * @author      :   ANS806 - 2017/12/05 - create
    * @param       :
    * @return      :   mixed
    * @access      :   public
    * @see         :   remark
    */
    public function postStockInputOutput(Request $request) {
        try {
            $param              =   $request->all();
            $sql                =   "SPC_049_INPUT_OUTPUT_EXPORT_FND2"; 
            $result             =   Dao::call_stored_procedure($sql, $param, true);
            $result[0]          = isset($result[0]) ? $result[0] : NULL;
            //width of columns
            $arrWidthColumns    =   array(
                'A'     =>  18,
                'B'     =>  15,
                'C'     =>  15,
                'D'     =>  20,
                'E'     =>  15,
                'F'     =>  15,
                'G'     =>  15,
                'H'     =>  40,
                'I'     =>  40,
                'J'     =>  15,
                'K'     =>  15,
                'L'     =>  40,
            );
            if ( !is_null($result[0])) {
                $filename    = '入出庫一覧_'.date("YmdHis");
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
                                $v1['in_out_no'], 
                                $v1['in_out_nm'], 
                                $v1['in_out_date'], 
                                $v1['in_out_data_nm'], 
                                $v1['warehouse_div'], 
                                $v1['warehouse_nm'],
                                $v1['item_cd'],
                                $v1['item_nm_j'],
                                $v1['specification'],
                                $v1['serial_no'],
                                $v1['in_out_qty'],
                                $v1['detail_remarks'],
                            ));
                            //even odd row background color
                            if($row % 2 != 0){
                                $sheet->cells('A'.$row.':L'.$row, function($cells) {
                                    $cells->setBackground('#FFF2CC');
                                });
                            }
                            //align left data
                            $sheet->cells('A'.$row.':B'.$row, function($cells) {
                                $cells->setAlignment('left');
                                $cells->setValignment('center');
                            });
                            //align left data
                            $sheet->cells('D'.$row.':I'.$row, function($cells) {
                                $cells->setAlignment('left');
                                $cells->setValignment('center');
                            });
                            //align left data
                            $sheet->cells('L'.$row, function($cells) {
                                $cells->setAlignment('left');
                                $cells->setValignment('center');
                            });
                            
                            //align center data
                            $sheet->cells('C'.$row, function($cells) {
                                $cells->setAlignment('center');
                                $cells->setValignment('center');
                            });
                            //align right data
                            $sheet->cells('J'.$row, function($cells) {
                                $cells->setAlignment('right');
                                $cells->setValignment('center');
                            });
                            //align left data
                            $sheet->cells('K'.$row, function($cells) {
                                $cells->setAlignment('right');
                                $cells->setValignment('center');
                            });
                            // $sheet->getStyle('B'.$row)->getAlignment()->setWrapText(false);
                            // $sheet->getStyle('F'.$row)->getAlignment()->setWrapText(false);
                            // $sheet->getStyle('H'.$row)->getAlignment()->setWrapText(false);
                            // $sheet->getStyle('I'.$row)->getAlignment()->setWrapText(false);
                            // $sheet->getStyle('K'.$row)->getAlignment()->setWrapText(false);
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
