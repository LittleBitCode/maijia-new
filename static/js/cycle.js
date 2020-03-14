  // JavaScript Document
//created by zcy 20161011
;(function($,window,document,undefined){
   //默认参数var this.PARAMS;
            var plugin=function(ele, opt){
						this.parent=ele;
						this.defaults= {percent:100 ,w:500,oneCircle:"false"};	
						//初始化参数
						this.PARAMS=$.extend({},this.defaults,opt) ;
						this.DrawCircle();
				}
		    //定义方法
			plugin.prototype={
				      DrawCircle:function(){
						
					       if(!canvasSupport()){
								return
							}
							var drawOne=this.PARAMS.oneCircle;
							var r=this.PARAMS.w/2;
							var r1=this.PARAMS.w/2-20;
							var x1=this.PARAMS.w/2;
							var y1=this.PARAMS.w/2;
							var canvas=this.parent[0];
							var tip=this.PARAMS.percent;
							var angle="";
							var init=0;
							var initA=0;
							var preM=0;
							var initM=0;  //因为是半圆  所以初始角度是Math.PI; 
							var s=2*Math.PI/180;
							var bottomC=Math.PI;
							var allCount=180;
							var allCountP=1.8;
							var poinits=new Array();
							if(drawOne=="ture"){
								angle=tip*2*Math.PI/100;
								canvas.width=this.PARAMS.w;
								canvas.height=this.PARAMS.w;
								 bottomC=2*Math.PI;
								 allCount=0;
								 allCountP=3.6;
							}else{
								angle=tip*Math.PI/100+Math.PI;
								canvas.width=this.PARAMS.w;
								canvas.height=this.PARAMS.w/2;
								init=180;
								preM=Math.PI;
								initM=Math.PI;  //因为是半圆  所以初始角度是Math.PI; 
								s=2*Math.PI/180;
							}
							var cxt=canvas.getContext("2d");
							//cxt.lineCap="round";
							cxt.lineWidth=8;
							var speed=1;
							var radius=this.PARAMS.w/2-2;
							var ball={x:0,y:0,speed:2};
							var T1;
							function drawScreen(){
								cxt.fillStyle="#ffffff";
								cxt.fillRect(0,0,canvas.width,canvas.height);
								//创建圆环与虚线
								//底圆
								cxt.beginPath();
								cxt.strokeStyle="#ebebeb";
								cxt.arc(x1,y1,r1-6,0,bottomC,true);
								cxt.stroke();  //先执行stroke  就不会出现横线
								cxt.closePath();


								//画实线
								if(initM<angle){
									initM+=s;
								}else{
									initM=angle;
									}
								cxt.beginPath();
								cxt.strokeStyle="#00a1fe";
								cxt.arc(x1,y1,r1-6,0,initM,false);
								cxt.stroke();  //先执行stroke  就不会出现横线
								cxt.closePath();    

								for(var i=initA;i<=init;i+=2){
									var radians2=i*(Math.PI/180);
									var a1=x1+Math.cos(radians2)*radius;
									var a2=y1+Math.sin(radians2)*radius;
									cxt.fillStyle="#ffffff";
									cxt.beginPath();
									cxt.arc(a1,a2,1,0,Math.PI*2,false);
									//console.log(balls[i].x)
									cxt.closePath();
									cxt.fill();
								}
								//百分比文字
								cxt.font="30px sans bold";
								cxt.textBaseline="middle";
								cxt.textAlign="center";
								cxt.fillStyle="#00a1fe";
								var messT=tip*initM/angle;
								/*if(drawOne!="ture"){
									 messT=tip*(initM)/angle;
									 console.log(initM-Math.PI)
									}*/
								if(messT>tip){
									  messT=tip
									}
								var mess;
								if(tip==0){
									mess=0.00+"%";
								}else if(messT>0&&messT<0.01){
								  mess=0.01+"%";
								}else{
									 mess= Math.round(messT * 100) / 100+"%";
								}
								cxt.fillText(mess,canvas.width/2,canvas.height/2);
							}	
						  //
						 
						  T1=setInterval(drawScreen,30) 
					  }
			 }
			function canvasSupport(){
			//判断是否支持canvas标签
			 return Modernizr.canvas;
		    }	
		//在插件中使用plugin对象
	    $.fn.audios2=function(options){
		//创建实体
		var plugina=new plugin(this,options);
		}	
})(jQuery,window,document);

