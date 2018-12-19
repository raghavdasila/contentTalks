<?php 
    date_default_timezone_set("Asia/Kolkata");
    
    function render($files,$of,$clkblk,$lead_id,$lemail)
    {
        session_start();
        $_SESSION["file"]='../../DATA/'.$of.$files[0];
        $_SESSION["clickback"]=$clkblk;
        $_SESSION["persistentID"]='notset';
        $_SESSION["lid"]=$lead_id;
        $_SESSION["leadmail"]=$lemail;
        $_SESSION['set']="set";
        header("Location: engine/viewer.html");   
    }

    $CALLED=$_GET['item'];
    $BY=$_GET['by'];
    $conn="host=localhost dbname=postgres user=postgres password=contenttalks";
    $dbc=pg_connect($conn);
    $owner=pg_query_params("SELECT owner_id from aux_clickback where clickback=$1;",array($CALLED));
    $iid=pg_fetch_row($owner);
    pg_close($dbc);
    
    $conn="host=localhost dbname=user".$iid[0]." user=postgres password=contenttalks";
    $dbc=pg_connect($conn);

    $record=pg_query_params("SELECT * from email_record where clickback=$1",array($CALLED));
    $data=pg_fetch_row($record);
    $content=substr($data[3],1,strlen($data[3])-2);
    $SALT=$data[1];
    $emails=pg_query('SELECT id,email from leads where id in ('.substr($data[2],1,strlen($data[2])-2).');');
    
    $VISITOR=-1;
    while($e=pg_fetch_row($emails))
    {
        if(md5($e[1].$SALT)===$BY)
        {
            $VISITOR=$e[0];
            $r_input=array(
                'action'=>"opened",
                'on'=>date('d-m-Y H:i:s'),
                'by'=>$e[1],
                'id'=>$e[0]
            );
            $input=json_encode($r_input);
            $record=pg_query_params(
                'INSERT INTO package_'.$CALLED.' values(
                        default,
                        current_timestamp,
                        $1
                ) RETURNING id;
            ',array($input));
            $files=explode(',',$content);
            render($files,$iid[0],$CALLED,$e[0],$e[1]);
            break;
        }
    }
    pg_close($dbc); 
?> 
