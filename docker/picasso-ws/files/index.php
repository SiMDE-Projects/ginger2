<?php
    $path_only = explode('&',parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH))[0];
    switch($path_only) {
        case "/getUserInfo":
            if($_GET["username"] != "testlogin") {
                http_response_code(404);
                exit(404);
            }
            echo '{"username":"testlogin","firstName":"John","lastName":"Doe","mail":"john.doe@etu.utc.fr","profile":"ETU UTC","cards":{"Mifare":{"cardSerialNumber":"D4C3B2A1","cardStartDate":1536568479403,"cardEndDate":null},"Desfire":{"cardSerialNumber":"FFEEDDCCBBAA","cardStartDate":1614067472040,"cardEndDate":null}},"legalAge":true}';
            break;
        case "/cardLookup":
            if($_GET["serialNumber"] != "FFEEDDCCBBAA" && $_GET["serialNumber"] != "D4C3B2A1") {
                http_response_code(404);
                exit(404);
            }
            echo '{"username":"testlogin","firstName":"John","lastName":"Doe","mail":"john.doe@etu.utc.fr","profile":"ETU UTC","cards":{"Mifare":{"cardSerialNumber":"D4C3B2A1","cardStartDate":1536568479403,"cardEndDate":null},"Desfire":{"cardSerialNumber":"FFEEDDCCBBAA","cardStartDate":1614067472040,"cardEndDate":null}},"legalAge":true}';
            break;
        default:
            echo "Action not recognized";
            break;
    }
