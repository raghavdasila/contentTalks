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
    switch($_POST['action'])
    {
        case 'fetch_packages':
                $params=[
                        (int)$_POST['from'],
                        (int)$_POST['till']
                ];
                $packages=pg_query('SELECT id,timestamp,template,clickback FROM email_record ORDER BY timestamp DESC;');
                if($params[1]==-1){$params[1]=pg_num_rows($packages);}

                $returnContent=[];
                for($i=$params[0];$i<$params[1];$i++)
                {array_push($returnContent,implode('^',pg_fetch_row($packages,$i)));}
                echo implode('|',$returnContent);
        break;
        case 'fetch_data':
                $package=pg_query_params(
                        'SELECT * FROM email_record where id=$1;',
                        array($_POST['of'])
                );
                $record=pg_fetch_row($package);
                echo implode('^',$record);
        break;
        case 'fetch_raw_data':
                $params=[
                        $_POST['for']
                ];
                $package=pg_query(
                        'SELECT data FROM package_'.$params[0].' ORDER BY timestamp DESC;'
                );
                $returnContent=[];
                while($r=pg_fetch_row($package))
                {array_push($returnContent,implode('^',$r));}
                echo implode('|',$returnContent);
        case 'fetch_content_data':
                
                break;
        case 'fetch_lead_data':
                $params=[
                        (int)$_POST['for']
                ];
                $data=pg_query_params(
                        'SELECT * FROM leads WHERE id=$1',
                        $params
                );
                echo implode('^',pg_fetch_row($data));
        break;  
    }
?>
