

<script type="text/javascript">
	$('.select').each(function(){
		var data = $(this).attr('placeholder-data');

		$(this).select2({
			placeholder: data
		});
	});

	$('.datetimepicker').datetimepicker({
		scrollMonth: false,
		timepicker: false,
		maxDate: 'now',
		format: 'Y-m-d'
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
            url: "{{URL::to(Crypt::decrypt($setting->admin_url) . '/import-second-product/ajax-product')}}/" + branchId + '/' + productId,
            success: function(msg){
                $('.hasil-ajax-product').html(msg);
            }
        });
	});
</script>