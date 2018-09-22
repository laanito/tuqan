/**
 *  @version 0.1.1a
 *     @author Antonio Luis Batista Archilla <u>abatista@islanda.es</u>
 */

function rellenaMes(year, mes, dia, campo) {
    input = document.getElementsByTagName("INPUT");
    encontrado = false;
    i = -1;
    while (encontrado == false) {
        i++;
        if (input.item(i).name == campo + "[0]") {
            encontrado = true;
        }
    }
    input.item(i).value = dia;
    input.item(i + 1).value = mes;
    input.item(i + 2).value = year;
    input.item(i + 3).value = 0;
    input.item(i + 4).value = 0;
    var objDiv = document.getElementById("divcalendario");
    objDiv.innerHTML = "";
}

/**
 * Esta funcion dependiendo de si esta marcada o no el checkbox habilita el parametro 'habilitado' y deshabilita
 * 'deshabilitado' si esta marcado y al reves si no esta marcado
 */
function habilitarCamposForm(habilitado, deshabilitado) {
    elementos = document.getElementsByTagName("INPUT");
    encontrado = false;
    i = 0;
    while (encontrado == false) {
        if (elementos.item(i).type == "checkbox") {
            encontrado = true;
            check = elementos.item(i);
        }
        i++;
    }
    if (check.checked == false) {
        intermedio = habilitado;
        habilitado = deshabilitado;
        deshabilitado = intermedio;
    }
    input = document.getElementsByTagName("INPUT");
    textareas = document.getElementsByTagName("TEXTAREA");
    select = document.getElementsByTagName("SELECT");
    i = 0;
    while (input.item(i) != null) {
        if (input.item(i).name == habilitado) {
            input.item(i).disabled = false;
        }
        else if (input.item(i).name == deshabilitado) {
            input.item(i).disabled = true;
        }
        i++;
    }
    i = 0;
    while (textareas.item(i) != null) {
        if (textareas.item(i).name == habilitado) {
            textareas.item(i).disabled = false;
        }
        else if (textareas.item(i).name == deshabilitado) {
            textareas.item(i).disabled = true;
        }
        i++;
    }
    i = 0;
    while (select.item(i) != null) {
        if (select.item(i).name == habilitado) {
            select.item(i).disabled = false;
        }
        else if (select.item(i).name == deshabilitado) {
            select.item(i).disabled = true;
        }
        i++;
    }
}