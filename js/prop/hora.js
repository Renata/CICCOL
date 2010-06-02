function moveRelogio(){
    momentoAtual = new Date()
    hora = momentoAtual.getHours()
    minuto = momentoAtual.getMinutes()
    segundo = momentoAtual.getSeconds()

    horaImprimivel = hora + " : " + minuto + " : " + segundo

    document.form_relogio.relogio.value = horaImprimivel

    setTimeout("moveRelogio()",1000)
}