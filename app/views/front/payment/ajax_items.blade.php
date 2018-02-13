<table id="index-table" style="border-spacing: 0px;width: 60%;">
	<tr class="index-tr index-title">
		<th>#</th>
		<th>No. Invoice</th>
		<th>Owed</th>
		<th>Penambahan Pembulatan</th>
		<th>Price</th>
		<th></th>
	</tr>
	<?php
		$counter = 1;
	?>
	@foreach ($items as $item)
		<tr class='index-tr'>
			<td>{{$counter++}}</td>
			<td>{{$item->name}}</td>
			<td>Rp. {{digitGroup($item->owed)}}</td>
			<td>Rp. {{digitGroup($item->pembulatan)}}</td>
			<td>Rp. {{digitGroup($item->price)}}</td>
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
		<td>
			<strong>Total Paid:</strong>
		</td>
		<td>
			<strong>
				Rp. {{digitGroup(Cart::total())}}
			</strong>
			{{Form::hidden('price_total', Cart::total(), array('class'=>'price-total'))}}
		</td>
		<td></td>
	</tr>
</table>