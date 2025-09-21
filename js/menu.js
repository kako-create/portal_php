// jQuery só para o efeito de hover do submenu
$(document).ready(function () {
  $("#menu li").hover(
    function () {
      $(this).children("ul.submenu").stop(true, true).slideDown(200);
    },
    function () {
      $(this).children("ul.submenu").stop(true, true).slideUp(200);
    }
  );
});

// Vanilla JS para interceptar os cliques e carregar via AJAX
document.addEventListener("DOMContentLoaded", () => {
  document.querySelectorAll("#menu a").forEach((link) => {
    link.addEventListener("click", function (e) {
      e.preventDefault();

      const url = this.getAttribute("href"); // ex: ?go=edital

      fetch("index.php" + url)
        .then((res) => res.text())
        .then((html) => {
          const temp = document.createElement("div");
          temp.innerHTML = html;
          const novo = temp.querySelector("#conteudo");
          if (novo) {
            document.querySelector("#conteudo").innerHTML = novo.innerHTML;
          }
        })
        .catch((err) => console.error("Erro ao carregar:", err));
    });
  });
});
