<?php
/**
 * Класс возвращает массив массивов имен файлов содержащих одинаковый контент для указанной директории
 *
 * @file    Comparator.php
 * @author  Mikhail Shapshay
 * @license BSD/GPLv2
 *
 * @copyright
 **/

class Comparator
{
    protected $return_arr;

    /**
     * Comparator constructor.
     * @param $return_arr
     */
    public function __construct()
    {
        $this->return_arr = array();
    }

    /**
     * Метод возвращает массив массивов имен файлов содержащих одинаковый контент для указанной директории
     *
     * path - строка пути до директории
     * @param $path string
     *
     * @return array
     *
     * @author Mikhail Shapshay
     */
    public static function getFileComparison( $path )
    {
        $comporator = new Comparator();
        if(file_exists($path)){
            if($content = glob( $path.'/*'))
            {
                foreach($content as $item)
                {
                    if(!is_dir($item))
                    {
                        $comporator->return_arr[] = array(
                            "md5" => md5_file($item),
                            "file" => basename($item),
                            "size" => filesize($item),
                        );
                    }
                }

                $comporator->return_arr = $comporator->uniqueValuesFromArray($comporator->return_arr, 'md5');
            }
        }
        return $comporator->return_arr;
    }

    /**
     * Метод уникальный ассоциативный массив по ключу
     *
     * @param $array array
     * @param $key string
     *
     * @return array
     *
     * @author Mikhail Shapshay
     */
    protected function uniqueValuesFromArray($array, $key) {
        $tmp = array_count_values(array_column($array, $key));
        $tmp = array_filter($tmp, function($i){ return $i > 1; });
        $result = [];
        foreach ($array as $item) {
            !array_key_exists($item[$key], $tmp) ?: $result[] = $item;
        }
        return $result;
    }
}