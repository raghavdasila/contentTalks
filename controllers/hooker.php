<?php 
    date_default_timezone_set("Asia/Kolkata");
    include "lock.php";
    $file=$_SESSION["file"];
    $clkblk=$_SESSION["clickback"];
    $lead_id=$_SESSION["lid"];

    $conn="host=localhost dbname=postgres user=postgres password=contenttalks";
    $dbc=pg_connect($conn);
    $owner=pg_query_params("SELECT owner_id from aux_clickback where clickback=$1;",array($clkblk));
    $iid=pg_fetch_row($owner);
    pg_close($dbc);
    $conn="host=localhost dbname=user".$iid[0]." user=postgres password=contenttalks";
    $dbc=pg_connect($conn);

    $data_recieved=$_POST['data'];
    $data_parsed=explode('|',$data_recieved);

    $r_input=array(
        'action'=>"activity",
        'on'=>date('d-m-Y'),
        'by'=>$_SESSION['leadmail'],
        'id'=>$_SESSION['lid'],
        'content'=>$_SESSION['file'],
        'pageViewTime'=>explode(',',$data_parsed[0]),
        'pageNavPattern'=>explode(',',$data_parsed[1])
    );
    $input=json_encode($r_input);
    if($_SESSION['persistentID']=='notset' || $_SESSION['persistentID']=='dontset')
    {
        $record=pg_query_params(
            'INSERT INTO package_'.$clkblk.' values(
                default,
                current_timestamp,
                $1
            ) RETURNING id;
        ',array($input));      
        $_SESSION['persistentID']=pg_fetch_row($record)[0];
    }
    else if($_SESSION['persistentID']!='dontset')
    {
        $pid=(int)$_SESSION['persistentID'];
        $record=pg_query_params(
            'UPDATE package_'.$clkblk.' SET
                data=$1 where id=$2
            RETURNING id;
        ',array($input,$pid));      
    }
    pg_close($dbc); 
?> 
