jui.define("chart.polygon.core", [ "chart.vector", "util.transform", "util.math", "util.base" ],
    function(Vector, Transform, math, _) {

    var PolygonCore = function() {
        this.perspective = 0.9;

        this.rotate = function(width, height, depth, degree) {
            var t = new Transform(this.vertices),
                p = this.perspective,
                cx = width / 2,
                cy = height / 2,
                cz = depth / 2;

            // 5가지 항목 미리 합성
            var M = t.matrix("move3d", cx, cy, cz),
                M3 = t.matrix("move3d", -cx, -cy, -cz);
            M = math.matrix3d(M, t.matrix("rotate3dx", degree.x));
            M = math.matrix3d(M, t.matrix("rotate3dy", degree.y));
            M = math.matrix3d(M, t.matrix("rotate3dz", degree.z));

            // scale 만 따로 합성
            for(var i = 0, count = this.vertices.length; i < count; i++) {
                var z = this.vertices[i][2],
                    s = math.scaleValue(z, 0, depth, 1, p);

                var M2 = math.matrix3d(M, t.matrix("scale3d", s, s, 1));
                M2 = math.matrix3d(M2, M3);

                this.vertices[i] = math.matrix3d(M2, this.vertices[i]);

                // 벡터 객체 생성 및 갱신
                if(_.typeCheck("array", this.vectors)) {
                    if (this.vectors[i] == null) {
                        this.vectors[i] = new Vector(this.vertices[i][0], this.vertices[i][1], this.vertices[i][2]);
                    } else {
                        this.vectors[i].x = this.vertices[i][0];
                        this.vectors[i].y = this.vertices[i][1];
                        this.vectors[i].z = this.vertices[i][2];
                    }
                }
            }
        }

        this.min = function() {
            var obj = {
                x: Number.MAX_VALUE,
                y: Number.MAX_VALUE,
                z: Number.MAX_VALUE
            };

            for(var i = 0, len = this.vertices.length; i < len; i++) {
                obj.x = Math.min(obj.x, this.vertices[i][0]);
                obj.y = Math.min(obj.y, this.vertices[i][1]);
                obj.z = Math.min(obj.z, this.vertices[i][2]);
            }

            return obj;
        }

        this.max = function() {
            var obj = {
                x: Number.MIN_VALUE,
                y: Number.MIN_VALUE,
                z: Number.MIN_VALUE
            };

            for(var i = 0, len = this.vertices.length; i < len; i++) {
                obj.x = Math.max(obj.x, this.vertices[i][0]);
                obj.y = Math.max(obj.y, this.vertices[i][1]);
                obj.z = Math.max(obj.z, this.vertices[i][2]);
            }

            return obj;
        }
    }

    return PolygonCore;
});