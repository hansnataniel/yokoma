{{Form::open(array('url'=>URL::to(Crypt::decrypt($setting->admin_url) . '/sales-return/add-item'), 'method'=>'POST', 'files'=>True, 'class'=>'ajax-form-item'))}}
	<div id="blur-ajax-question">
		<div class="edit-group">
			<div class="edit-left">
				{{Form::label('product_name', 'Product Name')}}
			</div><!--
			--><div class="edit-right">
				{{Form::text('product_name', $saledetail->product->name, array('class'=>'medium-text readonly', 'readonly'))}}
				{{Form::hidden('saledetail_id', $saledetail->id, array('class'=>'ajax-sale'))}}
				<span class="required-tx">
					*Readonly
				</span>
			</div>
		</div>

		<div class="hasil-ajax-product">
			<div class="edit-group">
				<div class="edit-left">
					{{Form::label('price', 'Price')}}
				</div><!--
				--><div class="edit-right">
					{{Form::input('number', 'price', $saledetail->price, array('class'=>'small-text readonly'))}}
					<span class="required-tx">
						*Readonly
					</span>
				</div>
			</div>
			<div class="edit-group">
				<div class="edit-left">
					{{Form::label('qty', 'Qty')}}
				</div><!--
				--><div class="edit-right">
					{{Form::input('number', 'qty', 1, array('class'=>'small-text ajax-qty', 'required', 'min'=>1, 'max'=>$stock))}}
					<span class="required-tx">
						*Numeric, Max = {{$stock}}, Min = 1
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
</script>