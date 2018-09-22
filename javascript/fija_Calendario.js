//Esta funci�n sirve para emular el position:fixed en Internet Explorer


function reposicionaMenu() {
    //IE6 in non-quirks doesnt get document.body.scrollTop:
    var pos = (document.body.scrollTop) ? document.body.scrollTop : document.documentElement.scrollTop;
    document.getElementById("divcalendario").style.top = parseInt(pos + 25) + "px";
}

onload = function () {
    if (document.getElementById && !window.getComputedStyle) {// DOM but not Mozilla
        document.getElementById("divcalendario").style.position = "absolute";
        window.onscroll = reposicionaMenu;
    }
}