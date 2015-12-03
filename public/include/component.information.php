<div class="editor-panel view-information">

	<div class="editor-tool" style="font-size:13px;">
		<a class='h2' style="display:inline-block"  data-view="information">Information</a>

		<div class="editor-navbar">
		</div>
	</div>

	<div id="tab_contents_1" class="tab-contents editor-info" style="overflow-y:auto">
		<div style="padding:10px">
			<?php if ($isMy) { ?>
			<div class="row" style="padding:5px">
				<div class="col col-2">Access </div>
				<div class="col col-10">
					<label><input type="radio" name="access" value="public" checked onclick="viewAccessMessage()" <?php echo $data['access'] == 'public' ? 'checked' : '' ?>/> Public</label>
					<label><input type="radio" name="access" value="private" onclick="viewAccessMessage()" <?php echo $data['access'] == 'private' ? 'checked' : '' ?>/> Private </label>
					<label><input type="radio" name="access" value="share" onclick="viewAccessMessage()" <?php echo $data['access'] == 'share' ? 'checked' : '' ?>/> Share </label>
					<span id="access_message" style="font-size:11px;padding:5px;"></span>
				</div>
			</div>
			<?php } ?>
			<div class="row" style="padding:5px">
				<div class="col col-2">Name <i class="icon-help" title="Set the file name"></i></div>
				<div class="col col-10"><input type="text" class="input" style="width:100%;" id="name" require="true" <?php if (!$isMy) { ?>disabled<?php } ?> /></div>
			</div>
			<div class="row" style="padding:5px;">
				<div class="col col-2">Title </div>
				<div class="col col-10"><input type="text" class="input" style="width:100%;" id="title"  <?php if (!$isMy) { ?>disabled<?php } ?>  /></div>
			</div>
			<div class="row" style="padding:5px">
				<div class="col col-2">Description </div>
				<div class="col col-10">
					<textarea style="width:100%;height: 100px;" class="input" id="description" <?php if (!$isMy) { ?>disabled<?php } ?> ></textarea>
				</div>
			</div>
			<?php include_once "license.php" ?>
			<input type="hidden" id="sample" name="sample" value="" />
			<input type="hidden" name="resources" value="" />
		</div>
	</div>

</div>
