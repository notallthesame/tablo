
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Онлайн табло</title>
            <!-- JQuery -->
            <script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>   
            
             <!-- JS-скрипты -->           
            <script src="js/custom.js"></script>
            
            <!-- Стили -->
            <link href="css/style.css" rel="stylesheet">
    </head>
    
    <body>
        
        <header> 
            <div align="center">
                <img src="images/ar.png" alt="альтернативный текст" width="90%"> 
            </div>
        </header>
        
        <section>
            
            <!-- Выбор типа рейса -->
            <div id="select_type">
                <input type="checkbox" id="sortie" value="sortie" checked><label>Вылет</label>
                <input type="checkbox" id="arrival" value="arrival" checked><label>Прилет</label>
            </div>
            
            <!-- Таблица для отобрвжения фиксированной шапки таблицы -->
            <table id="fix_table">
                <thead id="fix_head">
                    <tr>
                        <th style="width: 50px;">Тип</th>
                        <th style="width: 125px;">Дата</th>
                        <th style="width: 50px;">Время</th>
                        <th style="width: 110px;">Рейс</th>
                        <th style="width: 265px;">Авиакомпания</th>
                        <th style="width: 200px;">Логотип</th>
                        <th style="width: 300px;">Направление</th>
                        <th style="width: 210px;">Воздушное судно</th>
                        <th style="width: 440px;">Статус</th>
                        <th style="width: 50px;">Примечание</th>
                    </tr>
                </thead>
            </table>
            
            <!-- Основная таблица с данными -->            
            <table id="table">
                <thead id="table_head">
                    <tr>
                        <?php foreach ($titles as $index=>$value): ?>                          
                            <th class="col_<?php echo $index;?>"><?php echo $value;?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data as $index=>$values): ?>
                        <?php 
                            if (($index + 1) % 2 != 0):
                                $classes = "line odd ";
                            else : 
                                $classes = "line even ";
                            endif; 
                            
                            $classes .= $values['status']['type'];
                            $image = "images/" . $values['status']['type'] . ".png";
                        ?>
                    
                        <tr id="<?php echo $index;?>" class="<?php echo $classes;?>">
                            <td class="col_type">
                                <div id="type_<?php echo $index;?>" align="center">
                                    <img src="<?php echo $image; ?>" alt="-" width="22px">
                                </div>
                            </td>  
                            <td class="col_date" id="date_<?php echo $index;?>"><?php echo $values['date']; ?></td>
                            <td class="col_time" id="time_<?php echo $index;?>"><?php echo $values['time']; ?></td>
                            <td class="col_fligth" id="flight_<?php echo $index;?>"><?php echo $values['flight']; ?></td>
                            <td class="col_airline" id="airline_<?php echo $index;?>"><?php echo $values['airline']; ?></td>
                            <td class="col_logo">
                                <div id="logo_<?php echo $index;?>" align="center">
                                    <img src="<?php echo $values['logo']; ?>" alt="-">
                                </div>
                            </td>
                            <td class="col_direction" id="direction_<?php echo $index;?>"><?php echo $values['direction']; ?></td>
                            <td class="col_aircraft" id="aircraft_<?php echo $index;?>"><?php echo $values['aircraft']; ?></td>
                            <td class="col_status" id="status_<?php echo $index;?>"><?php echo $values['status']['text']; ?></td>
                            <td class="col_sharing_code" id="sharing_code_<?php echo $index;?>"><?php echo $values['sharing_code']; ?></td>                               
                        </tr>
                        
                    <?php endforeach; ?>
                </tbody> 
            </table>
            
            <!-- Всплывающее окно "Информация о рейсе" -->
            <div id="window">
                <div id="window_head">                   
                    <div id="close">
                        <img src="images/cancel.png" alt="альтернативный текст" width="13px">
                    </div>  
                    Информация о рейсе
                </div>
                <table id="window_table">
                    <tbody>                      
                        <?php foreach ($titles as $index=>$value): ?>
                            <?php 
                                if ($index == "sharing_code"):
                                    $class = "";
                                else : 
                                    $class = "border_bottom";
                                endif; 
                            ?>
                            <tr class="window_line">
                                <td class="<?php echo $class;?>" style=" width: 35%;"><?php echo $value;?></td>
                                <td class="<?php echo $class;?>" id="<?php echo $index;?>"></td>   
                            </tr>
                        <?php endforeach; ?>
                    <tbody>
                </table>				
            </div>
  
        </section> 
        
        <footer>
            &#169; 2015 Мишина И.С.   
            <a href="http://www.vnukovo.ru/flights/online-timetable/" target="_blank">Источник данных - Аэропорт Внуково</a>
        </footer>
        
        
    </body>
</html>