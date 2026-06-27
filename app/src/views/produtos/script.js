let contador = 0;

function adicionarInput() {

    let input = document.createElement("input");

    input.type = "text";
    input.name = "adicional_nome1" + contador;
    input.placeholder = "nome";
    input.className = "form-control";

    document.getElementById("#adicionais").appendChild(input);

    contador++;
}