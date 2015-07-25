
$(function() { 
    // Выбор типа рейса
    $('#sortie, #arrival').click(function() {
        var id = $(this).attr('id');
        if ($('#' + id).is(":checked"))  {
            $('.' + id).show();           
        } else {
            $('.' + id).hide();
        }
    });

    // Всплывающее окно "Информация о рейсе"
    $('.line').click(function(){        
        var id = $(this).attr("id");
        
        $('#type').empty();
        $('#type').append($('#type_' + id).html());
        $('#date').text($('#date_' + id).html());
        $('#time').text($('#time_' + id).html());
        $('#flight').text($('#flight_' + id).html());
        $('#airline').text($('#airline_' + id).html());
        $('#logo').empty();
        $('#logo').append($('#logo_' + id).html());
        $('#direction').text($('#direction_' + id).html());
        $('#aircraft').text($('#aircraft_' + id).html());
        $('#status').text($('#status_' + id).html());
        $('#sharing_code').text($('#sharing_code_' + id).html());

        $('#window').offset({top: 150 + window.pageYOffset});
        $("#window").css("display", "block");
    });
    
    // Скрыть столбцы при изменении размера окна браузера
    $(window).resize(function(){
        // Размер окна > 1000px
        if ($(window).width() >= 1000) {
            $('.col_airline').show();
            $('.col_sharing_code').show();
            $('.col_aircraft').show();
        }
        // Размер окна < 1000px и > 800px
        if ($(window).width() < 1000 && $(window).width() > 800 ) {
            $('.col_airline').hide();
            $('.col_sharing_code').show();
            $('.col_aircraft').show();
        }
        // Размер окна <= 800px и > 600px
        if ($(window).width() <= 800 && $(window).width() > 600 ) {
            $('.col_airline').hide();
            $('.col_sharing_code').hide();
            $('.col_aircraft').show();
        }
        // Размер окна <= 600px и > 400px
        if ($(window).width() <= 600) {
            $('.col_airline').hide();
            $('.col_sharing_code').hide();
            $('.col_aircraft').hide();
        }
    });
    
    // Выделение всего столбца
    $("td").hover(
        // При наведении на ячейку
        function() {
            var classname = $(this).attr('class');
            if (classname != 'border_bottom') {
                var elements = document.getElementsByClassName(classname);
                for (var i=0; i<elements.length; i++) {
                    elements[i].classList.add("col_hover");
                }
            }
        },
        // При отведении курсора от ячейки      
        function() {
            var classname = $(this).attr('class').split(' ')[0];
            var elements = document.getElementsByClassName(classname);
            for (var i=0; i<elements.length; i++) {
                elements[i].classList.remove("col_hover");
            }
        }
    );
    
    // Закрытие всплывающего окна
    $('#close').click(function() {
        $("#window").css("display", "none");
    });
    
    // Фиксированная шапка таблицы при прокрутке страницы
    var fix_header = document.getElementById('fix_table');
    var fix_header_bottom = fix_header.getBoundingClientRect().bottom + window.pageYOffset;
    $('#fix_table').hide();
    // Прокрутка страницы
    window.onscroll = function() {
        var w = $('#table_head').width();
        $('#window').offset({top: 150 + window.pageYOffset});
        if (fix_header.classList.contains('fixed') && window.pageYOffset < fix_header_bottom) {
            fix_header.classList.remove('fixed');
            $('#fix_table').hide();
        } else if (window.pageYOffset > fix_header_bottom) {
            $('#fix_table').width(w);
            $('#fix_table').show();           
            fix_header.classList.add('fixed');     
        }
    };
});


 
 
