<script type="text/javascript">

$(function() {

	var fileListWin = window.fileListWin =  jui.create("ui.window", "#file-list", {
		width : 700,
		height : 500,
		modal : true,
		event : {
			apply : function() {
				this.hide();
			}
		}
	});

	var resourceTab = jui.create('ui.tab', ".import-toolbar", {
		target: ".import-content",
	});

});
</script>

<div id="file-list" class='window <?php echo $isMy ? 'my' : '' ?>' style='display:none'>
    <div class="head">
        <div class="left" style="letter-spacing:1px ;"><i class="icon-tool"></i> PROJECT INFO</div>
        <div class="right">
            <a href="#" class="close"><i class="icon-exit"></i></a>
        </div>
    </div>
	<div class="body" style="padding:10px;">
		<div style="position:relative;width:100%;height:100%">
		<ul class="tab import-toolbar">
			<li class='active'><a href="#information" style="letter-spacing:1px">INFORMATION</a></li>
		</ul>
		<div id="tab_contents_1" class='import-content'>
			<div id="information">
				<div>
					<div style="padding:10px">
						<?php if ($isMy) { ?>
						<div class="row" style="padding:5px">
							<div class="col col-2">ACCESS </div>
							<div class="col col-10">
								<label><input type="radio" name="access" value="public" checked onclick="viewAccessMessage()" <?php echo $data['access'] == 'public' ? 'checked' : '' ?>/> Public</label>
								<label><input type="radio" name="access" value="private" onclick="viewAccessMessage()" <?php echo $data['access'] == 'private' ? 'checked' : '' ?>/> Private </label>
								<label><input type="radio" name="access" value="share" onclick="viewAccessMessage()" <?php echo $data['access'] == 'share' ? 'checked' : '' ?>/> Share </label>
								<span id="access_message" style="font-size:11px;padding:5px;"></span>
							</div>
						</div>
						<?php } ?>
						<div class="row" style="padding:5px">
							<div class="col col-2">NAME <i class="icon-help" title="Set the file name"></i></div>
							<div class="col col-10"><input type="text" class="input" style="width:100%;" id="name" require="true" <?php if (!$isMy) { ?>disabled<?php } ?> /></div>
						</div>
						<div class="row" style="padding:5px;">
							<div class="col col-2">TITLE </div>
							<div class="col col-10"><input type="text" class="input" style="width:100%;" id="title"  <?php if (!$isMy) { ?>disabled<?php } ?>  /></div>
						</div>
						<div class="row" style="padding:5px">
							<div class="col col-2">DESCRIPTION </div>
							<div class="col col-10">
								<textarea style="width:100%;height: 100px;" class="input" id="description" <?php if (!$isMy) { ?>disabled<?php } ?> ></textarea>
							</div>
						</div>
						<?php include_once "include/license.php" ?>
					</div>
				</div>
			</div>
		</div>
		</div>
	</div>
	<div class="foot" style="text-align:right;padding:15px;box-sizing:border-box;">
		<a href="#" class="button button-common  button-small close" onclick="fileListWin.hide()">Close</a>
		<a href="#" class="button  button-small active" onclick="fileListWin.emit('apply')">Apply</a>
	</div>
</div>

