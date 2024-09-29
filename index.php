<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        /* Retain the original colors */
        body {
            background-color: #f8f9fa; /* Light grey background */
        }

        .card-header {
            background-color: #ffffff; /* Keep card header white */
        }

        .text-primary {
            color: #007bff !important; /* Your desired primary text color */
        }

        .text-secondary {
            color: #6c757d !important; /* Secondary color for labels */
        }

        .btn-primary {
            background-color: #007bff !important; /* Blue color for the login button */
            border: none; /* Remove border */
        }

        .btn-danger {
            background-color: #dc3545 !important; /* Red color for the Create an Account button */
            border: none;
        }

        .btn-primary:hover, .btn-danger:hover {
            opacity: 0.8; /* Add hover effect for buttons */
        }

        .card {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Add slight shadow to the card */
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="card border-0 mx-auto w-50">
            <div class="card-header border-0 bg-white mt-5">
                <h1 class="display-4 fw-bold text-center text-primary">LOGIN <i class="fa-solid fa-right-to-bracket"></i></h1>
            </div>
            <div class="card-body">
                <form action="./actions/user-actions.php" method="post" class="w-75 mx-auto">
                    <div class="row mb-3 g-2">
                        <label for="username" class="col-md-3 col-form-label text-md-end text-secondary small">Username</label>
                        <div class="col-md-8">
                            <input type="text" name="username" id="username" class="form-control" placeholder="Username">
                        </div>
                    </div>
                    <div class="row mb-3 g-2">
                        <label for="password" class="col-md-3 col-form-label text-md-end text-secondary small">Password</label>
                        <div class="col-md-8">
                            <input type="password" name="password" id="password" class="form-control" placeholder="Password">
                        </div>
                    </div>
                    <div class="row mb-3 g-2">
                        <div class="col-md-8 offset-md-3">
                            <button type="submit" class="btn btn-primary w-100" name="login">Login</button>
                        </div>
                    </div>
     
                    <div class="row mb-3 g-2">
                        <div class="col-md-8 offset-md-3 text-center">
                            <a href="./views/registration.php" class="btn btn-danger btn-sm">Create an Account</a>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
                
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>
</html>
