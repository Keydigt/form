<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {

	// Получаем пост от recaptcha
	$recaptcha = $_POST['g-recaptcha-response'];

	// Сразу проверяем, что он не пустой
	if(!empty($recaptcha)) {

		// Получаем HTTP от recaptcha
		$recaptcha = $_REQUEST['g-recaptcha-response'];

		// Сюда пишем СЕКРЕТНЫЙ КЛЮЧ, который нам присвоил гугл
		$secret = 'your_secret_key';

		// Формируем utl адрес для запроса на сервер гугла
		$url = "https://www.google.com/recaptcha/api/siteverify?secret=".$secret ."&response=".$recaptcha."&remoteip=".$_SERVER['REMOTE_ADDR'];

		// Инициализация и настройка запроса
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_TIMEOUT, 10);
		curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16");

		// Выполняем запрос и получается ответ от сервера гугл
		$curlData = curl_exec($curl);

		curl_close($curl);

		// Ответ приходит в виде json строки, декодируем ее
		$curlData = json_decode($curlData, true);

		// Смотрим на результат
		if($curlData['success']) {
					$tel = $teldesc = $subject = '';

					// Получаем пост от input в форме
					if (isset($_POST['tel']) && !empty($_POST['tel'])) {
						$tel = strip_tags($_POST['tel'])."<br>";
						$teldesc = "<b>Телефон:</b>";
					}

					// Получаем пост от input темы в форме
					if (isset($_POST['subject']) && !empty($_POST['subject'])) {
						$subject = strip_tags($_POST['subject'])."<br>";
					} else {
						$subject = "Заявка с формы на сайте";
					}


					// Получатель и отправитель
					$to 	  = "admin@keydiweb.ru";
					$sendfrom = "admin@keydiweb.ru";

					$headers  = "From: " . strip_tags($sendfrom) . "\r\n";
					$headers .= "Reply-To: ". strip_tags($sendfrom) . "\r\n";
					$headers .= "MIME-Version: 1.0\r\n";
					$headers .= "Content-Type: text/html;charset=utf-8 \r\n";
					// Скрытая копия, расскоментировать если понадобится
					// $headers .= "Bcc: admin@keydiweb.ru \r\n";

					// ip отправителя и содержимое письма
					$ip       = "<br>ip:".$_SERVER['REMOTE_ADDR'];
					$message  = "$tel $ip";

					// Отправка письма
					$send     = mail ($to, $subject, $message, $headers);
		} else {
			// Капча не пройдена, отправляем код ошибки
			http_response_code(500);
		}
	}
	else {
		//Капча не введена, отправляем код ошибки
		http_response_code(500);
	}
}
