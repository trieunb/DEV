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
use Excel;
use Dao;
use Modules\Common\Http\Controllers\CommonController as common;
class StockSearchExportController extends Controller
{
    public $title           = 'Stock';
    public $company         = 'Apel';
    public $description     = '現在庫一覧';
    /*
     * Header
     * @var array
     */
    private $header = [
        '倉庫コード',
        '倉庫名',
        '品目コード',
        '品目名',
        '規格',
        '現在庫数',
        '有効在庫数'
    ];
    /*
    * getDownloadExcel
    * -----------------------------------------------
    * @author      :   ANS316 - 2017/03/23 - create
    * @param       :
    * @return      :
    * @access      :   public
    * @see         :   remark
    */
    public function postStockOutput(Request $request) {
        try {
            $param          = \Input::all();
            $sql            = "SPC_050_STOCK_SEARCH_FND1";    //name stored
            $result         = Dao::call_stored_procedure($sql, $param,true);
            $result[0]      = isset($result[0]) ? $result[0] : NULL;
            //width of columns
            $arrWidthColumns     =   array(
                'A'     =>  15,
                'B'     =>  30,
                'C'     =>  15,
                'D'     =>  40,
                'E'     =>  25,
                'F'     =>  15,
                'G'     =>  15,
            );
            if ( !is_null($result[0])) {
                $fileName    = '現在庫一覧_'.date("YmdHis");
                \Excel::create($fileName, function($excel) use ($result, $arrWidthColumns) {
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
                        $sheet->getStyle('A1:G1')->applyFromArray($styleAllBorder);
                        $sheet->getStyle('A1:G1')->getFont()->getColor()->setRGB('ffffff');
                        $sheet->cells('A1:G1', function($cells) {
                            $cells->setBackground('#248af0');
                            $cells->setAlignment('center');
                            $cells->setValignment('center');
                        });
                        //write data to excel file
                        foreach ($result[0] as $k1 => $v1) {
                            $row ++;
                            $sheet->getStyle('A'.$row.':G'.$row)->applyFromArray($styleAllBorder);
                            $sheet->getStyle('A'.$row.':G'.$row)->getAlignment()->setWrapText(true);
                            $sheet->row($row, array(
                                $v1['warehouse_cd'], 
                                $v1['warehouse_nm'], 
                                $v1['item_cd'], 
                                $v1['item_nm_j'], 
                                $v1['specification'], 
                                $v1['stock_current_qty'],
                                $v1['stock_available_qty']
                            ));
                            //even odd row background color
                            if($row % 2 != 0){
                                $sheet->cells('A'.$row.':G'.$row, function($cells) {
                                    $cells->setBackground('#FFF2CC');
                                });
                            }
                            //align left data
                            $sheet->cells('A'.$row.':E'.$row, function($cells) {
                                $cells->setAlignment('left');
                                $cells->setValignment('left');
                            });
                            //align right data
                            $sheet->cells('F'.$row.':G'.$row, function($cells) {
                                $cells->setAlignment('right');
                                $cells->setValignment('right');
                            });
                        }
                        $sheet->setOrientation('portrait');
                        //focus on A1 cell
                        $sheet->setSelectedCells('A1');
                    });
                })->store('xlsx', public_path('download/excel'));
                return response(array(
                    'response'  =>  true, 
                    'fileName'  =>  '/download/excel/'.$fileName.'.xlsx'));
            } else {
                return response(array('response'=> false));
            }
        } catch (\Exception $e) {
            return response(array('response'=> false));
        }
    }
}
