<?php require_once __DIR__ . '/../modules/dbConnection.php' ?>
<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <title>Hello, world!</title>
  </head>
  <body style="height: 100%; width: 100%; box-sizing: border-box;">
  <div class="container">

    
    <h1 class="mt-4 mb-3">
        <img src="https://i.ibb.co/X87dZV7/croppedbook.png" alt="croppedbook" style="height:70px; width:70px;"/>
        Azka's Bookstore
    </h1>

    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a class="nav-link" href="./">Cabang Toko</a>
        </li>
    <li class="nav-item">
        <a class="nav-link active" aria-current="page" href="#">Daftar Buku</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="./authors.php">Daftar Penulis</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="./staffs.php">Daftar Staff</a>
    </li>
    </ul>

    <div class="container-fluid" style="height: 100%;">
        <div class="row">
            <div class="col" style="height:70vh; overflow:scroll">
                <table class="table table-hover mt-4">
                    <tbody>
                        <?php 
                            $storeBranches = postgreQuery('SELECT * FROM public."Books"');
                            foreach ($storeBranches as $index => $row):
                        ?>
                        <tr>
                            <th scope="row"><?= $index + 1 ?></th>
                            <td><?= substr($row['book_title'], 1, -1) ?></td>
                            <td><?= strtok($row['published_year'], '-') ?></td>
                            <td>
                                <a href="?selected_id=<?= $row['book_id'] ?>"><button type="button" class="btn btn-primary">view</button></a>
                            </td>
                        </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>



        <div class="col-5">
            <?php
                [ $selectedBranches, $selectedAuthors, $selectedBook ] = [ null, null, null ];
                if (isset($_GET['selected_id'])):
                    $selectedBook = postgreQuery('SELECT * FROM public."Books" WHERE book_id = '.$_GET['selected_id'])[0];
                    $selectedBranches = postgreQuery('SELECT DISTINCT public."StoreBranches".branch_id, public."StoreBranches"."address", public."Books_StoreBranches"."stock" FROM public."StoreBranches"
                                                        JOIN public."Books_StoreBranches" ON public."StoreBranches".branch_id=public."Books_StoreBranches"."FK_branch_id"
                                                        JOIN public."Books" ON public."Books_StoreBranches"."FK_book_id"='.$_GET['selected_id']);
                    $selectedAuthors  = postgreQuery('SELECT DISTINCT public."Authors"."name", public."Authors"."author_id" FROM public."Authors"
                                                        JOIN public."Authors_Books" ON public."Authors".author_id=public."Authors_Books"."FK_author_id"
                                                        JOIN public."Books" ON public."Authors_Books"."FK_book_id"='.$_GET['selected_id']);
                    if ($selectedBook && $selectedAuthors):
            ?>
            <h3 class="mt-3"><?php echo substr($selectedBook['book_title'], 1, -1) ?></h4>
            <h5 class="mb-4">
                <?php foreach ($selectedAuthors as $index => $author): ?>
                <a href="./authors.php?selected_id=<?= $author['author_id'] ?>"><?php echo substr($author['name'], 2, -2) ?></a>
                <?php
                    echo ($index+1 < count($selectedAuthors)) ? ", " : "";
                    endforeach
                    ?>
            </h5>
            <p class="mb-1">
                Tanggal terbit: <?php echo $selectedBook['published_year'] ?>
            </p>
            <p class="mb-3">
                Harga: Rp<?php echo $selectedBook['price'] ?>,00
            </p>
            <h6>Stok buku dalam cabang:</h6>
            <div style="overflow: scroll; width: 100%; height:35vh;">
                <table class="table table-hover table-sm">
                    <tbody>
                        <?php
                            $totalStock = 0;
                            foreach ($selectedBranches as $index => $branch):
                                $totalStock += (int)$branch['stock'];
                                ?>
                        <tr>
                            <!-- <td scope="row"><?= $index + 1 ?></td> -->
                            <td>
                                <a href="./index.php?selected_id=<?= $branch['branch_id'] ?>">
                                    <?= strtok(substr($branch['address'], 2, -2), ':') ?>
                                </a>
                            </td>
                            <td><?= $branch['stock'] ?></td>
                        </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
            <p>total: <?php echo $totalStock ?></p>
            <?php endif; endif; ?>
        </div>
    </div>
    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

</div>
</body>
</html>