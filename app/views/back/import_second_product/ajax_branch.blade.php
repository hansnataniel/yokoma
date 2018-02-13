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

{{Form::button('Add Item', array('class'=>'edit-submit add-item'))}}
<section class="view-data-info">
	<header class="view-data-header">
		Item's
	</header>
	<article class="view-data-ctn hasil-ajax-item">
		<table id="index-table" style="border-spacing: 0px;width: 60%;">
			<tr class="index-tr index-title">
				<th>#</th>
				<th>Item</th>
				<th>Price</th>
				<th>Quantity</th>
				<th style="text-align: right;">Subtotal</th>
				<th></th>
			</tr>
			<?php
				$counter = 1;
			?>
			@foreach ($items as $item)
				<tr class='index-tr'>
					<td>{{$counter++}}</td>
					<td>{{$item->name}}</td>
					<td>Rp. {{digitGroup($item->price)}}</td>
					<td>{{digitGroup($item->quantity)}}</td>
					<td style="text-align: right;">Rp. {{digitGroup($item->price * $item->quantity)}}</td>
					<td></td>
				</tr>
			@endforeach
			<tr class='index-tr'>
				<td></td>
				<td></td>
				<td></td>
				<td><strong>Total:</strong></td>
				<td style="text-align: right;">
					<strong>Rp. {{digitGroup(Cart::total())}}</strong>
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

	$('.ajax-product').live('change', function(){
		var branchId = $('.branch-id option:selected').val();
		var productId = $('.ajax-product option:selected').val();
        if(productId == '')
        {
            productId = 0;
        }

        $.ajax({
            type: "GET",
            url: "{{URL::to(Crypt::decrypt($setting->admin_url) . '/import-second-product/ajax-product')}}/" + branchId + '/' + productId,
            success: function(msg){
                $('.hasil-ajax-product').html(msg);
            }
        });
	});
</script>