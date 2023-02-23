<?php
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
        $uploadResult = move_uploaded_file($_FILES["file"]["tmp_name"], "/var/www/html/admin/lib/images/P" . $lastId . ".jpg");
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
function ierg4210_cat_delete(){}
function ierg4210_prod_delete_by_cid(){}
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
        echo json_encode($result);
    };

    header("Content-Type: application/json");
    $result = array("status" => "Failed");
    echo json_encode($result);
    exit();
}
function ierg4210_prod_fetchAll(){}
function ierg4210_prod_fetchOne(){}
function ierg4210_prod_edit(){}
function ierg4210_prod_delete(){}
