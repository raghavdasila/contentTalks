<?php
    if($_GET['email'] && $_GET['v'])
    {
        $conn="host=localhost dbname=postgres user=postgres password=contenttalks";
        $dbc=pg_connect($conn);
        $query=pg_query_params('select * from aux_account_verify where email=$1;',array($_GET['email']));
        $q=pg_fetch_row($query);
        if(strcmp($_GET['verify'],$q[2]))
        {
            $query=pg_query_params('delete from aux_account_verify where email=$1;',array($_GET['email']));
            $query=pg_query_params('select * from aux_account where email=$1;',array($_GET['email']));
            $u=pg_fetch_row($query);
            //------SETUP
            $setup=pg_query(
                'create database user'.$u[0].';'
            );
            pg_close($dbc);
            $conn="host=localhost dbname=user".$u[0]." user=postgres password=contenttalks";
            $dbc=pg_connect($conn);
            $setup=pg_query(
                'CREATE TABLE "leads" 
                (
                    "id" serial NOT NULL PRIMARY KEY, 
                    "timestamp" timestamp with time zone NOT NULL,
                    "email" text NOT NULL,
                    "phone" text NOT NULL,
                    "name" text NOT NULL,
                    "designation" text NOT NULL,
                    "address" text NOT NULL
                );
                CREATE INDEX "lead_email" ON "leads" ("email" text_pattern_ops);'
            );
            $setup=pg_query(
                'CREATE TABLE "email_record" 
                (
                    "id" serial NOT NULL PRIMARY KEY, 
                    "timestamp" timestamp with time zone NOT NULL,
                    "leads" text[],
                    "content" text[],
                    "clickback" text NOT NULL UNIQUE,
                    "template" text NOT NULL
                );
                CREATE INDEX "clickbacks" ON "email_record" ("clickback" text_pattern_ops);'
            );
            //-------
        }
        pg_close($dbc);
        header('Location: index.php?invalid=3');
    }
?>
