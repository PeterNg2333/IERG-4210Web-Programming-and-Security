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
        for (let i = 0; i < 2; i++) {
            RenderElementAfter("#product_input", "./Snippet_admin/product_card_admin.html");
          }
        var selectElement = document.getElementById('Category_dropDown');
        window.ierg4210_category_list = [...selectElement.options].map(opt => opt.text);
        // console.log(window.ierg4210_category_list);

        document.querySelector("#add_category").addEventListener("click", add_category);
        document.querySelector("#edit_category").addEventListener("click", edit_category);
        document.querySelector("#Category_dropDown").addEventListener("change", change_category);
        document.querySelector("#product_image").addEventListener("change", change_image);
        document.getElementById('cid_input').value = int_sanitization(document.getElementById('Category_dropDown').value);
        document.getElementById('delete_category').addEventListener("click", delete_category);
        setTimeout(function(){
            change_category();
            setTimeout(function(){
                var insert_dnd_ele= document.querySelector("#image_uploaded_display_section")
                insert_dnd_ele.addEventListener('dragenter', dragEnterHandler, false)
                insert_dnd_ele.addEventListener('dragleave', dragLeaveHandler, false)
                insert_dnd_ele.addEventListener('drop', dropHandler, false)
            }, 300);
        }, 500);
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
    var temp_name = string_sanitization(document.querySelector("#modify_category > input").value.trim());
    var _nonce = string_sanitization(document.getElementById("cat_insert_nonce").value);

    // if (is_exist_category(temp_name, window.ierg4210_category_list)){
    //     alert("The category: " + temp_name + " is already there");
    //     return ;
    // }
    if (! temp_name.match(/^[\w\-\ ]+$/)) {
        alert("invalid name");
        return ;
    }
    $.post("admin-process.php?action=cat_insert", 
        {Cname: temp_name,
         nonce: _nonce
        },
        function(json){
            // var jsonStr = json.replace(/^while\(1\);/, "");
            // var jsonObj = JSON.parse(jsonStr);
            print (typeof jsonObj);
            print (jsonObj);
            if (json.status == "Success"){
                alert("Add Success!!");
                location.reload()
            }            
            else if (json.failed == "auth-error"){
                alert("Auth Error!!");
                window.location.href = "login_admin.php";
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
    new_name = string_sanitization(new_name);
    if (! new_name.match(/^[\w\-\ ]+$/)) {
        alert("invalid name");
        return ;
    }
    if (is_exist_category(new_name , window.ierg4210_category_list)){
        alert("The category: " + new_name + " is already there");
        return ;
    }
    if (new_name != null || new_name != " "){
        var temp_name = new_name.trim();
        var selectelement = int_sanitization(document.getElementById('Category_dropDown').value.trim());
        var _nonce = string_sanitization(document.getElementById("cat_edit_nonce").value);
        $.post("admin-process.php?action=cat_edit", 
            {Cname: temp_name,
            CID: selectelement,
            nonce: _nonce
            },
            function(json){
                if (json.status == "Success"){
                    alert("Edit Success!!");
                    location.reload()
                    return ;
                }
                else if (json.failed == "auth-error"){
                    alert("Auth Error!!");
                    window.location.href = "login_admin.php";
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

function delete_category(){
    var selectelement = int_sanitization(document.getElementById('Category_dropDown').value.trim());
    var _nonce = string_sanitization(document.getElementById("cat_delete_nonce").value);
    $.post("admin-process.php?action=cat_delete", 
        {CID: selectelement,
        nonce: _nonce
        },
        function(json){
            if (json.status == "Success"){
                alert("Delete Success!!");
                location.reload()
            }
            else if (json.failed == "auth-error"){
                alert("Auth Error!!");
                window.location.href = "login_admin.php";
            }
            else {
                alert("Delete Failed!!");
            }
        }
    );
}

function change_category(){
    var selectedOpt = document.getElementById('Category_dropDown');
    document.getElementById('cid_input').value = int_sanitization(selectedOpt.value);
    // document.getElementById('cid_display_name').value = selectement.name;
    document.getElementById('cid_display_name').value = string_sanitization(selectedOpt.options[selectedOpt.selectedIndex].text);
    // alert(document.getElementById('cid_input').value);
    load_productsCard_by_cid();
}

function change_image(){
    var reader = new FileReader();
    var input = document.querySelector("#product_image");
    file_check(input.files[0].name);
    reader.onload = function (e) {
        $("#image_uploaded_display_section p").removeClass("invisible");
        $("#image_uploaded_display_section img").removeClass("invisible");
        $("#image_display").attr('src', e.target.result);
        $("#image_display").attr('alt', input.files[0].name);
     }
    reader.readAsDataURL(input.files[0]);
    // image.src = uploaded_image.files;
    
}

function dragEnterHandler(e){
    var temp_id = e.target.id;
    document.querySelector("#" + temp_id).classList.add("bg-secondary");
}
function dragLeaveHandler(e){
    var temp_id = e.target.id;
    document.querySelector("#" + temp_id).classList.remove("bg-secondary");
}
function dropHandler(e){
    var temp_id = e.target.id;
    var input = document.querySelector("#" + temp_id).parentNode.children[0];
    input.files = e.dataTransfer.files;
    // print(temp_id)
    // print(input.files[0])
    // print("datatranfer");
    // print(e.dataTransfer.files);
    // print("")
    // print("input_file")
    // print(input.files[0]);

    var reader = new FileReader();
    file_check(input.files[0].name);
    reader.onload = function (e) {
        $("#"+ temp_id + " img").attr('src', e.target.result);
        $("#"+ temp_id + " img").attr('alt', input.files[0].name);
     }
    reader.readAsDataURL(input.files[0]);
    $("#"+ temp_id + " p").removeClass("invisible");
    $("#"+ temp_id + " img").removeClass("invisible");
    document.querySelector("#" + temp_id).classList.remove("bg-secondary");
    e.preventDefault(); 
}

function load_productsCard_by_cid(){
    var selectement = int_sanitization(document.getElementById('Category_dropDown').value);
    $.post("admin-process.php?action=prod_fetchAll_by_cid", 
            {CID: selectement},
            function(p_res){
                $(".product_record").remove();
                // console.log("1");
                    var res_array = p_res[0]
                    res_array.forEach(function(record, i){
                        var cid = record["CID"];
                        // var Cname = record["CATEGORY_NAME"];
                        var pid = int_sanitization(record["PID"]);
                        var Pname = string_sanitization(record["PRODUCT_NAME"]);
                        var price = int_sanitization(record["PRICE"]);
                        var inv = int_sanitization(record["INVENTORY"]);
                        var description = string_sanitization(record["DESCRIPTION"]);
                        // print(record);
                        RenderElementAfter("#product_input"
                            , "./Snippet_admin/product_card_admin.html"
                            , function(){
                            var options = document.getElementById('Category_dropDown').children;
                            // Cateogory Selection
                            for (var i = 0; i < options.length; i++){
                                var clone = options[i].cloneNode(true)
                                if (clone.value == cid){
                                    clone.setAttribute("selected", true);
                                }
                                    // print(clone);
                                    // print(document.querySelector("#Category_dropDown_for_each_record-000"));
                                document.querySelector("#Category_dropDown_for_each_record-000").appendChild(clone);
                                }
                                var select_dropdown = $("#Category_dropDown_for_each_record-000");
                                select_dropdown.removeAttr('id');
                                select_dropdown.attr('id', "Category_dropDown_for_each_record-" + pid);
                            

                            // list item id
                            var list_item = $("#P-000");
                            list_item.removeAttr('id');
                            list_item.attr('id', "P-" + pid);
                            // Pid
                            var product_pid = $("#pid_input-000");
                            product_pid.val(pid);
                            product_pid.removeAttr('id');
                            product_pid.attr('id', "pid_input-" + pid);
                            // Product Name
                            var product_name = $("#product_name-000");
                            product_name.val(Pname);
                            product_name.removeAttr('id');
                            product_name.attr('id', "product_name-" + pid);
                            // Price
                            var product_price = $("#product_price-000");
                            product_price.val(price);
                            product_price.removeAttr('id');
                            product_price.attr('id', "product_price-" + pid);
                            // Inventory
                            var product_inv = $("#product_inv-000");
                            product_inv.val(inv);
                            product_inv.removeAttr('id');
                            product_inv.attr('id', "product_inv-" + pid);
                            // Description
                            var product_description = $("#product_desc-000");
                            product_description.val(description);
                            product_description.removeAttr('id');
                            product_description.attr('id', "product_desc-" + pid);
                            // Image
                            var image_input = $("#image_input-000");
                            var image_input_url = "image_input-" + pid;
                            image_input.removeAttr('id');
                            image_input.attr('id', image_input_url);
                            image_input.attr('alt', "P" + pid);

                            var image_uploaded = $("#image_uploaded-000");
                            image_uploaded.removeAttr('id');
                            image_uploaded.attr('id', "image_uploaded-" + pid);
                            var image_url = "./lib/images/P" + pid + ".jpg";
                            image_uploaded.attr("src", image_url);
                            image_uploaded.attr("alt", Pname);

                            // button 
                            var delete_button = $("#delete_button-000");
                            delete_button.removeAttr('id');
                            delete_button.attr('id', "delete_button-" + pid);
                            $(".fa-solid.fa-trash-can").attr('id', "delete_buttonIcon-" + pid);

                            // Event
                            document.querySelector("#"+ "delete_button-" + pid).addEventListener("click", delete_product, false)
                            document.querySelector("#"+ "delete_button-" + pid).firstChild.addEventListener("click", delete_product, false)
                            document.querySelector("#"+ image_input_url).addEventListener("change", change_image_for_productCard)
                            document.querySelector("#" + "P-" + pid).addEventListener("click", enable_modify)
                            
                            // Drag and Drop
                            var dnd = $("#drag_and_drop-000");
                            dnd.removeAttr('id');
                            dnd.attr('id', "drag_and_drop-" + pid);
                            var existing_dnd_ele = document.querySelector("#drag_and_drop-" + pid)
                            existing_dnd_ele.addEventListener('dragenter', dragEnterHandler, false)
                            existing_dnd_ele.addEventListener('dragleave', dragLeaveHandler, false)
                            existing_dnd_ele.addEventListener('drop', dropHandler, false)

                            // nonce
                            _nonce = string_sanitization(document.getElementById("prod_edit_nonce").value);
                            var product_nonce = $("#prod_edit_nonce-000");
                            product_nonce.val(_nonce);
                            product_nonce.removeAttr('id');
                            
                            // show result
                            
                            $("#P-" + pid).removeClass("d-none");
                        }
                    );
                });
            }
        );
}

function change_image_for_productCard(e){
    var text_array = e.target.id.split("-")
    var reader = new FileReader();
    var image_id = "#" +  "image_uploaded-" + text_array[1];
    $(image_id).removeAttr('src');
    $(image_id).removeAttr('alt');
    file_check(e.target.files[0].name);
    reader.onload = function (e) {
        $(image_id).attr('src', e.target.result);
        $(image_id).attr('alt', e.target.files[0].name);
     }
    reader.readAsDataURL(e.target.files[0]);
}

function delete_product(e){
    var text_array = e.target.id.split("-")
    var temp_id  = text_array[1];
    var _nonce = string_sanitization(document.getElementById("prod_delete_nonce").value);
    $.post("admin-process.php?action=prod_delete", 
        {pid: temp_id,
        nonce: _nonce},
        function(json){
            if (json.status == "Success"){
                alert("Delete Success!!");
                location.reload();
            }
            else if (json.failed == "auth-error"){
                alert("Auth Error!!");
                window.location.href = "login_admin.php";
            }
            else {
                alert("Delete Failed!!");
            }
        }
    );
}

function enable_modify(e){
    var text_array = e.target.id.split("-");
    var temp_id = "#P-" + text_array[1];
    // print (temp_id)
    var nodeArray = document.querySelectorAll(temp_id + " .product_input")
    // print (nodeArray);
    nodeArray.forEach(element => {
        element.removeAttribute("disabled");
        // print(element);
    });
    document.querySelector(temp_id + " #create_button").classList.remove("btn-secondary");
    document.querySelector(temp_id + " #create_button").classList.add("btn-success");

}

function file_check(img_name){
    var img_name = String(img_name)
    var file_name = img_name.split(".");
    var index = file_name.length-1;
    file_type = file_name[index];
    accept_type = ["jpg", "png", "jpeg"];
    if (accept_type.indexOf(file_type) > -1){
        return true;
        // print("corret type")
    }
    else{
        alert("wrong image type!!!")
        location.reload();

    }

}