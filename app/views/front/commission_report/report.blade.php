<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Laporan Komisi Sales</title>

	<style type="text/css">
		html{
			margin: 0;
			padding: 0;
		}

		body {
			width: 210mm;
			min-height: 297mm;
			border: solid 1px #000;
			border-bottom: none;
			padding: 8mm;
			margin: 0;
		}

		h1 {
			text-align: center;
			font-size: 26px;
		}

		.report-desc {
			font-size: 14px;
			line-height: 20px;
		}

		.report-desc span:first-child{
			display: inline-block;
			width: 100px;
		}

		table {
			width: 100%;
			font-size: 14px;
			margin-top: 5px;
			border-right: solid 1px #000;
			border-bottom: solid 1px #000;
		    margin-bottom: 30px;
		}

		table tr td {
			padding: 5px;
			border-left: solid 1px #000;
			border-top: solid 1px #000;
		}

		table tr.item-product {
			font-weight: bold;
		}

		table tr.item-title {
			background: rgba(0,0,0,0.1);
		}

		.button-print {
		    width: 46px;
		    height: 46px;
		    font-size: 14px;
		    border: 0;
		    background: #d71d21;
		    color: #fff;
		    border-radius: 23px;
		    position: fixed;
		    top: 1cm;
		    left: 23cm;
		    cursor: pointer;
		    opacity: 0.7;
		}

		.button-print.excel {
		    top: 2.5cm;
		}

		.button-print:hover {
		    opacity: 1;
		}
	</style>
</head>
<body>
	<header>
		<div style="text-align: center;">
			{{-- {{HTML::image('img/logo.png', '', array('id'=>'header-img', 'style'=>'width: 130px;'))}} --}}
			<h1 style="display: inline-block;font-size: 22px;vertical-align: middle;margin: 0;margin-top: -13px;margin-left: 20px;font-family: monospace; text-transform: uppercase">
				{{$setting->name . ' - ' . $branch->name}}
			</h1>
		</div>
		<h3 style="margin: 0;font-size: 13px;font-weight: 100;margin-top: 6px;text-align: center;border-bottom: solid 1px #000;padding-bottom: 10px;">
			{{$branch->address}}, {{'Phone: ' . $branch->phone . ', Email: ' . $branch->email}}
		</h3>
	</header>
	<div class='content'>
		<h1>Laporan Komisi Sales</h1>

		<div class="report-desc">
			<span>Tanggal</span>
			<span>: {{date('d/m/Y', strtotime($start_date)) . ' s/d ' . date('d/m/Y', strtotime($end_date))}}</span>
		</div>

		@foreach($salesmans as $salesman)
			<div class="report-desc">
				<span>Nama Seles</span>
				<span>: {{$salesman->name}}</span>
			</div>
			<div class="report-desc">
				<span>Alamat</span>
				<span>: {{$salesman->address}}</span>
			</div>
			<table border="0" style="border-spacing: 0;">
				<?php
					$total = 0;
				?>
				@if(count($sales) != 0)
					<tr class="item-product">
						<td>#</td>
						<td>Tgl.</td>
						<td>No. Nota</td>
						<td>Customer</td>
						<td style="text-align: center;">Komisi</td>
					</tr>
					<?php $no = 1 ;?>
					@foreach($sales as $sale)
						@if(($sale->customer->salesman_id1 == $salesman->id) OR $sale->customer->salesman_id2 == $salesman->id)
							<?php
								if($sale->customer->salesman_id1 == $salesman->id)
								{
									$commission = $sale->price_total * $sale->commission1 / 100;
								}
								else
								{
									if($sale->customer->from_net == false)
									{
										$commission = $sale->price_total * $sale->commission2 / 100;
									}
									else
									{
										$commission1 = $sale->price_total * $sale->commission1 / 100;
										$commission = ($sale->price_total - $commission1) * $sale->commission2 / 100;
									}
								}
								
								$total = $total + $commission; 
							?>
							<tr class="item">
								<td>{{$no++}}</td>
								<td>{{date('d/m/Y', strtotime($sale->date))}}</td>
								<td>{{$sale->no_invoice}}</td>
								<td>{{$sale->customer->name}}</td>
								<td style="text-align: right">Rp. {{digitGroup($commission)}}</td>
							</tr>
						@endif
					@endforeach
					<tr class="item-product">
						<td></td>
						<td colspan="3" style="text-align: right;">Total</td>
						<td style="text-align: right">Rp. {{digitGroup($total)}}</td>
					</tr>
				@endif
			</table>
		@endforeach
		@if(count($salesmans) == 1)
			<a target="_blank" href="{{URL::to('commission-report/pdf/' . $salesman->id . '/' . $branch->id . '/' . $start_date . '/' . $end_date)}}">
				{{Form::button('Print', array('class'=>'button-print print'))}}
			</a>
			
			<a href="{{URL::to('commission-report/excel/' . $salesman->id . '/' . $branch->id . '/' . $start_date . '/' . $end_date)}}">
				{{Form::button('Excel', array('class'=>'button-print excel'))}}
		@else
			<a target="_blank" href="{{URL::to('commission-report/pdf/0/' . $branch->id . '/' . $start_date . '/' . $end_date)}}">
				{{Form::button('Print', array('class'=>'button-print print'))}}
			</a>
			<a href="{{URL::to('commission-report/excel/0/' . $branch->id . '/' . $start_date . '/' . $end_date)}}">
				{{Form::button('Excel', array('class'=>'button-print excel'))}}
		@endif
	</div>
</body>
</html>