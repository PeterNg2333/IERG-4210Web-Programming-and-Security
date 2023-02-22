$(window).on("load", function() { 
    $("#loadingImg h5").text("Ready").delay(500).fadeOut(500); 
    $("#loadingImg img").delay(500).fadeOut(500); 
    $("#preloader").delay(800).fadeOut(700); 
    setTimeout(function(){
        $("#preloader").remove(); 
        document.body.className = "afterLoading";
        $("#header").removeClass("d-none");
        $("#main").removeClass("d-none");
        // RenderElement("#header", "./Snippet_admin/header_admin.html");
        // RenderElement("#main", "./Snippet_admin/main_admin.html");
        for (let i = 0; i < 20; i++) {
            RenderElementAfter("#product_input", "./Snippet_admin/product_card_admin.html");
          }
    }, 1200);
});