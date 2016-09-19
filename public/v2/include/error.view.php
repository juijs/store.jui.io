window.error_widget = window.error_widget || [];

window.removeError = function() {

	for(var i = 0, len = error_widget.length; i < len; i++) {
		componentCode.removeLineWidget(error_widget[i]);
		sampleCode.removeLineWidget(error_widget[i]);
	}

}

window.editorScrollToLine = function(editor, line) {
	editor.setCursor({line:line,ch:0});
    var height = editor.getScrollInfo().clientHeight; 
    var coords = editor.charCoords({line: line, ch: 0}, "local"); 
    editor.scrollTo(null, (coords.top + coords.bottom - height) / 2); 
}

window.setError = function(type, line, column, message) {
	removeError();

	var msg = $("<div class='lint-error' ><i class='lint-error-icon'>!!</i></div>");
	msg.append(message);
	if (type == 'component') {
		error_widget.push(componentCode.addLineWidget(line-1, msg[0], { coverGutter: false, noHScroll: true}));
		editorScrollToLine(componentCode, line-1);
	} else if (type == 'sample') {
		error_widget.push(sampleCode.addLineWidget(line-1, msg[0], {coverGutter: false, noHScroll: true}));
		editorScrollToLine(sampleCode, line-1);
	}

}