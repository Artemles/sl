<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

    <form style="display: flex;justify-content: center;align-items: center;min-height: 100vh;"
       method="post">
        <input style="margin-right: 10px;" type="text" name="url" required>
        <button>Сократить</button>
    </form>

<?//создаем хэш
    function createHash (){
        $randomHash = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $hash = substr(str_shuffle($randomHash), 0, 9);
        return $hash;
    }


// функция создания БД
    function createBD (){
        $link = mysqli_connect("localhost", "root", "");
        $sql = "CREATE DATABASE demo";// Создание базы данных с именем demo


        if(mysqli_query($link, $sql)){
            echo "<br>База данных успешно создана";
        } else{
            echo "<br>Ошибка создания базы данных $sql. " . mysqli_error($link);
        }
    }

// функция создания таблицы
    function creatTableBD (){
        $link = mysqli_connect("localhost", "root", "", "demo");
        $sql = "CREATE TABLE links(
            id INT NOT NULL PRIMARY KEY AUTO_INCREMENT UNIQUE,
            idhash VARCHAR(30) NOT NULL,
            link VARCHAR(255) NOT NULL 
        )";


        if(mysqli_query($link, $sql)){
            echo "<br>Таблица успешно создана.";
        } else{
            echo "<br>ERROR: Не удалось выполнить $sql. " . mysqli_error($link);
        }
    }

// функция вставки данных
    function insertValue($url) {
        global $hash;
        $link = mysqli_connect("localhost", "root", "", "demo");
        $sql = "INSERT INTO links ( idhash, link) VALUES ('$hash', '$url')";


        if(mysqli_query($link, $sql)){
            echo "<br>Записи успешно вставлены. <br>";
        } else{
            echo "<br>ERROR: Не удалось выполнить $sql. " . mysqli_error($link);
        }
    }

// функция получения данных
    function getValue($urlNow) {
        $link = mysqli_connect("localhost", "root", "", "demo");
        $GetValue = "SELECT * FROM links WHERE idhash = '$urlNow'";

        if($result = mysqli_query($link, $GetValue)){
            $rowrt = mysqli_fetch_array($result);
            
            if($rowrt !=""){
                foreach($result as $row){
                
                    ?><pre><?
                    print_r($row);
                    ?></pre><?
    
                    $userid = $row["id"];
                    $idhash = $row["idhash"];
                    $link = $row["link"];
                    echo "<br>".$userid." ".$idhash." ".$link."<br>";
                    return $link;
                }
            }else{
                return FALSE;
                echo "Ссылки не существует. :,(";
                echo "Произошла ошибка";
            }
            
        }else{
            return FALSE;
            echo "Ссылки не существует. :,(";
            echo "Произошла ошибка";
        }
    }

    
    // Проверка есть ли база данных 
    $link = mysqli_connect("localhost", "root", "", "demo");
    //получаем ссылку
    $url = $_POST["url"];
    // Генерация хеша
    $hash = createHash();

    

    if($link === false){
        createBD();
        creatTableBD();
    }


    if(!empty($url)){
        global $hash;

        insertValue($url);
        echo  "Введенная ссылка: ".$url."<br>";
        echo  "Короткая ссылка: http://sl/".$hash;
    }
    

    //Получение текущей ссылки страницы
    $urlNow = trim($_SERVER["REQUEST_URI"], '/');    

    if(($urlNow !="index.php")&&($urlNow != "")){
        global $urlNow;

        if($userage =getValue($urlNow)){
            echo $userage."<br>Нынешняя ссылка: ".$urlNow;
            header("Location:$userage");
        }else{
           echo "Ссылка не доступна.";
        }
        
    }
    ?>
</body>
</html>