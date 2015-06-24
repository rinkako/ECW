<html lang="en"><head>
	<meta charset="utf-8">


    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

	<title>ECW下载课件</title>
    <!-- Bootstrap -->
    <link href="/public/css/bootstrap.min.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
	<link href="/public/css/download.css" rel="stylesheet" type="text/css">
<style type="text/css"></style><style type="text/css"></style></head>
<body>

	<?php $this->load->view('nav');?>
	<div class="body">

				<div class="window-fetch">
	                <div class="window-dialog">
	                  <div class="window-content">
	                    <div class="window-header">
	                      <div class="window-title">
	                        <span class="glyphicon glyphicon-file file"></span>
	                        <p><?php echo $share_name; ?></p>
	                      </div>
	                      <div class="window-footer">
	                        <div><p><?php echo $classroom; ?></p><p><?php echo $course_name; ?></p><p><?php echo $share_time; ?></p></div>
	                        <button type="button" onclick=<?php echo "'window.location.href=\"" . $share_uri . "\"'"; ?> class="btn btn-primary">下载</button>
	                      </div>
	                    </div>
	                    <div class="window-body">
			                <div class="form-group">
			                <?php
								foreach ($comments as $com) {
				                  echo "<div class='window-discuss'><p>" . $com->content . "</p><div class='text-date'>" . $com->time . "</div></div>";
								}
							?>
			                  <div class="input-group input-comment">
			                    <input style="height:51px" type="text" class="form-control">
			                    <button type="button" class="btn btn-primary">评论</button>
			                  </div>
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

</body></html>
