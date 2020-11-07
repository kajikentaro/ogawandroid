var URL_SEND_NEWMOVIE = "http://54.238.75.103/app/backend/insert_url.php";
var URL_JUDGE_MOVIE_REQUEST = "http://54.238.75.103/app/backend/select_url.php";
var URL_GET_COMMENT = "http://54.238.75.103/app/backend/send_comment_list.php";
var URL_GET_PLAYING_URL = "http://54.238.75.103/app/backend/playing_url.php";
var URL_GET_PLAYING_TIME = "http://54.238.75.103/app/backend/time_keeper.php";
var URL_UPDATE_TIME = "http://54.238.75.103/app/backend/update_time.php";
var next_player_info;
var current_movie_info;
var currentURL;
var V_id ;
var player;
const query = location.search;
const value = query.split('=');
window.onload = ()=>{
    console.log("hello");
}
var tag = decodeURIComponent(value[1]);//index.htmlからget通信で受け取った変数
/*tagが定義されていないとメインページに戻る仕様。開発中は無視しておｋ
  if (tag === "undefined") window.location.href="index.html";
  else  document.getElementById("tagname").innerHTML = tag ;*/

//このチャンネルで再生中の動画を取得するPOST通信が終わったら呼ばれる。
var start_play = function(d){
    if(d[0] ==void 0){
	console.log("current urlを取得できません。tagが指定されていない可能性があります");
    }
    current_movie_info = d[0];
    loadAPI();
}
get_comment_list();
sendPostXHR(URL_GET_PLAYING_URL, "fill", "hoge",tag,start_play); //このチャンネルで再生中の動画を取得するPOST通信。start_play関数よりあとにないとだめっぽい
function loadAPI(){
    var tt = document.createElement('script');
    tt.src = "https://www.youtube.com/iframe_api";
    var firstScriptTag = document.getElementsByTagName('script')[0];
    firstScriptTag.parentNode.insertBefore(tt, firstScriptTag);
}
//APIが読み込まれたら実行される関数。StartTimeを取得して参加
function onYouTubeIframeAPIReady() {
    var date = new Date();
    var now_unix = Math.floor( date.getTime() / 1000 ) ;
    LoadYoutube(now_unix - Number(current_movie_info['time']));
}
function LoadYoutube(StartTime = 0) {
    currentURL = current_movie_info['url'];
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

function onPlayerReady(event) {
    player.mute();//ミュートにしてから再生しないといけないらしい
    event.target.playVideo();//再生
}
function onPlayerStateChange(event) {
    var date = new Date();
    var now_unix = Math.floor( date.getTime() / 1000 ) ;
    var now_position = (now_unix-Number(current_movie_info['time']));
    switch (player.getPlayerState()){
	case -1://未開始
	    break;
	case 0://終了
	    if((now_position-player.getDuration()) > 10){
		console.log("動画の再生時間を超えています");
		sendPostXHR(URL_UPDATE_TIME, "fill", 'hoge', tag,()=>{
			document.location.reload(true);
			console.log("サーバーの時間を現在時刻に変更しました。ページを再読込します。");
			});
	    }
	    console.log("再生が終了しました");
	    document.location.reload(true);//ココより下がうまく動かないからとりまリロードしてる。あんまりよくない
	    //current_movie_info = next_player_info;
	    //var date = new Date();
	    //var now_unix = Math.floor( date.getTime() / 1000 ) ;
	    //LoadYoutube(now_unix - Number(next_player_info['time']));
	    break;
	case 1://再生中
	    //動画終了10行前にlast_10sec関数を呼び出すように設定する。
	    var timetmp = Math.max(0,player.getDuration() - now_position - 10)*1000) ;
	    setTimeout(last_10sec,timetmp);
	    break;
	case 2://一時停止
	    event.target.playVideo();//止めさせない
	    break;
	case 3://バッファリング中
	    break;
	case 5://頭出し済
	    break;
    }
}
var last_10sec = function(){
    console.log("残り10秒です");

    sendPostXHR(URL_JUDGE_MOVIE_REQUEST, "current_url",currentURL ,tag,()=>{
	    sendPostXHR(URL_GET_PLAYING_URL, "fill", "hoge",tag,(response)=>{
		    next_player_info = response[0];
		    });
	    });
}
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
function sendPostXHR(url,name,data,tag,postfunc){
    var request = createXmlHttpRequest();
    request.open('POST', url);
    request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    request.addEventListener('load', function (response) {
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
function get_comment_list(){
    sendPostXHR(URL_GET_COMMENT, "fill","hoge",tag,(d)=>{
	    for(const com of d){
	    add_comment(com['comment']);
	    }
	    });
}
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
    var splited = e.data.split(" ");
    var str = "";
    for(var i =1;i<splited.length;i++){
	str = str+" "+splited[i];
    }
    add_comment(str);

};
function send_comment() {
    text = document.getElementById("com_text").value;
    document.getElementById("com_text").value = "";
    conn.send(tag +" " + text);
    add_comment(text);
}
