$(function(){


	// Create new page
	$("#wiki-new-page").submit(function(){
		var page = $("input[name=wiki-page]").val();
		var path =location.pathname.substr(0,location.pathname.lastIndexOf("/")+1);
		location = encodeURI(page);
		return false;
	});

	// Edit current page
	$("#wiki-edit").click(function(){
		location.search = "?edit";
	});

	// Delete current page
	$("#wiki-delete").click(function(){
		// alert("!");
		location.search = "?delete";
	});

	// Jump to another page
	$("#wiki-jump").change(function(){
		var page =$(this).val();
		location = page;		
	});

});