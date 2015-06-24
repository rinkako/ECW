<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">


    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

	<title>Welcome to CodeIgniter</title>
    <!-- Bootstrap -->
    <link href="/public/css/bootstrap.min.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
	<link href="/public/css/message.css" rel="stylesheet" type="text/css" />
</head>
<body>


	<?php $this->load->view('nav');?>

	<div class="body">
		  
				<div class="window-fetch">
	                <div class="window-dialog">
	                  <div class="window-content">
	                    <div class="window-header">
	                      <h4 class="window-title" style="color:orange;">课件分享码为</h4>
	                    </div>
	                    <div class="window-body">
							<form class="bs-component">
			                <div class="form-group">
			                  <div class="input-group">
			                    <span class="input-group-addon window-addon"><span class="glyphicon glyphicon-save"></span></span>
			                    <texarea type="text" class="form-control" >5EF67CZ</texarea>
			                  </div>
			                </div>
			                </form>
	                    </div>
	                    <div class="window-footer">
	                      <button type="button" class="btn btn-primary">复制</button>
	                      <p>请在ecw.sinaapp.com下载</p>
	                    </div>
	                  </div>
	                </div>
	              </div>
	</div>


    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="/public/js/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="/public/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="/public/js/welcome.js"></script>
</body>
</html>