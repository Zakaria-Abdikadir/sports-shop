<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script src="<?= BASE_URL ?>assets/js/script.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {

    const alerts = document.querySelectorAll(".alert");

    alerts.forEach(function(alert) {

        setTimeout(function() {

            alert.classList.remove("show");

            setTimeout(function() {
                alert.remove();
            }, 300);

        }, 3000); // Hide after 3 seconds

    });

});
</script>

</body>
</html>