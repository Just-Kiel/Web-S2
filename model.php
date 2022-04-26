<?php
require_once("connexion.php");
if (!isset($_SESSION)) {
    session_start();
  }
  
function getCurrentUser()
{
    if ($_SESSION)
    {
        try {
            $currentUserID = $_SESSION['currentUserID'];
            $data = connexion()->query("SELECT * FROM users WHERE userID=$currentUserID")->fetchAll();
        } catch (PDOException $e) {
            print "Erreur !: " . $e->getMessage() . "<br/>";
            die();
        }
        return $data;
    }
    else
    {
        return NULL;
    }
}

function getAllCities()
{
    try {
        $data = connexion()->query('SELECT * FROM cities')->fetchAll();
    } catch (PDOException $e) {
        print "Erreur !: " . $e->getMessage() . "<br/>";
        die();
    }
    return $data;
}

function getAllGoodPlans(){
    try {
        $data = connexion()->query('SELECT * FROM goodplans')->fetchAll();
    } catch (PDOException $e) {
        print "Erreur !: " . $e->getMessage() . "<br/>";
        die();
    }
    return $data;
}

function getOneCity($n){
    try {
        $data = connexion()->query('SELECT * FROM cities WHERE cityID = '.$n)->fetchAll();
    } catch (PDOException $e) {
        print "Erreur !: " . $e->getMessage() . "<br/>";
        die();
    }
    return $data;
}

function getOneUser($n){
    try {
        $data = connexion()->query('SELECT userID, lastname, firstname FROM users WHERE userID = '.$n)->fetchAll();
    } catch (PDOException $e) {
        print "Erreur !: " . $e->getMessage() . "<br/>";
        die();
    }
    return $data;
}

function getOneMedia($n){
    try {
        $data = connexion()->query('SELECT * FROM medias WHERE mediaID = '.$n)->fetchAll();
    } catch (PDOException $e) {
        print "Erreur !: " . $e->getMessage() . "<br/>";
        die();
    }
    return $data;
}

function getAllCategories(){
    try {
        $data = connexion()->query('SELECT * FROM categories')->fetchAll();
    } catch (PDOException $e) {
        print "Erreur !: " . $e->getMessage() . "<br/>";
        die();
    }
    return $data;
}

function addOneMedia($user, $t){

    $target_dir = "./views/usersData/";
    $target_bdd_dir = "usersData/";
    $target_file = $target_dir.basename($t);
    $target_bdd_file = $target_bdd_dir.basename($t);
    $uploadOk=1;

    $data = [
        'title' => $t,
        'url' => $target_bdd_file,
        'user' => $user,
    ];
    $sql = "INSERT INTO medias (name, url, userID) VALUES (:title,:url,:user)";

    try {
        $stmt= connexion()->prepare($sql);
        $stmt->execute($data);
        if (move_uploaded_file($_FILES["media"]["tmp_name"], $target_file)) {
            echo "The file ". htmlspecialchars($t). " has been uploaded.";
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    } catch (PDOException $e) {
        print "Erreur !: " . $e->getMessage() . "<br/>";
        die();
    }

    try {
        $data = connexion()->query('SELECT MAX(mediaID) FROM medias')->fetchAll();
    } catch (PDOException $e) {
        print "Erreur !: " . $e->getMessage() . "<br/>";
        die();
    }
    return $data[0][0];
}

function addOneGoodPlan($t, $content, $sD, $eD, $cat, $city, $user, $mediaID){
    if(!empty($city)){
        if(!empty($mediaID)){
            $data = [
                'title' => $t,
                'content' => $content,
                'sD' => $sD,
                'eD' => $eD,
                'category' => $cat,
                'city' => $city,
                'user' => $user,
                'media' => $mediaID,
            ];
            $sql = "INSERT INTO goodplans (title, textContent, startingDate, endingDate, categoryID, cityID, userID, mediaID) VALUES (:title,:content, :sD, :eD, :category, :city, :user, :media)";
        } else {
            $data = [
                'title' => $t,
                'content' => $content,
                'sD' => $sD,
                'eD' => $eD,
                'category' => $cat,
                'city' => $city,
                'user' => $user,
            ];
            $sql = "INSERT INTO goodplans (title, textContent, startingDate, endingDate, categoryID, cityID, userID) VALUES (:title,:content, :sD, :eD, :category, :city, :user)";
        }
    } else {
        if(!empty($mediaID)){
            $data = [
                'title' => $t,
                'content' => $content,
                'sD' => $sD,
                'eD' => $eD,
                'category' => $cat,
                'user' => $user,
                'media' => $mediaID,
            ];
            $sql = "INSERT INTO goodplans (title, textContent, startingDate, endingDate, categoryID, userID, mediaID) VALUES (:title,:content, :sD, :eD, :category, :user, :media)";
        } else {
            $data = [
                'title' => $t,
                'content' => $content,
                'sD' => $sD,
                'eD' => $eD,
                'category' => $cat,
                'user' => $user,
            ];
            $sql = "INSERT INTO goodplans (title, textContent, startingDate, endingDate, categoryID, userID) VALUES (:title,:content, :sD, :eD, :category, :user)";
        }
    }

    try {
        $stmt= connexion()->prepare($sql);
        $stmt->execute($data);
        echo "Post ajouté.<br/>
        <a href='accueil'>Accueil</a>";
    } catch (PDOException $e) {
        print "Erreur !: " . $e->getMessage() . "<br/>";
        die();
    }
}