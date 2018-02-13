<table id="index-table" style="border-spacing: 0px;width: 60%;">
	<tr class="index-tr index-title">
		<th>#</th>
		<th>Product</th>
		<th>Type</th>
		<th>Amount</th>
		<th></th
	</tr>
	<?php
		$counter = 1;
	?>
	@foreach ($items as $item)
		<tr class='index-tr'>
			<td>{{$counter++}}</td>
			<td>{{$item->name}}</td>
			<td>{{$item->type  == 1 ? "Stock In":"Stock Out"}}</td>
			<td>{{digitGroup($item->quantity)}}</td>
			<td class="icon">
				<div class="index-icon">
					{{HTML::image('img/admin/index_action.png', '', array('class'=>'icon-master'))}}
					<div class="index-sub-icon">
						<div class="icon-sub update-item" type="goods" dataId="{{$item->product_id . '-' . $item->type}}">{{HTML::image('img/admin/edit.png')}} <span>Edit</span></div>
						<div class="icon-sub delete-item" dataId="{{$item->product_id . '-' . $item->type}}">{{HTML::image('img/admin/delete.png')}} <span>Delete</span></div>
					</div>
				</div>
			</td>
		</tr>
	@endforeach
</table>