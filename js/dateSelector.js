$(function() {
    var startDate;
    var endDate;
    
    var selectCurrentWeek = function() {
        window.setTimeout(function () {
            $('.week-picker').find('.ui-datepicker-current-day a').addClass('ui-state-active')
        }, 1);
    }
    
    $('.week-picker').datepicker( {
        showOtherMonths: true,
        selectOtherMonths: true,
        dayNamesMin: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],  
        showWeek: true,
        weekHeader: "W",
        firstDay: 1,

        onSelect: function(dateText, inst) { 
            var date = $(this).datepicker('getDate');
            startDate = new Date(date.getFullYear(), date.getMonth(), date.getDate() - date.getDay() + 1);
            endDate = new Date(date.getFullYear(), date.getMonth(), date.getDate() - date.getDay() + 7);
            var dateFormat = inst.settings.dateFormat || $.datepicker._defaults.dateFormat;
            $('#startDate').text($.datepicker.formatDate( dateFormat, startDate, inst.settings ));
            $('#endDate').text($.datepicker.formatDate( dateFormat, endDate, inst.settings ));
            

            $.ajax({
                url:"getCalendarAjax.php",
                data : { dateStart: $.datepicker.formatDate( dateFormat, startDate, inst.settings), dateEnd: $.datepicker.formatDate( dateFormat, endDate, inst.settings )},
                success:function(result){
                    $(".weekdaybody").remove();
                    $(".timebody").after(result);
                }
            });
            selectCurrentWeek();
        },
        beforeShowDay: function(date) {
            var cssClass = '';
            if(date >= startDate && date <= endDate)
                cssClass = 'ui-datepicker-current-day';
            return [true, cssClass];
        },
        onChangeMonthYear: function(year, month, inst) {
            selectCurrentWeek();
        }
    });
    
    $('.week-picker .ui-datepicker-calendar tr').on('mousemove', function() { $(this).find('td a').addClass('ui-state-hover'); });
    $('.week-picker .ui-datepicker-calendar tr').on('mouseleave', function() { $(this).find('td a').removeClass('ui-state-hover'); });

    // // evntuelt!
    // $('#picker').datepicker();

    // $('.next-day').on("click", function () {
    //     var date = $('#picker').datepicker('getDate');
    //     date.setTime(date.getTime() + (1000*60*60*24)*7)
    //     $('#picker').datepicker("setDate", date);
    // });

    // $('.prev-day').on("click", function () {
    //     var date = $('#picker').datepicker('getDate');
    //     date.setTime(date.getTime() - (1000*60*60*24)*7)
    //     $('#picker').datepicker("setDate", date);
    // });
});
