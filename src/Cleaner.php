<?php
/**
 * Класс рекурсивной очистки директорий и файлов в них
 *
 * @file    Cleaner.php
 * @author  Mikhail Shapshay
 * @license BSD/GPLv2
 *
 * @copyright
 **/
class Cleaner
{
    /**
     * Метод рекурсивной очистки директорий и файлов в них
     *
     * path - строка пути до директории
     * @param $path string
     * @author Mikhail Shapshay
     */
    public static function clearDirectory( $path )
    {
        if(file_exists($path)){
            $cleaner = new Cleaner();
            if($content = glob( $path.'/*'))
            {
                foreach ($content as $item)
                {
                    if(is_dir($item))
                    {
                        $cleaner->clearDirectory($item);
                    }
                    else {
                        @chmod($item, 0777);
                        unlink($item);
                    }
                }
            }
            @chmod($item, 0777);
            rmdir($path);
        }
    }
}