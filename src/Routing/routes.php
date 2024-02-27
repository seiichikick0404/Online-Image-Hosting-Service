<?php
session_start();

use Helpers\DatabaseHelper;
use Helpers\ValidationHelper;
use Response\Render\HTMLRenderer;
use Response\Render\JSONRenderer;



return [
    'top'=>function(){
        return new HTMLRenderer('component/createImage');
    },
    'imageLibrary'=>function(){
        $images = DatabaseHelper::getImages();
        return new HTMLRenderer('component/imageLibrary', ['images' => $images]);
    },
    'api/json/save'=> function(){
        $title = isset($_POST['title']) ? $_POST['title'] : null;
        $image = isset($_FILES['image']) ? $_FILES['image'] : null;
        $clientIp = $_SERVER['REMOTE_ADDR'] ? : null;

        // バリデーションチェック
        $validated = ValidationHelper::uploadImage($title, $image, $clientIp);
        if (!$validated['success']) {
            return new JSONRenderer(["response" => $validated]);
        } else {
            $responseData = DatabaseHelper::createImage($title, $image, $clientIp);
            return new JSONRenderer(["response" => $responseData]);
        }
    },
    'show'=>function(){
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $uri =  str_replace("/show", "", $uri);

        try {
            DatabaseHelper::incrementViewCount($uri);
            $targetImage = DatabaseHelper::getImage($uri);
        } catch(Exception $e) {
            $targetImage = null;
        }

        return new HTMLRenderer('component/showImage', ['imageData' => $targetImage]);
    },
    'delete'=>function(){
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $deleteUrl = str_replace("/delete", "", $uri);

        try {
            $status = DatabaseHelper::deleteImage($deleteUrl);
        } catch(Exception $e) {
            $status = false;
        }

        if ($status) {
            return new HTMLRenderer('component/deleteImage', ['message' => "削除が完了しました。"]);
        } else {
            return new HTMLRenderer('component/deleteImage', ['imageData' => "画像データの削除に失敗しました。"]);
        }
    },
];