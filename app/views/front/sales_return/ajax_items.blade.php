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
			<td class="icon">
				<div class="index-icon">
					{{HTML::image('img/admin/index_action.png', '', array('class'=>'icon-master'))}}
					<div class="index-sub-icon">
						<div class="icon-sub update-item" dataId="{{$item->id}}">{{HTML::image('img/admin/edit.png')}} <span>Edit</span></div>
						<div class="icon-sub delete-item" dataId="{{$item->id}}">{{HTML::image('img/admin/delete.png')}} <span>Delete</span></div>
					</div>
				</div>
			</td>
		</tr>
	@endforeach
	<tr class='index-tr'>
		<td></td>
		<td></td>
		<td></td>
		<td>
			<strong>Total:</strong>
		</td>
		<td style="text-align: right;">
			<strong>Rp. {{digitGroup(Cart::total())}}</strong>
		</td>
		<td></td>
	</tr>
</table>