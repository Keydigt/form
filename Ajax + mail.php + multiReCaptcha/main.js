$(document).ready(function () {
	$success = "<p class='success'>Спасибо за заявку<br/> Ожидайте звонка</p>";
	$error = "Поставьте галочку в поле 'Я не робот'";
	// Класс элемента для показа сообщение об ошибке
	$msgs = $(".msgs");
    $("form").submit(function () {
        // Получение ID формы
        var formID = $(this).attr('id');
        // Добавление решётки к formID
        var formNm = $('#' + formID);
		// ajax запрос
        $.ajax({
            type: "POST",
            url: 'mail.php',
            data: formNm.serialize(),
            success: function (data) {
				// проверка status ответа от recaptcha
				if (status == "1") {
					console.log("recaptcha ok");
				}
				// Стираем данные из формы и пишем сообщение об успешной отправке
            	$(formNm).html($success);
				// Через 4сек очищаем все input
				setTimeout(function() {
	                $('input').not(':input[type=submit], :input[type=hidden]').val('');
	            }, 4000);
            },
            error: function (jqXHR, text, error) {
                // Вывод сообщения об ошибке отправки в консоль
				console.log("Ошибка: "+$error);
				// Сообщаем об ошибке посетителю в $msgs
                $(formNm).find($msgs).html($error);
            }
        });
        return false;
    });
});



$(document).ready(function(){

	$('.call_modal').click(function(){
		$('.form_modal').addClass('active');
	});

	$('.form_modal i').click(function(){
		$(this).parents('.form_modal').removeClass('active');
	});

	$('.advantages .seotext__text').readmore({
		speed: 175,
		collapsedHeight: 175,
		moreLink: '<a class="open tt" href="#">Узнать больше ↓</a>',
		lessLink: '<a class="close tt" href="#">Скрыть ↑</a>'
	});
});
