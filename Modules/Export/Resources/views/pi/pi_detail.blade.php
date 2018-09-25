@extends('layouts.export')
@section('stylesheet')
    <link rel="stylesheet" href="{{ public_path('modules/export/css/pi_detail.css') }}" rel="stylesheet" type="text/css">
@endsection
@section('content')
<table>
    <tbody>
        <tr class="boder">
            <th></th>
            <td colspan="6" align="center">
                <span style="font-weight: bold;">Proforma Invoice</span>
            </td>
        </tr>
        <tr class="boder">
            <th></th>
            <th rowspan="5" valign="top">To</th>
            <td colspan="3" rowspan="5" valign="top" width="50" style="wrap-text: true;">
                <span>{{ $pi_h['cust_nm'] or '' }}</span> <br>
                <span>{{ $pi_h['cust_adr1'] or '' }}</span> <br>
                <span>{{ $pi_h['cust_adr2'] or '' }}</span> <br>
                Tel:<span>{{ $pi_h['cust_tel'] or '' }}</span>      Fax: <span>{{ $pi_h['cust_fax'] or '' }}</span>
            </td>
            <th align="center">No</th>
            <td>{{ $pi_h['pi_no'] or '' }}</td>
        </tr>
        <tr class="boder">
            <th></th>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <th align="center">Date</th>
            <td>{{ strftime("%B %d,%Y", strtotime($pi_h['pi_date'])) }}</td>
        </tr>
        <tr class="boder">
            <th></th>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td valign="top" align="center" style="wrap-text: true;">
                <span>{{ $pi_h['mark1'] or '' }}</span><br>
                <span>{{ $pi_h['mark2'] or '' }}</span><br>
                <span>{{ $pi_h['mark3'] or '' }}</span><br>
                <span>{{ $pi_h['mark4'] or '' }}</span><br>
            </td>
        </tr>
        <tr></tr>
        <tr></tr>
        <tr class="boder">
            <th></th>
            <th rowspan="5" valign="top">Consignee</th>
            <td colspan="3" rowspan="5" valign="top" width="50" style="wrap-text: true;">
                <span>{{ $pi_h['consignee_nm'] or '' }}</span> <br>
                <span>{{ $pi_h['consignee_adr1'] or '' }}</span> <br>
                <span>{{ $pi_h['consignee_adr2'] or '' }}</span> <br>
                Tel:<span>{{ $pi_h['consignee_tel'] or '' }}</span>         Fax: <span>{{ $pi_h['consignee_fax'] or '' }}</span>
            </td>
        </tr>
        <tr></tr>
        <tr></tr>
        <tr></tr>
        <tr></tr>
        <tr class="boder">
            <th></th>
            <th>Packing</th>
            <td>{{ $pi_h['packing'] or '' }}</td>
            <th>Shipment</th>
            <td colspan="3">{{ $pi_h['shipment_nm'] or '' }}</td>
        </tr>
        <tr class="boder">
            <th></th>
            <th>Port of Shipment</th>
            <td><span>{{ $pi_h['port_country_nm'] or '' }} , <span>{{ $pi_h['port_city_nm'] or '' }}</span></td>
            <th>Destination</th>
            <td colspan="3"><span>{{ $pi_h['dest_country_nm'] or '' }} , <span>{{ $pi_h['dest_city_nm'] or '' }}</span></td>
        </tr>
        <tr class="boder">
            <th></th>
            <th rowspan="2" valign="middle" width="10">Code</th>
            <th rowspan="2" valign="middle" align="center" width="40">Description</th>
            <th rowspan="2" valign="middle" align="center" width="10">Q'ty</th>
            <th rowspan="2" valign="middle" align="center" width="20" style="wrap-text: true;">
                <span>Unit Of</span> <br>Measure
            </th>
            <th colspan="2" valign="middle" align="center" width="20">US$</th>
        </tr>
        <tr class="boder">
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th align="center" width="10">Unit Price</th>
            <th align="center">Amount</th>
        </tr>
        @if(isset($pi_d) && !empty($pi_d))
            @foreach($pi_d as $key => $val)
        <tr class="boder-tr-d">
            <th></th>
            <td align="left" valign="top">{{ $val['product_cd'] or ''}}</td>
            <td align="left" valign="top" style="wrap-text: true;">{{ $val['description'] or ''}}</td>
            <td align="right" valign="top">{{ $val['qty'] or ''}}</td>
            <td align="left" valign="top">{{ $val['unit_measure_nm'] or ''}}</td>
            <td align="right" valign="top">{{ $val['unit_price'] or ''}}</td>
            <td align="right" valign="top">{{ $val['unit_measure'] or ''}}</td>
        </tr>
            @endforeach
        @endif
        <tr class="boder-tr">
            <th></th>
            <td width="20"></td>
            <td valign="top">Total</td>
            <td width="12">{{ $pi_h['total_qty'] or ''}}</td>
            <td>{{ $pi_h['unit_total_measure_nm'] or ''}}</td>
            <td width="12"></td>
            <td width="18"></td>
        </tr>
    </tbody>
</table>
@endsection
