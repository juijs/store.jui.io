<div style="width:100%">
	<div class="create-panel">
		<div class="form-group">
			<h1>Create a new repository</h1>
			<div class="head-help">
A repository contains all the files for your project, including the revision history.
			</div>
		</div> 

		<hr />
		<div class="form-group">
			<label>Repository</label>
			<input type="text" name="repository-name" style="width:100%" placeholder="Repository Name or Remote Git Repository : ex) https://yourgit.com/sample.git " />
		</div> 
		<div class="form-group">
			<div class="help">Great repository names are short and memorable. Need inspiration? How about literate-broccoli.</div>
		</div>
		<div class="form-group">
			<label>Title <small>(optional)</small></label>
			<input type="text" style="width:100%" name="title"/>
		</div>
		<div class="form-group">
			<label>Description <small>(optional)</small></label>
			<input type="text" style="width:100%" name="description"/>
		</div>
		<hr />
		<div class="form-group">
			<div class="item">
				<label ><input type="radio" name="access" value="public" checked/> Public</label>
				<div class="help">Anyone can see this repository. You choose who can commit.</div>
			</div>
			<div class="item">
				<label ><input type="radio" name="access" value="private" /> Private</label>
				<div class="help">You choose who can see and commit to this repository.
</div>
			</div>

		</div>
		<hr />
		<div class="form-group">
			<div class="item">
				<label ><input type="checkbox" name="init" value="readme"/> Initialize this repository with a README</label>
				<div class="help">This will let you immediately clone the repository to your computer. Skip this step if you’re importing an existing repository.</div>
			</div>

		</div>
		<hr />
		<div class="form-group">
			<a class="button active button-regular create-btn">Create repository</a>
		</div>

	</div>
	
</div>
<script type="text/javascript">
$(function() {
	$(".create-btn").on('click', function () {
		var data = {
			name : $("input[name=repository-name]").val(),
			access :  $("input[name=access]:checked").val(),
			title :  $("input[name=title]").val(),
			description : $("input[name=description]").val(),
			init : $("input[name=init]").prop('checked')
		}

		show_loading('Cloning...');
		$.post('<?php echo V2_PLUGIN_URL ?>/code/create_repository.php', data, function (res) {
			if (res.result) {
				location.href = '/v2/editor.php?id=' + res.id;
			}
		});
	});
});
</script>
<style type="text/css"> 
footer {
	position:fixed;
	left:0px;
	right:0px;
	bottom:0px;
}
</style>
