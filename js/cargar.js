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

// ==================== GESTIÓN DE PRODUCTOS ====================
