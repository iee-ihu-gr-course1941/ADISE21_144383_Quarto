<?php

require_once "../lib/dbconnect.php";
require_once "../lib/board.php";
require_once "../lib/game.php";
require_once "../lib/users.php";

$method = $_SERVER['REQUEST_METHOD'];
$request = explode('/', trim($_SERVER['PATH_INFO'],'/'));
$input = json_decode(file_get_contents('php://input'),true);

if($input==null) {
        $input=[];
    }
if(isset($_SERVER['HTTP_X_TOKEN'])) {
        $input['token']=$_SERVER['HTTP_X_TOKEN'];
} else {
        $input['token']='1';   // ='1' for debugging
    }



switch ($r=array_shift($request)) {     //na prosthesw to $input ws parametro
    case 'board' : 
                    switch ($b=array_shift($request)) {
                                case '':
                                case null: handle_board($method, $input);   //na prosthesw to $input ws parametro
                                                break;
                                case 'piece': handle_piece($method, $request[0],$request[1],$input);
                                                break;
                                case 'player': 
                                                break;
                                default: header("HTTP/1.1 404 Not Found");
                                                break;
         }
    case 'player':  handle_player($method, $request[0],$input);
        break;
    case 'status':      
                    handle_status($method, $input);  //na prosthesw to $input ws parametro
         break;
    default:  header("HTTP/1.1 404 Not Found");
                        exit;
}

function handle_board($method, $input) {    //na prosthesw to $input ws parametro
 
        if($method=='GET') {
                show_board($input);       //na prosthesw to $input ws parametro
        } else if ($method=='POST') {
                reset_board($input);      //na prosthesw to $input ws parametro
        }
}

function handle_piece($method, $x,$y,$input) {
        ;
}
 
function handle_player($method, $p,$input) {
         
         handle_user($method, $p,$input);
        ;
}


function handle_status($method,$input) {       //na prosthesw to $input ws parametro
        if($method=='GET') {
            show_status($input);
        } else {
            header('HTTP/1.1 405 Method Not Allowed');
        }
    }

?>