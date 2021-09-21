<?php
    $path_only = explode('&',parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH))[0];
    switch($path_only) {
        case "/getUserInfo":
            if($_GET["username" == "cerichar"]){
              echo '{"username":"cerichar","firstName":"Cesar","lastName":"RICHARD","mail":"cesar.richard@utc.fr","profile":"SEJOURNANT","cards":{"Desfire":{"cardSerialNumber":"806ab52a580e04","cardStartDate":1622101045315,"cardEndDate":null}},"legalAge":true}';
              exit(200);
            }
            if($_GET["username"] != "amiotnoe") {
                http_response_code(404);
                exit(404);
            }
            echo '{"username":"amiotnoe","firstName":"Noe","lastName":"AMIOT","mail":"noe.amiot@etu.utc.fr","profile":"ETU UTC","cards":{"Mifare":{"cardSerialNumber":"b7b1be6d","cardStartDate":1536568479403,"cardEndDate":null},"Desfire":{"cardSerialNumber":"806a93aa6d2a04","cardStartDate":1614067472040,"cardEndDate":null}},"legalAge":true}';
            break;
        case "/cardLookup":
            if($_GET["serialNumber"] != "806A93AA6D2A04" && $_GET["serialNumber"] != "B7B1BE6D") {
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