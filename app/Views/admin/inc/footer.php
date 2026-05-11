</main> <footer class="py-8 text-center border-t border-slate-200 mt-auto bg-white">
            <p class="text-xs text-slate-400 font-medium">
                &copy; <?php echo date('Y'); ?> <span class="text-slate-600 font-bold">Exotikha</span> Admin Panel.
                <span class="mx-1 text-slate-200">|</span> 
                v1.0.0
            </p>
        </footer>

    </div> </div> <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-dismiss Flash Messages
        const flashContainer = document.getElementById('msg-flash');
        
        if (flashContainer && flashContainer.children.length > 0) {
            setTimeout(function() {
                // Transition douce
                flashContainer.style.transition = 'opacity 0.5s ease-out, transform 0.5s ease-out';
                flashContainer.style.opacity = '0';
                flashContainer.style.transform = 'translateY(-10px)';
                
                // Suppression du DOM après l'animation
                setTimeout(function() {
                    flashContainer.remove();
                }, 500);
            }, 4000); // Disparaît après 4 secondes
        }
    });
</script>

</body>
</html>