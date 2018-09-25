<table>
    <thead>
        <tr>
            <th>作業日報番号</th>
            <th>製造指示番号</th>
            <th>作業実施日</th>
            <th>作業担当者コード</th>
            <th>作業担当者名</th>
            <th>作業時間</th>
        </tr>
    </thead>
    <tbody>
        @if(isset($workingtime) && !empty($workingtime))
            @for($i=0; $i < count($workingtime); $i++)
                <tr>
                    <td>{{$workingtime[$i]['work_report_no']}}</td>
                    <td>{{$workingtime[$i]['manufacture_no']}}</td>
                    <td>{{$workingtime[$i]['work_date']}}</td>
                    <td>{{$workingtime[$i]['work_user_cd']}}</td>
                    <td>{{$workingtime[$i]['user_nm_j']}}</td>
                    <td>{{$workingtime[$i]['work_div']}}</td>
                </tr>
            @endfor
        @endif
    </tbody>
</table>