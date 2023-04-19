<?php

require_once(__DIR__ . "/../../vendor/autoload.php");

use App\BasicBlockVisitorAbstract;

class EdgeVisitor extends BasicBlockVisitorAbstract {
   protected function makeBasicBlockStub() {
      $uid = random_int(256, 268435456);
      $this->numBlocksInstrumented += 1;

      $code = '$____key = '.$uid.' ^ $GLOBALS["____instr"]["prev"];'.
              'isset($GLOBALS["____instr"]["map"][$____key]) ?: $GLOBALS["____instr"]["map"][$____key] = 0;'.
              '$GLOBALS["____instr"]["map"][$____key] += 1;'.
              '$GLOBALS["____instr"]["prev"] = '. ($uid >> 1) .';'.
              '$dirLen = strlen(__DIR__ . "\\");'.
              '$filePath = str_split(__FILE__, $dirLen);'.
              '$GLOBALS["____scores"]["vulScore"][] = $GLOBALS["____scores"]["fileScore"][$filePath];';

      return $this->codeToNodes($code);
   }

   protected function makeModuleStubFile() {
      // Request ID should be provided by Http Header Req-Id, e.g. Req-Id: 12345

      $code = 'if (! array_key_exists("____instr", $GLOBALS)) {'.
              '   $GLOBALS["____instr"]["map"] = array();'.
              '   $GLOBALS["____instr"]["prev"] = 0;'.
              '   function ____instr_write_map() {'.
              '      $f = fopen("/tmp/instr/map." . (isset($_SERVER["HTTP_REQ_ID"]) ? $_SERVER["HTTP_REQ_ID"] : 0), "w+");'.
              '      foreach ($GLOBALS["____instr"]["map"] as $k=>$v) {'.
              '          fwrite($f, $k . "-" . $v . "\n");'.
              '      }'.
              '      fclose($f);'.
              '   }'.
              '   register_shutdown_function("____instr_write_map");'.
              '}'.
              'if (! array_key_exists("____scores", $GLOBALS)) {'.
              '   $GLOBALS["____scores"]["vulScore"] = array();'.
              '   require_once(__DIR__ . "/../../vendor/autoload.php");'.
              '   use App\FileScore;'.
              '   $GLOBALS["____scores"]["fileScore"] = FileScore::SCORES;'.
              '   function ____instr_write_score() {'.
              '      $f = fopen("/tmp/instr/scores." . (isset($_SERVER["HTTP_REQ_ID"]) ? $_SERVER["HTTP_REQ_ID"] : 0), "w+");'.
              '      foreach ($GLOBALS["____scores"]["vulScore"] as $v) {'.
              '          fwrite($f, $v . "\n");'.
              '      }'.
              '      fclose($f);'.
              '   }'.
              '   register_shutdown_function("____instr_write_score");'.
              '}';

      return $this->codeToNodes($code);
   }

   protected function makeModuleStubHttp() {
      // (ob_start(null, 0, 0) 
      //     takes care of output_buffering. By inserting it at top-most level
      //     all output is captured here before sent to browser thus allowing header editing, 
      //     + disallowing any calls to flush it by ob_*_flush() also helps in case the app 
      //       tries to flush all buffers

      $code = 'if (! array_key_exists("____instr", $GLOBALS)) {'.
              '   $GLOBALS["____instr"]["map"] = array();'.
              '   $GLOBALS["____instr"]["prev"] = 0;'.
              '   function ____instr_write_map() {'.
              '      foreach ($GLOBALS["____instr"]["map"] as $k=>$v) {'.
              '          header("I-" . $k . ": " . $v);'.
              '      }'.
              '   }'.
              '   register_shutdown_function("____instr_write_map");'.
              '   ob_start(null, 0, 0);'.
              '}'.
              'if (! array_key_exists("____scores", $GLOBALS)) {'.
              '   $GLOBALS["____scores"]["vulScore"] = array();'.
              '   require_once(__DIR__ . "/../../vendor/autoload.php");'.
              '   use App\FileScore;'.
              '   $GLOBALS["____scores"]["fileScore"] = FileScore::SCORES;'.
              '   function ____instr_write_score() {'.
              '      foreach ($GLOBALS["____scores"]["vulScore"] as $k=>$v) {'.
              '          header("I-" . $k . ":" . $v);'.
              '      }'.
              '   }'.
              '   register_shutdown_function("____instr_write_score");'.
              '   ob_start(null, 0, 0);'.
              '}';

      return $this->codeToNodes($code);
   }
}
