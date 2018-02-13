<section class="view-data-info">
	<header class="view-data-header">
		Item's
	</header>
	<article class="view-data-ctn">
		{{Form::button('Add Item', array('class'=>'edit-submit add-item add-item-good'))}}
		<table id="index-table" style="border-spacing: 0px;width: 80%; min-width: 900px;">
			<tr class="index-tr index-title">
				<th>#</th>
				<th>Product</th>
				<th>Price</th>
				<th>Quantity</th>
				<th style="text-align: right;">Subtotal</th>
				<th></th>
			</tr>
			<?php
				$counter = 1;
				$total = 0;
			?>
			@foreach ($items as $item)
				<?php 
					$subtotal = $item->price * $item->quantity;

					$total = $total + $subtotal; 
				?>
				<tr class='index-tr'>
					<td>{{$counter++}}</td>
					<td>{{$item->name}}</td>
					<td>Rp. {{digitGroup($item->price)}}</td>
					<td>{{digitGroup($item->quantity)}}</td>
					<td style="text-align: right;">Rp. {{digitGroup($subtotal)}}</td>
					<td class="icon">
						<div class="index-icon">
							{{HTML::image('img/admin/index_action.png', '', array('class'=>'icon-master'))}}
							<div class="index-sub-icon">
								<div class="icon-sub update-item" type="goods" dataId="{{$item->id}}">{{HTML::image('img/admin/edit.png')}} <span>Edit</span></div>
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
				<td><strong>Total:</strong></td>
				<td style="text-align: right;">
					<strong>Rp. {{digitGroup($total)}}</strong>
					{{Form::hidden('price_total', $total)}}
				</td>
				<td></td>
			</tr>
		</table>
	</article>
</section>

<section id="blur-ajax-item-good"></section>

<section class="view-data-info">
	<header class="view-data-header">
		Pembelian Total = Rp. {{digitGroup($total)}}
	</header>
</section>
