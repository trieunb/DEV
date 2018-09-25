@extends('layouts.export')
@section('stylesheet')
    <link rel="stylesheet" href="{{ public_path('modules/export/css/pi_detail.css') }}" rel="stylesheet" type="text/css">
@endsection
@section('content')
<table>
    <tbody>
        <tr>
            <td></td>
            <td class="border-td" colspan="14" align="center">
                <span style="font-weight: bold;">Proforma Invoice</span>
            </td>
        </tr>
         <!-- To -->
        <tr>
            <td></td>
            <th class="border-td-left-right" colspan="2" valign="top">To</th>
            <td colspan="8" style="wrap-text: true;">{{ $pi_h['cust_nm'] or '' }}</td>
            <th class="border-td" colspan="2" align="center">No.</th>
            <td class="border-td" colspan="2" align="center">{{ $pi_h['pi_no'] or '' }}</td>
        </tr>
        <tr>
            <td></td>
            <th class="border-td-left-right" colspan="2"></th>
            <td colspan="8" style="wrap-text: true;">{{ $pi_h['cust_adr1'] or '' }}</td>
            <th class="border-td" colspan="2" align="center">Date</th>
            <td class="border-td" colspan="2">{{ strftime("%B %d,%Y", strtotime($pi_h['pi_date'])) }}</td>
        </tr>
        <tr>
            <td></td>
            <th class="border-td-left-right" colspan="2"></th>
            <td class="border-td-right" colspan="8" style="wrap-text: true;">{{ $pi_h['cust_adr2'] or '' }} {{ $pi_h['cust_country_nm'] or '' }}</td>
            <td class="border-td-right" colspan="4" align="center" style="wrap-text: true;">{{ $pi_h['mark1'] or '' }}</td>
        </tr>
        <tr>
            <td></td>
            <th class="border-td-left-right" colspan="2"></th>
            <td>Tel:</td>
            <td colspan="2">{{ $pi_h['cust_tel'] or '' }}</td>
            <td>Fax:</td>
            <td class="border-td-right" colspan="4">{{ $pi_h['cust_fax'] or '' }}</td>
            <td class="border-td-right" colspan="4" align="center" style="wrap-text: true;">{{ $pi_h['mark2'] or '' }}</td>
        </tr>
        <tr>
            <td></td>
            <th class="border-td-bottom border-td-left-right" colspan="2"></th>
            <td class="border-td-bottom"></td>
            <td class="border-td-bottom" colspan="2"></td>
            <td class="border-td-bottom"></td>
            <td class="border-td-bottom border-td-right" colspan="4"></td>
            <td class="border-td-right" colspan="4" align="center" style="wrap-text: true;">{{ $pi_h['mark3'] or '' }}</td>
        </tr>
        <!-- Consignee -->
         <tr>
            <td></td>
            <th class="border-td-left-right" colspan="2" valign="top">Consignee</th>
            <td class="border-td-right" colspan="8" style="wrap-text: true;">{{ $pi_h['consignee_nm'] or '' }}</td>
            <td class="border-td-right" colspan="4" align="center">{{ $pi_h['mark4'] or '' }}</td>
        </tr>
        <tr>
            <td></td>
            <th class="border-td-left-right" colspan="2"></th>
            <td class="border-td-right" colspan="8" style="wrap-text: true;">{{ $pi_h['consignee_adr1'] or '' }}</td>
            <td class="border-td-right" colspan="4"></td>
        </tr>
        <tr>
            <td></td>
            <th class="border-td-left-right" colspan="2"></th>
            <td class="border-td-right" colspan="8" style="wrap-text: true;">{{ $pi_h['consignee_adr2'] or '' }} {{ $pi_h['consignee_country_nm'] or '' }}</td>
            <td class="border-td-right" colspan="4" align="center" style="wrap-text: true;"></td>
        </tr>
        <tr>
            <td></td>
            <th class="border-td-left-right" colspan="2"></th>
            <td>Tel:</td>
            <td colspan="2">{{ $pi_h['consignee_tel'] or '' }}</td>
            <td>Fax:</td>
            <td class="border-td-right" colspan="4">{{ $pi_h['consignee_fax'] or '' }}</td>
            <td class="border-td-right" colspan="4" align="center" style="wrap-text: true;"></td>
        </tr>
        <tr>
            <td></td>
            <th class="border-td-bottom border-td-left-right" colspan="2"></th>
            <td class="border-td-bottom "></td>
            <td class="border-td-bottom" colspan="2"></td>
            <td class="border-td-bottom"></td>
            <td class="border-td-bottom border-td-right" colspan="4"></td>
            <td class="border-td-bottom border-td-right" colspan="4" align="center" style="wrap-text: true;"></td>
        </tr>
        <tr>
            <td></td>
            <th class="border-td-bottom border-td-left-right" colspan="2">Packing</th>
            <td class="border-td-bottom border-td-left-right" colspan="4">{{ $pi_h['packing'] or '' }}</td>
            <th class="border-td-bottom border-td-left-right" colspan="3">Shipment</th>
            <td class="border-td-bottom border-td-left-right" colspan="5">{{ $pi_h['shipment_nm'] or '' }}</td>
        </tr>
        <tr>
            <td></td>
            <th class="border-td-bottom border-td-left-right" colspan="2">Port of Shipment</th>
            <td class="border-td-bottom border-td-left-right" colspan="4">
                <span>{{ $pi_h['port_country_nm'] or '' }} , <span>{{ $pi_h['port_city_nm'] or '' }}</span>
            </td>
            <th class="border-td-bottom border-td-left-right" colspan="3">Destination</th>
            <td class="border-td-bottom border-td-left-right" colspan="5">
                <span>{{ $pi_h['dest_country_nm'] or '' }} , <span>{{ $pi_h['dest_city_nm'] or '' }}</span>
            </td>
        </tr>
        <!-- Payment -->
        <tr>
            <td></td>
            <th class="border-td-bottom border-td-left-right" valign="middle" align="center" colspan="2">Payment</th>
            <td class="border-td-bottom border-td-left-right" valign="middle" align="center" colspan="7">
                {{ $pi_h['payment_conditions_nm'] or '' }}
            </td>
            <th class="border-td-bottom border-td-left-right" style="wrap-text: true;">Trade Terms</th>
            <td class="border-td-bottom border-td-left" valign="middle" align="center" colspan="3">{{ $pi_h['trade_terms_nm_ctl'] or '' }}</td>
            <td class="border-td-bottom border-td-right" valign="middle" align="center">{{ $pi_h['consignee_country_nm_ctl'] or '' }}</td>
        </tr>
        <!-- Code -->
        <tr>
            <td></td>
            <th class="border-td-bottom border-td-left-right" colspan="2" rowspan="2" align="center" valign="middle">Code</th>
            <th class="border-td-bottom border-td-left-right" colspan="6" rowspan="2" align="center" valign="middle">Description</th>
            <th class="border-td-bottom border-td-left-right" align="center" rowspan="2" valign="middle">Q'ty</th>
            <th class="border-td-bottom border-td-left-right" align="center" rowspan="2" valign="middle" 
            style="wrap-text: true;">Unit of Measure</th>
            <th class="border-td-bottom border-td-bottom border-td-left-right" colspan="4" align="center" valign="middle">US$</th>
        </tr>
        <tr>
            <td></td>
            <td class="border-td-left-right" colspan="2"></td>
            <td class="border-td-left-right" colspan="6"></td>
            <td class="border-td-left-right"></td>
            <td class="border-td-left-right"></td>
            <td class="border-td-bottom border-td-left-right" align="center" colspan="3">Unit Price</td>
            <td class="border-td-bottom border-td-left-right" align="center">Amount</td>
        </tr>
        @if(isset($pi_d) && !empty($pi_d))
            @foreach($pi_d as $key => $val)
        <tr>
            <td></td>
            <td class="border-td-left-right" colspan="2" align="center" valign="top">{{ $val['product_cd'] or ''}}</td>
            <td class="border-td-left-right" colspan="6" align="left" valign="top">{{ $val['description'] or ''}}</td>
            <td class="border-td-left-right" align="right" valign="top">{{ $val['qty'] or ''}}</td>
            <td class="border-td-left-right" align="left" valign="top">{{ $val['unit_measure_nm'] or ''}}</td>
            <td class="border-td-left-right" colspan="3" align="right" valign="top">{{ $val['unit_price'] or ''}}</td>
            <td class="border-td-left-right" align="right" valign="top">{{ $val['unit_measure'] or ''}}</td>
        </tr>
            @endforeach
        @endif
        <tr>
            <td></td>
            <td class="border-td-top border-td-left-right" colspan="2"></td>
            <th class="border-td-top border-td-left-right" valign="top" colspan="6">Total</th>
            <td class="border-td-top border-td-left-right">{{ $pi_h['total_qty'] or ''}}</td>
            <td class="border-td-top border-td-left-right">{{ $pi_h['unit_total_measure_nm'] or ''}}</td>
            <td class="border-td-top border-td-left-right" colspan="3"></td>
            <td class="border-td-top border-td-left-right"></td>
        </tr>
        <tr>
            <td></td>
            <td class="border-td-left-right" colspan="2"></td>
            <td class="border-td-left-right" colspan="6"></td>
            <td class="border-td-left-right"></td>
            <td class="border-td-left-right"></td>
            <td class="border-td-left-right" colspan="3"></td>
            <td class="border-td-left-right"></td>
        </tr>
        <tr>
            <td></td>
            <td class="border-td-left-right" colspan="2"></td>
            <td class="border-td-left-right" colspan="6"></td>
            <td class="border-td-left-right"></td>
            <td class="border-td-left-right"></td>
            <td class="border-td-left-right" colspan="3"></td>
            <td class="border-td-left-right"></td>
        </tr>
        <tr>
            <td></td>
            <td class="border-td-bottom border-td-left-right" colspan="2"></td>
            <td class="border-td-bottom border-td-left-right" colspan="6"></td>
            <td class="border-td-bottom border-td-left-right"></td>
            <td class="border-td-bottom border-td-left-right"></td>
            <td class="border-td-bottom border-td-left-right" colspan="3"></td>
            <td class="border-td-bottom border-td-left-right"></td>
        </tr>
        <!-- Footer -->
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td class="border-td-left border-td-bottom">FCA</td>
            <td class="border-td-right border-td-bottom" colspan="3">JaPan</td>
            <td class="border-td-right border-td-bottom" align="right">{{ $pi_h['total_detail_amt'] or '' }}</td>
        </tr>
        <tr>
            <td></td>
            <th colspan="2">Time of shipment:</th>
            <td>{{ $pi_h['time_of_shipment'] or '' }}</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td class="border-td-left border-td-bottom">@if(isset($pi_h['trade_terms_freigt_amt'])) Freight @endif</td>
            <td class="border-td-right border-td-bottom" colspan="3"></td>
            <td class="border-td-right border-td-bottom" align="right">{{ $pi_h['trade_terms_freigt_amt'] or '' }}</td>
        </tr>
        <tr>
            <td></td>
            <th>Bank : </th>
            <td></td>
            <td colspan="7">{{ $pi_h['bank_nm'] or '' }}</td>
            <td class="border-td-left border-td-bottom">@if(isset($pi_h['trade_terms_insurance_amt'])) Insurance @endif</td>
            <td class="border-td-right border-td-bottom" colspan="3"></td>
            <td class="border-td-right border-td-bottom">{{ $pi_h['trade_terms_insurance_amt'] or '' }}</td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td colspan="7">{{ $pi_h['bank_ctl1_nm'] or '' }}</td>
            <td class="border-td-left border-td-bottom">CPT</td>
            <td class="border-td-right border-td-bottom" colspan="3">Iraq</td>
            <td class="border-td-right border-td-bottom" align="right">{{ $pi_h['total_amt'] or '' }}</td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <th>A/C No. </th>
            <td colspan="3"><span>{{ $pi_h['bank_ctl2_nm'] or '' }}</span>,{{ $pi_h['bank_ctl3_nm'] or '' }}</td>
            <th>SWIFT: </th>
            <td colspan="2">{{ $pi_h['bank_ctl4_nm'] or '' }}</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <th colspan="2">Country of Origin :</th>
            <td colspan="2">{{ $pi_h['country_of_origin'] or '' }}</td>
            <th>Manufacturer:</th>
            <td colspan="3">{{ $pi_h['manufacture'] or '' }}</td>
        </tr>
        <tr>
            <td></td>
            <th>Validity :</th>
            <td></td>
            <td colspan="12">{{ $pi_h['pi_validity'] or '' }}</td>
        </tr>
        <tr>
            <td></td>
            <th colspan="14" align="left">Other conditions :  </th>
        </tr>
        <tr>
            <td></td>
            <td colspan="10">{{ $pi_h['other_conditions1'] or '' }}</td>
        </tr>
        <tr>
            <td></td>
            <td colspan="10">{{ $pi_h['other_conditions2'] or '' }}</td>
        </tr>
        <tr>
            <td></td>
            <td colspan="10">{{ $pi_h['other_conditions3'] or '' }}</td>
            <td colspan="14">{{ $header['company_nm'] or '' }}</td>
        </tr>
        <tr>
            <td></td>
            <td colspan="10">{{ $pi_h['other_conditions4'] or '' }}</td>
        </tr>
        <tr>
            <td></td>
            <td colspan="10">{{ $pi_h['other_conditions5'] or '' }}</td>
        </tr>
        <tr>
            <td></td>
            <td colspan="10">{{ $pi_h['other_conditions6'] or '' }}</td>
        </tr>
        <tr>
            <td></td>
            <td colspan="10">{{ $pi_h['other_conditions7'] or '' }}</td>
            <td colspan="4">{{ $pi_h['sign_user_nm'] or '' }}</td>
        </tr>
        <tr>
            <td></td>
            <td colspan="10">{{ $pi_h['other_conditions8'] or '' }}</td>
            <td colspan="4">Export Manager</td>
        </tr>
        <tr>
            <td></td>
            <td colspan="10">{{ $pi_h['other_conditions9'] or '' }}</td>
            <td colspan="4">Export Manager</td>
        </tr>
         <tr>
            <td></td>
            <td colspan="10">{{ $pi_h['other_conditions10'] or '' }}</td>
        </tr>
    </tbody>
</table>
@endsection
