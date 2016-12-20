<div class="editor-panel-full  editor-panel-border  view-component" style="background:#ffffff">

		<div class="editor-tool pr-title">

			<?php if ($isMy && !$is_viewer) { ?>
			<a class="add-btn" title="Add a slide"><?php echo get_svg_image('plus') ?></a>
			<a class="delete-slide-btn" title="Delete a slide"><?php echo get_svg_image('trashcan') ?></a>
			<a class="check-slide-secondary" title="Changing a child"><?php echo get_svg_image('return') ?></a>
			<?php } ?>
			<a class="pr-settings toolbar-button"  title="Presentation Settings"><?php echo get_svg_image('gear') ?></a>
			<span  class="splitter-toggle right" data-splitter="previewSplitter" data-splitter-toggle="1"  title="Toggle Preview" ><?php echo get_svg_image('right') ?></i></span>  
		</div>

		<div class="pr-content">

				<div id="pr-settings" style="display:none">
					
				</div>
				<div id="pr-slide">

						<div class="slider-items" droppable>
							<ul ></ul>
						</div>
						<div class="slider-editor">
							<div class="slider-title">
								<ul id="tab_slide_settings" class="tab bottom">
									<li class="active"><a href="#slider-description">CONTENT</a></li>
									<li><a href="#slider-styles">STYLE</a></li>
									<li><a href="#slider-notes">NOTE</a></li>
									<li><a href="#slider-settings">OPTIONS</a></li>
								</ul>
							</div>
							
							<div class="slider-content full-layout">
								<div id="slider-description" class="has-help">
									<div class="help">Markdown 으로 Presentation 내용일 입력하실 수 있습니다. 컨텐츠가 변경되면 Preview 화면으로 바로 보실 수 있습니다.</div>
									<div class='content'>
										<textarea id="slide_code"></textarea>
									</div>
								</div>
								<div id="slider-styles" class="has-help">
									<div class="help">하나의 Slider 를 CSS 를 사용해서 다른 Slider 에 상관없이 마음껏 꾸밀 수 있습니다.</div>
									<div class='content'>
										<textarea id="slide_style"></textarea>
									</div>
								</div>
								<div id="slider-notes" class="has-help">
									<div class="help">발표하실 때 필요한 노트는 여기다 적어주세요. Markdown 형태로 하시면 됩니다.</div>
									<div class='content'>
										<textarea id="slide_note"></textarea>
									</div>
								</div>
								<div id="slider-settings">
								
								</div>

							</div>
						</div>

				</div>
		</div>

</div>

<form id="chart_form" action="<?php echo V2_PLUGIN_URL ?>/pr/generate.php" method="post" target="chart_frame" enctype="multipart/form-data" style="display:none">
<input type="hidden" name="slide_code" value="" />
<input type="hidden" name="pr_settings" value="" />
<input type="hidden" name="name" value="" />
<input type="hidden" name="type" value="<?php echo $type ?>" />
<input type="hidden" name="resources" value="" />
<input type="hidden" name="selected_num" value="" />
<input type="hidden" name="preprocessor" value="" />
<input type="hidden" name="export" value="" />
</form>
