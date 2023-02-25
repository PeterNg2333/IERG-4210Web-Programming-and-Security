const { load } = require("npm");

$(window).on("load", function() { 
    $("#loadingImg h5").text("Ready").delay(500).fadeOut(500); 
    $("#loadingImg img").delay(500).fadeOut(500); 
    $("#preloader").delay(800).fadeOut(700); 
    setTimeout(function(){
        $("#preloader").remove(); 
        $("#header").removeClass("d-none");
        $("#main").removeClass("d-none");
        $("#footer").removeClass("d-none");
        var product_detail_button = document.querySelectorAll(".product_detail_button");
        product_detail_button.forEach(element => {
            element.addEventListener("click", loadProrduct);
        });
        loadProductHelper()
        // setTimeout(function(){
        //     if (! loadProduct()){
        //         loadCategory();
        //     }
        // }, 500);
        // Add button handler       
    }, 1200);
});

function loadProrduct(e){
    e.preventDefault();
    print(e.target.id);
    print(e);
    var text_array = e.target.id.split("-");
    var id = text_array[1];
    const url = new URL(window.location);
    url.searchParams.set('pid', id);
    window.history.pushState({}, '', url);
    loadProductHelper();
}

function loadProductHelper(){
    const url = new URL(window.location);
    var get_pid = url.searchParams.get("pid");
    if (get_pid != null || get_pid != undefined){
        $.post("admin/admin-process.php?action=prod_fetchOne_by_cid_page", 
            {pid: get_pid},
            function(p_res){
                var res_array = p_res[0]
                const _myNode = document.getElementById("product");
                _myNode.textContent = '';
                res_array.forEach(function(record, i){
                    print(record);
                    var get_cid = record.CID;
                    var get_cName = record.CATEGORY_NAME;
                    var get_desc= record.DESCRIPTION;
                    var get_inv = record.INVENTORY;
                    var get_pid = record.PID;
                    var get_price = record.PRICE;
                    var get_pName = record.PRODUCT_NAME;
                    document.getElementById("product").innerHTML="<h4>Testing</h4>"
                    RenderElementInside("#product",
                        "./Snippet/Product.html",
                        function(){
                            // image
                            var image_url = "./admin/lib/images/P" + get_pid + ".jpg";
                            $("#productDetailImg").attr("src", image_url);
                            $("#productDetailImg").attr("alt", get_pName);
                            // product details
                            $("#productDetailTitle").text(get_pName);
                            $("#productDetailPrice").text(get_price);
                            $("#productDetailDesc").text(get_desc);
                            if (Number(get_inv) <= 3){
                                print(Number(get_inv));
                                print(typeof Number(get_inv));
                                get_inv = "Only " + get_inv + " left!";
                                $("#productDetailInv").addClass("text-danger");
                            }
                            $("#productDetailInv").text(get_inv);

                            // add category url
                            $("#CatergoryPath").empty();
                            var get_path = '> <a id="cPathRemove" href="/main.php?cid=' + get_cid + '"> '+ get_cName +' </a>';
                            $("#CatergoryPath").html(get_path);

                            // add product url
                            $("#productPath a").text(get_pName);
                            $("#productPath a").attr("href", "/main.php?pid=" + get_pid);
                            $("#productPath").removeClass("d-none");

                            // add id
                            var addToCartNum = $("#addToCartNum-000");
                            addToCartNum.removeAttr('id');
                            addToCartNum.attr('id', "addToCartNum-" + get_pid);
                            var addToCart = $("#addToCart-");
                            addToCart.removeAttr('id');
                            addToCart.attr('id', "addToCart-" + get_pid);
                            // addEventhandler
                        })
                });
            });
        }
        else {
            print("it is not a product detail page");
        }
    // alert();
}

window.loadProductMore = false;
window.addEventListener('scroll', ()=>{
    var totalHeight = document.body.clientHeight;
    var innerHeight = window.innerHeight;
    var srcoll = window.scrollY;
    var windowHeight = totalHeight - innerHeight
    if (windowHeight - srcoll <= 70 && window.loadProductMore == false){
        window.loadProductMore = true;
        var current_product_loaded = document.querySelectorAll(".count_product_loaded").length;
        print ("loaded product: " + current_product_loaded);
        $.post("admin/admin-process.php?action=prod_fetch_next_four_page", {} , function(p_res){
            var res_array = p_res[0]
            // print("res_array");
            // print(res_array);
            $.post("admin/admin-process.php?action=prod_count_limit", {} , function(count_res){
                var count_array = count_res[0]
                var max_count = count_array["PRODUCT_NUM"];
                // print("count_array");
                if (current_product_loaded <= max_count){
                    var load_count = current_product_loaded + 1;
                    var product_html = "";
                    res_array.forEach(element => {
                        if (load_count <= current_product_loaded + 6){
                            print(element);
                            load_count ++;
                        }
                        
                    });
                }

                ////////////////////////// End loading
                window.loadProductMore = false;
            });
        });

                    
        alert("time to refresh");
    }
    // print("totalHeight: " + totalHeight);
    // print("innerHeight: " + innerHeight);
    // print("srcoll" + srcoll);

});

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
