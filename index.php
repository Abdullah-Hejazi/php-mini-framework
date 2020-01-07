<?php
    require __DIR__ . '/vendor/autoload.php';
    use Framework\Route;
    use Framework\Request;
    use Framework\Response;
    use Framework\Environment;
    use Framework\Console;
    require('Settings/router.php');

    Environment::Generate('Settings/.env');

    $request = new Request();

    $responseObject = new Response($request);
    $response = $responseObject->Start();

    echo $response;


?>