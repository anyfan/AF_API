<?php
class db
{

    public static function connect_db()
    {
        $servername = "localhost";
        $username = "api";
        $password = "123456";
        $dbname = "api";

        // 创建连接
        $conn = new mysqli($servername, $username, $password, $dbname);

        // 检测连接
        if ($conn->connect_error) {
            die("连接失败: " . $conn->connect_error);
        }

        return $conn;
        // $conn->close();
    }

    function get($cat, $str_len)
    {
        $sql = "SELECT * FROM hitokoto WHERE status = 1 ";
        if ($cat !== 0) {
            $sql .= "AND cat = '$cat' ";
        }
        if ($str_len !== 0) {
            $sql .= "AND str_len <= $str_len ";
        }
        $sql .= "ORDER BY rand() LIMIT 1";
        $conn = self::connect_db();
        $result = mysqli_query($conn, $sql);
        $data = mysqli_fetch_assoc($result);
        return $data;
    }

    function update()
    {
        $conn = self::connect_db();
        $sql = "SELECT id FROM hitokoto";
        $result = mysqli_query($conn, $sql);
        $num = $result->num_rows;
        for ($i = 1; $i <= $num; $i++) {
            $result = mysqli_query($conn, "SELECT hitokoto FROM hitokoto WHERE id=$i");
            $str_len = strlen(mysqli_fetch_array($result)[0]);
            mysqli_query($conn, "UPDATE hitokoto SET str_len=$str_len WHERE id=$i ");
        }
    }

}
