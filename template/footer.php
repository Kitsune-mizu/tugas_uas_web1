            </div>
        </main>

        <footer class="app-footer">
            <div class="footer-content">
                <p>&copy; 2025 Inventory System. All rights reserved.</p>
                <div class="footer-links">
                    <span>Made with Ai for Web Programming</span>
                </div>
            </div>
        </footer>
    </div>

    <script>
        // Konfirmasi hapus data
        function confirmDelete() {
            return confirm('Apakah Anda yakin ingin menghapus data ini?');
        }

        // Toast notification
        function showToast(message, type = 'success') {
            const toast = document.createElement('div');
            toast.className = `toast toast-${type}`;
            toast.innerHTML = `
                <div class="toast-content">
                    <i class="fas fa-${type === 'success' ? 'check' : 'exclamation'}-circle"></i>
                    <span>${message}</span>
                </div>
            `;
            document.body.appendChild(toast);
            
            setTimeout(() => {
                toast.classList.add('show');
            }, 100);
            
            setTimeout(() => {
                toast.classList.remove('show');
                setTimeout(() => {
                    document.body.removeChild(toast);
                }, 300);
            }, 3000);
        }

        // Check for URL parameters to show toast
        const urlParams = new URLSearchParams(window.location.search);
        const message = urlParams.get('message');
        const messageType = urlParams.get('type');
        
        if (message) {
            showToast(decodeURIComponent(message), messageType || 'success');
        }
    </script>
</body>
</html>