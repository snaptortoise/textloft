$(function(){

	// Create new page
	$("#wiki-new-page").submit(function(){
		var page = $("input[name=wiki-page]").val();
		var path =location.pathname.substr(0,location.pathname.lastIndexOf("/")+1);
		location = encodeURI(page);
		return false;
	});

	// Jump to another page
	$("#wiki-jump").change(function(){
		var page =$(this).val();
		location = page;		
	});

});