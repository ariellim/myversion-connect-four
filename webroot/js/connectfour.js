/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

var rowArray = ["#row1", "#row2", "#row3", "#row4", "#row5", "#row6", "#row7"];
var colArray = ["col1", "col2", "col3", "col4", "col5", "col6", "col7"];

var color; // player color
var count = 1; // counter for continuous elements
var winner = false; // win indicator

var indC1,
    indC2,
    indC3,
    indR1,
    indR2,
    indR3;

var user_moved = false; // was able to take turn
var oponent_moved = false; // oponent has taken turn

var sessionid = ''; // unique id
var user_assignment = 0;
var oponent_assignment = 0;
var game_started = 0; // has run
var gameboard_id = 0; // board id
var new_game = 0; // is new
var pressed_newgame = 0;
var turn = 0; // who's next

$(document).ready(function() {
	function errMsg() {
		alert("There was a system problem error!");
	}
	
	// User or Player Updater Functions
	
	function makeID()
	{
		var text = "";
		var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

		for( var i=0; i < 10; i++ )
			text += possible.charAt(Math.floor(Math.random() * possible.length));

		return text;
	}
	
	function setPlayer(status,assignment,oponent) {
		$('#player'+assignment+'-status')
				.text(status=='offline'?'Offline':(oponent?'OPONENT':'YOU'))
				.attr('class',(status=='online'?'online':'offline'));
	}
	
	function registerPlayer() {
		sessionid = $.cookie("sessionid");
		user_assignment = $.cookie("user_assignment");
		oponent_assignment = $.cookie("oponent_assignment");
		gameboard_id = $.cookie("gameboard_id");

		if(!sessionid)
		{
			sessionid = makeID();
			$.cookie("sessionid",sessionid);
		}	

		$.ajax({
			url: "server/register?sessionid="+escape(sessionid),
			method: "GET"
		})
		.fail(function() {
			errMsg();
		})
		.done(function( data ) {
			var obj = $.parseJSON(data);
			// set cookies
			$.cookie("gameboard_id",gameboard_id=obj.boardid);
			// user
			$.cookie("user_assignment",user_assignment=obj.user.assignment);
			setPlayer('online',user_assignment);
			//oponent
			$.cookie("oponent_assignment",oponent_assignment=obj.oponent.assignment);
			if(oponent_assignment!=0) // oponent online
				setPlayer('online',oponent_assignment,oponent=true); // set oponent online
			else
				setPlayer('offline',user_assignment==2?1:2,oponent=true); // set oponent offline
		});
	}
	
	function playerPing() {
		if(sessionid)
		{
			$.ajax({
				url: "server/ping?sessionid="+escape(sessionid),
				method: "GET"
			})
			.fail(function() {
				errMsg();
			})
			.done(function( data ) {
				var obj = $.parseJSON(data);
				// who's turn
				turn = obj.turn;
				$.cookie("gameboard_id",gameboard_id=obj.boardid);
				// user
				$.cookie("user_assignment",user_assignment=obj.user.assignment);
				setPlayer('online',user_assignment);
				//oponent
				$.cookie("oponent_assignment",oponent_assignment=obj.oponent.assignment);
				// fetch newgame flag
				new_game = obj.newgame;
				if(oponent_assignment!==0) // oponent online
					setPlayer('online',oponent_assignment,oponent=true); // set oponent online
				else
					setPlayer('offline',user_assignment===2?1:2,oponent=true); // set oponent offline
				
				var column = (oponent_assignment===2?obj.player2_move:obj.player1_move);
				
				if(turn === user_assignment && column && oponent_moved===false)
				{					
					checkForFree(column);
					user_moved = false;
					oponent_moved = true;
				}
				
				if(turn===2)
				{
					change1to2();
				}
				else if(turn!==0)
				{
					change2to1();
				}
				
				if(pressed_newgame==0)
				{
					if(game_started==0 || new_game==1) //start game if not yet started
					{
						if(user_assignment!=0 && oponent_assignment!=0) // Both are online
						{
							$('.notify').text('The GAME begins... Good Luck!');
							resetGameFlag();
							winner = false;
							user_moved = false;
							game_started = 1;							
						}
					}
				}
				else
				{
					pressed_newgame = 0;
				}
			});
			if(winner===false)
				$('.notify').text('');//clear notify if winner not declared
		}
	}
	
	setInterval(playerPing, 5000); // call ping every 3secs
	
	registerPlayer();
	
	// Game Logics and Algorithms
	function sendMove(col) {
		$.ajax({
			url: "server/sendmove/"+gameboard_id+'/'+user_assignment+'?col='+escape(col),
			method: "GET"
		})
		.fail(function() {
			errMsg();
		})
		.done(function( data ) {
			if(data=='received')
				user_moved = true;
				oponent_moved = false;
		});
	}
	
	function newGame() {
		$.ajax({
			url: "server/newgame/"+gameboard_id,
			method: "GET"
		})
		.fail(function() {
			errMsg();
		})
		.done(function( data ) {
			if(data=='received')
			{
				game_started = 0;
			}
		});
	}
	
	function resetGameFlag() {
		$.ajax({
			url: "server/newgame/"+gameboard_id+'/1/',
			method: "GET"
		})
		.fail(function() {
			errMsg();
		})
		.done(function( data ) {
			if(data==='received')
			{
				game_started = 1;
				$('#player1').addClass('playerTurn');
				resetGame();
			}
		});
	}

	function checkForFree(column) {

		for (var i = rowArray.length-1; i >= 0; i--) {
			if ( $(rowArray[i] + "> ." + column).hasClass('bot') ) {				
				placeColorPiece(i, column);
				checkIfTopRow(i, column);
				checkForWin(i, column);
			}
		}

	}

	function checkForWin(i, column) {
		var startRow = i;
		var startCol;


		colArray.forEach(function(elem, index) {
			if (elem === column) {
				startCol = index;
			}
		});
		var dirs = ["diag1", "diag2", "vert", "horiz"];
		for (var num = 0; num < 4; num++) {
			checkDirection(rowArray, colArray, startRow, startCol, dirs[num]);
		}
	}

	function checkDirection(row, col, r, c, dir) {

		switch (dir) {
			case "diag1":
				indC1 = indR1 = 1;
				indC2 = indR2 = 2;
				indC3 = indR3 = 3;
				break;
			case "diag2":
				indC1 = -1;
				indC2 = -2;
				indC3 = -3;
				indR1 = 1;
				indR2 = 2;
				indR3 = 3;
				break;
			case "vert":
				indC1 = indC2 = indC3 = 0;
				indR1 = 1;
				indR2 = 2;
				indR3 = 3;
				break;
			case "horiz":
				indR1 = indR2 = indR3 = 0;
				indC1 = 1;
				indC2 = 2;
				indC3 = 3;
				break;
		}

		console.log(indC1);

		if( $(row[r+indR1] + "> ." + col[c+indC1]).hasClass(color) ) {
			checkFourCount();
			if( $(row[r+indR2] + "> ." + col[c+indC2]).hasClass(color) ) {
				checkFourCount();
				if( $(row[r+indR3] + "> ." + col[c+indC3]).hasClass(color) ) {
					checkFourCount();
				} else {
					reverseDirection(row, col, r, c);
				}
			} else {
				reverseDirection(row, col, r, c);
			}
		} else {
			reverseDirection(row, col, r, c);
		}

	}

	function reverseDirection(row, col, r, c) {
		if ( $(row[r-indR1] + "> ." + col[c-indC1]).hasClass(color) ) {
			checkFourCount();
			if ( $(row[r-indR2] + "> ." + col[c-indC2]).hasClass(color) ) {
				checkFourCount();
				if ( $(row[r-indR3] + "> ." + col[c-indC3]).hasClass(color) ) {
					checkFourCount();
				} else {
					count = 1;
				}      
			} else {
				count = 1;
			}
		} else {
			count = 1;
		}
	}



	function checkFourCount() {
		count++;
		console.log("Count " + count);
		if(count === 4) {
			$('.arrows').prop('disabled', true);
			winner = true;
			count = 1;
			$('#winner').append(color.toLocaleUpperCase() + " WINS!")
				.css("color", color);
		}
	}

	function checkIfTopRow(i, column) {
		if (i !== 6) {
			$(rowArray[i+1] + "> ." + column)
			.addClass('bot');
		}
	};

	function placeColorPiece(i, column) {
		
		$(rowArray[i] + "> ." + column)
					.removeClass('bot')
					.removeClass('free');
	
		if ( $('#player1').hasClass('playerTurn') ) {
			color = 'red';
			$(rowArray[i] + "> ." + column).addClass(color);
			change1to2();
		} else {
			color = 'blue';
			$(rowArray[i] + "> ." + column).addClass(color);
			change2to1();
		}
	};

	function change1to2() {
		$('#player1').removeClass('playerTurn');
		$('#player2').addClass('playerTurn');
	};

	function change2to1() {
		$('#player2').removeClass('playerTurn');
		$('#player1').addClass('playerTurn');
	}

	function resetGame() {
		$('td').removeClass('blue')
			.removeClass('red')
			.addClass('free')
			.removeClass('bot');
		$('.arrow').removeClass('free');
		change2to1();
		$('#row1 > td').addClass('bot');
		$('#winner').css("color", '');
		winner = false;
		gamestarted = 0;
		oponent_moved = false;
	};    
    
    $('button').on('click', function() {
        if (winner === false && game_started===1 && turn === user_assignment ) {
            var column = this.id;
			turn = (turn===2?1:2);
			sendMove(column);
            checkForFree(column);
        }
    });
    
    $('#newGame').on('click', function() {
		game_started = 0;
		pressed_newgame = 1;
		resetGame();
        newGame();
    });	
});
