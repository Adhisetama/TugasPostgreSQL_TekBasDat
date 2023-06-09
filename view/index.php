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
    <link rel="icon" type="image/x-icon" href="https://i.ibb.co/X87dZV7/croppedbook.png">
  </head>
  <body style="height: 100%; width: 100%; box-sizing: border-box;">
  <div class="container">

    
    <h1 class="mt-4 mb-3">
        <img src="https://i.ibb.co/X87dZV7/croppedbook.png" alt="croppedbook" style="height:70px; width:70px;"/>
        Azka's Bookstore
    </h1>

    <ul class="nav nav-tabs">
    <li class="nav-item">
        <a class="nav-link active" aria-current="page" href="#">Cabang Toko</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="./books.php">Daftar Buku</a>
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
            <div class="col">
                <table class="table table-hover mt-4">
                    <tbody>
                        <?php 
                            $storeBranches = postgreQuery('SELECT * FROM public."StoreBranches"');
                            foreach ($storeBranches as $index => $row):
                        ?>
                        <tr>
                            <th scope="row"><?= $index + 1 ?></th>
                            <td><?= substr($row['address'], 2, -2) ?></td>
                            <td><?= substr($row['phone'], 1, -1) ?></td>
                            <td>
                                <a href="?selected_id=<?= $row['branch_id'] ?>"><button type="button" class="btn btn-primary">view</button></a>
                            </td>
                        </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>



        <div class="col-5">
            <?php
                [ $selectedBranch, $selectedStaffs, $selectedBooks ] = [ null, null, null ];
                if (isset($_GET['selected_id'])):
                    $selectedBranch = postgreQuery('SELECT * FROM public."StoreBranches" WHERE branch_id = '.$_GET['selected_id'])[0];
                    $selectedStaffs = postgreQuery('SELECT * FROM public."Staffs" WHERE "FK_branch_id" = '.$_GET['selected_id']);
                    $selectedBooks  = postgreQuery('SELECT DISTINCT public."Books".book_title, public."Books".book_id, public."Books_StoreBranches"."stock" FROM public."Books"
                                                        JOIN public."Books_StoreBranches" ON public."Books".book_id=public."Books_StoreBranches"."FK_book_id"
                                                        JOIN public."StoreBranches" ON public."Books_StoreBranches"."FK_branch_id"='.$_GET['selected_id']);
                    if ($selectedBranch && $selectedStaffs && $selectedBooks):
                        # olah data
                        $selectedBranch['address'] = substr($selectedBranch['address'], 2, -2);
                        $selectedBranch['phone'] = substr($selectedBranch['phone'], 1, -1);
                        $selectedBranch['city'] = strtok($selectedBranch['address'], ':');
                        $selectedBranch['address'] = substr($selectedBranch['address'], strlen($selectedBranch['city']) + 2);
            ?>
            <h4 class="mt-3">Cabang <?php echo $selectedBranch['city'] ?></h4>
            <p><?php echo $selectedBranch['address'] ?></p>
            <p>
                Staffs:
                <span>
                    <?php 
                    $staffCount = count($selectedStaffs);
                    foreach($selectedStaffs as $index => $staff): ?>
                        <a href="./staffs.php#<?= $staff['staff_id'] ?>">
                            <?php echo substr($staff['name'], 2, -2); ?>
                        </a>
                    <?php
                        echo ($index + 1 != $staffCount) ? ", " : ".";
                    endforeach
                    ?>
                </span>
            </p>
            <h6>Buku tersedia dalam cabang:</h6>
            <div style="overflow: scroll; width: 100%; height:40vh;">
                <table class="table table-hover table-sm">
                    <tbody>
                        <?php 
                            foreach ($selectedBooks as $index => $book):
                                ?>
                        <tr>
                            <!-- <td scope="row"><?= $index + 1 ?></td> -->
                            <td>
                                <a href="./books.php?selected_id=<?= $book['book_id'] ?>">
                                    <?= substr($book['book_title'], 1, -1) ?>
                                </a>
                            </td>
                            <td><?= $book['stock'] ?></td>
                        </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
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