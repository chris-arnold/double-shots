$(function () {
$("#add_player-link").click(function () {

	document.getElementById("main_admin").innerHTML="Nothing Here Yet.";

		$.ajax({
		type: "POST",
		url: "admin-functions.php",
		data: { action: "playerform" }
		}).done(function( msg ) {
			$("#statstable").empty();
		 		document.getElementById("main_admin").innerHTML=msg;
    		});

	});
});

$(function () {
$("#top-link").click(function () {
	document.getElementById("main_admin").innerHTML="";
	});
});

$(function () {
$("#modify_game-link").click(function () {

	document.getElementById("main_admin").innerHTML="Loading...";

		$.ajax({
		type: "POST",
		url: "admin-functions.php",
		data: { action: "modify_game_main" }
		}).done(function( msg ) {
			$("#statstable").empty();
		 		document.getElementById("main_admin").innerHTML=msg;
    		});

	});
});

function mod_1v1() {
		
	$.ajax({
		type: "POST",
		url: "admin-functions.php",
		data: { action: "modify_game_1v1" }
	}).done(function( msg ) {
	//alert(msg);
 		document.getElementById("main_admin").innerHTML=msg;
	});

};
function mod_2v1() {
		
	$.ajax({
		type: "POST",
		url: "admin-functions.php",
		data: { action: "modify_game_2v1" }
	}).done(function( msg ) {
	//alert(msg);
 		document.getElementById("main_admin").innerHTML=msg;
	});

};
function mod_2v2() {
		
	$.ajax({
		type: "POST",
		url: "admin-functions.php",
		data: { action: "modify_game_2v2" }
	}).done(function( msg ) {
	//alert(msg);
 		document.getElementById("main_admin").innerHTML=msg;
	});

};

function delete_1v1(val) {
		
	$.ajax({
		type: "POST",
		url: "admin-functions.php",
		data: { action: "delete_1v1", id: val }
	}).done(function( msg ) {
	//alert(msg);
 		document.getElementById("main_admin").innerHTML=msg;
	});

};

function delete_2v2(val) {
		
	$.ajax({
		type: "POST",
		url: "admin-functions.php",
		data: { action: "delete_2v2", id: val }
	}).done(function( msg ) {
	//alert(msg);
 		document.getElementById("main_admin").innerHTML=msg;
	});

};
function delete_2v1(val) {
		
	$.ajax({
		type: "POST",
		url: "admin-functions.php",
		data: { action: "delete_2v1", id: val }
	}).done(function( msg ) {
	//alert(msg);
 		document.getElementById("main_admin").innerHTML=msg;
	});

};

function add_player(){
		var val=document.getElementById("newname").value;
		//alert(val);

	$.ajax({
		type: "POST",
		url: "admin-functions.php",
		data: { action: "add_player", name: val }
	}).done(function( msg ) {
	//alert(msg);
 		document.getElementById("main_admin").innerHTML=msg;
	});
};

function next_1v1(page){
	$.ajax({
		type: "POST",
		url: "admin-functions.php",
		data: { action: "next_1v1", page: page }
	}).done(function( msg ) {
	//alert(msg);
 		document.getElementById("main_admin").innerHTML=msg;
	});
};

function previous_1v1(page){
	$.ajax({
		type: "POST",
		url: "admin-functions.php",
		data: { action: "previous_1v1", page: page }
	}).done(function( msg ) {
	//alert(msg);
 		document.getElementById("main_admin").innerHTML=msg;
	});
};
