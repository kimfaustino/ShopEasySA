/**
 * ShopEasySA - Main JavaScript File
 * Handles interactive features across the website
 */

document.addEventListener('DOMContentLoaded', function() {
    
    // Auto-hide alerts after 5 seconds
    var alerts = document.querySelectorAll('.alert');
    alerts.forEach(function(alert) {
        setTimeout(function() {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(function() { 
                if(alert.parentNode) alert.remove(); 
            }, 500);
        }, 5000);
    });
    
    // Add loading state to form submissions
    var forms = document.querySelectorAll('form');
    forms.forEach(function(form) {
        form.addEventListener('submit', function() {
            var btn = this.querySelector('button[type="submit"]');
            if(btn && !btn.disabled) {
                btn.disabled = true;
                btn.innerHTML = 'Processing...';
            }
        });
    });
    
    // Image upload validation
    var imageInput = document.querySelector('input[type="file"]');
    if(imageInput) {
        imageInput.addEventListener('change', function(e) {
            var file = e.target.files[0];
            
            if(file && file.size > 5 * 1024 * 1024) {
                alert('File too large. Maximum size is 5MB');
                this.value = '';
            }
            
            var allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
            if(file && !allowedTypes.includes(file.type)) {
                alert('Invalid file type. Please use JPG, PNG, or WEBP');
                this.value = '';
            }
        });
    }
});

// Cart count update function
function updateCartCount() {
    fetch('cart_count.php')
        .then(function(response) { return response.json(); })
        .then(function(data) {
            var badge = document.querySelector('.cart-badge');
            if(data.count > 0) {
                if(badge) {
                    badge.textContent = data.count;
                } else {
                    var cartLink = document.querySelector('.fa-shopping-cart');
                    if(cartLink && cartLink.parentElement) {
                        cartLink.parentElement.innerHTML += '<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger cart-badge">' + data.count + '</span>';
                    }
                }
            } else if(badge) {
                badge.remove();
            }
        })
        .catch(function(error) { console.error('Error:', error); });
}

// Confirm delete function
function confirmDelete(itemName) {
    return confirm('Are you sure you want to delete "' + itemName + '"? This action cannot be undone.');
}