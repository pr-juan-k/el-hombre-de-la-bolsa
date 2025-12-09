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

  // Formulario de usuario
 

  // Formulario de producto
  document.getElementById("formProducto").addEventListener("submit", (e) => {
    e.preventDefault()
    guardarProducto()
  })

  // Preview de foto
  document.getElementById("fotoProducto").addEventListener("change", (e) => {
    previsualizarFoto(e)
  })

  // Calcular precio amigo automáticamente (15% descuento)
  document.getElementById("precioTotal").addEventListener("input", function () {
    const precioTotal = Number.parseFloat(this.value) || 0
    const precioAmigo = precioTotal * 0.85 // 15% descuento
    document.getElementById("precioAmigo").value = precioAmigo.toFixed(2)
  })
}

// ==================== GESTIÓN DE USUARIOS ====================

function mostrarFormUsuario() {
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

function previsualizarFoto(e) {
  const file = e.target.files[0]
  const preview = document.getElementById("previewFoto")

  if (file) {
    const reader = new FileReader()

    reader.onload = (event) => {
      preview.innerHTML = `<img src="${event.target.result}" alt="Preview">`
    }

    reader.readAsDataURL(file)
  } else {
    preview.innerHTML = ""
  }
}

function guardarProducto() {
  const fotoInput = document.getElementById("fotoProducto")
  const descripcion = document.getElementById("descripcionProducto").value.trim()
  const cantidad = document.getElementById("cantidadProducto").value.trim()
  const precioUnitario = document.getElementById("precioUnitario").value
  const precioTotal = document.getElementById("precioTotal").value
  const precioAmigo = document.getElementById("precioAmigo").value
  const categoria = document.getElementById("categoriaProducto").value

  if (
    !fotoInput.files[0] ||
    !descripcion ||
    !cantidad ||
    !precioUnitario ||
    !precioTotal ||
    !precioAmigo ||
    !categoria
  ) {
    alert("Por favor, complete todos los campos")
    return
  }

  // Crear FormData para enviar archivo y datos
  const formData = new FormData()
  formData.append("accion", "guardarProducto")
  formData.append("foto", fotoInput.files[0])
  formData.append("descripcion", descripcion)
  formData.append("cantidad", cantidad)
  formData.append("precioUnitario", precioUnitario)
  formData.append("precioTotal", precioTotal)
  formData.append("precioAmigo", precioAmigo)
  formData.append("categoria", categoria)

  fetch(API_URL, {
    method: "POST",
    body: formData,
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        // Mostrar mensaje de éxito
        const mensaje = document.getElementById("mensajeExito")
        mensaje.classList.remove("hidden")

        setTimeout(() => {
          mensaje.classList.add("hidden")
        }, 3000)

        // Limpiar formulario
        document.getElementById("formProducto").reset()
        document.getElementById("previewFoto").innerHTML = ""

        console.log("Producto guardado:", data.producto)
      } else {
        alert(data.error || "Error al guardar el producto")
      }
    })
    .catch((error) => {
      console.error("Error:", error)
      alert("Error al conectar con el servidor")
    })
}
