/**
 *    Funciones para el manejo de cookies
 */
function getCookie(name) {
    var cname = name + "=";
    var dc = document.cookie;
    if (dc.length > 0) {
        begin = dc.indexOf(cname);
        if (begin !== -1) {
            begin += cname.length;
            end = dc.indexOf(";", begin);
            if (end === -1) end = dc.length;
            return decodeURI(dc.substring(begin, end));
        }
    }
    return null;
}

function delCookie(name, path, domain) {
    if (getCookie(name)) {
        document.cookie = name + "=" +
            ((path == null) ? "" : "; path=" + path) +
            ((domain == null) ? "" : "; domain=" + domain) +
            "; expires=Thu, 01-Jan-1970 00:00:01 GMT";
    }
}

function setCookie(name, value, expires, path, domain, secure) {
    document.cookie = name + "=" + encodeURI(value) +
        ((expires == null) ? "" : "; expires=" + expires.toGMTString()) +
        ((path == null) ? "" : "; path=" + path) +
        ((domain == null) ? "" : "; domain=" + domain) +
        ((secure == null) ? "" : "; secure");
}
