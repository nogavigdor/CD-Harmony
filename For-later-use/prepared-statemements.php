<?php 

//assuming there  are already created variables of $txtNam, $txtAdd and $txtCit
$stmt = $dbh->prepare("INSERT INTO Customers (CustomerName,Address,City)
VALUES (:nam, :add, :cit)");
$stmt->bindParam(':nam', $txtNam);
$stmt->bindParam(':add', $txtAdd);
$stmt->bindParam(':cit', $txtCit);
$stmt->execute();

//and another example
try {
    // Establish a database connection
    $dbh = new PDO("mysql:host=localhost;dbname=database_name", "username", "password");

    // SQL statement with placeholders
    $sql = "INSERT INTO Customers (CustomerName, Address, City) VALUES (?, ?, ?)";

    // Prepare the SQL statement
    $stmt = $dbh->prepare($sql);

    // Bind parameters to placeholders
    $stmt->bindParam(1, $txtNam, PDO::PARAM_STR);
    $stmt->bindParam(2, $txtAdd, PDO::PARAM_STR);
    $stmt->bindParam(3, $txtCit, PDO::PARAM_STR);

    // Execute the prepared statement
    if ($stmt->execute()) {
        echo "Data inserted successfully.";
    } else {
        // Handle the execution error
        echo "Error: " . $stmt->errorInfo()[2];
    }

    // Close the statement
    $stmt = null;
} catch (PDOException $e) {
    // Handle any connection error
    die("Connection failed: " . $e->getMessage());
}
