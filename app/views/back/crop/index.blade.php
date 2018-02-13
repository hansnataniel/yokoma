@extends('back.template.master')

@section('title')
    Crop Photo
@stop

@section('page_title')
    Crop Photo
@stop

@section('help')
    <ul style="padding-left: 18px;">
        <li>sebelum Menyimpan Data Anda diwajibkan untuk Meng-crop atau memotong gambar terlebih dahulu.</li>
    </ul>
@stop

@section('head_additional')
    {{HTML::style('css/cropper.css')}}
    {{HTML::script('js/cropper.js')}}
    <script>
        $(document).ready(function(){
            $('.img-ctn').css({
                'height': <?php echo "$h_akhir"; ?>
            });
            // $('body').hide();
        });
        
        $(window).load(function(){
            // $('body').fadeOut();

            if(($(window).width() > 300) && ($(window).width() < 500))
            {
                var xWidth = <?php echo $w_akhir300; ?>;
                var xheight = <?php echo $h_akhir300; ?>;
            }

            if(($(window).width() > 500) && ($(window).width() < 768))
            {
                var xWidth = <?php echo $w_akhir480; ?>;
                var xheight = <?php echo $h_akhir480; ?>;
            }

            if(($(window).width() > 768) && ($(window).width() < 1024))
            {
                var xWidth = <?php echo $w_akhir720; ?>;
                var xheight = <?php echo $h_akhir720; ?>;
            }

            if($(window).width() >= 1024)
            {
                var xWidth = <?php echo $w_akhir; ?>;
                var xheight = <?php echo $h_akhir; ?>;
            }

            if(xheight == 0)
            {
                $('.img-container').css({
                    'width': $('.hiden').width(),
                    'height': $('.hiden').height()
                }); 

                $('.img-ctn').css({
                    'width': $('.hiden').width(),
                    'height': $('.hiden').height()
                });

                $('.cropper-container').css({
                    'width': $('.img-container').width(),
                    'height': 400
                });
            }
            else
            {
                $('.img-container').css({
                    'width': xWidth,
                    'height': xheight
                }); 

                $('.img-ctn').css({
                    'width': xWidth,
                    'height': xheight
                });

                $('.cropper-container').css({
                    'width': $('.img-container').width(),
                    'height': xheight
                });
            }

            var WRatio = <?php echo $w_ratio; ?>;
            var HRatio = <?php echo $h_ratio; ?>;


            $.ajax({
                type: 'GET',
                url: "{{URL::to(Crypt::decrypt($setting->admin_url) . '/cropper')}}/"+WRatio+"/"+HRatio,
                success:function(msg){
                    $('.mid-con .mid').fadeOut();
                    $('.img-container').fadeIn();
                    $('.crop-result').html(msg);
                    // alert('loaded');
                }
            });
            
            $('.btn-tool').click(function(){
                $('.btn-tool').removeClass('btn-activated');
                $(this).addClass('btn-activated');
            });
        });
    </script>
    <style>
        #jcrop_wrapper2 {
            max-width: 1024px;
            margin: 0 auto;
            position: relative;
            display: block;
            /*padding: 0px 20px;*/
        }

        #jcrop_wrapper2 img {
            max-width: none;
            overflow: auto;
        }

        #jcrop_wrapper2 h2 {
            text-align: center;
            position: relative;
            display: table;
            margin: 0 auto;
            font-family: pacifio;
            color: #73503c;
            font-size: 60px;
            font-weight: normal;
            padding: 0px;
        }

        #jcrop_wrapper2 h2 span {
            top: 5px;
            position: relative;
            display: table;
        }

        .crop-submit {
            position: relative !important;
            display: table !important;
            margin-bottom: 50px !important;
            width: 120px !important;
            height: 40px !important;
        }

        .img-ctn {
            position: relative;
            display: table;
            margin: auto;
            width: 100%;
            height: 100%;
        }

        .mid-con {
            position: absolute;
            width: 100%;
            height: 100%;
            display: table;
            top: 0px;
            left: 0px;
        }

        .mid {
            position: relative;
            display: table-cell;
            vertical-align: middle;
            text-align: center;
            width: 100%;
            height: 100%;
        }

        .img-container {
            position: relative;
            display: none;
            margin: 0px;
        }

        .button-group {
            position: relative;
            display: table;
            margin: 30px auto;
            width: 100%;
            font-size: 0px;
            text-align: center;
        }

        .btn {
            position: relative;
            display: inline-block;
            vertical-align: top;
            font-size: 14px;
            width: 50px;
            border: 0px;
            border-bottom: 2px solid transparent;
            /*padding: 10px 0px;*/
            height: 34px;
            /*margin: 0px 5px;*/
            background: #f5f5f5 !important;
            /*background: rgba(96, 241, 215, 1) !important;*/
            -webkit-transition: border-bottom 0.4s;
            -moz-transition: border-bottom 0.4s;
            -ms-transition: border-bottom 0.4s;
            transition: border-bottom 0.4s;
        }

        .btn:focus {
            background: rgba(96, 241, 215, 1);
            outline: 0px;
            border-bottom: 2px solid transparent;
        }

        .btn-border {
            position: absolute;
            right: -0.5px;
            width: 1px;
            display: inline-block;
            height: 20px;
            top: 7px;
            background: #5c5c5c;
        }

        .btn:hover {
            border-bottom: 2px solid #0d0f3b;
            -webkit-transition: border-bottom 0.4s;
            -moz-transition: border-bottom 0.4s;
            -ms-transition: border-bottom 0.4s;
            transition: border-bottom 0.4s;
        }

        span img {
            height: 15px;
        }

        .btn-activated {
            border-bottom: 2px solid #0d0f3b !important;
        }

        .center {
            margin: auto;
            position: relative;
            display: table;
        }

        .hiden {
            width: 100%;
            position: absolute;
            display: block;
            opacity: 0;
        }

        .hiden img {
            max-width: 100% !important;
            position: relative;
            display: block;
        }
    </style>
@stop

@section('content')
    <div class="crop-result"></div>
    <div id="jcrop_wrapper2">
        <div class="hiden">
            {{HTML::image($image)}}
        </div>
        <br/><br/>
        {{Form::open(array('url' => URL::current(), 'method' => 'POST', 'id' => 'coords'));}}
            <div class="img-ctn">
                <div class="img-container">
                    {{HTML::image(asset($image), '', array('id' => 'target'))}}
                </div>
                <div class="mid-con">
                    <div class="mid">
                        {{HTML::image('img/admin/crop.GIF')}}
                    </div>
                </div>
            </div>
            {{Form::hidden('x1', '', array('id' => 'dataX'))}}
            {{Form::hidden('y1', '', array('id' => 'dataY'))}}
            {{Form::hidden('w', '', array('id' => 'dataWidth'))}}
            {{Form::hidden('h', '', array('id' => 'dataHeight'))}}
            <div class="button-group">
                <button class="btn btn-primary btn-tool btn-activated" data-method="setDragMode" data-option="crop" type="button">
                    <span class="docs-tooltip" data-toggle="tooltip">
                        <span class="icon-crop">
                            {{HTML::image('img/admin/icon_crop_blue.png')}}
                        </span>
                    </span>
                </button>
                <button class="btn btn-primary btn-tool" data-method="setDragMode" data-option="move" type="button">
                    <span class="docs-tooltip" data-toggle="tooltip">
                        <span class="icon-move">
                            {{HTML::image('img/admin/icon_drag_blue.png')}}
                        </span>
                    </span>
                </button>
                <button class="btn btn-primary" data-method="zoom" data-option="0.1" type="button">
                    <span class="docs-tooltip" data-toggle="tooltip">
                        <span class="icon-zoom-in">
                            {{HTML::image('img/admin/icon_zoomin_blue.png')}}
                        </span>
                    </span>
                </button>
                <button class="btn btn-primary" data-method="zoom" data-option="-0.1" type="button">
                    <span class="docs-tooltip" data-toggle="tooltip">
                        <span class="icon-zoom-out">
                            {{HTML::image('img/admin/icon_zoomout_blue.png')}}
                        </span>
                    </span>
                </button>
            </div>
        {{Form::submit('Crop', array('class'=>'edit-submit center'))}}
        {{Form::close()}}
    </div>
@stop