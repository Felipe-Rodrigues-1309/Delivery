// MÉTODO MAIS SEGURO (RECOMENDADO)
const adicionais = document.querySelectorAll('.adicional-checkbox');

adicionais.forEach(chk => {
    chk.addEventListener("change", () => {
        const produtoId = chk.dataset.produtoId;
        const totalSpan = document.getElementById("valorTotal" + produtoId);
        
        // 1. Pega o valor base do produto (do atributo data-valor-base)
        const valorBase = parseFloat(totalSpan.dataset.valorBase);
        let totalAdicionais = 0;

        // 2. Seleciona TODOS os checkboxes de adicionais para ESTE produto que estão marcados
        const todosAdicionaisDoProduto = document.querySelectorAll(`.adicional-checkbox[data-produto-id="${produtoId}"]:checked`);

        // 3. Soma o valor de todos os adicionais marcados
        todosAdicionaisDoProduto.forEach(adicionalMarcado => {
            totalAdicionais += parseFloat(adicionalMarcado.dataset.valor);
        });

        // 4. Calcula o novo total
        const novoTotal = valorBase + totalAdicionais;

        // 5. Atualiza total
        totalSpan.textContent = "Total: R$ " + novoTotal.toFixed(2).replace(".", ",");
    });
});
