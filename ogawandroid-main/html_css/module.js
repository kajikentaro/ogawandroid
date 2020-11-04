console.log("homepageLoad_mes>tag_list");
const url = "http://54.238.75.103/app/backend/";
var xhr1 = new XMLHttpRequest();
xhr1.withCredentials = true;
xhr1.open('POST', url);
xhr1.setRequestHeader('content-type', 'application/x-www-form-urlencoded;charset=UTF-8');
xhr1.responseType = 'json';
xhr1.send(test);
xhr1.onreadystatechange = function () {
    if (xhr1.readyState === 4 && xhr1.status === 200) {
        data = this.response;
        console.log("get tag_list");
        console.log(data);
    }
}


console.log("tag>url");
const url = "";
var xhr2 = new XMLHttpRequest();
xhr2.withCredentials = true;
xhr2.open('POST', url);
var tag = "movie";
var test = "tag=" + tag;
xhr2.setRequestHeader('content-type', 'application/x-www-form-urlencoded;charset=UTF-8');
xhr2.responseType = 'json';
xhr2.send(test);
xhr2.onreadystatechange = function () {
    if (xhr2.readyState === 4 && xhr2.status === 200) {
        data = this.response;
        console.log("tag_name : " + data[0]["tag"]);
        console.log("url : " + data[0]["url"]);
        console.log(data);
    }
}

console.log("comment>message");
const url = "";
var xhr3 = new XMLHttpRequest();
xhr3.withCredentials = true;
xhr3.open('POST', url);
var tag = "movie";
var test = "tag=" + tag;
xhr3.setRequestHeader('content-type', 'application/x-www-form-urlencoded;charset=UTF-8');
xhr3.responseType = 'json';
xhr3.send(test);//テキスト形式で送信
xhr3.onreadystatechange = function () {
    if (xhr3.readyState === 4 && xhr3.status === 200) {
        console.log(xhr3.responseText);
        console.log("tag_name : " + data[0]["tag"]);
        console.log("url : " + data[0]["url"]);
        console.log(data[0]);
    }
}

console.log("tag>commentList");
const url = "http://54.238.75.103/backend/player_info.php";
var xhr1 = new XMLHttpRequest();
xhr1.withCredentials = true;
xhr1.open('POST', url);
var tag = "movie";
var test = "tag=" + tag;
xhr1.setRequestHeader('content-type', 'application/x-www-form-urlencoded;charset=UTF-8');
xhr1.responseType = 'json';
xhr1.send(test);//テキスト形式で送信
xhr1.onreadystatechange = function () {
    if (xhr1.readyState === 4 && xhr1.status === 200) {
        console.log(xhr1.responseText);
        console.log("tag_name : " + data[0]["tag"]);
        console.log("url : " + data[0]["url"]);
        console.log(data[0]);
    }
}

console.log("endmessage>selectedURL");
const url2 = "http://54.238.75.103/app/backend/select_url.php";
const date = new Date();
const random_num = "random_num=11 * date.getHours()*date.getDay()+1";
console.log(date.getMinutes() * date.getHours() * date.getDay());
var xhr1 = new XMLHttpRequest();
xhr1.withCredentials = true;
xhr1.open('POST', url);
xhr1.setRequestHeader('content-type', 'application/x-www-form-urlencoded;charset=UTF-8');
xhr1.responseType = 'json';
xhr1.send(random_num);
xhr1.onreadystatechange = function () {
    if (xhr1.readyState === 4 && xhr1.status === 200) {
        data = this.response;
        console.log(data);
    }
}