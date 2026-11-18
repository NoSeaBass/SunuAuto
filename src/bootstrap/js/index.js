document.addEventListener('DOMContentLoaded', function() {
        // Gestion du scroll pour la navbar
        const navbar = document.querySelector('.navbar');
        
        function checkScroll() {
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        }
        
        // Vérification initiale
        checkScroll();
        
        // Ajout de l'événement scroll
        window.addEventListener('scroll', checkScroll);
        
        // Animation des sections au scroll
        const pageSections = document.querySelectorAll('.page-section');
        
        // Observer pour les sections
        const sectionObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                // Si la section est visible
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                } else {
                    // Option: Si vous voulez que les animations se répètent
                    // quand les sections ne sont plus visibles
                    entry.target.classList.remove('visible');
                }
            });
        }, {
            root: null, // viewport
            threshold: 0.15, // la section doit être visible à 15% pour déclencher l'animation
            rootMargin: '-50px 0px' // marge négative pour déclencher l'animation un peu avant que la section soit visible
        });
        
        // Ajouter la classe animate aux sections pour préparer l'animation
        // puis observer chaque section
        pageSections.forEach(section => {
            section.classList.add('animate');
            sectionObserver.observe(section);
        });
        
        // Smooth scroll pour les liens d'ancrage
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                
                const targetId = this.getAttribute('href');
                if (targetId === '#') return; // Ignorer les liens avec href="#"
                
                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    // Calculer la position de l'élément cible
                    const yOffset = -70; // Ajustement pour la navbar fixe
                    const y = targetElement.getBoundingClientRect().top + window.pageYOffset + yOffset;
                    
                    window.scrollTo({
                        top: y,
                        behavior: 'smooth'
                    });
                }
            });
        });
    });