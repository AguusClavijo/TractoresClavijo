// frontend/login/login.js
document.addEventListener("DOMContentLoaded", () => {
  const formPopupContainer = document.getElementById("formPopupContainer");
  const loginPopupBtn = document.getElementById("loginPopupBtn");
  const closePopupIcon = document.getElementById("closePopupIcon");
  const toggleFormLinks = document.querySelectorAll(".toggle-form-link");

  // Obtener todos los wrappers de formularios
  const loginFormWrapper = document.getElementById("loginFormWrapper");
  const registerFormWrapper = document.getElementById("registerFormWrapper");
  const forgotPasswordFormWrapper = document.getElementById(
    "forgotPasswordFormWrapper"
  ); // Añadir este

  // Array de todos los wrappers para facilitar el ocultarlos
  const allFormWrappers = [
    loginFormWrapper,
    registerFormWrapper,
    forgotPasswordFormWrapper,
  ];

  const navbarToggler = document.querySelector(".navbar-toggler");
  const navbarCollapse = document.getElementById("navbarNavLogin");

  function openPopup(formToShow = "login") {
    // Por defecto mostrar login
    formPopupContainer.classList.add("active");

    // Ocultar todos los formularios primero
    allFormWrappers.forEach((wrapper) => {
      if (wrapper) wrapper.style.display = "none";
    });

    // Mostrar el formulario solicitado
    if (formToShow === "login" && loginFormWrapper) {
      loginFormWrapper.style.display = "block";
    } else if (formToShow === "register" && registerFormWrapper) {
      registerFormWrapper.style.display = "block";
    } else if (formToShow === "forgot_password" && forgotPasswordFormWrapper) {
      forgotPasswordFormWrapper.style.display = "block";
    } else if (loginFormWrapper) {
      // Fallback a login si el target no es válido
      loginFormWrapper.style.display = "block";
    }

    if (navbarCollapse && navbarCollapse.classList.contains("show")) {
      const bsCollapse =
        bootstrap.Collapse.getInstance(navbarCollapse) ||
        new bootstrap.Collapse(navbarCollapse, { toggle: false });
      if (bsCollapse) {
        bsCollapse.hide();
      }
    }
    document.body.style.overflow = "hidden";
  }

  function closePopup() {
    formPopupContainer.classList.remove("active");
    document.body.style.overflow = "";
  }

  // La función toggleForms ya no es necesaria, se maneja en el event listener
  // function toggleForms(targetIdToShow) { ... }

  if (loginPopupBtn) {
    loginPopupBtn.addEventListener("click", (e) => {
      e.preventDefault();
      openPopup("login"); // Especificar que se abra el de login
    });
  }

  if (closePopupIcon) {
    closePopupIcon.addEventListener("click", closePopup);
  }

  if (formPopupContainer) {
    formPopupContainer.addEventListener("click", (e) => {
      if (e.target === formPopupContainer) {
        closePopup();
      }
    });
  }

  toggleFormLinks.forEach((link) => {
    link.addEventListener("click", (e) => {
      e.preventDefault();
      const targetId = link.getAttribute("data-target"); // ej: "loginFormWrapper"

      // Ocultar todos los formularios
      allFormWrappers.forEach((wrapper) => {
        if (wrapper) wrapper.style.display = "none";
      });

      // Mostrar el formulario objetivo
      const targetWrapper = document.getElementById(targetId);
      if (targetWrapper) {
        targetWrapper.style.display = "block";
      }
    });
  });

  if (navbarToggler && navbarCollapse) {
    navbarCollapse.addEventListener("show.bs.collapse", function () {
      if (formPopupContainer.classList.contains("active")) {
        closePopup();
      }
    });
  }

  document.addEventListener("click", function (event) {
    if (navbarCollapse && navbarCollapse.classList.contains("show")) {
      const isClickInsideNav = navbarCollapse.contains(event.target);
      const isClickOnToggler = navbarToggler.contains(event.target);
      if (!isClickInsideNav && !isClickOnToggler) {
        const bsCollapse =
          bootstrap.Collapse.getInstance(navbarCollapse) ||
          new bootstrap.Collapse(navbarCollapse, { toggle: false });
        if (bsCollapse) {
          bsCollapse.hide();
        }
      }
    }
  });

  // Lógica para mostrar el formulario correcto si PHP lo indica a través de la sesión
  // (esto ya se maneja con los estilos en línea en login.php basados en $_SESSION['active_form'])
  // Sin embargo, si queremos que el JS también lo respete al cargar, podemos añadir:
  const activeFormFromPHP =
    "<?php echo $_SESSION['active_form'] ?? 'login'; ?>";
  if (formPopupContainer.classList.contains("active")) {
    // Si el popup ya está activo por PHP
    allFormWrappers.forEach((wrapper) => {
      if (wrapper) wrapper.style.display = "none";
    });
    if (activeFormFromPHP === "register" && registerFormWrapper) {
      registerFormWrapper.style.display = "block";
    } else if (
      activeFormFromPHP === "forgot_password" &&
      forgotPasswordFormWrapper
    ) {
      forgotPasswordFormWrapper.style.display = "block";
    } else if (loginFormWrapper) {
      // Default to login
      loginFormWrapper.style.display = "block";
    }
  }
});
