// Código que permite realizar el cambio de modo oscuro/claro de la aplicación, se almacena para conservarlo en toda la navegación

// Verificar y aplicar el estado del modo al cargar la página
document.addEventListener("DOMContentLoaded", function() {
    // Leer el estado almacenado del modo
    var modoAlmacenado = localStorage.getItem("modo");
  
    // Verificar y aplicar el estado del modo en el body
    if (modoAlmacenado === "oscuro") {
      document.body.classList.add("modoOscuro");
      document.getElementById("toggleBtn").classList.add("modo-oscuro");
    } else {
      document.body.classList.remove("modoOscuro");
      document.getElementById("toggleBtn").classList.add("modo-claro");
    }
  });
  
  function modoOscuro() {
    var element = document.body;
    element.classList.toggle("modoOscuro");
  
    // Obtener el estado actual del modo
    var modoActual = element.classList.contains("modoOscuro") ? "oscuro" : "claro";
  
    // Guardar el estado en el almacenamiento local
    localStorage.setItem("modo", modoActual);
  
    // Actualizar el estilo del botón según el modo
    var toggleBtn = document.getElementsByClassName("toggleBtn");
    toggleBtn.classList.toggle("modo-claro");
    toggleBtn.classList.toggle("modo-oscuro");
  }
  