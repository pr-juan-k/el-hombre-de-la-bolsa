// Simulación de almacenamiento (posteriormente se conectará con PHP y archivos .txt)
// Los datos se guardarán en localStorage temporalmente

const API_URL = "cargarphp.php"

// Inicializar al cargar la página
document.addEventListener("DOMContentLoaded", () => {
  inicializarFormularios()
  cargarUsuarios()
})

function inicializarFormularios() {
  // Botón dar de alta usuario
  document.getElementById("btnAltaUsuario").addEventListener("click", () => {
    mostrarFormUsuario()
  })

  // Botón ver usuarios
  document.getElementById("btnVerUsuarios").addEventListener("click", () => {
    mostrarTablaUsuarios()
  })
  //Boton ver Formulario
  document.getElementById("btnCargarProducto").addEventListener("click",()=>{
    mostrarFormProducto()
  })
  //Boton ver tabla productos
  document.getElementById("btnVerProducto").addEventListener("click",()=>{
    mostrarTablaProductos()
  })

  // Formulario de usuario
 

}

// ==================== GESTIÓN DE BOTONES ====================
//Productos acciones Botones
function mostrarTablaProductos()
{
  const btnTablaProducto = document.getElementById("btnVerProducto");
  const tablaProductos = document.getElementById("tablaProductosContainer1");

  tablaProductos.classList.remove("hidden");
}
function cerrarT()
{
  const tablaProductos1 = document.getElementById("tablaProductosContainer1");

  tablaProductos1.classList.add("hidden");
}
function mostrarFormProducto()
{
  const btnFormProduc = document.getElementById("btnCargarProducto");
  const formularioProducto = document.getElementById("formularioProducto");

  //btnFormProduc.classList.add("hidden");
  formularioProducto.classList.remove("hidden");
}
function cerrarFormUsuarioProducto() 
{
  //btnFormProduc.classList.remove("hidden");
  formularioProducto.classList.add("hidden");
}
// Usuarios acciones Botones
function mostrarFormUsuario() 
{
  const formContainer = document.getElementById("formUsuarioContainer")
  const tablaContainer = document.getElementById("tablaUsuariosContainer")

  tablaContainer.classList.add("hidden")
  formContainer.classList.remove("hidden")
}

function cerrarFormUsuario() {
  document.getElementById("formUsuarioContainer").classList.add("hidden")
  document.getElementById("formUsuario").reset()
}

function mostrarTablaUsuarios() {
  const formContainer = document.getElementById("formUsuarioContainer")
  const tablaContainer = document.getElementById("tablaUsuariosContainer")

  formContainer.classList.add("hidden")
  tablaContainer.classList.remove("hidden")

  cargarUsuarios()
}

function cerrarTablaUsuarios() {
  document.getElementById("tablaUsuariosContainer").classList.add("hidden")
}


//Cargar usuarios en TXT
function cargarUsuarios() {
  fetch(`${API_URL}?accion=cargarUsuarios`)
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        mostrarUsuariosEnTabla(data.usuarios)
      } else {
        console.error("Error al cargar usuarios:", data.error)
      }
    })
    .catch((error) => {
      console.error("Error:", error)
    })
}



function eliminarUsuario(id) {
  if (!confirm("¿Está seguro de eliminar este usuario?")) {
    return
  }

  const formData = new FormData()
  formData.append("accion", "eliminarUsuario")
  formData.append("id", id)

  fetch(API_URL, {
    method: "POST",
    body: formData,
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        alert(data.mensaje)
        cargarUsuarios()
      } else {
        alert(data.error || "Error al eliminar el usuario")
      }
    })
    .catch((error) => {
      console.error("Error:", error)
      alert("Error al conectar con el servidor")
    })
}

// ==================== CAlcula precio total automatico ====================

document.addEventListener('DOMContentLoaded', function() {
  // Seleccionamos los elementos
  const inputCantidad = document.getElementById('cantidadProducto');
  const inputPrecioUnitario = document.getElementById('precioUnitario');
  const inputPrecioTotal = document.getElementById('precioTotal');

  // Función para calcular
  function calcular() {
      // Convertimos a número y usamos 0 si el campo está vacío
      const cantidad = parseFloat(inputCantidad.value) || 0;
      const precioUnitario = parseFloat(inputPrecioUnitario.value) || 0;

      // Multiplicamos
      const total = (cantidad * precioUnitario) * 1.36;

      // Mostramos el resultado con 2 decimales
      inputPrecioTotal.value = total.toFixed(2);
  }

  // Escuchamos el evento 'input' en ambos campos
  inputCantidad.addEventListener('input', calcular);
  inputPrecioUnitario.addEventListener('input', calcular);
});

function inicializarTablaAdmin() {
  const inputBusqueda = document.getElementById("inputBusquedaAdmin");
  const btnVerMas = document.getElementById("btnVerMasAdmin");
  const bodyTabla = document.getElementById("bodyAdminProductos");
  
  if (!inputBusqueda || !bodyTabla) return;

  let limiteVisible = 40;

  function filtrarYPaginar() {
      const termino = inputBusqueda.value.toLowerCase().trim();
      const filas = Array.from(bodyTabla.querySelectorAll(".fila-producto-admin"));
      
      // 1. Limpiar resaltados previos
      filas.forEach(f => f.classList.remove('producto-resaltado'));

      if (termino === "") {
          // Si no hay búsqueda, mostramos los primeros 40 en orden original
          filas.forEach((fila, index) => {
              fila.style.display = (index < limiteVisible) ? "" : "none";
          });
          btnVerMas.style.display = (filas.length > limiteVisible) ? "inline-block" : "none";
          return;
      }

      // 2. Separar filas que coinciden de las que no
      const coincidencias = [];
      const noCoincidencias = [];

      filas.forEach(fila => {
          const nombre = fila.getAttribute("data-nombre") || "";
          const codigo = fila.getAttribute("data-codigo") || "";

          if (nombre.includes(termino) || codigo.includes(termino)) {
              fila.classList.add('producto-resaltado'); // Pintamos el producto
              coincidencias.push(fila);
          } else {
              noCoincidencias.push(fila);
          }
      });

      // 3. REORDENAR EL DOM: Mover coincidencias al principio
      // Vaciamos el body y reinsertamos en el nuevo orden
      coincidencias.forEach(f => {
          f.style.display = ""; // Siempre mostrar los que coinciden
          bodyTabla.appendChild(f);
      });
      
      noCoincidencias.forEach(f => {
          f.style.display = "none"; // Ocultar el resto para que solo queden los buscados
          bodyTabla.appendChild(f);
      });

      // Ocultar botón "Ver más" durante la búsqueda para evitar confusión
      btnVerMas.style.display = "none";
  }

  inputBusqueda.addEventListener("input", filtrarYPaginar);

  btnVerMas.onclick = function() {
      limiteVisible += 40;
      filtrarYPaginar();
  };

  filtrarYPaginar();
}

document.addEventListener("DOMContentLoaded", inicializarTablaAdmin);

