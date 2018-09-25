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
class UserMasterExportController extends Controller
{
    protected $file_excel   = 'ユーザーマスタ一覧_';
    /*
     * Header
     * @var array
     */
    private $header = [
        'ユーザーコード ',
        'ユーザー名称和文',
        'ユーザー略称和文',
        'ユーザー名称英文',
        'ユーザー略称英文',
        '権限区分',
        '在職区分',
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
            $sql                =   "SPC_077_USER_MASTER_SEARCH_FND1"; 
            $result             =   Dao::call_stored_procedure($sql, $param, true);
            $result[0]          =   isset($result[0]) ? $result[0] : NULL;
            //width of columns
            $arrWidthColumns    =   array(
                'A'     =>  20,
                'B'     =>  40,
                'C'     =>  30,
                'D'     =>  30,
                'E'     =>  30,
                'F'     =>  20,
                'G'     =>  20,
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
                                $v1['user_cd'], 
                                $v1['user_nm_j'], 
                                $v1['user_ab_j'], 
                                $v1['user_nm_e'], 
                                $v1['user_ab_e'], 
                                $v1['auth_role_div'],
                                $v1['incumbent_div'],
                            ));

                            //even odd row background color
                            if($row % 2 != 0){
                                $sheet->cells('A'.$row.':G'.$row, function($cells) {
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
