<?php // overloaded functions for sql insert query
// Created by Aaron C. 10/20/2024 Finished 02/06/2025

/**
 * Generates an SQL INSERT statement string.
 *
 * @param string $tableName The name of the table to insert into.
 * @param array $SQLValues An array of column names to be inserted.
 * @return string The generated SQL INSERT statement.
 */



 /*  function getColumnName($tableName, $conn){
    return $columnNames;
  } */
   function sql_inserting_com($tableName, $SQLValues){
    // declared function variables
    $arraySize = count($SQLValues);
    $lastNameValue = $SQLValues[$arraySize - 1];

    // start of SQL insert string building
    $sqlString = "INSERT INTO $tableName (";
    foreach($SQLValues as $SQLvalue){
       if($SQLvalue != $lastNameValue){
           $sqlString .= "`$SQLvalue`, ";
       }
       else{
           $sqlString .= "`$SQLvalue`) VALUES (";
       }
    }
    foreach($SQLValues as $SQLvalue){
       if($SQLvalue != $lastNameValue){
          $sqlString .= ":$SQLvalue, ";
       }
       else{
           $sqlString .= ":$SQLvalue)";
       }
    }
    return $sqlString; //SQL dynamic GENERATED statements
}

    /**
 * Generates an SQL UPDATE statement string based on the number of columns to update.
 *
 * @param string $tableName The name of the table to update.
 * @param array $tableColumnName An array of column names to be updated.
 * @return string The generated SQL UPDATE statement.
 */
   function update_string($tableName, $tableColumnName){
    //require 'wLInventory.php';
    $count = count($tableColumnName);
    $primaryIDName = find_ID($tableName);
    //start of switch selection on size of update statement
    switch($count){
        case 1:
            return "UPDATE $tableName SET `$tableColumnName[0]` = :columnValue1 WHERE $primaryIDName = :pValue";
        case 2:
            return "UPDATE $tableName SET `$tableColumnName[0]` = :columnValue1, `$tableColumnName[1]` = :columnValue2 WHERE $primaryIDName = :pValue";
        case 3:
            return "UPDATE $tableName SET `$tableColumnName[0]` = :columnValue1, `$tableColumnName[1]` = :columnValue2, `$tableColumnName[2]` = :columnValue3
                    WHERE $primaryIDName = :pValue";
        case 4: 
            return "UPDATE $tableName SET `$tableColumnName[0]` = :columnValue1, `$tableColumnName[1]` = :columnValue2, `$tableColumnName[2]` = :columnValue3,
                    `$tableColumnName[3]` = :columnValue4 WHERE $primaryIDName = :pValue";
        case 5:
            return "UPDATE $tableName SET `$tableColumnName[0]` = :columnValue1, `$tableColumnName[1]` = :columnValue2, `$tableColumnName[2]` = :columnValue3,
                    `$tableColumnName[3]` = :columnValue4, `$tableColumnName[4]` = :columnValue5 WHERE $primaryIDName = :pValue";
        case 6: 
            return "UPDATE $tableName SET `$tableColumnName[0]` = :columnValue1, `$tableColumnName[1]` = :columnValue2, `$tableColumnName[2]` = :columnValue3,
                    `$tableColumnName[3]` = :columnValue4, `$tableColumnName[4]` = :columnValue5, `$tableColumnName[5]` = :columnValue6 WHERE $primaryIDName = :pValue";
        case 7:
            return "UPDATE $tableName SET `$tableColumnName[0]` = :columnValue1, `$tableColumnName[1]` = :columnValue2, `$tableColumnName[2]` = :columnValue3,
                    `$tableColumnName[3]` = :columnValue4, `$tableColumnName[4]` = :columnValue5, `$tableColumnName[5]` = :columnValue6,
                    `$tableColumnName[6]` = :columnValue7 WHERE $primaryIDName = :pValue";
        default:
            return "invalid number of columns";
    }// end of switch stemntation needed
}
