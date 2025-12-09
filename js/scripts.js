// Logica titulo en Producto segun boton seleccionado

// Paso 1: Seleccionar todos los botones de categoría
const categoryButtons = document.querySelectorAll(".category-button");

// Paso 2: Iterar y adjuntar un manejador de eventos a cada botón
categoryButtons.forEach(button => {
    button.addEventListener("click", () => {
        
        // 1. Obtener el valor de la categoría del atributo data-category
        // Para el botón 'Supers', esto captura el texto "Supers".
        const category = button.getAttribute("data-category");
        
        // 2. Redirigir a productos.html, agregando el dato a la URL
        // Usamos 'cat' como el nombre del parámetro.
        // La URL final se verá así: productos.html?cat=Supers
        window.location.href = `producto.html?cat=${encodeURIComponent(category)}`;
    });
});


const menuBtn = document.getElementById("menuBtn")
const closeMenuBtn = document.getElementById("closeMenuBtn")
const menuOverlay = document.getElementById("menuOverlay")
const menuLinks = document.querySelectorAll(".menu-nav a")

// Abrir menú
menuBtn.addEventListener("click", (e) => {
    e.stopPropagation();
  
    // 1. Verificar el estado actual del menú/botón
    const isMenuOpen = menuBtn.classList.contains("active");
  
    if (isMenuOpen) {
      // Si ya tiene 'active', significa que es el botón de CERRAR (la X)
      closeMenu();
    } else {
      // Si NO tiene 'active', significa que es el botón de ABRIR (la hamburguesa)
      menuOverlay.classList.add("active");
      menuBtn.classList.add("active");
      document.body.style.overflow = "hidden";
    }
  });


// Cerrar menú
function closeMenu() {
  menuOverlay.classList.remove("active")
  menuBtn.classList.remove("active")
  document.body.style.overflow = ""
}

closeMenuBtn.addEventListener("click", (e) => {
  e.stopPropagation()
  closeMenu()
})

// Cerrar al hacer clic fuera del contenido del menú
menuOverlay.addEventListener("click", (e) => {
  if (e.target === menuOverlay) {
    closeMenu()
  }
})

// Cerrar menú al hacer clic en un enlace
menuLinks.forEach((link) => {
  link.addEventListener("click", closeMenu)
})

// Cerrar con tecla Escape
document.addEventListener("keydown", (e) => {
  if (e.key === "Escape" && menuOverlay.classList.contains("active")) {
    closeMenu()
  }
})

const clientTypeBtn = document.getElementById("clientTypeBtn")
const clientTypeLabel = document.getElementById("clientTypeLabel")
const clientDropdown = document.getElementById("clientDropdown")
const clientOptions = document.querySelectorAll(".client-option")
const friendCodeContainer = document.getElementById("friendCodeContainer")
const friendCodeInput = document.getElementById("friendCodeInput")
const applyCodeBtn = document.getElementById("applyCodeBtn")

let currentClientType = "cliente"
let friendCode = ""

// Toggle dropdown
clientTypeBtn.addEventListener("click", (e) => {
  e.stopPropagation()
  clientTypeBtn.classList.toggle("active")
  clientDropdown.classList.toggle("active")
})

// Cerrar dropdown al hacer clic fuera
document.addEventListener("click", (e) => {
  if (!clientTypeBtn.contains(e.target) && !clientDropdown.contains(e.target)) {
    clientTypeBtn.classList.remove("active")
    clientDropdown.classList.remove("active")
  }
})

// Seleccionar tipo de cliente
clientOptions.forEach((option) => {
    option.addEventListener("click", (e) => {
      const type = e.target.dataset.type
      currentClientType = type
  
      // 🎯 PUNTO DE MODIFICACIÓN 🎯
      if (type === "cliente") {
        clientTypeLabel.textContent = "Cliente"
        friendCodeContainer.classList.remove("active")
        // Mostrar el ícono: el contenedor del botón (clientTypeBtn) vuelve a su estado normal.
        clientTypeBtn.classList.remove("hide-content-on-select") 
      } else if (type === "especial") {
        clientTypeLabel.textContent = "Tengo codigo"
        friendCodeContainer.classList.add("active")
        // Ocultar el ícono: añadimos una clase para que CSS lo oculte.
        clientTypeBtn.classList.add("hide-content-on-select")
        clientTypeBtn.classList.add("hidden") 
        
      }
  
      // Cerrar dropdown
      clientTypeBtn.classList.remove("active")
      clientDropdown.classList.remove("active")
  
      console.log("[v0] Tipo de cliente seleccionado:", type)
    })
  })

// Aplicar código de cliente amigo
applyCodeBtn.addEventListener("click", () => {
  friendCode = friendCodeInput.value.trim()
  if (friendCode) {
    alert(`Código de cliente amigo aplicado: ${friendCode}`)
    console.log("[v0] Código de cliente amigo:", friendCode)
    // Aquí puedes agregar lógica PHP para validar el código
  } else {
    alert("Por favor ingresa un código válido")
  }
})

const scrollToProductsBtn = document.getElementById("scrollToProductsBtn")
const productsSection = document.getElementById("products")

scrollToProductsBtn.addEventListener("click", () => {
  productsSection.scrollIntoView({
    behavior: "smooth",
    block: "start",
  })
})

// Prevenir envío del formulario de búsqueda (para demo)
document.querySelector(".search-form").addEventListener("submit", (e) => {
  e.preventDefault()
  const searchValue = document.querySelector(".search-input").value
  if (searchValue.trim()) {
    alert(`Buscando (tipo: ${currentClientType}): ${searchValue}`)
    console.log("[v0] Búsqueda:", searchValue, "Cliente:", currentClientType)
    // Aquí podrías agregar la lógica para buscar en archivos .txt con PHP
  }
})



