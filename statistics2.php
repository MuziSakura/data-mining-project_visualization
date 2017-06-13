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
    //$name1[0]  select_name: 表示要画的属性
    $name1 = explode('__', $name);
    $name_length = count($name1);
    //echo($name_length);

    $select_name = $name1[0];
    for($x = 1; $x < $name_length; $x ++)
    {
        $name2[$x] = explode('_', $name1[$x]);
    }
    
    // name2[$x][0]: 用于查找的属性名
    // name2[$x][1]: 用于查找的属性的取值
    
    if (($select_name ==="balance") or ($select_name ==="duration") or ($select_name ==="pdays"))
    {
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

        while($row = mysqli_fetch_assoc($query)) {
            array_push($data, $row);
        }

    }
    else {
        $sql = "SELECT distinct $select_name FROM $table";
        $tmp = array();
        $query = mysqli_query($conn, $sql);
        while($row = mysqli_fetch_assoc($query)) {
            array_push($tmp, $row);
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

        while($row = mysqli_fetch_assoc($query)) {
            array_push($data, $row);
        }


        foreach ($tmp as $valueObj) {
            $value = $valueObj[$select_name];

            $flag = false;
            foreach ($data as $candidateValueObj) {
                $candidateValue = $candidateValueObj[$select_name];
                if ($value === $candidateValue) {
                    $flag = true;
                    break;
                }
            }

            if ($flag === false) {
                array_push($data, array($select_name => $value, "COUNT(*)" => 0));
            }

        }

        usort($data, function($x, $y) {

            global $select_name;

            $valueX = $x[$select_name];
            $valueY = $y[$select_name];

            if (is_numeric($valueX)) {
                $valueX = (int)($valueX);
                $valueY = (int)($valueY);
                if ($valueX === $valueY) return 0;
                return ($valueX < $valueY) ? -1 : 1;
            }

            return strcmp($valueX, $valueY);

        });

    }

    
    //echo "get_json";

    echo json_encode($data);    
    
   

    mysqli_close($conn);
?>