@extends('back.template.master')

@section('title')
	New Nota
@stop

@section('head_additional')
	{{HTML::style('css/jquery.datetimepicker.css')}}
	{{HTML::script('js/jquery.datetimepicker.js')}}

	<script>
		$(function(){
			$('.datetimepicker').datetimepicker({
				scrollMonth: false,
				timepicker: false,
				maxDate: 'now',
				format: 'Y-m-d'
			});
			
			$('.numeric').inputmask('currency', {
				digitsOptional: true,
				groupSize: 3,
				autoGroup: true,
				prefix: '',
				allowMinus: true,
				placeholder: ''
			});

			$('.branch-id').live('change', function(){
				$('.blur-loader').show();
				var selected = $('.branch-id option:selected').val();
	            if(selected == '')
	            {
	                selected = 0;
	            }

	            $.ajax({
	                type: "GET",
	                url: "{{URL::to(Crypt::decrypt($setting->admin_url) . '/sales/ajax-branch')}}/"+selected,
	                success: function(msg){
	                    $('.hasil-ajax-branch').html(msg);
	                    var commission1 = $('.commission1').val();
						if(commission1 == '')
			            {
			                commission1 = 0;
			            }
			            
						var commission2 = $('.commission2').val();
						if(commission2 == '')
			            {
			                commission2 = 0;
			            }
			            
						var fromNet = $('#from_net').is(":checked");

			            $.ajax({
			                type: "GET",
			                url: "{{URL::to(Crypt::decrypt($setting->admin_url) . '/sales/ajax-customer')}}/" + selected + '/' + commission1 + '/' + commission2 + '/' + fromNet,
			                success: function(msg){
			                    $('.hasil-ajax-item').html(msg);
			                }
			            });
						$('.blur-loader').hide();
	                }
	            });
			});

			$('.customer-id').live('change', function(){
				$('.blur-loader').show();

				var selected = $('.customer-id option:selected').val();
	            if(selected == '')
	            {
	                selected = 0;
	            }

	            $.ajax({
	                type: "GET",
	                url: "{{URL::to(Crypt::decrypt($setting->admin_url) . '/sales/ajax-salesman')}}/"+selected,
	                success: function(msg){
	                    $('.hasil-ajax-salesman').html(msg);
	                    
			            var commission1 = $('.commission1').val();
						if(commission1 == '')
			            {
			                commission1 = 0;
			            }
			            
						var commission2 = $('.commission2').val();
						if(commission2 == '')
			            {
			                commission2 = 0;
			            }
			            
						var fromNet = $('#from_net').is(":checked");

			            $.ajax({
			                type: "GET",
			                url: "{{URL::to(Crypt::decrypt($setting->admin_url) . '/sales/ajax-customer')}}/" + selected + '/' + commission1 + '/' + commission2 + '/' + fromNet,
			                success: function(msg){
			                    $('.hasil-ajax-item').html(msg);
			                }
			            });
	                }
	            });
				$('.blur-loader').hide();
			});

			$('.ajax-product').live('change', function(){
				var branchId = $('.branch-id option:selected').val();
				var productId = $('.ajax-product option:selected').val();
	            if(productId == '')
	            {
	                productId = 0;
	            }

	            $.ajax({
	                type: "GET",
	                url: "{{URL::to(Crypt::decrypt($setting->admin_url) . '/sales/ajax-product')}}/" + branchId + '/' + productId,
	                success: function(msg){
	                    $('.hasil-ajax-product').html(msg);
	                }
	            });
			});

			$('.add-item-good').live('click', function(){
	            var customerId = $('.customer-id option:selected').val();
	            if(customerId == '')
	            {
	                customerId = 0;
	            }

				$.ajax({
	                type: "GET",
	                url: "{{URL::to(Crypt::decrypt($setting->admin_url) . '/sales/ajax-blur-item')}}/" + customer_id,
	                success: function(msg){
	                    $('#blur-ajax-item-good').html(msg);
						$('#blur-ajax-item-good').show();
	                }
	            });
			});

			$('.add-item-recycle').live('click', function(){
				var customerId = $('.customer-id option:selected').val();
	            if(customerId == '')
	            {
	                customerId = 0;
	            }

				$.ajax({
	                type: "GET",
	                url: "{{URL::to(Crypt::decrypt($setting->admin_url) . '/sales/ajax-blur-item-recycle')}}/" + customerId,
	                success: function(msg){
	                    $('#blur-ajax-item-recycle').html(msg);
						$('#blur-ajax-item-recycle').show();
	                }
	            });
			});

			$('#button-cancel').live('click', function(){
				$('#blur-ajax-item-good').hide();
				$('#blur-ajax-item-recycle').hide();
			});

			$('.ajax-form-item').live('submit', function(e){
				e.preventDefault();
				var customerId = $('.customer-id option:selected').val();
	            if(customerId == '')
	            {
	                customerId = 0;
	            }

	            var commission1 = $('.commission1').val();
				if(commission1 == '')
	            {
	                commission1 = 0;
	            }
	            
				var commission2 = $('.commission2').val();
				if(commission2 == '')
	            {
	                commission2 = 0;
	            }
	            
				var fromNet = $('#from_net').is(":checked");

				var submitData = $(this).serialize();
				$.ajax({
					type: "POST",
					data: submitData,
					url: "{{URL::to(Crypt::decrypt($setting->admin_url) . '/sales/add-item')}}/" + customerId + '/' + commission1 + '/' + commission2 + '/' + fromNet,
					success:function(msg) {
						$('.hasil-ajax-item').html(msg);
						$('#blur-ajax-item').hide();
					}
				});
			});

			$('.delete-item').live('click', function(){
				$('.blur-loader').show();
				var customerId = $('.customer-id option:selected').val();
	            if(customerId == '')
	            {
	                customerId = 0;
	            }

	            var commission1 = $('.commission1').val();
				if(commission1 == '')
	            {
	                commission1 = 0;
	            }
	            
				var commission2 = $('.commission2').val();
				if(commission2 == '')
	            {
	                commission2 = 0;
	            }
	            
				var fromNet = $('#from_net').is(":checked");

				var itemId = $(this).attr('dataId');
				$.ajax({
					type: "GET",
					url: "{{URL::to(Crypt::decrypt($setting->admin_url) . '/sales/ajax-delete-item')}}/" + itemId + '/' + customerId + '/' + commission1 + '/' + commission2 + '/' + fromNet,
					success:function(msg) {
						$('.hasil-ajax-item').html(msg);
						$('.blur-loader').hide();
					}
				});
			});


			$('.update-item').live('click', function(){
				var customerId = $('.customer-id option:selected').val();
	            if(customerId == '')
	            {
	                customerId = 0;
	            }

				var itemId = $(this).attr('dataId');
				var type = $(this).attr('type');
				$.ajax({
	                type: "GET",
	                url: "{{URL::to(Crypt::decrypt($setting->admin_url) . '/sales/ajax-blur-update-item')}}/" + itemId + '/' + customerId,
	                success: function(msg){
						if(type == 'goods')
						{
		                    $('#blur-ajax-item-good').html(msg);
							$('#blur-ajax-item-good').show();
						}
						else
						{
							$('#blur-ajax-item-recycle').html(msg);
							$('#blur-ajax-item-recycle').show();
						}

	                }
	            });
			});

			$('.ajax-form-update-item').live('submit', function(e){
				e.preventDefault();
				var customerId = $('.customer-id option:selected').val();
	            if(customerId == '')
	            {
	                customerId = 0;
	            }

	            var commission1 = $('.commission1').val();
				if(commission1 == '')
	            {
	                commission1 = 0;
	            }
	            
				var commission2 = $('.commission2').val();
				if(commission2 == '')
	            {
	                commission2 = 0;
	            }
	            
				var fromNet = $('#from_net').is(":checked");
				var submitData = $(this).serialize();
				$.ajax({
					type: "POST",
					data: submitData,
					url: "{{URL::to(Crypt::decrypt($setting->admin_url) . '/sales/ajax-update-item')}}/" + customerId + '/' + commission1 + '/' + commission2 + '/' + fromNet,
					success:function(msg) {
						$('.hasil-ajax-item').html(msg);
						$('#blur-ajax-item').hide();
					}
				});
			});

			$('.commission1, .commission2').live('keyup', function(){
				var customerId = $('.customer-id option:selected').val();
	            if(customerId == '')
	            {
	                customerId = 0;
	            }

				var commission1 = $('.commission1').val();
				if(commission1 == '')
	            {
	                commission1 = 0;
	            }
	            
				var commission2 = $('.commission2').val();
				if(commission2 == '')
	            {
	                commission2 = 0;
	            }

				var fromNet = $('#from_net').is(":checked");

				$.ajax({
					type: "GET",
					url: "{{URL::to(Crypt::decrypt($setting->admin_url) . '/sales/ajax-commission')}}/" + customerId + '/' + commission1 + '/' + commission2 + '/' + fromNet,
					success:function(msg) {
						$('.hasil-ajax-item').html(msg);
					}
				});
			});

			$('#from_net').live('click', function(){
				var customerId = $('.customer-id option:selected').val();
	            if(customerId == '')
	            {
	                customerId = 0;
	            }

				var commission1 = $('.commission1').val();
				if(commission1 == '')
	            {
	                commission1 = 0;
	            }
	            
				var commission2 = $('.commission2').val();
				if(commission2 == '')
	            {
	                commission2 = 0;
	            }

				var fromNet = $('#from_net').is(":checked");

				$.ajax({
					type: "GET",
					url: "{{URL::to(Crypt::decrypt($setting->admin_url) . '/sales/ajax-commission')}}/" + customerId + '/' + commission1 + '/' + commission2 + '/' + fromNet,
					success:function(msg) {
						$('.hasil-ajax-item').html(msg);
					}
				});
			});

			$('.button-submit').click(function(){
				$('.submit-done').click();
			});
		});
	</script>

	<style type="text/css">
		.add-item {
			background-image: url("{{URL::to('/img/admin/icon_addnew.png')}}");
			background-repeat: no-repeat;
			background-position: 22px 9px;
		    padding-left: 25px;
		}

		.add-item:hover {
			background-image: url("{{URL::to('/img/admin/icon_addnew.png')}}");
			background-repeat: no-repeat;
			background-position: 22px 9px;
		    padding-left: 25px;
		}

		#blur-ajax-item-good, #blur-ajax-item-recycle {
		    background: #fff;
		    display: none;
		}

		#blur-ajax-question {
	        border: solid 1px #0C0E3B;
		    padding: 30px;
		}

		.edit-submit {
		    width: 160px;
	        margin-left: 1px;
		}

		.submit-done {
			display: none;
		}
	</style>
@stop

@section('page_title')
	<a href="{{URL::to(Session::get('last_url'))}}">{{HTML::image('img/admin/back.png', '', array('class'=>'image-img'))}}</a> New Nota
@stop

@section('help')
	<ul style="padding-left: 18px;">
		<li></li>
	</ul>
@stop

@section('content')
	{{Form::model($sale, array('url' => URL::current(), 'files' => true))}}
		<section id="edit-container">
			<div class="edit-group">
				<div class="edit-left">
					{{Form::label('branch_id', 'Branch')}}
				</div><!--
				--><div class="edit-right">
					{{Form::select('branch_id', $branch_options, null, array('class'=>'medium-text select branch-id', 'required'))}}
					<span class="required-tx">
						*Required
					</span>
				</div>
			</div>

			<div class="hasil-ajax-branch"></div>
		</section>

		{{Form::submit('Save', array('class'=>'edit-submit submit-done'))}}
	{{Form::close()}}
	<section id="edit-container">

		<div class="hasil-ajax-item"></div>

		<div class="edit-group">
			<div class="edit-left">
			</div><!--
			--><div class="edit-right">
				{{Form::button('Save', array('class'=>'edit-submit margin button-submit'))}}
				{{Form::button('Reset', array('class'=>'edit-submit', 'id'=>'reset'))}}
				<section id="blur">
					<div id="blur-question">
						<span id="blur-text">Do you really want to reset this form?</span>
						{{Form::reset('Yes', array('class'=>'blur-submit blur-left'))}}
						{{Form::button('Cancel', array('class'=>'blur-submit cancel'))}}
					</div>
				</section>
			</div>
		</div>
	</section>


@stop