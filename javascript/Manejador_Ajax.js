//@version 0.3.5a

window.onload = initialize;
let separador = 'ZZZ';
let separadorCadenas = 'YYY';
let http = null;
let tiempoPeticion = 10000;
setCookie('ed', '0');
//window.onload = iniciar_Qnova;
// convert all characters to lowercase to simplify testing
let agt = navigator.userAgent.toLowerCase();

// *** BROWSER VERSION ***
// Note: On IE5, these return 4, so use is_ie5up to detect IE5.
let is_major = parseInt(navigator.appVersion);
let is_minor = parseFloat(navigator.appVersion);

// Note: Opera and WebTV spoof Navigator.  We do strict client detection.
// If you want to allow spoofing, take out the tests for opera and webtv.
let is_nav = ((agt.indexOf('mozilla') !== -1) && (agt.indexOf('spoofer') === -1)
    && (agt.indexOf('compatible') === -1) && (agt.indexOf('opera') === -1)
    && (agt.indexOf('webtv') === -1) && (agt.indexOf('hotjava') === -1));
let is_nav6up = (is_nav && (is_major >= 5));
let is_gecko = (agt.indexOf('gecko') !== -1);


let is_ie = ((agt.indexOf("msie") !== -1) && (agt.indexOf("opera") === -1));
let is_ie4 = (is_ie && (is_major === 4) && (agt.indexOf("msie 4") !== -1));
let is_ie4up = (is_ie && (is_major >= 4));

let is_opera = (agt.indexOf("opera") !== -1);
let is_opera7 = (is_opera && is_major >= 7) || agt.indexOf("opera 7") !== -1;


/**
 *    Iniciamos qnova con los mensajes del usuario
 */
function iniciar_Qnova() {
    sndReq('inicio:mensajes:listado:inicial', '0', 1);
}

/**
 *    Gestion de los botones de atras/adelante del navegador aqui inicializamos los objetos necesarios
 */

function initialize() {
    // initialize the DHTML History
    // framework
    dhtmlHistory.initialize();

    // subscribe to DHTML history change
    // events
    dhtmlHistory.addListener(historyChange);
    var currentLocation = dhtmlHistory.getCurrentLocation();
    iniciar_Qnova();
    initDhtmlGoodiesMenu();
}

/**
 * Se llama a esta funcion cada vez que hacemos un cambio en el navegador
 */

function historyChange(newLocation, sectionData) {
    //Filtramos los editar por que fallan si intentamos ir con las flechas del navegador (necesita prepararse)
    if (newLocation.search("^editar") === -1) {
        sndReq(newLocation, 0, 1);
    }
}

function historico(action) {
    dhtmlHistory.add(action, null);
}

//Con esta funcion volvemos atras desde el editor, simplemente lo ocultamos
function atraseditor() {
    ap_showWaitMessage('diveditor', 0, 2);
    ap_showWaitMessage('contenedor', 1, 2);
}

function atras(numero) {
    //Ponemos todas las check sin marcar o podemos tener problemas
    check = document.getElementsByTagName("input");
    i = 0;
    for (i = 0; i < check.length; i++) {
        if ((check[i].type === "checkbox") && (check[i].value === "aplicable")) {
            check[i].checked = false;
        }
    }
    sndReq('atras', '0', 1);
//    history.go(numero);
//    trocear = location.href.split('#');
//    while (trocear[1]=="listado")
//    {
//        history.back();
//        trocear = location.href.split('#');    
//    }
}


/**
 * Esta funcion dibuja la barra de espera de carga de pagina en la division correspondiente
 */
function cargando(div) {
    document.getElementById(div).innerHTML = "<img src=\"/images/cargando.gif\">";
}

/**
 * Esta funcion dibuja la barra de espera al guardar datos
 */
function guardando(div) {
    document.getElementById(div).innerHTML = "<img src=\"/images/guardando2.gif\">";
}

/**
 * Esta funcion la usa ap_showWaitMessage para asociar el estilo de la division a la barra y asi poder
 * ocultarla o mostrarla
 */

function ap_getObj(name) {
    if (document.getElementById) {
        return document.getElementById(name).style;
    }
    else if (document.all) {
        return document.all[name].style;
    }
    else if (document.layers) {
        return document.layers[name];
    }
}

/**
 * Esta funcion es a la que llamamos para que nos muestre o nos oculte la barra de cargando
 *
 */

function ap_showWaitMessage(div, flag, tipo) {
    if (tipo === 1) {
        cargando(div);
    }
    else if (tipo === 2) {

    }
    else {
        guardando(div);
    }
    var x = ap_getObj(div);
    x.visibility = (flag) ? 'visible' : 'hidden'
}

/**
 * Esta funcion se usa en proveedores:productos para cambiar los valores de la suma
 */

function puntuacion() {
    var suma = 0;
    check = document.getElementsByTagName("input");
    //camposuma=document.getElementById("camposuma");
    for (i = 0; i < check.length; i++) {
        if (check[i].type === "checkbox") {
            if (check[i].checked === true) {
                checkid = check[i].name.split(':');
                //Las lineas comentadas no funcionan en ie, asi funcionan en ambos
                //columna=document.getElementById("td:"+checkid[1]);
                document.getElementById("td:" + checkid[1]).innerHTML = check[i].value;
                //columna.innerHTML=check[i].value;
                suma += Number(check[i].value);
            }
            else {
                checkid = check[i].name.split(':');
                //columna=document.getElementById("td:"+checkid[1]);
                document.getElementById("td:" + checkid[1]).innerHTML = 0;
                //columna.innerHTML=0;
            }
        }
    }
    document.getElementById("camposuma").value = suma;
}

/**
 * La checkbox del th de los listados es la que usa esta funcion que lo unico que hace es marcar o desmarcar
 * todas las checkbox del listado
 */
function marcardesmarcar() {
    checkeado = false;
    if (document.getElementById("checkarriba").checked === true) {
        checkeado = true;
    }
    check = document.getElementsByTagName("input");
    i = 0;
    for (i = 0; i < check.length; i++) {
        if ((check[i].type === "checkbox") && (check[i].value === "aplicable")) {
            check[i].checked = checkeado;
        }
    }
    comprobar_Botones();
}


/**
 *    Esta funcion se usa en los listados para que cuando marcamos o desmarcamos check comprueba que botones
 * activar, si solo 1 se activan los botones de fila, con 1 o mas el boton de eliminar
 */
function comprobar_Botones() {
    check = document.getElementsByTagName("input");
    marcados = 0;
    var noborra = false;
    for (i = 0; i < check.length; i++) {
        if (check[i].type === "checkbox") {
            if (check[i].checked === true) {
                if (check[i].id === 'noborra') {
                    noborra = true;
                }
                marcados++;
            }
        }
    }
    for (i = 0; i < check.length; i++) {
        if (check[i].type === "button") {
            switch (marcados) {
                case 0:
                    if ((check[i].name === 'noafecta') || (check[i].name === 'sincheck')) {
                        check[i].className = 'b_activo';
                        check[i].disabled = false;
                    }
                    else {
                        check[i].className = 'b_inactivo';
                        check[i].disabled = true; //cambio de clase
                    }
                    break;
                case 1:
                    if ((check[i].name === 'sincheck') || ((check[i].name === 'general') && (noborra))) {
                        check[i].className = 'b_inactivo';
                        check[i].disabled = true;
                    }
                    else {
                        check[i].className = 'b_activo';
                        check[i].disabled = false;

                    }
                    break;
                default:
                    if ((check[i].name === 'fila') || (check[i].name === 'sincheck') || ((check[i].name === 'general') && (noborra))) {
                        check[i].className = 'b_inactivo';
                        check[i].disabled = true;
                    }
                    else {
                        check[i].className = 'b_activo';
                        check[i].disabled = false;
                    }
            }
        }
    }
}

/**
 *    Esta funcion obtendra el numero de la fila que tiene el checkbox marcado
 */

function cogerUnicaCheck(doc) {
    var esnulo = false;
    var iNumero = -1;
    if (doc.getElementById("checkarriba") == null) {
        esnulo = true;
    }
//    checkarriba=doc.getElementById("checkarriba");
    check = doc.getElementsByTagName("input");
    i = 0;
    while ((iNumero === -1) && (i < check.length)) {
        if ((check[i].type === "checkbox") && (check[i].id !== "checkarriba")) {
            if (check[i].checked === true) {
                //Si tenemos la check de arriba del listado la que obtenemos sera una posicion menos
                if (esnulo) {
                    iNumero = (i) + separador + check[i].value;
                }
                else {
                    iNumero = (i - 1) + separador + check[i].value;
                }
            }
        }
        i++;
    }
    return iNumero;
}


/**
 *    Esta funcion obtendra un String con los checkbox del listado, en cada posicion habra un 1 o 0 si esta o no
 * marcado
 */
function cogerCheckBox(doc) {
    var sResCadena = "";
    var sBotCadena = "";
    var sValor = "";
    check = doc.getElementsByTagName("input");
    i = 0;
    for (i = 0; i < check.length; i++) {
        if ((check[i].type === "checkbox") && ((check[i].value === "aplicable") || (check[i].id === "aplicable"))) {
            if (check[i].checked === true) {
                sResCadena += "1";
                sValor = check[i].value;
            }
            else {
                sResCadena += "0";
            }
        }
        if ((check[i].type === "checkbox") && ((check[i].value === "boton") || (check[i].id === "boton"))) {
            if (check[i].checked === true) {
                sBotCadena += "1";
                sValor = check[i].value;
            }
            else {
                sBotCadena += "0";
            }
        }
    }
    sResCadena += separadorCadenas;
    sResCadena += sValor;
    sResCadena += separadorCadenas;
    sResCadena += sBotCadena;
    return sResCadena;
}

function cogerValueCheckBox(doc) {
    var sResCadena = "";
    var sValor = "";
    check = doc.getElementsByTagName("input");
    i = 0;
    for (i = 0; i < check.length; i++) {
        if ((check[i].type === "checkbox") && (check[i].name === "aplicable")) {
            if (check[i].checked === true) {
                sResCadena += check[i].value;
                sResCadena += separadorCadenas;
            }
        }
    }
    return sResCadena;
}

function cogerCampos() {
    var sResCadena = separador;
    var sResSelect = separador;
    select = document.getElementsByTagName("select");
    var selectVacios = true;
    for (i = 0; i < select.length; i++) {
        if (select[i].name === 'paginas') {
            sResCadena = select[i].value + separador;
        }
        //Si nos pasan un valor de -1 es que el select es para ver todos los elementos
        else if (select[i].value !== -1) {
            selectVacios = false;
            sResSelect = separadorCadenas + select[i].name + separadorCadenas + select[i].value;
        }
    }
    text = document.getElementsByTagName("input");
    var todosVacios = true;
    for (i = 0; i < text.length; i++) {
        if (text[i].type === "text") {
            if (text[i].value.length > 0) {
                todosVacios = false;
                sResCadena += separadorCadenas + text[i].name + separadorCadenas + text[i].value;
            }
        }
    }
    if (todosVacios && selectVacios) {
        sResCadena += "limpiar";
    }
    return sResCadena += separador + sResSelect;
}

/**
 *     Esta funcion nos crea una nueva instancia para crear una peticion
 */

function createRequestObject() {
    var ro;
    var browser = navigator.appName;
    if (browser === "Microsoft Internet Explorer") {
        ro = new ActiveXObject("Microsoft.XMLHTTP");
    } else {
        ro = new XMLHttpRequest();
    }
    return ro;
}

function errorEspera() {
    http = null;
    ap_showWaitMessage('wait', 0, 1);
    document.getElementById('contenedor').innerHTML = "<p>No ha llegado respuesta en el tiempo estimado, por favor vuelva a intentarlo <br /> Si persiste el error p?ngase en contacto con el administrador de la aplicación</p>";
}

/**
 * Al igual que las anteriores funciones para obtener la checkbox marcada esta funcion nos devuelve el value del radio button
 * marcado o 0 en caso de ninguno
 */
function cogerRadio(doc) {
    let radio = doc.getElementsByTagName("input");
    let valor = 0;
    let level = 0;
    for (i = 0; i < radio.length; i++) {
        if ((radio[i].type === "radio")) {
            if (radio[i].checked === true) {
                valor = radio[i].value;
                //Ahora cogemos en que nivel esta
                let nivel = radio[i].parentNode.parentNode.id.split('_');
                level = nivel.length;
            }
        }
    }
    devolver = new Array(2);
    devolver[0] = valor;
    devolver[1] = level;
    return (devolver);
}

function cogerOptionPerfil() {
    doc = document.getElementById('form').contentWindow.document;
    selects = doc.getElementsByTagName('select');
    for (i = 0; i < selects.length; i++) {
        if (selects[i].name === "usuarios:perfil") {
            valor = selects[i].value;
        }
    }
    return valor;
}

/**
 *    Esta funcion nos procesa las peticiones, controlamos que no haya mas de una peticion a la
 * vez procesando solo cuando nuestra variable http sea null, si es asi la inicializamos,
 * impidiendo nuevas peticiones, una vez procesada, la funcion handleResponse nos devolvera
 * la variable http a null pudiendose de nuevo cursarse peticiones, ademas muestra la barra
 * de cargando
 */

function filtroEditor(action) {
    let proceso;
    let editorCreado = getCookie('ed');
    //primero le pasamos el filtro de cerrar:selecciona
    if ((action === 'cerrar:selecciona') || (action === 'ninguna')) {
        proceso = 0;
    }
    else if (editorCreado === 1) {

        editorPintado = getCookie('editor');
        if (editorPintado === 1) {
            //La division del editor esta mostrandose en este momento
            if (action !== 'editor') {
                //La accion no es editor, ocultamos la division y procesamos la peticion normalmente
                ap_showWaitMessage('diveditor', 0, 2);
                ap_showWaitMessage('contenedor', 1, 2);
                document.getElementById('contenedor').innerHTML = " ";
                setCookie('editor', '0');
                proceso = 1;
                //Borramos el contenido de la caja de texto del editor(podia dar problemas en ie)
                editorframe = document.getElementById("FCKEDITOR");
                frame = editorframe.contentWindow.document;
                editorinterno = frame.getElementsByTagName("IFRAME")[0];
                frameinterno = editorinterno.contentWindow.document;
                editormasinterno = frameinterno.getElementsByTagName("IFRAME")[0];
                framemasinterno = editormasinterno.contentWindow.document;
                bodyframe = framemasinterno.getElementsByTagName("BODY")[0];
                bodyframe.innerHTML = " ";
            }
            else {
                //La accion es editor, si no hay datos no hacemos nada, en otro caso pegamos los datos
                proceso = 1;
            }
        }
        else {
            //La division del editor esta oculta en este momento
            if (action !== 'editor') {
                //La accion no es editor, lo procesamos normalmente
                proceso = 1;
            }
            else {
                //La accion es editor, ocultamos contenedor y mostramos editor, si hay datos los pegamos en el editor
                ap_showWaitMessage('contenedor', 0, 2);
                ap_showWaitMessage('diveditor', 1, 2);
                setCookie('editor', '1');
                proceso = 1;
            }
        }
    }
    else {
        proceso = 1;
    }
    return proceso;
}


function cogerCheckBoxPerfiles(doc) {
    var aCadenas = "";
    var sVerCadena = "";
    var sNuevoCadena = "";
    var sModificarCadena = "";
    var sRevisarCadena = "";
    var sAprobarCadena = "";
    var sHistoricoCadena = "";
    var sTareasCadena = "";
    var nombre = "";

    check = doc.getElementsByTagName("input");
    i = 0;
    for (i = 0; i < check.length; i++) {
        if ((check[i].type === "checkbox")) {
            var cbox;
            var pepe;
            cbox = check[i];
            pepe = cbox.id;
            nombre = pepe.split('_');
            switch (nombre[0]) {
                case 'perfilVer': {
                    if (check[i].checked === true) {
                        sVerCadena += "1" + separadorCadenas + nombre[1] + separadorCadenas;
                    }
                    else {
                        sVerCadena += "0" + separadorCadenas + nombre[1] + separadorCadenas;
                    }
                    break;
                }
                case 'perfilNuevo': {
                    if (check[i].checked === true) {
                        sNuevoCadena += "1" + separadorCadenas + nombre[1] + separadorCadenas;
                    }
                    else {
                        sNuevoCadena += "0" + separadorCadenas + nombre[1] + separadorCadenas;
                    }
                    break;
                }
                case 'perfilModificar': {
                    if (check[i].checked === true) {
                        sModificarCadena += "1" + separadorCadenas + nombre[1] + separadorCadenas;
                    }
                    else {
                        sModificarCadena += "0" + separadorCadenas + nombre[1] + separadorCadenas;
                    }
                    break;
                }
                case 'perfilRevisar': {
                    if (check[i].checked === true) {
                        sRevisarCadena += "1" + separadorCadenas + nombre[1] + separadorCadenas;
                    }
                    else {
                        sRevisarCadena += "0" + separadorCadenas + nombre[1] + separadorCadenas;
                    }
                    break;
                }
                case 'perfilAprobar': {
                    if (check[i].checked === true) {
                        sAprobarCadena += "1" + separadorCadenas + nombre[1] + separadorCadenas;
                    }
                    else {
                        sAprobarCadena += "0" + separadorCadenas + nombre[1] + separadorCadenas;
                    }
                    break;
                }
                case 'perfilHistorico': {
                    if (check[i].checked === true) {
                        sHistoricoCadena += "1" + separadorCadenas + nombre[1] + separadorCadenas;
                    }
                    else {
                        sHistoricoCadena += "0" + separadorCadenas + nombre[1] + separadorCadenas;
                    }
                    break;
                }
                case 'perfilTareas': {
                    if (check[i].checked === true) {
                        sTareasCadena += "1" + separadorCadenas + nombre[1] + separadorCadenas;
                    }
                    else {
                        sTareasCadena += "0" + separadorCadenas + nombre[1] + separadorCadenas;
                    }
                    break;
                }
            }
        }
    }
    aCadenas += sVerCadena;
    aCadenas += sNuevoCadena;
    aCadenas += sModificarCadena;
    aCadenas += sRevisarCadena;
    aCadenas += sAprobarCadena;
    aCadenas += sHistoricoCadena;
    aCadenas += sTareasCadena;

    return aCadenas;
}

function cogerCheckBoxPermisos(doc) {
    var nombre = "";
    var sCadena = "";
    var aId = [];
    var aPermisos = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
    check = doc.getElementsByTagName("input");
    i = 0;
    for (i = 0; i < check.length; i++) {
        if ((check[i].type === "checkbox")) {
            aId = check[i].id.split('_');
            if (check[i].checked === true) {
                aPermisos[aId[1]] = 1;
            }
            else {
                aPermisos[aId[1]] = 0;
            }
        }
    }
    for (i = 0; i < 22; i++) {
        sCadena += aPermisos[i];
    }
    return sCadena;
}


function sndReq(action, sesion, tipo, datos) {
    // Comprobamos que no se esta cursando otra peticion
    proceso = 1;
    document.body.style.cursor = "wait";
    if (http == null) {
        trocearAction = action.split(':');
        switch (trocearAction[4]) {
            case 'fila': {
                datos = cogerUnicaCheck(document);
                break;
            }

            case 'general': {
                datos = cogerCheckBox(document);
                break;
            }

            case 'radio': {
                framearbol = document.getElementById("arbol");
                docarbol = framearbol.contentWindow.document;
                devolver = cogerRadio(docarbol);
                iIdMarcada = devolver[0];
                if (iIdMarcada === 0) {
                    if (trocearAction[3] !== "nuevo") {
                        alert('No ha seleccionado ningun elemento');
                        action = 'ninguna';
                    }
                    else {
                        datos = 0;
                    }
                }
                else {
                    if ((trocearAction[3] !== "nuevo") || (devolver[1] < 8)) {
                        datos = iIdMarcada;
                    }
                    else {
                        //Si el nivel devuelto es 5(la variable marcara 8) no permitimos crear un nuevo nodo
                        alert('Maximo 5 niveles');
                        action = 'ninguna';
                    }
                }
                break;
            }

            case 'verPerfil': {
                datos = cogerOptionPerfil();
                break;
            }

            case 'listado': {
                datos = datos + separador + cogerCampos();
                break;
            }

            case 'cerrar': {
                //Si nos llegan datos los ponemos en los campos del formularios necesarios
                if (datos != null) {
                    aForm = datos.split(separador);
                    formframe = document.getElementById("form");
                    frame = formframe.contentWindow.document;
                    elementos = frame.getElementsByTagName("input");
                    noencontrado = true;
                    i = 0;
                    while ((noencontrado) && (i < elementos.length)) {
                        if (elementos[i].name === aForm[1]) {
                            elementos[i].value = aForm[0];
                            noencontrado = false;
                        }
                        else {
                            i++;
                        }
                    }
                    noencontrado = true;
                    i = 0;
                    while ((noencontrado) && (i < elementos.length)) {
                        if (elementos[i].name === (aForm[1] + "texto")) {
                            elementos[i].value = aForm[2];
                            noencontrado = false;
                        }
                        else {
                            i++;
                        }
                    }
                }
                ap_showWaitMessage('contenedor', 0, 2);
                ap_showWaitMessage('diviframe', 1, 2);
                break;
            }

            case 'grafica': {
                datos = document.getElementById('desgrafica').value + separador + document.getElementById('modgrafica').value;
                break;
            }

            case 'actualizar': {
                datos = cogerCheckBoxPerfiles(document);
                break;
            }
            case 'permisos': {
                aux = document.getElementById("diviframe");
                frame = aux.getElementsByTagName("IFRAME")[0];
                contenedor_derecha = frame.contentWindow.document;
                datos = cogerCheckBoxPermisos(contenedor_derecha);
                break;
            }
        }


        proceso = filtroEditor(trocearAction[2]);
        if (action.length < 1) {
            proceso = 0;
        }
        if (proceso === 1) {
            temporizador = window.setTimeout("errorEspera()", tiempoPeticion);
            setCookie('temporizador', temporizador);
            dhtmlHistory.add(action, null);
            /*ap_showWaitMessage('wait',1 ,tipo);*/
            http = createRequestObject();
            http.open('post', '/ajax', true);
            http.onload = handleResponse;
            var data = new FormData();
            data.append('action', action);
            if (typeof sesion !== 'undefined') {
                data.append('sesion', sesion);
            }
            if (typeof datos !== 'undefined') {
                data.append('datos', datos);
            }
            http.send(data);
        }
    }
}

/**
 * Esta funcion es la que nos procesa los datos de vuelta del php y se encarga de poner
 * la variable php a 0 para poder volver a cursar peticiones y ademas quita la barra de cargando
 */

function handleResponse() {
    if (http.readyState === 4) {
        var response = http.responseText;
        var update = [];
        var quitar = true;
        update = response.split('|');
        //document.getElementById('contenedor').innerHTML=update[0];
        if (response.indexOf('|' !== -1)) {
            /**
             * En caso de que la longitud de la cadena devuelta sea menor o igual a 1 querra
             * decir que tenemos un error con lo cual no mostramos nada.
             */
            i = 1;
            /**
             * Si lo que queremos es desconectarnos anulamos todas las cookies y volvemos a index
             */
            if (update[i] === "logout") {
                clearTimeout(temporizador);
                delCookie('temporizador');
                delCookie('PHPSESSID');
                delCookie('ed');
                delCookie('editor');
                delCookie('anchura');
                delCookie('altura');
                delCookie('IdiomaCal');
                location.href = "/";
            }
            /**
             * Este bucle nos va mostrando las divisiones que tengamos que cargar, primero el contenedor
             * y, en el caso en que tengamos que hacerlo, tambien el menu de la izquierda
             */
            if (update[i] === null) {
                destino = document.getElementById("contenedor");
                if (is_ie) {
                    error = document.createElement("<TEXTAREA name='thetext' rows='80' cols='120'>" + response + "</TEXTAREA>");
                }
                else {
                    error = document.createElement("textarea");
                    error.setAttribute('cols', 120);
                    error.setAttribute('rows', 80);
                }
                error.value = response;
                destino.appendChild(error);
            }
            while (update[i] != null) {
                if (update[i].length >= 1) {
                    switch (update[i - 1]) {
                        case "diviframe": {
                            ap_showWaitMessage('contenedor', 0, 2);
                            ap_showWaitMessage('diviframe', 1, 2);
                            document.getElementById('diviframe').innerHTML = update[i];
                            break;
                        }
                        case "creapdf": {
                            if (update[i].indexOf('<iframe') > -1) {
                                quitar = false;
                            }
                            ap_showWaitMessage('contenedor', 1, 2);
                            ap_showWaitMessage('diviframe', 0, 2);
                            document.getElementById('contenedor').innerHTML = update[i];
                            break;
                        }
                        case "diveditor": {
                            divcontenedor = document.getElementById('contenedor');
                            divcontenedor.style.visibility = "hidden";
                            document.getElementById("diveditor").innerHTML = update[i];
                            divcontenedor = document.getElementById('diveditor');
                            divcontenedor.style.visibility = "visible";
                            delCookie('ed');
                            setCookie('ed', '1');
                            setCookie('editor', '1');
                            break;
                        }
                        case 'divayuda': {
                            divcontenedor = document.getElementById('divayuda');
                            document.getElementById("divayuda").innerHTML = update[i];
                            initSlideLeftPanel();
                            break;
                        }
                        case "calendario": {
                            divcal = document.getElementById("form");
                            calendario = divcal.contentWindow.document.getElementById("divcalendario");
                            calendario.innerHTML = update[i];
                            break;
                        }
                        case "alert": {
                            temporizador = getCookie('temporizador');
                            clearTimeout(temporizador);
                            alert(update[i]);
                            temporizador = window.setTimeout("errorEspera()", 3 * tiempoPeticion);
                            setCookie('temporizador', temporizador);
                            break;
                        }
                        case "contenedor": {
                            ap_showWaitMessage('contenedor', 1, 2);
                            ap_showWaitMessage('diviframe', 0, 2);
                            document.getElementById(update[i - 1]).innerHTML = update[i];
                            break;
                        }
                        case "submenu": {
                            document.getElementById(update[i - 1]).innerHTML = update[i];
                            initDhtmlGoodiesMenu();
                            break;
                        }
                        case "menu":
                        case "titulo": {
                            document.getElementById(update[i - 1]).innerHTML = update[i];
                            break;
                        }
                        case "formulario": {
                            aForm = update[i].split(separador);
                            formframe = document.getElementById("form");
                            frame = formframe.contentWindow.document;
                            elementos = frame.getElementsByTagName("input");
                            noencontrado = true;
                            i = 0;
                            while ((noencontrado) && (i < elementos.length)) {
                                if (elementos[i].name === aForm[1]) {
                                    elementos[i].value = aForm[0];
                                    noencontrado = false;
                                }
                                else {
                                    i++;
                                }
                            }
                            noencontrado = true;
                            i = 0;
                            while ((noencontrado) && (i < elementos.length)) {
                                if (elementos[i].name === (aForm[1] + "texto")) {
                                    elementos[i].value = aForm[2];
                                    noencontrado = false;
                                }
                                else {
                                    i++;
                                }
                            }
                            ap_showWaitMessage('contenedor', 0, 2);
                            ap_showWaitMessage('diviframe', 1, 2);
                            break;
                        }
                        case "sacareditor": {
                            aParaEditor = update[i].split(separadorCadenas);
                            editorframe = document.getElementById("FCKEDITOR");
                            //Ponemos aqui contentWindow para que funcione tambien en ie, con contentdocument no funciona
                            frame = editorframe.contentWindow.document;
                            frame.getElementById("codigodoc").value = aParaEditor[1];
                            frame.getElementById("nombredoc").value = aParaEditor[2];
                            //@TODO posible conflicto con ifram de formularios
                            editorinterno = frame.getElementsByTagName("IFRAME")[0];
                            //Ponemos aqui contentWindow para que funcione tambien en ie, con contentdocument no funciona
                            frameinterno = editorinterno.contentWindow.document;
                            editormasinterno = frameinterno.getElementsByTagName("IFRAME")[0];
                            framemasinterno = editormasinterno.contentWindow.document;
                            bodyframe = framemasinterno.getElementsByTagName("BODY")[0];
                            bodyframe.innerHTML = aParaEditor[0];
                            document.getElementById('contenedor').innerHTML = " ";
                            ap_showWaitMessage('contenedor', 0, 2);
                            ap_showWaitMessage('diveditor', 1, 2);
                            setCookie('editor', '1');
                            break;
                        }
                        case "contenedor_derecha": {
                            aux = document.getElementById("diviframe");
                            frame = aux.getElementsByTagName("IFRAME")[0];
                            contenedor_derecha = frame.contentWindow.document;
                            contenedor_derecha.getElementById(update[i - 1]).innerHTML = update[i];
                            break;
                        }
                        default: {
                            destino = document.getElementById("contenedor");
                            if (is_ie) {
                                var error = document.createElement("<TEXTAREA name='thetext' rows='80' cols='120'>" + response + "</TEXTAREA>");
                            }
                            else {
                                error = document.createElement("textarea");
                                error.setAttribute('cols', 120);
                                error.setAttribute('rows', 80);
                                error.value = response;
                            }
                            destino.appendChild(error);
                            break;
                        }
                    }
                }
                else {
                    document.getElementById('contenedor').innerHTML = "opcion incorrecta";
                }
                i++;
                i++;
            }
            /**
             * Escondemos el temporizador de carga
             */
            if (quitar) {
                ap_showWaitMessage('wait', 0);
                document.body.style.cursor = "default";
            }

        }
        /**
         * Liberamos para que se puedan hacer otras peticiones
         */
        http = null;

        temporizador = getCookie('temporizador');
        clearTimeout(temporizador);
        delCookie('temporizador');
    }
}
