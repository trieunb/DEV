<?php
/**
*|--------------------------------------------------------------------------
*| shipmnet export
*|--------------------------------------------------------------------------
*| Package       : Apel
*| @author       : ANS342 - khadvt@ans-asia.com
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
class ComponentListExportController extends Controller
{
    protected $file_excel   = '部品表一覧_';
    /*
     * Header
     * @var array
     */
    private $header = [
        '製品コード',
        '製品名和文',
        '製品規格名',
        '部品コード',
        '部品名和文',
        '部品規格名',
        '必要部品数',
        '開始日',
        '終了日',
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
            $sql                =   "SPC_072_COMPONENT_LIST_SEARCH_FND1"; 
            $result             =   Dao::call_stored_procedure($sql, $param, true);
            $result[0]          =   isset($result[0]) ? $result[0] : NULL;
            //width of columns
            $arrWidthColumns    =   array(
                'A'     =>  15,
                'B'     =>  40,
                'C'     =>  40,
                'D'     =>  15,
                'E'     =>  40,
                'F'     =>  40,
                'G'     =>  15,
                'H'     =>  15,
                'I'     =>  15,
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
                        $sheet->getStyle('A1:I1')->applyFromArray($styleAllBorder);
                        $sheet->getStyle('A1:I1')->getFont()->getColor()->setRGB('ffffff');
                        $sheet->cells('A1:I1', function($cells) {
                            $cells->setBackground('#248af0');
                            $cells->setAlignment('center');
                            $cells->setValignment('center');
                        });
                        //write data to excel file
                        foreach ($result[0] as $k1 => $v1) {
                            $row ++;
                            $sheet->getStyle('A'.$row.':I'.$row)->applyFromArray($styleAllBorder);
                            $sheet->getStyle('A'.$row.':I'.$row)->getAlignment()->setWrapText(true);
                            $sheet->row($row, array(
                                $v1['parent_item_cd'], 
                                $v1['parent_item_nm_j'], 
                                $v1['parent_specification'], 
                                $v1['child_item_cd'], 
                                $v1['child_item_nm_j'],
                                $v1['child_specification'],
                                $v1['child_item_qty'],
                                $v1['apply_st_date'],
                                $v1['apply_ed_date'],
                            ));

                            //even odd row background color
                            if($row % 2 != 0){
                                $sheet->cells('A'.$row.':I'.$row, function($cells) {
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
                                $cells->setAlignment('right');
                                $cells->setValignment('center');
                            });
                            //align left data
                            $sheet->cells('H'.$row, function($cells) {
                                $cells->setAlignment('center');
                                $cells->setValignment('center');
                            });
                            //align left data
                            $sheet->cells('I'.$row, function($cells) {
                                $cells->setAlignment('center');
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
