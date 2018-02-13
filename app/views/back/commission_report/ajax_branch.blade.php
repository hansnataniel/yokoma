<div class="edit-left">
	{{Form::label('salesman', 'Salesman Name')}}
</div><!--
--><div class="edit-right">
	{{Form::select('salesman', $salesman_options, null, array('class'=>'medium-text select'))}}
	<span class="required-tx">
		*Required
	</span>
</div>

<script type="text/javascript">
	$('.select').each(function(){
		var data = $(this).attr('placeholder-data');

		$(this).select2({
			placeholder: data
		});
	});
</script>