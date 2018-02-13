@if($salesman1 != null)
	<div class="edit-group">
		<div class="edit-left">
			{{Form::label('salesman1', 'Commission 1 ')}}
		</div><!--
		--><div class="edit-right">
			<div style="display: inline-block; vertical-align: top;">
				{{Form::text('salesman1', $salesman1->name, array('class'=>'medium-text readonly', 'readonly'))}}
			</div>
			{{Form::input('number', 'commission1', $customer->commission1, array('class'=>'small-text-prepend commission1', 'placeholder'=>'Commision', 'min'=>0, 'max'=>100))}}<!--
		 --><span class="image-prepend">
				<div>
					%
				</div>
			</span>
		</div>
	</div>
@else
	{{Form::hidden('commission1', 0, array('class'=>'small-text commission1'))}}
@endif
@if($salesman2 != null)
	<div class="edit-group">
		<div class="edit-left">
			{{Form::label('salesman2', 'Commission 2')}}
		</div><!--
		--><div class="edit-right">
			<div style="display: inline-block; vertical-align: top;">
				{{Form::text('salesman2', $salesman2->name, array('class'=>'medium-text readonly', 'readonly'))}}
			</div>
			{{Form::input('number', 'commission2', $customer->commission2, array('class'=>'small-text-prepend commission2', 'placeholder'=>'Commision', 'min'=>0, 'max'=>100))}}<!--
		 --><span class="image-prepend" style="vertical-align: middle;">
				<div>
					%
				</div>
			</span>
			@if($customer->from_net == 1)
				{{Form::checkbox('from_net', 'true', true, array('id'=>'from_net', "style" => "vertical-align: middle;display: inline-block;"))}}
			@else
				{{Form::checkbox('from_net', 'true', false, array('id'=>'from_net', "style" => "vertical-align: middle;display: inline-block;"))}}
			@endif
			{{Form::label('from_net', 'From Net')}}
		</div>
	</div>
@else
	{{Form::hidden('commission2', 0, array('class'=>'small-text commission2'))}}
	{{Form::checkbox('from_net', 'true', false, array('id'=>'from_net', "style" => "display: none;"))}}
@endif