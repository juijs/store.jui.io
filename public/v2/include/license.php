<div class="row" style="padding:5px">
	<div class="col col-2">LICENSE </div>
	<div class="col col-10">
		<div class="row">
			<div class="col col-8">
				<select class="input" style='width: 100%;' id="license" <?php if (!$isMy) { ?>disabled<?php } ?> >
					<option value="Apache-2.0">Apache License, Version 2.0</option>
					<option value="gpl-license">GNU General Public License (GPL)</option>
					<option value="AGPL-3.0">GNU AFFERO GENERAL PUBLIC LICENSE, Version 3 (AGPL-3.0)</option>
					<option value="lgpl-license">GNU Library or "Lesser" General Public License (LGPL)</option>
					<option value="MIT" selected>MIT license</option>
					<option value="Artistic-2.0">Artistic License 2.0</option>
					<option value="EPL-1.0">Eclipse Public License</option>
					<option value="BSD-2-Clause">The BSD 2-Clause License</option>
					<option value="MPL-2.0">Mozilla Public License 2.0</option>
					<option value="etc">Other License</option>
					<!--<option value="Beerware">Beerware</option> -->
				</select>
			</div>
			<div class="col col-4" style="padding: 5px;">
				<a class='button button-common button-small' onclick="more_info_license()" style="width: 100%;">
					<i class="icon-link"></i> Read more info
				</a>
			</div>
		</div>
	</div>
</div>
