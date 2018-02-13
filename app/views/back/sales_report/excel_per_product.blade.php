<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Laporan Penjualan Per Product</title>
</head>
<body>
	
	<table>
		<tr>
			<th colspan="7" style="text-align: center;">Laporan Penjualan</th>
		</tr>
		
		<tr>
			<td colspan="5" style="text-align: center;">{{$branch->name . ', ' . $branch->address}}</td>
		</tr>

		<tr>
			<td></td><td></td><td></td><td></td><td></td><td></td><td></td>
		</tr>

		<tr>
			<td colspan="7">Group By: Per Produk</td>
		</tr>

		<tr>
			<td colspan="7">Dari tanggal: {{date('d-m-Y', strtotime($start_date)) . ' s/d ' . date('d-m-Y', strtotime($end_date))}}</td>
		</tr>

		<tr>
			<td></td><td></td><td></td><td></td><td></td><td></td><td></td>
		</tr>
		
		<?php
			$product_terjual = 0;
			$total_penjualan = 0;
		?>
		@foreach($products as $product)
			<?php
				$sales = Sale::where('branch_id', '=', $branch->id)->whereBetween('date', array($start_date, $end_date))->where('status', '!=', 'Canceled')->orderBy('id', 'asc')->get();
				$total = 0;
				$price_total = 0;
			?>
			@if(count($sales) != 0)
				<tr>
					<th colspan="7">Nama Produk: {{$product->name}}</th>
				</tr>
				<tr>
					<th>#</th>
					<th>No. Nota</th>
					<th>Tgl.</th>
					<th>Customer</th>
					<th>Harga</th>
					<th>Qty</th>
					<th>Total</th>
				</tr>
				<?php $no = 1 ;?>
				@foreach($sales as $sale)
					<?php
						$saledetails = Salesdetail::where('sale_id', '=', $sale->id)->where('product_id', '=', $product->id)->get();
					?>
					@foreach($saledetails as $saledetail)
						@if($saledetail->type == 'Sales')
							<tr>
								<td>{{$no++}}</td>
								<td>{{$saledetail->sale->no_invoice}}</td>
								<td>{{date('d/m/Y', strtotime($saledetail->sale->date))}}</td>
								<td>{{$saledetail->sale->customer->name}}</td>
								<td>{{$saledetail->price}}</td>
								<td>{{$saledetail->qty}}</td>
								<td>{{$saledetail->subtotal}}</td>
								<?php 
									$total = $total + $saledetail->qty; 
									$price_total = $price_total + $saledetail->subtotal; 
								?>
							</tr>
						@endif
					@endforeach
				@endforeach
				<tr>
					<th></th>
					<th colspan="4" style="text-align: right;">Total Semua Penjualan</th>
					<th>{{$total}}</th>
					<th>{{$price_total}}</th>
				</tr>
				<?php 
					$product_terjual = $product_terjual + $total;
					$total_penjualan = $total_penjualan + $price_total; 
				?>
			@endif
			<tr>
				<td></td><td></td><td></td><td></td><td></td><td></td><td></td>
			</tr>
		@endforeach
	</table>
	<tr>
		<td></td><td></td><td></td><td></td><td></td><td></td><td></td>
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