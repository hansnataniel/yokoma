<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Laporan Piutang</title>
	{{HTML::script('js/jquery-1.8.3.min.js')}}
	<script type="text/javascript">
		
	</script>

	<style type="text/css">
		html{
			margin: 0;
			padding: 0;
		}

		body {
			width: 210mm;
			min-height: 297mm;
			border: solid 1px #000;
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
			width: 60px;
		}

		table {
			width: 100%;
			font-size: 14px;
			margin-top: 10px;
			/*border-right: solid 1px #000;*/
			/*border-bottom: solid 1px #000;*/
		}

		table tr td {
			padding: 5px;
			/*border-left: solid 1px #000;*/
			border-top: solid 1px #000;
		}

		table tr.item-product {
			font-weight: bold;
		}

		table tr.item-title {
			background: rgba(0,0,0,0.2);
			font-weight: bold;
			line-height: 20px;
		}

		table tr.item-title span {
			font-size: 18px;
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

		.summary {
		    margin-top: 25px;
		    margin-bottom: 20px;
		    width: 320px;
		    border: solid 1px #000;
		    padding: 10px 15px;
		    font-size: 18px;
		    line-height: 24px;
		    position: relative;
		    padding-top: 15px;
		}

		.summary strong {
		    background: #fff;
		    position: absolute;
		    top: -14px;
		    left: 0;
		    padding-left: 10px;
		    padding-right: 10px;
		    font-size: 20px;
		}
		.summary div span:first-child {
		    width: 115px;
		    display: inline-block;
		}
	</style>
</head>
<body>
	<header>
		<div style="text-align: center;">
			{{HTML::image('img/logo.png', '', array('id'=>'header-img', 'style'=>'width: 130px;'))}}
			<h1 style="display: inline-block;font-size: 22px;vertical-align: middle;margin: 0;margin-top: -13px;margin-left: 20px;font-family: monospace; text-transform: uppercase">
				{{$setting->name . ' - ' . $branch->name}}
			</h1>
		</div>
		<h3 style="margin: 0;font-size: 13px;font-weight: 100;margin-top: 6px;text-align: center;border-bottom: solid 1px #000;padding-bottom: 10px;">
			{{$branch->address}}, {{'Phone: ' . $branch->phone . ', Email: ' . $branch->email}}
		</h3>
	</header>
	<div class='content'>
		<h1>LAPORAN PIUTANG</h1>
		<div class="report-desc">
			<span>Date</span>
			<span>: {{date('d/m/Y')}}</span>
		</div><br>

		<div class="report-desc">
			<strong>OVERDUE</strong>
		</div>
		<table border="0" style="border-spacing: 0;  margin-bottom: 50px">
			<?php 
				$no = 1 ;
				$total_piutang = 0;
			?>
			@foreach($customers as $customer)
				<?php
					$sales = Sale::where('customer_id', '=', $customer->id)->where('due_date', '<=', date('Y-m-d'))->where('status', '=', 'Waiting for Payment')->get();
				?>
				@if(count($sales) != 0)
					<tr class='item-title'>
						<td colspan="7">
							<span>{{$customer->name}}</span><br>
							{{$customer->address}},
							{{$customer->no_telp}}<br>
							CP: {{$customer->cp_name}},
							{{$customer->cp_no_hp}}
						</td>
					</tr>
					<tr class="item-product">
						<td>#</td>
						<td>No. Nota</td>
						<td>Invoice Date</td>
						<td>Due Date</td>
						<td style="text-align: right;">Amount to Pay</td>
						<td style="text-align: right;">Paid</td>
						<td style="text-align: right;">Owed</th>
					</tr>
					<?php 
						$no=1; 
						$total1 = 0;
						$total2 = 0;
						$total3 = 0;
					?>
					@foreach($sales as $sale)
						@if(date('Y-m-d') >= $sale->due_date)
							<tr class='index-tr' style="height: 30px;">
								<td>{{$no++}}</td>
								<td>{{$sale->no_invoice}}</td>
								<td>{{date('d/m/Y', strtotime($sale->date))}}</td>
								<td style="color: red;">
									{{date('d/m/Y', strtotime($sale->due_date))}}
								</td>
								<td style="text-align: right;">Rp. {{digitGroup($sale->paid)}}</td>
								<td style="text-align: right;">Rp. {{digitGroup($sale->owed)}}</td>
								<td style="color: red; text-align: right;">Rp. {{digitGroup($sale->paid - $sale->owed)}}</td>
							</tr>
							<?php 
								$total1 = $total1 + $sale->paid;
								$total2 = $total2 + $sale->owed;
								$total3 = $total3 + ($sale->paid - $sale->owed);
								$total_piutang = $total_piutang + ($sale->paid - $sale->owed);
							?>
						@endif
					@endforeach
					<tr class="item-product">
						<td colspan="4" style="text-align: right;">Total</td>
						<td style="text-align: right;">Rp. {{digitGroup($total1)}}</td>
						<td style="text-align: right;">Rp. {{digitGroup($total2)}}</td>
						<td style="color: red; text-align: right;">Rp. {{digitGroup($total3)}}</th>
					</tr>
					<tr class='index-tr' style="height: 30px;">
						<td colspan="7"></td>
					</tr>
				@endif
			@endforeach
		</table>

		<div class="report-desc">
			<strong>NOT OVERDUE</strong>
		</div>
		<table border="1" style="border-spacing: 0;">
			<?php $no = 1 ;?>
			@foreach($customers as $customer)
				<?php
					$sales = Sale::where('customer_id', '=', $customer->id)->where('due_date', '>', date('Y-m-d'))->where('status', '=', 'Waiting for Payment')->get();
				?>
				@if(count($sales) != 0)
					<tr class='item-title'>
						<td colspan="7">
							<span>{{$customer->name}}</span><br>
							{{$customer->address}},
							{{$customer->no_telp}}<br>
							CP: {{$customer->cp_name}},
							{{$customer->cp_no_hp}}
						</td>
					</tr>
					<tr class="item-product">
						<td>#</td>
						<td>No. Nota</td>
						<td>Invoice Date</td>
						<td>Due Date</td>
						<td style="text-align: right;">Amount to Pay</td>
						<td style="text-align: right;">Paid</td>
						<td style="text-align: right;">Owed</th>
					</tr>
					<?php 
						$no=1; 
						$total1 = 0;
						$total2 = 0;
						$total3 = 0;
					?>
					@foreach($sales as $sale)
						@if(date('Y-m-d') < $sale->due_date)
							<tr class='index-tr' style="height: 30px;">
								<td>{{$no++}}</td>
								<td>{{$sale->no_invoice}}</td>
								<td>{{date('d/m/Y', strtotime($sale->date))}}</td>
								<td>
									{{date('d/m/Y', strtotime($sale->due_date))}}
								</td>
								<td style="text-align: right;">Rp. {{digitGroup($sale->paid)}}</td>
								<td style="text-align: right;">Rp. {{digitGroup($sale->owed)}}</td>
								<td style="color: red; text-align: right;">Rp. {{digitGroup($sale->paid - $sale->owed)}}</td>
							</tr>
							<?php 
								$total1 = $total1 + $sale->paid;
								$total2 = $total2 + $sale->owed;
								$total3 = $total3 + ($sale->paid - $sale->owed);
								$total_piutang = $total_piutang + ($sale->paid - $sale->owed);
							?>
						@endif
					@endforeach
					<tr class="item-product">
						<td colspan="4" style="text-align: right;">Total</td>
						<td style="text-align: right;">Rp. {{digitGroup($total1)}}</td>
						<td style="text-align: right;">Rp. {{digitGroup($total2)}}</td>
						<td style="color: red; text-align: right;">Rp. {{digitGroup($total3)}}</th>
					</tr>
					<tr class='index-tr' style="height: 30px;">
						<td colspan="7"></td>
					</tr>
				@endif
			@endforeach
		</table>
		<div class="summary">
			<strong>Summary</strong>
			<div>
				<span>Total Piutang</span>
				<span>: Rp. {{digitGroup($total_piutang)}}</span>
			</div>
		</div>
	</div>
	<a target="_blank" href="{{URL::to('account-receivable/pdf/' . $branch->id)}}">
		{{Form::button('Print', array('class'=>'button-print print'))}}
	</a>
	<a href="{{URL::to('account-receivable/excel/' . $branch->id)}}">
		{{Form::button('Excel', array('class'=>'button-print excel'))}}
	</a>
</body>
</html>