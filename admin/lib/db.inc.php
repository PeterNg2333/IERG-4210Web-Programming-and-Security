<?php
session_start();
function ierg4210_DB() {
	// connect to the database
	// TODO: change the following path if needed
	// Warning: NEVER put your db in a publicly accessible location
	$db = new PDO('sqlite:/var/www/cart.db');

	// enable foreign key support
	$db->query('PRAGMA foreign_keys = ON;');

	// FETCH_ASSOC:
	// Specifies that the fetch method shall return each row as an
	// array indexed by column name as returned in the corresponding
	// result set. If the result set contains multiple columns with
	// the same name, PDO::FETCH_ASSOC returns only a single value
	// per column name.
	$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

	return $db;
}

function ierg4210_cat_fetchall() {
    // DB manipulation
    global $db;
    $db = ierg4210_DB();
    $q = $db->prepare("SELECT * FROM categories ORDER BY CID LIMIT 100;");
    if ($q->execute())
        return $q->fetchAll();
}

// Since this form will take file upload, we use the tranditional (simpler) rather than AJAX form submission.
// Therefore, after handling the request (DB insert and file copy), this function then redirects back to admin.html
function ierg4210_prod_insert() {
    // input validation or sanitization
    // DB manipulation
    global $db;
    $db = ierg4210_DB();

    // TODO: complete the rest of the INSERT command
    if (!preg_match('/^\d*$/', $_POST['cid']))
        throw new Exception("invalid-cid");
    $_POST['cid'] = (int) $_POST['cid'];
    if (!preg_match('/^[\w\- ]+$/', $_POST['name']))
        throw new Exception("invalid-name");
    if (!preg_match('/^[\d\.]+$/', $_POST['price']))
        throw new Exception("invalid-price");
    if (!preg_match('/^[\d]+$/', $_POST['inventory']))
        throw new Exception("invalid-inventory");
    if (!preg_match('/^[\w\- ]+$/', $_POST['description']))
        throw new Exception("invalid-description");

    /////////////////////////////////////////////
    $sql="INSERT INTO products (cid, product_name, inventory, price, description) VALUES (?, ?, ?, ?, ?);";
    $q = $db->prepare($sql);

    // Copy the uploaded file to a folder which can be publicly accessible at incl/img/[pid].jpg
    if ($_FILES["file"]["error"] == 0
        && $_FILES["file"]["type"] == "image/jpeg" 
            || $_FILES["file"]["type"] == "image/png" 
            || $_FILES["file"]["type"] == "image/jpg"
        && mime_content_type($_FILES["file"]["tmp_name"]) == "image/jpeg" 
            || mime_content_type($_FILES["file"]["tmp_name"]) == "image/png" 
            || mime_content_type($_FILES["file"]["tmp_name"]) == "image/jpg" 
        && $_FILES["file"]["size"] < 5000000) 

    {
        $cid = $_POST["cid"];
        $name = $_POST["name"];
        $price = $_POST["price"];
        $desc = $_POST["description"];
        $inv = $_POST["inventory"];

        $sql="INSERT INTO products (cid, product_name, inventory, price, description) VALUES (?, ?, ?, ?, ?);";
        $q = $db->prepare($sql);
        $q->bindParam(1, $cid);
        $q->bindParam(2, $name);
        $q->bindParam(3, $inv);
        $q->bindParam(4, $price);
        $q->bindParam(5, $desc);
        $q->execute();
        $lastId = $db->lastInsertId();

        // Note: Take care of the permission of destination folder (hints: current user is apache)
        $uploadResult = move_uploaded_file($_FILES["file"]["tmp_name"], "/var/www/IERG-4210Web-Programming-and-Security/admin/lib/images/P" . $lastId . ".jpg");
        if ($uploadResult) {
            // redirect back to original page; you may comment it during debug
            header('Location: admin.php');
            exit();
        }
    }
    header('Content-Type: text/html; charset=utf-8');
    echo 'Invalid file detected. <br/><a href="javascript:history.back();">Back to admin panel.</a>';
    exit();
}
// TODO: add other functions here to make the whole application complete

/////////////////////////////// Admin (Need Auth) /////////////////////////////////////
function ierg4210_cat_insert() {
    if (!preg_match('/^[\w\-\&\_\ ]+$/', $_POST['Cname']))
        throw new Exception("invalid-name");
    // DB manipulation
    global $db;
    $Cname = $_POST["Cname"];

    $db = ierg4210_DB();
    $q = $db->prepare("INSERT INTO categories (CID, CATEGORY_NAME) VALUES (NULL, ?)");
    $q->bindParam(1, $Cname);
    
    if ($q->execute()){
        header("Content-Type: application/json");
        $result = array("status" => "Success");
        echo json_encode($result);
        exit();
    };

    header("Content-Type: application/json");
    $result = array("status" => "Failed");
    echo json_encode($result);
    exit();
    
}
function ierg4210_cat_edit(){
    if (!preg_match('/^[\w\-\&\_\ ]+$/', $_POST['Cname']))
        throw new Exception("invalid-name");
    if (!preg_match('/^[\d]+$/', $_POST['CID']))
        throw new Exception("invalid-id");
    // DB manipulation
    global $db;
    $db = ierg4210_DB();
    $q = $db->prepare("UPDATE CATEGORIES SET CATEGORY_NAME = ? WHERE CID = ?;");
    $Cname = $_POST["Cname"];
    $CID = $_POST["CID"];
    $q->bindParam(1, $Cname);
    $q->bindParam(2, $CID);

    if ($q->execute()){
        header("Content-Type: application/json");
        $result = array("status" => "Success");
        echo json_encode($result);
        exit();
    };

    header("Content-Type: application/json");
    $result = array("status" => "Failed");
    echo json_encode($result);
    exit();
}
function ierg4210_cat_delete(){
    if (!preg_match('/^[\d]+$/', $_POST['CID']))
    throw new Exception("invalid-id");

    // DB manipulation
    global $db;
    $db = ierg4210_DB();
    $CID = $_POST["CID"];

    $q_get_product = $db->prepare("SELECT PID FROM PRODUCTS LEFT JOIN CATEGORIES USING(CID) WHERE CID = ? LIMIT 100;");
    $q_get_product ->bindParam(1, $CID);
    if ($q_get_product->execute()){
        $product_array = $q_get_product->fetchAll();
        $q_delete_product = $db->prepare("DELETE FROM PRODUCTS WHERE CID = ?;");
        $q_delete_product ->bindParam(1, $CID);
        if ($q_delete_product->execute()){
            foreach ($product_array as $value){
                $filePath = "/var/www/IERG-4210Web-Programming-and-Security/admin/lib/images/P" .$value["PID"]. ".jpg";
                unlink($filePath);
            }
            $q = $db->prepare("DELETE FROM CATEGORIES WHERE CID = ?;");
            $q->bindParam(1, $CID);
            if (($q->execute())){
                $result = array("status" => "Success");
                echo json_encode($result);
                exit();
                };
        }
    } 
    header("Content-Type: application/json");
    $result = array("status" => "Failed");
    echo json_encode($result);
    exit();
}

function ierg4210_prod_edit(){
    // input validation or sanitization
    // DB manipulation
    global $db;
    $db = ierg4210_DB();

    // TODO: complete the rest of the INSERT command
    if (!preg_match('/^\d*$/', $_POST['cid']))
        throw new Exception("invalid-cid");
    $_POST['cid'] = (int) $_POST['cid'];
    if (!preg_match('/^[\w\- ]+$/', $_POST['name']))
        throw new Exception("invalid-name");
    if (!preg_match('/^[\d\.]+$/', $_POST['price']))
        throw new Exception("invalid-price");
    if (!preg_match('/^[\d]+$/', $_POST['inventory']))
        throw new Exception("invalid-inventory");
    if (!preg_match('/^[\w\- ]+$/', $_POST['description']))
        throw new Exception("invalid-description");
    if (!preg_match('/^[\d]+$/', $_POST['pid']))
        throw new Exception("invalid-id");

    /////////////////////////////////////////////
    $sql="UPDATE PRODUCTS SET cid = ?, product_name = ?, inventory = ?, price =?, description = ? Where pid = ?;";
    $q = $db->prepare($sql);


        $cid = $_POST["cid"];
        $name = $_POST["name"];
        $price = $_POST["price"];
        $desc = $_POST["description"];
        $inv = $_POST["inventory"];
        $pid = $_POST["pid"];

        $sql="UPDATE PRODUCTS SET cid = ?, product_name = ?, inventory = ?, price =?, description = ? Where pid = ?;";
        $q = $db->prepare($sql);
        $q->bindParam(1, $cid);
        $q->bindParam(2, $name);
        $q->bindParam(3, $inv);
        $q->bindParam(4, $price);
        $q->bindParam(5, $desc);
        $q->bindParam(6, $pid);
        $filePath = "/var/www/IERG-4210Web-Programming-and-Security/admin/lib/images/P" . $pid . ".jpg";
        // Copy the uploaded file to a folder which can be publicly accessible at incl/img/[pid].jpg
        if ($_FILES["file"]["error"] == "4" || $_FILES["file"]["name"] == "") {
            // return $q->execute();
            if ($q->execute() && file_exists($filePath)){
                header('Location: admin.php');
                exit();
            }
        }
        else if($_FILES["file"]["error"] == 0
                && $_FILES["file"]["type"] == "image/jpeg" 
                    || $_FILES["file"]["type"] == "image/png" 
                    || $_FILES["file"]["type"] == "image/jpg"
                && mime_content_type($_FILES["file"]["tmp_name"]) == "image/jpeg" 
                    || mime_content_type($_FILES["file"]["tmp_name"]) == "image/png" 
                    || mime_content_type($_FILES["file"]["tmp_name"]) == "image/jpg" 
                    
                && $_FILES["file"]["size"] < 5000000)

        {
        // Note: Take care of the permission of destination folder (hints: current user is apache)
            if (($q->execute()) && (file_exists($filePath))){
                unlink($filePath);
                exit();
            };
            $uploadResult = move_uploaded_file($_FILES["file"]["tmp_name"], $filePath);
            if ($uploadResult){
                // redirect back to original page; you may comment it during debug
                header('Location: admin.php');
                exit();
            }
        } 
        else if($q->execute()) {
            header('Location: admin.php');
            exit();
        }

    
    header('Content-Type: text/html; charset=utf-8');
    echo 'Invalid file detected. <br/><a href="javascript:history.back();">Back to admin panel.</a>';
    exit();
}

function ierg4210_prod_delete(){
if (!preg_match('/^[\d]+$/', $_POST['pid']))
    throw new Exception("invalid-id");
// DB manipulation
global $db;
$pid = $_POST["pid"];

$db = ierg4210_DB();
$q = $db->prepare("DELETE FROM products where PID = ?;");
$q->bindParam(1, $pid);
$filePath = "/var/www/IERG-4210Web-Programming-and-Security/admin/lib/images/P" . $pid . ".jpg";
if (($q->execute()) && (file_exists($filePath))){
    unlink($filePath);
    header("Content-Type: application/json");
    $result = array("status" => "Success");
    echo json_encode($result);
    exit();
};

header("Content-Type: application/json");
$result = array("status" => "Failed");
echo json_encode($result);
exit();
}

///////////////// Admin (No need Auth) ////////////////////////////////////

function ierg4210_prod_fetchAll_by_cid(){
    if (!preg_match('/^[\d]+$/', $_POST['CID']))
        throw new Exception("invalid-id");

    // DB manipulation
    global $db;
    $db = ierg4210_DB();
    $q = $db->prepare("SELECT * FROM PRODUCTS LEFT JOIN CATEGORIES USING(CID) WHERE CID = ? LIMIT 100;");
    $CID = $_POST["CID"];
    $q->bindParam(1, $CID);

    if ($q->execute()){
        header("Content-Type: application/json");
        $result = $q->fetchAll();
        echo json_encode(array($result));
        exit();
    };

    header("Content-Type: application/json");
    $result = array("status" => "Failed");
    echo json_encode($result);
    exit();
}

function ierg4210_prod_fetchAll(){
    // DB manipulation
    global $db;
    $db = ierg4210_DB();
    $q = $db->prepare("SELECT * FROM PRODUCTS ORDER BY PID LIMIT 100;");
    if ($q->execute())
         return $q->fetchAll();
}


function ierg4210_prod_and_cat_count(){
    // DB manipulation
    global $db;
    $db = ierg4210_DB();
    $q = $db->prepare("SELECT COUNT(DISTINCT CID) AS CATEGORY_NUM, COUNT(DISTINCT PID) AS PRODUCT_NUM, COUNT(DISTINCT CASE WHEN INVENTORY = 0 THEN PID ELSE NULL END) AS OUT_OF_STOCK FROM PRODUCTS LEFT JOIN CATEGORIES USING (CID);");
    if ($q->execute())
        return $q->fetchAll();
}

///////////////// Webpage (No need ) ////////////////////////////////////
function ierg4210_prod_fetchAll_by_cid_page($CID){
    // DB manipulation
    global $db;
    $db = ierg4210_DB();
    $q = $db->prepare("SELECT * FROM PRODUCTS LEFT JOIN CATEGORIES USING(CID) WHERE CID = ? LIMIT 100;");
    $q->bindParam(1, $CID);

    if ($q->execute()){
         return $q->fetchAll();
    }

    header("Content-Type: application/json");
    $result = array("status" => "Failed");
    echo json_encode($result);
    exit();
}

function ierg4210_cat_fetch_by_cid_page($CID){
    // DB manipulation
    global $db;
    $db = ierg4210_DB();
    $q = $db->prepare("SELECT * FROM CATEGORIES WHERE CID = ? LIMIT 1;");
    $q->bindParam(1, $CID);

    if ($q->execute()){
         return $q->fetchAll();
    }

    header("Content-Type: application/json");
    $result = array("status" => "Failed");
    echo json_encode($result);
    exit();
}

function ierg4210_prod_fetchOne_by_pid_page(){
    if (!preg_match('/^[\d]+$/', $_POST['pid']))
        throw new Exception("invalid-id");

    // DB manipulation
    global $db;
    $db = ierg4210_DB();
    $q = $db->prepare("SELECT * FROM PRODUCTS LEFT JOIN CATEGORIES USING(CID) WHERE PID = ? LIMIT 1;");
    $pid = $_POST["pid"];
    $q->bindParam(1, $pid);

    if ($q->execute()){
        header("Content-Type: application/json");
        $result = $q->fetchAll();
        echo json_encode(array($result));
        exit();
    };

    header("Content-Type: application/json");
    $result = array("status" => "Failed");
    echo json_encode($result);
    exit();
}

function ierg4210_prod_fetch_next_four_page(){
    // DB manipulation
    global $db;
    $db = ierg4210_DB();
    $q = $db->prepare("SELECT * FROM PRODUCTS ORDER BY PID LIMIT 100;");
    if ($q->execute()){
        header("Content-Type: application/json");
        $result = $q->fetchAll();
        echo json_encode(array($result));
        exit();
    }
    header("Content-Type: application/json");
    $result = array("status" => "Failed");
    echo json_encode($result);
    exit();
}

function ierg4210_prod_count_limit(){
    // DB manipulation
    global $db;
    $db = ierg4210_DB();
    $q = $db->prepare("Select COUNT(PID) AS PRODUCT_NUM FROM Products;");
    if ($q->execute()){
        header("Content-Type: application/json");
        $result = $q->fetchAll();
        echo json_encode(array($result));
        exit();
    }
    header("Content-Type: application/json");
    $result = array("status" => "Failed");
    echo json_encode($result);
    exit();
}

///////////////////////////////////////////// Auth /////////////////////////////////////////////////

function ierg4210_DB_account() {
	// connect to the database
	// TODO: change the following path if needed
	// Warning: NEVER put your db in a publicly accessible location
	$db_account = new PDO('sqlite:/var/www/account.db');

	// enable foreign key support
	$db_account->query('PRAGMA foreign_keys = ON;');

	// FETCH_ASSOC:
	// Specifies that the fetch method shall return each row as an
	// array indexed by column name as returned in the corresponding
	// result set. If the result set contains multiple columns with
	// the same name, PDO::FETCH_ASSOC returns only a single value
	// per column name.
	$db_account->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

	return $db_account;
}

function ierg4210_login(){
    // DB manipulation
    global $db_account;
    $db_account = ierg4210_DB_account();
    
    // // First time
    // $qq = $db_account->prepare("DELETE FROM USER WHERE EMAIL = 'user1155143402@gmail.com';");
    // if (! $qq->execute()){
    //     return "failed!";
    // }

    // $qqq = $db_account->prepare("INSERT INTO USER (EMAIL, PASSWORD, SALT, FLAG) VALUES ('user1155143402@gmail.com', 'user123!', '456456456456', 0);");
    // if (! $qqq->execute()){
    //     return "failed!";
    // }

    // $p1 =  hash_hmac('sha256', "admin123!", "123123123123");
    // $q1 = $db_account->prepare("UPDATE USER SET PASSWORD = ? WHERE EMAIL = 'admin1155143402@gmail.com';");
    // $q1->bindParam(1, $p1);
    // if (! $q1->execute()){
    //     return "failed!";
    // }

    // $p2 =  hash_hmac('sha256', "user123!", "456456456456");
    // $q2 = $db_account->prepare("UPDATE USER SET PASSWORD = ? WHERE EMAIL = 'user1155143402@gmail.com';");
    // $q2->bindParam(1, $p2);
    // if (! $q2->execute()){
    //     return "failed!";
    // }

    // // Testing
    // $p1 =  hash_hmac('sha256', "admin123!", "123123123123");
    // $p2 =  hash_hmac('sha256', "user123!", "456456456456");
    // return  $p1. ' & '. $p2 ;
    // $q2 = $db_account->prepare("UPDATE USER SET FLAG = 0 WHERE EMAIL = 'user1155143402@gmail.com';");
    // if (! $q2->execute()){
    //     return "failed!";
    // }

    if (!preg_match('/^[\w\-\/][\w\'\-\/\.]*@[\w\-]+(\.[\w\-]+)*(\.[\w]{2,6})$/', $_POST['username']))
        throw new Exception("invalid-email");
    if (!preg_match('/^[\w\-\@\!\' ]+$/', $_POST['password']))
        throw new Exception("invalid-password");
    $username = $_POST["username"];
    $password = $_POST["password"];

    $q = $db_account->prepare("Select * FROM USER WHERE EMAIL = ? LIMIT 1;");
    $q->bindParam(1, $username);
    if ($q->execute()){
        header("Content-Type: application/json");
        $result = $q->fetchAll();
        if (empty($result[0])){
            return "Wrong-email_or_password";
        }
        $user = $result[0];
        $user_email = $user["EMAIL"];
        $user_password = $user["PASSWORD"];
        $user_salt = $user["SALT"];
        $user_flag = $user["FLAG"];
        if ($user_password == hash_hmac('sha256', $password, $user_salt)){
            // When successfully authenticated,
            // 1. create authentication token
                $exp = time() + 3600 * 24 * 1;
                $hash = hash_hmac('sha256', $exp . $user_password, $user_salt);
                $token = array('em'=>$user_email, 'exp'=>$exp, 'k'=> ($hash));
                setcookie('auth', json_encode($token), $exp, "/", "secure.s19.ierg4210.ie.cuhk.edu.hk", true, true);
                $_SESSION['auth'] = $token;
                session_regenerate_id();

            // 2. redirect to admin.php
            if ($user_flag==1){
                header('Location: admin.php', true, 302);
            } 
            else{
                header('Location: ../main.php', true, 302);
            } 
        }else {
            throw new Exception('auth-error');
        }
    }
    header('Content-Type: text/html; charset=utf-8');
    echo 'Wrong Password or Username. <br/><a href="javascript:history.back();">Back to Login.</a>';
    exit();
}


function ierg4210_changePd(){
    global $db_account;
    $db_account = ierg4210_DB_account();

    if (!preg_match('/^[\w\-\/][\w\'\-\/\.]*@[\w\-]+(\.[\w\-]+)*(\.[\w]{2,6})$/', $_POST['username']))
        throw new Exception("invalid-email");
    if (!preg_match('/^[\w\-\@\!\' ]+$/', $_POST['old_password']))
        throw new Exception("invalid-old-password");
    if (!preg_match('/^[\w\-\@\!\' ]+$/', $_POST['new_password_1']))
        throw new Exception("invalid-new-password");
    if (!preg_match('/^[\w\-\@\!\' ]+$/', $_POST['new_password_2']))
        throw new Exception("invalid-new-password");
    if ($_POST["new_password_1"] != $_POST["new_password_2"]){
        throw new Exception("new-password-unmatched");
    }
    if ($_POST["old_password"] == $_POST["new_password_1"]){
        throw new Exception("same_password");
    }
    $username = $_POST["username"];
    $password = $_POST["old_password"];
    $new_password_1 = $_POST["new_password_1"];

    // checking
    $q = $db_account->prepare("Select * FROM USER WHERE EMAIL = ? LIMIT 1;");
    $q->bindParam(1, $username);
    if ($q->execute()){
        header("Content-Type: application/json");
        $result = $q->fetchAll();
        if (empty($result[0])){
            return "Wrong-email_or_password";
        }
        $user = $result[0];
        $user_email = $user["EMAIL"];
        $user_password = $user["PASSWORD"];
        $user_salt = $user["SALT"];
        if ($user_password == hash_hmac('sha256', $password, $user_salt) ){
            // When successfully authenticated,
            // 1. create authentication token
            $new_salt = mt_rand();
            $new_password =  hash_hmac('sha256', $new_password_1, $new_salt);
            $q2 = $db_account->prepare("UPDATE USER SET PASSWORD = ?, SALT = ?  WHERE EMAIL = ?");
            $q2->bindParam(1, $new_password);
            $q2->bindParam(2, $new_salt);
            $q2->bindParam(3, $user_email);
            if (! $q2->execute()){
                return "SQL failed!";
            }

            // 2. redirect back to login page and clear cookie
                return ierg4210_exit();
        } 
        else {
            throw new Exception('auth-error');
        }
    }
    header('Content-Type: text/html; charset=utf-8');
    echo 'Error. <br/><a href="javascript:history.back();">Back to Login.</a>';
    exit();
}

function ierg4210_exit(){
    // unset($_COOKIE['auth']);
    // $exp = time() - 3600 * 24 * 1;
    // setcookie('auth', json_encode(""), $exp, "/", "secure.s19.ierg4210.ie.cuhk.edu.hk", true, true);

    // unset($_SESSION['auth']);
    // header('Location: login_admin.html', true, 302);
    // exit();
    auth();
}



function auth(){

}







// function ierg4210_prod_fetch_next_four_page(){
//     if (!preg_match('/^[\d]+$/', $_POST['load_num']))
//     throw new Exception("invalid-id");
//     // DB manipulation
//     global $db;
//     $db = ierg4210_DB();
//     $q = $db->prepare("Select *, row_num FROM Products LEFT JOIN (SELECT PID, row_number() OVER() as row_num FROM PRODUCTS) USING(PID) Where row_num <= ?;");
//     $load_num = $_POST["load_num"];
//     $load_num = $load_num + 4;
//     $q->bindParam(1, $load_num);
//     if ($q->execute()){
//         header("Content-Type: application/json");
//         $result = $q->fetchAll();
//         echo json_encode(array($result));
//         exit();
//     }
//     header("Content-Type: application/json");
//     $result = array("status" => "Failed");
//     echo json_encode($result);
//     exit();
// }

// function ierg4210_prod_fetch_four_page(){
//     // DB manipulation
//     global $db;
//     $db = ierg4210_DB();
//     $q = $db->prepare("SELECT * FROM PRODUCTS;");
//     if ($q->execute())
//          return $q->fetchAll();

//     // // DB manipulation
//     // global $db;
//     // $db = ierg4210_DB();
//     // $q = $db->prepare("SELECT * FROM PRODUCTS LEFT JOIN (SELECT PID, row_number() OVER() as row_num FROM PRODUCTS) USING(PID) Where ROW_NUM <= 4;");
//     // if ($q->execute())
//     //     return $q->fetchAll();
// }