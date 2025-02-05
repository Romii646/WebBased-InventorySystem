<?php
// Created by Aaron C. 10/08/2024
   $db = 'database.php';
   $sSQLS = "selectSQLString.php";
   $sOP = 'SQLOp.php';
   $wordList = ["p_id", "name", "condition", "cost", "location", "type", "size", "addMonitorSize", "speed", "wattage", "modular", "addMotherboardSize"];
   $tableList = ["keyboards", "table"];
   $description_k = ["key board ID", "computer ID", "key board name", "Condition of part", "Keyboard cost",
                     "Keyboard location", "input for table name"];


 if (!function_exists('find_ID')) {
   function find_ID($table_name) {
       $idArray = [
         "accessories" => "acc_id",
         "graphicscards" => "gpu_id",
         "keyboards" => "kb_id",
         "mice" => "mouse_id",
         "monitors" => "monitor_id",
         "motherboards" => "mobo_id",
         "pcsetups" => "pc_id",
         "powersupplies" => "psu_id",
         "ramsticks" => "ram_id"
                     ];
          return $idArray[$table_name];
      }
}