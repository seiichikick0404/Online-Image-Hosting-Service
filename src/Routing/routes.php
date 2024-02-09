<?php
session_start();

use Helpers\DatabaseHelper;
use Helpers\ValidationHelper;
use Response\HTTPRenderer;
use Helpers\CreateSnippetHelper;
use Response\Render\HTMLRenderer;
use Response\Render\JSONRenderer;



return [
    'snippet/create'=>function(): HTTPRenderer{
        $syntaxes = DatabaseHelper::getSyntaxes();
        $errors = isset($_SESSION['errors']) ? $_SESSION['errors'] : null;
        unset($_SESSION['errors']);

        return new HTMLRenderer('component/createSnippet', ['syntaxes' => $syntaxes, 'errors' => $errors]);
    },
    'snippet/library'=>function(): HTTPRenderer{
         $snippets = DatabaseHelper::getActiveSnippets();
         return new HTMLRenderer('component/library', ['snippets' => $snippets]);
    },
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
    'snippet/show'=>function(): HTTPRenderer{
        $path = $_GET['uniqueKey'] ?? null;

        if (!$path) {
            header("Location: create");
            exit;
        }
        $path = ValidationHelper::string($path);
        $data = DatabaseHelper::getSnippet($path);

        return new HTMLRenderer('component/show', ['data' => $data]);
   },
    'api/parts'=>function(){
        $id = ValidationHelper::integer($_GET['id']??null);
        $part = DatabaseHelper::getComputerPartById($id);
        return new JSONRenderer(['part'=>$part]);
    },
    'top'=>function(){
        return new HTMLRenderer('component/createImage');
    },
];