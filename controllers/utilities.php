<?php 
    function createEntry($db,$params,$checkWith,$checkVal,$ownerIdentifier,$ownedBy)
    {
        $pg_paramInjectors='';
        for($i=1;$i<=count($params);$i++)
        {$pg_paramInjectors=$pg_paramInjectors.',$'.$i;}
        $check=pg_query_params('select COUNT(*) from '.$db.' where '.$checkWith.'=$1 and '.$ownerIdentifier.'=$2',array($checkVal,(int)$ownedBy));
        $c=pg_fetch_row($check);
        if((int)$c[0]==0)
        {
            $query=pg_query_params('insert into '.$db.' values(default'.$pg_paramInjectors.',current_timestamp);',$params);
            return 0;
        }
        else
        {return 1;}
    }

    function deleteEntry($db,$key,$val)
    {
        $query=pg_query_params('delete from '.$db.' where '.$key.'=$1;',array($val));
    }
    function editEntry($db,$key,$val)
    {
        $cols=pg_query_params('select column_name from information_schema.columns where table_name=$1',array($db));
        $c=pg_fetch_row($cols);
        $edit='update set ('.$c[0].',';
        while($c=pg_fetch_row($cols))
        {$edit=$edit.','.$c[0];}
        $edit=')=(default,';
        for($i=1;$i<pg_num_rows($cols);$i++)
        {$edit=$edit.',$'.$i;}
        $edit=$edit.') where '.$key.'=$'.pg_num_rows($cols).';';
        $query=pg_query_params($edit,array($val));
        if(pg_affected_rows($query)==pg_num_rows($cols))
        {return 0;}
        else
        {return 1;}
    }
?>
