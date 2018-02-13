@extends('back.template.master')

@section('title')
	user Image Cropping
@stop

@section('head_additional')
	<script language="Javascript">
        jQuery(function($) {
            var jcrop_api;

            $('#target').Jcrop({
                aspectRatio: <?php echo $w_ratio ?>/<?php echo $h_ratio ?>,
                minSize: [<?php echo $min_w ?>, <?php echo $min_h ?>],
                onChange: showCoords,
                onSelect: showCoords,
                onRelease: clearCoords
            },
            function(){
                jcrop_api = this;
            });

            $('#coords').on('change','input',function(e){
                var x1 = $('#x1').val(),
                x2 = $('#x2').val(),
                y1 = $('#y1').val(),
                y2 = $('#y2').val();
                jcrop_api.setSelect([x1,y1,x2,y2]);
            });

            function showCoords(c)
            {
                $('#x1').val(c.x);
                $('#y1').val(c.y);
                $('#w').val(c.w);
                $('#h').val(c.h);
            };

            function clearCoords()
            {
                $('#x1').val('');
                $('#y1').val('');
                $('#w').val('');
                $('#h').val('');
            };
        });
    </script>
@stop

@section('page_title')
	user Image Cropping
@stop

@section('help')

@stop

@section('content')
	<div id = "jcrop_wrapper">
        {{HTML::image(asset($image), '', array('id' => 'target'))}}<br>
        {{Form::open(array('url' => URL::current(), 'method' => 'POST', 'id' => 'coords'));}}
        {{Form::hidden('x1', '', array('id' => 'x1'))}}
        {{Form::hidden('y1', '', array('id' => 'y1'))}}
        {{Form::hidden('w', '', array('id' => 'w'))}}
        {{Form::hidden('h', '', array('id' => 'h'))}}
        {{Form::submit('Crop')}}
        {{Form::close()}}
    </div>
@stop