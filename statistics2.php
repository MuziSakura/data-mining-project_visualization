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

    $name1 = explode('__', $name);
    $name_length = count($name1);
    //echo($name_length);

    $select_name = $name1[0];

    for($x = 1; $x < $name_length; $x ++)
    {
        $name2[$x] = explode('_', $name1[$x]);
    }
    

    $sql = "SELECT $select_name, COUNT(*) FROM $table WHERE (";
    for($x = 1; $x < $name_length; $x ++)
    {
        if($x === $name_length - 1) {

            $sql .= $name2[$x][0] . "='" . $name2[$x][1] . "'";
        }
        else {
            $sql .= $name2[$x][0] . "='" . $name2[$x][1] . "' AND " ;
        }
        
    }
    

    $sql .= ") GROUP BY $select_name";
    //echo $sql;

    $query = mysqli_query($conn, $sql);
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