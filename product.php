<?php
// Se connecter à la base de données
require_once "database.php";
$request_method = $_SERVER["REQUEST_METHOD"];

error_log(print_r($request_method, 1));

function getProducts($id = 0)
{
    $dbh = new Database();
    $dbo = $dbh->getConnection();

    $sql = "SELECT * FROM produits";

    if ($id != 0) {
        $sql .= " WHERE id=" . $id . " LIMIT 1";
    }
 
    $stmt = $dbo->prepare($sql);
    $stmt->execute();
    $response = $stmt->fetchAll();

    header('Content-Type: application/json');
    echo json_encode($response, JSON_PRETTY_PRINT);
}

function addProduct()
{
    $dbh = new Database();
    $dbo = $dbh->getConnection();
    $POST = array(); //tableau qui va contenir les données reçues
    parse_str(file_get_contents('php://input'), $POST);
    $name = $POST["name"];
    $description = $POST["description"];
    $price = $POST["price"];
    $category = $POST["category"];
    $status = $POST["statut"];
    $supplier = $POST["supplier"];
    $purchase = $POST["purchase"];
    $expire = $POST["expire"];
    $query = "INSERT INTO produits (name, description, price, category_id, statut_id, supplier_id, purchase_date, expiration_date)
            VALUES('" . $name . "', '" . $description . "', '" . $price . "', '" . $category . "', '" . $status . "', '" . $supplier . "', '" . $purchase . "', '" . $expire . "')";
    error_log($query);
    $stmt = $dbo->prepare($query);
    $stmt->execute();
}


function updateProduct($id)
{
    $dbh = new Database();
    $dbo = $dbh->getConnection();
    $_PUT = array(); //tableau qui va contenir les données reçues
    parse_str(file_get_contents('php://input'), $_PUT);
    $name = $_PUT["name"];
    $description = $_PUT["description"];
    $price = $_PUT["price"];
    $category = $_PUT["category"];
    $status = $_PUT["status"];
    $supplier = $_PUT["supplier"];
    $purchase = $_PUT["purchase"];
    $expire = $_PUT["expire"];
    //construire la requête SQL
    $query = "UPDATE produits SET name='" . $name . "',
                     description='" . $description . "', 
                     price='" . $price . "',
                     category_id='" . $category . "',
                     statut_id='" . $status . "',
                     supplier_id='" . $supplier . "',
                     purchase_date='" . $purchase . "',
                     expiration_date='" . $expire . "'
                     WHERE id_product=" . $id;

    error_log($query);
    $stmt = $dbo->prepare($query);
    $stmt->execute();

    header('Content-Type: application/json');
    error_log("Done !");
}

function duplicateProduct($id)
{
    $dbh = new Database();
    $dbo = $dbh->getConnection();
    $_DUPE = array(); //tableau qui va contenir les données reçues
    parse_str(file_get_contents('php://input'), $_DUPE);

    $name = $_DUPE["name"];
    $description = $_DUPE["description"];
    $price = $_DUPE["price"];
    $category = $_DUPE["category"];
    $status = $_DUPE["status"];
    $supplier = $_DUPE["supplier"];
    $purchase = $_DUPE["purchase"];
    $expire = $_DUPE["expire"];
    //construire la requête SQL
    $query = "INSERT INTO produits SET name='" . $name . "',
                     description='" . $description . "', 
                     price='" . $price . "',
                     category_id='" . $category . "',
                     statut_id='" . $status . "',
                     supplier_id='" . $supplier . "',
                     purchase_date='" . $purchase . "',
                     expiration_date='" . $expire . "'";

    error_log($query);
}

switch ($request_method) {
    case 'GET':
        if (!empty($_GET["id"])) {
            // Récupérer un seul produit
            $id = intval($_GET["id"]);
            getProducts($id);
        } else {
            // Récupérer tous les produits
            getProducts();
        }
        break;
    case 'POST':
        addProduct();
        break;
    case 'PUT':
        // Modifier un produit
        $id = intval($_GET["id"]);
        updateProduct($id);
        break;
    case 'DUPLICATE':
        duplicateProduct($id);
        break;
    default:
        // Requête invalide
        header("HTTP/1.0 405 Method Not Allowed");
        break;
}
