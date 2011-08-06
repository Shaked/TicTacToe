<html>
<head>
<style>
	.center{ text-align:center; margin-bottom: 8px; font-size: 25px;}
	body { 		
		font-family: Verdana;
		background: #CCCCBF
	}
	table { 
		border: 5px solid #DDDDCC; 
		width: 500px; 
		height: 500px; 
		text-align:center
	}  
	tr,td { 
		width: 100px;
		height: 50px;
		border: 8px solid #DFDFDF; 
	}
	td{ 
		color: #EEEFFF;
		font-size:28px; 

		margin: 0; 
		padding:0;
		font-weight: bold;
	}
	
	div#restart { 
		width:300px; 
		margin: 0 auto; 
		text-align: center; 
		font-size: 20px; 
		border-right: 1px solid gray; 
		border-bottom: 1px solid gray; 
		border-top: 1px solid #EFEFEF;
		border-left:  1px solid #EFEFEF;	
		margin-bottom: 5px;
		cursor: hand;
		cursor: pointer;
	} 
	
	div#restart.clicked { 
		border-right: 1px solid #EFEFEF; 
		border-bottom: 1px solid #EFEFEF; 
		border-top: 1px solid gray;
		border-left:  1px solid  gray;	
	}
</style>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js" type="text/javascript"></script>
<script type="text/javascript">
	var game = {}; 
	$(document).ready(function(){
		function initGame(){ 
			game.gameBoard 	 = [['','',''],['','',''],['','','']];
			game.players = {1:"X",2:"O"}; 
			game.isClick = 0;
			game.toggleBoardClick = function(){ 
				if (0 == this.isClick){ 
					this.enableBoardClick(); 
					this.isClick = 1;
					return ;  
				};
				this.disableBoardClic(); 
				this.isClick = 0; 
			};  
			game.getPlayer = function(p){    
				if (isNaN(p)){ 
					return (game.players[1] == p)? 1:2; 
				}; 
				return (1 == p)? game.players[1]:game.players[2];
			}; 

			game.getCurrentBoard = function(){
				$('td').each(function(){
					var $This = $(this),player = $This.text();
					var pos1 = parseInt($This.parent().attr('id').replace('r','')), pos2=parseInt($This.attr('class').replace('t',''));				
					if (player != ''){
						player = game.getPlayer(player);
						game.gameBoard[pos1][pos2] = player; 
					}; 
				}); 
				return this.gameBoard; 
			};

			game.setPosition = function(pos1,pos2,player,obj){
				this.gameBoard[pos1][pos2] = this.getPlayer(player); 
				if (2 == this.gameBoard[pos1][pos2]){ 
					$('td').each(function(key,val){
						if (pos1 == $(this).parent().attr('id').replace('r','') && pos2 == $(this).attr('class').replace('t','')){
							$(this).text(player); 
							return ; 
						};
					});	
				} else { 
					obj.text(player);
				}; 
			};

			game.enableBoardClick = function(){
				$('td').unbind('click').click(function(){
				var $This = $(this); 
					if ($This.text() == ''){
						var pos1 			=	parseInt($This.parent().attr('id').replace('r','')),
							pos2			=	parseInt($This.attr('class').replace('t','')),
							currentBoard	= 	$.extend(true,{},game.getCurrentBoard());
						game.disableBoardClick('you can\'t click on the board while the computer is thinking.. ');
						game.setPosition(pos1,pos2,game.players[1],$This); 
						$.ajax({
						   type: "POST",
						   url: "Runner.php",
						   data: {pos1:pos1,pos2:pos2,gameBoard:currentBoard},
						   success: function(msg){
								var jobj = JSON.parse(msg); 
								if (jobj.move){
									game.setPosition(jobj.move[0],jobj.move[1],game.players[2]); 
									game.enableBoardClick();
								};
								if (jobj.errorMessage){ 
									switch(jobj.errorMessage[0]){
										case 400: 
											alert("no one won, its a draw!");
										break; 
										default: 
											setTimeout(function(){
												winner = game.getPlayer(jobj.errorMessage); 
												if (confirm("Player " + winner + " won, do you want to try again?")){
													window.location.reload(true); 
												};
												game.disableBoardClick("Game is over, the winner is: " + winner);
											},500); 
									};
								};
						   }
						 }); 
					};
				});
			}; 
			
			game.disableBoardClick = function(text){
			
				$('td').unbind('click').click(function(){ 
					alert(text);
				}); 
			};
		};	 
		

		
		initGame();
		
		game.enableBoardClick();
		
		$("#restart").mousedown(function(){
			$(this).addClass('clicked'); 
		}).mouseup(function(){
			window.location.reload(true);
		}); 
	});
</script> 
</head>
<body>
	<div class="center">
		<a href="https://github.com/Shaked/TicTacToe/tree/master/TicTacToe" target="_blank">
			GitHub - TicTacToe MinMax implementation	
		</a> 
	</div>
	<div id="restart">
		Restart Game 
	</div>
	<table align="center">
		<tr id="r0">
			<td class="t0"></td>
			<td class="t1"></td>
			<td class="t2"></td>
		</tr>
		<tr id="r1">
			<td class="t0"></td>
			<td class="t1"></td>
			<td class="t2"></td>
		</tr>
		<tr id="r2">
			<td class="t0"></td>
			<td class="t1"></td>
			<td class="t2"></td>
		</tr>
	</table>
</body>
</html>