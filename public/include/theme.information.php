<div class="editor-panel view-information">

	<div class="editor-tool" style="font-size:13px;">
		<a class="label" data-view="information">Information</a>

		<div style="float:right">
			<?php if ($isMy) { ?>
			<a class="btn btn-small" onclick="savecode()">Save</a>
			<a class="btn btn-small" onclick="deletecode()">Delete</a>
			<?php } else { ?>
			<a class="btn btn-small" onclick="forkcode()">Fork</a>
			<?php } ?>
		</div>
	</div>

	<div id="tab_contents_1" class="tab-contents editor-info" style="overflow-y:auto">
		<div class="form-information" style="padding:10px">
			<div class="row" style="padding:5px">
				<div class="col col-2">Access </div>
				<div class="col col-9">
					<label><input type="radio" name="access" value="public" checked onclick="viewAccessMessage()" /> Public</label>
					<label><input type="radio" name="access" value="private" onclick="viewAccessMessage()"/> Private</label>
					<span id="access_message" style="font-size:11px"></span>
				</div>
			</div>
			<div class="row" style="padding:5px">
				<div class="col col-2"> * ID </div>
				<div class="col col-9"><input type="text" class="input" style="width:100%;" id="name" require="true" /></div>
			</div>
			<div class="row" style="padding:5px;">
				<div class="col col-2">Title </div>
				<div class="col col-9"><input type="text" class="input" style="width:100%;" id="title"  /></div>
			</div>
			<div class="row" style="padding:5px">
				<div class="col col-2">Description </div>
				<div class="col col-9">
					<textarea style="width:100%;height: 100px;" class="input" id="description"></textarea>
				</div>
			</div>
			<input type="hidden" id="sample" name="sample" value="" />
		</div>
	</div>

</div>