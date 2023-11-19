<div class="search-bar container row">
    <div class="row">
        <form method="get" action="">
            <input type="search" name="procura" id="search" class="input-search browser-default col 10" value="<?php echo isset($_GET['procura']) ? htmlspecialchars($_GET['procura']) : ''; ?>" placeholder="Buscar...">
            <input type="submit" class="busca-func col s1" value="Buscar">
        </form>
            <div class="close-content col s1">
                <form method="get" action="" clas="close-input">
                    <button class="butao-limpa" name="limpa" value="1"><i class="butao-limpa material-icons">cancel</i></button>
                </form>
            </div>
    </div>

</div>