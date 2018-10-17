


/*
$('#formSubscribe').submit(function(e) {
  e.preventDefault(); //принудительно блокируем все действия по отправке формы
  $.ajax({
    url: '/ekb_archiv/',
    data: {earth: $('#earth').serialize(),
		  month: $('#month').serialize(),
		  update_stat: $('#update_stat').serialize()
		  },
    success: function() {
      alert('СОСИ ПИСОС');
    },
   
  });
});


//////////////////
    function AjaxFormRequest(form_id,url) {
                jQuery.ajax({
                    url:     url, //Адрес подгружаемой страницы
                    type:     "POST", //Тип запроса
                    data: jQuery("#"+form_id).serialize(),
					success: function() { //Если все нормально
                   alert('Load was performed.');
                },
                error: function(response) { //Если ошибка
                alert "Ошибка при отправке формы";
                }
             });
	}


/*
$(document).ready(function() {
	$('.submit').submit(function() { // проверка на пустоту заполненных полей. Атрибут html5 — required не подходит (не поддерживается Safari)
		if (document.table_admin.update_stat.value == '') {
			valid = false;
			return valid;
		}
		$.ajax({
			type: "POST",
			url: "/ekb_archiv/",
			data: $(this).serialize()
		});
		return false;
	});
});
*/