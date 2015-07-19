<?php
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);

require 'Slim/Slim.php';
require 'classes/blog/postHandler.php';
require 'classes/security/EncDecrypt.php';
\Slim\Slim::registerAutoloader();
require 'vendor/autoload.php';
header('Content-Type: text/html; charset=utf-8');
$app = new \Slim\Slim();
$app->contentType('Content-Type: application/json; charset=utf-8');





$app->get("/",function() use ($app){
   echo "Open Sooq Assignment\n";
});

$app->get("/createpost/:post/:userid", function($post,$userid) use ($app) {
    $encDecrypt = new EncDecrypt();
    $post = $encDecrypt->decrypt($post);
    $userid = $encDecrypt->decrypt($userid);
    $model = new postHandler();
    $values = json_decode($post);
    echo $model->createPost($values,$userid);
});


$app->get("/updatepost/:userid/:postid/:values", function($userid,$postid,$post) use ($app) {
 $encDecrypt = new EncDecrypt();
    $post = $encDecrypt->decrypt($post);
    $userid = $encDecrypt->decrypt($userid);
    $postid = $encDecrypt->decrypt($postid);
    $model = new postHandler();
    $values = json_decode($post);
    echo $model->updatePost($userid,$postid,$values);

  
});


$app->get("/fetchposts/:searchterms", function($searchterms) use ($app) {
$encDecrypt = new EncDecrypt();

$searchterms = json_decode($encDecrypt->decrypt($searchterms), true);
$model = new postHandler();
$allData = $model->fetchPosts($searchterms);

echo $allData = $encDecrypt->encrypt($allData);

  
});

$app->get("/fetchpost/:id", function($id) use ($app) {
$encDecrypt = new EncDecrypt();

$id = json_decode($encDecrypt->decrypt($id), true);
$model = new postHandler();
$allData = $model->fetchPost($id);

echo $allData = $encDecrypt->encrypt($allData);

  
});

$app->get("/deletepost/:userid/:postid", function($userid,$postid) use ($app) {
$encDecrypt = new EncDecrypt();

$postid = json_decode($encDecrypt->decrypt($postid), true);
$userid = json_decode($encDecrypt->decrypt($userid), true);
$model = new postHandler();
$delete = $model->deletePost($userid,$postid);

echo $delete = $encDecrypt->encrypt($delete);

  
});

$app->run();
