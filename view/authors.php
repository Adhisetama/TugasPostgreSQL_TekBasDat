<?php require_once __DIR__ . '/../modules/dbConnection.php' ?>
<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css'> 

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
        <a class="nav-link" href="./books.php">Daftar Buku</a>
    </li>
    <li class="nav-item" style="position: relative">
        <a href="./../edit/authors.php">
            <i class='fa fa-edit' style="position:absolute; top:-7px; right:-7px; background-color:red; color:white; height:22px; width:22px; border-radius:4px; text-align:center; line-height:22px"></i>
        </a>
        <a class="nav-link active" aria-current="page">Daftar Penulis</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="./staffs.php">Daftar Staff</a>
    </li>
    </ul>

    <div class="container-fluid" style="height: 100%;">
        <div class="row">
            <div class="col">
                <table class="table table-hover mt-4">
                    <tbody>
                        <?php 
                            $storeBranches = postgreQuery('SELECT * FROM public."Authors"');
                            foreach ($storeBranches as $index => $row):
                        ?>
                        <tr>
                            <th scope="row"><?= $index + 1 ?></th>
                            <td><?= substr($row['name'], 2, -2) ?></td>
                            <td><?= strtok($row['birthdate'], '-') ?></td>
                            <td>
                                <a href="?selected_id=<?= $row['author_id'] ?>"><button type="button" class="btn btn-primary">view</button></a>
                            </td>
                        </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>



        <div class="col-5">
            <?php
                [ $selectedAuthor, $selectedBooks ] = [ null, null ];
                if (isset($_GET['selected_id'])):
                    $selectedAuthor = postgreQuery('SELECT * FROM public."Authors" WHERE author_id = '.$_GET['selected_id'])[0];
                    $selectedBooks = postgreQuery('SELECT public."Books".*
                                                        FROM public."Books"
                                                        WHERE public."Books".book_id IN (
                                                            SELECT public."Authors_Books"."FK_book_id"
                                                            FROM public."Authors_Books"
                                                            WHERE public."Authors_Books"."FK_author_id" = '.$_GET['selected_id']
                                                        .')');
                    // $selectedBooks  = postgreQuery('SELECT DISTINCT public."Books".book_title, public."Books".published_year FROM public."Books"
                    //                                     JOIN public."Authors_Books" ON public."Authors_Books"."FK_book_id"=public."Books".book_id
                    //                                     JOIN public."Authors" ON public."Authors"."author_id"='.$_GET['selected_id']);
                    if ($selectedAuthor):
                        # olah data
            ?>
            <h3 class="mt-3"><?php echo substr($selectedAuthor['name'], 2, -2) ?></h4>
            <p>Lahir: <?php echo $selectedAuthor['birthdate'] ?></p>
            <h6>Buku yang ditulis:</h6>
            <div style="overflow: scroll; width: 100%; height:40vh;">
                <table class="table table-hover table-sm">
                    <tbody>
                        <?php 
                            foreach ($selectedBooks as $index => $book):
                                ?>
                        <tr>
                            <!-- <td scope="row"><?= $index + 1 ?></td> -->
                            <td><?= substr($book['book_title'], 1, -1) ?></td>
                            <td><?= strtok($book['published_year'], '-') ?></td>
                        </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
            <?php endif; endif; ?>
        </div>
    </div>
    </div>

    <!-- jika page hasil redirect dari /edit/query.php -->
    <?php if (isset($_POST['is-success'])) {
        $msg = $_POST['is-success'] ? "Sukses mengedit data." : "Edit data gagal.";
        echo "<script>alert('$msg')</script>";
    } ?>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

</div>
</body>
</html>