<div class="edit-group">
	<div class="edit-left">
		{{Form::label('yang_harus_dibayar', 'Yang Harus Dibayar')}}
	</div><!--
	--><div class="edit-right">
		<span class="image-prepend">
			<div>
				Rp.
			</div>
		</span><!--
		-->{{Form::text('yang_harus_dibayar', $sale->paid, array('class'=>'medium-text-prepend numeric readonly', 'readonly'))}}
		<span class="required-tx">
			*Readonly
		</span>
	</div>
</div>

<div class="edit-group">
	<div class="edit-left">
		{{Form::label('Terhutang', 'Terhutang')}}
	</div><!--
	--><div class="edit-right">
		<span class="image-prepend">
			<div>
				Rp.
			</div>
		</span><!--
		-->{{Form::input('number', 'Terhutang', $sale->paid - $sale->owed, array('class'=>'medium-text-prepend readonly', 'readonly'))}}
		<span class="required-tx">
			*Readonly
		</span>
	</div>
</div>

<script type="text/javascript">
	$('.numeric').inputmask('currency', {
		digitsOptional: true,
		groupSize: 3,
		autoGroup: true,
		prefix: '',
		allowMinus: true,
		placeholder: ''
	});
</script>