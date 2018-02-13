<div class="edit-group">
	<div class="edit-left">
		{{Form::label('price', 'Price')}}
	</div><!--
	--><div class="edit-right">
		{{Form::input('number', 'price', $product->price, array('class'=>'small-text', 'required'))}}
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
		{{Form::input('number', 'qty', 1, array('class'=>'small-text', 'required', 'max'=>$stock, 'min'=>1))}}
		<span class="required-tx">
			*Numeric, Max = {{$stock}}, Min = 1
		</span>
	</div>
</div>