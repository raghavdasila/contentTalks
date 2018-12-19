# contentTalks
A mass mailing platform which generates unique clickbacks for linked pdfs (within the email). Each clickback allows the user to track lead's activities on the pdf. Uses PDF.js.
Before deployment, use models.py with Django to initialise a postgreSQL database.
Paste the following lines in the Nginx configuration file:  
     ```
        rewrite ^/verify /verify.php;
        rewrite ^/dashboard /dashboard.html;
        rewrite ^/repository /repository.html;
        rewrite ^/leads /leads.html;
        rewrite ^/send /send.html;
        rewrite ^/view /view.php;
    ```
    Project was hosted at thecontenttalks.com and is no longer under development.
