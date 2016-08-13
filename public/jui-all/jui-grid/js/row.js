jui.define("grid.row", [ "jquery" ], function($) {

    var Base = function() {
        function setIndexChild(row) {
            var clist = row.children;

            for(var i = 0; i < clist.length; i++) {
                clist[i].setIndex(i);
                clist[i].reload();

                if(!clist[i].isLeaf()) {
                    setIndexChild(clist[i]);
                }
            }
        }

        function setElementCells(self) {
            self.list = [];

            $(self.element).find("td").each(function(i) {
                self.list[i] = this;

                if(self.hidden[i]) {
                    this.style.display = "none";
                }
            });
        }

        function getElement(self) {
            if(!self.tpl) return self.element;

            var element = $(self.tpl(
                $.extend({
                    row: {
                        type: self.type,
                        index: self.index,
                        data: self.data,
                        depth: self.depth,
                        children: self.children
                    }
                }, self.data))
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

        function reloadChildAll(children) {
            for(var i = 0; i < children.length; i++) {
                children[i].setIndex(i);
                children[i].reload();
            }
        }

        this.setIndex = function(rownum) {
            this.rownum = (!isNaN(rownum)) ? rownum : this.rownum;

            if(!this.parent) {
                this.index = "" + this.rownum;
            } else {
                this.index = this.parent.index + "." + this.rownum;
            }

            if(this.parent && typeof(this.index) == "string") {
                this.depth = this.index.split(".").length - 1;
            }

            if(!this.isLeaf()) {
                setIndexChild(this);
            }
        }

        this.reload = function(columns) {
            if(this.element != null) {
                var newElem = getElement(this),
                    clsValue = $(this.element).attr("class");

                $(newElem).addClass(clsValue).insertAfter(this.element);
                $(this.element).remove();

                this.element = newElem;
            } else {
                this.element = getElement(this);
            }

            if(columns != null) {
                this.hideCells(columns);
            }

            setElementCells(this);
        }

        this.destroy = function() {
            if(this.parent != null) {
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
            reloadChildAll(this.children);
        }

        this.removeChild = function(index) {
            for(var i = 0; i < this.children.length; i++) {
                var row = this.children[i];

                if(row.index == index) {
                    this.children.splice(i, 1); // �迭���� ����
                    removeChildAll(row);
                }
            }

            reloadChildAll(this.children);
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
            this.hidden[index] = false;
            $(this.list[index]).show();
        }

        this.hideCell = function(index) {
            this.hidden[index] = true;
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

    /**
     * @class grid.row
     *
     * Grid's Row Class
     *
     * @alias Table Row
     * @requires jquery
     */
    var Row = function() {
        /** @property {Array} data Data of a specifiedrow. */
        this.data = null;

        /** @property {Integer} [rownum=null] The unique number of a child row under the specified parent row if a parent row exists. */
        this.rownum = null;

        /** @property {String/Integer} [index=null] Index of a specified row. In the case of a tree structure, a depth is given. */
        this.index = null;

        /** @property {HTMLElement} [element=null] TR element of a specified row. */
        this.element = null;

        /** @property {Array} list List of TD elements of a specified row. */
        this.list = [];

        /** @property {Object} list List of hidden TD element. */
        this.hidden = {};

        /** @property {uix.table.row} parent Variable that refers to the parent row. */
        this.parent = null;

        /** @property {Array} children List of child rows. */
        this.children = [];

        /** @property {Integer} [depth=0] The depth of the current row in the case of a tree structure. */
        this.depth = 0;

        /** @property {"open"/"fold"} [type="fold"] State value that indicates whether a child row is shown or hidden. */
        this.type = "fold";

        /** @property {Function} [type="null"] State value that indicates whether a child row is shown or hidden. */
        this.tpl = null;

        this.init = function(data, tplFunc, pRow) {
            this.data = data;
            this.tpl = tplFunc;
            this.parent = pRow;
        }
    }

    Row.prototype = new Base;

    return Row;
});