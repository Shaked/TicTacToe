<?php
require_once("TicTacToe.php");

if (!isset($_POST['pos1']) || !isset($_POST['pos2'])){
	die("please try to play the game..");
} 

$config = array(
				'player'	=>	Game::PLAYER,
				'move'		=>	array($_POST['pos1'],$_POST['pos2']),
				); 

//if we starting new game we don't realy have to send game board 
if (isset($_POST['gameBoard'])){
	$config['gameBoard'] = $_POST['gameBoard'];
}
$ticTacToe = new TicTacToe($config);
echo $ticTacToe;
