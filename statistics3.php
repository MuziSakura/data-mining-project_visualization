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
    //name: age___job_student__maried_no___duration_-122.3_122.3
    //name1[0]: 要画的属性
    //name1[1]: my_id
    //name1[2]: my_id_2
    //
    //name1[1][1...]: 用于查找数据的条件，形式：duration_-122.95_122.95
    $name1 = explode('___', $name);
    $name_length = count($name1);
   
    //foo 为True代表条件有两种，my_id my_id_2;
    if($name1[1] !== 'null')
        $foo = True;
    else 
        $foo = False;
    //echo($name_length);

    $select_name = $name1[0];//要画的属性的名字


    if ($foo)
    {

        $my_id = explode('__', $name1[1]);
        //echo($my_id[0]);  //education_secondary
        $name1_length = count($my_id);//第一种条件的个数
        //echo("name1_length: ");
        //echo($name1_length);
        $my_id_2 = explode('__', $name1[2]);
        //echo($my_id[0]);
        $name2_length = count($my_id_2);//第二种条件的个数
        //echo("name2_length: ");
        //echo($name2_length);
        //echo($my_id_2[0]);
        $left_flag = array();
        $right_flag = array();
        $flag_name = array(); 
        $name1_flag = array();// 第一种条件
        $name2_flag = array();// 第二种条件
       
        $sql = "SELECT $select_name, COUNT(*) FROM $table WHERE (";
        for($x =0; $x < $name1_length; $x ++)
        {
             $name1_flag = explode('_', $my_id[$x]);
             

             if($x === $name1_length - 1) {

                $sql .= $name1_flag[0] . "='" . $name1_flag[1] . "' AND ";
            }
            else {
                $sql .= $name1_flag[0] . "='" . $name1_flag[1] . "' AND " ;
            }
        }
        for($x = 0; $x < $name2_length; $x ++)
        {
            $name2_flag = explode('_', $my_id_2[$x]);
            if($x === $name2_length - 1) {

                $sql .= $name2_flag[0] . ">='" . $name2_flag[1] . " ' AND " . $name2_flag[0] . "<='" . $name2_flag[2] . "'";
            }
            else {
                $sql .= $name2_flag[0] . "<='" . $name2_flag[1] . " ' AND " . $name2_flag[0] . ">='" . $name2_flag[2] . "' AND " ;
            }           
        }
        $sql .= ") GROUP BY $select_name";
        //echo($sql);
    }
   

   
    else{
        //echo("hello my_id_2");
        //$my_id = explode('__', $name1[1]);
        //$name1_length = count($my_id);//第一种条件的个数
        $my_id_2 = explode('__', $name1[2]);
        //echo($my_id_2[0]);
        $name2_length = count($my_id_2);//第二种条件的个数
        //echo($name2_length);
        $left_flag = array();
        $right_flag = array();
        $flag_name = array(); 
        //$name1_flag = array();// 第一种条件
        $name2_flag = array();// 第二种条件
        //$name1_flag = explode('__', $my_id);
        //echo($my_id_2[1]);
        for($x = 0; $x < $name2_length; $x ++)
        {
            //echo($my_id_2[$x]);
            $name2_flag[$x] = explode('_', $my_id_2[$x]);
            //echo($name2_flag[$x][0]);  
            $flag_name[$x] = $name2_flag[$x][0];//第$x个条件的属性名
            $left_flag[$x] = $name2_flag[$x][1];// 第x个条件的左边界
            $right_flag[$x] = $name2_flag[$x][2];// 第x个条件的右边界
            //echo($flag_name[$x]);
            //echo($left_flag[$x]);
            //echo($right_flag[$x]);    
        }

        $sql = "SELECT $select_name, COUNT(*) FROM $table WHERE (";
        for($x = 0; $x < $name2_length; $x ++)
        {
            if($x === $name2_length - 1) {
                $sql .= $flag_name[$x] . "<='" . $right_flag[$x] . " ' AND " . $flag_name[$x] . ">='" . $left_flag[$x] . "'";
            } 
            else {
                $sql .= $flag_name[$x] . "<='" . $right_flag[$x] . " ' AND " . $flag_name[$x] . ">='" . $left_flag[$x] . "' AND " ;
            }    
        }
        $sql .= ") GROUP BY $select_name";
    }
    
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
    
    if (($select_name ==="balance") or ($select_name ==="duration") or ($select_name ==="pdays"))
    {

    }

    else {
        $sql = "SELECT distinct $select_name FROM $table";
        $tmp = array();
        $query = mysqli_query($conn, $sql);
        while($row = mysqli_fetch_assoc($query)) {
            array_push($tmp, $row);
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