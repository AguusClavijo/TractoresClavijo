document.addEventListener('DOMContentLoaded', () => {
    // Selectores de elementos
    const loginFormContainer = document.getElementById('loginFormContainer');
    const loginLink = document.getElementById('loginLink');
    const registerLink = document.getElementById('registerLink');
    const loginBtn = document.getElementById('loginBtn'); // Botón "Iniciar Sesión" en el header
    const closeIcon = document.getElementById('closeIcon');
    const loginForm = document.getElementById('loginForm'); // Contenedor del formulario de inicio de sesión
    const registerForm = document.getElementById('registerForm'); // Contenedor del formulario de registro
    const menuToggle = document.getElementById('menuToggle'); // Icono de hamburguesa
    const navegacion = document.getElementById('navegacion'); // El contenedor <nav>

    // Función para manejar el estado de los formularios (login/registro)
    function updateFormVisibility() {
        if (window.innerWidth <= 768) {
            // Lógica para móviles: usar display none/block
            if (loginFormContainer.classList.contains('active')) {
                // Si el contenedor principal tiene 'active', mostrar registro y ocultar login
                loginForm.style.display = 'none';
                registerForm.style.display = 'block';
            } else {
                // Si no tiene 'active', mostrar login y ocultar registro
                loginForm.style.display = 'block';
                registerForm.style.display = 'none';
            }
            // Asegurarse de que las transformaciones de escritorio no estén afectando
            loginForm.style.transform = 'translateX(0)';
            registerForm.style.transform = 'translateX(0)';
        } else {
            // Lógica para escritorio: usar transform translateX
            loginForm.style.display = 'block'; // Asegurarse de que ambos estén visibles para la transformación
            registerForm.style.display = 'block'; // Asegurarse de que ambos estén visibles para la transformación

            if (loginFormContainer.classList.contains('active')) {
                // Si el contenedor principal tiene 'active', deslizar login y mostrar registro
                loginForm.style.transform = 'translateX(-400px)';
                registerForm.style.transform = 'translateX(0)';
            } else {
                // Si no tiene 'active', mostrar login y deslizar registro
                loginForm.style.transform = 'translateX(0)';
                registerForm.style.transform = 'translateX(400px)';
            }
        }
    }

    // Evento para mostrar el formulario de registro
    if (registerLink) {
        registerLink.addEventListener("click", (e) => {
            e.preventDefault();
            loginFormContainer.classList.add('active'); // Activa la clase 'active' para el registro
            updateFormVisibility(); // Actualiza la visibilidad de los formularios
        });
    }

    // Evento para mostrar el formulario de inicio de sesión
    if (loginLink) {
        loginLink.addEventListener("click", (e) => {
            e.preventDefault();
            loginFormContainer.classList.remove('active'); // Remueve la clase 'active' para el login
            updateFormVisibility(); // Actualiza la visibilidad de los formularios
        });
    }

    // Evento para abrir el contenedor del formulario (desde el botón "Iniciar Sesión" del header)
    if (loginBtn) {
        loginBtn.addEventListener("click", () => {
            loginFormContainer.classList.add('active-btn'); // Muestra el contenedor principal
            loginFormContainer.classList.remove('active'); // Asegura que se inicie en el login
            updateFormVisibility(); // Actualiza la visibilidad de los formularios

            // Si está en móvil, cierra el menú de navegación cuando se abre el formulario de inicio de sesión
            if (window.innerWidth <= 768) {
                navegacion.classList.remove('active');
            }
        });
    }

    // Evento para cerrar el contenedor del formulario
    if (closeIcon) {
        closeIcon.addEventListener("click", () => {
            loginFormContainer.classList.remove('active-btn'); // Oculta el contenedor principal
            loginFormContainer.classList.remove('active'); // Asegura que se resetee al estado de login
            updateFormVisibility(); // Actualiza la visibilidad de los formularios
        });
    }

    // Evento para alternar el menú de navegación móvil (hamburguesa)
    if (menuToggle) {
        menuToggle.addEventListener('click', () => {
            navegacion.classList.toggle('active'); // Alterna la clase 'active' en la navegación

            // Si el menú de navegación móvil se abre, cierra el formulario de inicio de sesión si está abierto
            if (navegacion.classList.contains('active')) {
                loginFormContainer.classList.remove('active-btn');
                loginFormContainer.classList.remove('active'); // También resetear para el login
                updateFormVisibility(); // Asegurarse que los formularios se restablezcan
            }
        });
    }

    // Ajustar el display del formulario al redimensionar la ventana (ej. rotación de dispositivo)
    window.addEventListener('resize', () => {
        updateFormVisibility(); // Llama a la función para ajustar la visibilidad
        // Asegura que la navegación se oculte en desktop si se redimensiona
        if (window.innerWidth > 768) {
            navegacion.classList.remove('active'); // Oculta el menú de hamburguesa en desktop
        }
    });

    // Inicializar la visibilidad de los formularios cuando la página carga
    updateFormVisibility();
});