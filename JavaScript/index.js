
$(window).on("load", function() { 
    $("#loadingImg h5").text("Ready").delay(500).fadeOut(500); 
    $("#loadingImg img").delay(500).fadeOut(500); 
    $("#preloader").delay(800).fadeOut(700); 
    setTimeout(function(){
        $("#preloader").remove(); 
        $("#header").removeClass("d-none");
        $("#main").removeClass("d-none");
        $("#footer").removeClass("d-none");

        
        // setTimeout(function(){
        //     if (! loadProduct()){
        //         loadCategory();
        //     }
        // }, 500);
        // Add button handler       
    }, 1200);
});

function loadProrduct(e){
    print(e.target.id);
    return false
}

function loadProductHelper(){
    url_id = ($(location).attr('href')).split("#")[1];
    alert(url_id);
}

// // load category
// function loadCategory(){
//     url_id = ($(location).attr('href')).split("#")[1]
//     current_category_path = $("#CatergoryPath a").attr("href");
//     if (url_id == "Food_Category" || current_category_path=="#Food_Category"){
//         loadFood_Category()
//     }
//     else if (url_id == "Electric_Category" || current_category_path=="#Electric_Category"){
//         loadElectric_Category();
//     }
//     else if (url_id == "Clothing_Category"  || current_category_path=="#Clothing_Category"){
//         loadClothing_Category();
//     }

//     // Add id
//     else {
//         $("#product").children().not(':first').remove()
//         RenderElementAfter("#product_card", "./Snippet/Snippet_Statics/Product_Card_Food.html");
//         RenderElementAfter("#product_card", "./Snippet/Snippet_Statics/Product_Card_Electronic.html");
//         RenderElementAfter("#product_card", "./Snippet/Snippet_Statics/Product_Card_Clothing.html");
//         $("#CatergoryPath a").text("You might like")
//         $("#CatergoryPath a").attr("href", "#You_might_like");
//         $("#productPath").addClass("d-none")
//     }
// }

// // load category
// function loadFood_Category(){
//     $("#product").children().not(':first').remove();
//     $("#CatergoryPath a").text("Category: Food");
//     $("#CatergoryPath a").attr("href", "#Food_Category");
//     for (let i = 0; i < 3; i++) {
//         RenderElementAfter("#product_card", "./Snippet/Snippet_Statics/Product_Card_Food.html");
//       }
//     $("#productPath").addClass("d-none")
// }

// function loadElectric_Category(){
//     $("#product").children().not(':first').remove();
//     $("#CatergoryPath a").text("Category: Electronics");
//     $("#CatergoryPath a").attr("href", "#Electric_Category");
//     for (let i = 0; i < 3; i++) {
//         RenderElementAfter("#product_card", "./Snippet/Snippet_Statics/Product_Card_Electronic.html");
//       }
//     $("#productPath").addClass("d-none")
// }

// function loadClothing_Category(){
//     $("#product").children().not(':first').remove();
//     $("#CatergoryPath a").text("Category: Clothing, Shoes, & Accessories");
//     $("#CatergoryPath a").attr("href", "#Clothing_Category");
//     for (let i = 0; i < 3; i++) {
//         RenderElementAfter("#product_card", "./Snippet/Snippet_Statics/Product_Card_Clothing.html");
//       }
//     $("#productPath").addClass("d-none")
// }

// // load product

// function loadProduct(){
//     url_id = ($(location).attr('href')).split("#")[1]
//     current_product_path = $("#productPath a").attr("href");
//     // load product
//     if (url_id == "Fish"  || current_product_path=="#Fish"){
//         loadFish();
//         return true
//     }
//     else if (url_id == "Apple"  || current_product_path=="#Apple"){
//         loadApple();
//         return true
//     }
//     else if (url_id == "Cable"  || current_product_path=="#Cable"){
//         loadCable();
//         return true
//     }
//     else if (url_id == "Shoes"  || current_product_path=="#Shoes"){
//         loadShoes();
//         return true
//     }
//     else if (url_id == "T-shirt"  || current_product_path=="#T-shirt"){
//         loadT_Shirt();
//         return true
//     }
//     return false
// }

// function loadFish(){
//     $("#product").children().not(':first').remove();
//     RenderElementAfter("#product_card", "./Snippet/Snippet_Statics/Product_Fish.html");
//     // Category Path
//     $("#CatergoryPath a").text("Category: Food");
//     $("#CatergoryPath a").attr("href", "#Food_Category");
//     // Product Path
//     $("#productPath a").text("Fish");
//     $("#productPath a").attr("href", "#Fish");
//     $("#productPath").removeClass("d-none");
// }

// function loadApple(){
//         $("#product").children().not(':first').remove();
//         RenderElementAfter("#product_card", "./Snippet/Snippet_Statics/Product_Apple.html");
//         // Category Path
//         $("#CatergoryPath a").text("Category: Food");
//         $("#CatergoryPath a").attr("href", "#Food_Category");
//         // Product Path
//         $("#productPath a").text("Apple");
//         $("#productPath a").attr("href", "#Apple");
//         $("#productPath").removeClass("d-none");
//     }

// function loadCable(){
//         $("#product").children().not(':first').remove();
//         RenderElementAfter("#product_card", "./Snippet/Snippet_Statics/Product_Cable.html");
//         // Category Path
//         $("#CatergoryPath a").text("Category: Electronics");
//         $("#CatergoryPath a").attr("href", "#Electric_Category");
//             // Product Path
//             $("#productPath a").text("Cable");
//             $("#productPath a").attr("href", "#Cable");
//             $("#productPath").removeClass("d-none");

//         }

// function loadShoes(){
//         $("#product").children().not(':first').remove();
//         RenderElementAfter("#product_card", "./Snippet/Snippet_Statics/Product_Shoes.html");
//         // Category Path
//     $("#CatergoryPath a").text("Category: Clothing, Shoes, & Accessories");
//     $("#CatergoryPath a").attr("href", "#Clothing_Category");
//         // Product Path
//         $("#productPath a").text("Shoes");
//         $("#productPath a").attr("href", "#Shoes");
//         $("#productPath").removeClass("d-none");
// }

// function loadT_Shirt(){
//         $("#product").children().not(':first').remove();
//         RenderElementAfter("#product_card", "./Snippet/Snippet_Statics/Product_T-shirt.html");
//         // Category Path
//     $("#CatergoryPath a").text("Category: Clothing, Shoes, & Accessories");
//     $("#CatergoryPath a").attr("href", "#Clothing_Category");
//         // Product Path
//         $("#productPath a").text("T_shirt");
//         $("#productPath a").attr("href", "#T-shirt");
//         $("#productPath").removeClass("d-none");
//     }
