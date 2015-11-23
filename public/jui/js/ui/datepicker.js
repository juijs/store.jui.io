jui.defineUI("ui.datepicker", [ "jquery", "util.base" ], function($, _) {

    function getStartDate(date) {
        date.setHours(0);
        date.setMinutes(0);
        date.setSeconds(0);
        date.setMilliseconds(0);

        return date;
    }

    /**
     * @class ui.datepicker
     * @extends core
     * @alias Date Picker
     * @requires jquery
     * @requires util.base
     */
    var UI = function() {
    	var year = null, month = null, date = null,
            selDate = null, items = {}; // 헌재 페이지의 요소 엘리먼트 캐싱
        var $head = null, $body = null;


        function setCalendarEvent(self) {
            self.addEvent($head.children(".prev"), "click", function(e) {
                self.prev(e);
            });

            self.addEvent($head.children(".next"), "click", function(e) {
                self.next(e);
            });
        }
        
        function setCalendarDate(self, no) {
        	var opts = self.options;

            if(opts.type == "daily") {
            	var m = (month < 10) ? "0" + month : month,
            		d = (no < 10) ? "0" + no : no;
                selDate = new Date(year + "/" + m + "/" + d);
            } else if(opts.type == "monthly") {
            	var m = (no < 10) ? "0" + no : no;
                selDate = new Date(year + "/" + m + "/01");
            } else if(opts.type == "yearly") {
                selDate = new Date(no + "/01/01");
            }

            // 0시 0분 0초 0밀리 초로 설정
            selDate = getStartDate(selDate);
        }

        function getCalendarDate(self) {
        	var opts = self.options,
        		tmpDate = null;
        	
        	if(opts.type == "daily") {
        		var m = (month < 10) ? "0" + month : month;
        		tmpDate = new Date(year + "/" + m + "/01");
        	} else if(opts.type == "monthly") {
        		tmpDate = new Date(year + "/01/01");
        	} else if(opts.type == "yearly") {
        		tmpDate = new Date();
        	}

        	return getStartDate(tmpDate);
        }

        function getCalendarHtml(self, obj) {
            var opts = self.options;
            var resHtml = "",
            	tmpItems = [];
            
            // 활성화 날짜 캐시 초기화
            items = {};

            for(var i = 0; i < obj.objs.length; i++) {
                tmpItems.push(obj.nums[i]);

                if(isNextBr(i)) {
                    resHtml += self.tpl["dates"]({ dates: tmpItems });
                    tmpItems = [];
                }
            }

            var $list = $(resHtml);
            $list.find("td").each(function(i) {
                $(this).addClass(obj.objs[i].type);

                self.addEvent(this, "click", function(e) {
                    if(obj.objs[i].type == "none") return;

                    $body.find("td").removeClass("active");
                    $(this).addClass("active");
                    
                    setCalendarDate(self, obj.objs[i].no);
                    self.emit("select", [ self.getFormat(), e ]);
                });
                
                if(obj.objs[i].type != "none") {
                	items[obj.objs[i].no] = this;
                }
            });

            function isNextBr(i) {
                return (opts.type == "daily") ? ((i + 1) % 7 == 0) : ((i + 1) % 3 == 0);
            }

            return $list;
        }

        function getLastDate(year, month) {
            if(month == 2) {
                if(year % 100 != 0 && (year % 4 == 0 || year % 400 == 0))
                    return 29;
                else
                    return 28;
            } else {
                var months = [ 31, 0, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31 ];
                return months[month - 1];
            }
        }

        function getDateList(y, m) {
            var objs = [],
                nums = [],
                no = 1;

            var d = new Date(),
                start = new Date(y + "-" + ((m < 10) ? "0" + m : m)).getDay(),
                ldate = getLastDate(y, m);

            var prevYear = (m == 1) ? y - 1 : y,
                prevMonth = (m == 1) ? 12 : m - 1,
                prevLastDay = getLastDate(prevYear, prevMonth);

            for(var i = 0; i < start; i++) {
                nums[i] = (prevLastDay - start) + (i + 1);
                objs[i] = { type: "none", no: nums[i] };
            }

            for(var i = start; i < 42; i++) {
                if(no <= ldate) {
                    var type = "";

                    if(d.getMonth() + 1 == m && d.getDate() == no) {
                        type = "now";
                    }

                    if(selDate != null) {
                        if(selDate.getFullYear() == y && selDate.getMonth() + 1 == m && selDate.getDate() == no) {
                            type = "active";
                        }
                    }

                    nums[i] = no;
                    objs[i] = { type: type, no: nums[i] };
                    no++;
                } else {
                    nums[i] = no - ldate;
                    objs[i] = { type: "none", no: nums[i] };
                    no++;
                }
            }

            return { objs: objs, nums: nums };
        }

        function getMonthList(y) {
            var objs = [],
                nums = [];

            var d = new Date();

            for(var i = 1; i <= 12; i++) {
                var type = "";

                if(d.getFullYear() == y && d.getMonth() + 1 == i) {
                    type = "now";
                }

                if(selDate != null) {
                    if(selDate.getFullYear() == y && selDate.getMonth() + 1 == i) {
                        type = "active";
                    }
                }

                nums.push(i);
                objs.push({ type: type, no: i });
            }

            return { objs: objs, nums: nums };
        }

        function getYearList(y) {
            var objs = [],
                nums = [],
                startYear = y - 4;

            var d = new Date();

            for(var i = startYear; i < startYear + 12; i++) {
                var type = "";

                if(d.getFullYear() == i) {
                    type = "now";
                }

                if(selDate != null) {
                    if(selDate.getFullYear() == i) {
                        type = "active";
                    }
                }

                nums.push(i);
                objs.push({ type: type, no: i });
            }

            return { objs: objs, nums: nums };
        }

        this.init = function() {
            $head = $(this.root).children(".head");
            $body = $(this.root).children(".body");

            // 이벤트 정의
            setCalendarEvent(this);

            // 기본 날짜 설정
            this.select(this.options.date);
        }

        /**
         * @method page
         * Outputs a calendar that fits the year/month entered
         *
         * @param {Integer} year
         * @param {Integer} month
         */
        this.page = function(y, m) {
            if(arguments.length == 0) return;
            var opts = this.options;

            if(opts.type == "daily") {
                year = y;
                month = m;
                
                $body.find("tr:not(:first-child)").remove();
                $body.append(getCalendarHtml(this, getDateList(year, month)));
            } else if(opts.type == "monthly") {
                year = y;
                
                $body.find("tr").remove();
                $body.append(getCalendarHtml(this, getMonthList(year)));
            } else if(opts.type == "yearly") {
                year = y;

                $body.find("tr").remove();
                $body.append(getCalendarHtml(this, getYearList(year)));
            }
            
            $head.children(".title").html(_.dateFormat(getCalendarDate(this), opts.titleFormat));
        }

        /**
         * @method prev
         * Outputs a calendar that fits the previous year/month
         *
         */
        this.prev = function(e) {
            var opts = this.options;

            if(opts.type == "daily") {
                var y = (month == 1) ? year - 1 : year,
                    m = (month == 1) ? 12 : month - 1;
                
                this.page(y, m);
            } else if(opts.type == "monthly") {
                this.page(year - 1);
            } else if(opts.type == "yearly") {
                this.page(year - 12);
            }
            
            this.emit("prev", [ e ]);
        }

        /**
         * @method next
         * Outputs a calendar that fits the next year/month
         *
         */
        this.next = function(e) {
            var opts = this.options;

            if(opts.type == "daily") {
                var y = (month == 12) ? year + 1 : year,
                    m = (month == 12) ? 1 : month + 1;

                this.page(y, m);
            } else if(opts.type == "monthly") {
                this.page(year + 1);
            } else if(opts.type == "yearly") {
                this.page(year + 12);
            }

            this.emit("next", [ e ]);
        }

        /**
         * @method select
         * Selects today if there is no value, or selects a date applicable to a timestamp or year/month/date
         *
         * @param {"year"/"month"/"date"/"timestamp"/"Date"}
         */
        this.select = function() {
        	var opts = this.options,
        		args = arguments;

        	if(args.length == 0) {
        		y = year;
        		m = month;
        		d = date;
        	} else if(args.length == 3) {
        		y = args[0];
        		m = args[1];
        		d = args[2];
        	} else if(args.length == 1) {
        		var time = (_.typeCheck("date", args[0])) ? args[0] : new Date(args[0]);

        		y = time.getFullYear();
        		m = time.getMonth() + 1;
        		d = time.getDate();
        	}

            if(opts.type == "daily") {
            	this.page(y, m);
            	this.addTrigger(items[d], "click");
            } else if(opts.type == "monthly") {
            	this.page(y);
            	this.addTrigger(items[m], "click");
            } else if(opts.type == "yearly") {
                this.page(y);
                this.addTrigger(items[y], "click");
            }
        }

        /**
         * @method addTime
         * Selects a date corresponding to the time added to the currently selected date
         *
         * @param {"Integer"/"Date"} time Timestamp or Date
         */
        this.addTime = function(time) {
        	selDate = new Date(this.getTime() + time);
        	this.select(this.getTime());
        }

        /**
         * @method getDate
         * Gets the value of the date currently selected
         *
         * @return {Date} Date object
         */
        this.getDate = function() {
            return selDate;
        }

        /**
         * @method getTime
         * Gets the timestamp value of the date currently selected
         *
         * @return {Integer} Timestamp
         */
        this.getTime = function() {
            return selDate.getTime();
        }

        /**
         * @method getFormat
         * Gets a date string that fits the format entered
         *
         * @return {String} format Formatted date string
         */
        this.getFormat = function(format) {
            return _.dateFormat(selDate, (typeof(format) == "string") ? format : this.options.format);
        }
    }

    UI.setup = function() {
        var now = getStartDate(new Date());

        return {
            /**
             * @cfg {"daily"/"monthly"/"yearly"} [type="daily"]
             * Determines the type of a calendar
             */
            type: "daily",

            /**
             * @cfg {String} [titleFormat="yyyy.MM"]
             * Title format of a calendar
             */
            titleFormat: "yyyy.MM",

            /**
             * @cfg {String} [format="yyyy-MM-dd"]
             * Format of the date handed over when selecting a specific date
             */
            format: "yyyy-MM-dd",

            /**
             * @cfg {Date} [date="now"]
             * Selects a specific date as a basic
             */
            date: now,

            /**
             * @cfg {Boolean} [animate=false]
             * @deprecated
             */
            animate: false
        };
    }

    /**
     * @event select
     * Event that occurs when selecting a specific date
     *
     * @param {String} value Formatted date string
     * @param {EventObject} e The event object
     */

    /**
     * @event prev
     * Event that occurs when clicking on the previous button
     *
     * @param {EventObject} e The event object
     */

    /**
     * @event next
     * Event that occurs when clicking on the next button
     *
     * @param {EventObject} e The event object
     */

    return UI;
});