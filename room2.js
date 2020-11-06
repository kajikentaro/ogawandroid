var URL_SEND_NEWMOVIE = "http://54.238.75.103/app/backend/insert_url.php";
var URL_JUDGE_MOVIE_REQUEST = "http://54.238.75.103/app/backend/select_url.php";
var URL_GET_COMMENT = "http://54.238.75.103/app/backend/insert_url.php";
var URL_GET_PLAYING_URL = "http://54.238.75.103/app/backend/playing_url.php";
var URL_GET_PLAYING_TIME = "http://54.238.75.103/app/backend/time_keeper.php";
var currentURL;
var V_id ;
var player;
var YoutubeLength;
var currentTime = 0;
var nextURL;
const query = location.search;
const value = query.split('=');
var tag = decodeURIComponent(value[1]);//index.htmlからget通信で受け取った変数
////console.log("saito:"+"saito:"+tag);
/*tagが定義されていないとメインページに戻る仕様。開発中は無視しておｋ
if (tag === "undefined") window.location.href="index.html";
else  document.getElementById("tagname").innerHTML = tag ;*/

function URLToId(url) {
	var tmp =  url.split("=")[1];
	if(tmp ==void 0){
		tmp = url.split("/");
		tmp = tmp[tmp.length-1];
	}
	if(tmp == void 0){
		echo("URLからIDを取得できません");
	}
	return tmp;
}
function unmute() {
	player.unMute();
}

var current_movie_info  
//このチャンネルで再生中の動画を取得するPOST通信が終わったら呼ばれる。
var start_play = function(d){
	if(d[0] ==void 0){
		console.log("current urlを取得できません。tagが指定されていない可能性があります");
	}
	current_movie_info = d[0];
	currentURL = d[0]['url'];//URLをセット
	nextURL = d[0]['url'];//nextURLにもセットしておく＼_(・ω・`)ｺｺ重要!
	V_id = URLToId(currentURL);
	//API読み込み↓
	var tt = document.createElement('script');
	tt.src = "https://www.youtube.com/iframe_api";
	var firstScriptTag = document.getElementsByTagName('script')[0];
	firstScriptTag.parentNode.insertBefore(tt, firstScriptTag);
}

//このチャンネルで再生中の動画を取得するPOST通信。start_play関数よりあとにないとだめっぽい
sendPostXHR(URL_GET_PLAYING_URL, "fill", "hoge",tag,start_play);

function LoadYoutube(StartTime = 0) {
	player = new YT.Player('screen', {
		videoId: URLToId(currentURL),
		playerVars: {
			'autoplay': 1,
			'controls': 0,
			'disablekb': 1,
			'iv_load_policy': 3,
			'rel': 0,
			'modestbranding': 1,
			'start': StartTime
		},
		events: {
			'onReady': onPlayerReady,
			'onStateChange': onPlayerStateChange,
		}
	});
}
//APIが読み込まれたら実行される関数。StartTimeを取得して参加
function onYouTubeIframeAPIReady() {
	/*
	sendPostXHR(URL_GET_PLAYING_TIME, "time", "-1", tag,(d)=>{
		StartTime = Number(d[0]['time']);
		console.log(player.getDuration());
		LoadYoutube(StartTime);
	});
	*/
	var date = new Date();
	var now_unix = Math.floor( date.getTime() / 1000 ) ;
	LoadYoutube(now_unix - Number(current_movie_info['time']));
}
function onPlayerReady(event) {
	player.mute();//ミュートにしてから再生しないといけないらしい
	event.target.playVideo();//再生
	YoutubeLength = player.getDuration();//動画の長さ
	//console.log("saito:"+YoutubeLength);
	onload_flag = true;
}
/*player.getPlayerState()
-1 – 未開始
0 – 終了
1 – 再生中
2 – 一時停止
3 – バッファリング中
5 – 頭出し済み
*/
function onPlayerStateChange(event) {
	if (player.getPlayerState() == 2) {
		event.target.playVideo();
		//止めさせない
	}
	if (player.getPlayerState() == 0) {
		var date = new Date();
		var now_unix = Math.floor( date.getTime() / 1000 ) ;
		if((now_unix-Number(current_movie_info['time'])-player.getDuration()) > 0){
			console.log("動画の再生時間を超えています");
		}
		/////////////////////////////////////////////////////////////////
		//返り値は何でもいい。ここでflagをtrueにする。
		//var any = sendPostXHR(URL_JUDGE_MOVIE_REQUEST, "current_url", currentURL,tag);　//start_message >> flag=true
		/////////////////////////////////////////////////////////////////
	}
}
var next_player_info;
var flag_waiting_nextURL =true;
var judge_method = function () {
	if(player.getCurrentTime == void 0){
		console.log("player.getCurrentTime is null!!");
		currentTime = 0;
	}else{
		currentTime = parseInt(player.getCurrentTime());
	}
	//console.log("saito:"+player.getVideoLoadedFraction());
	//console.log("saito:"+"state" + player.getPlayerState());
	//console.log("saito:"+currentTime + "/" + YoutubeLength);
	//console.log("saito:"+player.getPlaybackRate());
	//返り値は適当でいい。1秒ごとにPOSTで現在の動画時間を送る
	//var any = sendPostXHR(/*phpURL*/0, "currentTime", currentTime);
	//nextURLが更新されていない、かつ、動画の残り時間10秒のとき
	if (flag_waiting_nextURL&&nextURL == currentURL && YoutubeLength - currentTime < 10) {
		flag_waiting_nextURL = false;
		console.log("judge movie request happend");
		var t = new Date();
		//console.log("saito:"+sendPostXHR(URL_JUDGE_MOVIE_REQUEST, "current_url", currentURL,tag));　//start_message >> flag=true
		sendPostXHR(URL_JUDGE_MOVIE_REQUEST, "current_url",currentURL ,tag,()=>{
			sendPostXHR(URL_GET_PLAYING_URL, "fill", "hoge",tag,(response)=>{
			nextURL = response[0]['url'];
			next_player_info = response;
		});
		});
		/////////////////////////////////////////////////////////////////
		//backend側での処理
		//1. 最初に来たエンドメッセージのリクエストでランダムにURLを選定。flag=falseにする。DBのcurrentURLを書き換える
		//2. その後にくるリクエストはflagではじいて、currentURLを返す。
		//3. 次の動画が始まるときにスタートメッセージを送るので、そのときにflag=trueをする.
	}
}
var onload_flag = false;
//setInterval(()=>{
	if(onload_flag){
		judge_method();
	}}, 1000);


//（送信先のディレクトリ、サーバーで受け取ったときの変数の名前、変数の中身、タグ名）
function sendPostXHR(url,name,data,tag,postfunc){
	var request = createXmlHttpRequest();
	request.open('POST', url);
	request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	request.addEventListener('load', function (response) {
		//console.log("response:"+this.responseText);
		var result = JSON.parse(this.responseText);
		postfunc(result);
	});
	request.send(name+"="+data+"&tag="+tag);
}
function createXmlHttpRequest() { var xmlhttp=null; if(window.ActiveXObject) { try { xmlhttp=new ActiveXObject("Msxml2.XMLHTTP"); } catch(e) { try { xmlhttp = new ActiveXObject("Microsoft.XMLHTTP"); } catch (e2) { } } } else if(window.XMLHttpRequest) { xmlhttp = new XMLHttpRequest(); } return xmlhttp; } 

function send_url() {
	var sendURL = document.getElementById("URL").value;
	sendPostXHR(URL_SEND_NEWMOVIE, "url", sendURL, tag,()=>{
		message = "送信に成功しました.";
		document.getElementById("result_URLsend").innerHTML = message;
	});
}
//コメントの処理
function add_comment(text) {
	var newElement = document.createElement("p"); // p要素作成
	var newContent = document.createTextNode(text); // テキストノードを作成
	newElement.appendChild(newContent); // p要素にテキストノードを追加
	newElement.setAttribute("id", "child-p1"); // p要素にidを設定
	var parentDiv = document.getElementById("comment_content");
	parentDiv.insertBefore(newElement, parentDiv.firstChild);
}
var conn = new WebSocket('ws://54.238.75.103:8080');
conn.onopen = function (e) {
	console.log("connection for comment established!");
};
conn.onmessage = function (e) {
	add_comment(e.data);
};
function send_comment() {
	text = document.getElementById("com_text").value;
	document.getElementById("com_text").value = "";
	conn.send(tag +" " + text);
	add_comment(text);
}
