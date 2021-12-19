<?php
  $datas = [
    "utc_etu" => [
      'username' => 'testlogin',
      'firstName' => 'John',
      'lastName' => 'Doe',
      'mail' => 'john.doe@etu.utc.fr',
      'profile' => 'ETU UTC',
      'cards' => [ 
        'Mifare' => [ 
          'cardSerialNumber' => 'D4C3B2A1',
          'cardStartDate' => 1536568479403,
          'cardEndDate' => NULL,
        ],
        'Desfire' => [
          'cardSerialNumber' => 'FFEEDDCCBBAA',
          'cardStartDate' => 1614067472040,
          'cardEndDate' => NULL,
        ],
      ],
      'legalAge' => true,
    ],
    "escom_etu" => [
      'username' => 'escomlogin',
      'firstName' => 'Etu',
      'lastName' => 'ESCOM',
      'mail' => 'etu@escom.fr',
      'profile' => 'ESCOM ETU',
      'cards' => [
        'Desfire' => [
          'cardSerialNumber' => 'ABC111',
          'cardStartDate' => 1614067472040,
          'cardEndDate' => NULL,
        ],
      ],
      'legalAge' => true,
    ],
    "utc_pers" => [
      'username' => 'perslogin',
      'firstName' => 'PERSONNEL',
      'lastName' => 'UTC',
      'mail' => 'pers@utc.fr',
      'profile' => 'PERSONNEL UTC',
      'cards' => [
        'Desfire' => [
          'cardSerialNumber' => 'ABC222',
          'cardStartDate' => 1614067472040,
          'cardEndDate' => NULL,
        ],
      ],
      'legalAge' => true,
    ],
    "escom_pers" => [
      'username' => 'escompers',
      'firstName' => 'PERSONNEL',
      'lastName' => 'ESCOM',
      'mail' => 'pers@escom.fr',
      'profile' => 'PERSONNEL ESCOM',
      'cards' => [
        'Desfire' => [
          'cardSerialNumber' => 'ABC333',
          'cardStartDate' => 1614067472040,
          'cardEndDate' => NULL,
        ],
      ],
      'legalAge' => true,
    ],
  ];

  $path_only = explode('&',parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH))[0];
  switch($path_only) {
      case "/getUserInfo":
        switch ($_GET["username"]) {
          case 'testlogin':
            echo json_encode($datas["utc_etu"]);
            break;
          case 'escomlogin':
            echo json_encode($datas["escom_etu"]);
            break;
          case 'perslogin':
            echo json_encode($datas["utc_pers"]);
            break;
          case 'escompers':
            echo json_encode($datas["escom_pers"]);
            break;
          case 'generate_account_500':
            http_response_code(500);
            exit(500);
          default:
            http_response_code(404);
            exit(404);
        }
      case "/cardLookup":
        switch($_GET["serialNumber"]) {
          case "FFEEDDCCBBAA":
          case "D4C3B2A1":
            echo json_encode($datas["utc_etu"]);
          default:
            http_response_code(404);
            exit(404);
        }
      default:
          echo "Action not recognized";
          break;
  }
