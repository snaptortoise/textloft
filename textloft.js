$(function(){


	// Create new page
	$("#wiki-new-page").click(function(){
		var page = $("input[name=wiki-page]").val();
		var path =location.pathname.substr(0,location.pathname.lastIndexOf("/")+1);
		location = encodeURI(page);
	});

	// Edit current page
	$("#wiki-edit").click(function(){
		location.search = "?edit";
	});

});