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
	 -->{{Form::input('number', 'price', $product->price, array('class'=>'small-text-prepend ajax-price', 'required'))}}
		<span class="required-tx">
			*Numeric
		</span>
	</div>
</div>

@if($product->type == 'Product')
	<div class="edit-group">
		<div class="edit-left">
			{{Form::label('discount1', 'Discount 1')}}
		</div><!--
		--><div class="edit-right">
			{{Form::input('number', 'discount1', 0, array('class'=>'small-text-prepend', 'required'))}}<!--
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
			{{Form::input('number', 'discount2', 0, array('class'=>'small-text-prepend', 'required'))}}<!--
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
		{{Form::input('number', 'qty', 1, array('class'=>'small-text', 'required', 'min'=>1))}}
		<span class="required-tx">
			*Numeric, Min = 1
		</span>
	</div>
</div>