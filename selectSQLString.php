<?php // overloaded functions for sql insert query
// Created by Aaron C. 10/20/2024 Finished 10/25/2024
// revision 
   function sql_inserting_com(){
    $SQLValues = func_get_args();
    $arraySize = func_num_args();
    switch($arraySize){
        case 6:
            $SQLstring = "INSERT INTO $SQLValues[5] ($SQLValues[0], $SQLValues[1], `$SQLValues[2]`, $SQLValues[3], $SQLValues[4]) 
                      VALUES(:$SQLValues[0], :$SQLValues[1], :$SQLValues[2], :$SQLValues[3], :$SQLValues[4])";
        return $SQLstring;
        case 7:
            $SQLstring = "INSERT INTO $SQLValues[6] ($SQLValues[0], $SQLValues[1], `$SQLValues[2]`, `$SQLValues[3]`, $SQLValues[4], $SQLValues[5]) 
                      VALUES(:$SQLValues[0], :$SQLValues[1], :$SQLValues[2], :$SQLValues[3], :$SQLValues[4], :$SQLValues[5])";
            return $SQLstring;
        case 8:
               $SQLstring = "INSERT INTO $SQLValues[7] ($SQLValues[0], $SQLValues[1], `$SQLValues[2]`, `$SQLValues[3]`, `$SQLValues[4]`, $SQLValues[5], $SQLValues[6]) 
                         VALUES(:$SQLValues[0], :$SQLValues[1], :$SQLValues[2], :$SQLValues[3], :$SQLValues[4], :$SQLValues[5], :$SQLValues[6])";
            return $SQLstring;
    }
}

    // this function is to help decide which SQL statement will be needed to update a record as a whole in part.
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
    }// end of switch statement
   } 
