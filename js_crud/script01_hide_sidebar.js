const sidebar = document.querySelector('.sidebar');
const sidebarToggle = document.querySelector('.sidebar-toggle');

function toggleSidebar() {
  sidebar.classList.toggle('hidden');
}

sidebarToggle.style.display = 'block';
