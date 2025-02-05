<!DOCTYPE html>
<html lang = "en">
<head>
    <meta charset = "UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Other form process page</title>
</head>
<body>
    <?php
    // Created by Aaron C. 09/25/2024 Finished: 12/01/2024
       error_reporting(E_ALL);
       ini_set('display_errors', 1);

       if($_SERVER["REQUEST_METHOD"] == "POST") {
          //echo "Form submitted successfully.<br>";
       } 
       else {
        echo "No form data received.<br>";
       }

    // start of form processing for keyboard page
    // variable list and require list
       require 'wLInventory.php';
       require $sOP;
       $errorCount = 0;
       
       // display error function
       function display_error($entryName){
           global $errorCount;
           ++$errorCount;
           echo "the field \"$entryName\" is required. Error count: \"$errorCount\"<br>";
        }
    // validate input function
       function validate_input($data, $entryName):String{
          global $errorCount;
          if(empty($data)){
            display_error($entryName);
            $data = "";
          }
          return $data;
        }
       // end of function list

       // start of main program
       // Insert form
       $form = $_POST['form'];
       switch($form){
        case 'form1':
           $insertOP = new insertOp(); // class object
           // start of assiging variables to POST values
           $table_name = validate_input($_POST['tableSelect'], $description_k[6]);
           $p_id_value = validate_input($_POST[$wordList[0]], $description_k[0]);
           $name = validate_input($_POST[$wordList[1]], $description_k[2]);
           $condition = validate_input($_POST[$wordList[2]], $description_k[3]);
           $cost = validate_input($_POST[$wordList[3]], $description_k[4]);
           if (!is_numeric($cost)) {
               ++$errorCount;
               echo "The value for cost is not numeric. Error count: \"$errorCount\"<br>";
               $cost = null;
           } 
           else {
               $cost = (float)$cost;
           }
           $location = validate_input($_POST[$wordList[4]],$description_k[5]);
           $type = $_POST[$wordList[5]];
           $monitorSize = $_POST[$wordList[7]];
           $speed = $_POST[$wordList[8]];
           $watt = $_POST[$wordList[9]];
           $mod = $_POST[$wordList[10]];
           $motherboardSize = $_POST[$wordList[11]];
           // end of assigning variable to POST values
           if($errorCount == 0) {
              $idName = find_ID($table_name);
              $insertOP -> connect();
              $insertOP -> set_table_names($idName, $wordList[1], $wordList[2], $wordList[3], $wordList[4],
                                            $wordList[6], type: $wordList[5], speed: $wordList[8], wattage: $wordList[9], modular: $wordList[10]);
              $insertOP -> add_query(id_value: $p_id_value, name_value: $name, condition_value: $condition, cost_value: $cost, location_value: $location, monitor_size_value: $monitorSize,
                                     motherboard_value: $motherboardSize,speed_value: $speed, type_value: $type, wattage_value: $watt, modular_value: $mod,tableName: $table_name);
              $insertOP -> DB_close();
              header("Location: inventoryForm.html");
              exit();
           }
           else{
              echo "No form data recieved";
           }
           break;

        // View Table form ***********************************************************************
        case 'form2':
            $viewOp = new queryOp();// instantiating queryOp class
            $table_name = validate_input($_POST['tableView'], $description_k[6]);
            if($errorCount == 0) {
                $viewOp -> connect();
                $viewOp -> set_table_name($table_name);
                $viewOp -> query_table();
                $viewOp -> print_table();
                $viewOp -> DB_close();
            }
            else{
                echo "No form data recieved";
            }
            break;

        // operation to update table row ***************************************************************************
        case 'form3':
            //local variables
            $updateOp = new updateOp();
            $tableNameValues =[];
            $table_name = validate_input($_POST['tableUpdate'], "update table row");

            try{
                if(!empty($_POST['pValue'])){
                    $p_id_value = validate_input($_POST['pValue'],"getting p_id value for $table_name");
                }
                else{
                    throw new Exception ("Need a Primary ID to update a row on a table.");
                }
            }
            catch(Exception $e){
                echo "Error:" . $e -> getMessage();
            }

            if(!empty($_POST['nValue'])){
                $tableNameValues['name'] = validate_input($_POST['nValue'], "updating table $table_name with current name value");
            }

            if(!empty($_POST['conditionValue'])){
                $tableNameValues['condition'] = validate_input($_POST['conditionValue'], "updating condition attribute for table: $table_name");
            }

            if(!empty($_POST['costValue'])){
                $tableNameValues['cost'] = validate_input($_POST['costValue'], "updating cost attribute on table: $table_name");
            }

            if(!empty($_POST['locationValue'])){
                $tableNameValues['location'] = validate_input($_POST['locationValue'], "updating location value in table $table_name"); 
            }

            if(!empty($_POST['monitorsSizeValue'])){
                $tableNameValues['size'] = validate_input($_POST['monitorsSizeValue'], "updating size value in table $table_name");
            }

            if(!empty($_POST['motherboardSizeValue'])){
                $tableNameValues['size'] = validate_input($_POST['motherboardSizeValue'], "updating size value in table $table_name");
            }

            if(!empty($_POST['typeValue'])){
                $tableNameValues['type'] = validate_input($_POST['typeValue'], "Updating type Value for table: $table_name");
            }

            if(!empty($_POST['speedValue'])){
                $tableNameValues['speed'] = validate_input($_POST['speedValue'], "updating speed value for table: $table_name");
            }

            if(!empty($_POST['wattValue'])){
                $tableNameValues['wattage'] = validate_input($_POST['wattValue'], "updatting wattage value for table: $table_name");
            }

            if(!empty($_POST['modValue'])){
                $tableNameValues['modular'] = validate_input($_POST['modValue'], "updating modular value for table: $table_name");
            }
            if($errorCount == 0) {
                $updateOp -> connect();
                if(!empty($tableNameValues)){
                $updateOp -> set_table_update($table_name, $tableNameValues, $p_id_value);}
                else{echo "no values to update";}
                $updateOp -> update_table();
                $updateOp -> DB_close();
            }
            break;

        // operation to delete table row ***********************************************************************
        case 'form4':
            $deleteOp = new deleteOp(); // instantiating deleteOp class
            $table_name = validate_input($_POST['tableDelete'], "deleting row for keyboard table");
            $p_id = validate_input($_POST['pValue'], $description_k[0]);
            if($errorCount == 0) {
                $deleteOp -> connect();
                $deleteOp -> set_table_delete($table_name, $p_id);
                $deleteOp -> delete_row();
                $deleteOp -> DB_close();
            }
            break;
       }
    ?>
</body>
</html>