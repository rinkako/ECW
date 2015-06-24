var courses;
$(function() {
	$("#select-classroom").trigger("change");

    $("#upload-form").ajaxForm({
        beforeSend: function() {
        	$(".upload-zone").html("<p>上传中...</p>");
        },
        uploadProgress: function(event, position, total, percentComplete) {
            var percentVal = percentComplete + '%';
			$(".upload-zone").html("<p>上传中(" + percentVal + ")...</p>");
        },
        complete: function(xhr) {
        	$(".upload-zone").html("<p>上传完成</p>");
        	window.location.href = "/index.php/upload/pre_upload_success";
        }
    });

})

$("#upload-form").submit(function(e) {
	if (!checkFileExt()) {
		e.preventDefault();
	} else {
		$(".upload-zone").html("<p>上传中...</p>");
	}

});


$("#select-classroom").change(function() {
	var room = $("#select-classroom").val();

	$.ajax({
		type: 'post',
		url: "/index.php/upload/pre_upload",
		data: {
			classroom: room
		},
		success: function(msg) {
			if (msg['status']) {
				$("#select-courses").html("");
				var ret = msg['data'];
				courses = ret;
				for (var i = 0; i < ret.length; ++i) {
					$("#select-courses").append("<option value='" + ret[i].name + "'>" + ret[i].name + "(" + ret[i].startPeriod + "-" + ret[i].endPeriod + ")" + "</option>");
				}
				$("#select-courses").trigger("change");
			}
		},
		dataType: 'json'
	});
});

$("#select-courses").change(function() {
	var cname = $("#select-courses").val();

	for (var i = 0; i < courses.length; ++i) {
		if (courses[i].name == cname) {
			$("#select-weeks").html("");
			for (var j = parseInt(courses[i].startWeek); j <= parseInt(courses[i].endWeek); ++j) {
				$("#select-weeks").append("<option value='" + j + "'>" + j + "</option>");
			}
		}
	}
});

$(".upload-zone").click(function() {
	$("#upload-file").click();
})

$("#upload-file").change(function() {
	var fakeurl = $("#upload-file").val();
	if (fakeurl == "") {
		$(".upload-zone").html("<p>点击此处上传</p>");
	} else {
	var arr = fakeurl.split("\\");
	var filename = arr[arr.length-1];


	$(".upload-zone").html("<p>" + arr[arr.length-1] + "</p>");
	}
})

var checkFileExt = function () {
	var room = $("#select-classroom").val();
	var courses = $("#select-courses").val();
	var weeks = $("#select-weeks").val();
	if (room == "" || courses == "" || weeks == "" || room == null || courses == null || weeks == null) {
		alert("请先选择教室、课程和展示周，谢谢");
		return false;
	}

	var fakeurl = $("#upload-file").val();
	if (fakeurl == "") {
		$(".upload-zone").html("<p>请点击此处上传</p>");
		return false;
	}

	var arr = fakeurl.split("\\");
	var filename = arr[arr.length-1];

	var exts = filename.split(".");
	var ext = exts[exts.length-1];
	var isValid = ext.match('txt|doc|docx|xls|xlsx|ppt|pptx|pdf|gif|jpg|png|zip|rar');
	if (isValid == null) {
		alert("仅支持格式: txt|doc|docx|xls|xlsx|ppt|pptx|pdf|gif|jpg|png|zip|rar，请重新选择")
		return false;
	} else {
		return true;
	}
	return false;
}