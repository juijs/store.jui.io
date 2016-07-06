require("../../dist/node");

var fs = require("fs");

var chart = jui.create("chart.builder", null, {
    width : 800,
    height : 800,
    axis : {
        x : {
            type : "block",
            domain : "quarter",
            line : true
        },
        y : {
            type : "range",
            domain : function(d) {
                return Math.max(d.sales, d.profit);
            },
            step : 20,
            line : true,
            orient : "right"
        },
        data : [
            { quarter : "1Q", sales : 50, profit : 35 },
            { quarter : "2Q", sales : -20, profit : -100 },
            { quarter : "3Q", sales : 10, profit : -5 },
            { quarter : "4Q", sales : 30, profit : 25 }
        ]

    },
    brush : {
        type : "column",
        target : [ "sales", "profit" ]
    }
});

fs.writeFileSync("test.svg", chart.svg.toXML());
