$(function(){

	// Create new page
	$(".wiki-new-page").submit(function(){
		var page = $("input[name=wiki-page]").val();
		var path =location.pathname.substr(0,location.pathname.lastIndexOf("/")+1);
		window.location = path + encodeURI(page);
		return false;
	});

	// Edit current page
	$("#wiki-edit").click(function(){
		window.location.search = "?edit";
		return false;
	});

	// Cancel editing
	$("#wiki-cancel").click(function(){
		window.location.search='';
		return false;
	});

	// Delete current page
	$("#wiki-delete").click(function(){
		// alert("!");
		window.location.search = "?delete";
		return false;
	});
	
	// Rename current page
	$("#rename-page").submit(function(e){
		$rename = $("#rename-page-title");
		var new_name = encodeURI($rename.val());
		var old_name = encodeURI($rename.data("old"));

		// e.preventDefault();
		// window.location.search = "?rename&new=" + new_name + "&old=" + old_name;
		// return false;			

	});
	

	// Jump to another page
	$("#wiki-jump").change(function(){
		var page = $(this).val();
		window.location = page;		
		return false;
	});

	// For iOS web app so links don't open in new window
	$("a").each(function(){
		$(this).click(function(){
	        window.location=this.getAttribute("href");
	        return false
		});
	});
	
});