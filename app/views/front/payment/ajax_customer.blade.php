@if($customer != null)
	<div class="edit-group">
		<div class="edit-left">
			{{Form::label('date', 'Date')}}
		</div><!--
		--><div class="edit-right">
			{{Form::text('date', date('Y-m-d'), array('class'=>'medium-text datetimepicker', 'required', 'readonly'))}}
			<span class="required-tx">
				*Required
			</span>
		</div>
	</div>
	<div class="edit-group">
		<div class="edit-left">
			{{Form::label('metode_pembayaran', 'Metode Pembayaran')}}
		</div><!--
		--><div class="edit-right">
			{{Form::radio('metode_pembayaran', 'Cash', true, array('class'=>'quiz-radio', 'id'=>'cash'))}} {{Form::label('cash', 'Cash', array('class'=>'question-label'))}}<br>
			{{Form::radio('metode_pembayaran', 'Transfer', false, array('class'=>'quiz-radio', 'id'=>'transfer'))}} {{Form::label('transfer', 'Transfer', array('class'=>'question-label'))}}<br>
			{{Form::radio('metode_pembayaran', 'Giro', false, array('class'=>'quiz-radio', 'id'=>'giro'))}} {{Form::label('giro', 'Giro', array('class'=>'question-label'))}}
		</div>
	</div>
	<div class="edit-group tgl-pencairan">
		<div class="edit-left">
			{{Form::label('tgl_pencairan', 'Tgl. Pencairan')}}
		</div><!--
		--><div class="edit-right">
			{{Form::text('tgl_pencairan', null, array('class'=>'medium-text datetimepicker2', 'required', 'readonly'))}}
			<span class="required-tx">
				*Required jika metode pembayaran "Giro"
			</span>
		</div>
	</div>
	<div class="edit-group">
		<div class="edit-left">
			{{Form::label('keterangan', 'Keterangan')}}
		</div><!--
		--><div class="edit-right">
			{{Form::textarea('keterangan', null, array('class'=>'large-text area'))}}
		</div>
	</div>
	<div class="edit-group">
		<div class="edit-left">
			{{Form::label('amount_to_pay', 'Amount to Pay')}}
		</div><!--
		--><div class="edit-right">
			<span class="image-prepend">
				<div>
					Rp.
				</div>
			</span><!--
			-->{{Form::input('number', 'amount_to_pay', null, array('class'=>'medium-text-prepend numeric amount-to-pay', 'required'))}}
			<span class="required-tx">
				*Required, Numeric
			</span>
		</div>
	</div>
	{{Form::button('Add Nota', array('class'=>'edit-submit add-item'))}}
	<section class="view-data-info">
		<header class="view-data-header">
			Nota Item's
		</header>
		<article class="view-data-ctn hasil-ajax-item">
			<table id="index-table" style="border-spacing: 0px;width: 60%;">
				<tr class="index-tr index-title">
					<th>#</th>
					<th>No. Nota</th>
					<th>Owed</th>
					<th>Penambahan Pembulatan</th>
					<th>Price</th>
					<th></th>
				</tr>
				<?php
					$counter = 1;
				?>
				@foreach ($items as $item)
					<tr class='index-tr'>
						<td>{{$counter++}}</td>
						<td>{{$item->name}}</td>
						<td>Rp. {{digitGroup($item->owed)}}</td>
						<td>Rp. {{digitGroup($item->pembulatan)}}</td>
						<td>Rp. {{digitGroup($item->price)}}</td>
						<td></td>
					</tr>
				@endforeach
				<tr class='index-tr'>
					<td></td>
					<td></td>
					<td><strong>Total Paid:</strong></td>
					<td>
						<strong>{{digitGroup(Cart::total())}}</strong>
						{{Form::hidden('price_total', Cart::total(), array('class'=>'price-total'))}}
					</td>
					<td></td>
				</tr>
			</table>
		</article>
	</section>

	<script type="text/javascript">

		$('.select').each(function(){
			var data = $(this).attr('placeholder-data');

			$(this).select2({
				placeholder: data
			});
		});

		$('.datetimepicker').datetimepicker({
			scrollMonth: false,
			timepicker: false,
			maxDate: 'now',
			format: 'Y-m-d'
		});

		$('.datetimepicker2').datetimepicker({
			scrollMonth: false,
			timepicker: false,
			format: 'Y-m-d'
		});

		$('.quiz-radio').change(function(){
			var value = $(this).val();
			if(value == 'Giro')
			{
				$('.tgl-pencairan').show();
			}
			else
			{
				$('.tgl-pencairan').hide();
			}
		});

		// $('.ajax-product').live('change', function(){
		// 	var branchId = $('.branch-id option:selected').val();
		// 	var productId = $('.ajax-product option:selected').val();
	 //        if(productId == '')
	 //        {
	 //            productId = 0;
	 //        }

	 //        $.ajax({
	 //            type: "GET",
	 //            url: "{{URL::to(Crypt::decrypt($setting->admin_url) . '/sales/ajax-product')}}/" + branchId + '/' + productId,
	 //            success: function(msg){
	 //                $('.hasil-ajax-product').html(msg);
	 //            }
	 //        });
		// });
	</script>
@else
	{{-- tidak ada hasil --}}
@endif