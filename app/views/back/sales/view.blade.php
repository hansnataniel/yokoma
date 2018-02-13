@extends('back.template.master')

@section('title')
	Nota View
@stop

@section('head_additional')

@stop

@section('page_title')
	<a href="{{URL::to(Session::get('last_url'))}}">{{HTML::image('img/admin/back.png', '', array('class'=>'image-img'))}}</a> Nota View
@stop

@section('help')
	<ul style="padding-left: 18px;">
		<li>Disini anda dapat melihat data Nota secara keseluruhan.</li>
		<li>Gunakan tombol Edit untuk masuk ke halaman Edit Promo.</li>
	</ul>
@stop

@section('content')
	<section id="view-container">
		<div id="view-general-information">
			<div id="view-date">
				<span class="view-status">
					<span class="view-cell">Created at</span><span class="view-cell">:</span> <span class="view-cell text-blue">{{date('l, d F Y G:i:s', strtotime($sale->created_at))}}</span>
				</span>
				<span class="view-status">
					<span class="view-cell">Updated at</span><span class="view-cell">:</span> <span class="view-cell text-blue">{{date('l, d F Y G:i:s', strtotime($sale->updated_at))}}</span>
				</span>
			</div>
			@if (file_exists(public_path() . '/usr/img/sale/' . $sale->id . '_' . Str::slug($sale->name, '_') . '.jpg'))
				{{HTML::image('usr/img/sale/' . $sale->id . '_' . Str::slug($sale->name, '_') . '.jpg', '', array('class'=>'view-photo'))}}
			@endif
		</div>
		<table class="view-information" style="border-spacing: 0px;">
			<tr class="view-tr">
				<td class="view-td view-td-left">
					No. Invoice
				</td><!--
				--><td class="view-td view-td-right">
					{{$sale->no_invoice}}
				</td>
			</tr>
			<tr class="view-tr">
				<td class="view-td view-td-left">
					Date
				</td><!--
				--><td class="view-td view-td-right">
					{{date('d/m/Y', strtotime($sale->date))}}
				</td>
			</tr>
			<tr class="view-tr">
				<td class="view-td view-td-left">
					Costumer
				</td><!--
				--><td class="view-td view-td-right">
					{{$sale->customer->name}}
				</td>
			</tr>
			<tr class="view-tr">
				<td class="view-td view-td-left">
					Amout to Pay
				</td><!--
				--><td class="view-td view-td-right">
					Rp. {{digitGroup($sale->paid)}}
				</td>
			</tr>
			<tr class="view-tr">
				<td class="view-td view-td-left">
					Paid
				</td><!--
				--><td class="view-td view-td-right">
					Rp. {{digitGroup($sale->owed)}}
				</td>
			</tr>
			<tr class="view-tr">
				<td class="view-td view-td-left">
					Owed
				</td><!--
				--><td class="view-td view-td-right">
					Rp. {{digitGroup($sale->paid - $sale->owed)}}
				</td>
			</tr>
			<tr class="view-tr">
				<td class="view-td view-td-left">
					Status
				</td><!--
				--><td class="view-td view-td-right">
					@if($sale->status == 'Waiting for payment')
						<span style="color: orange;">{{$sale->status}}</span>
					@elseif($sale->status == 'Canceled')
						<span style="color: red;">{{$sale->status}}</span>
					@elseif($sale->status == 'Paid')
						<span style="color: green;">{{$sale->status}}</span>
					@else
						<span style="color: blue;">{{$sale->status}}</span>
					@endif
				</td>
			</tr>
			<tr class="view-tr">
				<td class="view-td view-td-left">
					Salesman | Commission
				</td><!--
				--><td class="view-td view-td-right">
					<?php 
						$salesman1 = Salesman::find($sale->customer->salesman_id1);
						if($salesman1 != null)
						{
							$commission1 = $sale->price_total * $sale->commission1 / 100;
							
							echo($salesman1->name . ' | Rp. ' . digitGroup($commission1) );
						}

						$salesman2 = Salesman::find($sale->customer->salesman_id2);
						if($salesman2 != null)
						{
							if($sale->customer->from_net == false)
							{
								$commission2 = $sale->price_total * $sale->commission2 / 100;
							}
							else
							{
								$commission1 = $sale->price_total * $sale->commission1 / 100;
								$commission2 = ($sale->price_total - $commission1) * $sale->commission2 / 100;
							}
							
							echo('<br><br>' . $salesman2->name . ' | Rp. ' . digitGroup($commission2));
						}
					?>
				</td>
			</tr>
		</table>
	</section>

	<section class="view-data-info">
		<header class="view-data-header">
			Nota Details
		</header>
		<article class="view-data-ctn hasil-ajax-item">
			<table id="index-table" style="border-spacing: 0px;width: 80%;">
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
				?>
				@foreach ($saledetails as $saledetail)
					@if($saledetail->type == 'Sales')
						<tr class='index-tr'>
							<td>{{$counter++}}</td>
							<td>{{$saledetail->product->name}}</td>
							<td>Rp. {{digitGroup($saledetail->price)}}</td>
							<td>{{digitGroup($saledetail->discount1)}}%</td>
							<td>{{digitGroup($saledetail->discount2)}}%</td>
							<td>{{digitGroup($saledetail->qty)}}</td>
							<td style="text-align: right;">Rp. {{digitGroup($saledetail->subtotal)}}</td>
							<td></td>
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
						<strong>Rp. {{digitGroup($sale->price_total)}}</strong>
					</td>
					<td></td>
				</tr>
				<tr class='index-tr'>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><strong>Accu Mati Total:</strong></td>
					<td style="text-align: right;">
						<strong>Rp. {{digitGroup($sale->recycle_total)}}</strong>
					</td>
					<td></td>
				</tr>
				<?php 
					$pembulatans = Pembulatan::where('sale_id', '=', $sale->id)->get();
					$total_pembulatan = 0;
				?>
				@if(count($pembulatans) != 0)
					@foreach($pembulatans as $pembulatan)
						<?php $total_pembulatan = $total_pembulatan + $pembulatan->price; ?>
					@endforeach
					<tr class='index-tr'>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td><strong>Penambahan Pembulatan:</strong></td>
						<td style="text-align: right;">
							<strong>Rp. {{digitGroup($total_pembulatan)}}</strong>
						</td>
					</tr>
				@endif
				<tr class='index-tr'>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><strong>Amount To Pay:</strong></td>
					<td style="text-align: right;">
						<strong>Rp. {{digitGroup($sale->paid)}}</strong>
					</td>
					<td></td>
				</tr>
			</table>
		</article>
	</section>

	<?php 
		$paymentdetails = Paymentdetail::where('sale_id', '=', $sale->id)->get();
		$total = 0;
	?>
	@if(count($paymentdetails) != 0)
		<section id="view-container">
			<header class="view-data-header" style="font-size: 32px;">
				Penerimaan Piutang
			</header>
		</section>
		<article class="view-data-ctn hasil-ajax-item">
			<table id="index-table" style="border-spacing: 0px;width: 60%;">
				<tr class="index-tr index-title">
					<th>#</th>
					<th>Form No</th>
					<th>Date</th>
					<th>Paid</th>
				</tr>
				<?php
					$counter = 1;
				?>
				@foreach($paymentdetails as $paymentdetail)
					<tr class='index-tr'>
						<td>{{$counter++}}</td>
						<td>{{$paymentdetail->payment->no_invoice}}</td>
						<td>{{date('d/m/Y', strtotime($paymentdetail->payment->date))}}</td>
						<td style="text-align: right;">Rp. {{digitGroup($paymentdetail->price_payment)}}</td>
						<?php
							$total = $total + $paymentdetail->price_payment;
						?>
					</tr>
				@endforeach
				<tr class='index-tr'>
					<td></td>
					<td></td>
					<td><strong>Total Paid:</strong></td>
					<td style="text-align: right;">
						<strong>Rp. {{digitGroup($total)}}</strong>
					</td>
				</tr>
				<tr class='index-tr'>
					<td></td>
					<td></td>
					<td><strong>Total Owed:</strong></td>
					<td style="text-align: right;">
						<strong>Rp. {{digitGroup($sale->paid - $total)}}</strong>
					</td>
				</tr>
			</table>
		</article>
	@endif

@stop