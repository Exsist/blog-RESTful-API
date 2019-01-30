<?php
//$connection = mysqli_connect('127.0.0.1', 'kpk', 'pass', 'park'); // PDO
//mysqli_set_charset($connection, "utf8");

require_once 'api/functions.php';
//define('API_PATH', "/kpk-m1wsrru/");
define('API_PATH_PATTERN', "#^/kpk-m1wsrru/(?P<action>.*)$#");

//define(API_PATH, "/");

$parsed_url = parse_url($_SERVER['REQUEST_URI']);



if (!(isset($parsed_url['path']) && preg_match(API_PATH_PATTERN, $parsed_url['path'], $api_url_suffix) == 1)) {
    $response = [];
    $response['status'] = 'wrong';
    $response['errors'] = 'error API access!';
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}

if (isset($api_url_suffix['action'])) {
    $response = [];
    $response['status'] = 'unknown request';


    preg_match('#^posts/(?P<post_id>\d+)$#', $api_url_suffix['action'], $tmp);
    $post_id = (isset($tmp['post_id'])) ? $tmp['post_id'] : null;

    preg_match('#^posts/(?P<post_id>\d+)/comments$#', $api_url_suffix['action'], $tmp);
    $post_id_comments = (isset($tmp['post_id'])) ? $tmp['post_id'] : null;


    preg_match('#^posts/(?P<post_id>\d+)/comments/(?P<comment_id>\d+)$#', $api_url_suffix['action'], $tmp);
    $post_id_comments_post_id = (isset($tmp['post_id'])) ? $tmp['post_id'] : null;
    $post_id_comments_comment_id = (isset($tmp['comment_id'])) ? $tmp['comment_id'] : null;

    preg_match('#^posts/tag/(?P<tag_name>\w+)$#', $api_url_suffix['action'], $tmp);
    $post_tag_name = (isset($tmp['tag_name'])) ? $tmp['tag_name'] : null;

    switch ($api_url_suffix['action']) {
        case 'auth':
            // будет код авторизации
            $response['status'] = 'auth';
            $response['errors'] = '';
            break;

        case 'posts':
            // создание постов
            $response['status'] = 'posts';
            $response['errors'] = '';
            break;

        case 'post_images':
            // загрузка изображения
            $response['status'] = 'post_images';
            $response['errors'] = '';
            break;

        case $post_id != null:
            // чтение постов
            $response['status'] = 'posts/<POST_ID>';
            $response['post_id'] = $post_id;
            $response['errors'] = '';
            break;

        case $post_id_comments != null:
            // чтение коментария к посту
            $response['status'] = 'posts/<POST_ID>/comments';
            $response['post_id'] = $post_id_comments;
            $response['author'] = $author_id ;
            $response['comment'] = $comment_posts ;  // \w[0-255]
            $response['errors'] = '';
            break;

        case $post_id_comments == null:
            // чтение коментария к посту
            $response['status'] = false;
            $response['message'] = ;  // \w[0-255]
            $response['errors'] = '';
            break;

        case $post_id_comments_post_id != null && $post_id_comments_comment_id != null :
            //  чтение id коментария к посту
            $response['status'] = 'posts/<POST_ID>/comments/<COMMENT_ID>';
            $response['post_id'] = $post_id_comments_post_id;
            $response['comment_id'] = $post_id_comments_comment_id;
            $response['errors'] = '';
            break;

        case $post_tag_name != null:
            // чтение постов
            $response['status'] = 'posts/tag/<TAG_NAME>';
            $response['tag_name'] = $post_tag_name;
            $response['errors'] = '';
            break;


    }
    header('Content-Type: application/json');
    echo json_encode($response);
}
//$response = [];
//$response['status'] = 'ok';
//$response['errors'] = '';
//$response['api_url_suffix'] = $api_url_suffix;
//echo json_encode($response);


// работаем дальше
//print_r($parsed_url);


//$page = (isset($_GET['page'])) ? $_GET['page'] : 'main';
//$action = (isset($_GET['action'])) ? $_GET['action'] : null;
//
//switch ($action) {
//    case 'login':
//        $remember_me = isset($_POST['remember']);
//        $username = (isset($_POST['uname'])) ? $_POST['uname'] : '';
//        $password = (isset($_POST['psw'])) ? md5($_POST['psw']) : '';
//        $login_query = "
//            SELECT id, first_name, last_name
//            FROM auth_user
//            WHERE username='$username' AND password='$password' AND is_active='1'
//        ";
//        $login_response = mysqli_query($connection, $login_query);
//        if ($login_response && $login_response->num_rows == 1) {
//            $user = mysqli_fetch_assoc($login_response);
//            if ($remember_me) {
//                setcookie('first_name', $user['first_name'], time() + 3600 * 24 * 14);
//                setcookie('last_name', $user['last_name'], time() + 3600 * 24 * 14);
//                setcookie('user_id', $user['id'], time() + 3600 * 24 * 14);
//            } else {
//                $_SESSION['first_name'] = $user['first_name'];
//                $_SESSION['last_name'] = $user['last_name'];
//                $_SESSION['user_id'] = $user['id'];
//            }
//            header("location: /");
//        }
//        break;
//    case 'logout':
//        setcookie('first_name', '', time()- 3600 * 24);
//        setcookie('last_name', '', time() - 3600 * 24);
//        setcookie('user_id', '', time() - 3600 * 24);
//        session_destroy();
//        header("location: /"); // переходим на главную
//        break;
//    case 'show_talk':
//        $recipient_id = (isset($_GET['chat_member_id'])) ? $_GET['chat_member_id'] : null;
//        $author_id = get_user_id();
//        if ($recipient_id && $author_id) {
//            $talk_query = "
//                SELECT *
//                FROM chat
//                WHERE
//                    recipient_id IN ('$recipient_id', '$author_id') AND
//                    author_id IN ('$recipient_id', '$author_id')
//                ORDER BY created DESC
//            ";
//            $talk_response = mysqli_query($connection, $talk_query);
//        }
//        break;
//    case 'send_message':
//        $message_text = (isset($_POST['text'])) ? $_POST['text'] : '';
//        $author_id = (isset($_POST['author'])) ? $_POST['author'] : null;
//        $recipient_id = (isset($_POST['recipient'])) ? $_POST['recipient'] : null;
//        if ($author_id && $recipient_id) {
//            $send_message_query = "
//                INSERT INTO chat
//                    (text, recipient_id, author_id)
//                VALUES
//                    ('$message_text', '$recipient_id', '$author_id');
//            ";
//            $send_message_response = mysqli_query($connection, $send_message_query);
//            header("location: /?action=show_talk&chat_member_id=$recipient_id");
//        }
//        break;
//    case 'get_active_talk':
//        if (isset($_GET['chat_member_id'])) {
//            $recipient_id = $_GET['chat_member_id'];
//            $author_id = get_user_id();
//            if ($recipient_id && $author_id) {
//                include "../templates/chat_messages_ajax.html";
//                exit;
//            }
//        }
//        break;
//    case 'get_message_form':
//        if (isset($_GET['chat_member_id'])) {
//            $recipient_id = $_GET['chat_member_id'];
//            $author_id = get_user_id();
//            if ($recipient_id && $author_id) {
//                include "../templates/chat_form_ajax.html";
//                exit;
//            }
//        }
//        break;
//    case 'get_chat_members':
//        include "../templates/chat_members_ajax.html";
//        exit;
//        break;
//    case 'user_register':
//        $username = (isset($_POST['username'])) ? $_POST['username'] : null;
//        $first_name = (isset($_POST['first_name'])) ? $_POST['first_name'] : '';
//        $last_name = (isset($_POST['last_name'])) ? $_POST['last_name'] : '';
//        $password1 = (isset($_POST['password1'])) ? $_POST['password1'] : null;
//        $password2 = (isset($_POST['password2'])) ? $_POST['password2'] : null;
//        $email = (isset($_POST['email'])) ? $_POST['email'] : '';
//        //$avatar = (isset($_POST['avatar'])) ? $_POST['avatar'] : '';
//        $age = (isset($_POST['age'])) ? $_POST['age'] : null;
//        $register_errors = '';
//        if ($username && $age) {
//            if (intval($age) < 18) {
//                $register_errors .= "вы слишком молоды<br>";
//            }
//            if ($password1 != $password2) {
//                $register_errors .= "пароли не совпадают<br>";
//            }
//            $check_username_query = "
//                SELECT username, email
//                FROM auth_user
//                WHERE username='$username' OR email='$email'
//            ";
//            $check_username_response = mysqli_query($connection, $check_username_query);
//            if ($check_username_response->num_rows > 0) {
//                while ($row = mysqli_fetch_assoc($check_username_response)) {
//                    if (isset($row['username'])) {
//                        $register_errors .= "пользователь {$row['username']} уже существует<br>";
//                    }
//                    if (isset($row['email'])) {
//                        $register_errors .= "адрес {$row['email']} уже зарегистрирован<br>";
//                    }
//                }
//            }
//            if ($register_errors === '') {
//                $password = md5($password1);
//                $register_query = "
//                    INSERT INTO auth_user
//                        (username, first_name, last_name, password, email, age, is_active)
//                    VALUES
//                        ('$username', '$first_name', '$last_name', '$password', '$email', '$age', '1');
//                ";
//                $register_response = mysqli_query($connection, $register_query);
//                if ($register_response) {
//                    header("location: /");
//                    exit;
//                }
//            }
//        }
//        header("location: /?page=auth_register&errors={$register_errors}");
//        break;
//}
//
//include '../templates/header.html';
//include "../templates/$page.html";
//
//if ($page == 'main' && is_authenticated()) {
//    include "../templates/chat_window.html";
//}
//
//include '../templates/footer.html';

?>