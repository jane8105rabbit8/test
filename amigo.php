<?php
$ch = curl_init();

$token = file('token_gcdn.txt', $flags = FILE_IGNORE_NEW_LINES);
$token = implode($token);

curl_setopt($ch, CURLOPT_URL, "https://api.amigocdn.com/v2/distributions/");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
  "Content-Type: application/json; charset=utf-8",
  "Authorization: Bearer $token"
));

$response = curl_exec($ch);
curl_close($ch);

$num = substr_count($response, "\"name\"");
$decodeJSON = json_decode($response);
$distribution = explode("\"name\"", $response);

include 'search.php';

switch ($argv[1]){
  case "create_distribution":
    include 'create_distribution.php';
    switch ($argv[2]){
      case "one":
        create_one_distribution($argv[3], $argv[4], $argv[5], $argv[6], $argv[7], $token);
        break;
      case "many":
        create_many_distribution($argv[3], $argv[4], $token);
        break;
      default:
        echo "wrong input";
        break;
    }
    break;
  case "create_ssl":
    include 'create_ssl.php';
    create_ssl($argv[2], $argv[3], $token);
    break;
  case "get":
    include 'get.php';
    switch ($argv[2]){
      case "json":
        get_json($argv[3], $num, $decodeJSON);
        break;
      case "sentence":
        get_sentence($argv[3], $num, $distribution, $decodeJSON);
        break;
      default:
        echo "wrong input";
        break;
    }
    break;
  case "update":
    include 'update.php';
    update($argv[2], $argv[3], $num, $distribution, $decodeJSON, $token);
    break;
  case "redo":
    include 'redo.php';
    redo($num, $distribution, $decodeJSON, $token);
    break;
  case "backup":
    include 'backup.php';
    backup($num, $distribution, $decodeJSON, $token);
    break;
  case "restore":
    include 'restore.php';
    switch ($argv[2]){
      case "all":
        restore_all($num, $distribution, $decodeJSON, $token);
        break;
      case "part":
        restore_part($argv[3], $num, $distribution, $decodeJSON, $token);
        break;
      default:
        echo "wrong input";
        break;
    }
    break;
  case "invalidation":
    include 'invalidation.php';
    invalidation($argv[2], $argv[3], $num, $distribution, $decodeJSON, $token);
    break;
  default:
    echo "wrong input";
    break;
}
