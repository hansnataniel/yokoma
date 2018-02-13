<html style="margin: 0; padding: 0;">
	<head>
		<?php 
			$setting = Setting::first(); 
		?>
		<title>{{$setting->name}}</title>
	</head>
	<body style="margin: 0;">
		<table id="wrapper" style="font-size: 14px; color: #595959; font-family: arial; width: 148mm;">
			<tr>
				<td id="header-container" style="padding: 10px 20px;">
					@if (file_exists(public_path() . '/img/logo.png'))
						<div style="text-align: center;">
							{{HTML::image('img/logo.png', '', array('id'=>'header-img', 'style'=>'width: 130px;'))}}
							<h1 style="display: inline-block;font-size: 22px;vertical-align: middle;margin: 0;margin-top: -13px;margin-left: 20px;font-family: monospace; text-transform: uppercase;width: 375px;">
								{{$setting->name . ' - ' . $branch->name}}
							</h1>
						</div>
						<h3 style="margin: 0;font-size: 13px;font-weight: 100;margin-top: 6px;text-align: center;border-bottom: solid 1px #000;padding-bottom: 10px;">
							{{$branch->address}}, {{'Phone: ' . $branch->phone . ', Email: ' . $branch->email}}
						</h3>
					@else
						<span style="font-size: 40px; color: #0014ff; font-family: arial;">{{$setting->name}}</span>
					@endif
				</td>
			</tr>
			<tr>
				<td id="section-container" style="padding: 20px;padding-top: 10px;">
					<p>
		                <span>Sales Return Data:</span>
		            </p>
					<table border="0" style="font-size: 14px; color: #595959; font-family: arial; border-spacing: 0px; border: solid 1px #dbdbdb; border-top: none; width: 140mm;">
						<tr>
							<td style="padding: 10px; background: #0014ff; color: #fff;">
								No. Invoice
							</td>
							<td style="padding: 10px; background: #0014ff; color: #fff;">
								Customer
							</td>
							<td style="padding: 10px; background: #0014ff; color: #fff;">
								Date
							</td>
						</tr>
						<tr>
							<td style="padding: 10px">{{$salesreturn->no_invoice}}</td>
							<td style="padding: 10px">{{$salesreturn->sale->customer->name}}</td>
							<td style="padding: 10px">{{date('d/m/Y', strtotime($salesreturn->date))}}</td>
						</tr>
						<tr>
							<td colspan="3">
								<table style="width: 100%; border-spacing: 0px;">
									<tr>
										<td style="padding: 10px; background: #0014ff; color: #fff;">
											#
										</td>
										<td style="padding: 10px; background: #0014ff; color: #fff;">
											Name
										</td>
										<td style="padding: 10px; background: #0014ff; color: #fff;text-align: right;">
											Price
										</td>
										<td style="padding: 10px; background: #0014ff; color: #fff;text-align: center;">
											Qty
										</td>
										<td style="padding: 10px; background: #0014ff; color: #fff;text-align: right;">
											Subtotal
										</td>
									</tr>

									<?php
										$counter = 0;
									?>
									@foreach ($salesreturndetails as $salesreturndetail)
										<?php 
											$counter++; 
										?>
										<tr>
											<td style="padding: 7px 10px;line-height: 14px;font-size: 14px;">
												{{$counter}}
											</td>
											<td style="padding: 7px 10px;line-height: 14px;font-size: 14px;">
												{{$salesreturndetail->product->name}}
											</td>
											<td style="padding: 7px 10px;line-height: 14px;font-size: 14px;text-align: right;">
												Rp. {{digitGroup($salesreturndetail->price)}}
											</td>
											<td style="padding: 7px 10px;line-height: 14px;font-size: 14px;text-align: center;">
												{{digitGroup($salesreturndetail->qty)}}
											</td>
											<td style="padding: 7px 10px;line-height: 14px;font-size: 14px;text-align: right;">
												Rp. {{digitGroup($salesreturndetail->subtotal)}}
											</td>
										</tr>
									@endforeach
									<tr>
										<td colspan="4" style="padding: 10px; background: #0014ff; color: #fff;text-align: center;">
											Amout To Pay
										</td>
										<td style="padding: 10px; background: #0014ff; color: #fff;text-align: right;">
		                                    Rp {{digitGroup($salesreturn->price_total)}}
										</td>
									</tr>
								</table>
							</td>
							<td></td>
						</tr>
					</table>
					<p style="font-size: 14px;">
						Terbilang : <strong><i>({{terbilang($salesreturn->price_total, 3)}} Rupiah)</i></strong>
					</p>
					<p style="margin-top: 5px;font-size: 14px;">Keterangan : ..............................................................................................................</p>
				</td>
			</tr>
		</table>
	</body>
</html>