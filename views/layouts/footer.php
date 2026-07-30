    </main>
</div><!-- /.page-wrapper -->

    <footer class="app-footer">
        <div class="footer-container">
            <p>&copy; <?= date('Y') ?> College Institutional Development Office. All rights reserved.</p>
            <div class="footer-links">
                <span class="badge badge-info"><i class="fa-solid fa-database"></i> MySQL PDO MVC</span>
                <span class="badge badge-secondary"><i class="fa-solid fa-calendar-days"></i> Multi-Year Tracking Enabled</span>
            </div>
        </div>
    </footer>

    <!-- Toast Notification Container -->
    <div id="toast-container" class="toast-container"></div>

    <!-- Application Script -->
    <script src="assets/js/app.js?v=<?= time() ?>"></script>

    <!-- Sidebar Script -->
    <script>
        function toggleSidebar() {
            const sidebar  = document.getElementById('appSidebar');
            const overlay  = document.getElementById('sidebarOverlay');
            const btn      = document.getElementById('hamburgerBtn');
            const isOpen   = sidebar.classList.contains('open');
            if (isOpen) {
                closeSidebar();
            } else {
                sidebar.classList.add('open');
                overlay.classList.add('active');
                btn.classList.add('active');
                btn.setAttribute('aria-expanded', 'true');
                document.body.classList.add('sidebar-open');
            }
        }

        function closeSidebar() {
            const sidebar = document.getElementById('appSidebar');
            const overlay = document.getElementById('sidebarOverlay');
            const btn     = document.getElementById('hamburgerBtn');
            sidebar.classList.remove('open');
            overlay.classList.remove('active');
            btn.classList.remove('active');
            btn.setAttribute('aria-expanded', 'false');
            document.body.classList.remove('sidebar-open');
        }

        // Close sidebar on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeSidebar();
        });
    </script>
</body>
</html>
