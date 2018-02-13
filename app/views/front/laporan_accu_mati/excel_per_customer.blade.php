<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Laporan Penjualan Accu Mati Per Customer</title>
</head>
<body>
	
	<table>
		<tr>
			<th colspan="5" style="text-align: center;">Laporan Penjualan Accu Mati</th>
		</tr>

		<tr>
			<td colspan="5" style="text-align: center;">{{$branch->name . ', ' . $branch->address}}</td>
		</tr>


		<tr>
			<td></td><td></td><td></td><td></td><td></td>
		</tr>

		<tr>
			<td colspan="5">Group By: Per Customer</td>
		</tr>

		<tr>
			<td colspan="5">Dari tanggal: {{date('d-m-Y', strtotime($start_date)) . ' s/d ' . date('d-m-Y', strtotime($end_date))}}</td>
		</tr>

		<tr>
			<td></td><td></td><td></td><td></td><td></td>
		</tr>
		<?php
			$product_terjual = 0;
			$total_penjualan = 0;
		?>
		@foreach($customers as $customer)
			<?php
				$sales = Sale::where('customer_id', '=', $customer->id)->whereIn('id', $sale_customers)->whereBetween('date', array($start_date, $end_date))->where('status', '!=', 'Canceled')->orderBy('id', 'asc')->get();
				$total_qty = 0;
				$total = 0;
			?>
			@if(count($sales) != 0)
				<tr>
					<th colspan="5">{{$customer->name}}</th>
				</tr>
				<tr>
					<td colspan="5">{{$customer->address}}, {{$customer->no_telp}}</td>
				</tr>

				<tr>
					<th>#</th>
					<th>No. Nota</th>
					<th>Tgl.</th>
					<th>Total</th>
					<th>Total Pembayaran</th>
				</tr>
				<?php $no = 1 ;?>
				@foreach($sales as $sale)
					<?php
						$qty = 0;
						$saledetails = Salesdetail::where('sale_id', '=', $sale->id)->where('type', '=', 'Sales')->get();
						foreach ($saledetails as $saledetail) 
						{
							$qty = $qty + $saledetail->qty;
						}
						$total_qty = $total_qty + $qty; 
						$total = $total + $sale->paid; 
					?>
					<tr>
						<td>{{$no++}}</td>
						<td>{{$sale->no_invoice}}</td>
						<td>{{date('d/m/Y', strtotime($sale->date))}}</td>
						<td>{{$qty}}</td>
						<td>{{$sale->paid}}</td>
					</tr>
				@endforeach
				<tr>
					<th></th>
					<th colspan="2" style="text-align: right;">Total</th>
					<th>{{$total_qty}}</th>
					<th>{{$total}}</th>
				</tr>
				<?php
					$product_terjual = $product_terjual + $total_qty;
					$total_penjualan = $total_penjualan + $total;
				?>
			@endif
			<tr>
				<td></td><td></td><td></td><td></td><td></td>
			</tr>
		@endforeach
	</table>
	<tr>
		<td></td><td></td><td></td><td></td><td></td>
	</tr>
	
	<tr>
		<th colspan="2" style="text-align: right;">Summary</th>
	</tr>

	<tr>
		<th>Produk Terjual</th>
		<th>{{digitGroup($product_terjual)}} pcs</th>
	</tr>

	<tr>
		<th>Total Penjualan</th>
		<th>Rp. {{digitGroup($total_penjualan)}}</th>
	</tr>
</body>
</html>