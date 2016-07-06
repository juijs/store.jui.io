jui.define("chart.brush.pie", [ "util.base", "util.math", "util.color" ], function(_, math, ColorUtil) {

	/**
	 * @class chart.brush.pie
     * @extends chart.brush.core
	 */
	var PieBrush = function() {
        var self = this, textY = 3;
        var g;
        var cache_active = {};

        this.setActiveEvent = function(pie, centerX, centerY, centerAngle) {
            var dist = this.chart.theme("pieActiveDistance"),
                tx = Math.cos(math.radian(centerAngle)) * dist,
                ty = Math.sin(math.radian(centerAngle)) * dist;

            pie.translate(centerX + tx, centerY + ty);
        }

        this.setActiveTextEvent = function(pie, centerX, centerY, centerAngle, outerRadius, isActive) {
            var dist = (isActive) ? this.chart.theme("pieActiveDistance") : 0,
                cx = centerX + (Math.cos(math.radian(centerAngle)) * ((outerRadius + dist) / 2)),
                cy = centerY + (Math.sin(math.radian(centerAngle)) * ((outerRadius + dist) / 2));

            pie.translate(cx, cy);
        }

        this.getFormatText = function(target, value, max) {
            var key = target;

            if(typeof(this.brush.format) == "function") {
                return this.format(key, value, max);
            } else {
                if (!value) {
                    return key;
                }

                return key + ": " + this.format(value);
            }
        }

		this.drawPie = function(centerX, centerY, outerRadius, startAngle, endAngle, color) {
			var pie = this.chart.svg.group();

            if (endAngle == 360) { // if pie is full size, draw a circle as pie brush
                var circle = this.chart.svg.circle({
                    cx : centerX,
                    cy : centerY,
                    r : outerRadius,
                    fill : color,
                    stroke : this.chart.theme("pieBorderColor") || color,
                    "stroke-width" : this.chart.theme("pieBorderWidth")
                });

                pie.append(circle);

                return pie;
            }
            
            var path = this.chart.svg.path({
                fill : color,
                stroke : this.chart.theme("pieBorderColor") || color,
                "stroke-width" : this.chart.theme("pieBorderWidth")
            });

			// 바깥 지름 부터 그림
			var obj = math.rotate(0, -outerRadius, math.radian(startAngle)),
				startX = obj.x,
                startY = obj.y;
			
			// 시작 하는 위치로 옮김
			path.MoveTo(startX, startY);

			// outer arc 에 대한 지점 설정
			obj = math.rotate(startX, startY, math.radian(endAngle));

			pie.translate(centerX, centerY);

			// arc 그림
			path.Arc(outerRadius, outerRadius, 0, (endAngle > 180) ? 1 : 0, 1, obj.x, obj.y)
                .LineTo(0, 0)
                .ClosePath();

            pie.append(path);
            pie.order = 1;

			return pie;
		}

		this.drawPie3d = function(centerX, centerY, outerRadius, startAngle, endAngle, color) {
			var pie = this.chart.svg.group(),
				path = this.chart.svg.path({
                    fill : color,
                    stroke : this.chart.theme("pieBorderColor") || color,
                    "stroke-width" : this.chart.theme("pieBorderWidth")
                });

			// 바깥 지름 부터 그림
			var obj = math.rotate(0, -outerRadius, math.radian(startAngle)),
				startX = obj.x,
                startY = obj.y;

			// 시작 하는 위치로 옮김
			path.MoveTo(startX, startY);

			// outer arc 에 대한 지점 설정
			obj = math.rotate(startX, startY, math.radian(endAngle));

			pie.translate(centerX, centerY);

			// arc 그림
			path.Arc(outerRadius, outerRadius, 0, (endAngle > 180) ? 1 : 0, 1, obj.x, obj.y)

            var y = obj.y + 10,
                x = obj.x + 5,
                targetX = startX + 5,
                targetY = startY + 10;

            path.LineTo(x, y);
            path.Arc(outerRadius, outerRadius, 0, (endAngle > 180) ? 1 : 0, 0, targetX, targetY)
            path.ClosePath();

            pie.append(path);
            pie.order = 1;

			return pie;
		}

        this.drawText = function(centerX, centerY, centerAngle, outerRadius, text) {
            var g = this.svg.group({
                    visibility: !this.brush.showText ? "hidden" : "visible"
                }),
                isLeft = (centerAngle + 90 > 180) ? true : false;

            if(this.brush.showText == "inside") {
                var cx = centerX + (Math.cos(math.radian(centerAngle)) * (outerRadius / 2)),
                    cy = centerY + (Math.sin(math.radian(centerAngle)) * (outerRadius / 2));

                var text = this.chart.text({
                    "font-size": this.chart.theme("pieInnerFontSize"),
                    fill: this.chart.theme("pieInnerFontColor"),
                    "text-anchor": "middle",
                    y: textY
                }, text);

                text.translate(cx, cy);

                g.append(text);
                g.order = 2;
            } else {
                var dist = this.chart.theme("pieOuterLineSize"),
                    r = outerRadius * this.chart.theme("pieOuterLineRate"),
                    cx = centerX + (Math.cos(math.radian(centerAngle)) * outerRadius),
                    cy = centerY + (Math.sin(math.radian(centerAngle)) * outerRadius),
                    tx = centerX + (Math.cos(math.radian(centerAngle)) * r),
                    ty = centerY + (Math.sin(math.radian(centerAngle)) * r),
                    ex = (isLeft) ? tx - dist : tx + dist;

                var path = this.svg.path({
                    fill: "transparent",
                    stroke: this.chart.theme("pieOuterLineColor"),
                    "stroke-width": 0.7
                });

                path.MoveTo(cx, cy)
                    .LineTo(tx, ty)
                    .LineTo(ex, ty);

                var text = this.chart.text({
                    "font-size": this.chart.theme("pieOuterFontSize"),
                    fill: this.chart.theme("pieOuterFontColor"),
                    "text-anchor": (isLeft) ? "end" : "start",
                    y: textY
                }, text);

                text.translate(ex + (isLeft ? -3 : 3), ty);

                g.append(text);
                g.append(path);
                g.order = 0;
            }

            return g;
        }

		this.drawUnit = function (index, data, g) {
			var obj = this.axis.c(index);

			var width = obj.width,
                height = obj.height,
                x = obj.x,
                y = obj.y,
                min = width;

			if (height < min) {
				min = height;
			}

			// center
			var centerX = width / 2 + x,
                centerY = height / 2 + y,
                outerRadius = min / 2;

			var target = this.brush.target,
                active = this.brush.active,
				all = 360,
				startAngle = 0,
				max = 0;

			for (var i = 0; i < target.length; i++) {
				max += data[target[i]];
			}

			for (var i = 0; i < target.length; i++) {
                var value = data[target[i]],
                    endAngle = all * (value / max);

                if (this.brush['3d']) {
                    var pie3d = this.drawPie3d(centerX, centerY, outerRadius, startAngle, endAngle, ColorUtil.darken(this.color(i), 0.5));
                    g.append(pie3d);
                }

				startAngle += endAngle;
			}

            startAngle = 0;

			for (var i = 0; i < target.length; i++) {
                var value = data[target[i]],
                    endAngle = all * (value / max),
                    centerAngle = startAngle + (endAngle / 2) - 90,
                    pie = this.drawPie(centerX, centerY, outerRadius, startAngle, endAngle, this.color(i)),
                    text = this.drawText(centerX, centerY, centerAngle, outerRadius, this.getFormatText(target[i], value, max));

                // 설정된 키 활성화
                if (active == target[i] || _.inArray(target[i], active) != -1) {
                    if(this.brush.showText == "inside") {
                        this.setActiveTextEvent(text.get(0), centerX, centerY, centerAngle, outerRadius, true);
                    }

                    this.setActiveEvent(pie, centerX, centerY, centerAngle);
                    cache_active[centerAngle] = true;
                }

                // 활성화 이벤트 설정
                if (this.brush.activeEvent != null) {
                    (function(p, t, cx, cy, ca, r) {
                        p.on(self.brush.activeEvent, function(e) {
                            if(!cache_active[ca]) {
                                if(self.brush.showText == "inside") {
                                    self.setActiveTextEvent(t, cx, cy, ca, r, true);
                                }

                                self.setActiveEvent(p, cx, cy, ca);
                                cache_active[ca] = true;
                            } else {
                                if(self.brush.showText == "inside") {
                                    self.setActiveTextEvent(t, cx, cy, ca, r, false);
                                }

                                p.translate(cx, cy);
                                cache_active[ca] = false;
                            }
                        });

                        p.attr({ cursor: "pointer" });
                    })(pie, text.get(0), centerX, centerY, centerAngle, outerRadius);
                }

                self.addEvent(pie, index, i);
                g.append(pie);
                g.append(text);

				startAngle += endAngle;
			}
		}

        this.drawBefore = function() {
            g = this.chart.svg.group();
        }

		this.draw = function() {
			this.eachData(function(i, data) {
				this.drawUnit(i, data, g);
			});

            return g;
		}
	}

    PieBrush.setup = function() {
        return {
            /** @cfg {Boolean} [clip=false] If the brush is drawn outside of the chart, cut the area. */
            clip: false,
            /** @cfg {String} [showText=null] Set the text appear. (outside or inside)  */
            showText: null,
            /** @cfg {Function} [format=null] Returns a value from the format callback function of a defined option. */
            format: null,
            /** @cfg {Boolean} [3d=false] check 3d support */
            "3d": false,
            /** @cfg {String|Array} [active=null] Activates the pie of an applicable property's name. */
            active: null,
            /** @cfg {String} [activeEvent=null]  Activates the pie in question when a configured event occurs (click, mouseover, etc). */
            activeEvent: null
        }
    }

	return PieBrush;
}, "chart.brush.core");
