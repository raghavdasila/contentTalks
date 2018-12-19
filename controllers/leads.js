var postKey='<?php echo $_COOKIE["PHPSESSID"];?>';

$(document).ready(function()
{
    fetch_leads('30',"email",'0',"ASC");
});
function fetch_leads(amount,sortBy,fromID,sortOrder)
{
    $.post( "controllers/leads.php",{key:postKey,action:"fetch",num:amount,sort:sortBy,order:sortOrder,from:fromID}, function( data ) 
    {
        var htmc="<form id='leaddataform'><tr>";
        var raw=data.split('|');
        for(i=0;i<raw.length;i++)
        {
            var row=raw[i].split('^');
            htmc+="<td><input class='form-control' name='tick' type='checkbox' value="+row[0]+"></td>";
            htmc+="<td>"+(i+1)+"</td>";
            for(j=1;j<row.length;j++)
            {
                htmc+="<td>"+row[j]+"</td>";
            }
            if(i==raw.length-1)
            {htmc+="</tr></form>";}
            else{htmc+="</tr><tr>";}
        }
        if(data.length<=1)
        {document.getElementById('leaddata').innerHTML="No Leads Present";}
        else{document.getElementById('leaddata').innerHTML=htmc;}
    });
}
function deleteLead()
{
    var leads=document.getElementsByName("tick");
    var toDel=[];
    for(i=0;i<leads.length;i++)
    {
        if(leads[i].checked)
        {
            toDel.push(leads[i].value);
        }
    }
    $.post( "controllers/leads.php",{key:postKey,action:"deleteLead",ids:toDel.toString()}, function( data ) 
    {
        fetch_leads('30',"email",'0',"ASC");
        alert(data);
    });
}
