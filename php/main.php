<?php
//allow only AJAX
if ($_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest') {
    return;
}
//Email lib
require 'PHPMailer/PHPMailerAutoload.php';
session_start();

if (isset($_POST["email"]) && isset($_POST["email"]) && isset($_POST['phone'])) {

    //Database connection config
    $host = "localhost";
    $username = "root";
    $password = "";
    $dbname = "site1";

    $dbConnection = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);

    //set the PDO error mode to exception
    $dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //POST data + xss protection
    $fio = strip_tags($_POST['fio']);
    $email = strip_tags($_POST["email"]);
    $phone = strip_tags($_POST['phone']);
    $descr = strip_tags($_POST['description']);

    //additional info
    $clientIp = $_SERVER['REMOTE_ADDR'];
    $dt = gmdate('Y-m-d h:m:s \G\M\T', time());

    try {

        $sql = "INSERT INTO mod_feedback (fio, email, phone, descr, dt, ip) VALUES (:fio, :email, :phone, :descr, :dt, :clientIp) ";

        //prepare sql and bind parameters
        $stmt = $dbConnection->prepare($sql);
        $stmt->bindParam(':fio', $fio);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':descr', $descr);
        $stmt->bindParam(':dt', $dt);
        $stmt->bindParam(':clientIp', $clientIp);
        // insert a row
        $stmt->execute();

        //Send email to admin
        $mail = new PHPMailer;
        $mail->setFrom($email, $fio);
        $mail->addAddress('ihor@seotm.com', 'Админ');
        $mail->Subject = 'форма обратной связи';
        $mailBody = 'ФИО: ' . $fio . ' email: ' . $email . ' phone: ' . $phone .  ' descr: ' . $descr . 'дата: ' . $dt . ' client-Ip: ' . $clientIp;
        $mail->msgHTML($mailBody);
        $mail->IsHTML(true);

        if($mail->Send()) {

            echo "<small>Ваше сообщение успешно отправлено.</small>";

        } else {

            echo "<small class=\"warning\">Ошибка, ваше сообщение не отправлено. Проверьте правильность ввода данных.</small>";

        }

    } catch(PDOException $e) {

        echo "<small class=\"warning\">Ошибка: ".$e->getMessage()."<small>";

    }

    $dbConnection = null;

} else {

    echo "<small class=\"warning\">Ошибка, ваше сообщение не отправлено. Проверьте правильность ввода данных.</small>";

}
