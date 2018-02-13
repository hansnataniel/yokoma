<div class="edit-group">
	<div class="edit-left">
		{{Form::label('customer_id', 'Customer')}}
	</div><!--
	--><div class="edit-right">
		{{Form::select('customer_id', $customer_options, null, array('class'=>'medium-text select customer-id', 'required'))}}
		<span class="required-tx">
			*Required
		</span>
	</div>
</div>

<div class="hasil-ajax-salesman">
	{{Form::hidden('commission1', 0, array('class'=>'small-text commission1'))}}
	{{Form::hidden('commission2', 0, array('class'=>'small-text commission2'))}}
	{{Form::checkbox('from_net', 'true', false, array('id'=>'from_net', "style" => "display: none;"))}}
</div>

<div class="edit-group">
	<div class="edit-left">
		{{Form::label('date', 'Date')}}
	</div><!--
	--><div class="edit-right">
		{{Form::text('date', date('Y-m-d'), array('class'=>'medium-text datetimepicker', 'required', 'readonly'))}}
		<span class="required-tx">
			*Required
		</span>
	</div>
</div>

<div class="edit-group">
	<div class="edit-left">
		{{Form::label('keterangan', 'Keterangan')}}
	</div><!--
	--><div class="edit-right">
		{{Form::textarea('keterangan', null, array('class'=>'large-text area'))}}
	</div>
</div>

<script type="text/javascript">
	$('.select').each(function(){
		var data = $(this).attr('placeholder-data');

		$(this).select2({
			placeholder: data
		});
	});

	$('.datetimepicker').datetimepicker({
		scrollMonth: false,
		timepicker: false,
		maxDate: 'now',
		format: 'Y-m-d'
	});
</script>