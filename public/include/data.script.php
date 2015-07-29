<script type="text/javascript">
$(function() {


	var sampleCode = window.sampleCode = CodeMirror.fromTextArea($("#sample_code")[0], {
	  mode:  "javascript",
	  lineNumbers : true
	});

	$("#component_load").change(function(e) {
	
		if (e.target.files[0]) {
			var blob = e.target.files[0];
			var reader = new FileReader();

			reader.onloadend = function(evt) {
				componentCode.setValue(evt.target.result); 

				getThemeObject();
			}

			reader.readAsText(blob, "utf-8");
		}

	});

	window.savecode = function savecode() {

		var data = {
			type : 'data',
			id : '<?php echo $_GET['id'] ?>',
            access : $("[name=access]:checked").val(),
			title : $("#title").val(),
			name : $.trim($("#name").val()),
			description : $("#description").val(),
			license : $("#license").val(),
			data_type : $("#data_type").val()
		}

		
		if (data.name == '')
		{
			alert("Input a ID String (ex : my.module.name)");
			return;
		}


		$.post("/save.php", data, function(res) {
			
			if (res.result)
			{
				location.href = '?id=' + res.id; 	
			} else {
				alert(res.message ? res.message : 'Failed to save');
			}
		});
	}

	window.inputData = function inputData() {

		var data = {
			id : '<?php echo $_GET['id'] ?>',
            type : $("#select_data_type").val(),
			data : sampleCode.getValue()
		};

		$.post("/push.php", data, function(res) {
			
			if (res.result)
			{
				$.get('/read.data.php', { componentId : data.id, id : res.id }, function(data) {

					var $items = $(".data-items");
					var content = JSON.stringify(data.content);

					var $div = $("<div class='data-item' />").data(data).html(content).attr('id', 'data-' + data.id);
					$items.append($div);

					location.href = '#data-' + data.id
				});
			} else {
				alert(res.message ? res.message : 'Failed to save');
			}
		});
	}

	window.deletecode = function deletecode () {
		if (confirm("Delete this data?\r\ngood count is also deleted.")) {
			$.post("/delete.php", { id : '<?php echo $_GET['id'] ?>' }, function(res) {
				if (res.result) {
					location.href = '/mylist.php'; 	
				} else {
					alert(res.message ? res.message : 'Failed to delete');
				}
			});
		}
	}


	function loadContent() {
		var id = '<?php echo $_GET['id'] ?>';

		if (id){
			$.get('/read.php', { id : id }, function(data) {
                $("[value=" + data.access + "]").attr('checked', true);
				$("#title").val(data.title);
				$("#name").val(data.name);
				$("#description").val(data.description);
				$("#license").val(data.license || "None");
				$("#data_type").val(data.data_type || "LOG");

				var $items = $(".data-items");
				for(var i = 0; i < data.items.length; i++) {
					var content = JSON.stringify(data.items[i].content);

					var $div = $("<div class='data-item' />").data(data.items[i]).html(content).attr('id', 'data-' + data.items[i].id);
					$items.append($div);
				}
			});
		}

	}


	if ('<?php echo $_GET['id'] ?>')
	{
		loadContent();
	}

});
</script>



<?php include __DIR__."/script.php" ?>
