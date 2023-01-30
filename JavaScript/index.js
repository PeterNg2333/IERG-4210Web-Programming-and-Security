// Loading function
function RenderElement(className, HTMLText_Url){
    $.get(HTMLText_Url, function (html_text) {
        $(className).html(html_text);
   });
}

function RenderElementAfter(className, HTMLText_Url){
    $.get(HTMLText_Url, function (html_text) {
        $(className).after(html_text);
   });
}

$(window).on("load", function() { 
    $("#loadingImg h5").text("Ready").delay(500).fadeOut(500); 
    $("#loadingImg img").delay(500).fadeOut(500); 
    $("#preloader").delay(800).fadeOut(700); 
    setTimeout(function(){
        $("#preloader").remove(); 
        RenderElement("#header", "./Snippet/Header.html");
        RenderElement("#main", "./Snippet/Main.html");
        // RenderElement("#footer", "./Snippet/Footer.html");
        RenderElementAfter("#product_card", "./Snippet/Snippet_Statics/Product_Card_Food.html");
        RenderElementAfter("#product_card", "./Snippet/Snippet_Statics/Product_Card_Electronic.html");
        RenderElementAfter("#product_card", "./Snippet/Snippet_Statics/Product_Card_Clothing.html");
        // RenderElement("#product", "./Snippet/Product.html");

        // Add button handler
        
    }, 1200);
});

function loadCategory(){
    url_id = ($(location).attr('href')).split("#")[1]
    if (url_id == "Food_Category")
        loadFood_Category();
    else if (url_id == "Electric_Category")
        loadElectric_Category();
    else if (url_id == "Electric_Category")
        loadClothing_Category
    $("#productPath").addClass("d-none")
}

function loadFood_Category(){
    $("#product").children().not(':first').remove()
    $("#CatergoryPath a").text("Category: Food")
    $("#CatergoryPath a").attr("href", "#Food_Category");
    for (let i = 0; i < 3; i++) {
        RenderElementAfter("#product_card", "./Snippet/Snippet_Statics/Product_Card_Food.html");
      }
    
}

function loadElectric_Category(){
    $("#product").children().not(':first').remove()
    $("#CatergoryPath a").text("Category: Electronics")
    $("#CatergoryPath a").attr("href", "#Electric_Category");
    for (let i = 0; i < 3; i++) {
        RenderElementAfter("#product_card", "./Snippet/Snippet_Statics/Product_Card_Electronic.html");
      }
}

function loadClothing_Category(){
    $("#product").children().not(':first').remove()
    $("#CatergoryPath a").text("Category: Clothing, Shoes, & Accessories")
    $("#CatergoryPath a").attr("href", "#Clothing_Category");
    for (let i = 0; i < 3; i++) {
        RenderElementAfter("#product_card", "./Snippet/Snippet_Statics/Product_Card_Clothing.html");
      }
}





