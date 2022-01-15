<?php

function show_board($input) {
    global $mysqli;
	
	$token= $input['token'];

	if($token==null || $token=='') {		//check if user exists
		header("HTTP/1.1 400 Bad Request");
		print json_encode(['errormesg'=>"token is not set."]);
		exit;
	}

	$sql = 'select * from board';	
	$st = $mysqli->prepare($sql);
	$st->execute();
	$res = $st->get_result();

	header('Content-type: application/json');
	print json_encode($res->fetch_all(MYSQLI_ASSOC), JSON_PRETTY_PRINT);
	
}


function reset_board($input) {
	global $mysqli;
	
	$sql = 'call clean_board()';
	$mysqli->query($sql);
	show_board($input);
}

function piecePlacement($x, $y, $pieceId, $input) {

	$token= $input['token'];

	if($token==null || $token=='') {		//check if user exists
		header("HTTP/1.1 400 Bad Request");
		print json_encode(['errormesg'=>"token is not set."]);
		exit;
	}
	
	global $mysqli;

	$sql = `select piece_id from board where x = ? AND y = ?`;
	$st = $mysqli->prepare($sql);
	$st->bind_param('a',$x,$y);
	$st->execute();
	$res = $st->get_result();

	if($res[0] != null) {										//check if place on board is empty
		print('This place on the board is not empty');
		
		header("HTTP/1.1 400 Bad Request");
		exit;
	}

	$sql = `select p_turn from game_status `;
	$st = $mysqli->prepare($sql);
	$st->execute();
	$res = $st->get_result();
	
	$pturn = $res[0];

	choosePiece($pturn, $pieceId);  //choosing piece for the opponent

//	Is it the first move?

	$sql = `select count(*) as c from pieces`;
	$st = $mysqli->prepare($sql);
	$st->execute();
	$res = $st->get_result();
	$numOfPiecesAvailiable = $res->fetch_assoc()['c'];
	
	if($numOfPiecesAvailiable == 16)	//if all the pieces are availiable then this is the first move
		exit;							//In the first round player 1 just picks a piece for player 2 to place

	$sql = `select id from pieces where Player = if(? = 1, 1, 2) `;
	$st = $mysqli->prepare($sql);
	$st->bind_param('a',$pturn);
	$st->execute();
	$res = $st->get_result();

	$pid = $res[0];

	$sql = 'call place_piece(?,?,?)';  //place piece chosen by opponent
	$st = $mysqli->prepare($sql);
	$st->bind_param('a',$pid,$x,$y);
	$st->execute();

	show_board($input);
	checkEndGame();
	
}

function choosePiece($pturn, $pieceId){

	global $mysqli;

	$sql = `update pieces set Player = if(? = 1, 2, 1) where id = ? `;
	$st = $mysqli->prepare($sql);
	$st->bind_param('a',$pturn,$pieceId);
	$st->execute();


}


function show_piece($x, $y, $input) {
	global $mysqli;
	
	$token= $input['token'];

	if($token==null || $token=='') {		//check if user exists
		header("HTTP/1.1 400 Bad Request");
		print json_encode(['errormesg'=>"token is not set."]);
		exit;
	}

	$sql = 'select * from board where x=? and y=?';
	$st = $mysqli->prepare($sql);
	$st->bind_param('ii',$x,$y);
	$st->execute();
	$res = $st->get_result();


	header('Content-type: application/json');
	print json_encode($res->fetch_all(MYSQLI_ASSOC), JSON_PRETTY_PRINT);

}

function read_board(){

	global $mysqli;	

	$sql = 'select x, y, piece_id, White, Square, Tall, Hollow 
		from board left join pieces on board.piece_id=pieces.id';
		$st = $mysqli->prepare($sql);
	    $st->execute();
	    $res = $st->get_result();
		$res->fetch_all(MYSQLI_ASSOC);

		return $res;
}

function convert_board(&$orig_board) {
	$board=[];
	foreach($orig_board as $i=>&$row) {
		$board[$row['x']][$row['y']] = &$row;
} 
	return($board);
}

function check_pieces($a,$b,$c,$d){

	if($a['piece_id']==null || $b['piece_id']==null || $c['piece_id']==null || $d['piece_id']==null){
		return false;
	}

	if($a['White'] == $b['White'] && $a['White'] == $c['White'] && $a['White'] == $d['White']){
		if($a['White'])
			print('4 White Pieces in: \n ');
		else
			print('4 Black Pieces in: \n ');
		return true;
	}
	elseif($a['Square'] == $b['Square'] && $a['Square'] == $c['Square'] && $a['Square'] == $d['Square']){
		if($a['Square'])
			print('4 Square Pieces in: \n ');
		else
			print('4 Round Pieces in: \n ');
		return true;
	}
	elseif($a['Tall'] == $b['Tall'] && $a['Tall'] == $c['Tall'] && $a['Tall'] == $d['Tall']){
		if($a['Tall'])
			print('4 Tall Pieces in: \n ');
		else
			print('4 Short Pieces in: \n ');
		return true;
		
	}
	elseif($a['Hollow'] == $b['Hollow'] && $a['Hollow'] == $c['Hollow'] && $a['Hollow'] == $d['Hollow']){
		if($a['Hollow'])
			print('4 Hollow Pieces in: \n ');
		else
			print('4 Full (Not Hollow) Pieces in: \n ');
		return true;
	}

	return false; //no winning condition
}

function checkEndGame(){

	global $mysqli;

	$orig_board=read_board();
	$board=convert_board($orig_board);

	//check win condition (4 pieces with a common atribute in a row)
	
	for($i=1;$i<=count($board);$i++){
			$result=check_pieces($board[$i][1],$board[$i][2],$board[$i][3],$board[$i][4]);
			if($result){
				print('row '.$i);
				return true;
			}
		}

		for($i=1;$i<=count($board);$i++){
			$result=check_pieces($board[1][$i],$board[2][$i],$board[3][$i],$board[4][$i]);
			if($result){
				print('column '.$i);
				return true;
			}
		}
		 
		//checks the first diagonal
		$result=check_pieces($board[1][1],$board[2][2],$board[3][3],$board[4][4]);
		if($result){
			print('the first diagonal');
			return true;
		}

		//checks the second diagonal
		$result=check_pieces($board[1][4],$board[2][3],$board[3][2],$board[4][1]);
		if($result){
			print('the second diagonal');
			return true;
		}
		
	
	//check if the board is full (if all the pieces are placed)

	$sql = `select count(*) as c from pieces`;
	$st = $mysqli->prepare($sql);
	$st->execute();
	$res = $st->get_result();
	$numOfPiecesAvailiable = $res->fetch_assoc()['c'];
	
	if($numOfPiecesAvailiable == 0){
		print('The board is full!');
		return true;
	}

	return false;

}


?>