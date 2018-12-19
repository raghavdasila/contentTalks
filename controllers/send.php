<?php 
    include "lock.php";
    $root="/usr/local/openresty/nginx/html/DATA/".$_SESSION['iid'];
    $conn="host=localhost dbname=user".$_SESSION['iid']." user=postgres password=contenttalks";
    $dbc=pg_connect($conn);

    function respond($msg)
    {
            $_SESSION['message']=$msg;
            echo '<script>
                
            </script>';
    }
    function fetch_template($clickback,$template,$to,$salt)
    {
        $secure_id=md5($to.$salt);  
        $url="https://thecontenttalks.com/view?item=".$clickback."&by=".$secure_id;
        $htmc="";
        if($template==="pdfb") //ONLY FOR DEV
        {
            $htmc="<h1>Check this brochure out!</h1>
                <a href=\"$url\">Brochure</a>
            ";
        }
        return $htmc;
    }
    function send_package($leads,$file_content,$folder_content,$template)
    {
        if(count($folder_content)>1)
        {   
            for($i=0;$i<count($folder_content);$i++)
            {
                $files=array_slice(scandir($root.$folder_content[$i]),2);
                for($j=0;$j<count($files);$j++)
                {
                    $files[$j]=$folder_content[$i].$files[$j];
                }
                $file_content=array_merge($file_content,$files);
            }
            
        }
        $CLICKBACK=hash('sha512',(string)time());
        $params=['{'.implode(',',$leads).'}','{'.implode(',',$file_content).'}',$CLICKBACK,$template];
        $pg_paramInjectors='';
        for($i=1;$i<=count($params);$i++)
        {$pg_paramInjectors=$pg_paramInjectors.',$'.$i;}
        $appendToRecord=pg_query_params(
            'INSERT INTO "email_record" values(
                default,
                current_timestamp
                '.$pg_paramInjectors.'
            );',$params
        );
        $query=pg_query_params(
            'SELECT timestamp from "email_record" 
                where "clickback"=$1
            ;',array($CLICKBACK)
        );
        $salt=pg_fetch_row($query);
        $SALT=$salt[0];
        $query=pg_query(
            'CREATE TABLE package_'.$CLICKBACK.' 
            (
                "id" serial NOT NULL PRIMARY KEY, 
                "timestamp" timestamp with time zone NOT NULL,
                "data" jsonb NULL
            );'
        );
        $query=pg_query(
            'SELECT email from leads where id in ('.implode(',',$leads).');'
        );
        
        while($to=pg_fetch_row($query))
        {
            $subject =$_SESSION['nm']." via TheContentTalks";
            $txt=fetch_template($CLICKBACK,$template,$to[0],$SALT);
            $usr=explode('@',$_SESSION['em']);
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            $headers .= "From: ".$usr[0]."@thecontenttalks.com\r\n";
            mail($to[0],$subject,$txt,$headers);
        }
        $uconn="host=localhost dbname=postgres user=postgres password=contenttalks";
        $udbc=pg_connect($uconn);
        $query=pg_query_params(
            'INSERT INTO aux_clickback values(
                default,$1,$2
            );',array($CLICKBACK,(int)$_SESSION['iid'])
        );
        pg_close($udbc);
    }
    switch($_POST['action'])
    {
        case 'send':
            $unpack=explode('|',$_POST['package']);
            $leads=explode('^',$unpack[0]);
            $file_content=explode('^',$unpack[1]);
            $folder_content=explode('^',$unpack[2]);
            $template=$unpack[3];
            send_package($leads,$file_content,$folder_content,$template);
            
            //(file_get_contents("http://localhost:8000");
            break;
    }
    pg_close($dbc);
?>