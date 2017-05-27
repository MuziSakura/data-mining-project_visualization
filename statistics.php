<?php
    header('Access-Control-Allow-Origin: *');
    $username = "temp"; 
    $password = "Temp2333!";   
    $host = "localhost";
    $database = "bank";
    $table = "bank_tbl";

    $conn = mysqli_connect($host, $username, $password);
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    //echo 'Connected succe,ssfully' . "<br />";
    mysqli_select_db($conn, 'bank');
    //$connection = mysql_select_db($database, $server);
    // 这个函数参数怎么传递？
    $name = $_GET["name"];
    
    $query = mysqli_query($conn, "SELECT $name, COUNT(*) FROM $table GROUP BY $name");
    if ( ! $query ) {
            
            die;
    }

    $data = array();

    http_response_code(200);
    header("Content-type: application/json");

    for ($x = 0; $x < mysqli_num_rows($query); $x++) {
        $data[] = mysqli_fetch_assoc($query);
    }
    //echo "get_json";

    echo json_encode($data);    
    
   

    mysqli_close($conn);
?>