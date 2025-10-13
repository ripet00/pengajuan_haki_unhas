<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sidebar = document.getElementById('sidebar');
        const mobileOverlay = document.getElementById('mobile-overlay');
        const sidebarToggleMobile = document.getElementById('sidebar-toggle-mobile');
        const sidebarToggleDesktop = document.getElementById('sidebar-toggle-desktop');
        const logoText = document.getElementById('logo-text');
        const sidebarTexts = document.querySelectorAll('.sidebar-text');
        
        let isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
        let isMobileOpen = false;

        // Initialize sidebar state
        if (window.innerWidth >= 768 && isCollapsed) {
            sidebar.classList.remove('sidebar-expanded');
            sidebar.classList.add('sidebar-collapsed');
            logoText.style.opacity = '0';
            sidebarTexts.forEach(text => text.style.opacity = '0');
        }

        // Mobile sidebar toggle
        if (sidebarToggleMobile) {
            sidebarToggleMobile.addEventListener('click', function() {
                isMobileOpen = !isMobileOpen;
                if (isMobileOpen) {
                    sidebar.classList.remove('sidebar-mobile-hidden');
                    sidebar.classList.add('sidebar-mobile-visible');
                    mobileOverlay.classList.remove('hidden');
                } else {
                    sidebar.classList.add('sidebar-mobile-hidden');
                    sidebar.classList.remove('sidebar-mobile-visible');
                    mobileOverlay.classList.add('hidden');
                }
            });
        }

        // Desktop sidebar toggle
        if (sidebarToggleDesktop) {
            sidebarToggleDesktop.addEventListener('click', function() {
                isCollapsed = !isCollapsed;
                localStorage.setItem('sidebarCollapsed', isCollapsed);
                
                if (isCollapsed) {
                    sidebar.classList.remove('sidebar-expanded');
                    sidebar.classList.add('sidebar-collapsed');
                    logoText.style.opacity = '0';
                    sidebarTexts.forEach(text => text.style.opacity = '0');
                } else {
                    sidebar.classList.remove('sidebar-collapsed');
                    sidebar.classList.add('sidebar-expanded');
                    logoText.style.opacity = '1';
                    sidebarTexts.forEach(text => text.style.opacity = '1');
                }
            });
        }

        // Close mobile sidebar when clicking overlay
        if (mobileOverlay) {
            mobileOverlay.addEventListener('click', function() {
                isMobileOpen = false;
                sidebar.classList.add('sidebar-mobile-hidden');
                sidebar.classList.remove('sidebar-mobile-visible');
                mobileOverlay.classList.add('hidden');
            });
        }

        // Initialize mobile sidebar as hidden
        if (window.innerWidth < 768) {
            sidebar.classList.add('sidebar-mobile-hidden');
        }

        // Handle window resize
        window.addEventListener('resize', function() {
            if (window.innerWidth >= 768) {
                sidebar.classList.remove('sidebar-mobile-hidden', 'sidebar-mobile-visible');
                mobileOverlay.classList.add('hidden');
                isMobileOpen = false;
                
                // Restore desktop state
                if (isCollapsed) {
                    sidebar.classList.remove('sidebar-expanded');
                    sidebar.classList.add('sidebar-collapsed');
                    logoText.style.opacity = '0';
                    sidebarTexts.forEach(text => text.style.opacity = '0');
                } else {
                    sidebar.classList.remove('sidebar-collapsed');
                    sidebar.classList.add('sidebar-expanded');
                    logoText.style.opacity = '1';
                    sidebarTexts.forEach(text => text.style.opacity = '1');
                }
            } else {
                sidebar.classList.remove('sidebar-collapsed');
                sidebar.classList.add('sidebar-expanded');
                logoText.style.opacity = '1';
                sidebarTexts.forEach(text => text.style.opacity = '1');
                
                if (!isMobileOpen) {
                    sidebar.classList.add('sidebar-mobile-hidden');
                }
            }
        });
    });
</script>