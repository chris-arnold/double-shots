$(function () {
$("#onevone").click(function () {

	$.ajax({
		type: "POST",
		url: "stats_functions.php",
		data: { stats: "1v1" }
		}).done(function( msg ) {
			$("#statstable").empty();
		 		document.getElementById("statstable").innerHTML=msg;
		 		sorttable.makeSortable(document.getElementById('statstable'));
				sorttable.innerSortFunction.apply(document.getElementById("tot1v1win"), []);
				sorttable.innerSortFunction.apply(document.getElementById("tot1v1win"), []);
    		});
		});
});

$(function () {
$("#addonevone").click(function () {

	$.ajax({
		type: "POST",
		url: "stats_functions.php",
		data: { newmatch: "1v1" }
		}).done(function( msg ) {
			//alert("HERE!");
		 		document.getElementById("add_a_game").innerHTML=msg;
		 		//sorttable.makeSortable(document.getElementById('statstable'));
    		});
		});
});

$(function () {
$("#addtwovone").click(function () {

	$.ajax({
		type: "POST",
		url: "stats_functions.php",
		data: { newmatch: "2v1" }
		}).done(function( msg ) {
			//alert("HERE!");
		 		document.getElementById("add_a_game").innerHTML=msg;
		 		//sorttable.makeSortable(document.getElementById('statstable'));
    		});
		});
});

$(function () {
$("#addtwovtwo").click(function () {

	$.ajax({
		type: "POST",
		url: "stats_functions.php",
		data: { newmatch: "2v2" }
		}).done(function( msg ) {
			//alert("HERE!");
		 		document.getElementById("add_a_game").innerHTML=msg;
		 		//sorttable.makeSortable(document.getElementById('statstable'));
    		});
		});
});


$(function () {
$("#clear").click(function () {
	document.getElementById("add_a_game").innerHTML="";
	});
});

$(function () {
	$('#addagame').on('change', '#player1', function () { 
		
	document.getElementById("player2").innerHTML="";
	var fstr=document.getElementById("player1").value;
	$.ajax({
		type: "POST",
		url: "stats_functions.php",
		data: { player: 'p2', pid: fstr }
	}).done(function( msg ) {
	//alert(msg);
 		document.getElementById("player2").innerHTML=msg;
	});

	});
});

$(function () {
	$('#addagame').on('change', '#team1', function () { 
		
	document.getElementById("soloplayer").innerHTML="";
	var fstr=document.getElementById("team1").value;
	$.ajax({
		type: "POST",
		url: "stats_functions.php",
		data: { player: 'solo', tid: fstr }
	}).done(function( msg ) {
 		document.getElementById("soloplayer").innerHTML=msg;
	});

	});
});

$(function () {
	$('#addagame').on('change', '#team2_1', function () { 
		
	document.getElementById("team2_2").innerHTML="";
	var fstr=document.getElementById("team2_1").value;
	$.ajax({
		type: "POST",
		url: "stats_functions.php",
		data: { player: '2v2', tid: fstr }
	}).done(function( msg ) {
 		document.getElementById("team2_2").innerHTML=msg;
	});

	});
});

$(function () {
$("#twovone").click(function () {

	$.ajax({
		type: "POST",
		url: "stats_functions.php",
		data: { stats: "2v1" }
		}).done(function( msg ) {
			$("#statstable").empty();
		 		document.getElementById("statstable").innerHTML=msg;
		 		sorttable.makeSortable(document.getElementById('statstable'));
				sorttable.innerSortFunction.apply(document.getElementById("tot1v1win"), []);
				sorttable.innerSortFunction.apply(document.getElementById("tot1v1win"), []);
    		});
		});
});


$(function () {
$("#overall").click(function () {

	$.ajax({
		type: "POST",
		url: "stats_functions.php",
		data: { stats: "overall" }
		}).done(function( msg ) {
			$("#statstable").empty();
		 		document.getElementById("statstable").innerHTML=msg;
		 		sorttable.makeSortable(document.getElementById('statstable'));
				sorttable.innerSortFunction.apply(document.getElementById("tot1v1win"), []);
				sorttable.innerSortFunction.apply(document.getElementById("tot1v1win"), []);
    		});
		});
});

$(function () {
$("#twovtwo").click(function () {

	document.getElementById("statstable").innerHTML="Nothing Here Yet.";
	});
});



