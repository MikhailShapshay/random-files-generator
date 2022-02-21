<?php
/**
 * Класс рекурсивной генерации произвольных файлов
 *
 * @file    Generator.php
 * @author  Mikhail Shapshay
 * @license BSD/GPLv2
 *
 * @copyright
 **/

class FileGenerator
{
    protected $dir_rights = '0777';
    protected $arr_dirs = array();
    protected $arr_files = array();

    /**
     * Метод рекурсивной генерации произвольных файлов
     *
     * path - строка пути до директории
     * dirs_count - число папок
     * files_count - число файлов
     * max_nesting - максимальная вложенность
     * max_length_name - максимальная длина имени файла/директории
     * max_file_size - максимальный размер файла
     * probability - вероятность (0-100) идентичности содержимого файлов
     *
     * @param $path string
     * @param $dirs_count int
     * @param $files_count int
     * @param $max_nesting int
     * @param $max_length_name int
     * @param $max_file_size int
     * @param $probability int
     *
     * @author Mikhail Shapshay
     */
    public static function generateDirectoryAndFiles(
        $path,
        $dirs_count,
        $files_count,
        $max_nesting,
        $max_length_name,
        $max_file_size,
        $probability
    )
    {
        $generator = new FileGenerator();
        $generator->createNestedDerictorys($path, $dirs_count, $max_nesting, $max_length_name);
        $probability = round(($files_count/100)*$probability,0);
        $tmp_size = mt_rand(1000, $max_file_size);
        for($i=1; $i<=$files_count; $i++){
            $temp_arr = $generator->arr_files;
            $temp_arr[] = '';
            $temp_arr = $generator->shuffleArray($temp_arr);
            $file_name = '';
            while (in_array($file_name, $temp_arr)) {
                $dir_path = $generator->arr_dirs[mt_rand(0, sizeof($generator->arr_dirs)-1)];
                $length_name = mt_rand(1, $max_length_name);
                $file_name = $path.$dir_path.'/'.$generator->randomString($length_name).'.txt';
            }
            if($probability>0){
                $generator->createArbitraryFile($file_name, $max_file_size, $tmp_size);
                $probability--;
            }
            else{
                $tmp_size = $generator->createArbitraryFile($file_name, $max_file_size);
            }
            $generator->arr_files[] = $file_name;
        }
    }

    /**
     * Метод генерации файла заданного размера
     *
     * file_name - имя файла
     * size - максимальный размер в байтах
     * file_size - строгий размер в байтах
     *
     * 32bits 4 294 967 296 bytes MAX Size
     *
     * @param $file_name string
     * @param $size int
     * @param $file_size int
     *
     * @return int
     *
     * @author Mikhail Shapshay
     */
    protected function createArbitraryFile($file_name,$size, $file_size = 0)
    {
        if($file_size==0)
            $file_size = mt_rand(1000, $size);
        $file = fopen($file_name, 'w');
        if($file_size >= 1000000000)  {
            $part = ($file_size / 1000000000);
            if (is_float($part))  {
                $part = round($part,0);
                fseek($file, ( $file_size - ($part * 1000000000) -1 ), SEEK_END);
                fwrite($file, "\0");
            }
            while(--$part > -1) {
                fseek($file, 999999999, SEEK_END);
                fwrite($file, "\0");
            }
        }
        else {
            fseek($file, $file_size - 1, SEEK_END);
            fwrite($file, "\0");
        }
        fclose($file);

        return $file_size;
    }

    /**
     * Метод генерации вложенных папок
     *
     * path - строка пути до директории
     * dirs_count - число папок
     * max_nesting - максимальная вложенность
     * max_length_name - максимальная длина имени директории
     *
     * @param $path string
     * @param $dirs_count int
     * @param $max_nesting int
     * @param $max_length_name int
     *
     * @return bool
     *
     * @author Mikhail Shapshay
     */
    protected function createNestedDerictorys(
        $path,
        $dirs_count,
        $max_nesting,
        $max_length_name
    )
    {
        for($i=1; $i<=$dirs_count; $i++){
            $temp_arr = $this->arr_dirs;
            $temp_arr[] = '';
            $temp_arr = $this->shuffleArray($temp_arr);
            $dir_name = '';
            while (in_array($dir_name, $temp_arr)){
                $length_name = mt_rand(1, $max_length_name);
                $dir_name = $this->randomString($length_name);
                $tmp_dir_path = $temp_arr[mt_rand(0, sizeof($temp_arr)-1)];
                $nesting = preg_split('/\//', $tmp_dir_path, -1, PREG_SPLIT_NO_EMPTY);
                if(sizeOf($nesting)>=$max_nesting)
                    continue;
                if($tmp_dir_path!=''){
                    $dir_name = $tmp_dir_path."/".$dir_name;
                }
            }
            mkdir($path.$dir_name, $this->dir_rights, true);
            $this->arr_dirs[] = $dir_name;

        }
        return true;
    }

    /**
     * Метод перемешивания массива
     *
     * arr - входящий массив
     *
     * @param $arr array
     *
     * @return array
     *
     * @author Mikhail Shapshay
     */
    protected function shuffleArray($arr) {
        for($i=0; $i < sizeOf($arr); $i++){
            $index = (int) ((rand(0, 9)/10) * (sizeOf($arr) - $i)) + $i;
            $temp = $arr[$i];
            $arr[$i] = $arr[$index];
            $arr[$index] = $temp;
        }

        return $arr;
    }

    /**
     * Метод для случайной строки заданной длинны
     *
     * length - длинна строки
     *
     * @param $length int
     *
     * @return string
     *
     * @author Mikhail Shapshay
     */
    protected function randomString($length) {
        $str = substr(str_shuffle('_0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, $length);

        return $str;
    }
}