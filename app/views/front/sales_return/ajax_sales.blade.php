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

<section class="view-data-info" style="display: inline-block; width: 49%; vertical-align: top;">
	<header class="view-data-header">
		Sales Items, <a href="{{URL::to('sales/view/' . $sale->id)}}" target="_blank"><div class="icon-sub" style="width: 95px; padding-top: 9px; display: inline-block; margin-left: 10px;">{{HTML::image('img/admin/view.png')}} <span>View Sales</span></div></a>
	</header>
	<article class="view-data-ctn">
		<table id="index-table" style="border-spacing: 0px;width: 100%;">
			<tr class="index-tr index-title">
				<th>#</th>
				<th>Product</th>
				<th>Price</th>
				<th>Qty</th>
				<th style="text-align: right;">Subtotal</th>
				<th></th>
			</tr>
			<?php
				$counter = 1;
			?>
			@foreach ($saledetails as $saledetail)
				@if($saledetail->product->type == 'Product')
					<tr class='index-tr'>
						<td>{{$counter++}}</td>
						<td>{{$saledetail->product->name}}</td>
						<td>Rp. {{digitGroup($saledetail->subtotal / $saledetail->qty)}}</td>
						<td>{{digitGroup($saledetail->qty)}}</td>
						<td style="text-align: right;">Rp. {{digitGroup($saledetail->subtotal)}}</td>
						<td>
							<div class="icon-sub add-item" dataId="{{$saledetail->id}}" style="background-image: none; width: 50px; padding-left: 4px; height: 13px;">
								<span>Return</span>
							</div>
						</td>
					</tr>
				@endif
			@endforeach
			<tr class='index-tr'>
				<td></td>
				<td></td>
				<td></td>
				<td><strong>Total:</strong></td>
				<td style="text-align: right;">
					<strong>Rp. {{digitGroup($sale->paid)}}</strong>
				</td>
				<td></td>
			</tr>
		</table>
	</article>
</section>

<section class="view-data-info" style="display: inline-block; width: 49%; vertical-align: top;">
	<header class="view-data-header">
		Sales Return Items
	</header>
	<article class="view-data-ctn hasil-ajax-item">
		<table id="index-table" style="border-spacing: 0px;width: 100%;">
			<tr class="index-tr index-title">
				<th>#</th>
				<th>Product</th>
				<th>Price</th>
				<th>Qty</th>
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
<?php
	$minDate = date('Y/m/d', strtotime($sale->date));
?>

<script type="text/javascript">
	$('.datetimepicker').datetimepicker({
		scrollMonth: false,
		timepicker: false,
		maxDate: 'now',
		minDate: '{{$minDate}}',
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
            url: "{{URL::to('sales/ajax-product')}}/" + branchId + '/' + productId,
            success: function(msg){
                $('.hasil-ajax-product').html(msg);
            }
        });
	});
</script>