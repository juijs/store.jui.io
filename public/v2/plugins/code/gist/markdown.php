<?php

/**
 * $file : file full path 
 * $dir :  root directory 
 * $id : repository id 
 * $cache_file : cache file path 
 * $url_root : url based root path 
 * $relative_path : relative path of file 
 * 
 */

if (!hasCache($file) || $is_new) {
	generateCache($file, HtmlPreprocessor(file_get_contents($file), 'markdown'));
}


?>

<div  class='store-code' data-store-id="<?php echo $id ?>" data-file="<?php echo $filename ?>">
	<div class="content"><?php outputCache($file); ?></div>
	<div class='store-meta'>
			<a href="https://store.jui.io/v2/file/<?php echo $id ?>/<?php echo $filename ?>" style="float:right">view raw</a>
			<a href="https://store.jui.io/v2/view.php?id=<?php echo $id ?>"><?php echo $filename ?></a>
			hosted with ❤ by <a href="https://store.jui.io">JUI Store</a>
	</div>
</div>