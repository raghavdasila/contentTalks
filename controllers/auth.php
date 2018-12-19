<?php
    $conn="host=localhost dbname=postgres user=postgres password=contenttalks";
    $dbc=pg_connect($conn);
    session_start();
    if($_POST['action'])
    {
        if($_POST['action']=="logout")
        {
            session_destroy();
            pg_close($dbc);
            header("Location: ../index.php?invalid=5");
        }
        if($_POST['action']=="login")
        {
            $params=[$_POST['email'],md5($_POST['pwd'])];
            $query=pg_query_params('select COUNT(*) from aux_account_verify where email=$1;',array($params[0]));
            $query2=pg_query_params('select COUNT(*) from aux_account where email=$1 and password=$2',$params);
            $q=pg_fetch_row($query);
            $q2=pg_fetch_row($query2);
            if((int)$q[0]!=0 && (int)$q2[0]!=0)
            {
                pg_close($dbc);
                session_destroy();
                header("Location: ../index.php?invalid=2");
                exit;
            }
            $query=pg_query_params('select * from aux_account where email=$1 and password=$2',$params);
            $accountDetails=pg_fetch_row($query);
            if(pg_num_rows($query)==1)
            {
                $_SESSION['iid']=$accountDetails[0];
                $_SESSION['em']=$accountDetails[1];
                $_SESSION['ph']=$accountDetails[2];
                $_SESSION['nm']=$accountDetails[3];
                $_SESSION['set']='set';
                pg_close($dbc);
                header("Location: ../dashboard");
            }
            else
            {
                pg_close($dbc);
                header("Location: ../index.php?invalid=1");
            }
        }
        if($_POST['action']=="signup")
        {
            $check=pg_query_params('select COUNT(*) from aux_account where email=$1;',array($_POST['email']));
            $exists=pg_fetch_row($check);
            if((int)$exists[0]!=0)
            {
                pg_close($dbc);
                header("Location: ../index.php?invalid=4");
            }
            //email| phone| name | company| address| state| country| pincode| password | designation
            $params=[$_POST['email'],$_POST['phone'],$_POST['name'],$_POST['company'],$_POST['address'],'','','',md5($_POST['pwd']),$_POST['desig']];
            $to = $_POST['email'];
            $verify_link=sha1((string)mt_rand());
            $subject = "ContenTalks Account Creation verification code";
            $txt = "Click here to verify you account: https://thecontenttalks.com/verify?email=".$_POST['email']."&v=".$verify_link;

            $query=pg_query_params("insert into aux_account_verify values(default,$1,$2);",array($_POST['email'],$verify_link));
            $pg_paramInjectors='';
            for($i=1;$i<=count($params);$i++)
            {$pg_paramInjectors=$pg_paramInjectors.',$'.$i;}
            $query=pg_query_params("insert into aux_account values(default".$pg_paramInjectors.");",$params);
            $query=pg_query_params("select id from aux_account where email=$1;",array($_POST['email']));
            $q=pg_fetch_row($query);
            $dir='../DATA/'.$q[0].'/';
            mkdir($dir);
            
            $headers = "From: verify@contenttalks.com\r";
            mail($to,$subject,$txt,$headers);
            pg_close($dbc);
            header('Location: ../index.php?invalid=2');
        }
    }
    else if($_SESSION['set'])
    {
        pg_close($dbc);
    }
    else
    {
        session_destroy();
        pg_close($dbc);
        header("Location: ../index.php?invalid=1");
    }
?>
