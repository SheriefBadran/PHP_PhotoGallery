PHP_PhotoGallery
================
Publik Release: sheriefbadran.com
PHP_PhotoGallery = rotkatalog.

Installationsanvisning (databas):
Hitta filen install.php i PHP_PhotoGallery/data
Kör filen install.php lokalt.

En dump av databasen finns i:
PHP_PhotoGallery/data/test_db.sql

Körinstruktioner:
1. Öppna filen PHP_PhotoGallery/data/pathConfig.php
2. Ställ in root path på rad 9 eller 10.
3. Öppna filen PHP_PhotoGallery/data/db/DatabaseAccessModel.php
4. Sätt dina värden på de privata variablerna $dbUsername, $dbPassword och $connectionString.

Publik del av fotogalleri:
PHP_PhotoGallery/public_html/index.php

Administration av fotogalleri:
PHP_PhotoGallery/publi_html/admin/admin.php

För att CSS ska fungera korrekt, är det lämpligt att ändra link href i filen PHP_PhotoGallery/data/HTMLview.php.