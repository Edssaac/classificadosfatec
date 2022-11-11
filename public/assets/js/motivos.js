var motivos = {
    1: "Conteúdo sexual",
    2: "Conteúdo violento ou repulsivo",
    3: "Conteúdo de incitação ao ódio ou abusivo",
    4: "Assédio ou bullying",
    5: "Desinformação",
    6: "Abuso infantil",
    7: "Spam ou enganoso",
    8: "Viola meus direitos",
}

$("#denunciar").click(function () {
    $("#motivo").val("0");
    $("#mensagem").val("");
});