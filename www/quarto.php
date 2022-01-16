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
        $input['token']='';   // ='1' for debugging
    }



switch ($r=array_shift($request)) {     
    case 'board' : 
                    switch ($b=array_shift($request)) {
                                case '':
                                case null: handle_board($method, $input);   
                                                break;
                                case 'piece': handle_piece($method, $request[0],$request[1], $request[2], $input);
                                                break;
                                default: header("HTTP/1.1 404 Not Found");
                                                break;
         }
    case 'player':  handle_player($method, $request[0],$input);
        break;
    case 'status':      
                    handle_status($method, $input);  
         break;
    default:  header("HTTP/1.1 404 Not Found");
                        exit;
}

function handle_board($method, $input) {   
 
        if($method=='GET') {
                show_board($input);       
        } else if ($method=='POST') {
                reset_board($input);      
        }else {
                header('HTTP/1.1 405 Method Not Allowed');
        }
}

function handle_piece($method, $x,$y, $piece_id,$input) {

        if(($y != null || $y != '') && ($piece_id != null || $piece_id != '')){
                if($method=='GET' && $piece_id == null) {
                        show_piece($x, $y, $input);     
                } else if ($method=='PUT') {
                        piecePlacement($x, $y, $piece_id, $input);      
                }else {
                        header('HTTP/1.1 405 Method Not Allowed');
                }
        }elseif($method=='PUT'){
                piecePlacement(null, null, $x, $input);   // first move requires only piece_id
        }else{
                header('HTTP/1.1 400 Bad Request');
                exit;
        }

        
}
 
function handle_player($method, $p,$input) {
         
         handle_user($method, $p,$input);
        
}


function handle_status($method,$input) {       
        if($method=='GET') {
            show_status($input);
        } else {
            header('HTTP/1.1 405 Method Not Allowed');
        }
    }

?>