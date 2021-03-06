var chart = jui.include("chart.builder");

var data = [
    { age : "80+",   female : 6.0,  male : 5.3 },
    { age : "75-79", female : 4.7,  male : 4.5 },
    { age : "70-74", female : 9.6,  male : 9.7 },
    { age : "65-69", female : 13.6, male : 12.9 },
    { age : "60-64", female : 19.0, male : 18.7 },
    { age : "55-59", female : 19.7, male : 19.5 },
    { age : "50-54", female : 23.2, male : 25.8 },
    { age : "45-49", female : 30.2, male : 32.1 },
    { age : "40-44", female : 34.9, male : 37.5 },
    { age : "35-39", female : 42.2, male : 42.9 },
    { age : "30-34", female : 43.9, male : 44.7 },
    { age : "25-29", female : 50.1, male : 51.3 },
    { age : "20-24", female : 53.8, male : 57.6 },
    { age : "15-19", female : 56.5, male : 64.0 },
    { age : "10-14", female : 63.3, male : 69.4 },
    { age : "5-9",   female : 60.6, male : 66.3 },
    { age : "5-4",   female : 54.2, male : 58.6 }
];

var names = {
    male : "Male",
    female : "Female"
};

chart("#chart-content", {
    axis : [{
        x : {
            type : "range",
            domain : function(d) {
                return Math.max(d.female, d.male);
            },
            step : 10,
            line : true,
            reverse : true
        },
        y : {
            type : "block",
            domain : "age"
        },
        data : data,
        area : {
            x : 0, y : 0, width : "50%", height : "100%"
        }
    }, {
        x : {
            reverse : false
        },
        y : {
            orient : "right"
        },
        area : {
            x : "50%", y : 0, width : "50%", height : "100%"
        },
        extend : 0
    }],
    brush : [{
        type : "bar",
        target : "female",
        colors : [ 1 ],
        axis : 0
    }, {
        type : "bar",
        target : "male",
        colors : [ 2 ],
        axis : 1
    }],
    widget : [{
        type : "legend",
        brush : [ 0, 1 ],
        format : function(k) {
            return names[k];
        }
    }, {
        type : "tooltip",
        brush : [ 0, 1 ],
        format : function(data, k) {
            return {
                key : names[k],
                value : data[k]
            }
        }
    }]
});