/**
 * 대쉬보드 만들어보자.
 * 
 * 여러가지 데이타를 가지고 대쉬보드 만들기
 * 
 * @author easylogic
 */
jui.define("data.two.source", [], function() {
  return {
   data1 :  [
        { date: "", value: 0, value2: 0 },
        { date: "22 Dec", value: 27000, value2: 9000 },
        { date: "23 Dec", value: 39000, value2: 36000 },
        { date: "24 Dec", value: 48000, value2: 81000 },
        { date: "25 Dec", value: 70000, value2: 65000 },
        { date: "26 Dec", value: 74000, value2: 48000 },
        { date: "27 Dec", value: 93000, value2: 60000 },
        { date: "28 Dec", value: 70000, value2: 98000 },
        { date: "29 Dec", value: 72000, value2: 39000 },
        { date: "30 Dec", value: 68000, value2: 82000 },
        { date: "31 Dec", value: 49000, value2: 45000 },
        { date: "", value: 0, value2: 0 }
    ],
   data2 : [
        { title : "Overall Visits", value : 192, max : 200, min : 0 },
        { title : "New Visits", value : 66, max : 100, min : 0 },
        { title : "Mobile Visits", value : 75, max : 100, min : 0 },
        { title : "Desktop Visits", value : 25, max : 100, min : 0 }
    ]
  }
});