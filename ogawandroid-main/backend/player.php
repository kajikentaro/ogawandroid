


<!DOCTYPE html>
<html lang="jp">
    <head>
        <meta charset="UTF-8">
        <title>movie player</title>
    </head>
    <body>
        <header>
        <div class="logo">Youtube Player</div>
        </header>
        <div class="contents">
        <div class="movie-wrapper">
        <div class="movie">Youtube Player</div>
	</div><div class="comment-wrapper">
	    <div class="comment-stream">
		<h2>コメント欄</h2>
	    </div>
	    <div class="comment-input-wrapper">
                    <input class="comment-input">
		    <button class="comment-send-button">送信</button>
               </div>
        </div></div>
    </body>
    <style>
        .contents{
            margin:0px auto;
            width:1010px;
            background-color: blue;
        }
        .comment-input-wrapper{
            background-color: red;
            width:100%;
        }
        .comment-input{
            width:50%;
            margin:0px;
            display:inline-block;
        }
        .comment-send-button{
            width:30%;
            margin:0px;
            display:inline-block;
        }
        .comment-stream{
            height:500px;
            width:90%;
            margin:10px auto;
            background-color:aliceblue;
        }
        .comment-input{
            width:90%;
            margin:20px auto;
        }
        .movie{
            margin:30px auto;
            height:300px;
            width:600px;
            background-color:black;
        }
        .movie-wrapper{
            width:70%;
            margin:0px;
            background-color: #ddd;
            display: inline-block;
            box-sizing: border-box;
            vertical-align: top;
        }
        .comment-wrapper{
            margin:0px;
            width:30%;
            height:600px;
            background-color: aqua;
            display: inline-block;
            box-sizing: border-box;
            vertical-align: top;
        }
        header{
            background-color:#c4302b;
        }
        body{
            margin:0px;
        }
        .logo{
            text-align:center;
            margin:0px;
            font-size:40px;
            color:white;
        }
    </style>
</html>
