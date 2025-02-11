<!DOCTYPE html>
<html lang = "en">
<head>
    <meta charset = "UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="formStyle.css"> 
    <title>Keyboard page</title>
</head>
<body>
<?php
// Created by Aaron C. 10/08/2024
require 'wLInventory.php';
require $db;
include $sSQLS;
 abstract Class SQLOp {
    // super class variables
   protected $statement;
   Protected $tableName;
   Protected $SQLstring;
   Protected $conn;
   protected $database;

   public function __construct(){
    $this -> database = new Database();
   }
   public function connect(){
    $this -> conn = null;
    try{
    $this -> conn = $this -> database -> getConnection();
    //echo "Database connection successful. <br>";
    }
    catch(PDOException $e){
       die("Connection failed" . $e->getMessage());
    }
   }
   public function DB_close(){
    $this -> database -> closeDB();
   }
 }

 // Class insertOp is used to add rows into existing table
 Class insertOp extends SQLOp {
    // class variables
    private $keys = [];
    
// function to set class variables
    function set_table_names (...$columnNames){
        $count = 0;
        foreach($columnNames as $columnName){
         $this -> keys[$count++] = $columnName;
        }
    }

    // function to insert new rows into a existing record
    function add_query($tableName,...$values){
        // function variables
        $SQLstring = "";
        $parList = [];

        // SQLstring is the variable that contains the SQL generated command
        //Function can be found in selectSQLString.php
        $SQLstring = sql_inserting_com($tableName ,$this -> keys);//SQL insert command GENERATOR

        // this for loop is used to bind the user's variables to the SQL command
        // parList is an array variable to connect sql place hold with user's variables
        for($i = 0; $i < count($this -> keys); $i++){
           if(!empty($values[$i])){
               $parList[":" . $this -> keys[$i]] = $values[$i];
           }
        }

        // The variable statement is assigned the SQLstring with the prepare method for binding values
        $statement = $this -> conn -> prepare($SQLstring);

        if ($statement === false) {// error checking if the SQL statement was prepared correctly
            die("SQL statement preparation failed: " . print_r($this -> conn->errorInfo(), true));
        }

        // the for loop binds user variable's as a reference and its value to statement variable
        foreach($parList as $para => $value){
            $statement -> bindValue($para, $value);
        }

        // Check if the statement is ready to execute
        if (!$statement) {
            echo "Statement preparation failed.<br>";
            print_r($this -> conn->errorInfo());
        }  
        // this if else statement executes the SQL command and tells you if it was successful or not.
     
        try{
           if($statement -> execute()){
              //echo "Record inserted successfully.";
              $this -> database -> closeDB();
            } 
           else{
              echo "Record insert not successful.";
           }
        }
        catch(PDOException $e){
            echo "Execution failed: " . $e -> getMessage();
        }
    }
 }

// Class queryOp is used to view a table in its entirety.
Class queryOp extends SQLOp {
    protected $tableName;
    private $stmt;
//function set
    public function set_table_name(string $tableName){
        $this -> tableName = $tableName;
    }

    //querying table
    public function query_table(){
        $this -> SQLstring = "SELECT * FROM ";
        $this -> stmt = $this -> conn -> query($this -> SQLstring.$this ->tableName);
    }

    public function print_table(){
        $headStmts = $this -> stmt -> fetch(PDO::FETCH_ASSOC);

        if($headStmts){
            echo "<table border = 1>";
        // generate table headers dynamically
           echo "<tr>";
           foreach(array_keys($headStmts) as $colName){
               echo "<th>" . htmlspecialchars($colName) . "</th>";
           }
           echo "</tr>";

           echo "<tr>";
        // display first row associated with headers
           foreach($headStmts as $row_value){
               echo "<td>" . htmlspecialchars($row_value) . "</td>"; 
           }
           echo "</tr>";

        // display remain table rows
           while($row = $this -> stmt -> fetch(PDO::FETCH_ASSOC)){
           echo "<tr>";
              foreach($row as $row_value){
                  echo "<td>" . htmlspecialchars($row_value) . "</td>";
              }
           echo "</tr>";
           }
           echo "</table>";
        }
        else {
            echo "No data found.";
        }
        $this -> database -> closeDB();
    }
}

class updateOp extends SQLOp{// updateOp class intended to update tables
    // class variables
    protected $statement;

    public function set_table_update($tableName, $columnValue, $pValue){
        include 'wLInventory.php';
        $columnArray = []; // array used to dynamically bind columnValues to column names
        $combineColumnArray =[];
        $tableColumnValue = array_values($columnValue);
        $tableColumnName = array_keys($columnValue);
        $this -> SQLstring = update_string($tableName, $tableColumnName);
        $this -> statement = $this -> conn -> prepare($this -> SQLstring);
        if($this -> statement === false){
            die("SQL statement preparation failed: " . print_r($this -> conn-> errorInfo(), true));
        }
        for($i = 1; $i <= count($tableColumnName); $i++){
            $columnArray [] = ":columnValue$i";
        };
        $combineColumnArray = array_combine($columnArray, $tableColumnValue);

        foreach ($combineColumnArray as $colName => $colValue){
            $this -> statement -> bindValue($colName, $colValue);
        }
        $this -> statement ->bindValue('pValue', $pValue);
    }

    public function update_table() {
        try{
            if($this -> statement -> execute()){
                echo "Update record successfully";
                $this -> database -> closeDB();
            }
        }
        catch(PDOException $e){
            echo "Execution not successful " . $e -> getMessage();

        }
    }
}// end of class updateOp

class deleteOp extends SQLOp {// delete rows
    public function set_table_delete($tableName, $deleteValue1){// set user variables to delete row
        // deleteValue1 is the primary ID to tables
        include 'wLInventory.php';
        $primaryIdName = find_ID($tableName);
        $this -> SQLstring = "DELETE FROM $tableName WHERE $primaryIdName = :deleteValue1";
        $this -> statement = $this -> conn -> prepare($this -> SQLstring);
        if($this -> statement === false){
            die("SQL statement preparation failed: " . print_r($this -> conn-> errorInfo(), true));
        }
        $this -> statement -> bindParam(':deleteValue1', $deleteValue1);
    }

    public function delete_row(){// function used to delete a row from a table.
        try{
            if($this -> statement -> execute()){
                echo "Row successfully deleted";
            }
        }
        catch(PDOException $e){
            echo "Deletion not successful " . $e -> getMessage();
        }
    }
}// end of class deleteOp

class pcSetUp extends SQLOp{
    /**
     * Adds a row to the pcsetups table.
     * 
     * @param mixed ...$values Values to be inserted into the table.
     */
    public function add_row(...$values){
        // start of function statements
        //$bindValues = [$p_ID_value, $mobo_ID_value, $gpu_ID_Value, $ram_ID_value, $psu_ID_value, $monitor_ID_value, $acc_ID_value, $kb_ID_value,
                //$mouse_ID_value, $PCcondition_value];
        try{
           $this -> statement = $this -> conn -> prepare("INSERT INTO pcsetups (pc_id, mobo_id, gpu_id, ram_id, psu_id, monitor_id, acc_id, kb_id, mouse_id, tableLocation, PCcondition)
            VALUES (:val1, :val2, :val3, :val4, :val5, :val6, :val7, :val8, :val9, :val10, :val11)");

           for($i = 1; $i <= count($values); $i++){
             $this -> statement -> bindValue(":val$i",$values[$i - 1]);
           }

           if($this -> statement){
            echo "Statement prepared successfully <br>";
           }
           else {
            echo "Statement preparation failed.<br>";
            print_r($this -> conn->errorInfo());
           }

           // executing pcsetup statement for execution to the database
           try{
              if($this -> statement -> execute()){
                 echo "Record inserted successfully.";
              }
              else{
                 echo "Insertion not successful.";
              }
           }
           catch(PDOException $e){
              echo "error: ". $e -> getMessage();
           }
       }
       catch(PDOException $e){
        echo "error: ". $e -> getMessage();
       }
    }// end of add_row function

    public function view_table(){
        try{
            $viewStatement = $this -> conn -> query("SELECT * FROM pcsetups");
            $data = $viewStatement -> fetchAll(PDO::FETCH_ASSOC);
            if($data){
                return $data;
            }
            else{
                return ['message' => 'No Data fetched'];
            }
        }
        catch (PDOException $e) {
            return ['error' => $e->getMessage()];
        }
    }// end of view_table function

    public function update_row($columnNameValue, $pValue){
        $tableName = "pcsetups";
        $columnArray = [];
        $tableColumnName = array_keys($columnNameValue);
        $tableColumnValue = array_values($columnNameValue);
        $updateStmnt = update_string($tableName, $tableColumnName);
        $updateSqlStmnt = $this -> conn -> prepare($updateStmnt);
        if($updateSqlStmnt === false){
            die("SQL statement preparation failed: " . print_r($this -> conn-> errorInfo(), true));
        }
        for($i = 1; $i <= count($tableColumnName); $i++){
            $columnArray [] = ":columnValue$i";
        };
        $combineColumnArray = array_combine($columnArray, $tableColumnValue);
        foreach ($combineColumnArray as $colName => $colValue){
            $updateSqlStmnt -> bindParam($colName, $colValue);
        }
        $updateSqlStmnt -> bindParam('pValue', $pValue);

        try{
            if($updateSqlStmnt -> execute()){
                echo "Update record successfully";
            }
        }
        catch(PDOException $e){
            echo "Execution not successful " . $e -> getMessage();

        }
    }// end of update_row function

    public function delete_row($deleteValue){
        $this -> statement = $this -> conn ->prepare("DELETE FROM pcsetups WHERE pc_id = id_value");

        if($this -> statement === false){
            die("SQL statement preparation failed: " . print_r($this -> conn-> errorInfo(), true));
        }
        $this -> statement -> bindParam(':id_value', $deleteValue);

        try{
            if($this -> statement -> execute()){
                echo "Row successfully deleted";
            }
        }
        catch(PDOException $e){
            echo "Deletion not successful " . $e -> getMessage();
        }
    }//end of remove_row function
}// end of pcSetUp class
?>
</body>
</html>
