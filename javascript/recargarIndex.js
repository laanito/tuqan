function recargaIndex(sel) {
//    alert(sel.options[sel.selectedIndex].value);
    delCookie('IdiomaCal');
    var idioma = sel.options[sel.selectedIndex].text;
    setCookie('IdiomaCal', idioma);
    var sIdioma = sel.options[sel.selectedIndex].value;
    window.location = "login_empresa.php?idioma=" + sIdioma;
}