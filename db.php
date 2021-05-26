<?php
require_once('config.php');
# 使用之前需要引入该模块 require 或 require_one
class db
{

    # 连接数据库对象
    public $connection;


    /**
     * 功能：获取mysql连接对象
     * @return false|mysqli 返回数据库连接对象
     */
    function __construct()
    {
        global $db_config;
        $this->connection = mysqli_connect($db_config['host'], $db_config['user'], $db_config['password'], $db_config['database']) or die("连接数据库失败");
        # 设置字符集
        mysqli_query($this->connection, "set names utf8");

        # 返回连接数据库的对象
        return $this->connection;
    }


    // /**
    //  * 功能：获取mysql连接对象
    //  * @param $host 主机名称
    //  * @param $user 用户名
    //  * @param $password 密码
    //  * @param $database 需要连接的数据库
    //  * @return false|mysqli 返回数据库连接对象
    //  */
    // function getConnection($host, $user, $password, $database)
    // {
    //     $this->connection = mysqli_connect($host, $user, $password, $database) or die("连接数据库失败");
    //     # 设置字符集
    //     mysqli_query($this->connection, "set names utf8");

    //     # 返回连接数据库的对象
    //     return $this->connection;
    // }



    /**
     * 功能：查询某张表中的全部数据
     * @param $tableName 表名
     * @return bool|mysqli_result 返回查询的结果集
     */
    function queryAll($tableName)
    {
        $sql = "select * from " . $tableName;
        # 执行查询 并且返回查询结果
        return  mysqli_query($this->connection, $sql);
    }

    /**功能：向指定的表中插入数据，参数为一个关联数组
     * @param $tableName 表名
     * @param $args 需要添加的字段(关联数组)
     * @return bool|mysqli_result 执行成功返回true 执行失败返回false
     */
    function insert($tableName, $args)
    {
        # 获取关联数组的长度
        $length = count($args);
        # 辅助指针 移动key和value的位置
        $index = 0;
        # sql 前半部分
        $sql1 = "insert into " . $tableName . "(";
        # sql 后半部分
        $sql2 = "values(";
        foreach ($args as $key => $value) {
            # 如果为第一次不加 ,其余的都加上 拼接key部分
            if ($index == 0) {
                $sql1 = $sql1 . $key;
            } else {
                $sql1 = $sql1 . "," . $key;
            }
            if ($index == $length - 1) {
                $sql1 .= ")";
            }

            # 如果为第一次不加 ,其余的都加上 拼接value部分
            if ($index == 0) {
                $sql2 .= "'" . $value . "'";
            } else {
                $sql2 .= "," . "'" . $value . "'";
            }
            if ($index == $length - 1) {
                $sql2 = $sql2 . ")";
            }
            # 让指针往后移动
            $index++;
        }
        # 将key和value进行拼接组成一条完整的sql
        $sql = $sql1 . $sql2;
        # 执行sql 并且返回一个布尔值
        return mysqli_query($this->connection, $sql);
    }

    /**
     * 功能：通过id删除某张表中的记录
     * @param $tableName 表名
     * @param $idName id名称
     * @param $id key
     * @return bool|mysqli_result 删除成功返回true 删除失败返回false
     */
    function deleteById($tableName, $idName, $id)
    {
        $sql = "delete from $tableName where $idName=$id";
        # 执行sql 并返回执行结果
        return mysqli_query($this->connection, $sql);
    }

    /**
     * 功能：传入一条sql执行，返回结果集 (推荐使用该方法)
     * @param $sql sql语句
     * @return bool|mysqli_result 返回执行后的结果集
     */
    function executeSql($sql)
    {
        return mysqli_query($this->connection, $sql);
    }

    /**
     * 功能：修改表中的数据
     * @param $tableName 表名
     * @param $field 关联数组(字段和字段值)
     * @param $condition 修改条件
     */
    function update($tableName, $field, $condition)
    {

        $sql = "update " . $tableName . " set ";
        # ；用于拼接字段和value
        $updateField = "";
        # 定义一个辅助指针，用于循环遍历关联数组
        $index = 0;
        foreach ($field as $key => $value) {
            if ($index == 0) {
                $updateField .= $key . "=" . "'" . $value . "'";
            } else {
                $updateField .= "," . $key . "=" . "'" . $value . "'";
            }
            $index++;
        }
        # 组合完成后的完整sql
        $sql .= $updateField . " where " . $condition;

        # 执行sql并且返回结果集
        return mysqli_query($this->connection, $sql);
    }

    /**
     * 功能：按照条件查询某些字段的值
     * @param $tableName 需要查询的表名
     * @param $arrArgs 一个索引数组(字段名)
     * @param $condition 查询条件
     * @return bool|mysqli_result
     */
    function selectByCondition($tableName, $arrArgs, $condition)
    {
        # 最终完整的sql
        $sql = "";
        # 需要查询的字段
        $selectFiled = "";
        # 查询条件
        $length = count($arrArgs);
        for ($i = 0; $i < $length; $i++) {
            if ($i == 0) {
                $selectFiled .= "select " . $arrArgs[$i];
            } else {
                $selectFiled .= "," . $arrArgs[$i];
            }
        }
        # 组合为一条完整sql
        $sql .= $selectFiled . " from " . $tableName . " where " . $condition;
        # 执行查询 并且返回查询结果集
        return mysqli_query($this->connection, $sql);
    }

    /**
     * 功能：通过id查询一条数据 注意 ：数据库字段名必须为 id
     * @param $tableName 需要查询的表名
     * @param $id 按照此di进行查询
     * @return bool|mysqli_result 返回查询结果集
     */
    function selectById($tableName, $id)
    {
        # 拼接传递过来的sql
        $sql = "select * from " . $tableName . " where id=" . $id;
        # 返回查询结果集
        return mysqli_query($this->connection, $sql);
    }

    /**
     * 该对象销毁时关闭msql连接
     */
    function __destruct()
    {
        mysqli_close($this->connection);
    }
}
