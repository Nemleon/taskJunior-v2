$(document).ready(function() {

    $('form').submit(function(event) {

        event.preventDefault();

        $.ajax({

            type: $(this).attr('method'),
            url: $(this).attr('action'),
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,

            success: function createTable(result) {
                console.log(result);
                //проверка на ошибки
                if (result == 1) {
                    alert('Fill the fields');
                } else if (result == 2) {
                    alert('City not found');
                } else {
                    //если все хорошо, формируется таблица с полученными данными
                    let data = JSON.parse(result);
                    let parent = document.querySelector('#weather');
                    let city = document.createElement('div');
                    city.className = 'cityName';
                    let cityName = '<b>' + data[0].City + '</b><br>';
                    $(city).html(cityName);
                    parent.appendChild(city);
                    for (let i = 0; i < data.length; i++) {
                        let mainDiv = document.createElement('div');
                        let out = '';
                        if (i % 2 == 0) {
                            mainDiv.className = 'hoursWeather1';
                        } else {
                            mainDiv.className = 'hoursWeather2';
                        }
                        out += 'Current Date: <b>' + data[i].Date + '</b><br>'
                        out += 'Temperature: <b>' + data[i].temp + '°C</b><br>'
                        out += 'Humidity: <b>' + data[i].Humidity + '%</b><br>'
                        out += 'Pressure: <b>' + data[i].AtmPressure + ' mmHg Art.</b><br>'
                        $(mainDiv).html(out);
                        parent.appendChild(mainDiv);
                    }
                }
            },
        });
    });
});

