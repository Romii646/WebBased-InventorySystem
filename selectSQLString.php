<?php 
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
    return $sqlString;
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
    $primaryIDName = find_ID($tableName);// to find the primary ID name for the primary key column
    $sqlString = "UPDATE $tableName SET `$tableColumnName[0]` =";
    for($i = 1; $i <= $count; $i++){
        if($count == 1){
            $sqlString .= " :columnValue1 WHERE $primaryIDName = :pValue";
        }
        else if($i == $count){
            $sqlString .= " :columnValue$i WHERE $primaryIDName = :pValue";
        }
        else{
            $sqlString .= " :columnValue$i, `$tableColumnName[$i]` =";
        }
    
    }
    return $sqlString;
}
