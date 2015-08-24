<?php

$arr = explode(",", $data['preprocessor']);

$html = $arr[0];
$javascript = $arr[1];
$css = $arr[2];

$data['html_code'] = HtmlPreprocessor($data['html_code'], $html);
$data['css_code'] = CssPreprocessor($data['css_code'], $css);
$data['sample_code'] = JavascriptPreprocessor($data['sample_code'], $javascript);

?>