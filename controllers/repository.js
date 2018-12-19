var postKey='<?php echo $_COOKIE["PHPSESSID"];?>';
var inner_path="/";
var folder_c1='<button type="button" class="btn btn-outline-light btn-lg m-1" onclick="dopen(\'';
var folder_c2='\')"><i class="icon-md mdi mdi-folder-open"></i>';
var folder_c3='</button>';

var file_img_c1='<button type="button" class="btn btn-dark btn-lg m-1" onclick="fopen(\'';
var file_img_c2='\')"><i class="icon-lg mdi mdi-file-image"></i><h6>';
var file_img_c3='</h6></button>';

var file_pdf_c1='<button type="button" class="btn btn-primary btn-lg m-1 thumb" onclick="fopen(\'';
var file_pdf_c2='\')"><i class="icon-lg mdi mdi-file-pdf-box"></i><p class="filetext"><i class="icon-sm mdi mdi-file-pdf"></i>';
var file_pdf_c3='</p></button>';

var current_file="";
var message="";

$(document).ready(function()
{
    fetch_files("/");
});
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
function deletef()
{
    $.post( "controllers/repository.php",{key:postKey,action:"deletefolder",name:inner_path}, function( data ) 
    {
        document.getElementById('formtarget').innerHTML=data;
        back();
    });
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
function setUploadPath()
{
    document.getElementById('uploadpath').value=inner_path;
    document.getElementById('uploadpath2').value=inner_path;
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
        document.getElementById('todel').value=inner_path+'/'+handle;
    });
}
function fview()
{
    window.location.href="DATA/<?php echo $_SESSION['iid'];?>"+inner_path+'/'+current_file;
}
