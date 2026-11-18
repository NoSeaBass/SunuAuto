/**
 * Base Card Script for handling favorite buttons and card interactions
 */
document.addEventListener('DOMContentLoaded', function() {
        // Handle favorite button clicks
        document.querySelectorAll('.favorite-toggle').forEach(button => {
            button.addEventListener('click', function() {
                const carId = this.getAttribute('data-car-id');
                const isFavorite = this.classList.contains('active');
                
                // Toggle visual state
                this.classList.toggle('active');
                
                if (isFavorite) {
                    this.innerHTML = '<i class="bi bi-heart"></i>';
                } else {
                    this.innerHTML = '<i class="bi bi-heart-fill"></i>';
                }
                
                // In a real implementation, you would send this preference to the server
                toggleFavorite(carId, !isFavorite);
            });
        });
        
        // Function to toggle favorite status on server
        function toggleFavorite(carId, status) {
            // Check if user is logged in
            const isLoggedIn = document.body.classList.contains('logged-in');
            
            if (!isLoggedIn) {
                // Redirect to login or show login modal
                showLoginPrompt();
                return;
            }
            
            // This would be an AJAX call to your PHP backend in a real implementation
            console.log(`Car ${carId} favorite status set to ${status}`);
            
            // Example of what an AJAX call might look like:
            /*
            fetch('./traitement.cacher.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `action=toggleFavorite&carId=${carId}&status=${status ? 1 : 0}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log('Favorite status updated successfully');
                } else {
                    console.error('Error updating favorite status');
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
            */
        }
        
        // Function to show login prompt
        function showLoginPrompt() {
            const loginUrl = './formulaires/connexionClient.php';
            
            // You could show a modal here, but for simplicity we'll just redirect or confirm
            if (confirm('Vous devez être connecté pour ajouter des favoris. Voulez-vous vous connecter maintenant ?')) {
                window.location.href = loginUrl;
            }
        }
        
        // Add hover effects to cards
        document.querySelectorAll('.car-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.querySelector('.card-img-top').style.transform = 'scale(1.05)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.querySelector('.card-img-top').style.transform = 'scale(1)';
            });
        });
        
        // Initialize booking buttons
        document.querySelectorAll('.btn-book').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const carId = this.getAttribute('data-car-id');
                const carName = this.getAttribute('data-car-name');
                
                // Redirect to booking page or show booking modal
                window.location.href = `./reservation.php?id=${carId}&name=${encodeURIComponent(carName)}`;
            });
        });
    });