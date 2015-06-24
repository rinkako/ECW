<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">


    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

	<title>ECW课件分享系统</title>
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
		<div class="window-search">
		  <div class="body-left">
		  		<div class="description">
		  			<div class="desc-name">Easy courseware</div>
		  			<div class="desc-title">中山大学课件便捷分享系统</div>
		  			<div class="desc-info">SYSU COURSE SHARE PROJECT</div>
		  		</div>
		  </div>

		  <div class="body-right">
				<div class="window-fetch">
	                <div class="window-dialog">
	                  <div class="window-content">
	                    <div class="window-header">
	                      <h4 class="window-title">提取课件 <span class="window-tips"b></span></h4>
	                    </div>
	                    <div class="window-body">
			                <div class="form-group">
			                  <div class="input-group">
			                    <span class="input-group-addon window-addon"><span class="glyphicon glyphicon-save"></span></span>
			                    <input type="text" id="input-code" name="cwcode" class="form-control" placeholder="输入ECW Code提取课件">
			                  </div>
			                </div>
			                <div class="window-footer">
	                      		<button type="submit" id="fetch-btn" name="submit" class="btn btn-primary">提取</button>
	                    	</div>
	                    </div>
	                    
	                  </div>
	                </div>
	              </div>
	        </div>
		</div>

  	</div>

	<div class="window-result">
      <div class="result-fetch">
        <div class="result-dialog">
          <div class="result-content">
            <div class="result-header">
              <div class="result-title">
                <span class="glyphicon glyphicon-file file"></span>
                <p class="result-name"></p>
              </div>
              <div class="result-footer">
                <div><p class="result-classroom"></p><p class="result-cname"></p><p class="result-time"></p></div>
                <button type="button" class="result-download btn btn-primary">下载</button>
              </div>
            </div>
            <div class="result-body">
              <div class="result-comments">
              </div>

              <div class="input-group input-comment result-input-group">
                <input class="new-comment form-control" style="height:51px" type="text">
                <input type="button" class="result-comment-btn btn btn-primary" value="评论" />
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