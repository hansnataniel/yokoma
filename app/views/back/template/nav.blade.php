<script>
	$(function(){
		$('.nav-sub-link').mouseover(function(){
			$(this).children('.nav-icon-group').show();
		});
		$('.nav-sub-link').mouseout(function(){
			$(this).children('.nav-icon-group').hide();
		});
		$('.toggle').click(function(){
			$('.sub').stop().slideUp();
			$(this).parent().siblings().find('.dropdown1').removeClass('dropdown2');
			$(this).parent().parent().parent().siblings().find('.dropdown1').removeClass('dropdown2');
			$(this).parent().find('.sub').stop().slideToggle();
			$(this).parent().find('.dropdown1').toggleClass('dropdown2');
		});
	});
</script>
<div id="image-group">
	@if ($nmodul != false)
		{{HTML::image('img/admin/icon_menu.png', '', array('class'=>'menu-show nav'))}}
	@endif
	@if ($smodul != false)
		{{HTML::image('img/admin/icon_search.png', '', array('class'=>'menu-show search'))}}
	@endif
	@if ($hmodul != false)
		{{HTML::image('img/admin/icon_help.png', '', array('class'=>'menu-show help'))}}
	@endif
</div>
<div id="nav-image">
	{{HTML::image('img/admin/icon_close_menu.png', '', array('id'=>'menu-close'))}}
</div>
<div class="nav-cover">
	@if ($nmodul != false)
		<div class="subnav" id="navigation">
			@include('back.template.sub_nav')
		</div>
	@endif
	@if ($smodul != false)
		<div class="subnav" id="searching">
			@include('back.template.sub_search')
		</div>
	@endif
	@if ($hmodul != false)
		<div class="subnav" id="helper">
			@include('back.template.sub_help')
		</div>
	@endif
</div>