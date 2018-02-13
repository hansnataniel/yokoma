<html style="margin: 0; padding: 0;">
	<head>
		<?php 
			$setting = Setting::first(); 
		?>
		<title>{{$setting->name}}</title>

		<style type="text/css">
			.print {
			    width: 40px;
			    height: 40px;
			    border: 0;
			    background: #d71d21;
			   	color: #fff;
			    border-radius: 20px;
			    position: fixed;
			    top: 1cm;
			    left: 215mm;
			    cursor: pointer;
			    opacity: 0.7;
			}

			.print:hover {
			    opacity: 1;
			}
		</style>
	</head>
	<body style="margin: 0; width: 210mm; height: 138mm; padding-top: 5mm; font-family: arial; font-size 14px;">
		<table id="wrapper" style="font-size: 14px;color: #000;font-family: arial; width: 100%;font-size: 13px;line-height: 16px;">
			<tr>
				<td id="header-container" style="padding: 0 20px;">
					<div style="text-align: left; width: 50%; display: inline-block; vertical-align: top;">
						{{$setting->name . ' - ' . $branch->name}}<br>
						{{$branch->address}} <br>
						{{$branch->phone}}
					</div><!--
				 --><div style="text-align: left; width: 50%; display: inline-block; vertical-align: top;">
						{{date('d F Y', strtotime($purchase->date))}}<br>
					</div>
	                <div style="margin-top: 10px;">NOTA: {{$purchase->no_invoice}}</div>
				</td>
			</tr>
			<tr>
				<td id="section-container" style="padding: 0 20px;">
					<p>
		            </p>
					<table border="0" style="font-size: 14px; color: #000; font-family: arial; border-spacing: 0px; border-top: none; width: 100%; font-size: 14px;">
						<tr>
							<td colspan="3">
								<table style="width: 100%; border-spacing: 0px;">
									<tr>
										<td style="padding: 5px 10px;font-size: 14px; border-top: solid 1px #000; border-bottom: solid 1px #000;">
											No
										</td>
										<td style="padding: 5px 10px;font-size: 14px; border-top: solid 1px #000; border-bottom: solid 1px #000;">
											Nama Barang
										<td style="padding: 5px 10px;font-size: 14px;text-align: center; border-top: solid 1px #000; border-bottom: solid 1px #000;">
											Qty.Satuan
										</td>
										</td>
										<td style="padding: 5px 10px;font-size: 14px;text-align: right; border-top: solid 1px #000; border-bottom: solid 1px #000;">
											Nilai @
										</td>
										<td style="padding: 5px 10px;font-size: 14px;text-align: right; border-top: solid 1px #000; border-bottom: solid 1px #000;">
											Total
										</td>
									</tr>

									<?php
										$counter = 0;
									?>
									@foreach ($purchasedetails as $purchasedetail)
										<?php 
											$counter++; 
										?>
										<tr>
											<td style="padding: 3px 10px;line-height: 14px;font-size: 14px;">
												{{$counter}}
											</td>
											<td style="padding: 3px 10px;line-height: 14px;font-size: 14px;">
												{{$purchasedetail->product->name}}
											</td>
											<td style="padding: 3px 10px;line-height: 14px;font-size: 14px;text-align: center;">
												{{$purchasedetail->qty}} PCS
											</td>
											<td style="padding: 3px 10px;line-height: 14px;font-size: 14px;text-align: right;">
												{{digitGroup($purchasedetail->price)}}
											</td>
											<td style="padding: 3px 10px;line-height: 14px;font-size: 14px;text-align: right;">
												{{digitGroup($purchasedetail->subtotal)}}
											</td>
										</tr>
									@endforeach

									<?php 
										$max = 9 - $counter;
									?>
									@for($i = 0; $i < $max; $i++)
										<tr>
											<td style="padding: 3px 10px; height: 20px;" colspan="5"></td>
										</tr>
									@endfor

									<tr>
										<td colspan="4" style="padding: 2px 10px; text-align: right;border-top: solid 1px #000; font-size: 14px;">
											Total
										</td>
										<td style="padding: 2px 10px; text-align: right;border-top: solid 1px #000; font-size: 14px;">
		                                    {{digitGroup($purchase->price_total)}}
										</td>
									</tr>
								</table>
							</td>
							<td></td>
						</tr>
					</table>
				</td>
			</tr>
		</table>

		<div style="width: 110px; display: inline-block; vertical-align: top; text-align: center; margin-left: 20px; margin-top: 25px; font-seze: 14px;">
			Tanda terima<br><br><br><br>

			(......................)
		</div>
		<div style="width: 110px; display: inline-block; vertical-align: top; text-align: center; margin-left: 20px; margin-top: 25px; font-seze: 14px;">
			Hormat kami<br><br><br><br>

			(......................)
		</div>
	</body>
</html>