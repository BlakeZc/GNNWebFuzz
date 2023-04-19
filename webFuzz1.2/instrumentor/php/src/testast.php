<?php
echo __DIR__."\n";
var_dump($_SERVER['SCRIPT_FILENAME']);
$dirLen = strlen("D:\\面向实战的网络安全智能隐患排查与威胁响应关键技术研究\\web-fuzzing\\webFuzz1.2\\instrumentor\\php\\src\\");
$filename = str_split(__FILE__, $dirLen);
echo $filename[1]."\n";
echo __FILE__."\n";
require_once(__DIR__ . "/../vendor/autoload.php");
// require_once(__DIR__ . "/../vendor/autoload.php");
// echo __DIR__ . "/../vendor/autoload.php";

// use PhpParser\Error;
// use PhpParser\ParserFactory;

// $code = file_get_contents("./testast.php");

// $parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);

// try {
//     $ast = $parser->parse($code);
// } catch (Error $error) {
//     echo "Parse error: {$error->getMessage()}\n";
// }
// var_dump($ast);
use App\FileScore;

$score = FileScore::SCORES;
echo serialize($score);

?>