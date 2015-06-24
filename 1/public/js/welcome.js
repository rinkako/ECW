$(function(){
	$(".window-result").hide();

    $(document).bind('keypress', function(e) {
        if(e.keyCode==13){
             $(".result-comment-btn").trigger('click');
         }
    });
	
});

$("#input-code").change(function() {
	var code = $("#input-code").val();

	$.ajax({
		type: 'post', 
		url: '/index.php/download/get_share_course_comments',
		data: {
			cwcode: code
		},
		success: function(msg) {
			ret = JSON.parse(msg);
			if (ret['status'] === false) {
				$(".window-tips").html(ret['msg']);
				$(".window-tips").show();
			} else {
				var paras = ret['data'];
				$(".result-name").html(paras['share_name']);
				$(".result-classroom").html(paras['classroom']);
				$(".result-cname").html(paras['course_name']);
				$(".result-time").html(paras['share_time']);
				$(".result-download").attr("onclick", "window.location.href='" + paras['share_uri'] + "'");

				for (var i = 0; i < paras['comments'].length; ++i) {
					$(".result-comments").append("<div class='result-discuss'><p>" + paras['comments'][i].content + "</p><div class='text-date'>" + paras['comments'][i].time + "</div></div>");
				}

				$(".window-result")[0].id = "share_id_" + paras['share_id'];

				$("#input-code").blur();

				$("#fetch-btn").html("读取中...");
				setTimeout("showResult()", 500);
			}
		}

	})

});

$("#fetch-btn").click(function() {
	$("#input-code").trigger("change");
})

var showResult = function() {
	$(".body").hide();
	$(".window-result").show();
};

$(".result-comment-btn").click(function() {
	var content = $(".new-comment").val();
	var res = $(".window-result")[0].id.split("_");
	var sid = res[res.length - 1];

	if (!parseInt(sid)) {
		alert("非法操作");
		return;
	}


	$.ajax({
		type: 'post', 
		url: '/index.php/download/get_share_course_comments',
		data: {
			share_id: sid,
			content: content,
		},
		success: function(msg) {
			ret = JSON.parse(msg);
			if (ret['status'] === true) {
				$(".result-comments").append("<div class='result-discuss'><p>" + content + "</p><div class='text-date'>" + ret['time'] + "</div></div>")
			}
			$(".new-comment").val("");
			$(".result-comments").animate({scrollTop: $(".result-comments")[0].scrollHeight}, '500', 'swing', function() {});
		}
	})
})