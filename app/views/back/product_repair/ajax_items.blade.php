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
			<td class="icon">
				<div class="index-icon">
					{{HTML::image('img/admin/index_action.png', '', array('class'=>'icon-master'))}}
					<div class="index-sub-icon">
						<div class="icon-sub update-item" dataId="{{$item->product_id . '-' . $item->price}}">{{HTML::image('img/admin/edit.png')}} <span>Edit</span></div>
						<div class="icon-sub delete-item" dataId="{{$item->product_id . '-' . $item->price}}">{{HTML::image('img/admin/delete.png')}} <span>Delete</span></div>
					</div>
				</div>
			</td>
		</tr>
	@endforeach
	<tr class='index-tr'>
		<td></td>
		<td>
			<strong>Total:</strong>
		</td>
		<td>
			<strong>{{digitGroup(Cart::totalItems())}}</strong>
		</td>
		<td></td>
	</tr>
</table>