<div class="editor-panel view-information">

	<div class="editor-tool" style="font-size:13px;">
		<a class="h2" style="display:inline-block"  data-view="information">Information</a>

		<div style='float:right;cursor:pointer;display:none;' class='close'><i class='icon-close' style='font-size:20px'></i></div>
		<div style="float:right;margin-right:10px;">
			<?php if ($isMy) { ?>
			<a class="btn btn-small" onclick="savecode()">Save</a>
			<a class="btn btn-small" onclick="deletecode()">Delete</a>
			<?php } else { ?>
			<a class="btn btn-small" onclick="forkcode()">Fork</a>
			<?php } ?>
		</div>
	</div>

	<div id="tab_contents_1" class="tab-contents editor-info" style="overflow-y:auto">
		<form style="padding:10px">
			<?php if ($isMy) { ?>
			<div class="row" style="padding:5px">
				<div class="col col-2">Access </div>
				<div class="col col-9">
					<label><input type="radio" name="access" value="public" checked onclick="viewAccessMessage()" /> Public</label>
					<label><input type="radio" name="access" value="private" onclick="viewAccessMessage()"/> Private</label>
					<span id="access_message" style="font-size:11px"></span>
				</div>
			</div>
			<?php } ?>
			<div class="row" style="padding:5px">
				<div class="col col-2"> * ID </div>
				<div class="col col-9"><input type="text" class="input" style="width:100%;" id="name" require="true" <?php if (!$isMy) { ?>disabled<?php } ?> /></div>
			</div>
			<div class="row" style="padding:5px;">
				<div class="col col-2">Title </div>
				<div class="col col-9"><input type="text" class="input" style="width:100%;" id="title"  <?php if (!$isMy) { ?>disabled<?php } ?>  /></div>
			</div>
			<div class="row" style="padding:5px">
				<div class="col col-2">Description </div>
				<div class="col col-9">
					<textarea style="width:100%;height: 100px;" class="input" id="description" <?php if (!$isMy) { ?>disabled<?php } ?> ></textarea>
				</div>
			</div>
			<div class="row" style="padding:5px">
				<div class="col col-2">License </div>
				<div class="col col-9">
					<select class="input" id="license" <?php if (!$isMy) { ?>disabled<?php } ?> >
						<option value="None" selected>None</option>
						<option value="Apache License 2.0">Apache License 2.0</option>
						<option value="GNU General Public License v2.0">GNU General Public License v2.0</option>
						<option value="MIT License">MIT License</option>
					</select>
				</div>
			</div>
			<input type="hidden" id="sample" name="sample" value="" />
		</form>
	</div>

</div>
