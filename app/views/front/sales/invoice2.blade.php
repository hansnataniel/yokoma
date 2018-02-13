<html>
	<head>
		<title>Sales Invoice</title>
		{{HTML::style('css/invoice.css')}}
	</head>
	<body>
		<div class="wrapper">
			<header>
				{{HTML::image('img/logo.png', 'Logo Budijaya', array('class'=>'logo'))}}
				<div class="header-ket">
					<h1>{{$setting->name . ' ' . $branch->name}}</h1>
					<h3>{{$branch->address}}, {{'Phone: ' . $branch->phone . ', Email: ' . $branch->email}}</h3>
					<h3></h3>
				</div>
				<div class="header-ket2">
					<span class="left">No. Invoice</span> <span>: {{$sale->no_invoice}}</span><br>
					<span class="left">Name</span> <span>: {{$sale->customer->name}}</span><br>
					<span class="left">Date</span> <span>: {{date('d/m/Y', strtotime($sale->date))}}</span>
				</div>
			</header>
			<div class="content">
				<div class="content-ket">
					Berikut adalah invoice yang harus Anda bayar:
				</div>
				<table>
					<tr class="table-title">
						<td>#</td>
						<td>Item</td>
						<td>Price</td>
						<td>Quantity</td>
						<td>Subtotal</td>
					</tr>
					<?php $no=1; ?>
					@foreach($saledetails as $saledetail)
						<tr class="table-item">
							<td>{{$no++}}</td>
							<td>{{$saledetail->product->name}}</td>
							<td>Rp. {{digitGroup($saledetail->price)}}</td>
							<td>{{digitGroup($saledetail->qty)}}</td>
							<td>Rp. {{digitGroup($saledetail->subtotal)}}</td>
						</tr>
					@endforeach
					<tr class="table-total">
						<td colspan="4">Total</td>
						<td>Rp. {{digitGroup($sale->price_total)}}</td>
					</tr>
				</table>
			</div>
		</div>
	</body>
</html>