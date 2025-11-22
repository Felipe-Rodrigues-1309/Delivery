// script.js - versão robusta, evita listeners duplicados e faz fallback para localStorage

document.addEventListener("DOMContentLoaded", () => {
  console.log("script.js carregado - inicializando...");

  // utilitário: converte "Total: R$ 12,34" -> 12.34 (número)
  function textoParaNumero(str) {
    if (!str) return 0;
    let apenasNumero = str.replace(/[^0-9,.-]/g, "").trim();
    // se houver vírgula e ponto, assume formato pt-BR (1.234,56)
    if (apenasNumero.indexOf(",") > -1 && apenasNumero.indexOf(".") > -1) {
      // remove pontos de milhares, troca vírgula por ponto decimal
      apenasNumero = apenasNumero.replace(/\./g, "").replace(",", ".");
    } else {
      // troca vírgula por ponto
      apenasNumero = apenasNumero.replace(",", ".");
    }
    const n = parseFloat(apenasNumero);
    return isNaN(n) ? 0 : n;
  }

  // Atualiza o total dentro de um modal (apenas escopo do modal)
  function atualizarTotalDoModal(modal) {
    try {
      // tenta encontrar data-produto-id no modal (procura o primeiro checkbox)
      const primeiroChk = modal.querySelector(".adicional-checkbox");
      if (!primeiroChk) return; // nada a fazer

      const produtoId = primeiroChk.dataset.produtoId;
      const h2 = modal.querySelector("#valorTotal" + produtoId) || modal.querySelector("[id^='valorTotal']");
      if (!h2) return;

      const base = parseFloat(h2.dataset.valorBase) || 0;
      let total = base;

      modal.querySelectorAll(`.adicional-checkbox[data-produto-id="${produtoId}"]:checked`)
        .forEach(chk => {
          const v = parseFloat(chk.dataset.valor) || 0;
          total += v;
        });

      h2.textContent = "Total: R$ " + total.toFixed(2).replace(".", ",");
    } catch (err) {
      console.error("Erro em atualizarTotalDoModal:", err);
    }
  }

  // Inicializa listeners de change para checkboxes dentro de TODOS os modais
  function initCheckboxListeners() {
    document.querySelectorAll(".adicional-checkbox").forEach(chk => {
      // para evitar múltiplos handlers, verificamos uma flag
      if (chk.dataset._listenerAttached === "1") return;
      chk.dataset._listenerAttached = "1";

      chk.addEventListener("change", function () {
        // encontra o modal pai mais próximo
        const modal = this.closest(".modal");
        if (!modal) return;
        atualizarTotalDoModal(modal);
      });
    });
  }

  // Anexa listeners aos botões de adicionar (suporta .btn-add-carrinho e fallback .modal .btn-primary com data-id)
  function initAddButtons() {
    // Seletores possíveis (prioridade para botão com classe específica)
    const selectors = [".btn-add-carrinho", ".modal .btn-primary[data-id]"];

    selectors.forEach(selector => {
      document.querySelectorAll(selector).forEach(btn => {
        // evita múltiplos handlers
        if (btn.dataset._listenerAttached === "1") return;
        btn.dataset._listenerAttached = "1";

        btn.addEventListener("click", async function (e) {
          try {
            // pega o modal pai (se existir)
            const modal = btn.closest(".modal");
            let produtoId = btn.dataset.id || "";

            if (!produtoId && modal) {
              // tenta extrair do id do modal (modal123 -> 123)
              const modalId = modal.id || "";
              produtoId = modalId.replace("modal", "");
            }

            // nome do produto: procura por elementos dentro do modal, se não achar usa data-nome no botão
            let nome = btn.dataset.nome || "";
            if (modal && !nome) {
              const nomeElem = modal.querySelector(".nome-produto") || modal.querySelector(".modal-title");
              if (nomeElem) nome = nomeElem.textContent.trim();
            }

            // total no modal
            let total = 0;
            if (modal) {
              const totalElem = modal.querySelector("#valorTotal" + produtoId) || modal.querySelector("[id^='valorTotal']");
              if (totalElem) total = textoParaNumero(totalElem.textContent);
            } else {
              // fallback: se não houver modal, tenta data-total no botão
              if (btn.dataset.total) total = textoParaNumero(btn.dataset.total);
            }

            // quantidade, se existir um input #qtd{ID} no modal ou um input[type=number]
            let quantidade = 1;
            if (modal) {
              const qtdInput = modal.querySelector("#qtd" + produtoId) || modal.querySelector("input[type='number']");
              if (qtdInput) quantidade = parseInt(qtdInput.value) || 1;
            }

            // coletar adicionais apenas do modal atual (evita pegar checkboxes de outros modais)
            let adicionais = [];
            if (modal) {
              modal.querySelectorAll(`.adicional-checkbox[data-produto-id="${produtoId}"]:checked`)
                .forEach(a => {
                  adicionais.push({
                    nome: a.value,
                    valor: parseFloat(a.dataset.valor) || 0
                  });
                });
            }

            const item = {
              id: produtoId || String(Date.now()), // id de segurança
              nome: nome || ("Produto " + produtoId),
              quantidade: quantidade,
              adicionais: adicionais,
              total: total
            };

            console.log("Tentando adicionar ao carrinho:", item);

            // TENTAR enviar para servidor (opcional). Se falhar, salva no localStorage.
            let enviadoAoServidor = false;
            try {
              // Ajuste a URL se você tiver endpoint. Mantive como opcional:
              const endpoint = "back-end/add_carrinho.php"; // altere se usar outro caminho
              // tenta um POST JSON, mas não exige que o servidor responda OK
              const resp = await fetch(endpoint, {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify(item),
                cache: "no-store"
              });
              if (resp.ok) {
                console.log("Enviado ao servidor com sucesso (resp.ok).");
                enviadoAoServidor = true;
              } else {
                console.warn("Resposta do servidor não ok:", resp.status);
              }
            } catch (err) {
              console.warn("Falha ao enviar ao servidor (usando fallback localStorage):", err);
            }

            if (!enviadoAoServidor) {
              // fallback: salvar no localStorage
              let carrinho = JSON.parse(localStorage.getItem("carrinho")) || [];
              carrinho.push(item);
              localStorage.setItem("carrinho", JSON.stringify(carrinho));
              console.log("Salvo em localStorage.carrinho");
            }

            // Fecha o modal com segurança (se existir)
            if (modal) {
              try {
                let bsModal = bootstrap.Modal.getInstance(modal);
                if (!bsModal) bsModal = new bootstrap.Modal(modal);
                bsModal.hide();
              } catch (hideErr) {
                console.warn("Erro ao fechar modal:", hideErr);
              }
            }

            // feedback visual
            // aqui uso alert simples; você pode trocar por Toasts do Bootstrap
            alert("Produto adicionado ao carrinho!");
          } catch (outerErr) {
            console.error("Erro no listener de adicionar ao carrinho:", outerErr);
            alert("Erro ao adicionar ao carrinho. Veja console (F12).");
          }
        });
      });
    });
  }

  // Inicializa tudo
  initCheckboxListeners();
  initAddButtons();

  // Se no futuro você recarregar produtos dinamicamente, chame initCheckboxListeners() e initAddButtons() novamente.
  console.log("Eventos iniciais configurados.");
});
