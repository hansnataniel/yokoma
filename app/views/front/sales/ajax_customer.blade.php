@if($type == 'select')
	<div class="edit-group">
		<div class="edit-left">
			
		</div><!--
		--><div class="edit-right">
			{{Form::select('customer_id', $customer_options, null, array('class'=>'medium-text select', 'required'))}}
			<span class="required-tx">
				*Required
			</span>
		</div>
	</div>
@else
	<div class="edit-group">
		<div class="edit-left">
			{{Form::label('name', 'Name')}}
		</div><!--
		--><div class="edit-right">
			{{Form::text('name', null, array('class'=>'medium-text', 'required'))}}
			<span class="required-tx">
				*Required
			</span>
		</div>
	</div>
	<div class="edit-group">
		<div class="edit-left">
			{{Form::label('address', 'Address')}}
		</div><!--
		--><div class="edit-right">
			{{Form::text('address', null, array('class'=>'medium-text', 'required'))}}
			<span class="required-tx">
				*Required
			</span>
		</div>
	</div>
	<div class="edit-group">
		<div class="edit-left">
			{{Form::label('no_telp', 'No. Telphone')}}
		</div><!--
		--><div class="edit-right">
			{{Form::text('no_telp', null, array('class'=>'medium-text'))}}
		</div>
	</div>
	<div class="edit-group">
		<div class="edit-left">
			{{Form::label('cp_name', 'CP Name ')}}
		</div><!--
		--><div class="edit-right">
			{{Form::text('cp_name', null, array('class'=>'medium-text'))}}
		</div>
	</div>
	<div class="edit-group">
		<div class="edit-left">
			{{Form::label('cp_no_hp', 'CP No. HP ')}}
		</div><!--
		--><div class="edit-right">
			{{Form::text('cp_no_hp', null, array('class'=>'medium-text'))}}
		</div>
	</div>
	<div class="edit-group">
		<div class="edit-left">
			{{Form::label('salesman1', 'Salesman 1 ')}}
		</div><!--
		--><div class="edit-right">
			<div style="display: inline-block; vertical-align: top;">
				{{Form::select('salesman1', $salesman1_options, null, array('class'=>'medium-text select'))}}
			</div>
			{{Form::text('commission1', null, array('class'=>'small-text-prepend', 'placeholder'=>'Commision'))}}<!--
		 --><span class="image-prepend">
				<div>
					%
				</div>
			</span>
		</div>
	</div>
	<div class="edit-group">
		<div class="edit-left">
			{{Form::label('salesman2', 'Salesman 2')}}
		</div><!--
		--><div class="edit-right">
			<div style="display: inline-block; vertical-align: top;">
				{{Form::select('salesman2', $salesman2_options, null, array('class'=>'medium-text select'))}}
			</div>
			{{Form::input('number', 'commission2', null, array('class'=>'small-text-prepend', 'placeholder'=>'Commision'))}}<!--
		 --><span class="image-prepend" style="vertical-align: middle;">
				<div>
					%
				</div>
			</span>
			{{Form::checkbox('from_net', 'true', false, array('id'=>'from_net', "style" => "vertical-align: middle;display: inline-block;"))}}
			{{Form::label('from_net', 'From Net', array('id'=>'from_net'))}}
		</div>
	</div>
	<div class="edit-group">
		<div class="edit-left">
			{{Form::label('due_date', 'Debt Maturity')}}
		</div><!--
		--><div class="edit-right">
			{{Form::input('number', 'due_date', null, array('class'=>'medium-text-prepend', 'required', 'min'=>1))}}<!--
		 --><span class="image-prepend">
				<div>
					Days
				</div>
			</span>
			<span class="required-tx">
				*Required
			</span>
		</div>
	</div>
@endif

<script type="text/javascript">
	$('.select').each(function(){
		var data = $(this).attr('placeholder-data');

		$(this).select2({
			placeholder: data
		});
	});
</script>
