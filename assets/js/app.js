// Todo List Premium - JavaScript Enhancements

document.addEventListener('DOMContentLoaded', function() {
    // Auto-hide alerts
    const alerts = document.querySelectorAll('.alert-dismissible');
    alerts.forEach(alert => {
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });

    // Deadline countdown
    updateCountdowns();
    setInterval(updateCountdowns, 60000); // Update every minute

    // Confirm delete
    document.querySelectorAll('.confirm-delete').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            if (confirm('Yakin ingin menghapus tugas ini?')) {
                window.location = this.href;
            }
        });
    });

    // Priority color coding
    document.querySelectorAll('[data-priority]').forEach(el => {
        const priority = el.dataset.priority;
        let badgeClass = '';
        switch(priority) {
            case 'high': badgeClass = 'bg-danger'; break;
            case 'medium': badgeClass = 'bg-warning'; break;
            case 'low': badgeClass = 'bg-success'; break;
        }
        if (badgeClass) {
            el.className = badgeClass + ' text-white fw-bold px-2 py-1 rounded small';
        }
    });

    // Live search in task list
    const searchInput = document.getElementById('task-search');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const query = this.value.toLowerCase();
            document.querySelectorAll('.task-row').forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(query) ? '' : 'none';
            });
        });
    }

    // Chart.js integration for stats (if available)
    if (typeof Chart !== 'undefined' && document.getElementById('productivityChart')) {
        const ctx = document.getElementById('productivityChart').getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Selesai', 'Belum Selesai'],
                datasets: [{
                    data: [<?= $selesai ?? 0 ?>, <?= $belum ?? 0 ?>],
                    backgroundColor: ['#10b981', '#f59e0b']
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    }
});

function updateCountdowns() {
    document.querySelectorAll('.deadline-countdown').forEach(el => {
        const deadline = new Date(el.dataset.deadline);
        const now = new Date();
        const diff = deadline - now;
        
        if (diff < 0) {
            el.innerHTML = '<i class="fas fa-clock text-danger"></i> Telat';
            el.parentElement.classList.add('border-danger');
        } else if (diff < 24*60*60*1000) { // < 24h
            const hours = Math.floor(diff / (60*60*1000));
            el.innerHTML = `<i class="fas fa-exclamation-triangle text-warning"></i> ${hours}h lagi`;
        } else {
            const days = Math.floor(diff / (24*60*60*1000));
            el.innerHTML = `<i class="fas fa-clock text-success"></i> ${days}d lagi`;
        }
    });
}

// Reminder notification simulation
function checkReminders() {
    // Simulate checking reminders every 5 minutes
    if (Math.random() < 0.1) { // 10% chance
        if (Notification.permission === 'granted') {
            new Notification('Todo Premium', {
                body: 'Ada tugas hampir deadline!',
                icon: 'assets/favicon.ico'
            });
        } else if (Notification.permission !== 'denied') {
            Notification.requestPermission();
        }
    }
}
setInterval(checkReminders, 5 * 60 * 1000);

// Smooth scrolling for anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});

