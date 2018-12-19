//V 0.1
//accessory functions
var getKeys = function(obj){
   var keys = [];
   for(var key in obj){
      keys.push(key);
   }
   return keys;
}
function disp1(data){document.getElementById("datacache1").innerHTML=data;}
function disp2(data){document.getElementById("datacache2").innerHTML=data;}
function disp3(data){document.getElementById("datacache3").innerHTML=data;}
//callbacks
function MouseMoves(e)
{
    disp2("Mouse at "+e.clientX+","+e.clientY);
    var graphic="<circle cx=\""+e.clientX.toString()+"\" cy=\""+e.clientY.toString()+"\" r=\"10\"style=\"stroke: black; fill: red;\"/>";
    document.getElementById("overlay").innerHTML=graphic
}
function MouseEnters(e)
{
    disp1(e.relatedTarget.className+"entered");
}
//core functions
function injectListeners()
{
    var elems=document.getElementsByTagName("*");
    for(i=0;i<elems.length;i++)
    {
        elems[i].onmouseenter=MouseEnters;
        elems[i].onmousemove=MouseMoves;
    }
}
function fetchTargets()
{
    injectListeners();
}
//POINT OF ENTRY
disp1("Engine Loaded");
fetchTargets();
