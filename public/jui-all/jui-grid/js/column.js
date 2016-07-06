jui.define("grid.column", [ "jquery" ], function($) {

    /**
     * @class grid.column
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