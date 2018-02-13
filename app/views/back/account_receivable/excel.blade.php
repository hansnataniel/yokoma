<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Laporan Piutang {{date('d-m-Y')}}</title>
</head>
<body>
	<table>
		<tr>
			<th>No.</th>
			<th>No. Nota</th>
			<th>Nama Member</th>
			<th>Alamat</th>
			<th>Telepon</th>
			<th>Tgl. Nota</th>
			<th>Tgl. Jatuh Tempo</th>
			<th>Jumlah Yang Harus Dibayar</th>
			<th>Jumlah Terbayar</th>
			<th>Sisa Hutang</th>
		</tr>

		<?php $counter = 1; ?>
		@foreach($customers as $customer)
			<?php
				$sales = Sale::where('customer_id', '=', $customer->id)->where('due_date', '<=', date('Y-m-d'))->where('status', '=', 'Waiting for Payment')->get();
			?>
			@if(count($sales) != 0)
				@foreach($sales as $sale)
					<tr>
						<td>{{$counter++}}</td>
						<td>{{$sale->no_invoice}}</td>
						<td>{{$customer->name}}</td>
						<td>{{$customer->address}}</td>
						<td>{{$customer->no_telp}}</td>
						<td>{{date('d/m/Y', strtotime($sale->date))}}</td>
						<td>{{date('d/m/Y', strtotime($sale->due_date))}}</td>
						<td>{{$sale->paid}}</td>
						<td>{{$sale->owed}}</td>
						<td>{{$sale->paid - $sale->owed}}</td>
					</tr>
				@endforeach
				<tr>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
			@endif
		@endforeach
	</table>
</body>
</html>