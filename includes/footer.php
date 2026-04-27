</div> <!-- End container -->

<footer class="mt-5 py-3 bg-light text-center">
    <div class="container">
        <span class="text-muted">&copy; 2024 Smart Inventory System</span>
        <?php if(isset($conn)): ?>
            <div class="mt-2" style="font-size: 0.8rem; color: #aaa;">
                Connected to: <?php echo htmlspecialchars($host); ?>:<?php echo $port; ?> | 
                Database: <?php echo htmlspecialchars($db); ?>
            </div>
        <?php endif; ?>
    </div>
</footer>

</body>
</html>
