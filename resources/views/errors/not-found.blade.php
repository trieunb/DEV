@extends('errors.main-error')

@section('page-title')
    エラーページ
@stop
@section('stylesheet')
   
@stop

@section('javascript')
    
@stop
@section('content')
<div class="content">
        <div class="row">
            

            <div class="wrapper">
                
                <div class="logo">
                    <div class="pull-left">
                        <img class="logo" src="/images/img-logo.png" alt="">
                    </div>
                </div>
                <div class="clearfix line-bottom"></div>

                <div class="box-login">
                    <div id="wrapper-login">
                        <div id="header-login">
                            <p class="text-center">この機能を利用する権限がありません。</p>

                            <p class="text-center p-not-found">Not Access</p>

                        </div>
                        <div style="text-align: center">
                            <!-- <a href="{{url('/')}}" class="btn btn-default button btn-not-found">Topに戻る</a>
                            <br> -->
                            <button  onclick="goBack()" class="btn btn-default button btn-not-found">前のページに戻る</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>
<script>
function goBack() {
    window.history.back();
    parent.$.colorbox.close();
}
</script>
@stop
