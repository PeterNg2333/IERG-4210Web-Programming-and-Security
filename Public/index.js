// Untity Function
function print(textToBePrinted){
    console.log(textToBePrinted);
};

// Loading function
$(window).on("load", function() { 
    $("#loadingImg h5").text("Ready").delay(1000).fadeOut(2000); 
    $("#preloader").delay(500).fadeOut(2000); 

});