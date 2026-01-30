</main>

        <div class="py-8 text-center border-t border-slate-50 mt-auto">
            <p class="text-xs text-slate-400 font-medium">
                &copy; <?php echo date('Y'); ?> <span class="text-slate-600 font-bold">Exotikha</span> Admin Panel.
                <span class="mx-1 text-slate-200">|</span> 
                v1.0.0
            </p>
        </div>

    </div> <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-dismiss Flash Messages
            setTimeout(function() {
                // Sélectionne tous les éléments qui pourraient être des messages flash
                var flashMessages = document.querySelectorAll('[id="msg-flash"], .alert'); 
                
                flashMessages.forEach(function(flash) {
                    // Transition douce : Opacité + Déplacement vers le haut
                    flash.style.transition = 'opacity 0.5s ease-out, transform 0.5s ease-out';
                    flash.style.opacity = '0';
                    flash.style.transform = 'translateY(-20px)'; // Petit effet de slide up
                    
                    // Suppression du DOM après l'animation
                    setTimeout(function() {
                        flash.remove();
                    }, 500);
                });
            }, 4000); // Disparaît après 4 secondes
        });
    </script>
</body>
</html>