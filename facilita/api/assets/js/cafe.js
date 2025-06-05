document.addEventListener("DOMContentLoaded", function () {
  const modal = document.getElementById("modal-receita");
  const btnFechar = document.querySelector(".fechar-modal");

  const tituloModal = document.getElementById("modal-titulo");
  const descricaoModal = document.getElementById("modal-descricao");
  const precoModal = document.getElementById("modal-preco");

  const lista = document.getElementById("lista-receitas");

  lista.addEventListener("click", function (e) {
    if (e.target.tagName === "BUTTON") {
      const item = e.target.closest("li");

      // Simulando dados — em app real, você buscaria isso dinamicamente
      const nome = item.querySelector("span").textContent;
      const descricao = "Exemplo de receita bla bla bla";
      const preco = "R$ 8,50";

      tituloModal.textContent = nome;
      descricaoModal.textContent = descricao;
      precoModal.textContent = `Preço: ${preco}`;

      modal.style.display = "flex";
    }
  });

  btnFechar.addEventListener("click", () => {
    modal.style.display = "none";
  });

  window.addEventListener("click", (e) => {
    if (e.target === modal) {
      modal.style.display = "none";
    }
  });
});
