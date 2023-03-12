<!DOCTYPE html>

<?php
$servername = "localhost";
$dbname = "u660736675_esp32";
$username = "u660736675_artur";
$password = "****************";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$sql1 = "SELECT consumo, reading_time FROM MonitoramentoAgua WHERE DAY(reading_time) = DAY(DATE_SUB(CURRENT_TIMESTAMP, INTERVAL 3 HOUR)) ORDER BY id ASC";
$sql2= " SELECT SUM(consumo), day(reading_time) FROM `MonitoramentoAgua` where month(reading_time)=month(DATE_SUB(CURRENT_TIMESTAMP, INTERVAL 3 HOUR)) group by day(reading_time)";
$sql3 = " SELECT SUM(consumo), month(reading_time) FROM `MonitoramentoAgua` where year(reading_time)=year (DATE_SUB(CURRENT_TIMESTAMP, INTERVAL 3 HOUR)) group by month(reading_time)";


if ($result = $conn->query($sql1)) {
   
$vetorx=[];
$vetory=[];
$row_consumo = 0;    
    while ($row = $result->fetch_assoc()) {
        $row_reading_time = $row["reading_time"];
        $vetorx[]=$row_reading_time;  
        $row_consumo=$row_consumo+$row["consumo"];
        $vetory[]=$row_consumo;
        
    }
    $result->free();
}


if ($result = $conn->query($sql2)) {
    
   
    while ($row = $result->fetch_assoc()) {
        
        $dia[]= $row["day(reading_time)"];
        $somadia[] = $row["SUM(consumo)"];
        
    }
     $result->free();
}


if ($result = $conn->query($sql3)) {
    
   
    while ($row = $result->fetch_assoc()) {
        
        $mes[]= $row["month(reading_time)"];
        $somames[] = $row["SUM(consumo)"];
        
    }
     $result->free();
}




$conn->close();

?> 

<html>

<head>
<script src="https://cdn.plot.ly/plotly-2.16.1.min.js"></script>
<style> #graph1{

    
     margin-left: 20%;
     margin-right: 20%;

  }
  #graph2{

    
     margin-left: 20%;
     margin-right: 20%;

  }
  
  #graph3{

    
     margin-left: 20%;
     margin-right: 20%;

  }
  
  #center {
      
      text-align: center;
      font-family: verdana;
      
  }

</style>
</head>

<body>
    
     <div id="center" >
         <h1>Sistema supervisório</h1>
    </div>

    <div id="graph1" >
    </div>

    <div id="graph2" >
    </div>  
    
    <div id="graph3" >
    </div> 

    <script >
        

var trace1 = {
    x:<?php echo json_encode($vetorx); ?>,
    
    y: <?php echo json_encode($vetory); ?>,
    mode: 'markers'
};

var data = [trace1];

var layout = {
    title: "Consumo atual",
      xaxis: {title: 'Horário'},
  yaxis: {title: 'Consumo (L)'}
    
    
};

Plotly.newPlot("graph1", data, layout, {scrollZoom: true});

</script>



 <script >
        

var trace1 = {
    x:<?php echo json_encode($dia); ?>,
    
    y: <?php echo json_encode($somadia); ?>,
    type: 'bar'
};

var data = [trace1];

var layout = {
    title: "Consumo diário",
      xaxis: {title: 'Dia'},
  yaxis: {title: 'Consumo (L)'}
   
};

Plotly.newPlot("graph2", data, layout, {scrollZoom: true});

</script>



<script >
        

var trace1 = {
    x:<?php echo json_encode($mes); ?>,
    
    y: <?php echo json_encode($somames); ?>,
    type: 'bar'
};

var data = [trace1];

var layout = {
    title: "Consumo mensal",
      xaxis: {title: 'Mês'},
  yaxis: {title: 'Consumo (L)'}
    
};

Plotly.newPlot("graph3", data, layout, {scrollZoom: true});

</script>


</body> 

</html>


