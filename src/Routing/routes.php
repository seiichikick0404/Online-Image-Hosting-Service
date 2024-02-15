<?php
session_start();

use Helpers\DatabaseHelper;
use Helpers\ValidationHelper;
use Response\HTTPRenderer;
use Helpers\CreateSnippetHelper;
use Response\Render\HTMLRenderer;
use Response\Render\JSONRenderer;



return [
    'snippet/save'=>function(): HTTPRenderer{
        $errors = ValidationHelper::createSnippetPost($_POST);
        if (count($errors) > 0) {
            $_SESSION['errors'] = $errors;
            header("Location: ../snippet/create");
            exit;
        }

        $uniquePath = CreateSnippetHelper::generatePath();
        CreateSnippetHelper::createSnippet($_POST, $uniquePath);

        return new HTMLRenderer('component/show');
   },
    'top'=>function(){
        return new HTMLRenderer('component/createImage');
    },
    'imageLibrary'=>function(){
        return new HTMLRenderer('component/imageLibrary');
    },
    'api/json/save'=> function(){
        $title = isset($_POST['title']) ? $_POST['title'] : 'No title provided';
        $image = isset($_FILES['image']) ? $_FILES['image'] : null;
        $clientIp = $_SERVER['REMOTE_ADDR'];

        // TODO: バリデーション(今回のブランチでは行わない)

        //ここでデータを保存 
        $responseData = DatabaseHelper::createImage($title, $image, $clientIp);

        return new JSONRenderer(["response" => $image]);
    },
];