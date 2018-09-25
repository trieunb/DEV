<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
        {!! public_url('css/icons/fontawesome/css/font-awesome.min.css')!!}
        {!! public_url('css/bootstrap.css')!!}
        <!-- Styles -->

        {!! public_url('js/core/libraries/jquery.min.js')!!}
        <!-- {!! public_url('js/common/common.js')!!} -->

        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Raleway', sans-serif;
                font-weight: 100;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 12px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
    </head>
    <body>
        <div class="container text-center" style="padding-top: 20px">
            <button type="button" id="btn-random" class="btn btn-primary" style="font-weight: bold;">Random</button>
        </div>
        <div class="container" style="margin-top: 20px">
            <div class="col-md-5"></div>
            <div class="col-md-4">
                <div class="col-md-2">
                    <div class="result-boy" style="font-weight: bold;"></div>
                </div>
                <div class="col-md-1 space" style="font-weight: 600;"></div>
                <div class="col-md-6">
                    <div class="result-girl" style="font-weight: bold;"></div>
                </div>
            </div>
        </div>
        
    </body>

    <script type="text/javascript">
             //download data csv
            $(document).on('click','#btn-random',function (e) {
                try {
                    $.ajax({
                        type        :   'GET',
                        url         :   '/post-random',
                        dataType    :   'json',
                        data        :   '',
                        success: function(res) {
                            $('.result-boy').empty();
                            $('.space').empty();
                            $('.result-girl').empty();
                            for (var i = 0; i < 13; i++) {
                                $('.result-boy').append(res.boy[i]+'</br>');
                                $('.space').append('-</br>');
                                $('.result-girl').append(res.girl[i]+'</br>');
                            }
                        }
                    });
                } catch (e){
                    console.log('exportExcel: '+e.message);
                }
            });
    </script>
</html>
