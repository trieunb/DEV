<table>
    <thead>
        <tr>
            <th>倉庫コード</th>
            <th>倉庫名</th>
            <th>品目コード</th>
            <th>品目名</th>
            <th>規格</th>
            <th>現在庫数</th>
            <th>有効在庫数</th>
        </tr>
    </thead>
    <tbody>
        @if(isset($stockList) && !empty($stockList))
            @for($i=0; $i < count($stockList); $i++)
                <tr>
                    <td>{{$stockList[$i]['warehouse_cd']}}</td>
                    <td>{{$stockList[$i]['warehouse_nm']}}</td>
                    <td>{{$stockList[$i]['item_cd']}}</td>
                    <td>{{$stockList[$i]['item_nm_j']}}</td>
                    <td>{{$stockList[$i]['specification']}}</td>
                    <td>{{$stockList[$i]['stock_current_qty']}}</td>
                    <td>{{$stockList[$i]['stock_available_qty']}}</td>
                </tr>
            @endfor
        @endif
    </tbody>
</table>