<?php

/**
 * Получение данных со страницы сайта
 * 
 * @param string $type Тип данных
 * @param string $page Страница в виде строки
 * @return array $matches Результат
 */
function getData($type, $page) {   
    // Список регулярных выражений
    $patterns = array("date" => "/[0-9]{2}\.[0-9]{2}\.[0-9]{4}/imu",
                      "time" => "/>([0-9]{2}:[0-9]{2})<b/m",
                      "flight" => "/>([A-ZА-Я0-9]{2,3}\s[0-9]{2,4})<\/a>/mu",
                      "airline" => "/name\">([а-яё\s—-]+)</imu",
                      "logo" => "/src=\"(\/upload\/resize_cache\/iblock[a-z0-9\-\.\/_]+)\"/im",
                      "direction" => "/td>([а-яёa-z\(\)\s-\.\n\t ]{3,50})<\/td/imu",
                      "status" => "/td>(Вылетел.*|Прилетел.*|Задержан.*|Посадка.*|-|Идёт посадка.*"
                                    . "|Идет регистрация.*|Регистрация.*|Не вылетел.*|Отменен.*|Новый.*|Ямбург.*)<\/td/imu",
    );
    
    // Проверка на соответствие значения шаблону
    preg_match_all($patterns[$type], $page, $matches);

    // Определение индекса для получения результата из matches
    if ($type == "date") {
        $index = 0;
    } else {
        $index = 1;
    }

    // Удаление из списка направлений слова "Отменен"
    if ($type == "direction") {
        $key = array_search("Отменен", $matches[$index]);
        if ($key) {
            array_splice( $matches[$index], $key, 1);
        }
    }

    // Определение типа рейса: Вылет или Прилет
    if ($type == "status") {
        // Шаблон для определения типа рейса "Вылет"
        $pattern_sortie = "/Вылетел\sв\s[0-9:]{5}$|Посадка.*|Идёт посадка.*|Идет регистрация.*|Регистрация.*|Задержан.*/mu";
        
        foreach ($matches[$index] as $key=>$value) {
            if (preg_match_all($pattern_sortie, $value)) {
                // Вылет
                $matches[$index][$key] = array("text" => $value, "type" => "sortie");
            } else {
                // Прилет
                $matches[$index][$key] = array("text" => $value, "type" => "arrival");
            }
        }
    }

    // Создание файлов для сохранения логотипов авиакомпаний
    if ($type == "logo") {
        foreach ($matches[$index] as $key=>$value) {
            $pattern_type = "/\.[a-z]{3}/";
            preg_match($pattern_type, $value, $match);

            $path = './logos/' . $key . $match[0];
            $url = 'http://www.vnukovo.ru' . $value;

            fopen($path, "w");                            
            copy($url, $path);
            chmod($path, 0777);
            $matches[$index][$key] = $path;
        }
    }

    return $matches[$index];
}

/**
 * Удаление файлов из папки
 * 
 * @param string $dir Название папки
 */
function cleanDir($dir) {
    $files = glob($dir."/*");
    $c = count($files);
    if (count($files) > 0) {
        foreach ($files as $file) {      
            if (file_exists($file)) {
            unlink($file);
            }   
        }
    }
}

/**
 * Запуск
 * 
 * @param string $url Ссылка - источник данных 
 * @return array $data Готовые данные
 */
function run($url) {
    
    $folder = "logos";
    if (file_exists($folder)) {
        cleanDir($folder);
    } else {
        mkdir($folder);
        hmod($folder, 0777);
    }
    
    // Получим страницу в виде строки
    $page = file_get_contents($url);
    
    // Если данных нет, то заканчиваем скрипт
    if (!$page) {
        return;
    }
    
    // Типы данных
    $types = array("date", "time", "flight", "airline", "logo", "direction", "status");
    // Типы воздушных суднов
    $aircrafts = array("Боинг 737-800 (в)", "Боинг 737-700", "Боинг 737-800", "Боинг 737-500",
                       "АТР 72", "Аэробус А322", "Боинг 777-200ЕР", "Бомбардье ЦЛ-60", 
                       "Боинг 747-400", "ЦРЙ 200");
    // Шеринг коды
    $sharing_codes = array("Краснодар" => "UUS", "Симферополь (Интернэйшнл)" => "SIP",
                           "Самара (Курумоч)" => "KUF", "Минеральные воды" => "MRV",
                           "Ориенбург" => "KEN", "Новосибирск" => "OVB",
                           "Челябинск" => "CEK", "Ставрополь" => "STW");
    
    foreach ($types as $type) {
        // Получим данные
        $tmp['data'] = getData($type, $page);  
        
        // Добавим логотип для авиакомпании "ДОНАВИЯ" 
        if ($type == "logo") {
            foreach ($tmp['data'] as $index=>$value) {
                if ($data[$index]["airline"] == "ДОНАВИА") {
                    array_splice( $tmp['data'], $index, 0, array("extra_logos/donavia.gif"));
                }
            }
        }
        
        // Обработка полученных данных
        foreach ($tmp['data'] as $index=>$value) {
            $data[$index][$type] = $value;
        }
    }
    
    // Добавление типа воздушного судна и шеринг кода
    foreach ($data as $index=>$values) {
        $rand = rand(0, 9);
        
        $data[$index]['aircraft'] = $aircrafts[$rand];
        
        if (($values["airline"] == "Оренбургские авиалинии") and (in_array($values['direction'], array_keys($sharing_codes)))) {
            $data[$index]["sharing_code"] = "VKO-" . $sharing_codes[$values['direction']];   
        } else {
             $data[$index]["sharing_code"] = "-";
        }
    }
    
    return $data;
    
}

// Ссылка на источник данных
$url = 'http://www.vnukovo.ru/flights/online-timetable/#tab-sortie';

$data = run($url);

$titles = array("type" => "Тип" ,"date" => "Дата", "time" => "Время", "flight" => "Рейс", 
               "airline" => "Авиакомпания", "logo" => "Логотип", "direction" => "Направление", 
               "aircraft" => "Воздушное судно" ,"status" => "Статус", "sharing_code" => "Примечание");

/*
 * Проверка
print "<PRE>";
print_r($data);
print "</PRE>";
*/

// Подключим шаблон сайта
require_once "layout/content.php";

?>



