var postKey='<?php echo $_COOKIE["PHPSESSID"];?>';
var currentView="";
var viewDat=[];

$(document).ready(function()
{
    fetchPackages(0,-1);
});
function showPackages()
{
    if(currentView=="package")
    {alert("Already Displayed!");}
    else
    {fetchPackages(0,-1);}
}
function showReports(type)
{
    if(currentView!="package_details")
    {
        alert("Select a package!");
    }
    else
    {
        if(type=="lead")
        {
            $.post( "controllers/reports.php",{key:postKey,action:"fetch_raw_data",for:viewDat[4]}, function( data ) 
            {
                var raw_data=data.split('|');
                var htmc="<thead ><th>Action</th><th>On</th><th>By</th></thead>";
                for(i=0;i<raw_data.length;i++)
                {
                    var json_data=raw_data[i].split('^');
                    for(x=0;x<json_data.length;x++)
                    {
                        var parsed_data=JSON.parse(json_data[x]);
                        if(parsed_data==null)continue;
                        var transferObj=json_data[x].replace(/"/g,'^');
                        htmc+="<tr onclick='fetch_lead_details(\""+transferObj+"\");' style='cursor:pointer'>";
                        htmc+="<td>"+parsed_data['action']+"</td>";
                        htmc+="<td>"+parsed_data['on']+"</td>";
                        htmc+="<td>"+parsed_data['by']+"</td>";
                        htmc+="</tr>";
                    }
                }
                if(data.length<=1)
                {document.getElementById('datadisp').innerHTML="No Packages Found";}
                else{document.getElementById('datadisp').innerHTML=htmc;}
            });
        }
        else if(type=="content")
        {
            $.post( "controllers/reports.php",{key:postKey,action:"fetch_raw_data",for:viewDat[4]}, function( data ) 
            {
                var content=viewDat[3].split(',');

            });
        }
    }
}
function fetch_lead_details(data)
{
    $('#details').modal();
    var parsed_data=JSON.parse(data.replace(/\^/g,'"'));
    console.log(data);
    document.getElementById('detailsTitle').innerText="Lead Activity Detail";
    $.post( "controllers/reports.php",{key:postKey,action:"fetch_lead_data",for:parsed_data['id']}, function( data ) 
    {
        var leadData=data.split('^');
        var htmc="<table class='table table-dark centre-aligned-table top-aligned-table' >";
        switch(parsed_data['action'])
        {
            case 'opened':
                htmc+="<tr><td>Action:</td><td>Callback Link Opened</td></tr>";
                htmc+="<tr><td>On:</td><td>"+parsed_data['on']+"</td></tr>";
                htmc+="<tr><td>By:</td><td>";

                htmc+="<table class='table-dark'>";
                htmc+="<tr><td>Added:</td><td>"+leadData[1]+"</td></tr>";
                htmc+="<tr><td>Email:</td><td>"+leadData[2]+"</td></tr>";
                htmc+="<tr><td>Phone:</td><td>"+leadData[3]+"</td></tr>";
                htmc+="<tr><td>Name:</td><td>"+leadData[4]+"</td></tr>";
                htmc+="<tr><td>Designation:</td><td>"+leadData[5]+"</td></tr>";
                htmc+="<tr><td>Address:</td><td>"+leadData[6]+"</td></tr>";
                htmc+="</td></tr>";
                break;
            case 'activity':
                htmc+="<tr><td>Action:</td><td>Callback Link Opened</td></tr>";
                htmc+="<tr><td>On:</td><td>"+parsed_data['on']+"</td></tr>";
                htmc+="<tr><td>Content:</td><td>"+parsed_data['content']+"</td></tr>";
                htmc+="<tr><td>Page View Times (seconds):</td><td>"+parsed_data['pageViewTime']+"</td></tr>";
                htmc+="<tr><td>Page Navigation Pattern:</td><td>"+parsed_data['pageNavPattern']+"</td></tr>";

                htmc+="<tr><td>By:</td><td>";

                htmc+="<table class='table-dark'>";
                htmc+="<tr><td>Added:</td><td>"+leadData[1]+"</td></tr>";
                htmc+="<tr><td>Email:</td><td>"+leadData[2]+"</td></tr>";
                htmc+="<tr><td>Phone:</td><td>"+leadData[3]+"</td></tr>";
                htmc+="<tr><td>Name:</td><td>"+leadData[4]+"</td></tr>";
                htmc+="<tr><td>Designation:</td><td>"+leadData[5]+"</td></tr>";
                htmc+="<tr><td>Address:</td><td>"+leadData[6]+"</td></tr>";
                htmc+="</td></tr>";
                break;
                break
        }
        htmc+="</table>";
        document.getElementById('detailsBody').innerHTML=htmc;
    });
}
function fetchPackages(from,till)
{
    $.post( "controllers/reports.php",{key:postKey,action:"fetch_packages",from:from,till:till}, function( data ) 
    {
        var htmc="<thead class='bg-light'><th>Timestamp</th><th>Tempate</th><th>Unique Clickback</th></thead>";
        var raw=data.split('|');
        var init_row=raw[0].split('^');
        htmc+="<tbody><tr onclick='fetch_dat("+init_row[0]+")' style='cursor:pointer';>";
        for(i=0;i<raw.length;i++)
        {
            var row=raw[i].split('^');
            for(j=1;j<row.length;j++)
            {
                htmc+="<td>"+row[j]+"</td>";
            }
            if(i==raw.length-1)
            {htmc+="</tr></tbody>";}
            else{htmc+="</tr><tr onclick='fetch_dat("+raw[i+1].split('^')[0]+")' style='cursor:pointer';>";}
        }
        currentView="package";
        viewDat=[];
        if(data.length<=1)
        {document.getElementById('datadisp').innerHTML="No Packages Found";}
        else{document.getElementById('datadisp').innerHTML=htmc;}
    });
}
function fetch_dat(id)
{
    $.post( "controllers/reports.php",{key:postKey,action:"fetch_data",of:id}, function( data ) 
    {
        var dat=data.split('^');
        var htmc="<thead><th>Package Details</th></thead>";
        htmc+="<tr><td>Clickback</td><td>"+dat[4]+"</td></tr>";
        htmc+="<tr><td>Timestamp</td><td>"+dat[1]+"</td></tr>";
        htmc+="<tr><td>Template</td><td>"+dat[5]+"</td></tr>";
        htmc+="<tr><td>Total Target Leads</td><td>"+dat[2].split(',').length+"</td></tr>";
        htmc+="<tr><td>Content Files Attatched</td><td>"+dat[3].split(',').length+"</td></tr>";
        currentView="package_details";
        viewDat=[];
        viewDat=data.split('^');
        if(data.length<=1)
        {document.getElementById('datadisp').innerHTML="No Data Found";}
        else{document.getElementById('datadisp').innerHTML=htmc;}
    });
}
