jui.define("chart.brush.fullgauge", [ "util.math" ], function(math) {

	/**
	 * @class chart.brush.fullgauge
	 * @extends chart.brush.donut
	 */
	var FullGaugeBrush = function() {
		var self = this;
        var group, w, centerX, centerY, outerRadius, innerRadius, textScale;

		function createText(value, index) {
			var g = self.chart.svg.group().translate(centerX, centerY),
				size = self.chart.theme("gaugeFontSize");

            g.append(self.chart.text({
                "text-anchor" : "middle",
                "font-size" : size,
                "font-weight" : self.chart.theme("gaugeFontWeight"),
                "fill" : self.color(index),
                y: size / 3
            }, self.format(value, index)).scale(textScale));

			return g;
		}

        function createTitle(title, index, dx, dy) {
            var g = self.chart.svg.group().translate(centerX + dx, centerY + dy),
                anchor = (dx == 0) ? "middle" : ((dx < 0) ? "end" : "start"),
				color = self.chart.theme("gaugeTitleFontColor"),
				size = self.chart.theme("gaugeTitleFontSize");

            g.append(self.chart.text({
                "text-anchor" : anchor,
                "font-size" : size,
                "font-weight" : self.chart.theme("gaugeTitleFontWeight"),
                fill : (!color) ? self.color(index) : color,
                y: size / 3
            }, title).scale(textScale));

            return g;
        }

		this.drawUnit = function(index, data) {
			var obj = this.axis.c(index),
				value = this.getValue(data, "value", 0),
                title = this.getValue(data, "title"),
				max = this.getValue(data, "max", 100),
				min = this.getValue(data, "min", 0);

			var startAngle = this.brush.startAngle;
			var endAngle = this.brush.endAngle;

			if (endAngle >= 360) {
				endAngle = 359.99999;
			}

			var rate = (value - min) / (max - min),
				currentAngle = endAngle * rate;

			if (currentAngle > endAngle) {
				currentAngle = endAngle;
			}

			var width = obj.width,
				height = obj.height,
				x = obj.x,
				y = obj.y;

			// center
			w = Math.min(width, height) / 2;
			centerX = width / 2 + x;
			centerY = height / 2 + y;
			outerRadius = w - this.brush.size;
			innerRadius = outerRadius - this.brush.size;
            textScale = math.scaleValue(w, 40, 400, 1, 1.5);

			group.append(this.drawDonut(centerX, centerY, innerRadius, outerRadius, startAngle + currentAngle, endAngle - currentAngle, {
				stroke : this.chart.theme("gaugeBackgroundColor"),
				fill : "transparent"
			}));

			group.append(this.drawDonut(centerX, centerY, innerRadius, outerRadius, startAngle, currentAngle, {
				stroke : this.color(index),
				fill : "transparent"
			}));

            if(this.brush.showText) {
                group.append(createText(value, index));
            }

            if(title != "") {
                group.append(createTitle(title, index, this.brush.titleX, this.brush.titleY));
            }

			return group;
		}

		this.draw = function() {
			group = this.chart.svg.group();

			this.eachData(function(data, i) {
				this.drawUnit(i, data);
			});

			return group;
		}
	}

	FullGaugeBrush.setup = function() {
		return {
			/** @cfg {Number} [size=30] Determines the stroke width of a gauge.  */
			size: 60,
			/** @cfg {Number} [startAngle=0] Determines the start angle(as start point) of a gauge. */
			startAngle: 0,
			/** @cfg {Number} [endAngle=360] Determines the end angle(as draw point) of a gauge. */
			endAngle: 360,
			/** @cfg {Boolean} [showText=true] */
            showText: true,
			/** @cfg {Number} [titleX=0] */
            titleX: 0,
			/** @cfg {Number} [titleY=0]  */
            titleY: 0,
			/** @cfg {Function} [format=null] */
            format: null
		};
	}

	return FullGaugeBrush;
}, "chart.brush.donut");
