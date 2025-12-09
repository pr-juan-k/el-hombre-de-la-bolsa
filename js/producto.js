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

// ---------------------------------------------------------
// Botón Descargar catálogo  (corregido: faltaba variable category)
// ---------------------------------------------------------
document.getElementById("downloadCatalog").addEventListener("click", () => {
  const params = new URLSearchParams(window.location.search);
  const category = params.get("cat") || "categoría seleccionada"; // 🔧 CAMBIO
  alert("Descargando catálogo de " + category + "...\nEsta funcionalidad se implementará con el backend.");
});

// ---------------------------------------------------------
// Datos de productos
// ---------------------------------------------------------
const productsData = {
  plasticos: [
    { descripcion: "Bolsa Plástica Pequeña 20x30cm", cantidad: "100 unidades", precioUnitario: "$150", total: "$15000" },
    { descripcion: "Bolsa Plástica Mediana 30x40cm", cantidad: "100 unidades", precioUnitario: "$200", total: "$20000" },
    { descripcion: "Bolsa Plástica Grande 40x60cm", cantidad: "50 unidades", precioUnitario: "$180", total: "$9000" },
    { descripcion: "Bolsa Camiseta Reforzada", cantidad: "200 unidades", precioUnitario: "$120", total: "$24000" },
  ],
  cartones: [
    { descripcion: "Caja Cartón Corrugado 30x30x30cm", cantidad: "25 unidades", precioUnitario: "$350", total: "$8750" },
    { descripcion: "Caja Cartón Corrugado 40x40x40cm", cantidad: "20 unidades", precioUnitario: "$450", total: "$9000" },
    { descripcion: "Caja Cartón Corrugado 50x50x50cm", cantidad: "15 unidades", precioUnitario: "$550", total: "$8250" },
    { descripcion: "Plancha Cartón Micro-Corrugado", cantidad: "50 unidades", precioUnitario: "$180", total: "$9000" },
  ],
  bolsas: [
    { descripcion: "Bolsa Papel Kraft Pequeña", cantidad: "100 unidades", precioUnitario: "$220", total: "$22000" },
    { descripcion: "Bolsa Papel Kraft Mediana", cantidad: "100 unidades", precioUnitario: "$280", total: "$28000" },
    { descripcion: "Bolsa Papel Kraft Grande", cantidad: "50 unidades", precioUnitario: "$250", total: "$12500" },
    { descripcion: "Bolsa Biodegradable Ecológica", cantidad: "100 unidades", precioUnitario: "$320", total: "$32000" },
  ],
};

// ---------------------------------------------------------
// Variables necesarias para descuentos
// ---------------------------------------------------------
let clientType = "normal"; // 🔧 CAMBIO: agregué clientType para evitar errores

// ---------------------------------------------------------
// Botones de categoría
// ---------------------------------------------------------
const categoryButtons = document.querySelectorAll(".category-btn");
const productsTableContainer = document.getElementById("productsTableContainer");
const productsTableBody = document.getElementById("productsTableBody");
const tableTitle = document.getElementById("tableTitle");

categoryButtons.forEach((button) => {
  button.addEventListener("click", function () {
    const selectedCategory = this.getAttribute("data-category");

    categoryButtons.forEach((btn) => btn.classList.remove("active"));
    this.classList.add("active");

    productsTableContainer.style.display = "block";
    tableTitle.textContent = this.querySelector("span").textContent;

    populateTable(selectedCategory);

    setTimeout(() => {
      productsTableContainer.scrollIntoView({ behavior: "smooth", block: "start" });
    }, 100);
  });
});

// ---------------------------------------------------------
// Poblar la tabla
// ---------------------------------------------------------
function populateTable(category) {
  const products = productsData[category] || [];

  productsTableBody.innerHTML = "";

  products.forEach((product) => {
    const discountedPrice = applyClientDiscount(product.precioUnitario, clientType);
    const discountedTotal = applyClientDiscount(product.total, clientType);

    const row = document.createElement("tr");
    row.innerHTML = `
      <td>
        <div class="product-photo">
          <svg fill="currentColor" viewBox="0 0 24 24">
            <path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/>
          </svg>
        </div>
      </td>
      <td>${product.descripcion}</td>
      <td>${product.cantidad}</td>
      <td>${discountedPrice}</td>
      <td>${discountedTotal}</td>
    `;
    productsTableBody.appendChild(row);
  });
}

// ---------------------------------------------------------
// Función de descuentos
// ---------------------------------------------------------
function applyClientDiscount(price, clientType) {
  const basePrice = Number.parseFloat(price.replace(/[^0-9]/g, ""));

  if (clientType === "especial") {
    return "$" + Math.round(basePrice * 0.9).toLocaleString();
  } else if (clientType === "amigo") {
    return "$" + Math.round(basePrice * 0.85).toLocaleString();
  }

  return price;
}

// ---------------------------------------------------------
// Buscador en la tabla
// ---------------------------------------------------------
const searchInput = document.getElementById("productSearch");

searchInput.addEventListener("input", function () {
  const searchTerm = this.value.toLowerCase();
  const rows = productsTableBody.querySelectorAll("tr");

  rows.forEach((row) => {
    row.style.display = row.textContent.toLowerCase().includes(searchTerm)
      ? ""
      : "none";
  });
});
