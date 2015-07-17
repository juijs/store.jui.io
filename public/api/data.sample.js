/**
 * 데이타 Sample
 * 
 * 이건 데이타를 어떻게 정의하는지 보여주는 샘플이다. 
 * 
 * @author 박진호
 */
jui.define("data.sample", ["jquery"], function($) {
  return function(callback) {   	 
    
    var data = [
       { x : 'sample1', y : 200 } , 
       { x : 'sample2', y : 300 },
       { x : 'sample4', y : 2100 } ,
       { x : 'sample3', y : 5000 }
    ];
    
    callback(data);
  }
});