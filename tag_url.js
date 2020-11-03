console.log("tag>url");
const url = "http://54.238.75.103/backend/player_info.php";
var xhr1 = new XMLHttpRequest();
xhr1.withCredentials = true;
xhr1.open('POST', url);
var test = "tag=movie";
xhr1.setRequestHeader('content-type', 'application/x-www-form-urlencoded;charset=UTF-8');
xhr1.responseType = 'json';
xhr1.send(test);
xhr1.onreadystatechange = function () {
    if (xhr1.readyState === 4 && xhr1.status === 200) {
        //console.log(xhr1.responseText);
        data = this.response;
        console.log("tag_name : " + data[0]["tag"]);
        console.log("url : " + data[0]["url"]);
        console.log(data[0]);

    }
}