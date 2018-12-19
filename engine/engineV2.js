//V 2.0
//accessory functions and global variables
var w = window.innerWidth|| document.documentElement.clientWidth|| document.body.clientWidth;
var h = window.innerHeight|| document.documentElement.clientHeight|| document.body.clientHeight; 
var eventBus=null;
var currentPage=-1;
function createExternalHandle(globalEventBus){eventBus=globalEventBus;}
function disp(value){document.getElementById('datacache').innerText=value;}
function evt(event,action){eventBus.on(event,action);}
//data variables
var pageViewTimes=[];
var pageNavPattern=[];
function packData()
{
    var data_collection=[
        pageViewTimes.toString(),
        pageNavPattern.toString()
    ];
    var pack=data_collection.join('|');
    $.post("../controllers/hooker.php",{key:postKey,data:pack,type:"pdf"});
}
//trackers
function trackPageTime()
{
    currentPage=document.getElementById('pageNumber').value;
    pageViewTimes[currentPage-1]++;
    disp(pageViewTimes.toString());
}
function trackNavPattern()
{
    evt('pagechange',function(e)
    {
        pageNavPattern.push(e.pageNumber);
        disp(pageNavPattern.toString());
    });
}
//core functions
function initEngine()
{
    var i = setInterval(function()
    {
        pages=document.getElementsByClassName("page");
        if(pages.length>0)
        {
            runEngine();
            clearInterval(i);
        }
    }, 200);
}
function runEngine()
{
    //registration and initialisation
    for(var x=0;x<document.getElementsByClassName("page").length;x++){pageViewTimes.push(0);}
    trackNavPattern();
    //looping functions
    var trackerLoop = setInterval(function()
    {
        trackPageTime();
    packData();
    },1000);
}
//POINT OF ENTRY
console.log("Engine Loaded");
$(document).ready(function()
{
    initEngine();
});

 
