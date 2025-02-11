<!DOCTYPE html>
<html lang = "en">
<head>
    <meta charset = "UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Other form process page</title>
</head>
<body>
    <?php
    // Start of form processing for keyboard page
    // Variable list and require list
    require 'wLInventory.php';
    require $sOP;
    require $errorFunctions;

    // Start of main program
    // Insert form
    $form = $_POST['form'];
    switch($form){
        case 'form1':
            $insertOP = new insertOp(); // Class object for inserting data
            $columnValues = [];
            $columnNames = [];
            $count = 0;
            // Start of assigning variables to POST values

            foreach($_POST as $post => $values){
                if($post != "form" &&  $post != "tableSelect" && !empty($values)){
                    $columnValues[$count] = validate_input($values, $post);
                    if($post == "cost"){
                        if (!is_numeric($columnValues[$count])) {
                            ++$errorCount;
                            echo "The value for cost is not numeric. Error count: \"$errorCount\"<br>";
                            $columnValues[$count] = null;
                        } 
                        else {
                            $columnValues[$count] = (float)$columnValues[$count];
                        }
                    }
                    if($post != "p_id"){// change this value if the primary ID tags are different.
                        $columnNames[$count] = $post;
                    }
                    $count++;
                }
            }
            $table_name = validate_input($_POST['tableSelect'], "Table name");
            // End of assigning variable to POST values
            if($errorCount == 0) {
                $idName = find_ID($table_name);//goes to wLInventory.php
                $insertOP -> connect();
                $insertOP -> set_table_names($idName, ...$columnNames);// goes to SQLOp.php
                $insertOP -> add_query($table_name, ...$columnValues);// goes to SQLOp.php
                $insertOP -> DB_close();
                header("Location: inventoryForm.html");
                exit();
            }
            else{
                echo "No form data received";
            }
            break;

        // View Table form ***********************************************************************
        case 'form2':
            $viewOp = new queryOp(); // Instantiating queryOp class for viewing data
            $table_name = validate_input($_POST['tableView'], $description_k[6]);
            if($errorCount == 0) {
                $viewOp -> connect();
                /**
                 * Sets the table name and queries the table.
                 * 
                 * @param string $table_name Name of the table.
                 */
                $viewOp -> set_table_name($table_name);
                $viewOp -> query_table();
                $viewOp -> print_table();
                $viewOp -> DB_close();
            }
            else{
                echo "No form data received";
            }
            break;

        // Operation to update table row ***************************************************************************
        case 'form3':
            // Local variables
            $updateOp = new updateOp(); // Class object for updating data
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
                $tableNameValues['wattage'] = validate_input($_POST['wattValue'], "updating wattage value for table: $table_name");
            }

            if(!empty($_POST['modValue'])){
                $tableNameValues['modular'] = validate_input($_POST['modValue'], "updating modular value for table: $table_name");
            }
            if($errorCount == 0) {
                $updateOp -> connect();
                if(!empty($tableNameValues)){
                    /**
                     * Sets the table update values.
                     * 
                     * @param string $table_name Name of the table.
                     * @param array $tableNameValues Array of columns and values to update.
                     * @param int $p_id_value Primary ID value.
                     */
                    $updateOp -> set_table_update($table_name, $tableNameValues, $p_id_value);}
                else{echo "No values to update";}
                $updateOp -> update_table();
                $updateOp -> DB_close();
            }
            break;

        // Operation to delete table row ***********************************************************************
        case 'form4':
            $deleteOp = new deleteOp(); // Instantiating deleteOp class for deleting data
            $table_name = validate_input($_POST['tableDelete'], "deleting row for keyboard table");
            $p_id = validate_input($_POST['pValue'], $description_k[0]);
            if($errorCount == 0) {
                $deleteOp -> connect();
                /**
                 * Sets the table delete values.
                 * 
                 * @param string $table_name Name of the table.
                 * @param int $p_id Primary ID value.
                 */
                $deleteOp -> set_table_delete($table_name, $p_id);
                $deleteOp -> delete_row();
                $deleteOp -> DB_close();
            }
            break;
    }
    ?>
</body>
</html>
