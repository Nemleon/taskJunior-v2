<?php

require_once 'db.php';

$date = date("Y-m-d");
$cityName = trim(htmlentities($_POST['city']));
$countryName = trim(htmlentities($_POST['countries']));

//логика поведения скрипта
if (!$countryName == null || !$cityName == null) {

    $data = getDataDB($cityName, $countryName, $date);

    if ($data == null){

        $getApiData = getApiData($cityName, $countryName);

        if (!$getApiData == '2') {

            $data = getDataDB($cityName, $countryName, $date);
            $result = json_encode($data);
            print_r($result);

        } else {

            print_r($getApiData); //Ошибка о ненайденном городе

        }


    } else {

        $result = json_encode($data);
        print_r($result);

    }

} else {

    echo '1'; //незаполненные поля, чтобы не грузить просто так бд

}


function getApiData ($city, $country) {

    $countryName = $country;
    $cityName = $city;
    $apiKey = "731fdb9f46272f54a8b68c894765410b";
    $apiUrl = "http://api.openweathermap.org/data/2.5/forecast?q={$cityName},{$countryName}&units=metric&appid={$apiKey}";

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_URL, $apiUrl);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_VERBOSE, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $result = curl_exec($ch);
    curl_close($ch);

    if ($result == '{"cod":"404","message":"city not found"}') {

        return '2';

    } else {

        $decData = json_decode($result, true);
        setDataDB($decData);

    }
}

function setDataDB ($row) {

    $country = $row['city']['country'];
    $condition = "INSERT INTO {$country} VALUES (:data, :city, :humidity, :temp, :AtmPressure)";

    $db = new setData();

    for ($i=0; isset($row['list'][$i]) == true; $i++) {

        $preparedData = array (

            'data' => $row['list'][$i]['dt_txt'],
            'city' => $row['city']['name'],
            'humidity' => $row['list'][$i]['main']['humidity'],
            'temp' => $row['list'][$i]['main']['temp'],
            'AtmPressure' => $row['list'][$i]['main']['pressure']

        );

        $db->setDataDb($condition, $preparedData);

    }
}

function getDataDB ($city, $country, $date) {

    $condition = "SELECT * FROM {$country} WHERE date LIKE :date AND city = :city";

    $preparedData = array (

        'city' => "{$city}",
        'date' => "{$date}%"

    );

    $db = new getData();
    $result =  $db->getDataDb($condition, $preparedData);
    return $result;

}