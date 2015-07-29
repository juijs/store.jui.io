/**
 * 좌표 계산 테스트 차트
 * 
 * ㅇㅇ
 * 
 * @author Jae-Seok Hong
 */
jui.define("chart.brush.coordinate", [], function() {
    var Template = function() {
        this.draw = function() {
            var g = this.chart.svg.group();
            this.eachData(function(i, data) {
                g.append(this.chart.svg.line({
                    stroke: this.color(i),
                    "stroke-width": this.brush.size,
                    x1: this.axis.x(0),
                    y1: this.axis.y(0),
                    x2: this.axis.x(data.x),
                    y2: this.axis.y(data.y)
                }));
            });
            return g;
        }
    }
    Template.setup = function() {
        return {
            size: 1
        }
    }

    return Template;
}, "chart.brush.core");