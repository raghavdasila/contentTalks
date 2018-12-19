var postKey='<?php echo $_COOKIE["PHPSESSID"];?>';

$('#listleads').hide();
$('#contentselector').hide();
$('#emailtemplates').hide();
$(document).ready(function()
{
    
});
var toSend=[];
var filesSelected=[];
var foldersSelected=[];
var template="";

function reset()
{
    toSend=[];
    document.getElementById('leadsnum').innerText=toSend.length;
    filesSelected=[];
    document.getElementById('contentnum').innerText=filesSelected.length;
    template="";
    document.getElementById('templateselected').innerText=template;
    $('#SL').removeClass('btn-success');
    $('#SL').addClass('btn-warning');
    $('#SC').removeClass('btn-success');
    $('#SC').addClass('btn-warning');
    $('#SE').removeClass('btn-success');
    $('#SE').addClass('btn-warning');
}
function setLeads()
{
    var leads=document.getElementsByName("tick");
    toSend=[];
    for(i=0;i<leads.length;i++)
    {
        if(leads[i].checked)
        {
            toSend.push(leads[i].value);
        }
    }
    $('#listleads').hide();
    if(toSend.length>0)
    {
        $('#SL').removeClass('btn-warning');
        $('#SL').addClass('btn-success');
    }
    document.getElementById('leadsnum').innerText=toSend.length;
}
function showleads()
{
    $('#listleads').show();
    fetch_leads('-1',"email",'0',"ASC");
}
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

var inner_path="/";
var folder_c1='<button type="button" class="btn btn-outline-dark btn-lg m-1" onclick="dopen(\'';
var folder_c2='\')"><i class="icon-md mdi mdi-folder"></i>';
var folder_c3='</button>';

var file_img_c1='<button type="button" class="btn btn-dark btn-lg m-1" onclick="fopen(\'';
var file_img_c2='\')"><i class="icon-lg mdi mdi-file-image"></i><h6>';
var file_img_c3='</h6></button>';

var file_pdf_c1='<button type="button" class="btn btn-dark btn-lg m-1" onclick="fopen(\'';
var file_pdf_c2='\')"><i class="icon-lg mdi mdi-file-pdf"></i><h6>';
var file_pdf_c3='</h6></button>';

var current_file="";

function setTemplate(type)
{
    template=type;
    document.getElementById('templateselected').innerText=template;
    $('#SE').removeClass('btn-warning');
    $('#SE').addClass('btn-success');
}
function show_repo()
{
    $('#contentselector').show();
    fetch_files("/");
}
function selectfile()
{
    if(inner_path=='/')
    {
        filesSelected.push(inner_path+current_file);    
    }
    else
    {
        filesSelected.push(inner_path+'/'+current_file);
    }
    document.getElementById('contentnum').innerText=filesSelected.length;
    $('#SC').removeClass('btn-warning');
    $('#SC').addClass('btn-success');
}
function selectfolder()
{
    alert('Coming Soon!');
}
function fetch_files()
{
    $.post( "controllers/repository.php",{key:postKey,action:"open",i_path:inner_path}, function( data ) 
    {
        var dir=data.split('|');
        var folders=dir[0].split('^');
        var files=dir[1].split('^');

        var folder_html="";
        var file_html="";
        for(i=0;i<folders.length;i++)
        {
            folder_html+=folder_c1+folders[i]+folder_c2+folders[i]+folder_c3;
        }
        if(folders.length==1 && folders[0]=="")
        {
            folder_html="No more folders here!";
        }
        for(i=0;i<files.length;i++)
        {
            var exts=files[i].split('.');
            var ext=exts[exts.length-1];
            if(ext=='png' || ext=='jpg' || ext=='jpeg' || ext=='bmp')
            {
                file_html+=file_img_c1+files[i]+file_img_c2+files[i]+file_img_c3;
            }
            else if(ext=='pdf')
            {
                file_html+=file_pdf_c1+files[i]+file_pdf_c2+files[i]+file_pdf_c3;
            }
        }
        if(file_html=="")
        {
            file_html="No files found!";
        }
        document.getElementById('folderDisplay').innerHTML=folder_html;
        document.getElementById('fileDisplay').innerHTML=file_html;
        document.getElementById('path').textContent=inner_path;
        if(dir.length==3){alert(dir[2]);}
      });
}
function back()
{
    var i;
    for(i=inner_path.length-1;inner_path[i]!='/';i--){;}
    inner_path=inner_path.substr(0,i+1);
    if(inner_path[inner_path.length-1]=='/')
    {
        inner_path=inner_path.substr(0,inner_path.length-1);
    }
    if(inner_path==""){inner_path="/";}
    fetch_files();
}

function dopen(handle) //directories
{
    if(inner_path[inner_path.length-1]!='/')
    {
        inner_path+='/'+handle;    
    }
    else{inner_path+=handle;}
    fetch_files();
}
function fopen(handle) //files
{
    $('#fileDetails').modal();
    document.getElementById('filename').value=handle;
    current_file=handle;
    var exts=handle.split('.');
    var ext=exts[exts.length-1];
    document.getElementById('filetype').value=ext;
    $.post( "controllers/repository.php",{key:postKey,action:"openfile",name:inner_path+'/'+handle}, function( data ) 
    {
        var dat=data.split('^');
        document.getElementById('filesize').value=dat[0];
        document.getElementById('fileaccess').value=dat[1];
        document.getElementById('filemodified').value=dat[2];
        document.getElementById('file').value=inner_path+'/'+handle;
    });
}
function fview()
{
    window.location.href="DATA/<?php echo $_SESSION['iid'];?>"+inner_path+'/'+current_file;
}
//-----------------
function process()
{
    //--prep for sending to server
    var pack= [toSend.join('^'),filesSelected.join('^'),foldersSelected.join('^'),template];
    if((document.getElementById('leadsnum').innerText=='0')||
    (document.getElementById('contentnum').innerText=='0')||
    (document.getElementById('templateselected').innerText=""))
    {alert("Set All Options");}
    switch(template)
    {
        case 'pdfb':
            var exts=filesSelected[0].split('.');
            var ext=exts[exts.length-1];
            if(filesSelected.length!=1 || ext!="pdf")
            {
                alert("This Template Only Supports 1 pdf");
                reset();
            }
            break;
    }
    if((document.getElementById('leadsnum').innerText=='0')||(document.getElementById('contentnum').innerText=='0')||(document.getElementById('templateselected').innerText=""))
    {alert("Set All Options");}
    else
    {
        $.post( "controllers/send.php",{key:postKey,action:"send",package:pack.join('|')}, function( data ) 
        {
            alert('Your content has been delivered to your leads, monitor progress in reports section');
        });
    }
}