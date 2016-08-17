					<div class="editor-panel-full  editor-panel-border  view-component" style="background:#ffffff">

							<div class="pr-title">
								<ul id="tab_pr_settings" class="tab top">
									<li class="active"><a href="#pr-slide">Slides</a></li>
									<li ><a href="#pr-settings">Presentation Settings</a></li>
								</ul>
								<?php if ($isMy) { ?>
								<div style="position:absolute;top:10px; right:10px;display:inline-block;float:right;">
									<button type="button" class="btn delete-slide-btn"><i class="icon-trashcan"></i> 삭제</button>
									<button type="button" class="btn check-slide-secondary"><i class="icon-chevron-right"></i> 하위 슬라이드</button>									
								</div>
								<?php } ?>
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

				<form id="chart_form" action="<?php echo PLUGIN_URL ?>/<?php echo $type ?>/generate.php" method="post" target="chart_frame" enctype="multipart/form-data" style="display:none">
					<input type="hidden" name="slide_code" value="" />
					<input type="hidden" name="pr_settings" value="" />
					<input type="hidden" name="name" value="" />
					<input type="hidden" name="type" value="<?php echo $type ?>" />
					<input type="hidden" name="resources" value="" />
					<input type="hidden" name="selected_num" value="" />
					<input type="hidden" name="preprocessor" value="" />
				</form>
