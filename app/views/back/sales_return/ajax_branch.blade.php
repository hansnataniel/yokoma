<div class="edit-group">
	<div class="edit-left">
		{{Form::label('sale_id', 'Sales (No. Invoice | Name Customer)')}}
	</div><!--
	--><div class="edit-right">
		{{Form::select('sale_id', $sale_options, null, array('class'=>'medium-text select ajax-sales', 'required'))}}
		<span class="required-tx">
			*Required
		</span>
	</div>
</div>
<div class="hasil-ajax-sales">
	
</div>
<script type="text/javascript">
	$('.select').each(function(){
		var data = $(this).attr('placeholder-data');

		$(this).select2({
			placeholder: data
		});
	});

	$('.ajax-sales').live('change', function(){
		$('.blur-loader').show();
		var salesId = $('.ajax-sales option:selected').val();
        if(salesId == '')
        {
            salesId = 0;
        }

        $.ajax({
            type: "GET",
            url: "{{URL::to(Crypt::decrypt($setting->admin_url) . '/sales-return/ajax-sales')}}/" + salesId,
            success: function(msg){
                $('.hasil-ajax-sales').html(msg);
				$('.blur-loader').hide();
            }
        });
	});
</script>