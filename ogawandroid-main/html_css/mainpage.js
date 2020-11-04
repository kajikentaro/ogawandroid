console.log("hello");
let taglist = ["PopularMusic", "Anime", "Game", "Movie", "Study"];
let table = document.getElementById('tag_list');
let newRow;
taglist.forEach(function (val, index) {
    console.log(val);
    if (index % 4 == 0) {
        newRow = table.insertRow();
    }
    console.log(index);
    let newCell = newRow.insertCell();
    let newText = document.createTextNode(val);
    let a_tag = document.createElement("a");
    newCell.appendChild(a_tag);
})
let x = document.getElementsByTagName("a");
console.log(x.length);
for (let i = 0; i < x.length; i++) {
    x[i].id = "tag_id" + String(i);
    x[i].innerHTML = taglist[i];
    x[i].setAttribute('href', 'test.html');
}
