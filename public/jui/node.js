var jsdom = require("jsdom");
global.document = jsdom.jsdom();
global.window = document.parentWindow;
global.jQuery = global.$ = require("jquery");

require("./dist/jui");

module.exports = global.jui;
