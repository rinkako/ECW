
<html lang="en"><head>
	<meta charset="utf-8">


    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

	<title>ECW上传课件</title>
    <!-- Bootstrap -->
    <link href="/public/css/bootstrap.min.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
	<link href="/public/css/upload.css" rel="stylesheet" type="text/css">
<style type="text/css"></style></head>
<body>


	<?php $this->load->view('nav');?>

	<div class="body">

				<div class="window-fetch">
	                <div class="window-dialog">
	                  <div class="window-content">
	                  <?php echo form_open_multipart('/upload/pre_upload');?>
	                    <div class="window-header">
	                      <div class="window-description">
	                        <p>课室</p>
	                      </div>
	                      <div class="window-select">
	                        <select id="select-classroom" name="classroom" style="width:250px;">
	                        <?php
	                            var_dump($roomList);
	                        	for ($i = 0; $i < count($roomList); ++$i) {
	                        		echo "<option value='" . $roomList[$i] . "'>" . $roomList[$i] . "</option>";
	                        	}
	                        ?>
						   </select>
                            </div>
	                    </div>
	                    <div class="window-header">
	                      <div class="window-description">
	                        <p>课程</p>
	                      </div>
	                      <div class="window-select">
	                        <select id="select-courses" name="course_name" style="width:250px;">

							</select>
                           </div>
	                    </div>
                        <div class="window-header">
	                      <div class="window-description">
	                        <p>展示周</p>
	                      </div>
	                      <div class="window-select">
	                        <select id="select-weeks" name="pre_week" style="width:250px;">

						   </select>
                           </div>
	                    </div>
	                    <div class="window-header">
	                      <div class="window-description">
	                        <p>作者</p>
	                      </div>
	                      <div class="window-select">
	                        <textarea id="input-author" name="pre_author" style="width:250px;" rows="1"></textarea>
                           </div>
	                    </div>
	                        
	                    <div class="window-header">
	                      <div class="window-description">
	                        <p>文件备注</p>
	                      </div>
	                      <div class="window-select">
	                         <textarea id="input-mark" name="pre_remark" style="width:250px;" rows="1"></textarea>
                           </div>
	                    </div>
	                    
	                    <div class="window-upload">
	                      <div class="upload-zone"><p>点击此处上传</p></div>
	                    </div>
	                    <div class="window-footer">
	                      <input id="upload-file" type="file" name="userfile" size="20"/>
	                      <button id="upload-btn" type="submit" class="btn btn-primary">上传</button>
	                    </div>
	                  </form>
	                  </div>
	                </div>
	              </div>
	</div>


    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="/public/js/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="/public/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="/public/js/pre_upload.js"></script>
	<script type="text/javascript" src="/public/js/ajaxfileupload.js"></script>
</body></html>

<!---
<?php echo $error;?>

<?php echo form_open_multipart('upload/pre_upload');?>

<input type="file" name="userfile" size="20" />

<br /><br />

<input type="submit" value="upload" />

</form>
-->