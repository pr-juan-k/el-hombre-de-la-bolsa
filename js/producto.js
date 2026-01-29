// ---------------------------------------------------------
// Mostrar título según el parámetro ?cat= en la URL
// ---------------------------------------------------------
document.addEventListener("DOMContentLoaded", () => {
  const titleElement = document.getElementById("categoryTitle");
  const params = new URLSearchParams(window.location.search);
  const categoryName = params.get("cat");

  if (categoryName && titleElement) {
    titleElement.textContent = categoryName;
  }
});

// ---------------------------------------------------------
// Botón VOLVER
// ---------------------------------------------------------
document.getElementById("backBtn").addEventListener("click", () => {
  window.location.href = "index.html";
});

// Único evento para el botón de descarga
document.addEventListener('DOMContentLoaded', () => {
  const downloadBtn = document.getElementById('downloadCatalog');

  if (downloadBtn) {
      downloadBtn.addEventListener('click', () => {
          const urlParams = window.location.search;
          const params = new URLSearchParams(urlParams);
          const category = params.get("cat") || "general";

          // Aviso visual al usuario
          console.log("Generando PDF de " + category + "...");
          
          // Redirigimos al generador de PDF
          window.location.href = 'generar_pdf.php' + urlParams;
      });
  }
});


