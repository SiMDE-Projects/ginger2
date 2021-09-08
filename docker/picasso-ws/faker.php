<?php
    $path_only = explode('&',parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH))[0];
    switch($path_only) {
        case "/getUserInfo":
            if($_GET["username"] != "amiotnoe") {
                http_response_code(404);
                exit(404);
            }
            // echo "userinfo";
            echo '{"username":"amiotnoe","firstName":"Noe","lastName":"AMIOT","mail":"noe.amiot@etu.utc.fr","profile":"ETU UTC","cards":{"Mifare":{"cardSerialNumber":"b7b1be6d","cardStartDate":1536568479403,"cardEndDate":null},"Desfire":{"cardSerialNumber":"806a93aa6d2a04","cardStartDate":1614067472040,"cardEndDate":null}},"legalAge":true}';
            break;
        case "/cardLookup":
            //echo "lookup";
            if($_GET["serialNumber"] != "042A6DAA936A80" && $_GET["serialNumber"] != "6DBEB1B7") {
                http_response_code(404);
                exit(404);
            }
            echo '{"username":"amiotnoe","firstName":"Noe","lastName":"AMIOT","mail":"noe.amiot@etu.utc.fr","profile":"ETU UTC","cards":{"Mifare":{"cardSerialNumber":"b7b1be6d","cardStartDate":1536568479403,"cardEndDate":null},"Desfire":{"cardSerialNumber":"806a93aa6d2a04","cardStartDate":1614067472040,"cardEndDate":null}},"legalAge":true}';
            break;
        default:
            echo "Action not recognized";
            break;
    }
?>