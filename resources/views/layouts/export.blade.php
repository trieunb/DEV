<html>
    <header>
       <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
       <link rel="stylesheet" href="{{ public_path('modules/export/css/export.css') }}" rel="stylesheet" type="text/css">
    </header>
    <body>
    @yield('stylesheet')
    <table>
        <tbody>
            <tr></tr>
            <tr>
                <td></td>
                <th><img src="{{public_path('/images/logo_excel.png')}}"></th>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td colspan="7" valign="top" style="wrap-text: true;" height="20">
                    {{ $header['company_zip_address'] or '' }}<br>
                </td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <th>Tel:</th>
                <td>{{ $header['company_tel'] or '' }}</td>
                <td></td>
                <th>Fax:</th>
                <td>{{ $header['company_fax'] or ''}}</td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td width="5"></td>
                <td width="10"></td>
                <td width="8"></td>
                <td width="8"></td>
                <td width="10"></td>
                <td width="15"></td>
                <td width="5"></td>
                <td width="8"></td>
                <th width="6">Email:</th>
                <td>{{ $header['company_mail'] or '' }}</td>
                <td></td>
                <th width="5">URL:</th>
                <td width="5">{{ $header['company_url'] or ''}}</td>
                <td width="5"></td>
                <td width="15"></td>
            </tr>
        </tbody>
    </table>
    @yield('content')
    </body>
</html>