function checkform(form) {
    if (form.nombredoc.value == "") {
        alert("Nombre no puede ser vacio.");
        form.nombredoc.focus();
        return false;
    }
    else if (form.codigodoc.value == "") {
        alert("Codigo no puede ser vacio.");
        form.codigodoc.focus();
        return false;
    }
    else if (!(sinEspacios(form.nombredoc.value)) || (!(sinEspacios(form.codigodoc.value)))) {
        alert("El codigo y/o el nombre no pueden llevar retornos de carro.");
        return false;
    }
    //Comentado para el editor
    //window.open('about:blank','proceso','top=0,left=0,directories=no,height=400,location=no,menubar=no,resizable=no,scrollbars=no,status=no,toolbar=no,width=750');
    return true;
}

function sinEspacios(cadena) {
    if (cadena == "") {
        return false;
    }
    else {
        numero = cadena.length;
        for (i = 0; i < numero; i++) {
            if ((cadena.charAt(i) == "\n") || (cadena.charAt(i) == "\r")) {
                return false;
            }
        }
    }
    return true
}

function checkvalor(form) {
    if (form.valor.value == "") {
        alert("El campo no puede ser vacio.");
        form.nombredoc.focus();
        return false;
    }
    numero = form.valor.value.length;
    punto = 0;
    for (i = 0; i < numero; i++) {
        switch (form.valor.value.charAt(i)) {
            case ".": {
                punto++;
                break;
            }
            case '0':
            case '1':
            case '2':
            case '3':
            case '4':
            case '5':
            case '6':
            case '7':
            case '8':
            case '9': {
                break;
            }
            default: {
                punto = 2;
            }
        }
    }
    if (punto > 1) {
        alert('El campo debe ser un numero real');
        return false;
    }
    return true;
}