{{Form::open(array('url'=>URL::to(Crypt::decrypt($setting->admin_url) . '/sales/ajax-update-item'), 'method'=>'GET', 'files'=>True, 'class'=>'ajax-form-update-item', 'id'=>'ajax-form-update-item'))}}
	<div id="blur-ajax-question">
		<div class="edit-group">
			<div class="edit-left">
				{{Form::label('sale_id', 'No. Nota')}}
			</div><!--
			--><div class="edit-right">
				{{Form::text('sale_id', $item->name, array('class'=>'medium-text update-name readonly', 'readonly'))}}
				{{Form::hidden('item_id', $item->id, array('class'=>'update-item-id'))}}
				<span class="required-tx">
					*Readonly
				</span>
			</div>
		</div>
		<div class="edit-group">
			<div class="edit-left">
				{{Form::label('price', 'Paid')}}
			</div><!--
			--><div class="edit-right">
				{{Form::input('number', 'price', $item->price, array('class'=>'small-text update-price', 'required', 'min'=>1, 'max'=>$price))}}
				<span class="required-tx">
					*Numeric, <br>
					Max = {{digitGroup($price)}}
				</span>
			</div>
		</div>
		<div class="edit-group">
			<div class="edit-left">
				{{Form::label('pembulatan', 'Penambahan Pembulatan')}}
			</div><!--
			--><div class="edit-right">
				{{Form::input('number', 'pembulatan', $item->pembulatan, array('class'=>'small-text update-pembulatan'))}}
				<span class="required-tx" style="width: 150px;">
					*Numeric, Bisa minus, Contoh: -200, 120
				</span>
			</div>
		</div>
		<div style="text-align: center;">
			{{Form::submit('Edit', array('class'=>'edit-submit margin', 'id'=>'button-add'))}}
			{{Form::button('Cancel', array('class'=>'edit-submit', 'id'=>'button-cancel'))}}
		</div>
	</div>
{{Form::close()}}

<script type="text/javascript">
	$('.select').each(function(){
		var data = $(this).attr('placeholder-data');

		$(this).select2({
			placeholder: data
		});
	});

	$("#ajax-form-update-item").validate();
</script>