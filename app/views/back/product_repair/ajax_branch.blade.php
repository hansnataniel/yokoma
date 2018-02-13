<div class="edit-group">
	<div class="edit-left">
		{{Form::label('customer_id', 'Customer')}}
	</div><!--
	--><div class="edit-right">
		{{Form::select('customer_id', $customer_options, null, array('class'=>'medium-text select', 'required'))}}
		<span class="required-tx">
			*Required
		</span>
	</div>
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
		{{Form::label('keterangan', 'keterangan')}}
	</div><!--
	--><div class="edit-right">
		{{Form::textarea('keterangan', null, array('class'=>'large-text area'))}}
	</div>
</div>

{{Form::button('Add Item', array('class'=>'edit-submit add-item'))}}
<section class="view-data-info">
	<header class="view-data-header">
		Item's
	</header>
	<article class="view-data-ctn hasil-ajax-item">
		<table id="index-table" style="border-spacing: 0px;width: 60%;">
			<tr class="index-tr index-title">
				<th>#</th>
				<th>Product</th>
				<th>Quantity</th>
				<th></th>
			</tr>
			<?php
				$counter = 1;
			?>
			@foreach ($items as $item)
				<tr class='index-tr'>
					<td>{{$counter++}}</td>
					<td>{{$item->name}}</td>
					<td>{{digitGroup($item->quantity)}}</td>
					<td></td>
				</tr>
			@endforeach
			<tr class='index-tr'>
				<td></td>
				<td><strong>Total:</strong></td>
				<td style="text-align: right;">
					<strong>Rp. {{digitGroup(Cart::totalItems())}}</strong>
				</td>
				<td></td>
			</tr>
		</table>
	</article>
</section>

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