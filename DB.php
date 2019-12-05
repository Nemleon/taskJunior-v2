<?php
Class db {

    private $dsn = 'mysql:host=127.0.0.1;port=3306;dbname=weather;charset=utf8';
    private $user = 'root';
    private $pass = '';
    private $options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC];

    protected function dbConnect() {

        try {

            $DBH = new PDO ($this->dsn, $this->user, $this->pass, $this->options);
            set_time_limit(25);

        } catch (PDOException $e) {

            echo $e;

        }

        return $DBH;

    }
}
//Выгрузка данныз из бд
Class getData extends db {

    public function getDataDb ($condition, $preparedData) {
        try {

            $DBH = parent::dbConnect()->prepare($condition);
            $DBH->execute($preparedData);
            $result = $DBH->fetchAll();
            $DBH = null;
            return $result;

        } catch (PDOException $e) {

            $DBH = null;
            return null;

        }

    }
}
//Загрузка данных в бд
Class setData extends Db {

    public function setDataDb ($condition, $preparedData) {
        try {

            $DBH = parent::dbConnect()->prepare($condition);
            $DBH->execute($preparedData);
            $DBH = null;

        } catch (PDOException $e) {

            $DBH = null;
            return null;

        }
    }
}