<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Laporan Kartu Stock</title>
</head>
<body>
	
	<table>
		<tr>
			<th colspan="7" style="text-align: center;">Laporan Kartu Stock</th>
		</tr>
		
		<tr>
			<td colspan="7" style="text-align: center;">{{$branch->name . ', ' . $branch->address}}</td>
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

		@foreach($products as $product)
			<tr>
				<td colspan="7">Nama Produk: {{$product->name}}</td>
			</tr>

			<?php
				$inventorys = Inventorygood::where('branch_id', '=', $branch->id)->where('product_id', '=', $product->id)->whereBetween('date', array($start_date, $end_date))->orderBy('date', 'asc')->orderBy('id', 'asc')->get();

				$inventory_first = Inventorygood::where('branch_id', '=', $branch->id)->where('product_id', '=', $product->id)->where('date', '<', $start_date)->orderBy('date', 'desc')->orderBy('id', 'desc')->first();
				$total = 0;
			?>

			<tr>
				<th>#</th>
				<th>Tgl.</th>
				{{-- <th>No. Invoice</th> --}}
				<th>Transaksi</th>
				<th>Stock Awal</th>
				<th>Masuk/Keluar</th>
				<th>Stock Akhir</th>
				<th>Catatan</th>
			</tr>

			@if($inventory_first != null)
				<tr>
					<td>1</td>
					<td>{{date('d/m/Y', strtotime($start_date))}}</td>
					{{-- <td>No. Invoice</td> --}}
					<td>Posisi stock akhir</td>
					<td>{{$inventory_first->final_stock}}</td>
					<td>0</td>
					<td>{{$inventory_first->final_stock}}</td>
					<td>-</td>
				</tr>
			@endif

			<?php $no = 2 ;?>
			@if(count($inventorys) != 0)
				@foreach($inventorys as $inventory)
					<tr class="item">
						<td>{{$no++}}</td>
						<td>{{date('d/m/Y', strtotime($inventory->date))}}</td>
						<td>
							@if($inventory->status == 'Sale')
								<?php $saledetail = Salesdetail::find($inventory->trans_id);  ?>
								Penjualan ({{$saledetail->sale->no_invoice}})
							@elseif($inventory->status == 'Cancel')
								<?php $saledetail = Salesdetail::find($inventory->trans_id);  ?>
								Pembatalan Penjualan ({{$saledetail->sale->no_invoice}})
							@elseif($inventory->status == 'Pembelian')
								<?php $purchasedetail = Purchasedetail::find($inventory->trans_id);  ?>
								{{$inventory->status}} ({{$purchasedetail->purchase->no_invoice}})
							@else
								{{$inventory->status}}
							@endif
						</td>
						<td>{{$inventory->last_stock}}</td>
						<td>
							@if(($inventory->status == "Stock In") OR ($inventory->status == "Sale Return") OR ($inventory->status == "Cancel") OR ($inventory->status == "Pembelian"))
								+
							@else
								-
							@endif
							{{$inventory->amount}}
						</td>
						<td>{{$inventory->final_stock}}</td>
						<td>{{$inventory->note}}</td>
					</tr>
				@endforeach
			@endif
			
			<tr>
				<td></td><td></td><td></td><td></td><td></td><td></td><td></td>
			</tr>
		@endforeach
	</table>
</body>
</html>