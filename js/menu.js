        // Funcionalidad del menú hamburguesa
        const hamburger = document.getElementById('hamburger');
        const navbarContent = document.getElementById('navbarContent');

        hamburger.addEventListener('click', () => {
            navbarContent.classList.toggle('active');
        });

        // Funcionalidad dropdown para móviles
        const dropdownItems = document.querySelectorAll('.nav-item > .nav-link:not(.disabled)');

        dropdownItems.forEach(item => {
            if (item.nextElementSibling && item.nextElementSibling.classList.contains('dropdown-menu')) {
                item.addEventListener('click', (e) => {
                    if (window.innerWidth <= 768) {
                        e.preventDefault();
                        const dropdown = item.nextElementSibling;
                        dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
                    }
                });
            }
        });

        // Cerrar menú al hacer clic fuera en móviles
        document.addEventListener('click', (e) => {
            if (window.innerWidth <= 768 && !e.target.closest('.navbar')) {
                navbarContent.classList.remove('active');
            }
        });
