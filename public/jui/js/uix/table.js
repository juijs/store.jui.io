jui.define("uix.table.column", [ "jquery" ], function($) {

    /**
     * @class uix.table.column
     * @alias Table Column
     * @requires jquery
     */
    var Column = function(index) {
        /** @property {HTMLElement} [element=null] TH element of a specified column */
        this.element = null;

        /** @property {String} [order="asc"] Column sort state */
        this.order = "asc";

        /** @property {Integer} [name=null] Column name */
        this.name = null;

        /** @property {Array} data Data from all rows belonging for a specified column */
        this.data = [];

        /** @property {Array} list TD element of all rows belonging to a specified column */
        this.list = [];

        /** @property {Integer} index Column index */
        this.index = index;

        /** @property {"show"/"hide"/"resize"} [type="show"] The current column state */
        this.type = "show";

        /** @property {Integer} [width=null] Column width */
        this.width = null;

        this.hide = function() {
            this.type = "hide";
            $(this.element).hide();
        }

        this.show = function() {
            this.type = "show";
            $(this.element).show();
        }
    }

    return Column;
});


jui.define("uix.table.row", [ "jquery" ], function($) {

    /**
     * @class uix.table.row
     * @alias Table Row
     * @requires jquery
     */
    var Row = function(data, tplFunc, pRow) {
        var self = this,
            cellkeys = {}; // 숨겨진 컬럼 인덱스 키

        /** @property {Array} data Data of a specifiedrow. */
        this.data = data;

        /** @property {Integer} [rownum=null] The unique number of a child row under the specified parent row if a parent row exists. */
        this.rownum = null;

        /** @property {String/Integer} [index=null] Index of a specified row. In the case of a tree structure, a depth is given. */
        this.index = null;		// 계층적 구조를 수용할 수 있는 키값

        /** @property {HTMLElement} [element=null] TR element of a specified row. */
        this.element = null;

        /** @property {Array} list List of TD elements of a specified row. */
        this.list = [];			// 자신의 로우에 포함된 TD 태그 목록

        /** @property {uix.table.row} parent Variable that refers to the parent row. */
        this.parent = (pRow) ? pRow : null;

        /** @property {Array} children List of child rows. */
        this.children = [];

        /** @property {Integer} [depth=0] The depth of the current row in the case of a tree structure. */
        this.depth = 0;

        /** @property {"open"/"fold"} [type="fold"] State value that indicates whether a child row is shown or hidden. */
        this.type = "fold";

        function setIndex(rownum) {
            self.rownum = (!isNaN(rownum)) ? rownum : self.rownum;

            if(!self.parent) self.index = "" + self.rownum;
            else self.index = self.parent.index + "." + self.rownum;

            // 뎁스 체크
            if(self.parent && typeof(self.index) == "string") {
                self.depth = self.index.split(".").length - 1;
            }

            // 자식 인덱스 체크
            if(!self.isLeaf()) {
                setIndexChild(self);
            }
        }

        function setIndexChild(row) {
            var clist = row.children;

            for(var i = 0; i < clist.length; i++) {
                clist[i].reload(i);

                if(!clist[i].isLeaf()) {
                    setIndexChild(clist[i]);
                }
            }
        }

        function setElementCells() {
            self.list = [];

            $(self.element).find("td").each(function(i) {
                self.list[i] = this;

                if(cellkeys[i]) {
                    this.style.display = "none";
                }
            });
        }

        function getElement() {
            if(!tplFunc) return self.element;

            var element = $(tplFunc(
                    $.extend({ row: { index: self.index, data: self.data, depth: self.depth } }, self.data))
            ).get(0);

            return element;
        }

        function removeChildAll(row) {
            $(row.element).remove();

            for(var i = 0; i < row.children.length; i++) {
                var c_row = row.children[i];

                if(!c_row.isLeaf()) {
                    removeChildAll(c_row);
                } else {
                    $(c_row.element).remove();
                }
            }
        }

        function reloadChildAll() {
            for(var i = 0; i < self.children.length; i++) {
                self.children[i].reload(i);
            }
        }

        this.reload = function(rownum, isUpdate, columns) {
            if(!isUpdate) setIndex(rownum); // 노드 인덱스 설정

            if(this.element != null) {
                var newElem = getElement(),
                    clsValue = $(this.element).attr("class");

                $(newElem).addClass(clsValue).insertAfter(this.element);
                $(this.element).remove();

                this.element = newElem;
            } else {
                this.element = getElement();
            }

            if(columns != null) { // 컬럼 정보가 있을 경우, 숨기기 설정
                this.hideCells(columns);
            }

            setElementCells();
        }

        this.destroy = function() {
            if(this.parent != null) { // 부모가 있을 경우, 연결관계 끊기
                this.parent.removeChild(this.index);
            } else {
                removeChildAll(this);
                $(this.element).remove();
            }
        }

        this.isLeaf = function() {
            return (this.children.length == 0) ? true : false;
        }

        this.fold = function() {
            this.type = "fold";

            for(var i = 0; i < this.children.length; i++) {
                var c_row = this.children[i];
                $(c_row.element).hide();

                if(!c_row.isLeaf()) c_row.fold();
            }
        }

        this.open = function() {
            this.type = "open";

            for(var i = 0; i < this.children.length; i++) {
                var c_row = this.children[i];
                $(c_row.element).show();

                if(!c_row.isLeaf()) c_row.open();
            }
        }

        this.appendChild = function(row) {
            var lastElem = (this.isLeaf()) ? this.element : this.lastChildLeaf().element;
            $(row.element).insertAfter(lastElem);

            this.children.push(row);
        }

        this.insertChild = function(rownum, row, isReload) {
            var lastElem = this.element;

            if(rownum > 0) {
                var cRow = this.children[rownum - 1];

                // 마지막 자식이거나 대상 로우가 자식이 있을 경우
                if(!cRow.isLeaf() || this.children.length == rownum + 1) {
                    lastElem = cRow.lastChildLeaf().element;
                } else {
                    lastElem = cRow.element;
                }

            }

            $(row.element).insertAfter(lastElem);

            var preRows = this.children.splice(0, rownum);
            preRows.push(row);

            this.children = preRows.concat(this.children);
            reloadChildAll();
        }

        this.removeChild = function(index) {
            for(var i = 0; i < this.children.length; i++) {
                var row = this.children[i];

                if(row.index == index) {
                    this.children.splice(i, 1); // 배열에서 제거
                    removeChildAll(row);
                }
            }

            reloadChildAll();
        }

        this.lastChild = function() {
            if(!this.isLeaf())
                return this.children[this.children.length - 1];

            return null;
        }

        this.lastChildLeaf = function(lastRow) {
            var row = (!lastRow) ? this.lastChild() : lastRow;

            if(row.isLeaf()) return row;
            else {
                return this.lastChildLeaf(row.lastChild());
            }
        }

        this.showCell = function(index) {
            cellkeys[index] = false;
            $(this.list[index]).show();
        }

        this.hideCell = function(index) {
            cellkeys[index] = true;
            $(this.list[index]).hide();
        }

        this.hideCells = function(columns) {
            for(var i = 0; i < columns.length; i++) {
                if(columns[i].type == "hide") {
                    this.hideCell(i);
                }
            }
        }
    }

    return Row;
});


jui.define("uix.table.base", [ "jquery", "util.base", "uix.table.column", "uix.table.row" ], function($, _, Column, Row) {

    var Base = function(handler, fields) {
        var self = this;

        var $obj = handler.$obj,
            $tpl = handler.$tpl;

        var columns = [],
            rows = [],
            folds = {};

        var isNone = false,
            iParser = _.index();

        function init() {
            toggleRowNone();
            initColumns();
        }

        function initColumns() {
            var tmpColumns = [];

            $obj.thead.find("tr:last > th").each(function(i) {
                tmpColumns.push(this);
            });

            for(var i = 0; i < tmpColumns.length; i++) {
                var column = new Column(i);

                if(columns[i]) { // 기존의 컬럼 정보가 있을 경우에는 리스트만 초기화 한다.
                    column.element = columns[i].element;
                    column.order = columns[i].order;
                    column.name = columns[i].name;
                    column.data = columns[i].data;
                    column.list = columns[i].list;
                    column.type = columns[i].type;
                    column.width = columns[i].width;
                } else {
                    column.element = tmpColumns[i];

                    if($(column.element).attr("width") || (
                        $(column.element).attr("style") &&
                        $(column.element).attr("style").indexOf("width") != -1)) {
                        column.width = $(column.element).outerWidth();
                    }

                    if(fields && fields[i]) {
                        column.name = fields[i];
                    }
                }

                for(var j = 0; j < rows.length; j++) {
                    column.list.push(rows[j].list[i]);
                    column.data.push(rows[j].data[column.name]);
                }

                columns[i] = column;
            }
        }

        function initColumnRows(type, row) {
            if(type == "reload" || type == "append") {
                for(var i = 0; i < columns.length; i++) {
                    columns[i].list[row.index] = row.list[i];
                    columns[i].data[row.index] = row.data[columns[i].name];
                }
            } else if(type == "remove") {
                for(var i = 0; i < columns.length; i++) {
                    columns[i].list.splice(row.index, 1);
                    columns[i].data.splice(row.index, 1);
                }
            } else {
                initColumns();
            }
        }

        function createRow(data, no, pRow) {
            var row = new Row(data, $tpl.row, pRow);
            row.reload(no, false, columns);

            return row;
        }

        function setRowChildAll(dataList, row) {
            var c_rows = row.children;

            if(c_rows.length > 0) {
                for(var i = 0; i < c_rows.length; i++) {
                    dataList.push(c_rows[i]);

                    if(c_rows[i].children.length > 0) {
                        setRowChildAll(dataList, c_rows[i]);
                    }
                }
            }
        }

        function getRowChildLeaf(keys, row) {
            if(!row) return null;
            var tmpKey = keys.shift();

            if(tmpKey == undefined) {
                return row;
            } else {
                return getRowChildLeaf(keys, row.children[tmpKey]);
            }
        }

        function reloadRows() {
            var index = arguments[0], callback = arguments[1];

            if(typeof(index) == "function") {
                callback = index;
                index = 0;
            } else {
                index = (!isNaN(index)) ? index : 0;
            }

            for(var i = index; i < rows.length; i++) {
                rows[i].reload(i);
                initColumnRows("reload", rows[i]);

                if(typeof(callback) == "function") {
                    callback(i);
                }
            }
        }

        function insertRowData(index, data) {
            var row = createRow(data, index), preRows = row;

            if(rows.length == index && !(index == 0 && rows.length == 1)) {
                var tRow = rows[index - 1];
                $(row.element).insertAfter((tRow.children.length == 0) ? tRow.element : tRow.lastChildLeaf().element);
            } else {
                $(row.element).insertBefore(rows[index].element);
            }

            // Rows 데이터 갱신
            preRows = rows.splice(0, index);
            preRows.push(row);
            rows = preRows.concat(rows);

            // Rows UI 갱신
            reloadRows(index);

            return row;
        }

        function insertRowDataChild(index, data) {
            var keys = iParser.getIndexList(index);

            var pRow = self.getRowParent(index),
                rownum = keys[keys.length - 1];
            row = createRow(data, rownum, pRow);

            // 데이터 갱신
            pRow.insertChild(rownum, row);

            return row;
        }

        function appendRowData(data) {
            // Row 배열 세팅
            var row = createRow(data, rows.length);
            rows.push(row);

            // 실제 HTML에 추가
            $obj.tbody.append(row.element);

            // Column 배열 세팅
            initColumnRows("append", row);

            return row;
        }

        function appendRowDataChild(index, data) {
            var pRow = self.getRow(index),
                cRow = createRow(data, pRow.children.length, pRow);

            pRow.appendChild(cRow);

            return cRow;
        }

        function toggleRowNone() {
            if(typeof($tpl.none) != "function") return false;

            if(isNone) {
                if(rows.length > 0) {
                    $obj.tbody.find("tr:first").remove();
                    isNone = false;
                }
            } else {
                if(rows.length == 0) {
                    $obj.tbody.html($tpl.none());
                    isNone = true;
                }
            }

            return true;
        }

        this.appendRow = function() {
            var index = arguments[0], data = arguments[1];
            var result = null;

            if(!data) result = appendRowData(index);
            else result = appendRowDataChild(index, data);

            toggleRowNone();
            return result;
        }

        this.insertRow = function(index, data) {
            var result = null;

            if(iParser.isIndexDepth(index)) {
                result = insertRowDataChild(index, data);
            } else {
                if(rows.length == 0 && parseInt(index) == 0) {
                    result = this.appendRow(data);
                } else {
                    result = insertRowData(index, data);
                }
            }

            toggleRowNone();
            return result;
        }

        this.updateRow = function(index, data) {
            var row = this.getRow(index);

            for(var key in data) {
                row.data[key] = data[key];
            }

            row.reload(null, true);
            initColumnRows("reload", row);

            return row;
        }

        this.moveRow = function(index, targetIndex) {
            if(index == targetIndex) return;

            var rows = this.getRowAll(index),
                row = rows[0],
                data = _.clone(row.data);

            if(rows.length > 1) {
                for(var i = 0; i < rows.length; i++) {
                    var index = iParser.changeIndex(rows[i].index, targetIndex, rows[0].index);
                    this.insertRow(index, rows[i].data);
                }
            } else {
                this.insertRow(targetIndex, data);
            }

            this.removeRow(row.index);
        }

        this.removeRow = function(index) {
            var row = this.getRow(index);		// 자신 객체

            if(!iParser.isIndexDepth(index)) {
                row.destroy();

                initColumnRows("remove", rows[index]);
                rows.splice(index, 1);
                reloadRows(index);
            } else {
                row.destroy();
            }

            toggleRowNone();
        }

        this.openRow = function(index) {
            this.getRow(index).open();
            folds[index] = false;

            for(var key in folds) {
                if(folds[key] !== false) {
                    var foldRow = this.getRow(folds[key]);
                    if(foldRow != null) foldRow.fold();
                }
            }
        }

        this.openRowAll = function() {
            var tmpRows = this.getRowAll();

            for(var i = 0; i < tmpRows.length; i++) {
                if(!tmpRows[i].isLeaf()) {
                    tmpRows[i].open();
                    folds[tmpRows[i].index] = false;
                }
            }
        }

        this.foldRow = function(index) {
            this.getRow(index).fold();
            folds[index] = index;
        }

        this.foldRowAll = function() {
            var tmpRows = this.getRowAll();

            for(var i = 0; i < tmpRows.length; i++) {
                if(!tmpRows[i].isLeaf()) {
                    tmpRows[i].fold();
                    folds[tmpRows[i].index] = tmpRows[i].index;
                }
            }
        }

        this.removeRows = function() {
            rows = [];

            if(!toggleRowNone()) {
                $obj.tbody.html("");
            }

            initColumnRows();
        }

        this.sortRows = function(name, isDesc) {
            var qs = _.sort(rows);

            if(isDesc) {
                qs.setCompare(function(a, b) {
                    return (getValue(a) > getValue(b)) ? true : false;
                });
            } else {
                qs.setCompare(function(a, b) {
                    return (getValue(a) < getValue(b)) ? true : false;
                });
            }

            // 정렬 후, 데이터 갱신
            qs.run();
            $obj.tbody.html("");

            // 정렬 후, 화면 갱신
            reloadRows(function(i) {
                $obj.tbody.append(rows[i].element);
            });

            // 해당 컬럼에 해당하는 값 가져오기
            function getValue(row) {
                var value = row.data[name];

                if(typeof(value) == "string") {
                    return value.toLowerCase();
                } else {
                    if(!isNaN(value) && value != null) {
                        return value;
                    }
                }

                return "";
            }
        }

        this.appendColumn = function(tplType, dataList) {
            var columLength = columns.length,
                $columnRows = $($tpl[tplType]({ rows: dataList }));
            var $theadTrList = $columnRows.filter("thead").find("tr");

            $theadTrList.each(function(i) {
                var $tr = $obj.thead.find("tr").eq(i);

                $(this).find("th").each(function(j) {
                    $tr.append(this);

                    if($theadTrList.size() - 1 == i) {
                        columns.push({ element: this, list: [] });
                    }
                });
            });

            for(var k = 0; k < rows.length; k++) {
                $columnRows.filter("tbody").find("tr").eq(k).find("td").each(function(i) {
                    $(rows[k].element).append(this);

                    columns[columLength + i].list.push(this);
                    rows[k].list.push(this);

                    $.extend(rows[k].data, dataList[k]);
                });
            }
        }

        this.removeColumn = function(index) {
            for(var i = 0; i < columns[index].list.length; i++) {
                $(columns[index].element).remove();
                $(columns[index].list[i]).remove();
            }

            for(var j = 0; j < rows.length; j++) {
                rows[j].list.splice(index, 1);
            }

            columns.splice(index, 1);
        }

        this.hideColumn = function(index) {
            if(columns[index].type == "hide") return;

            var rows = this.getRowAll();
            for(var i = 0; i < rows.length; i++) {
                rows[i].hideCell(index);
            }

            columns[index].hide();
        }

        this.showColumn = function(index) {
            if(columns[index].type == "show") return;

            var rows = this.getRowAll();
            for(var i = 0; i < rows.length; i++) {
                rows[i].showCell(index);
            }

            columns[index].show();
        }

        this.getColumnCount = function() {
            return columns.length;
        }

        this.getRowCount = function() {
            return rows.length;
        }

        this.getColumn = function(index) {
            if(index == null) return columns;
            else return columns[index];
        }

        this.getRow = function(index) {
            if(index == null) return rows;
            else {
                if(iParser.isIndexDepth(index)) {
                    var keys = iParser.getIndexList(index);
                    return getRowChildLeaf(keys, rows[keys.shift()]);
                } else {
                    return (rows[index]) ? rows[index] : null;
                }
            }
        }

        this.getRowAll = function(index) {
            var dataList = [],
                tmpRows = (index == null) ? rows : [ this.getRow(index) ];

            for(var i = 0; i < tmpRows.length; i++) {
                if(tmpRows[i]) {
                    dataList.push(tmpRows[i]);

                    if(tmpRows[i].children.length > 0) {
                        setRowChildAll(dataList, tmpRows[i]);
                    }
                }
            }

            return dataList;
        }

        this.getRowParent = function(index) { // 트리 구조의 키에서 키 로우의 부모를 가져오는 함수
            if(!iParser.isIndexDepth(index)) return null;
            return this.getRow(iParser.getParentIndex(index));
        }

        this.setColumn = function(index, column) {
            columns[index] = column;
        }

        this.setRow = function(index, row) {
            rows[index] = row;
        }

        this.printInfo = function() {
            console.log(columns);
            console.log(rows);
        }

        init();
    }

    return Base;
});


jui.defineUI("uix.table", [ "jquery", "util.base", "ui.dropdown", "uix.table.base" ], function($, _, dropdown, Base) {

    _.resize(function() {
        var call_list = jui.get("table");

        for(var i = 0; i < call_list.length; i++) {
            var ui_list = call_list[i].list;

            for(var j = 0; j < ui_list.length; j++) {
                ui_list[j].resize();
            }
        }
    }, 1000);

    /**
     * @class uix.table
     * @extends core
     * @alias Table
     * @requires jquery
     * @requires util.base
     * @requires ui.dropdown
     * @requires uix.table.base
     *
     */
    var UI = function() {
        var $obj = null, ddUi = null; // table/thead/tbody 구성요소, 컬럼 설정 UI (Dropdown)
        var rowIndex = null, checkedList = {};
        var is_resize = false, is_edit = false;


        function getExpandHtml(self) {
            return "<tr class='expand' style='display: none;'><td id='EXPAND_" + self.timestamp + "'></td></tr>";
        }

        function getColumnIndexes(self, colkeys) {
            var indexList = [];

            for(var i = 0; i < colkeys.length; i++) {
                if(typeof(colkeys[i]) == "string") {
                    var column = self.getColumn(colkeys[i]);
                    indexList.push(column.index);
                } else {
                    indexList.push(colkeys[i]);
                }
            }

            return indexList;
        }

        function setColumnStatus(self) {
            var colkeys = self.options.colshow,
                len = self.uit.getColumnCount();

            if(colkeys === true) {
                self.options.colshow = colkeys = [];

                for(var i = 0; i < len; i++) {
                    colkeys.push(i);
                }
            } else {
                colkeys = getColumnIndexes(self, colkeys);
            }

            for(var i = 0; i < len; i++) {
                if($.inArray(i, colkeys) == -1)
                    self.uit.hideColumn(i);
                else
                    self.uit.showColumn(i);
            }
        }

        function setColumnMenu(self) {
            var $ddObj = null;
            var columns = self.listColumn(),
                columnNames = [];

            for(var i = 0; i < columns.length; i++) {
                columnNames.push($(columns[i].element).text());
            }

            $ddObj = $(self.tpl.menu({ columns: columnNames }));

            $("body").append($ddObj);
            ddUi = dropdown($ddObj, { close: false });

            $(ddUi.root).find("input[type=checkbox]").each(function(i) {
                if(columns[i].type == "show") this.checked = true;
                else this.checked = false;

                self.addEvent(this, "click", function(e) {
                    var ckCount = $(ddUi.root).find("input[type=checkbox]:checked").size();

                    if(this.checked) {
                        self.showColumn(i, e);
                    } else {
                        if(ckCount > 0) {
                            self.hideColumn(i, e);
                        } else {
                            this.checked = true;
                        }
                    }

                    self.hideExpand();
                    self.scroll();
                });
            });
        }

        function setScrollResize(self) {
            var tableWidth = $obj.table.outerWidth(),
                thCount = self.uit.getColumnCount(),
                isLastCheck = false;

            for(var i = thCount - 1; i >= 0; i--) {
                var colInfo = self.getColumn(i),
                    thWidth = $(colInfo.element).outerWidth();

                // 마지막 TD는 스크롤 사이즈를 차감
                if($(colInfo.element).css("display") == "none") {}
                else {
                    if(!isLastCheck) {
                        thWidth = thWidth - _.scrollWidth();
                        isLastCheck = true;
                    }
                }

                $(colInfo.list[0]).outerWidth(thWidth);
            }

            $obj.tbody.outerWidth(tableWidth);
        }

        function setScrollEvent(self) {
            if(!$(self.root).hasClass("table-scroll")) { // 스크롤일 경우, 별도 처리
                self.scroll();
            }

            $obj.tbody.off("scroll").scroll(function(e) {
                if(($obj.tbody.scrollTop() + self.options.scrollHeight) == $obj.tbody.get(0).scrollHeight){
                    self.emit("scroll", e);
                    return false;
                }
            });
        }

        function setUpdateInit(self, isInit) {
            if(self.uit.getRowCount() < 1) return;

            if(isInit) {
                if(self.options.expand) {
                    $obj.tbody.prepend(getExpandHtml(self));
                }

                self.scroll();
            }

            if(self.options.scroll) { // 스크롤 이벤트 처리
                setScrollEvent(self);
            }

            self.setVo();
        }

        function setEventRows(self, rows) {
            var rows = (!rows) ? self.uit.getRow() : rows;

            for(var i = 0; i < rows.length; i++) {
                (function(row) {
                    if(row.children.length > 0) {
                        setEventRow(self, row);
                        setEventRows(self, row.children);
                    } else {
                        setEventRow(self, row);
                    }
                })(rows[i])
            }
        }

        function setEventRow(self, row) {
            self.addEvent(row.element, "click", function(e) {
                // 1. 공통 이벤트 발생
                self.emit("select", [ row, e ]); // deprecated
                self.emit("click", [ row, e ]);

                // 2. 확장영역 자동 이벤트 처리
                if(self.options.expand) {
                    if(self.options.expandEvent === false) return;

                    if(rowIndex === row.index) {
                        self.hideExpand(e);
                    } else {
                        if(rowIndex != null) {
                            self.hideExpand(e);
                        }

                        self.showExpand(row.index, undefined, e);
                    }
                }
            });

            self.addEvent(row.element, "dblclick", function(e) {
                self.emit("dblclick", [ row, e ]);
            });

            self.addEvent(row.element, "contextmenu", function(e) {
                self.emit("rowmenu", [ row, e ]);
                return false;
            });

            if(self.options.fields && self.options.editCell) {
                if(self.options.editEvent === false) return;

                $(row.element).find("td").each(function(i) {
                    var cell = this;

                    (function(colIndex) {
                        self.addEvent(cell, "dblclick", function(e) {
                            if(is_edit) return;
                            is_edit = true;

                            if(e.target.tagName == "TD") {
                                setEventEditCell(self, e.currentTarget, row, colIndex);
                            }

                            self.emit("editstart", [ row, e ]);
                        });
                    })(i);
                });
            }

            if(self.options.fields && self.options.editRow) {
                if(self.options.editEvent === false) return;

                self.addEvent(row.element, "dblclick", function(e) {
                    if(e.target.tagName == "TD" || e.target.tagName == "TR") {
                        self.showEditRow(row.index, e);
                    }
                });
            }
        }

        function setEventEditCell(self, elem, row, colIndex, event, callback) {
            var column = self.getColumn(colIndex),
                data = (column.name) ? column.data[row.index] : $(elem).html(),
                colkeys = (!callback) ? self.options.editCell : self.options.editRow;

            var $input = $("<input type='text' class='edit' />").val(data).css("width", "100%");
            $(elem).html($input);

            if(!column.name || (colkeys !== true && $.inArray(colIndex, getColumnIndexes(self, colkeys)) == -1)) {
                $input.attr("disabled", true);
            }

            // 클릭 엘리먼트에 포커스 맞추기
            if(event && event.target == elem) $input.focus();

            // 엔터 키 이벤트 발생시 업데이트
            self.addEvent($input, "keypress", function(e) {
                if(e.which == 13) {
                    update(e);
                }
            });

            // 포커스가 바뀌었을 경우 업데이트
            self.addEvent($obj.tbody.find("tr"), "click", function(e) {
                if(e.target.tagName == "TD" || e.target.tagName == "TR") {
                    update(e);
                }
            });

            function update(e) {
                if(!is_edit) return;

                if(typeof(callback) == "function") { // editRow일 경우
                    callback();
                    is_edit = false;
                } else {
                    var data = {};
                    data[column.name] = $input.val();

                    var res = self.emit("editend", [ data ]);

                    // 이벤트 리턴 값이 false가 아닐 경우에만 업데이트
                    if(res !== false) {
                        self.update(row.index, data);
                        $input.remove();
                        is_edit = false;
                    }
                }
            }
        }

        function setEventColumn(self) {
            var opts = self.options,
                len = self.uit.getColumnCount();

            // 컬럼 컨텍스트 이벤트
            for(var i = 0; i < len; i++) {
                var col = self.getColumn(i);

                (function(index, column) {
                    if(!opts.fields || !opts.sort || opts.sortEvent !== true) {
                        self.addEvent(column.element, "click", function (e) {
                            self.emit("colclick", [ column, e ]);
                        });
                    }

                    self.addEvent(column.element, "dblclick", function(e) {
                        self.emit("coldblclick", [ column, e ]);
                    });

                    self.addEvent(column.element, "contextmenu", function(e) {
                        self.emit("colmenu", [ column, e ]);
                        return false;
                    });
                })(i, col);
            }
        }

        function setEventSort(self) {
            var sortIndexes = self.options.sort,
                len = (sortIndexes === true) ? self.uit.getColumnCount() : sortIndexes.length;

            for(var i = 0; i < len; i++) {
                var colKey = (sortIndexes === true) ? i : sortIndexes[i],
                    col = self.getColumn(colKey);

                if(col.element != null) {
                    (function(index, column) {
                        self.addEvent(column.element, "click", function(e) {
                            if($(e.target).hasClass("resize")) return;

                            self.sort(index, undefined, e);
                            self.emit("colclick", [ column, e ]);
                        });
                    })(colKey, col);

                    $(col.element).css("cursor", "pointer");
                }
            }
        }

        function setColumnResize(self) {
            var resizeX = 0,
                tablePos = $obj.table.offset();
            var col = null,
                colNext = null,
                colWidth = 0,
                colNextWidth = 0,
                colResize = null;

            // 리사이즈 엘리먼트 삭제
            $obj.thead.find(".resize").remove();

            for(var i = 0; i < self.uit.getColumnCount() - 1; i++) {
                var $colElem = $(self.getColumn(i).element),
                    $resizeBar = $("<div class='resize'></div>");
                var pos = $colElem.offset(); // ie8 버그로 인해 position에서 offset으로 변경함

                $resizeBar.css({
                    position: "absolute",
                    width: "8px",
                    height: $colElem.outerHeight(),
                    left: ($colElem.outerWidth() + (pos.left - tablePos.left) - 1) + "px",
                    top: (pos.top - tablePos.top) + "px",
                    cursor: "w-resize",
                    "z-index": "1"
                });

                $colElem.append($resizeBar);

                // Event Start
                (function(index) {
                    self.addEvent($resizeBar, "mousedown", function(e) {
                        if(resizeX == 0) resizeX = e.pageX;

                        // 컬럼 객체 가져오기
                        col = self.getColumn(index);
                        colNext = getNextColumn(index);
                        colWidth = $(col.element).outerWidth(),
                            colNextWidth = $(colNext.element).outerWidth();
                        colResize = this;
                        is_resize = true;

                        return false;
                    });
                })(i);
            }

            self.addEvent("body", "mousemove", function(e) {
                if(resizeX > 0) {
                    colResizeWidth(self, e.pageX - resizeX);
                }
            });

            self.addEvent("body", "mouseup", function(e) {
                if(resizeX > 0) {
                    resizeX = 0;
                    is_resize = false;

                    // 리사이징 바, 위치 이동
                    var left = $(col.element).offset().left - tablePos.left;
                    $(colResize).css("left", $(col.element).outerWidth() + left - 1);

                    self.emit("colresize", [ col, e ]);

                    return false;
                }
            });

            function getNextColumn(index) {
                for(var i = index + 1; i < self.uit.getColumnCount(); i++) {
                    var elem = self.getColumn(i).element;

                    if(!$(elem).is(':hidden')) {
                        return self.getColumn(i);
                    }
                }
            }

            function colResizeWidth(self, disWidth) {
                var colMinWidth = 30;

                // 최소 크기 체크
                if(colWidth + disWidth < colMinWidth || colNextWidth - disWidth < colMinWidth)
                    return;

                $(col.element).outerWidth(colWidth + disWidth);
                $(colNext.element).outerWidth(colNextWidth - disWidth);

                // 스크롤 옵션일 경우, 별도 처리
                if(self.options.scroll) {
                    var colLastWidth = $(colNext.element).outerWidth() - ((col.index == self.uit.getColumnCount() - 2) ? _.scrollWidth() : 0);

                    $(col.list[0]).outerWidth($(col.element).outerWidth());
                    $(colNext.list[0]).outerWidth(colLastWidth);
                }
            }
        }


        this.init = function() {
            var opts = this.options;

            // @Deprecated, 'rows'는 의미상 맞지 않아 차후 삭제
            opts.data = (opts.rows != null) ? opts.rows : opts.data;

            // UIHandler, 추후 코어에서 처리
            $obj = {
                table: $(this.root).css({ "position": "relative" }),
                thead: $(this.root).find("thead"),
                tbody: $(this.root).find("tbody")
            };

            // UITable 객체 생성
            this.uit = new Base({
                $obj: $obj, $tpl: this.tpl
            }, opts.fields); // 신규 테이블 클래스 사용

            if(opts.fields && opts.colshow) { // 컬럼 보이기 초기값 설정
                setColumnStatus(this);
            }

            if(opts.fields && this.tpl.menu) { // 컬럼 보이기/숨기기 메뉴 설정
                setColumnMenu(this);
            }

            if(opts.resize) {
                setColumnResize(this);
            }

            if(opts.fields && opts.sort && opts.sortEvent === true) {
                setEventSort(this);
            }

            if(opts.data.length > 0) {
                this.update(opts.data);
            } else {
                this.setVo(); // 데이터가 있을 경우에는 VO 세팅을 별도로 함
            }

            if(opts.width > 0) {
                $obj.table.outerWidth(opts.width);
            }

            if(!opts.fields) {
                if(opts.sort || opts.colshow || opts.editCell || opts.editRow) {
                    throw new Error("JUI_CRITICAL_ERR: 'fields' option is required");
                }
            }

            setEventColumn(this);
        }

        /**
         * @method update
         * Updates the list of rows or modifies the row at a specified index.
         *
         * @param {Array} rows
         */
        this.update = function() {
            var dataList = (arguments.length == 1) ? arguments[0] : arguments[1],
                index = (arguments.length == 2) ? arguments[0] : null;

            if(index != null) { // 1. 단일 로우 업데이트
                var tmpRow = this.uit.updateRow(index, dataList);
                setEventRow(this, tmpRow);

                // 첫번째 로우일 경우, 스크롤 다시 처리
                if(parseInt(index) == 0) {
                    this.scroll();
                }
            } else { // 2. 로우 목록 업데이트
                this.uit.removeRows();
                this.scroll();
                this.append(dataList);

                // 정렬 인덱스가 옵션에 있을 경우, 해당 인덱스의 컬럼 정렬
                if(this.options.sortIndex) {
                    this.sort(this.options.sortIndex, this.options.sortOrder, null);
                }
            }
        }

        /**
         * @method updateTree
         * It is possible to configure a tree table using an object array with the index and data properties.
         *
         * @param {Array} rows
         */
        this.updateTree = function(rows) { // index & data 조합의 객체 배열
            var iParser = _.index();

            // 전체 로우 제거
            this.uit.removeRows();

            // 트리 로우 추가
            for(var i = 0; i < rows.length; i++) {
                var pIndex = iParser.getParentIndex(rows[i].index);

                if(pIndex == null) {
                    this.uit.appendRow(rows[i].data);
                } else {
                    this.uit.appendRow(pIndex, rows[i].data);
                }
            }

            setUpdateInit(this, true);
            setEventRows(this);
        }

        /**
         * @method append
         * Add a row or a child row to at a specified index.
         *
         * @param {RowObject} row
         */
        this.append = function() {
            var isInit = (this.count() > 0) ? false : true;
            var dataList = (arguments.length == 1) ? arguments[0] : arguments[1],
                index = (arguments.length == 2) ? arguments[0] : null;

            dataList = (dataList.length == undefined) ? [ dataList ] : dataList;

            for(var i = 0; i < dataList.length; i++) {
                var tmpRow = null;

                if(index != null) tmpRow = this.uit.appendRow(index, dataList[i]);
                else tmpRow = this.uit.appendRow(dataList[i]);

                // 추가 로우 추가시 이벤트 걸기
                if(!isInit) {
                    setEventRow(this, tmpRow);
                }
            }

            setUpdateInit(this, isInit);
            if(isInit) setEventRows(this); // 최초에 데이터가 없을 경우에만 전체 이벤트 걸기
        }

        /**
         * @method insert
         * Adds a row at a specified index.
         *
         * @param {Integer} index
         * @param {RowObject} row
         */
        this.insert = function(index, dataList) {
            var isInit = (this.count() > 0) ? false : true;
            var dataList = (dataList.length == undefined) ? [ dataList ] : dataList;

            for(var i = 0; i < dataList.length; i++) {
                this.uit.insertRow(index, dataList[i]);
            }

            setUpdateInit(this, isInit);
            setEventRows(this);
        }

        /**
         * @method select
         * Adds a selected class to a row at a specified index and gets an instance of the applicable row.
         *
         * @param {Integer} index
         * @return {RowObject} row
         */
        this.select = function(index) {
            var row = this.get(index);

            // 초기화
            this.hideExpand();
            this.hideEditRow();
            this.uncheckAll();

            $(row.element).parent().find(".selected").removeClass("selected");
            $(row.element).addClass("selected");

            rowIndex = index;
            return row;
        }

        /**
         * @method unselect
         * Removes a selected class from a selected row and gets an instance of the row in question.
         *
         * @return {RowObject} row
         */
        this.unselect = function() {
            if(rowIndex == null) return;
            var row = this.get(rowIndex);

            $(row.element).removeClass("selected");
            rowIndex = null;

            return row;
        }

        /**
         * @method check
         * Add a checked class to a row at a specified index.
         *
         * @param {Integer} index
         */
        this.check = function(index) {
            var row = this.get(index);

            // 초기화
            this.hideExpand();
            this.hideEditRow();
            this.unselect();

            checkedList[index] = row;
            $(row.element).addClass("checked");
        }

        /**
         * @method uncheck
         * Removes a checked class from a row at a specified index.
         *
         * @param {Integer} index
         */
        this.uncheck = function(index) {
            var row = this.get(index);

            checkedList[index] = null;
            $(row.element).removeClass("checked");
        }

        /**
         * @method uncheckAll
         * Removes checked classes from all rows.
         */
        this.uncheckAll = function() {
            checkedList = {};
            $obj.tbody.find(".checked").removeClass("checked");
        }

        /**
         * @method remove
         * Remove a row at a specified index.
         *
         * @param {Integer} index
         */
        this.remove = function(index) {
            if(index == null) return null;

            this.uit.removeRow(index);
            setEventRows(this);
            this.scroll();
        }

        /**
         * @method reset
         * Removes all rows.
         */
        this.reset = function() {
            this.uit.removeRows();
            this.scroll();
        }

        /**
         * @method move
         * Moves a row iat a specified index to the target index.
         *
         * @param {Integer} index
         * @param {Integer} targetIndex
         */
        this.move = function(index, targetIndex) {
            this.uit.moveRow(index, targetIndex);
            setEventRows(this);

            // 첫번째 로우일 경우, 스크롤 다시 처리
            if(parseInt(index) == 0 || parseInt(targetIndex) == 0) {
                this.scroll();
            }
        }

        /**
         * @method sort
         * Moves a row iat a specified index to the target index.
         *
         * @param {Integer} index
         * @param {String} order  "asc" or "desc"
         */
        this.sort = function(index, order, e) {  // index는 컬럼 key 또는 컬럼 name
            if(!this.options.fields || !this.options.sort || is_resize) return;
            var column = this.getColumn(index);

            if(typeof(column.name) == "string") {
                column.order = (order) ? order : (column.order == "asc") ? "desc" : "asc";

                this.uit.setColumn(index, column);
                this.uit.sortRows(column.name, (column.order == "desc") ? true : false);
                this.emit("sort", [ column, e ]);

                setUpdateInit(this, true);
                setEventRows(this);
            }
        }

        /**
         * @method scroll
         * Sets the scroll based on the height of a table.
         *
         * @param {Integer} height
         */
        this.scroll = function(height) {
            if(!this.options.scroll) return;

            var self = this,
                h = (height && height > 0) ? height : this.options.scrollHeight,
                h = (h > 0) ? h : 200;

            this.options.scrollHeight = h;
            $obj.tbody.css("maxHeight", h + "px");

            setTimeout(function() {
                if($obj.tbody.outerHeight() < h) {
                    $obj.table.css({
                        "table-layout": ""
                    });

                    $obj.tbody.css({
                        "display": "",
                        "overflow": ""
                    });
                } else {
                    $obj.table.css({
                        "table-layout": "fixed"
                    });

                    $obj.tbody.css({
                        "display": "block",
                        "overflow": "auto"
                    });
                }

                setScrollResize(self);
            }, 10);
        }

        /**
         * @method open
         * Shows a child row of a specified index.
         *
         * @param {Integer} index
         */
        this.open = function(index) { // 로트 제외, 하위 모든 노드 대상
            if(index == null) return;

            this.uit.openRow(index);
            this.emit("open", [ this.get(index) ]);
        }

        /**
         * @method fold
         * Hides a child row of a specified index.
         *
         * @param {Integer} index
         */
        this.fold = function(index) {
            if(index == null) return;

            this.uit.foldRow(index);
            this.emit("fold", [ this.get(index) ]);
        }

        /**
         * @method openAll
         * Shows all child rows of a specified index.
         */
        this.openAll = function() { // 로트 포함, 하위 모든 노드 대상
            this.uit.openRowAll();
            this.emit("openall");
        }

        /**
         * @method foldAll
         * Hides all child rows of a specified index.
         */
        this.foldAll = function() {
            this.uit.foldRowAll();
            this.emit("foldall");
        }

        /**
         * @method resize
         * Resets the inner scroll and columns of a table.
         */
        this.resize = function() {
            this.scroll();

            if(this.options.resize) {
                setColumnResize(this);
            }
        }

        /**
         * @method resizeColumns
         * Resets the sizes of all columns of a table.
         */
        this.resizeColumns = function() {
            var columns = this.listColumn();

            for(var i = 0; i < columns.length; i++) {
                if(columns[i].width == null) {
                    $(columns[i].element).outerWidth("auto");
                }
            }
        }

        /**
         * @method size
         * Gets the size of all the rows of a table.
         *
         * @return {Integer} size
         */
        this.size = function() { // 차후 수정 (컬럼 * 로우 개수 * 바이트)
            return this.uit.getRowCount();
        }

        /**
         * @method count
         * Gets the number of trows of a table.
         *
         * @return {Integer} count
         */
        this.count = function() {
            return this.uit.getRowCount();
        }

        /**
         * @method list
         * Gets all the rows of a table.
         *
         * @return {Array} rows
         */
        this.list = function() {
            return this.uit.getRow();
        }

        /**
         * @method listData
         * Gets the data of all the rows of a table.
         *
         * @return {Array} datas
         */
        this.listData = function() {
            var rows = this.list(),
                data = [];

            for(var i = 0; i < rows.length; i++) {
                data.push(rows[i].data);
            }

            return data;
        }

        /**
         * @method listAll
         * Gets all the rows of a table including child rows.
         *
         * @return {Array} rows
         */
        this.listAll = function() {
            return this.uit.getRowAll();
        }

        /**
         * @method listChecked
         * Gets all rows in a check state.
         *
         * @return {Array} rows
         */
        this.listChecked = function() {
            var list = [];

            for(var row in checkedList) {
                if(checkedList[row] != null) {
                    list.push(checkedList[row]);
                }
            }

            return list;
        }

        /**
         * @method listColumn
         * Gets all columns.
         *
         * @return {Array} columns
         */
        this.listColumn = function() {
            return this.uit.getColumn();
        }

        /**
         * @method get
         * Gets the row at the specified index.
         *
         * @param {Integer} index
         * @return {RowObject} row
         */
        this.get = function(index) {
            if(index == null) return null;
            return this.uit.getRow(index);
        }

        /**
         * @method getAll
         * Gets all rows of at the specified index including child rows.
         *
         * @param {Integer} index
         * @return {Array} rows
         */
        this.getAll = function(index) {
            if(index == null) return null;
            return this.uit.getRowAll(index);
        }

        /**
         * @method getColumn
         * Gets the column at the specified index.
         *
         * @param {"Integer"/"String"} key index or column key
         * @return {ColumnObject} column
         */
        this.getColumn = function(index) { // index or columnName
            if(index == null) return null;
            else {
                if(typeof(index) == "string")
                    return this.uit.getColumn($.inArray(index, this.options.fields));
                else
                    return this.uit.getColumn(index);
            }
        }

        /**
         * @method showColumn
         * Shows the column index (or column name).
         *
         * @param {"Integer"/"String"} key index or column name
         */
        this.showColumn = function(index, e) { // index or columnName
            if(!this.options.fields) return;
            var column = this.getColumn(index);

            this.uit.showColumn(column.index);
            this.scroll();
            this.resizeColumns();

            if(this.options.resize) {
                setColumnResize(this);
            }

            // 커스텀 이벤트 발생
            this.emit("colshow", [ column, e ]);
        }

        /**
         * @method hideColumn
         * Hides the column index (or column name).
         *
         * @param {"Integer"/"String"} key index or column name
         */
        this.hideColumn = function(index, e) { // index or columnName
            if(!this.options.fields) return;
            var column = this.getColumn(index);

            this.uit.hideColumn(column.index);
            this.scroll();
            this.resizeColumns();

            if(this.options.resize) {
                setColumnResize(this);
            }

            // 커스텀 이벤트 발생
            this.emit("colhide", [ column, e ]);
        }

        /**
         * @method initColumns
         * It is possible to determine the index or name of the column to be shown in an array.
         *
         * @param {"Integer"/"String"} key index or column name
         */
        this.initColumns = function(keys) {
            if(typeof(keys) != "object") return;
            this.options.colshow = keys;

            setColumnStatus(this);
            this.scroll();
            this.resizeColumns();

            if(this.options.resize) {
                setColumnResize(this);
            }
        }

        /**
         * @method showColumnMenu
         * Shows the Show/Hide Column menu at specified coordinates.
         *
         * @param {Integer} x
         */
        this.showColumnMenu = function(x) {
            if(!this.options.fields || !ddUi) return;

            var columns = this.listColumn();
            var offset = $obj.thead.offset(),
                maxX = offset.left + $obj.table.outerWidth() - $(ddUi.root).outerWidth();

            x = (isNaN(x) || (x > maxX + offset.left)) ? maxX : x;
            x = (x < 0) ? 0 : x;

            // 현재 체크박스 상태 설정
            $(ddUi.root).find("input[type=checkbox]").each(function(i) {
                if(columns[i].type == "show") this.checked = true;
                else this.checked = false;
            });

            ddUi.move(x, offset.top + $obj.thead.outerHeight());
            ddUi.show();
        }

        /**
         * @method hideColumnMenu
         * Hides the Show/Hide Column menu.
         */
        this.hideColumnMenu = function() {
            if(!this.options.fields || !ddUi) return;
            ddUi.hide();
        }

        /**
         * @method toggleColumnMenu
         * Shows or hides the Show/Hide Column menu.
         *
         * @param {Integer} x
         */
        this.toggleColumnMenu = function(x) {
            if(!this.options.fields || !ddUi) return;

            if(ddUi.type == "show") this.hideColumnMenu();
            else this.showColumnMenu(x);
        }

        /**
         * @method showExpand
         * Shows the extended row area of a specified index.
         *
         * @param {Integer} index
         */
        this.showExpand = function(index, obj, e) {
            if(!this.options.expand) return;

            // 초기화
            this.unselect();
            this.hideEditRow();

            var expandSel = "#EXPAND_" + this.timestamp,
                row = this.get(index),
                obj = (typeof(obj) != "object") ? $.extend({ row: row }, row.data) : obj,
                $expand = $(expandSel).parent().show();

            $obj.tbody.find("tr").removeClass("open");
            $expand.insertAfter($(row.element).addClass("open"));

            $(expandSel)
                .attr("colspan", $obj.thead.find("tr:last > th:visible").size())
                .html(this.tpl["expand"](obj));

            // 스크롤 및 VO 적용
            this.scroll();
            this.setVo();

            // 커스텀 이벤트 호출
            rowIndex = index;
            this.emit("expand", [ row, e ]);
        }

        /**
         * @method hideExpand
         * Hides the extended row area of a specified index.
         */
        this.hideExpand = function(e) {
            if(!this.options.expand) return;
            if(rowIndex == null) return;

            var row = this.get(rowIndex);

            $('#EXPAND_' + this.timestamp).parent().hide();
            $obj.tbody.find("tr").removeClass("open");

            // 스크롤 적용
            this.scroll();

            // 커스텀 이벤트 호출
            rowIndex = null;
            this.emit("expandend", [ row, e ]);
        }

        /**
         * @method getExpand
         * Get a row in which the extended area is currently activated.
         *
         * @return {RowObject} row
         */
        this.getExpand = function() {
            if(!this.options.expand) return;

            if(rowIndex == null) return null;
            return this.get(rowIndex);
        }

        /**
         * @method showEditRow
         * Shows the modified row area of a specified index.
         *
         * @param {Integer} index
         */
        this.showEditRow = function(index, e) {
            if(!this.options.editRow || is_edit) return;

            // 초기화
            this.unselect();
            this.hideExpand();

            var self = this,
                row = this.get(index);
            var $cells = $(row.element).find("td");

            // 현재 테이블 수정 상태
            is_edit = true;

            $cells.each(function(i) {
                setEventEditCell(self, this, row, i, e, function() {
                    var data = {},
                        originData = row.data;

                    $cells.each(function(colIndex) {
                        var column = self.getColumn(colIndex);

                        if(column.name != null) {
                            var value = $(this).find(".edit").val();
                            data[column.name] = (!isNaN(value) && value != null) ? parseFloat(value) : value;
                        }
                    });

                    // 변경된 값으로 데이터 갱신하기
                    row.data = data;

                    // 콜백 결과 가져오기
                    var res = self.emit("editend", [ row, e ]);

                    // 이벤트 리턴 값이 false가 아닐 경우에만 업데이트
                    if(res !== false) {
                        self.update(row.index, data);
                    } else {
                        row.data = originData;
                    }
                });
            });

            rowIndex = index;
            self.emit("editstart", [ row, e ]);
        }

        /**
         * @method hideEditRow
         * Hides the modified row area of a specified index.
         */
        this.hideEditRow = function() {
            if(!this.options.editRow) return;
            if(rowIndex == null) return;

            var row = this.get(rowIndex);

            // 커스텀 이벤트 호출
            rowIndex = null;

            // 수정 상태 이전의 로우 데이터로 변경
            this.emit("editend", [ row.data ]);
            this.update(row.index, row.data);
        }

        /**
         * @method getEditRow
         * Get a row in which the modified area is currently activated.
         *
         * @return {RowObject} row
         */
        this.getEditRow = function() {
            if(!this.options.editRow) return;

            if(rowIndex == null) return null;
            return this.get(rowIndex);
        }

        /**
         * @method setCsv
         * Updates a table using a CVS string.
         */
        this.setCsv = function() {
            var opts = this.options;
            if(!opts.fields && !opts.csv) return;

            var csv = (arguments.length == 1) ? arguments[0] : arguments[1],
                key = (arguments.length == 2) ? arguments[0] : null;

            var fields = _.getCsvFields(opts.fields, opts.csv),
                csvNumber = (opts.csvNumber) ? _.getCsvFields(opts.fields, opts.csvNumber) : null,
                dataList = _.csvToData(fields, csv, csvNumber);

            if(key == null) {
                this.update(dataList);
            } else {
                this.reset();

                for(var i = 0; i < dataList.length; i++) {
                    var index = dataList[i][key];

                    if(index) {
                        this.insert(index, dataList[i]);
                    }
                }
            }
        }

        /**
         * @method setCsvFile
         * Updates a table using a CVS file.
         */
        this.setCsvFile = function() {
            if(!this.options.fields && !this.options.csv) return;

            var self = this,
                file = (arguments.length == 1) ? arguments[0] : arguments[1],
                key = (arguments.length == 2) ? arguments[0] : null;

            _.fileToCsv(file, function(csv) {
                if(key == null) self.setCsv(csv);
                else self.setCsv(key, csv);
            });
        }

        /**
         * @method getCsv
         * Gets the data of a table as a CSV string.
         *
         * @param {Boolean} isTree
         * @return {String} csv
         */
        this.getCsv = function(isTree) {
            if(!this.options.fields && !this.options.csv) return;

            var fields = _.getCsvFields(this.options.fields, this.options.csv);
            var dataList = [],
                rows = (isTree) ? this.listAll() : this.list();

            for(var i = 0; i < rows.length; i++) {
                dataList.push(rows[i].data);
            }

            return _.dataToCsv2({
                fields: fields,
                rows: dataList,
                names: this.options.csvNames
            });
        }

        /**
         * @method getCsvBase64
         * Gets the data of a table as a CSV string encoded as base64.
         *
         * @param {Boolean} isTree
         * @return {String} base64
         */
        this.getCsvBase64 = function(isTree) {
            if(!this.options.fields && !this.options.csv) return;

            return _.csvToBase64(this.getCsv(isTree));
        }

        /**
         * @method downloadCsv
         * Downloads the data of a table as a CSV file.
         *
         * @param {String} name
         * @param {Boolean} isTree
         */
        this.downloadCsv = function(name, isTree) {
            if(_.typeCheck("string", name)) {
                name = name.split(".")[0];
            }

            var a = document.createElement('a');
            a.download = (name) ? name + ".csv" : "table.csv";
            a.href = this.getCsvBase64(isTree);

            document.body.appendChild(a);
            a.click();
            a.parentNode.removeChild(a);
        }

        /**
         * @method activeIndex
         * Gets the index of a row that is activated in an extended/modified/selected state.
         *
         * @return {Integer} index
         */
        this.activeIndex = function() { // 활성화된 확장/수정/선택 상태의 로우 인덱스를 리턴
            return rowIndex;
        }
    }

    UI.setup = function() {
        return {
            /**
             * @cfg {Array} [fields=null]
             * Sets the name of columns in the order of being displayed on the table screen.
             */
            fields: null,

            /**
             * @cfg {Array} [csv=null]
             * Sets the column key shown when converted to a CSV string.
             */
            csv: null,

            /**
             * @cfg {Array} [csvNames=null]
             * Sets the name of a column shown when converting to a CSV string, which must be defined in the same order as the CSV option.
             */
            csvNames: null,

            /**
             * @cfg {Array} [csvNumber=null]
             * Sets the column key to be changed to a number form when converted to a CSV string.
             */
            csvNumber: null,

            /**
             * @cfg {Array} data
             * Sets the initial row list of a table.
             */
            data: [],

            /**
             * @cfg {Array} rows
             * Sets the initial row list of a table (@Deprecated).
             */
            rows: null, // @Deprecated

            /**
             * @cfg {Boolean/Array} [colshow=false]
             * Sets a column index shown when the Show/Hide Column menu is enabled.
             */
            colshow: false,

            /**
             * @cfg {Boolean} [scroll=false]
             * Determines whether to use a table scroll.
             */
            scroll: false,

            /**
             * @cfg {Integer} [scrollHeight=200]
             * Sets the reference height of a body area when using a table scroll.
             */
            scrollHeight: 200,

            /**
             * @cfg {Integer} [width=0]
             * Sets the area of a table.
             */
            width: 0,

            /**
             * @cfg {Boolean} [expand=false]
             * Determines whether to use an extended row area.
             */
            expand: false,

            /**
             * @cfg {Boolean} [expandEvent=true]
             * Sets the Show/Hide state of an extended row area when clicking on a row.
             */
            expandEvent: true,

            /**
             * @cfg {Boolean|Array} [editCell=false]
             * Determines whether to use a modified cell area.
             */
            editCell: false,

            /**
             * @cfg {Boolean|Array} [editRow=false]
             * Determines whether to use a modified row area.
             */
            editRow: false,

            /**
             * @cfg {Boolean} [editEvent=true]
             * Sets the Show/Hide state of an extended row area when doubleclicking on a row/cell.
             */
            editEvent: true,

            /**
             * @cfg {Boolean} [resize=false]
             * Determines whether to use the column resizing function.
             */
            resize: false,

            /**
             * @cfg {Boolean/Array} [sort=false]
             * Determines whether to use the table sort function.
             */
            sort: false,

            /**
             * @cfg {Integer} [sortIndex=null]
             * Determines whether to use the table sort function.
             */
            sortIndex: null,

            /**
             * @cfg {String} [sortOrder="asc"]
             * Determines whether to use the table sort function.
             */
            sortOrder: "asc",

            /**
             * @cfg {Boolean} [sortEvent=true]
             * Determines whether to use the sort function when you click on a column.
             */
            sortEvent: true,

            /**
             * @cfg {Boolean} [animate=false]
             * @deprecated
             */
            animate: false
        }
    }

    /**
     * @event select
     * Event that occurs when a row is selected (@Deprecated)
     *
     * @param {RowObject) row
     * @param {EventObject} e The event object
     */

    /**
     * @event click
     * Event that occurs when a row is clicked
     *
     * @param {RowObject) row
     * @param {EventObject} e The event object
     */

    /**
     * @event dblclick
     * Event that occurs when a row is double clicked
     *
     * @param {RowObject) row
     * @param {EventObject} e The event object
     */

    /**
     * @event sort
     * Event that occurs when the table is sorted.
     *
     * @param {ColumnObject) column
     * @param {EventObject} e The event object
     */

    /**
     * @event scroll
     * Event that occurs when the scroll of a table is located at the lowermost position.
     *
     * @param {EventObject} e The event object
     */

    /**
     * @event rowmenu
     * Event that occurs when a row is right clicked.
     *
     * @param {RowObject) row
     * @param {EventObject} e The event object
     */

    /**
     * @event colclick
     * Event that occurs when a column is clicked.
     *
     * @param {ColumnObject) column
     * @param {EventObject} e The event object
     */

    /**
     * @event colshow
     * Event that occurs when shown column is selected.
     *
     * @param {ColumnObject) column
     * @param {EventObject} e The event object
     */

    /**
     * @event colhide
     * Event that occurs when hidden column is selected.
     *
     * @param {ColumnObject) column
     * @param {EventObject} e The event object
     */

    /**
     * @event colresize
     * Event that occurs when the column resizing is activated.
     *
     * @param {ColumnObject) column
     * @param {EventObject} e The event object
     */

    /**
     * @event editstart
     * Event that occurs when a row is in a modification state.
     *
     * @param {RowObject) row
     * @param {EventObject} e The event object
     */

    /**
     * @event editend
     * Event that occurs when the modification of a row is completed.
     *
     * @param {RowObject) row
     * @param {EventObject} e The event object
     */

    /**
     * @event expand
     * Event that occurs when the extended row area is enabled.
     *
     * @param {RowObject) row
     * @param {EventObject} e The event object
     */

    /**
     * @event expandend
     * Event that occurs when the extended row area is disabled.
     *
     * @param {RowObject) row
     * @param {EventObject} e The event object
     */

    /**
     * @event open
     * Event that occurs when a child row is shown.
     *
     * @param {RowObject) row
     * @param {EventObject} e The event object
     */

    /**
     * @event fold
     * Event that occurs when a child row is hidden.
     *
     * @param {RowObject) row
     * @param {EventObject} e The event object
     */

    /**
     * @event openall
     * Event that occurs when all child rows are shown.
     */

    /**
     * @event foldall
     * Event that occurs when all child rows are hidden.
     */

    return UI;
});