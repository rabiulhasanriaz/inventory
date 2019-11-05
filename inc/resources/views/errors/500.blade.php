<!--
Author: W3layouts
Author URL: http://w3layouts.com
-->

<!DOCTYPE html>
<html lang="zxx">

<head>
    <title>{{env('APP_NAME')}}</title>
    <!-- Meta tag Keywords -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="UTF-8" />
    <!-- //Meta tag Keywords -->
    <link rel="stylesheet" href="{{ asset('asset/404/css/style.css') }}" type="text/css" media="all" /><!-- Style-CSS -->
</head>

<body>
    <!-- error -->
	<section class="w3l-error-9">
		<div class="error-page">
			<div class="wrapper-full">
				<div class="main-content">
					<h2>Sorry</h2>
					<h4>Looks like you're lost...</h4>
					<p>500You can either return to the previous page, visit our homepage.</p>
					<!-- buttons -->
					<div class="buttons">
						<a href="{{ url('/') }}" class="btn brk-btn-bg brk-btn">
							Go to Homepage
						</a>
					</div>
				</div>
				<div class="bottom-header">
					<!-- copyright -->
					<div class="copyrights text-center">
						<p>© {{date('Y')}} All rights reserved | Design by <a href="http://iglweb.com/" target="_blank">IGL Web Ltd.</a> </p>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- //error -->
</body>

</html>