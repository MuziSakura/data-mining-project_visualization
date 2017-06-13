<?php

// 如需更改数据库配置在此更改即可
$host = "localhost";
$db_user = "temp";
$db_pass = "Temp2333!";

// 连接到mysql
$conn = mysqli_connect($host,$db_user,$db_pass);
// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
echo 'Connected successfully' . "<br />";

// 创建bank数据库
$sql = "DROP DATABASE IF EXISTS bank";
if ($conn->query($sql) === TRUE) {
    echo "Database check successfully" . "<br />";
} else {
    echo "Error checking database: " . $conn->error . "<br />";
}
$sql = "CREATE DATABASE IF NOT EXISTS bank";
if ($conn->query($sql) === TRUE) {
    echo "Database created successfully" . "<br />";
} else {
    echo "Error creating database: " . $conn->error . "<br />";
}

// 选择bank数据库进行操作，创建数据表bank_tbl
mysqli_select_db($conn, 'bank');

$sql = "CREATE TABLE bank_tbl (
    age INT NOT NULL, 
    job char(20) NOT NULL, 
    marital char(10) NOT NULL, 
    education char(10) NOT NULL,
    mydefault char(5) NOT NULL, 
    balance INT NOT NULL, 
    housing char(5) NOT NULL, 
    loan char(5) NOT NULL, 
    contact char(10) NOT NULL, 
    day INT NOT NULL, 
    month char(3) NOT NULL, 
    duration INT NOT NULL, 
    campaign INT NOT NULL, 
    pdays INT NOT NULL, 
    previous INT NOT NULL, 
    poutcome char(10) NOT NULL,
    y char(3) NOT NULL
    )";
if ($conn->query($sql) === TRUE) {
    echo "Table bank_tbl created successfully"  . "<br />";
} else {
    echo "Error creating table: " . $conn->error;
}

//解析csv文件，返回二维数组，第一维是一共有多少行csv数据，第二维是键名为csv列名，值为当前行当前列的csv数据值
function input_csv($csv_file) {
    $result_arr = array ();
    $i = 0;
    $csv_key_name_arr[0] = "age";
    $csv_key_name_arr[1] = "job";
    $csv_key_name_arr[2] = "marital";
    $csv_key_name_arr[3] = "education";
    $csv_key_name_arr[4] = "mydefault";
    $csv_key_name_arr[5] = "balance";
    $csv_key_name_arr[6] = "housing";
    $csv_key_name_arr[7] = "loan";
    $csv_key_name_arr[8] = "contact";
    $csv_key_name_arr[9] = "day";
    $csv_key_name_arr[10] = "month";
    $csv_key_name_arr[11] = "duration";
    $csv_key_name_arr[12] = "campaign";
    $csv_key_name_arr[13] = "pdays";
    $csv_key_name_arr[14] = "previous";
    $csv_key_name_arr[15] = "poutcome";
    $csv_key_name_arr[16] = "y";

    while ($data_line = fgetcsv($csv_file, 10000, ";")) {
        //print_r($data_line);
        //echo "<br />";
        //echo $csv_key_name_arr[0]. "<br />";
        if($i == 0){
            $GLOBALS['csv_key_name_arr'] = $data_line;
            //echo $GLOBALS['age']. "<br />";
            $i++;
            continue;
        }
        foreach($GLOBALS['csv_key_name_arr'] as $csv_key_num=>$csv_key_name){
            //echo $data_line[$csv_key_num] ."<br />";
            $result_arr[$i][$csv_key_num] = $data_line[$csv_key_num];
            //echo $result_arr[$i][$csv_key_num] ."<br />";
        }
        $i++;
    }
    return $result_arr;
}
?>
<script type="text/javascript" src="jquery-1.8.2.js"></script>