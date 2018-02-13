{{Form::open(array('url'=>URL::to(Crypt::decrypt($setting->admin_url) . '/sales/add-item'), 'method'=>'POST', 'files'=>True, 'class'=>'ajax-form-item', 'id'=>'ajax-form-item'))}}
	<div id="blur-ajax-question">
		<div class="edit-group">
			<div class="edit-left">
				{{Form::label('product_id', 'Item')}}
			</div><!--
			--><div class="edit-right">
				{{Form::select('product_id', $product_options, null, array('class'=>'medium-text select ajax-product', 'required'))}}
				<span class="required-tx">
					*Required
				</span>
			</div>
		</div>

		<div class="hasil-ajax-product">
			<div class="edit-group">
				<div class="edit-left">
					{{Form::label('price', 'Price')}}
				</div><!--
				--><div class="edit-right">
					{{Form::input('number', 'price', 0, array('class'=>'small-text ajax-price', 'required'))}}
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
					{{Form::input('number', 'qty', 1, array('class'=>'small-text ajax-qty', 'required', 'min'=>1))}}
					<span class="required-tx">
						*Numeric, Min = 1
					</span>
				</div>
			</div>
		</div>
		<div style="text-align: center;">
			{{Form::submit('Add', array('class'=>'edit-submit margin', 'id'=>'button-add'))}}
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

	$("#ajax-form-item").validate();
</script>