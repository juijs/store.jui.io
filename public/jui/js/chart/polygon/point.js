jui.define("chart.polygon.point", [], function() {
    var PointPolygon = function(x, y, d) {
        this.vertices = [
            new Float32Array([ x, y, d, 1 ])
        ]
    }

    return PointPolygon;
}, "chart.polygon.core");