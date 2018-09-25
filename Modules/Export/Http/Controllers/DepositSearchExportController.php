<?php
/**
 *|--------------------------------------------------------------------------
 *| Deposit Export
 *|--------------------------------------------------------------------------
 *| Package       : Apel
 *| @author       : ANS796 - tuannt@ans-asia.com
 *| @created date : 2018/02/02
 *| 
 */
namespace Modules\Export\Http\Controllers;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Common\Http\Controllers\CommonController as common;
use Excel, Dao;

class DepositSearchExportController extends Controller {
    public $title           = 'Deposit';
    public $company         = 'Apel';
    public $description     = '入金票一覧';
    /*
     * Header
     * @var array
     */
    private $header = [
        '入金日',
        '入金No',
        '入金分類',
        '取引先コード',
        '取引先名',
        '国',
        '受注No',
        'InvoiceNo',
        '分割入金管理',
        '当初入金予定日',
        '入金銀行',
        '入金区分',
        '通貨',
        '先方送付額',
        '手数料（外貨）',
        '手数料（円貨）',
        '着金額（外貨）',
        '円入金額',
        'レート',
        'レート区分',
        '特記事項',
        '社内用備考',
        '行番号',
        'PiNo',
        '製品名',
        '数量',
        '単価',
        '金額'
    ];
    /*
     * postDepositOutput
     * -----------------------------------------------
     * @author      :   ANS796 - 2018/02/02 - create
     * @param       :
     * @return      :
     * @access      :   public
     * @see         :   remark
     */
    public function postDepositOutput(Request $request) {
        try {
            //width of columns
            $arrWidthColumns     =   array(
                'A'     =>  15,
                'B'     =>  15,
                'C'     =>  10,
                'D'     =>  15,
                'E'     =>  45,
                'F'     =>  20,
                'G'     =>  15,
                'H'     =>  15,
                'I'     =>  15,
                'J'     =>  17,
                'K'     =>  15,
                'L'     =>  20,
                'M'     =>  10,
                'N'     =>  15,
                'O'     =>  17,
                'P'     =>  17,
                'Q'     =>  17,
                'R'     =>  15,
                'S'     =>  12,
                'T'     =>  15,
                'U'     =>  35,
                'V'     =>  35,
                'W'     =>  15,
                'X'     =>  15,
                'Y'     =>  45,
                'Z'     =>  15,
                'AA'    =>  15,
                'AB'    =>  15,
            );
            $param              =   $request->all();
            $sql                =   "SPC_021_DEPOSIT_SEARCH_FND1"; 
            $result             =   Dao::call_stored_procedure($sql, $param, true);
            $data               =   isset($result[0]) ? $result[0] : NULL;
            if (!is_null($data)) {
                $fileName    = '入金票一覧_'.date("YmdHis");
                \Excel::create($fileName, function($excel) use ($data, $arrWidthColumns) {
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
                        $sheet->getStyle('A1:AB1')->applyFromArray($styleAllBorder);
                        $sheet->getStyle('A1:AB1')->getFont()->getColor()->setRGB('ffffff');
                        $sheet->cells('A1:AB1', function($cells) {
                            $cells->setBackground('#248af0');
                            $cells->setAlignment('center');
                            $cells->setValignment('center');
                        });
                        //write data to excel file.
                        foreach ($data as $k1 => $v1) {
                            $row ++;
                            $sheet->getStyle('A'.$row.':AB'.$row)->applyFromArray($styleAllBorder);
                            $sheet->getStyle('A'.$row.':AB'.$row)->getAlignment()->setWrapText(true);
                            $sheet->row($row, array(
                                $v1['deposit_date'], 
                                $v1['deposit_no'], 
                                $v1['deposit_div_nm'], 
                                $v1['client_cd'], 
                                $v1['client_nm'],                                
                                $v1['country_div_nm'], 
                                $v1['rcv_no'],
                                $v1['inv_no'],
                                $v1['split_deposit_div_nm'],
                                $v1['initial_deposit_date'],
                                $v1['deposit_bank_div_nm'],                                
                                $v1['deposit_way_div_nm'],
                                $v1['currency_div_nm'],
                                $v1['remittance_amt'],
                                $v1['fee_foreign_amt'],
                                $v1['fee_yen_amt'],
                                $v1['arrival_foreign_amt'],
                                $v1['deposit_yen_amt'],
                                $v1['exchange_rate'],
                                $v1['rate_confirm_div_nm'],
                                $v1['notices'],
                                $v1['inside_remarks'],
                                $v1['rcv_detail_no'],
                                $v1['pi_no'],
                                $v1['description'],
                                $v1['qty'],
                                $v1['unit_price'],
                                $v1['detail_amt']
                            ));
                            //even odd row background color
                            if($row % 2 != 0){
                                $sheet->cells('A'.$row.':AB'.$row, function($cells) {
                                    $cells->setBackground('#FFF2CC');
                                });
                            }
                            //center margin
                            $sheet->cells('A'.$row.':A'.$row, function($cells) {
                                $cells->setAlignment('center');
                                $cells->setValignment('center');
                            });
                            $sheet->cells('J'.$row.':J'.$row, function($cells) {
                                $cells->setAlignment('center');
                                $cells->setValignment('center');
                            });
                            //right margin
                            $sheet->cells('N'.$row.':N'.$row, function($cells) {
                                $cells->setAlignment('right');
                                $cells->setValignment('center');
                            });
                            $sheet->cells('O'.$row.':O'.$row, function($cells) {
                                $cells->setAlignment('right');
                                $cells->setValignment('center');
                            });
                            $sheet->cells('P'.$row.':P'.$row, function($cells) {
                                $cells->setAlignment('right');
                                $cells->setValignment('center');
                            });
                            $sheet->cells('Q'.$row.':Q'.$row, function($cells) {
                                $cells->setAlignment('right');
                                $cells->setValignment('center');
                            });
                            $sheet->cells('R'.$row.':R'.$row, function($cells) {
                                $cells->setAlignment('right');
                                $cells->setValignment('center');
                            });
                            $sheet->cells('S'.$row.':S'.$row, function($cells) {
                                $cells->setAlignment('right');
                                $cells->setValignment('center');
                            });
                            $sheet->cells('Z'.$row.':Z'.$row, function($cells) {
                                $cells->setAlignment('right');
                                $cells->setValignment('center');
                            });
                            $sheet->cells('AA'.$row.':AA'.$row, function($cells) {
                                $cells->setAlignment('right');
                                $cells->setValignment('center');
                            });
                            $sheet->cells('AB'.$row.':AB'.$row, function($cells) {
                                $cells->setAlignment('right');
                                $cells->setValignment('center');
                            });
                            //left margin
                            $sheet->cells('A'.$row.':I'.$row, function($cells) {
                                $cells->setAlignment('left');
                                $cells->setValignment('center');
                            });
                            $sheet->cells('K'.$row.':M'.$row, function($cells) {
                                $cells->setAlignment('left');
                                $cells->setValignment('center');
                            });
                            $sheet->cells('T'.$row.':Y'.$row, function($cells) {
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
                    'fileName'  =>  '/download/excel/'.$fileName.'.xlsx'));
            } else {
                return response(array('response'=> false));
            }
        } catch (\Exception $e) {
            return response(array('response'=> false, 'ee'=>$e->getMessage()));
        }
    }
}
