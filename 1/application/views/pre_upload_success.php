<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">


    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

	<title>ECW上传成功</title>
    <!-- Bootstrap -->
    <link href="/public/css/bootstrap.min.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
	<link href="/public/css/success.css" rel="stylesheet" type="text/css" />
</head>
<body>


	<?php $this->load->view('nav');?>

	<div class="body">
		  
				<div class="window-fetch">
	                <div class="window-dialog">
	                  <div class="window-content">
	                    <div class="window-body">
							<form class="bs-component">
			                <div class="form-group">
			                  <div class="input-group window-success">
			                    <div>
								<span class="glyphicon glyphicon-ok"></span>
			                    上传成功</div>
			                  </div>
			                </div>
			                </form>
			                <div class="window-tips">
	                      	<p>在对应展示周时，课程所在的课室用户端将可以下载你的课件</p>
	                    	</div>
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




<!--
<html>
<head>
<title>Upload Form</title>
</head>
<body>

<h3>Your file was successfully uploaded!</h3>

<ul>
<?php foreach ($upload_data as $item => $value):?>
<li><?php echo $item;?>: <?php echo $value;?></li>
<?php endforeach; ?>
</ul>

<p><?php echo anchor('upload', 'Upload Another File!'); ?></p>

</body>
</html>

-->