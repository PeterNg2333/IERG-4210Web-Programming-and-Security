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
        const url = new URL(window.location);
        var get_pid = url.searchParams.get("pid");
        var get_cid = url.searchParams.get("cid");
        // print(get_pid);
        // print(get_cid);
        // print((get_pid == null || get_pid == undefined) || (get_cid == null || get_cid == undefined))
        // print((get_pid != null || get_pid != undefined) || (get_cid != null || get_cid != undefined))
        if ((get_pid != null || get_pid != undefined) || (get_cid != null || get_cid != undefined)){
            // removeinfiniteLoading
            document.getElementById("LoadingMoreProduct").remove();
        }
        else if ((get_pid == null || get_pid == undefined)){
            window.addEventListener('scroll', addProductByScroll);
        }
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

function addToCart_button(e){
    var message = ""
    var text_array = e.target.id.split("-");
    var inputed_id = text_array[1];
    var num_added = document.querySelector("#addToCartNum-" + inputed_id).value;
    if (num_added != 0){
        // load local Storage
        var jsonstr = localStorage.getItem('shoppingList');

        // update local Storage
        if (jsonstr == undefined){
            var new_empty_array = []
            new_empty_array.push({id: inputed_id, orderAmount: num_added})
            var jsonstr = localStorage.setItem('shoppingList', JSON.stringify(new_empty_array));

            /////
            var json = JSON.parse(temp_array);
            print("add new array: " + stringify(json))
        } 
        else {
            var json = JSON.parse(jsonstr);
            json.forEach(element => {
                if (Number(element.id) === Number(inputed_id)){
                    element.orderAmount += num_added;
                    print("Update: element: " + element)
                }
            });
            // if (json.indexOf(id) > -1){
            //     json[id] += num_added
            // } 
            // else {
            //     json.id = num_added
            // }
            localStorage.setItem('shoppingList', JSON.stringify(json));
        }
        
        
        // Refresh shopping list
        alert(json);
    } else {
        alert("Add failed")
    }


}



function load_shoppingCart(id){}

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
                    // print(record);
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
                            var addToCart = $("#addToCart-000");
                            addToCart.removeAttr('id');
                            addToCart.attr('id', "addToCart-" + get_pid);

                            // addEventhandler
                            document.querySelector("#addToCart-" + get_pid).addEventListener("click", addToCart_button);
                            document.getElementById("LoadingMoreProduct").remove();
                            window.removeEventListener('scroll', addProductByScroll);
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
function addProductByScroll(){
    var totalHeight = document.body.clientHeight;
    var innerHeight = window.innerHeight;
    var srcoll = window.scrollY;
    var windowHeight = totalHeight - innerHeight

    if (windowHeight - srcoll <= 28 && window.loadProductMore == false){
        // $("#LoadingMoreProduct").removeClass("d-none");
        $("#LoadingMoreProduct_animation").removeClass("d-none");
        $("#LoadingMoreProduct_text").addClass("d-none");
        window.loadProductMore = true;
        var current_product_loaded = document.querySelectorAll(".count_product_loaded").length;
        // print ("loaded product: " + current_product_loaded);

        $.post("admin/admin-process.php?action=prod_fetch_next_four_page", {} , function(p_res){
            var res_array = p_res[0]
            // print("res_array");
            // print(res_array);
            $.post("admin/admin-process.php?action=prod_count_limit", {} , function(count_res){
                var count_array = count_res[0][0];
                var max_count = Number(count_array["PRODUCT_NUM"]);
                // console.log(max_count);
                // print("count_array");
                if (current_product_loaded < max_count){
                    var load_count = 0;
                    // var load_count = current_product_loaded + 1;
                    var product_html = "";
                    // console.log(load_count);

                    res_array.forEach(function(element){
                        if (load_count >= current_product_loaded && load_count < current_product_loaded + 6){
                            // console.log(element);
                            var get_pid = element.PID;
                            var get_price = element.PRICE;
                            var get_pName = element.PRODUCT_NAME;

                            product_html += '<div class="count_product_loaded col-lg-3 col-md-6 mb-3 px-0" id="P-'+get_pid +'">';
                            product_html += '    <div class="card mx-2 product_card_display">';
                            product_html += '        <a href="/main.php?pid='+ get_pid +'" class="product_detail_button">';
                            product_html += '             <img class="card-img-top" src="./admin/lib/images/P'+get_pid+'.jpg" alt="'+get_pName+'" id="imageP-'+get_pid+'">';
                            product_html += '        </a>';
                            product_html += '        <div class="card-body card_display_body row">';
                            product_html += '           <div class="row">';
                            product_html += '               <h5 class="product_detail_button card-title col-8"><a href="/main.php?pid='+get_pid+'" id="titleP-'+get_pid+'">'+get_pName+'</a></h5>';
                            product_html += '               <p class="card-text col-4">$' + get_price + '</p>';
                            product_html += '           </div>';
                            product_html += '           <input id="addToCartNum-'+get_pid+'" type=hidden value="1" />';
                            product_html += '           <button type="button" id="addToCart-'+ get_pid+'" onclick="addToCart_button" class="addToCart btn btn-primary btn-block product_card_display_button"> Add to Shopping Cart</button>';
                            product_html += '        </div>';
                            product_html += '   </div>';
                            product_html += '</div>';

                        }
                        else{
                            // console.log("Unloaded: " + load_count);
                        }
                        
                        load_count ++;
                    });

                }else if (max_count == current_product_loaded){
                    $("#LoadingMoreProduct_text").text("It's the end")
                }
                print(max_count);
                print(current_product_loaded);
                ////////////////////////// End loading
                $("#product").children().last().after(product_html);
                setTimeout(function () {
                    window.loadProductMore = false;
                    // $("#LoadingMoreProduct").addClass("d-none");
                    $("#LoadingMoreProduct_animation").addClass("d-none");
                    $("#LoadingMoreProduct_text").removeClass("d-none");
                }, 500);
                
                
            });
        });

                    
        // alert("time to refresh");
    }
    // print("totalHeight: " + totalHeight);
    // print("innerHeight: " + innerHeight);
    // print("srcoll" + srcoll);

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
