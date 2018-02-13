<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Laporan Penerimaan Piutang</title>
</head>
<body>
	
	<table>
		<tr>
			<th colspan="7" style="text-align: center;">Laporan Penerimaan Piutang</th>
		</tr>
		
		<tr>
			<td colspan="5" style="text-align: center;">{{$branch->name . ', ' . $branch->address}}</td>
		</tr>

		<tr>
			<td></td><td></td><td></td><td></td><td></td><td></td><td></td>
		</tr>

		<tr>
			<td colspan="7">Dari tanggal: {{date('d-m-Y', strtotime($start_date)) . ' s/d ' . date('d-m-Y', strtotime($end_date))}}</td>
		</tr>

		<tr>
			<td></td><td></td><td></td><td></td><td></td><td></td><td></td>
		</tr>

		<?php
			$price_total = 0;
		?>
		@if(count($payments) != 0)
			<tr>
				<th>#</th>
				<th>Tgl.</th>
				<th>Form No</th>
				<th>Customer</th>
				<th>No. Nota</th>
				<th>Penerimaan Piutang</th>
				<th>Komisi</th>
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
						<td>{{$paymentdetail->price_payment}}</td>
						<td>
							<?php 
								$sale = Sale::find($paymentdetail->sale_id);

								$salesman1 = Salesman::find($sale->customer->salesman_id1);
								if($salesman1 != null)
								{
									$commission1 = $paymentdetail->price_payment * $sale->commission1 / 100;
									
									echo($salesman1->name . ' : ' . digitGroup($commission1) );
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
									
									echo('<br><br>' . $salesman2->name . ' : ' . digitGroup($commission2));
								}
							?>
						</td>
						<?php 
							$price_total = $price_total + $paymentdetail->price_payment; 
						?>
					</tr>
				@endforeach
			@endforeach
			<tr>
				<td></td>
				<td colspan="4">Total Semua Penerimaan Piutang</td>
				<td>{{$price_total}}</td>
				<td></td>
			</tr>
		@endif
		<tr>
			<td></td><td></td><td></td><td></td><td></td><td></td><td></td>
		</tr>
	</table>
</body>
</html>