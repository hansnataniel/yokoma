{{Form::open(array('url'=>URL::to(Crypt::decrypt($setting->admin_url) . '/sales/ajax-update-item'), 'method'=>'GET', 'files'=>True, 'class'=>'ajax-form-update-item', 'id'=>'ajax-form-update-item'))}}
	<div id="blur-ajax-question">
		<div class="edit-group">
			<div class="edit-left">
				{{Form::label('product_id', 'Item')}}
			</div><!--
			--><div class="edit-right">
				{{Form::text('product_id', $item->name, array('class'=>'medium-text update-name readonly', 'readonly'))}}
				{{Form::hidden('item_id', $item->id, array('class'=>'update-item-id'))}}
				<span class="required-tx">
					*Readonly
				</span>
			</div>
		</div>
		<div class="edit-group">
			<div class="edit-left">
				{{Form::label('price', 'Price')}}
			</div><!--
			--><div class="edit-right">
				{{Form::input('number', 'price', $item->price, array('class'=>'small-text update-price', 'required'))}}
				<span class="required-tx">
					*Numeric
				</span>
			</div>
		</div>
		<div class="edit-group">
			<div class="edit-left">
				{{Form::label('qty', 'Qty')}}
			</div><!--
			--><div class="edit-right">
				{{Form::input('number', 'qty', $item->quantity, array('class'=>'small-text update-qty', 'required', 'min'=>1))}}
				<span class="required-tx">
					*Numeric, Min = 1
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