<div class="edit-group">
	<div class="edit-left">
		{{Form::label('customer_id', 'Customer')}}
	</div><!--
	--><div class="edit-right">
		{{Form::select('customer_id', $customer_options, null, array('class'=>'medium-text select ajax-customer', 'required'))}}
		<span class="required-tx">
			*Required
		</span>
	</div>
</div>
<div class="hasil-ajax-customer">
	
</div>

<script type="text/javascript">
	$('.select').each(function(){
		var data = $(this).attr('placeholder-data');

		$(this).select2({
			placeholder: data
		});
	});
</script>
