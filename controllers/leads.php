<?php 
    include "lock.php";

    $conn="host=localhost dbname=user".$_SESSION['iid']." user=postgres password=contenttalks";
    $dbc=pg_connect($conn);

    function respond($msg)
    {
            $_SESSION['message']=$msg;
            echo '<script>
            </script>';
    }
    function createLead($params)
    {
        $injectors="";
        for($i=1;$i<=count($params);$i++)
        {$injectors.=",$".(string)$i;}
        $createLead=pg_query_params(
            'INSERT INTO leads values
            (
                default,
                current_timestamp
                '.$injectors.'
            );',$params
        );
        return pg_affected_rows($createLead);
    }
    function deleteLead($id)
    {
        $deleteLead=pg_query_params(
            'DELETE FROM leads WHERE id=$1;',array($id)
        );
        return 1-pg_affected_rows($deleteLead);
    }
    switch($_POST['action'])
    {
        case 'addLead':
            $params=[
                $_POST['email'],
                $_POST['phone'],
                $_POST['name'],
                $_POST['designation'],
                $_POST['address']
            ];
            if(createLead($params)==1)
            {
                echo '<script>alert("Lead Added Successfully");window.parent.fetch_leads(30,"email",0,"ASC");</script>';
            }
            else
            {
                echo '<script>alert("Lead Already Exists!")</script>';
            }
            break;
        case 'fetch':
            $params=[
                (int)$_POST['num'],
                $_POST['sort'],
                $_POST['order'],
                (int)$_POST['from']
            ];
            //SQL INJECTION PRONE,FIX LATER, PREFERABLY BEFORE GETTING HACKED
            $query=pg_query('SELECT id,email,phone,name,designation,address,timestamp from leads order by '.$params[1].' '.$params[2].';');
            if($params[0]==-1)
            {
                $params[0]=pg_num_rows($query);
            }
            $result=[];
            for($i=$params[3];$i<$params[0] && $i<pg_num_rows($query);$i++)
            {
                $q=pg_fetch_row($query,$i);
                array_push($result,implode('^',$q));
            }
            echo implode('|',$result);
            break;
        case 'bulkLead':
            $row = 1;
            $fails=0;
            if (($handle = fopen($_FILES['leads']['tmp_name'], "r")) !== FALSE) 
            {
                while (($data = fgetcsv($handle, 0, ",")) !== FALSE) 
                {
                    if(createLead($data)!=1)
                    {$fails++;}
                    $row++;
                }
                fclose($handle);
                echo '<script>alert("'.(string)($row-1).' Leads Added Successfully with '.$fails.' failures");
                window.parent.fetch_leads(30,"email",0,"ASC");
                </script>';
            }
            break;
        case 'deleteLead':
            $toDel=explode(',',$_POST['ids']);
            $i=0;
            $fails=0;
            for(;$i<count($toDel);$i++)
            {
                $fails+=deleteLead((int)$toDel[$i]);
            }
            echo (string)($i).' Leads Deleted Successfully with '.$fails.' failures';
            break;
    }
?>
