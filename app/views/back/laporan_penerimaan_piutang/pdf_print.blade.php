<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Laporan Penerimaan Piutang</title>

	<style type="text/css">
		html{
			margin: 0;
			padding: 0;
		}

		body {
			width: 194mm;
			min-height: 297mm;
			border: solid 1px #000;
			border-bottom: none;
			padding: 8mm;
			margin: 0;
		}

		h1 {
			text-align: center;
			font-size: 22px;
		}

		.report-desc {
			font-size: 14px;
			line-height: 20px;
		}

		.report-desc span:first-child{
			display: inline-block;
			width: 60px;
		}

		table {
			width: 100%;
			font-size: 13px;
			margin-top: 10px;
			border-right: solid 1px #000;
			border-bottom: solid 1px #000;
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
			font-weight: bold;
			/*background: rgba(0,0,0,0.1);*/
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
		<h1>LAPORAN PENERIMAAN PIUTANG</h1>
		<div class="report-desc">
			<span>Tanggal</span>
			<span>: {{date('d/m/Y', strtotime($start_date)) . ' - ' . date('d/m/Y', strtotime($end_date))}}</span>
		</div>
		<table border="0" style="border-spacing: 0;">
			<?php
				$price_total = 0;
			?>
			@if(count($payments) != 0)
				<tr class="item-title">
					<td>#</td>
					<td>Tgl.</td>
					<td>Form No</td>
					<td>Customer</td>
					<td>No. Nota</td>
					<td>Penerimaan Piutang</td>
					<td style="border-right: solid 1px #000;">Komisi</td>
				</tr>
				<?php $no = 1 ;?>
				@foreach($payments as $payment)
					<?php
						$paymentdetails = Paymentdetail::where('payment_id', '=', $payment->id)->get();
					?>
					@foreach($paymentdetails as $paymentdetail)
						<tr class="item">
							<td>{{$no++}}</td>
							<td>{{date('d/m/Y', strtotime($payment->date))}}</td>
							<td>{{$payment->no_invoice}}</td>
							<td>{{$payment->customer->name}}</td>
							<td>{{$paymentdetail->sale->no_invoice}}</td>
							<td style="text-align: right;">Rp. {{digitGroup($paymentdetail->price_payment)}}</td>
							<td style="border-right: solid 1px #000;">
								<?php 
									$sale = Sale::find($paymentdetail->sale_id);

									$salesman1 = Salesman::find($sale->customer->salesman_id1);
									if($salesman1 != null)
									{
										$commission1 = $paymentdetail->price_payment * $sale->commission1 / 100;
										
										echo($salesman1->name . ' : Rp. ' . digitGroup($commission1) );
									}

									$salesman2 = Salesman::find($sale->customer->salesman_id2);
									if($salesman2 != null)
									{
										if($sale->customer->from_net == false)
										{
											$commission2 = $paymentdetail->price_payment * $sale->commission2 / 100;
										}
										else
										{
											$commission1 = $paymentdetail->price_payment * $sale->commission1 / 100;
											$commission2 = ($paymentdetail->price_payment - $commission1) * $sale->commission2 / 100;
										}
										
										echo('<br><br>' . $salesman2->name . ' : Rp. ' . digitGroup($commission2));
									}
								?>
							</td>
							<?php 
								$price_total = $price_total + $paymentdetail->price_payment; 
							?>
						</tr>
					@endforeach
				@endforeach
				<tr class="item-product">
					<td colspan="5" style="text-align: right; border-bottom: solid 1px #000;">Total Semua Penjualan</td>
					<td style="text-align: right; border-bottom: solid 1px #000;">Rp. {{digitGroup($price_total)}}</td>
					<td style="border-right: solid 1px #000; border-bottom: solid 1px #000;"></td>
				</tr>
			@endif
		</table>
	</div>
</body>
</html>