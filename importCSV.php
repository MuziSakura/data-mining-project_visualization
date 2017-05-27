<!DOCTYPE html>
<html>
<head>
    <title>import CSV</title>
</head>
<body>

<?php
    include_once("config.php");

    //接收通过form表单提交过来的文件
    $filename = $_FILES['file']['tmp_name'];
    //没有找到文件提示
    if (empty ($filename)) {
        echo '请选择要导入的CSV文件!' . "<br />";
    } else {
        //找到提交的文件进行提示
        echo '正在读取文件，请稍等...'. "<br />";
        //打开读取文件数据
        $handle = fopen($filename, 'r');
        //解析csv自定义的函数
        $result = input_csv($handle);
        //获取结果集数据数组文件总行数
        $len_result = count($result);
        echo 'length is ' . $len_result ."<br />";
        if ($len_result == 0) {
            echo '没有任何数据！';
        } else {
            //循环获取各字段值
            $data_values = "";
            for ($i = 1; $i <= $len_result; $i++) {
                //赋值给变量，如果为中文必须进行转码成数据库一致的编码
                //echo $result[$i][0];
                $age = $result[$i][0];
                $job = $result[$i][1];
                $marital = $result[$i][2];
                $education = $result[$i][3];
                $mydefault = $result[$i][4];
                $balance = $result[$i][5];
                $housing = $result[$i][6];
                $loan = $result[$i][7];
                $contact = $result[$i][8];
                $day = $result[$i][9];
                $month = $result[$i][10];
                $duration = $result[$i][11];
                $campaign = $result[$i][12];
                $pdays = $result[$i][13];
                $previous = $result[$i][14];
                $poutcome = $result[$i][15];
                $y = $result[$i][16];
                //拼接数据的SQL语句

                $data_values = "($age, '$job', '$marital', '$education', '$mydefault', $balance, '$housing', '$loan', '$contact', $day, '$month', $duration, $campaign, $pdays, $previous, '$poutcome', '$y')";
                $query = mysqli_query($conn, "insert into bank_tbl (age, job, marital, education, mydefault, balance, housing, loan, contact, day, month, duration, campaign, pdays, previous, poutcome, y) values $data_values");
                //echo $data_values ."<br />";
                //echo "(". $age . $job . $marital . $education . $default . $balance . $housing . $loan . $contact . $day . $month . $duration . $campaign . $pdays . $previous . $poutcome . $y .")" . "<br />";
            }
            
            //插入数据表中
            
            //echo $data_values ."<br />";
            //关闭指针文件
            fclose($handle);
            
            if($query){
                echo '导入成功！';
            }else{
                echo '导入失败！';
            }

        }
        
    }
?>
</body>
</html>