<section class="view-data-info">
	<header class="view-data-header">
		Good Item's
	</header>
	<article class="view-data-ctn">
		{{Form::button('Add Item', array('class'=>'edit-submit add-item add-item-good'))}}
		<table id="index-table" style="border-spacing: 0px;width: 80%; min-width: 900px;">
			<tr class="index-tr index-title">
				<th>#</th>
				<th>Product</th>
				<th>Price</th>
				<th>Disc. 1</th>
				<th>Disc. 2</th>
				<th>Quantity</th>
				<th style="text-align: right;">Subtotal</th>
				<th></th>
			</tr>
			<?php
				$counter = 1;
				$total = 0;
			?>
			@foreach ($items as $item)
				@if($item->type == 'Product')
					<?php 
						$subtotal = $item->price * $item->quantity;
						if($item->discount1 != 0)
						{
							$subtotal = $subtotal - ($subtotal * $item->discount1 / 100);							
						}

						if($item->discount2 != 0)
						{
							$subtotal = $subtotal - ($subtotal * $item->discount2 / 100);							
						}

						$total = $total + $subtotal; 
					?>
					<tr class='index-tr'>
						<td>{{$counter++}}</td>
						<td>{{$item->name}}</td>
						<td>Rp. {{digitGroup($item->price)}}</td>
						<td>{{digitGroup($item->discount1)}}%</td>
						<td>{{digitGroup($item->discount2)}}%</td>
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
				@endif
			@endforeach
			<tr class='index-tr'>
				<td></td>
				<td></td>
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
		Accu Mati Item's
	</header>
	<article class="view-data-ctn">
		{{Form::button('Add Accu Mati', array('class'=>'edit-submit add-item add-item-recycle'))}}
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
				$total_recycle = 0;
			?>
			@foreach ($items as $item)
				@if($item->type != 'Product')
					<?php
						$total_recycle = $total_recycle + ($item->price * $item->quantity);
					?>
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
									<div class="icon-sub update-item" type="recycle" dataId="{{$item->id}}">{{HTML::image('img/admin/edit.png')}} <span>Edit</span></div>
									<div class="icon-sub delete-item" dataId="{{$item->id}}">{{HTML::image('img/admin/delete.png')}} <span>Delete</span></div>
								</div>
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
					<strong>Rp. {{digitGroup($total_recycle)}}</strong>
					{{Form::hidden('recycle_total', $total_recycle)}}
				</td>
				<td></td>
			</tr>
		</table>
	</article>
</section>

<section id="blur-ajax-item-recycle"></section>

<section class="view-data-info">
	<header class="view-data-header">
		Nota Total = Rp. {{digitGroup($total)}}
	</header>
	@if($customer != null)
		<?php
			$salesman1 = Salesman::find($customer->salesman_id1);
			$salesman2 = Salesman::find($customer->salesman_id2);

			if($customer->salesman_id1 != null)
			{
				if(isset($new_commission1))
				{
					$commission1 = $total * $new_commission1 / 100;
				}
				else
				{
					$commission1 = $total * $customer->commission1 / 100;
				}
			}

			if(isset($new_from_net))
			{
				$from_net = $new_from_net;
			}
			else
			{
				$from_net = $customer->from_net;
			}

			if($customer->salesman_id2 != null)
			{
				if($from_net == 'false')
				{
					if(isset($new_commission2))
					{
						$commission2 = $total * $new_commission2 / 100;
					}
					else
					{
						$commission2 = $total * $customer->commission2 / 100;
					}
				}
				else
				{
					if(isset($new_commission2))
					{
						$commission2 = ($total - $commission1) * ($new_commission2 / 100);
					}
					else
					{
						$commission2 = ($total - $commission1) * ($customer->commission2 / 100);
					}
				}
			}
		?>
		<header class="view-data-header">
			Commission Total 
		</header>
		<section id="edit-container">
			@if($customer->salesman_id1 != null)
				<div class="edit-group" style="padding-top: 10px; padding-left: 23px;">
					<div class="edit-left">
						{{Form::label('commission1', 'Commission 1')}}
					</div><!--
					--><div class="edit-right">
						{{$salesman1->name}}, 
						@if(isset($new_commission1))
							{{$new_commission1}}%<br>
						@else
							{{$customer->commission1}}%<br>
						@endif
						Rp. {{digitGroup($commission1)}}
					</div>
				</div>
			@endif
			@if($customer->salesman_id2 != null)
				<div class="edit-group" style="padding-top: 10px; padding-left: 23px;">
					<div class="edit-left">
						{{Form::label('commission2', 'Commission 2')}}
					</div><!--
					--><div class="edit-right">
						{{$salesman2->name}}, 
						@if($from_net == 'true')
							@if(isset($new_commission1))
								{{$new_commission1}}
							@else
								{{$customer->commission1}}
							@endif
							+
							@if(isset($new_commission2))
								{{$new_commission2}}%
							@else
								{{$customer->commission2}}%
							@endif
						@else
							@if(isset($new_commission2))
								{{$new_commission2}}%
							@else
								{{$customer->commission2}}%
							@endif
						@endif
						<br>
						Rp. {{digitGroup($commission2)}}
					</div>
				</div>
			@endif
		</section>
	@else
		<header class="view-data-header">
			Commission Total
		</header>
	@endif
	<header class="view-data-header">
		Total Payment = Rp. {{digitGroup($total - $total_recycle)}}
	</header>
</section>
