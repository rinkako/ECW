var courses;
$(function() {
	$("#select-classroom").trigger("change");
})

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
	$(".upload-zone").html("<p>" + arr[arr.length-1] + "</p>");
	}
})