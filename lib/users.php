<?php

function show_users() {

	global $mysqli;
	$sql = 'select username,playerNumber from players'; 
	$st = $mysqli->prepare($sql);
	$st->execute();
	$res = $st->get_result();
	header('Content-type: application/json');
	print json_encode($res->fetch_all(MYSQLI_ASSOC), JSON_PRETTY_PRINT);
}

function show_user($b) {
	global $mysqli;
	$sql = 'select username from players where playerNumber=?'; 
	$st = $mysqli->prepare($sql);
	$st->bind_param('s',$b);
	$st->execute();
	$res = $st->get_result();
	header('Content-type: application/json');
	print json_encode($res->fetch_all(MYSQLI_ASSOC), JSON_PRETTY_PRINT);
}

function set_user($b,$input) {
	
	if(!isset($input['username']) || $input['username']=='') {
		header("HTTP/1.1 400 Bad Request");
		print json_encode(['errormesg'=>"No username given."]);
		exit;
	}

	if($b != 1 || $b != 2){
		header("HTTP/1.1 400 Bad Request");
		print json_encode(['errormesg'=>"Player Number can be either 1 or 2."]);
		exit;

	}

	$username=$input['username'];

	global $mysqli;

	$sql = 'select count(*) as c from players where playerNumber=? and username is not null';  
	$st = $mysqli->prepare($sql);
	$st->bind_param('s',$b);
	$st->execute();
	$res = $st->get_result();
	$r = $res->fetch_all(MYSQLI_ASSOC);
	if($r[0]['c']>0) {
		header("HTTP/1.1 400 Bad Request");
		print json_encode(['errormesg'=>"Player $b is already set. Please select another Number."]);  
		exit;
	}
	$sql = 'insert into players (username, token, playerNumber) values ("?", md5(CONCAT( "?", NOW())), "?")';  
	$st2 = $mysqli->prepare($sql);
	$st2->bind_param('sss',$username,$username,$b);
	$st2->execute();


	
	update_game_status();

	$sql = 'select * from players where playerNumber=?';  
	$st = $mysqli->prepare($sql);
	$st->bind_param('s',$b);
	$st->execute();
	$res = $st->get_result();
	header('Content-type: application/json');
	print json_encode($res->fetch_all(MYSQLI_ASSOC), JSON_PRETTY_PRINT);
	
	
}

function handle_user($method, $b,$input) {
	if($method=='GET') {
		if ($b == null){
			show_users();
		}else{
			show_user($b);
		}
		
	} else if($method=='PUT') {
        set_user($b,$input);
    }else {
		header('HTTP/1.1 405 Method Not Allowed');
	}
	
}





?>