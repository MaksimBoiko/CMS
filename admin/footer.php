            </div> <!-- end main -->
            <div id="footer">
                <?php
                    $seconds = round(microtime(TRUE) - $start_db, 3);
                    $memory = round((memory_get_usage() - $startMemory) / 1024, 2);
                    echo "Seconds: ", $seconds, ' | SQL queries: '.$queries.' | Memory: '.$memory.' Mb'.PHP_EOL; ?>
            </div>
        </div> <!-- end wrapper -->
    </body>
</html>