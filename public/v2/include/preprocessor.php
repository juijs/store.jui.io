<?php

$arr = explode(",", $data['preprocessor']);

$html = $arr[0];
$javascript = $arr[1];
$css = $arr[2];

$data['html_code'] = HtmlPreprocessor($data['html_code'], $html);

$temp_css_code = $data['css_code'];
if ($css == 'less') {
	$temp_css_code = file_get_contents(ABSPATH."/jui-all/jui/less/mixins.less").PHP_EOL.$temp_css_code;
}

$data['css_code'] = CssPreprocessor($temp_css_code, $css);
$data['sample_code'] = JavascriptPreprocessor($data['sample_code'], $javascript);

?>
