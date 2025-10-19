<?php
// File: includes/footer.php
?>
</div> <!-- /container-fluid -->
<footer class="footer mt-auto py-3 bg-light border-top text-center">
    <div class="container">
        <span class="text-muted">© <?php echo date('Y'); ?> Hospital EMR Demo</span>
    </div>
</footer>
</div> <!-- /#page-content-wrapper -->
</div> <!-- /#wrapper -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script>
    // Toggle sidebar
    const menuToggle = document.getElementById('menu-toggle');
    const wrapper = document.getElementById('wrapper');
    menuToggle.addEventListener('click', () => {
        wrapper.classList.toggle('toggled');
    });
</script>
</body>
</html>
