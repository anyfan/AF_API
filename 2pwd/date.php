<?php

/**
 * 存储类
 * Class Storage
 * @package 数据的存储
 */
class Storage
{
    protected $file_adress = 'date.txt';

    /**
     * 保存到文件
     * @param string|array $content
     * @return bool
     */
    public function save($content)
    {
        return file_put_contents($this->file_adress, $content) !== false;
    }


    /**
     * 获取文件内容
     * @return mixed|false
     */
    public function get()
    {
        $file = $this->file_adress;
        if (is_readable($file) === false) {
            return false;
        }
        $content = @file_get_contents($file);
        if ($content === false) {
            return false;
        }
        $content = explode(',', $content);
        return $content;
    }

    /**
     * 查找元素
     * @param string $content
     * @return mixed|false
     */
    public function find($content)
    {
        $date = $this->get();
        if ($date === false) {
            return false;
        }
        for ($x = 1; $x < count($date); $x+=2) {
            if ($content===$date[$x]) {
                return $date[$x-1];
            break;
            }else {
                return false;
            }
        }
    }
}
