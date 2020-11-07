const query=location.search;
const value=query.split('=');
var tag_2 = decodeURIComponent(value[1]);
document.getElementById("input_img").value=tag_2;
console.log(document.getElementById("input_img").value);
function send_file() {
    const form = document.getElementById('f1');
    const fd = new FormData(form);
    xhr = new XMLHttpRequest();
    xhr.withCredentials = true;
    xhr.open("POST", "http://54.238.75.103/app/backend/receive_image.php");
    //xhr.setRequestHeader('content-type', 'application/x-www-form-urlencoded;charset=UTF-8');
    xhr.send(fd);
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            data = this.responseText;
            console.log(data);
        }
    }
    console.log("end_send_file()");
}

var img = document.getElementById("show_image");
var d = new Date();
var sec = d.getSeconds();
var t=sec;
setInterval(function () {
    t += 1;
    if (t % 10 == 0||t-sec==1) {
        var xhr = new XMLHttpRequest();
        xhr.withCredentials = true;
        xhr.open('POST', "http://54.238.75.103/app/backend/playing_image.php");
        var send_tag = "tag=" + tag_2;
        xhr.setRequestHeader('content-type', 'application/x-www-form-urlencoded;charset=UTF-8');
        //xhr.responseType = 'json';
        xhr.send(send_tag);
        xhr.addEventListener('load', function (response) {
            var result = this.responseText;
            var result_json = JSON.parse(this.responseText);
            console.log(result_json['url']);
            show_image.setAttribute('src', result_json['url']);
        });
    }
}, 1000);
function get_comment_list() {
    sendPostXHR(URL_GET_COMMENT, "fill", "hoge", tag_2, (d) => {
        for (const com of d) {
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
    for (var i = 1; i < splited.length; i++) {
        str = str + " " + splited[i];
    }
    add_comment(str);
};
function send_comment() {
    text = document.getElementById("com_text").value;
    document.getElementById("com_text").value = "";
    conn.send(tag_2 + " " + text);
    add_comment(text);
}
