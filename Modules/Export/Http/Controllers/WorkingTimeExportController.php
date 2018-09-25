<?php
/**
*|--------------------------------------------------------------------------
*| Working time Export
*|--------------------------------------------------------------------------
*| Package       : Apel
*| @author       : ANS796 - tuannt@ans-asia.com
*| @created date : 2017/08/08
*| 
*/
namespace Modules\Export\Http\Controllers;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Excel, PHPExcel_Worksheet_Drawing;
use Session, DB, Dao, Button;
use Modules\Common\Http\Controllers\CommonController as common;
class WorkingTimeExportController extends Controller
{
    public $title           = 'Working time';
    public $company         = 'Apel';
    public $description     = '作業時間一覧';
    /*
     * Header
     * @var array
     */
    private $header = [
        '作業日報番号',
        '製造指示番号',
        '作業実施日',
        '作業担当者コード',
        '作業担当者名',
        '作業時間'
    ];
    /**
    * post ouput export
    * -----------------------------------------------
    * @author      :   ANS806 - 2017/12/05 - create
    * @param       :
    * @return      :   mixed
    * @access      :   public
    * @see         :   remark
    */
    public function postWorkingTimeOutput(Request $request) {
        try {
            $param          =   $request->all();
            $sql            =   "SPC_052_WORKING_TIME_SEARCH_FND1"; 
            $data           =   Dao::call_stored_procedure($sql, $param, true);
            if(isset($data[0]) && !empty($data[0])){
                $workingtime    =   isset($data[0]) ? $data[0] : array();
                $file_name      =   '作業時間一覧_'.date("YmdHis");      //file name
                $sheet_name     =   'Sheet1';                           //default sheet name
                \Excel::create($file_name, function($excel) use ($workingtime, $file_name, $sheet_name) {
                    $excel->sheet($sheet_name, function($sheet) use ($workingtime, $file_name, $sheet_name) {
                        $sheet->loadView('export::workingtime.workingtime', ['workingtime'=> $workingtime]);
                        $styleAllBorder = array(
                            'borders' => array(
                                'allborders' => array(
                                    'style' => \PHPExcel_Style_Border::BORDER_THIN,
                                    'color' => array('rgb' => '000000'),
                                )
                            )
                        );
                        $arrWidthColumns = array(
                            'A'     =>  16,
                            'B'     =>  16,
                            'C'     =>  15,
                            'D'     =>  25,
                            'E'     =>  50,
                            'F'     =>  15,
                        );
                        $row = 1;
                        //set width
                        $sheet->setWidth($arrWidthColumns);
                        //create and format header
                        $sheet->row($row, $this->header);
                        $sheet->getStyle('A1:F1')->applyFromArray($styleAllBorder);
                        $sheet->getStyle('A1:F1')->getFont()->getColor()->setRGB('ffffff');
                        $sheet->cells('A1:F1', function($cells) {
                            $cells->setBackground('#248af0');
                            $cells->setAlignment('center');
                            $cells->setValignment('center');
                        });
                        //write data to excel file
                        foreach ($workingtime as $k1 => $v1) {
                            $row ++;
                            $sheet->getStyle('A'.$row.':F'.$row)->applyFromArray($styleAllBorder);
                            $sheet->getStyle('A'.$row.':F'.$row)->getAlignment()->setWrapText(true);
                            //create detail data
                            $sheet->row($row, array(
                                $v1['work_report_no'], 
                                $v1['manufacture_no'], 
                                $v1['work_date'], 
                                $v1['work_user_cd'], 
                                $v1['user_nm_j'], 
                                $v1['work_div']
                            ));
                            //even odd row background color
                            if($row % 2 != 0){
                                $sheet->cells('A'.$row.':F'.$row, function($cells) {
                                    $cells->setBackground('#FFF2CC');
                                });
                            }
                            //align right data
                            $sheet->cells('A'.$row.':B'.$row, function($cells) {
                                $cells->setAlignment('right');
                                $cells->setValignment('right');
                            });
                            //align left data
                            $sheet->cells('C'.$row.':C'.$row, function($cells) {
                                $cells->setAlignment('center');
                                $cells->setValignment('center');
                            });
                            //align right data
                            $sheet->cells('D'.$row.':D'.$row, function($cells) {
                                $cells->setAlignment('right');
                                $cells->setValignment('right');
                            });
                            //align left data
                            $sheet->cells('E'.$row.':F'.$row, function($cells) {
                                $cells->setAlignment('left');
                                $cells->setValignment('left');
                            });
                        }
                        $sheet->setOrientation('portrait');
                        //focus on A1 cell
                        $sheet->setSelectedCells('A1');
                    });
                })->store('xlsx', public_path('download/excel'));
                return response(array(
                    'response'  =>  true, 
                    'fileName'  =>  '/download/excel/'.$file_name.'.xlsx'));
            }else{
                return response(array('response'=> false));
            }
        } catch (\Exception $e) {
            return response()->json(array('response' => false));
        }
    }
}
