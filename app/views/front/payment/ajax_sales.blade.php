<div class="edit-group">
	<div class="edit-left">
		{{Form::label('owed', 'Owed')}}
	</div><!--
	--><div class="edit-right">
		{{Form::input('number', 'owed', $price, array('class'=>'small-text readonly', 'readonly'))}}
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
		{{Form::input('number', 'price', null, array('class'=>'small-text', 'required', 'min'=>0, 'max'=>$price))}}
		{{Form::hidden('sale_id', $sale->id)}}
		<span class="required-tx">
			*Numeric, Min = 0
		</span>
	</div>
</div>