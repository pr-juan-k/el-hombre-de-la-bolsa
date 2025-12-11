// ==========================================
// 1. LÓGICA DE NAVEGACIÓN Y MENÚ (Tu código original)
// ==========================================

// Logica titulo en Producto segun boton seleccionado
const categoryButtons = document.querySelectorAll(".category-button");

categoryButtons.forEach(button => {
    button.addEventListener("click", () => {
        const category = button.getAttribute("data-category");
        window.location.href = `producto.html?cat=${encodeURIComponent(category)}`;
    });
});

const menuBtn = document.getElementById("menuBtn");
const closeMenuBtn = document.getElementById("closeMenuBtn");
const menuOverlay = document.getElementById("menuOverlay");
const menuLinks = document.querySelectorAll(".menu-nav a");

// Abrir menú
if(menuBtn) {
    menuBtn.addEventListener("click", (e) => {
        e.stopPropagation();
        const isMenuOpen = menuBtn.classList.contains("active");
        if (isMenuOpen) {
            closeMenu();
        } else {
            menuOverlay.classList.add("active");
            menuBtn.classList.add("active");
            document.body.style.overflow = "hidden";
        }
    });
}

// Cerrar menú
function closeMenu() {
    menuOverlay.classList.remove("active");
    menuBtn.classList.remove("active");
    document.body.style.overflow = "";
}

if(closeMenuBtn) {
    closeMenuBtn.addEventListener("click", (e) => {
        e.stopPropagation();
        closeMenu();
    });
}

// Cerrar al hacer clic fuera
if(menuOverlay) {
    menuOverlay.addEventListener("click", (e) => {
        if (e.target === menuOverlay) {
            closeMenu();
        }
    });
}

menuLinks.forEach((link) => {
    link.addEventListener("click", closeMenu);
});

document.addEventListener("keydown", (e) => {
    if (e.key === "Escape" && menuOverlay && menuOverlay.classList.contains("active")) {
        closeMenu();
    }
});

// Lógica de Cliente / Dropdown
const clientTypeBtn = document.getElementById("clientTypeBtn");
const clientTypeLabel = document.getElementById("clientTypeLabel");
const clientDropdown = document.getElementById("clientDropdown");
const clientOptions = document.querySelectorAll(".client-option");
const friendCodeContainer = document.getElementById("friendCodeContainer");
const friendCodeInput = document.getElementById("friendCodeInput");
const applyCodeBtn = document.getElementById("applyCodeBtn");

let currentClientType = "cliente";
let friendCode = "";

if(clientTypeBtn) {
    clientTypeBtn.addEventListener("click", (e) => {
        e.stopPropagation();
        clientTypeBtn.classList.toggle("active");
        clientDropdown.classList.toggle("active");
    });

    document.addEventListener("click", (e) => {
        if (!clientTypeBtn.contains(e.target) && !clientDropdown.contains(e.target)) {
            clientTypeBtn.classList.remove("active");
            clientDropdown.classList.remove("active");
        }
    });
}

clientOptions.forEach((option) => {
    option.addEventListener("click", (e) => {
        const type = e.target.dataset.type;
        currentClientType = type;

        if (type === "cliente") {
            clientTypeLabel.textContent = "Cliente";
            friendCodeContainer.classList.remove("active");
            clientTypeBtn.classList.remove("hide-content-on-select");
        } else if (type === "especial") {
            clientTypeLabel.textContent = "Tengo codigo";
            friendCodeContainer.classList.add("active");
            clientTypeBtn.classList.add("hide-content-on-select");
            clientTypeBtn.classList.add("hidden");
        }

        clientTypeBtn.classList.remove("active");
        clientDropdown.classList.remove("active");
    });
});

if(applyCodeBtn) {
    applyCodeBtn.addEventListener("click", () => {
        friendCode = friendCodeInput.value.trim();
        if (friendCode) {
            alert(`Código de cliente amigo aplicado: ${friendCode}`);
        } else {
            alert("Por favor ingresa un código válido");
        }
    });
}

// Scroll y Buscador
const scrollToProductsBtn = document.getElementById("scrollToProductsBtn");
const productsSection = document.getElementById("products");

if(scrollToProductsBtn && productsSection) {
    scrollToProductsBtn.addEventListener("click", () => {
        productsSection.scrollIntoView({ behavior: "smooth", block: "start" });
    });
}

const searchForm = document.querySelector(".search-form");
if(searchForm) {
    searchForm.addEventListener("submit", (e) => {
        e.preventDefault();
        const searchValue = document.querySelector(".search-input").value;
        if (searchValue.trim()) {
            alert(`Buscando (tipo: ${currentClientType}): ${searchValue}`);
        }
    });
}


// ==========================================
// 2. EFECTO CARRUSEL (WHEEL) CORREGIDO
// ==========================================

document.addEventListener('DOMContentLoaded', () => {
    const container = document.querySelector('.buttons-container');
    // Si no existe el contenedor, no ejecutamos nada del carrusel para evitar errores
    if (!container) return; 

    const buttons = Array.from(document.querySelectorAll('.category-button'));

    // CONFIGURACIÓN
    let currentIndex = 0;
    const itemHeight = 140; 
    const visibleItems = 2; 
    
    // Variables para control táctil
    let startY = 0;
    let currentY = 0;
    let isDragging = false;
    let dragThreshold = 30;

    // A) FUNCIÓN DE RENDERIZADO
    function updateCarousel() {
        buttons.forEach((btn, index) => {
            const dist = index - currentIndex;

            if (Math.abs(dist) > visibleItems + 1) {
                btn.style.visibility = 'hidden';
                return;
            }
            btn.style.visibility = 'visible';

            // Cálculos visuales
            const opacity = Math.max(0, 1 - Math.abs(dist) * 0.35);
            const scale = Math.max(0.7, 1 - Math.abs(dist) * 0.15);
            const translateY = dist * itemHeight;
            const rotateX = dist * -20; 
            const zIndex = 100 - Math.abs(dist);
            const blur = Math.abs(dist) * 2; 

            // Aplicar estilos
            btn.style.zIndex = zIndex;
            btn.style.opacity = opacity;
            btn.style.transform = `translate(-50%, calc(-50% + ${translateY}px)) scale(${scale}) perspective(1000px) rotateX(${rotateX}deg)`;
            btn.style.filter = `blur(${blur}px)`;
            
            // Clase activa
            if (dist === 0) btn.classList.add('active');
            else btn.classList.remove('active');
            
            // Permitir click solo en el central (y para navegación en los laterales)
            btn.style.pointerEvents = 'auto'; 
        });
    }

    // B) EVENTOS MOUSE (Rueda)
    container.addEventListener('wheel', (e) => {
        e.preventDefault(); 
        if (e.deltaY > 0) nextItem();
        else prevItem();
    }, { passive: false }); // Agregado passive: false por seguridad

    // C) EVENTOS CLICK EN LATERALES
    buttons.forEach((btn, index) => {
        btn.addEventListener('click', (e) => {
            if (currentIndex !== index) {
                e.preventDefault(); 
                e.stopPropagation(); // Detener propagación para evitar saltos raros
                currentIndex = index;
                updateCarousel();
            }
        });
    });

    // D) EVENTOS TÁCTILES (CORREGIDO EL DOBLE SCROLL)
    
    // Al tocar la pantalla
    container.addEventListener('touchstart', (e) => {
        startY = e.touches[0].clientY;
        isDragging = true;
    }, { passive: false }); // Importante: passive: false permite usar preventDefault

    // Al mover el dedo
    container.addEventListener('touchmove', (e) => {
        if (!isDragging) return;
        
        // ¡ESTA ES LA CORRECCIÓN!
        // Evita que la página completa haga scroll mientras mueves la rueda
        if (e.cancelable) {
            e.preventDefault(); 
        }
        
        currentY = e.touches[0].clientY;
    }, { passive: false }); // Importante: passive: false es obligatorio aquí

    // Al soltar el dedo
    container.addEventListener('touchend', () => {
        if (!isDragging) return;
        
        // Si currentY es 0 significa que solo fue un tap, no un arrastre
        if (currentY === 0) { 
            isDragging = false; 
            return; 
        }

        const diff = startY - currentY;

        if (Math.abs(diff) > dragThreshold) {
            if (diff > 0) {
                nextItem(); // Deslizó hacia arriba
            } else {
                prevItem(); // Deslizó hacia abajo
            }
        }
        
        // Reset variables
        isDragging = false;
        currentY = 0; 
    });

    // Funciones auxiliares
    function nextItem() {
        if (currentIndex < buttons.length - 1) {
            currentIndex++;
            updateCarousel();
        }
    }

    function prevItem() {
        if (currentIndex > 0) {
            currentIndex--;
            updateCarousel();
        }
    }

    

    // Inicializar
    updateCarousel();
});