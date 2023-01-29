// Loading function
function RenderElement(className, HTMLText_Url){
    $.get(HTMLText_Url, function (html_text) {
        $(className).html(html_text);
   });

}

$(window).on("load", function() { 
    $("#loadingImg h5").text("Ready").delay(500).fadeOut(500); 
    $("#loadingImg img").delay(500).fadeOut(500); 
    $("#preloader").delay(800).fadeOut(700); 
    setInterval(function(){
        $("#preloader").remove(); 
        RenderElement("#header", "./Snippet/Header.html");
        RenderElement("#main", "./Snippet/Main.html");
        RenderElement("#footer", "./Snippet/Footer.html");
    }, 2500);
});

