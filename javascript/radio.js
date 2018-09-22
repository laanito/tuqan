function limpiar() {
    radio = document.getElementsByTagName("input");
    for (i = 0; i < radio.length; i++) {
        if ((radio[i].type == "radio")) {
            radio[i].checked = false;
        }
    }
}