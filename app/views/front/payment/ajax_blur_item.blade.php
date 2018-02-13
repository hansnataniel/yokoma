{{Form::open(array('url'=>URL::to(Crypt::decrypt($setting->admin_url) . '/sales/add-item'), 'method'=>'POST', 'files'=>True, 'class'=>'ajax-form-item', 'id'=>'ajax-form-item'))}}
	<div id="blur-ajax-question">
		<div class="edit-group">
			<div class="edit-left">
				{{Form::label('sale_id', 'No. Nota')}}
			</div><!--
			--><div class="edit-right">
				{{Form::select('sale_id', $sales_options, null, array('class'=>'medium-text select ajax-sales', 'required'))}}
				<span class="required-tx">
					*Required
				</span>
			</div>
		</div>
		<div class="hasil-ajax-sales">
			
		</div>
		<div class="edit-group">
			<div class="edit-left">
				{{Form::label('pembulatan', 'Penambahan Pembulatan')}}
			</div><!--
			--><div class="edit-right">
				{{Form::input('number', 'pembulatan', null, array('class'=>'small-text'))}}
				<span class="required-tx" style="width: 150px;">
					*Numeric, Bisa minus, Contoh: -200, 120
				</span>
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