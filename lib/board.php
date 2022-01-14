<?php

function show_board() {
    global $mysqli;
	
	$sql = 'select * from board';
	$st = $mysqli->prepare($sql);

	$st->execute();
	$res = $st->get_result();

	header('Content-type: application/json');
	print json_encode($res->fetch_all(MYSQLI_ASSOC), JSON_PRETTY_PRINT);
}

function reset_board() {
	global $mysqli;
	
	$sql = 'call clean_board()';
	$mysqli->query($sql);
	show_board();
}

function piecePlacement($x, $y, $pieceId, $token) {

	if($token==null || $token=='') {		//check if user exists
		header("HTTP/1.1 400 Bad Request");
		print json_encode(['errormesg'=>"token is not set."]);
		exit;
	}
	
	global $mysqli;

	$sql = `select piece_id from board where x = ? AND y = ?`;  //check if place on board is empty
	$st = $mysqli->prepare($sql);
	$st->bind_param('a',$x,$y);
	$st->execute();
	$res = $st->get_result();

	if($res[0] != null) {
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

	show_board();
	checkEndGame();
	
}

function choosePiece($pturn, $pieceId){

	global $mysqli;

	$sql = `update pieces set Player = if(? = 1, 2, 1) where id = ? `;
	$st = $mysqli->prepare($sql);
	$st->bind_param('a',$pturn,$pieceId);
	$st->execute();


}

function checkEndGame(){

	global $mysqli;


	//check win condition (4 pieces with a common atribute in a row)





	$sql = `select count(*) as c from pieces`;
	$st = $mysqli->prepare($sql);
	$st->execute();
	$res = $st->get_result();
	$numOfPiecesAvailiable = $res->fetch_assoc()['c'];
	
	if($numOfPiecesAvailiable == 0){
		print('The board is full!');
	}

	

}


?>