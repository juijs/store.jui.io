<div class="editor-panel-full  editor-panel-border  view-component" style="background:#ffffff">

		<div class="editor-tool pr-title">

			<?php if ($isMy && !$is_viewer) { ?>
			<a class="add-btn" title="Add a slide"><?php echo get_svg_image('plus') ?> Add Slide</a>
			<a class="delete-slide-btn" title="Delete a slide"><?php echo get_svg_image('trashcan') ?> Remove Slide</a>
			<a class="check-slide-secondary" title="Changing a child"><?php echo get_svg_image('return') ?> Subslide</a>
			<?php } ?>
			<a class="pr-settings" style="float:right;margin-right:20px;" title="Presentation Settings"><?php echo get_svg_image('gear') ?></a>
			<span  class="splitter-toggle right" data-splitter="previewSplitter" data-splitter-toggle="1"  title="Toggle Preview" ><?php echo get_svg_image('right') ?></i></span>  
		</div>

		<div class="pr-content">

				<div id="pr-settings" style="display:none">
					
				</div>
				<div id="pr-slide">

						<div class="slider-items">
							<ul ></ul>
						</div>
						<div class="slider-editor">
							<div class="slider-title">
								<ul id="tab_slide_settings" class="tab bottom">
									<li class="active"><a href="#slider-description">Content</a></li>
									<li class="active"><a href="#slider-notes">Note</a></li>
									<li><a href="#slider-settings">Settings</a></li>
								</ul>
							</div>
							
							<div class="slider-content">
								<div id="slider-description"><textarea id="slide_code"></textarea></div>
								<div id="slider-notes"><textarea id="slide_note"></textarea></div>
								<div id="slider-settings"></div>
							</div>
						</div>

				</div>
		</div>

</div>

<form id="chart_form" action="<?php echo V2_PLUGIN_URL ?>/<?php echo $type ?>/generate.php" method="post" target="chart_frame" enctype="multipart/form-data" style="display:none">
<input type="hidden" name="slide_code" value="" />
<input type="hidden" name="pr_settings" value="" />
<input type="hidden" name="name" value="" />
<input type="hidden" name="type" value="<?php echo $type ?>" />
<input type="hidden" name="resources" value="" />
<input type="hidden" name="selected_num" value="" />
<input type="hidden" name="preprocessor" value="" />
</form>
