$(document).ready(function() {
	var cont_left = $("#menu_top_table").position().left;
	$(".menu_top_div").hover(function() {
		// hover in
		$(this).parent().parent().css("z-index", 1);
		$(this).animate({
		   opacity: "1"
		}, "medium");
	}, function() {
		// hover out
		$(this).parent().parent().css("z-index", 0);
		$(this).animate({
		   opacity: "0.8"
		}, "medium");
	});
});