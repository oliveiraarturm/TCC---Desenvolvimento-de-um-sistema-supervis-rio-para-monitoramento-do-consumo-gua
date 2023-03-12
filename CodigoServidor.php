<?php
$servername = "localhost";
$dbname = "u660736675_esp32";
$username = "u660736675_artur";
$password = "***********";
$api_key_value = "AXYLeagmiaYFo";
$api_key= $sensor = $localizacao = $value1 = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $json = file_get_contents('php://input');
    $data = json_decode($json,true);
    if ($data["api_key"]==$api_key_value)
     {
        $sensor = $data["sensor"];
        $localizacao = $data["localizacao"];
        $consumo = $data["consumo"];
        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        } 
        $sql = "INSERT INTO MonitoramentoAgua (sensor, localizacao, consumo)
        VALUES ('" . $sensor . "', '" . $localizacao . "', '" . $consumo/1000 . "')";
        if ($conn->query($sql) === TRUE) {
            echo "New record created successfully";
        } 
        else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    
        $conn->close();
    }
    else {
        echo "Wrong API Key provided.";
    }

}
else {
    echo "No data posted with HTTP POST.";
}



