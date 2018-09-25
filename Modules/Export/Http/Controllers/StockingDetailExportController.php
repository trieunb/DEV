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
use Excel, Dao;
use Modules\Common\Http\Controllers\CommonController as common;
class StockingDetailExportController extends Controller
{
    /*
     * Header
     * @var array
     */
    private $header1 = [
        '部品コード',
        '部品名',
        '',
        '規格',
        '',
        '発注数',
        '未入庫数',
        '入庫数',
        '単価',
        '金額',
    ];
    private $header2 = [
        '仕入先コード',
        '仕入先名',
        '発注日',
        '発注番号',
        '製造指示番号',
        '備考',
        '',
        '',
        '',
        '受入状況',
    ];
   
    /*
     * postShiftingRequestSearchOutput
     * -----------------------------------------------
     * @author      :   ANS804 - 2018/05/07 - create
     * @param       :
     * @return      :
     * @access      :   public
     * @see         :   remark
     */
    public function postExportExcel(Request $request) {
        try {
            $param           = $request->all();
            
            $sql             = "SPC_038_STOCKING_DETAIL_FND1";    //name stored
            
            $result          = Dao::call_stored_procedure($sql, $param,true);
            
            $result[0]       = isset($result[0]) ? $result[0] : NULL;
            
            //width of columns
            $arrWidthColumns =   array(
                'A'     =>  20,
                'B'     =>  45,
                'C'     =>  15,
                'D'     =>  20,
                'E'     =>  20,
                'F'     =>  15,
                'G'     =>  15,
                'H'     =>  15,
                'I'     =>  15,
                'J'     =>  20,
            );
            
            if ( !is_null($result[0])) {
                $filename    = '仕入入力_'.date("YmdHis");
                \Excel::create($filename, function($excel) use ($result, $arrWidthColumns) {
                    $excel->sheet('Sheet 1', function($sheet) use ($result, $arrWidthColumns) {
                        $sheet->setAutoSize(true);
                        $sheet->setWidth($arrWidthColumns);

                        //BORDER STYLE
                        $styleAllBorder = array(
                            'borders' => array(
                                'allborders' => array(
                                    'style' => \PHPExcel_Style_Border::BORDER_THIN,
                                    'color' => array('rgb' => '000000'),
                                )
                            )
                        );

                        // SET ROW 1
                        $row1 = 1;
                        //create and format header1
                        $sheet->row($row1, $this->header1);

                        // SET ROW 1
                        $row2 = 2;
                        //create and format header2
                        $sheet->row($row2, $this->header2);

                        // MERGER CELL
                        // row 1
                        $sheet->mergeCells('B'.$row1.':C'.$row1);
                        $sheet->mergeCells('D'.$row1.':E'.$row1);
                        // row 2
                        $sheet->mergeCells('F'.$row2.':I'.$row2);

                        //SET FORMAT
                        $sheet->getStyle('A1:J2')->applyFromArray($styleAllBorder);
                        $sheet->getStyle('A1:J2')->getFont()->getColor()->setRGB('ffffff');
                        $sheet->cells('A1:J2', function($cells) {
                            $cells->setBackground('#248af0');
                            $cells->setAlignment('center');
                            $cells->setValignment('center');
                        });

                        $row = $row1;
                        //write data to excel file.
                        foreach ($result[0] as $k1 => $v1) {
                            $row ++;
                            //SET VALUE FOR LINE 1
                            // line 1
                            $line1 = $row*2 - 1;

                            $sheet->getStyle('A'.$line1.':J'.$line1)->applyFromArray($styleAllBorder);
                            $sheet->getStyle('A'.$line1.':J'.$line1)->getAlignment()->setWrapText(true);
                            $sheet->row($line1, array(
                                $v1['parts_cd'],
                                $v1['parts_nm'],
                                '',
                                $v1['specification'],
                                '',
                                $v1['parts_order_qty'],
                                $v1['parts_not_yet_receipt_qty'],
                                $v1['parts_receipt_qty'],                          
                                $v1['unit_price'],
                                $v1['parts_purchase_actual_amount'],
                            ));
                            $sheet->mergeCells('B'.$line1.':C'.$line1);
                            $sheet->mergeCells('D'.$line1.':E'.$line1);

                            //set align
                            $sheet->cells('A'.$line1.':E'.$line1, function($cells) {
                                $cells->setAlignment('left');
                                $cells->setValignment('center');
                            }); 
                            $sheet->cells('F'.$line1.':J'.$line1, function($cells) {
                                $cells->setAlignment('right');
                                $cells->setValignment('center');
                            });

                            //SET VALUE FOR LINE 2
                            // line 2
                            $line2 = $row*2;

                            $sheet->getStyle('A'.$line2.':J'.$line2)->applyFromArray($styleAllBorder);
                            $sheet->getStyle('A'.$line2.':J'.$line2)->getAlignment()->setWrapText(true);
                            $sheet->row($line2, array(
                                $v1['supplier_cd'],
                                $v1['supplier_nm'],
                                $v1['order_date'],
                                $v1['parts_order_no'],
                                $v1['manufacture_no'],
                                $v1['remarks'],
                                '',
                                '',
                                '',
                                $v1['acceptance_status_nm'],
                            ));
                            $sheet->mergeCells('F'.$line2.':I'.$line2);

                            //set align
                            $sheet->cells('A'.$line2.':B'.$line2, function($cells) {
                                $cells->setAlignment('left');
                                $cells->setValignment('center');
                            }); 
                            $sheet->cells('C'.$line2, function($cells) {
                                $cells->setAlignment('center');
                                $cells->setValignment('center');
                            });
                            $sheet->cells('D'.$line2.':J'.$line2, function($cells) {
                                $cells->setAlignment('left');
                                $cells->setValignment('center');
                            });

                            //EVEN ODD ROW BACKGROUND COLOR
                            if($row % 2 != 0){
                                $sheet->cells('A'.$line1.':J'.$line2, function($cells) {
                                    $cells->setBackground('#FFF2CC');
                                });
                            }
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