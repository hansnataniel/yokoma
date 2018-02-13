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
				<td id="header-container" style="padding: 10px 20px;padding-top: 20px;">
					@if (file_exists(public_path() . '/img/logo.png'))
						<div style="text-align: center;">
							{{-- {{HTML::image('img/logo.png', '', array('id'=>'header-img', 'style'=>'width: 130px;'))}} --}}
							<h1 style="display: inline-block;font-size: 20px;vertical-align: middle;margin: 0;margin-top: -13px;margin-left: 20px;font-family: monospace; text-transform: uppercase;width: 375px;">
								{{$setting->name . ' - ' . $branch->name}}
							</h1>
						</div>
						<h3 style="margin: 0;font-size: 13px;font-weight: 100;margin-top: 6px;text-align: center;border-bottom: solid 1px #000;padding-bottom: 10px;">
							{{$branch->address}}, {{'Phone: ' . $branch->phone . ', Email: ' . $branch->email}}
						</h3>
					@else
						<span style="font-size: 40px; color: #808080; font-family: arial;">{{$setting->name}}</span>
					@endif
				</td>
			</tr>
			<tr>
				<td id="section-container" style="padding: 20px;padding-top: 10px;">
					<p>
		                <span>Nota bukti pembayaran piutang yang telah terbayar:</span>
		            </p>
					<table border="0" style="font-size: 14px; color: #595959; font-family: arial; border-spacing: 0px; border: solid 1px #dbdbdb; border-top: none; width: 140mm;">
						<tr>
							<td style="padding: 10px; background: #808080; color: #fff;">
								Form No
							</td>
							<td style="padding: 10px; background: #808080; color: #fff;">
								Customer
							</td>
							<td style="padding: 10px; background: #808080; color: #fff;">
								Tanggal
							</td>
						</tr>
						<tr>
							<td style="padding: 10px">{{$payment->no_invoice}}</td>
							<td style="padding: 10px">{{$payment->customer->name}}</td>
							<td style="padding: 10px">{{date('d/m/Y', strtotime($payment->date))}}</td>
						</tr>
						<tr>
							<td colspan="3">
								<table style="width: 100%; border-spacing: 0px;" border="0">
									<tr>
										<td style="padding: 10px; background: #808080; color: #fff;">
											#
										</td>
										<td style="padding: 10px; background: #808080; color: #fff;">
											No. Nota
										</td>
										<td style="padding: 10px; background: #808080; color: #fff;text-align: right;">
											Terbayar
										</td>
									</tr>

									<?php
										$counter = 0;
									?>
									@foreach ($paymentdetails as $paymentdetail)
										<?php 
											$counter++; 
										?>
										<tr>
											<td style="padding: 7px 10px;line-height: 14px;font-size: 14px;">
												{{$counter}}
											</td>
											<td style="padding: 7px 10px;line-height: 14px;font-size: 14px;">
												{{$paymentdetail->sale->no_invoice}}
											</td>
											<td style="padding: 7px 10px;line-height: 14px;font-size: 14px;text-align: right;">
												Rp. {{digitGroup($paymentdetail->price_payment)}}
											</td>
										</tr>
									@endforeach
									<tr>
										<td colspan="2" style="padding: 10px; background: #808080; color: #fff;text-align: center;">
											Total Pembayaran
										</td>
										<td style="padding: 10px; background: #808080; color: #fff;text-align: right;">
		                                    Rp {{digitGroup($payment->payment_total)}}
										</td>
									</tr>
								</table>
							</td>
							<td></td>
						</tr>
					</table>
					<p style="font-size: 14px;">
						Terbilang : <strong><i>({{terbilang($payment->payment_total, 3)}} Rupiah)</i></strong>
					</p>
					<p style="margin-top: 5px;font-size: 14px;">Keterangan : {{$payment->keterangan != null ? $payment->keterangan : '..............................................................................................................'}}</p>
				</td>
			</tr>
		</table>
	</body>
</html>