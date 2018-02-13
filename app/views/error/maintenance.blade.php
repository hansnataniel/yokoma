<html>
<head>
	<title>
		Maintenance Mode
	</title>

	{{HTML::style('css/front/style.css')}}
	
	<style type="text/css">
		.error-container {
			position: relative;
			display: block;
			width: 100vw;
			height: 100vh;
		}

		.error-content {
			position: relative;
			width: 100%;
			height: 100%;

			justify-content: center;
			display: flex;
			align-items: center;
		}

		.error-group {
			position: relative;
			display: table;
			width: calc(100% - 20px);
			padding: 30px;
			max-width: 400px;
			text-align: center;
		}

		.error-group img {
			position: relative;
			display: block;
			width: 250px;
			margin: 0px auto 30px;
		}

		.error-group h1 {
			position: relative;
			display: block;
			font-size: 30px;
			font-weight: normal;
			margin: 0px 0px 10px;
		}

		.error-group span {
			position: relative;
			display: block;
			line-height: 22px;
			font-size: 16px;
		}

		.error-group span a {
			color: #f7961e;
		}
	</style>
</head>
<body>
	<div class="error-container">
		<div class="error-content">
			<div class="error-group">
				{{HTML::image('img/budijaya-logo.png', '', array('id'=>'error-img'))}}
				<h1>
					Maintenance Mode
				</h1>
				<span>
					Sorry, this site is under maintenance.<br>
					Please try again in a few minute.
				</span>
			</div>
		</div>
	</div>
</body>
</html>