<?php
error_reporting (E_ALL);
ini_set("display_errors", 1);

require __DIR__ . '/../vendor/autoload.php';

$result = '';
if(isset($_POST['path'])&&!empty($_POST['path'])&&!isset($_POST['dirs_count'])){
    $dir = $_SERVER['DOCUMENT_ROOT'].'/'.$_POST['path'];
    $cleaner = new Cleaner();
    $cleaner->clearDirectory($dir);
    $result = 'Удаление завершено!';
}
if(isset($_POST['path'])&&!empty($_POST['path'])&&isset($_POST['dirs_count'])&&!empty($_POST['dirs_count'])){
    $dir = $_SERVER['DOCUMENT_ROOT'].'/'.$_POST['path'].'/';
    $generator = new FileGenerator();
    $generator->generateDirectoryAndFiles(
        $dir,
        $_POST['dirs_count'],
        $_POST['files_count'],
        $_POST['max_nesting'],
        $_POST['max_length_name'],
        $_POST['max_file_size'],
        $_POST['probability']
    );
    $result = 'Генерация завершена!';
}
if(isset($_POST['search_path'])&&!empty($_POST['search_path'])){
    $dir = $_SERVER['DOCUMENT_ROOT'].'/'.$_POST['search_path'];
    $comporator = new Comparator();
    $res = $comporator->getFileComparison($dir);
    $result = '<p>Результат сравнения:<p>';
    foreach ($res as $item){
        //$result.= $item['file'].', '.$item['md5'].', '.$item['size'].'<br>';
        $result.= $item['file'].'<br>';
    }
    $result.= '<p>Всего соответствий: '.sizeOf($res);
}
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Random files generator</title>
    <!-- Bootstrap CSS (jsDelivr CDN) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <!-- Bootstrap Bundle JS (jsDelivr CDN) -->
    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</head>
<body>
<div class="container px-4">
    <h1>Random files generator</h1>
    <div class="alert alert-success" role="alert"><strong><?=$result;?></strong></div>
    <hr>
    <h3>Класс рекурсивной очистки директорий и файлов в них</h3>
    <div>
        <form method="post">
            <div>
                <p><label for="path" class="form-label"><strong>Укажите директорию от корня сайта</strong><br>
                        <input type="text" class="form-control" name="path" id="path" placeholder="Укажите директорию от корня сайта"></p>
                <p><button class="btn btn-primary" type="submit">Очистить</button></p>
            </div>
        </form>
    </div>
    <hr>
    <h3>Класс рекурсивной генерации произвольных файлов</h3>
    <div>
        <form method="post">
            <div>
                <p><label for="path" class="form-label"><strong>Укажите директорию от корня сайта</strong><br>
                        <input type="text" class="form-control" name="path" id="path" placeholder="Укажите директорию от корня сайта"></p>
                <p><label for="dirs_count" class="form-label"><strong>Укажите число папок</strong><br>
                        <input type="text" class="form-control" name="dirs_count" id="dirs_count" placeholder="Укажите число папок"></p>
                <p><label for="files_count" class="form-label"><strong>Укажите число файлов</strong><br>
                        <input type="text" class="form-control" name="files_count" id="files_count" placeholder="Укажите число файлов"></p>
                <p><label for="max_nesting" class="form-label"><strong>Укажите максимальную вложенность</strong><br>
                        <input type="text" class="form-control" name="max_nesting" id="max_nesting" placeholder="Укажите максимальную вложенность"></p>
                <p><label for="max_length_name" class="form-label"><strong>Укажите максимальную длину имени файла/директории</strong><br>
                        <input type="text" class="form-control" name="max_length_name" id="max_length_name" placeholder="Укажите максимальную длину имени файла/директории"></p>
                <p><label for="max_file_size" class="form-label"><strong>Укажите максимальный размер файла в байтах</strong><br>
                        <input type="text" class="form-control" name="max_file_size" id="max_file_size" placeholder="Укажите максимальный размер файла в байтах"></p>
                <p><label for="probability" class="form-label"><strong>Укажите вероятность (0-100) идентичности содержимого файлов</strong><br>
                        <input type="text" class="form-control" name="probability" id="probability" placeholder="Укажите вероятность (0-100) идентичности содержимого файлов"></p>
                <p><button class="btn btn-primary" type="submit">Генерация</button></p>
            </div>
        </form>
    </div>
    <hr>
    <h3>Класс возвращает массив массивов имен файлов содержащих одинаковый контент для указанной директории</h3>
    <div>
        <form method="post">
            <div>
                <p><label for="search_path" class="form-label"><strong>Укажите директорию от корня сайта</strong><br>
                        <input type="text" class="form-control" name="search_path" id="search_path" placeholder="Укажите директорию от корня сайта"></p>
                <p><button class="btn btn-primary" type="submit">Найти соответствия</button></p>
            </div>
        </form>
    </div>
</div>
</body>
</html>