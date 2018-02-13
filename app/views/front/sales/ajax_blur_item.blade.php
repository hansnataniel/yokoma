{{Form::open(array('url'=>URL::to(Crypt::decrypt($setting->admin_url) . '/sales/add-item'), 'method'=>'POST', 'files'=>True, 'class'=>'ajax-form-item', 'id'=>'ajax-form-item'))}}
	<div id="blur-ajax-question">
		<div class="edit-group">
			<div class="edit-left">
				{{Form::label('product_id', 'Product Name')}}
			</div><!--
			--><div class="edit-right">
				{{Form::select('product_id', $product_options, null, array('class'=>'medium-text select ajax-product', 'required'))}}
				<span class="required-tx">
					*Required
				</span>
			</div>
		</div>

		@if($type == "Good")
			{{Form::hidden('type', 'Product')}}
		@else
			{{Form::hidden('type', 'Second')}}
		@endif

		<div class="hasil-ajax-product">
			<div class="edit-group">
				<div class="edit-left">
					{{Form::label('price', 'Price')}}
				</div><!--
				--><div class="edit-right">
					<span class="image-prepend">
						<div>
							Rp
						</div>
					</span><!--
				 -->{{Form::input('number', 'price', 0, array('class'=>'small-text-prepend ajax-price', 'required'))}}
					<span class="required-tx">
						*Numeric
					</span>
				</div>
			</div>

			@if($type == "Good")
				<div class="edit-group">
					<div class="edit-left">
						{{Form::label('discount1', 'Discount 1')}}
					</div><!--
					--><div class="edit-right">
						{{Form::input('number', 'discount1', 0, array('class'=>'small-text-prepend'))}}<!--
					 --><span class="image-prepend">
							<div>
								%
							</div>
						</span>
						<span class="required-tx">
							*Numeric
						</span>
					</div>
				</div>

				<div class="edit-group">
					<div class="edit-left">
						{{Form::label('discount2', 'Discount 2')}}
					</div><!--
					--><div class="edit-right">
						{{Form::input('number', 'discount2', 0, array('class'=>'small-text-prepend'))}}<!--
					 --><span class="image-prepend">
							<div>
								%
							</div>
						</span>
						<span class="required-tx">
							*Numeric
						</span>
					</div>
				</div>
			@endif

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
		<div style="">
			{{Form::submit('Add', array('class'=>'edit-submit margin', 'id'=>'button-add'))}}
			{{Form::button('Cancel', array('class'=>'edit-submit button-cancel', 'id'=>'button-cancel'))}}
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