<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Laporan Komisi Sales</title>
</head>
<body>
	
	<table>
		<tr>
			<th colspan="5" style="text-align: center;">Laporan Komisi Sales</th>
		</tr>
		
		<tr>
			<td colspan="5" style="text-align: center;">{{$branch->name . ', ' . $branch->address}}</td>
		</tr>

		<tr>
			<td></td><td></td><td></td><td></td><td></td>
		</tr>

		<tr>
			<td colspan="5">Dari tanggal: {{date('d-m-Y', strtotime($start_date)) . ' s/d ' . date('d-m-Y', strtotime($end_date))}}</td>
		</tr>

		@foreach($salesmans as $salesman)
			<tr>
				<td colspan="5">Nama Sales: {{$salesman->name}}</td>
			</tr>

			<tr>
				<td></td><td></td><td></td><td></td><td></td>
			</tr>

			<?php
				$total = 0;
			?>
			@if(count($sales) != 0)
				<tr class="item-product">
					<th>#</th>
					<th>Tgl.</th>
					<th>No. Nota</th>
					<th>Customer</th>
					<th>Komisi</th>
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
							<td>{{$commission}}</td>
						</tr>
					@endif
				@endforeach
				<tr class="item-product">
					<th></th>
					<th colspan="3" style="text-align: right;">Total</th>
					<th>{{$total}}</th>
				</tr>
				
				<tr>
					<td></td><td></td><td></td><td></td><td></td>
				</tr>
				<tr>
					<td></td><td></td><td></td><td></td><td></td>
				</tr>
			@endif
		@endforeach
	</table>
</body>
</html>