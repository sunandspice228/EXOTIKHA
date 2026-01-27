</main> <div class="p-6 text-center text-xs text-slate-400 font-medium">
            &copy; <?php echo date('Y'); ?> Exotikha Admin Panel. v1.0.0
        </div>

    </div> <script>
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                var flashMessages = document.querySelectorAll('[id="msg-flash"]');
                flashMessages.forEach(function(flash) {
                    flash.style.transition = 'opacity 0.5s ease-out';
                    flash.style.opacity = '0';
                    setTimeout(function() {
                        flash.remove();
                    }, 500);
                });
            }, 4000); // 4 seconds
        });
    </script>
</body>
</html>