$( ".tech-button" ).click(function() {
	var tech = $(this).val();
	showlist(tech);
});
	
$( ".delnorm" ).click(function() {
	var norm = $(this).val();
	deletenorm(norm);
	setTimeout(function () {
		showlist(tech3);
	}, 200);
});

$("#techsel").change(function(){
	var tech4 = $('#techsel option:selected').val();
	$.post("/ajax.php", {info: "works", technorm: tech4},controlUpdate);
	$("#works").empty();
	function controlUpdate(data) {
		$('#works').html(data);
	}
});


$( "#addpok" ).click(function() {
	var tech1 = $('#tech1').val();
	var motchas = $('#motchas').val();
	var toplivo = $('#toplivo').val();
	if( motchas == '') {
            $('#motchas').addClass('border border-danger');
	}  else if ( toplivo == '') {
            $('#toplivo').addClass('border border-danger');
	} else {
		$('#addpok').addClass('d-none');
		$.post("/ajax.php", {info: "addnorm", technorm: tech1, motchas: motchas, toplivo: toplivo});
		setTimeout(function () { showlist(tech1); }, 200);
	}


	

});

// функция показать нормативы для техники
function showlist(tech) {
	$("#tech").empty();
	$.post("/ajax.php", {info: "shownorm", technorm: tech},controlUpdate);
	function controlUpdate(data) {
		$('#tech').html(data);
	}
}
	
function deletenorm(norm) {
	$.post("/ajax.php", {info: "deletenorm", technorm: norm});
}
	
	
	
  