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
        for (let i = 0; i < 4; i++) {
            RenderElementAfter("#product_input", "./Snippet_admin/product_card_admin.html");
          }
        var selectElement = document.getElementById('Category_dropDown');
        window.ierg4210_category_list = [...selectElement.options].map(opt => opt.text);
        // console.log(window.ierg4210_category_list);

        
        document.querySelector("#add_category").addEventListener("click", add_category);
        document.querySelector("#edit_category").addEventListener("click", edit_category);
        document.querySelector("#Category_dropDown").addEventListener("change", change_category);
        document.querySelector("#product_image").addEventListener("change", change_image);
        document.getElementById('cid_input').value = document.getElementById('Category_dropDown').value;
        setTimeout(function(){
            change_category();
        }, 600);
    }, 600);
});

function is_exist_category(text, array){
    if (text != "All") 
        return false;
    for (var element of array){
        if (element.toLowerCase() == text.toLowerCase());
            // print(element);
            return true;
    }
    return false;
}

function add_category(){
    var temp_name = document.querySelector("#modify_category > input").value.trim();
    if (is_exist_category(temp_name, window.ierg4210_category_list)){
        alert("The category: " + temp_name + " is already there");
        return ;
    }
    $.post("admin-process.php?action=cat_insert", 
        {Cname: temp_name},
        function(json){
            if (json.status == "Success"){
                alert("Add Success!!");
                location.reload()
            }
            else {
                alert("Add Failed!!");
                document.querySelector("#modify_category > input").value = "";
            }
        }
    );
}

function edit_category(){
    var new_name = prompt("Enter the new category name", "name")
    if (is_exist_category(new_name , window.ierg4210_category_list)){
        alert("The category: " + new_name + " is already there");
        return ;
    }
    if (new_name != null || new_name != " "){
        var temp_name = new_name.trim();
        var selectement = document.getElementById('Category_dropDown').value.trim();
        $.post("admin-process.php?action=cat_edit", 
            {Cname: temp_name, CID: selectement},
            function(json){
                if (json.status == "Success"){
                    alert("Edit Success!!");
                    location.reload()
                    return ;
                }
                else {
                    alert("Edit Failed!!");
                    return ;
                }
            }
        );
    } 
    else alert("Edit Failed!!");
}

function change_category(){
    var selectedOpt = document.getElementById('Category_dropDown');
    document.getElementById('cid_input').value = selectedOpt.value;
    // document.getElementById('cid_display_name').value = selectement.name;
    document.getElementById('cid_display_name').value = selectedOpt.options[selectedOpt.selectedIndex].text;
    // alert(document.getElementById('cid_input').value);
    load_productsCard_by_cid();
}

function change_image(){
    var reader = new FileReader();
    var input = document.querySelector("#product_image");
    reader.onload = function (e) {
        $("#image_uploaded_display_section").removeClass("d-none");
        $("#image_display").attr('src', e.target.result);
        $("#image_display").attr('alt', input.files[0].name);
     }
    reader.readAsDataURL(input.files[0]);
    // image.src = uploaded_image.files;
    
}

function load_productsCard_by_cid(){
    var selectement = document.getElementById('Category_dropDown').value;
    $.post("admin-process.php?action=prod_fetchAll_by_cid", 
            {CID: selectement},
            function(p_res){
                $(".product_record").remove();
                console.log("1");
                $.get("admin-process.php?action=cat_fetchall", function(c_res){
                    console.log("2");
                    console.log(typeof c_res);
                    console.log(c_res[0]);
                    for (var record of p_res[0]){
                            var cid = record["CID"];
                            var Cname = record["CATEGORY_NAME"];
                            var pid = record["PID"];
                            var Pname = record["PRODUCT_NAME"];
                            var price = record["PRICE"];
                            var inv = record["INVENTORY"];
                            var description= record["DESCRIPTION"];
                    }
                });
            }
        );
}
