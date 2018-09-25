<div style="display: none;" id="back-search-condition">
    @if(isset($search_html))
        {!! $search_html !!}
    @endif
</div>
<script type="text/javascript">
    var __screen = '{{$screen or ''}}';
    var _back_link = '{{$back_link or '/toppage'}}';
    var _back_screen = '{{$back_screen or 'Toppage'}}';
    var _is_from_search = '{{$is_from_search or '0'}}';
    var _back_data = htmlEntities('{{$back_data or '{"back_link" : "/toppage"}'}}');
    _back_data = JSON.parse(_back_data);
</script>