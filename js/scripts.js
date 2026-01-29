// ==========================================
// 1. LÓGICA DE NAVEGACIÓN Y MENÚ (Tu código original)
// ==========================================



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
// selecion de rubro Y VERIFICA CODIGO

document.addEventListener('DOMContentLoaded', () => {
    localStorage.clear();
    console.log("LocalStorage limpiado automáticamente.");
    // --- 1. ELEMENTOS (Asegúrate de que estos IDs coincidan con tu HTML) ---
    const revendedorBtn = document.getElementById('revendedorBtn');
    const modal = document.getElementById('modalRevendedor');
    const closeModalX = document.getElementById('closeModalRev');
    const btnValidarModal = document.getElementById('btnValidarModal');
    const inputCodigoModal = document.getElementById('inputCodigoModal');
    const mensajeModal = document.getElementById('mensajeModal');
    
    // El contenedor del botón de catálogo
    const catalogContainer = document.getElementById('catalogContainer');

    // --- 2. FUNCIÓN PARA MOSTRAR/OCULTAR CATÁLOGO ---
    function actualizarVisibilidadCatalogo(indice) {
        if (!catalogContainer) return;

        // Convertimos a número el índice (r0=6, r10=7, r50=8, r100=9)
        const idx = parseInt(indice);
        const indicesRevendedores = [6, 7, 8, 9];

        if (indicesRevendedores.includes(idx)) {
            catalogContainer.style.display = 'block'; // Lo mostramos
        } else {
            catalogContainer.style.display = 'none';  // Lo ocultamos
        }
    }

    // --- 3. CHEQUEO INICIAL (Al cargar la página) ---
    // Si el usuario ya tenía un código válido guardado, mostramos el botón
    const indiceGuardado = localStorage.getItem('cliente_descuento');
    if (indiceGuardado) {
        actualizarVisibilidadCatalogo(indiceGuardado);
    }

    // --- 4. ABRIR Y CERRAR MODAL ---
    if(revendedorBtn) {
        revendedorBtn.addEventListener('click', () => {
            modal.style.display = 'flex';
        });
    }

    if(closeModalX) {
        closeModalX.addEventListener('click', () => {
            modal.style.display = 'none';
        });
    }

    window.addEventListener('click', (e) => {
        if (e.target == modal) modal.style.display = 'none';
    });

    // --- 5. FUNCIÓN DE VALIDACIÓN (MODIFICADA PARA EL CATÁLOGO) ---
    function validarCodigo(codigo) {
        if (!codigo) {
            alert("Por favor ingresa un código");
            return;
        }

        const formData = new FormData();
        formData.append('codigo', codigo);

        fetch('verificar_codigo.php', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                // GUARDAR EN LOCALSTORAGE
                localStorage.setItem('cliente_nombre', data.nombre);
                localStorage.setItem('cliente_descuento', data.indice);

                // --- LOGICA DEL BOTÓN: Activamos el botón si es revendedor ---
                actualizarVisibilidadCatalogo(data.indice);

                // Feedback en el modal
                mensajeModal.innerHTML = `<span style="color: #059669;">✅ Bienvenido ${data.nombre}. Precios aplicados.</span>`;
                
                const friendCodeContainer = document.getElementById('friendCodeContainer');
                if(friendCodeContainer) {
                    friendCodeContainer.innerHTML = `<div class="success-code-msg" style="color:#059669; font-weight:bold; padding:10px;">✅ Código de ${data.nombre} aplicado</div>`;
                }

                setTimeout(() => { modal.style.display = 'none'; }, 1500);

            } else {
                mensajeModal.innerHTML = `<span style="color: #b91c1c;">❌ ${data.message}</span>`;
                // Si el código es inválido, ocultamos el botón de catálogo
                if(catalogContainer) catalogContainer.style.display = 'none';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            mensajeModal.innerHTML = `<span style="color: #b91c1c;">Hubo un error al validar.</span>`;
        });
    }


    // Acción del botón catálogo
const btnCatalog = document.getElementById('btnDescargarCatalogo');
if(btnCatalog) {
    btnCatalog.addEventListener('click', () => {
        // Obtenemos el descuento guardado (6, 7, 8 o 9)
        const descEspecial = localStorage.getItem('cliente_descuento');
        
        if (descEspecial) {
            // Redirigimos al nuevo PHP pasando el nivel de precios
            window.location.href = `generar_catalogo_completo.php?desc=${descEspecial}`;
        } else {
            alert("Error: No se encontró el nivel de descuento. Por favor reingrese su código.");
        }
    });
}

    // --- 6. EVENTOS DE CLIC ---
    if(btnValidarModal) {
        btnValidarModal.addEventListener('click', () => {
            const codigo = inputCodigoModal.value.trim();
            validarCodigo(codigo);
        });
    }

    const applyCodeBtn = document.getElementById('applyCodeBtn');
    const friendCodeInput = document.getElementById('friendCodeInput');
    if(applyCodeBtn && friendCodeInput) {
        applyCodeBtn.addEventListener('click', () => {
            validarCodigo(friendCodeInput.value.trim());
        });
    }

    // --- 7. REDIRECCIÓN ---
    const botonesRubro = document.querySelectorAll('.category-button');
    botonesRubro.forEach(btn => {
        btn.addEventListener('click', () => {
            const rubro = btn.getAttribute('data-category').toLowerCase().trim();
            const nombreEspecial = localStorage.getItem('cliente_nombre');
            const descEspecial = localStorage.getItem('cliente_descuento');

            let url = `producto.php?cat=${encodeURIComponent(rubro)}`;
            if (nombreEspecial && descEspecial && descEspecial !== "0") {
                url += `&user=${encodeURIComponent(nombreEspecial)}&desc=${descEspecial}`;
            }
            window.location.href = url;
        });
    });
});

// ==========================================
// 4. LÓGICA solicitar codigo de revendedor
// ==========================================
document.addEventListener('DOMContentLoaded', () => {
    // Elementos de navegación
    const modal = document.getElementById('modalRevendedor');
    const cardLogin = document.getElementById('cardLogin');
    const cardSolicitud = document.getElementById('cardSolicitud');
    const linkMostrarSolicitud = document.getElementById('linkMostrarSolicitud');
    const linkVolverLogin = document.getElementById('linkVolverLogin');
    const closeButtons = document.querySelectorAll('.closeModal');

    // Elementos del formulario de solicitud
    const btnEnviarWhatsapp = document.getElementById('btnEnviarWhatsapp');

    // --- 1. NAVEGACIÓN ENTRE TARJETAS ---
    linkMostrarSolicitud.addEventListener('click', () => {
        cardLogin.style.display = 'none';
        cardSolicitud.style.display = 'block';
    });

    linkVolverLogin.addEventListener('click', () => {
        cardSolicitud.style.display = 'none';
        cardLogin.style.display = 'block';
    });

    closeButtons.forEach(btn => {
        btn.onclick = () => { modal.style.display = 'none'; };
    });

    // --- 2. LÓGICA DE WHATSAPP ---
    // --- LÓGICA DE PEDIR CODIGO POR  WHATSAPP CON CIERRE Y LIMPIEZA ---
btnEnviarWhatsapp.addEventListener('click', () => {
    // 1. Obtener los elementos de los campos
    const inputNombre = document.getElementById('reqNombre');
    const inputApellido = document.getElementById('reqApellido');
    const inputDireccion = document.getElementById('reqDireccion');
    const radioMinima = document.getElementById('minima');
    
    // 2. Obtener los valores
    const nombre = inputNombre.value.trim();
    const apellido = inputApellido.value.trim();
    const direccion = inputDireccion.value.trim();
    const tipoCompra = document.querySelector('input[name="tipoCompra"]:checked').value;

    // 3. Validación
    if (!nombre || !apellido || !direccion) {
        alert("Por favor complete todos los datos antes de enviar.");
        return;
    }

    // 4. Configuración del mensaje
    const telefono = "5493812743333"; // Tu número de WhatsApp
    const mensaje = `Hola! Quiero solicitar mi código de revendedor y la lista de precios. Mis datos son:
*Nombre:* ${nombre}
*Apellido:* ${apellido}
*Dirección:* ${direccion}
*Interés:* ${tipoCompra}`;

    // 5. Abrir WhatsApp en una nueva pestaña
    const urlWhatsApp = `https://wa.me/${telefono}?text=${encodeURIComponent(mensaje)}`;
    window.open(urlWhatsApp, '_blank');

    // --- 6. LIMPIEZA Y CIERRE (Lo que pediste) ---
    
    // Resetear los inputs de texto
    inputNombre.value = "";
    inputApellido.value = "";
    inputDireccion.value = "";
    
    // Resetear el radio button al primero por defecto
    radioMinima.checked = true;

    // Resetear la vista del modal (volver a la tarjeta de login para la próxima vez)
    cardSolicitud.style.display = 'none';
    cardLogin.style.display = 'block';

    // Cerrar el modal por completo
    modal.style.display = 'none';
});
});


// ==========================================
// 3. LÓGICA Buscador en Index
// ==========================================
// Dentro de document.addEventListener('DOMContentLoaded', () => { ...

const searchInput = document.querySelector('.search-input');
const searchSuggestions = document.getElementById('searchSuggestions');
const searchResultsBody = document.getElementById('searchResultsBody');

if (searchInput) {
    searchInput.addEventListener('input', () => {
        const query = searchInput.value.trim();
        
        // Obtener descuento actual del localStorage
        const descEspecial = localStorage.getItem('cliente_descuento') || '0';

        if (query.length > 0) {
            // Consultar al servidor
            fetch(`buscar_productos.php?q=${encodeURIComponent(query)}&desc=${descEspecial}`)
                .then(response => response.text())
                .then(html => {
                    searchResultsBody.innerHTML = html;
                    searchSuggestions.style.display = 'block'; // Mostrar la tabla
                })
                .catch(error => console.error('Error en búsqueda:', error));
        } else {
            searchSuggestions.style.display = 'none'; // Ocultar si está vacío
            searchResultsBody.innerHTML = '';
        }
    });
}

// });


