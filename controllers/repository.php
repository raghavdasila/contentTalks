<?php 
    include "lock.php";
    $root="/usr/local/openresty/nginx/html/DATA/".$_SESSION['iid'];
    $path=$root;

    function putFiles($target_dir,$key)
    {
        $allowed=['txt','PDF','pdf','png','jpg','jpeg','bmp'];
        $allowed_size=1000000;
        for($i=0;$i<count($_FILES[$key]['name']);$i++)
        {
            $ext=explode('.',$_FILES[$key]["name"][$i]);
            if(array_search($ext[count($ext)-1],$allowed)==false)
            {}
            if(array_search($ext[count($ext)-1],$allowed)==false)
            {return 2;}
            if($_FILES[$key]['size'][$i]>$allowed_size)
            {return 3;}
            $target_file = $target_dir.basename($_FILES[$key]["name"][$i]);
            if(move_uploaded_file($_FILES[$key]["tmp_name"][$i], $target_file))
            {continue;}
            else{return 1;}
        }
        return "success";
    }
    function respond($msg)
    {//window.location="https://www.thecontenttalks.com/repository";
            $_SESSION['message']=$msg;
            echo '<script>
                window.parent.fetch_files();
            </script>';
    }
    switch($_POST['action'])
    {
        case 'open':
            $path.=$_POST['i_path'];
            $list=array_slice(scandir($path),2);
            $folders=[];
            $files=[];
            for($i=0;$i<count($list);$i++)
            {
                if(is_dir($path.'/'.$list[$i]))
                {array_push($folders,$list[$i]);}
                else
                {array_push($files,$list[$i]);}
            }
            $res= implode('^',$folders).'|'.implode('^',$files);
            if($_SESSION['message']!="")
            {
                $res.='|'.$_SESSION['message'];
                $_SESSION['message']="";
            }
            echo $res;
            break;
        case 'upload':
            $do=putFiles($root.$_POST['uploadpath'].'/','files');
            if(gettype($do)=="string"){echo '<script>window.parent.fetch_files();</script>';}
            else if($do==1){respond('Server Error! Please try again later');}
            else if($do==2){respond('Unsupported File Type detected!');}
            else if($do==3){respond('File size too large');}
            break;
        case 'addfolder':
            error_log($root.$_POST['uploadpath2'].'/'.$_POST['name']);
            if(file_exists($root.$_POST['uploadpath2'].'/'.$_POST['name'])==false)
            {
                mkdir($root.$_POST['uploadpath2'].'/'.$_POST['name']);
                echo '<script>window.parent.fetch_files();</script>';
            }
            else{respond("Folder Exists");}
            break;
        case 'deletefolder':
            $to_del=$path.$_POST['name'];
            $files=array_slice(scandir($to_del),2);
            $proceed=true;
            for($i=0;$i<count($files);$i++)
            {
                if(is_dir($to_del.'/'.$files[$i]))
                {
                    $proceed=false;
                }
            }
            if($proceed)
            {
                for($i=0;$i<count($files);$i++)
                {
                    unlink($to_del.'/'.$files[$i]);
                }
                rmdir($to_del);
                echo '<script>window.parent.fetch_files();</script>';
            }
            else{respond("Failed! Please make sure you delete subfolders first");}
            break;
        case 'openfile':
            $stat=stat($path.'/'.$_POST['name']);
            $dat=[$stat['size'],$stat['atime'],$stat['mtime']];
            echo implode('^',$dat);
            break;
        case 'deletefile':
            $to_del=$path.$_POST['todel'];
            unlink($to_del);
            echo '<script>window.parent.fetch_files();</script>';
            break;
    }
?>
