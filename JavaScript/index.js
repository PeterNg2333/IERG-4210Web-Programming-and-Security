// Loading function
$(window).on("load", function() { 
    $("#loadingImg h5").text("Ready").delay(500).fadeOut(500); 
    $("#loadingImg img").delay(500).fadeOut(500); 
    $("#preloader").delay(800).fadeOut(1000); 
    setInterval(function(){
        $("#preloader").remove(); 
    }, 2500);
});

$.get("./Snippet/Header.html", function (result) {
     html = result;
     
     print(html)
});