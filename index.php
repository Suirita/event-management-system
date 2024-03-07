<?php
include 'PDO.php';
session_start();
if (empty($_SESSION)) {
    $_SESSION['isLoggedIn'] = false;
}
if (isset($_SESSION['message'])) {
    if ($_SESSION['message'] == "You need to login first to buy a ticket") {
        unset($_SESSION['message']);
    }
}

$CURDATE = date('Y-m-d H:i:s');
$DATA = $DB->prepare("SELECT numVersion,dateEvenement,version.idEvenement,titre,categorie,image FROM version inner join evenement on version.idEvenement = evenement.idEvenement WHERE dateEvenement >= :CURDATE order by dateEvenement, titre");
$DATA->bindValue(':CURDATE', $CURDATE, PDO::PARAM_STR);
$DATA->execute();

include 'search.php';
include 'sort.php';

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Farha</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-light fixed-top px-4 py-2 rounded glass">
        <div class="container-fluid">
            <a class=" navbar-brand font-bold" href="#Header">Farha</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link px-4 py-2" href="#Header">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-4 py-2" href="#Footer">Contact</a>
                    </li>
                    <li class="nav-item">
                        <?php
                        if ($_SESSION['isLoggedIn'] == true) {
                        ?>
                            <div class="dropdown">
                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-person-circle dropdown-toggle hover" viewBox="0 0 16 16" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                    <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0" />
                                    <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1" />
                                </svg>
                                <ul class="dropdown-menu glass" aria-labelledby="dropdownMenuButton">
                                    <li><a class="dropdown-item" href="profile.php">Profile</a></li>
                                    <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                                </ul>
                            </div>
                        <?php
                        } elseif ($_SESSION['isLoggedIn'] == false) {
                        ?>
                            <button type="button" class="btn font-bold bg-black text-white px-4 py-2 rounded" data-bs-toggle="modal" data-bs-target="#authModal">
                                Login
                            </button>
                        <?php
                        }
                        ?>
                    </li>

                </ul>
            </div>
        </div>
    </nav>

    <?php
    if ($_SESSION['isLoggedIn'] == true) {
    ?>
        <div class="container margin-top">
            <div class="alert alert-primary d-flex align-items-center justify-content-between alert-dismissible fade show px-5" role="alert">
                <div class="d-flex align-items-center justify-content-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
                    </svg>
                    <?php
                    echo $_SESSION['message'];
                    ?>
                </div>
                <div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        </div>
    <?php
    } elseif ($_SESSION['isLoggedIn'] == false && isset($_SESSION['message'])) {
    ?>
        <div class="container margin-top">
            <div class="alert alert-danger d-flex align-items-center justify-content-between alert-dismissible fade show px-5" role="alert">
                <div class="d-flex align-items-center justify-content-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-exclamation-triangle-fill" viewBox="0 0 16 16">
                        <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5m.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2" />
                    </svg>
                    <?php
                    echo $_SESSION['message'];
                    ?>
                </div>
                <div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        </div>
    <?php
    }
    ?>

    <header id="Header">
        <div class="container bg-white mt-5 rounded">
            <img src="img/hero.jpg" alt="Hero image" class="img-fluid object-fit rounded py-3 px-1" style="height: 500px; width: 100%;">
        </div>
    </header>

    <main>
        <section>
            <div class="container mx-auto">
                <div class="row align-items-center justify-content-around w-75 h-24 mx-auto rounded glass up">
                    <form method="get" class="col-3 d-flex align-items-center gap-1">
                        <input type="text" placeholder="Search" name="searchInput" class="form-control rounded ">
                        <button type="submit" name="search" class="btn bg-black text-white font-bold">Search</button>
                    </form>
                    <form method="get" class="col-8 d-flex align-items-center gap-1">
                        <label for="category" class="col-auto">Sort by:</label>
                        <select name="category" class="form-select rounded">
                            <option value="" hidden selected>Category</option>
                            <option value="all">All</option>
                            <option value="Théatre">Theater</option>
                            <option value="Musique">Music</option>
                            <option value="Cinéma">Cinema</option>
                        </select>
                        <input type="date" name="startDate" class="form-control rounded">
                        <input type="date" name="endDate" class="form-control rounded">
                        <button type="submit" name="sort" class="btn bg-black text-white font-bold">Sort</button>
                    </form>
                </div>
            </div>
        </section>

        <section>
            <div class="modal fade glass" id="authModal" tabindex="-1" aria-labelledby="authModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title" id="authModalLabel">Login</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body d-flex justify-content-center">
                            <div class="col-9">
                                <form method="post" action="login.php">
                                    <input type="email" name="email" class="form-control my-3" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Email" required>
                                    <input type="password" name="password" class="form-control mb-3" id="exampleInputPassword1" placeholder="Password" required>
                                    <div class="d-flex justify-content-center">
                                        <button type="submit" name="login" class="btn btn-primary mb-3">Login</button><br>
                                    </div>
                                    <div class="d-flex justify-content-center">
                                        <span class="mb-3">Don't have an account? <a class="authLink" data-bs-target="#authModal2" data-bs-toggle="modal">Sign up</a></span>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade glass" id="authModal2" tabindex="-1" aria-labelledby="authModalLabel2" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title" id="authModalLabel2">Sign Up</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body d-flex justify-content-center">
                            <div class="col-9">
                                <form method="post" action="signUp.php">
                                    <div class="row">
                                        <div class="col">
                                            <input type="text" name="firstName" class="form-control my-3" placeholder="First name" required>
                                        </div>
                                        <div class="col">
                                            <input type="text" name="lastName" class="form-control my-3" placeholder="Last name" required>
                                        </div>
                                    </div>
                                    <input type="email" name="email" class="form-control mb-3" placeholder="Email" required>
                                    <input type="password" name="password" class="form-control mb-3" placeholder="Password" required>
                                    <div class="d-flex justify-content-center">
                                        <button type="submit" name="signUp" class="btn btn-primary mb-3">Sign Up</button><br>
                                    </div>
                                    <div class="d-flex justify-content-center">
                                        <spa class="mb-3">Already have an account? <a class="authLink" data-bs-target="#authModal" data-bs-toggle="modal">Login</a></spa>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section>
            <div class="container bg-white px-4 mb-5 pb-4 rounded shadow-lg">
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
                    <?php
                    foreach ($DATA->fetchAll() as $row) :
                    ?>
                        <div class="col">
                            <div class="card rounded shadow-lg glass h-100">
                                <p class="<?php echo $row["categorie"]; ?> badge rounded-pill position-absolute top-0 end-0 m-1"><?= $row["categorie"] ?></p>
                                <img src="img/<?= $row["image"] ?>" class="card-img-top" alt="<?= $row["titre"] ?>">
                                <div class="card-body">
                                    <h5 class="card-title"><?= $row["titre"] ?></h5>
                                    <p class="card-text mb-5"><?= $row["dateEvenement"] ?></p>
                                    <div class="d-flex justify-content-end">
                                        <form method="get" action="details.php">
                                            <input type="hidden" name="numVersion" value="<?= $row["numVersion"] ?>">
                                            <button type="submit" name="details" class="btn bg-black text-white font-bold position-absolute bottom-0 end-0 m-2">Buy Ticket</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php
                    endforeach;
                    ?>
                </div>
            </div>
        </section>
    </main>

    <footer id="Footer" class="container my-5 text-white text-center text-lg-start bg-black rounded p-2">
        <div class="container p-4">
            <div class="row mt-4">
                <div class="col-lg-4 col-md-12 mb-4 mb-md-0">
                    <h5 class="text-uppercase mb-4">About company</h5>
                    <p>
                        At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium
                        voluptatum deleniti atque corrupti.
                    </p>
                    <p>
                        Blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas
                        molestias.
                    </p>

                    <div class="mt-4">
                        <a type="button" class="btn btn-floating btn-black btn-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-facebook" viewBox="0 0 16 16">
                                <path d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951" />
                            </svg>
                        </a>
                        <a type="button" class="btn btn-floating btn-black btn-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-dribbble" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M8 0C3.584 0 0 3.584 0 8s3.584 8 8 8c4.408 0 8-3.584 8-8s-3.592-8-8-8m5.284 3.688a6.8 6.8 0 0 1 1.545 4.251c-.226-.043-2.482-.503-4.755-.217-.052-.112-.096-.234-.148-.355-.139-.33-.295-.668-.451-.99 2.516-1.023 3.662-2.498 3.81-2.69zM8 1.18c1.735 0 3.323.65 4.53 1.718-.122.174-1.155 1.553-3.584 2.464-1.12-2.056-2.36-3.74-2.551-4A7 7 0 0 1 8 1.18m-2.907.642A43 43 0 0 1 7.627 5.77c-3.193.85-6.013.833-6.317.833a6.87 6.87 0 0 1 3.783-4.78zM1.163 8.01V7.8c.295.01 3.61.053 7.02-.971.199.381.381.772.555 1.162l-.27.078c-3.522 1.137-5.396 4.243-5.553 4.504a6.82 6.82 0 0 1-1.752-4.564zM8 14.837a6.8 6.8 0 0 1-4.19-1.44c.12-.252 1.509-2.924 5.361-4.269.018-.009.026-.009.044-.017a28.3 28.3 0 0 1 1.457 5.18A6.7 6.7 0 0 1 8 14.837m3.81-1.171c-.07-.417-.435-2.412-1.328-4.868 2.143-.338 4.017.217 4.251.295a6.77 6.77 0 0 1-2.924 4.573z" />
                            </svg>
                        </a>
                        <a type="button" class="btn btn-floating btn-black btn-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-twitter-x" viewBox="0 0 16 16">
                                <path d="M12.6.75h2.454l-5.36 6.142L16 15.25h-4.937l-3.867-5.07-4.425 5.07H.316l5.733-6.57L0 .75h5.063l3.495 4.633L12.601.75Zm-.86 13.028h1.36L4.323 2.145H2.865z" />
                            </svg>
                        </a>
                        <a type="button" class="btn btn-floating btn-black btn-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-google" viewBox="0 0 16 16">
                                <path d="M15.545 6.558a9.4 9.4 0 0 1 .139 1.626c0 2.434-.87 4.492-2.384 5.885h.002C11.978 15.292 10.158 16 8 16A8 8 0 1 1 8 0a7.7 7.7 0 0 1 5.352 2.082l-2.284 2.284A4.35 4.35 0 0 0 8 3.166c-2.087 0-3.86 1.408-4.492 3.304a4.8 4.8 0 0 0 0 3.063h.003c.635 1.893 2.405 3.301 4.492 3.301 1.078 0 2.004-.276 2.722-.764h-.003a3.7 3.7 0 0 0 1.599-2.431H8v-3.08z" />
                            </svg>
                        </a>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-4 mb-md-0">
                    <h5 class="text-uppercase mb-4 pb-1">Search something</h5>
                    <div class="form-outline form-white mb-4">
                        <input type="text" id="formControlLg" class="form-control form-control-lg" placeholder="Search" />
                    </div>
                    <ul class="fa-ul" style="margin-left: 1.65em;">
                        <li class="mb-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-house" viewBox="0 0 16 16">
                                <path d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L2 8.207V13.5A1.5 1.5 0 0 0 3.5 15h9a1.5 1.5 0 0 0 1.5-1.5V8.207l.646.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293zM13 7.207V13.5a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5V7.207l5-5z" />
                            </svg>
                            <span class="ms-2">Tangier, Morroco</span>
                        </li>
                        <li class="mb-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-envelope" viewBox="0 0 16 16">
                                <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1zm13 2.383-4.708 2.825L15 11.105zm-.034 6.876-5.64-3.471L8 9.583l-1.326-.795-5.64 3.47A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.741M1 11.105l4.708-2.897L1 5.383z" />
                            </svg>
                            <span class="ms-2">fahd.suirita123@gmail.com</span>
                        </li>
                        <li class="mb-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-telephone" viewBox="0 0 16 16">
                                <path d="M3.654 1.328a.678.678 0 0 0-1.015-.063L1.605 2.3c-.483.484-.661 1.169-.45 1.77a17.6 17.6 0 0 0 4.168 6.608 17.6 17.6 0 0 0 6.608 4.168c.601.211 1.286.033 1.77-.45l1.034-1.034a.678.678 0 0 0-.063-1.015l-2.307-1.794a.68.68 0 0 0-.58-.122l-2.19.547a1.75 1.75 0 0 1-1.657-.459L5.482 8.062a1.75 1.75 0 0 1-.46-1.657l.548-2.19a.68.68 0 0 0-.122-.58zM1.884.511a1.745 1.745 0 0 1 2.612.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.68.68 0 0 0 .178.643l2.457 2.457a.68.68 0 0 0 .644.178l2.189-.547a1.75 1.75 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.6 18.6 0 0 1-7.01-4.42 18.6 18.6 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877z" />
                            </svg>
                            <span class="ms-2">+212 614 027 656</span>
                        </li>
                        <li class="mb-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-printer-fill" viewBox="0 0 16 16">
                                <path d="M5 1a2 2 0 0 0-2 2v1h10V3a2 2 0 0 0-2-2zm6 8H5a1 1 0 0 0-1 1v3a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1v-3a1 1 0 0 0-1-1" />
                                <path d="M0 7a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2h-1v-2a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v2H2a2 2 0 0 1-2-2zm2.5 1a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1" />
                            </svg>
                            <span class="ms-2">+212 614 027 656</span>
                        </li>
                    </ul>
                </div>
                <div class="col-lg-4 col-md-6 mb-4 mb-md-0">
                    <h5 class="text-uppercase mb-4">Opening hours</h5>
                    <table class="table-dark text-center text-white">
                        <tbody class="font-weight-normal">
                            <tr>
                                <td>Mon - Thu:</td>
                                <td>8am - 9pm</td>
                            </tr>
                            <tr>
                                <td>Fri - Sat:</td>
                                <td>8am - 1am</td>
                            </tr>
                            <tr>
                                <td>Sunday:</td>
                                <td>9am - 10pm</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.2);">
            © 2024 Copyright: Farha
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>

</body>

</html>