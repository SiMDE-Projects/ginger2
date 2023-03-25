<?php
/** @noinspection PhpMissingBreakStatementInspection */
$datas = [
    [
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
    [
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
    [
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
    [
        'username' => 'escompers',
        'firstName' => 'PERSONNEL',
        'lastName' => 'ESCOM',
        'mail' => 'pers@escom.fr',
        'profile' => 'ESCOM PERSONNEL',
        'cards' => [
            'Desfire' => [
                'cardSerialNumber' => 'ABC333',
                'cardStartDate' => 1614067472040,
                'cardEndDate' => NULL,
            ],
        ],
        'legalAge' => true,
    ],
    [
        'username' => 'sejournant',
        'firstName' => 'SEJOURNANT',
        'lastName' => 'UTC',
        'mail' => 'sejournant@utc.fr',
        'profile' => 'SEJOURNANT',
        'cards' => [
            'Desfire' => [
                'cardSerialNumber' => 'ABC444',
                'cardStartDate' => 1614067472040,
                'cardEndDate' => NULL,
            ],
        ],
        'legalAge' => true,
    ],
    [
        'username' => 'unknownprofiletype',
        'firstName' => 'Firstname',
        'lastName' => 'Lastname',
        'mail' => 'mario@utc.fr',
        'profile' => 'unknownprofiletype',
        'cards' => [
            'Desfire' => [
                'cardSerialNumber' => 'ABC555',
                'cardStartDate' => 1614067472040,
                'cardEndDate' => NULL,
            ],
        ],
        'legalAge' => true,
    ],
    [
        'username' => 'temporaire',
        'firstName' => 'TEMPORAIRE',
        'lastName' => 'UTC',
        'mail' => 'temporaire@utc.fr',
        'profile' => 'TEMPORAIRE',
        'cards' => [
            'Desfire' => [
                'cardSerialNumber' => 'ABC666',
                'cardStartDate' => 1614067472040,
                'cardEndDate' => NULL,
            ],
        ],
        'legalAge' => true,
    ],
    [
        'username' => 'personnelrecherche',
        'firstName' => 'PERSONNEL DE RECHERCHE',
        'lastName' => 'UTC',
        'mail' => 'personnelrecherche@utc.fr',
        'profile' => 'PERSONNEL DE RECHERCHE',
        'cards' => [
            'Desfire' => [
                'cardSerialNumber' => 'ABC777',
                'cardStartDate' => 1614067472040,
                'cardEndDate' => NULL,
            ],
        ],
        'legalAge' => true,
    ],
    [
        'username' => 'esccetu',
        'firstName' => 'ESCC ETU',
        'lastName' => 'UTC',
        'mail' => 'etu@escc.fr',
        'profile' => 'ESCC ETU',
        'cards' => [
            'Desfire' => [
                'cardSerialNumber' => 'ABC888',
                'cardStartDate' => 1614067472040,
                'cardEndDate' => NULL,
            ],
        ],
        'legalAge' => true,
    ],
    [
        'username' => 'visiteurescom',
        'firstName' => 'VISITEUR ESCOM',
        'lastName' => 'UTC',
        'mail' => 'visiteur@escom.fr',
        'profile' => 'VISITEUR ESCOM',
        'cards' => [
            'Desfire' => [
                'cardSerialNumber' => 'ABC999',
                'cardStartDate' => 1614067472040,
                'cardEndDate' => NULL,
            ],
        ],
        'legalAge' => true,
    ],
    [
        'username' => 'esccpers',
        'firstName' => 'ESCC PERSONNEL',
        'lastName' => 'ESCC',
        'mail' => 'pers@escc.fr',
        'profile' => 'ESCC PERSONNEL',
        'cards' => [
            'Desfire' => [
                'cardSerialNumber' => 'ABCD111',
                'cardStartDate' => 1614067472040,
                'cardEndDate' => NULL,
            ],
        ],
        'legalAge' => true,
    ],
    [
        'username' => 'etuthese',
        'firstName' => 'ETU THESE',
        'lastName' => 'UTC',
        'mail' => 'etuthese@utc.fr',
        'profile' => 'ETU THESE',
        'cards' => [
            'Desfire' => [
                'cardSerialNumber' => 'ABCD222',
                'cardStartDate' => 1614067472040,
                'cardEndDate' => NULL,
            ],
        ],
        'legalAge' => true,
    ],
    [
        'username' => 'sejournantetu',
        'firstName' => 'SEJOURNANT ETU',
        'lastName' => 'UTC',
        'mail' => 'sejournanteru@utc.fr',
        'profile' => 'SEJOURNANT ETU',
        'cards' => [
            'Desfire' => [
                'cardSerialNumber' => 'ABCD333',
                'cardStartDate' => 1614067472040,
                'cardEndDate' => NULL,
            ],
        ],
        'legalAge' => true,
    ],
    [
        'username' => 'societe',
        'firstName' => 'SOCIETE',
        'lastName' => 'UTC',
        'mail' => 'societe@utc.fr',
        'profile' => 'SOCIETE',
        'cards' => [
            'Desfire' => [
                'cardSerialNumber' => 'ABCD444',
                'cardStartDate' => 1614067472040,
                'cardEndDate' => NULL,
            ],
        ],
        'legalAge' => true,
    ],
    [
        'username' => 'visiteur',
        'firstName' => 'VISITEUR',
        'lastName' => 'UTC',
        'mail' => 'visiteur@utc.fr',
        'profile' => 'VISITEUR',
        'cards' => [
            'Desfire' => [
                'cardSerialNumber' => 'ABCD555',
                'cardStartDate' => 1614067472040,
                'cardEndDate' => NULL,
            ],
        ],
        'legalAge' => true,
    ],
    [
        'username' => 'butc',
        'firstName' => 'BUTC',
        'lastName' => 'UTC',
        'mail' => 'butc@utc.fr',
        'profile' => 'BUTC',
        'cards' => [
            'Desfire' => [
                'cardSerialNumber' => 'ABCD666',
                'cardStartDate' => 1614067472040,
                'cardEndDate' => NULL,
            ],
        ],
        'legalAge' => true,
    ],
];

function getUserByLogin($username): ?array
{
    global $datas;
    foreach ($datas as $data) {
        if ($data['username'] === $username) {
            return $data;
        }
    }
    return null;
}

function getUserByCard($card): ?array
{
    global $datas;
    foreach ($datas as $user) {
        foreach ($user["cards"] as $data) {
            if ($data['cardSerialNumber'] === $card) {
                return $user;
            }
        }
    }
    return null;
}

$path_only = explode('&', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH))[0];
switch ($path_only) {
    case "/getUserInfo":
        $user = getUserByLogin($_GET['username']);
        if ($user) {
            echo json_encode($user);
            http_response_code(200);
            exit(200);
        }
        if ($_GET["username"] === 'generate_account_500') {
            http_response_code(500);
            exit(500);
        }
        http_response_code(404);
        exit(404);
    case "/cardLookup":
        $user = getUserByCard($_GET['serialNumber']);
        if ($user) {
            echo json_encode($user);
            http_response_code(200);
            exit(200);
        }
        http_response_code(404);
        exit(404);
    default:
        echo "Action not recognized";
        break;
}
