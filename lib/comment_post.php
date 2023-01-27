<?php
session_start();
require_once ('../classes/comment.php');
require_once ('./functions.php');

if (!isset($_SESSION['input_data'])) {
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit();
}

$comments = $_SESSION['input_data'];

$comment = new Comment();
$comment->CommentPost($comments);

$id = $_SESSION['input_data']['article_id'];

$_SESSION = array();
session_destroy();

switch ($id) {
    case -1:
      header('Location: ../public/chat.php?category=テレビ#box');
      break;
    case -2:
      header('Location: ../public/chat.php?category=ラジオ#box');
      break;
    case -3:
      header('Location: ../public/chat.php?category=アニメ#box');
      break;
    default:
      header("Location: ../public/detail.php?id={$id}#box");
      break;
  }
?>