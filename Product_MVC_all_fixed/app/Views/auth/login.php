<!DOCTYPE html>
<html lang="km">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login-Product</title>
    <link href="https://fonts.googleapis.com/css2?family=Battambang:wght@100;300;400;700;900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

    <link rel="stylesheet" href="asset/style.css">
</head>

<body>

    <div class="login-container">

        <!-- Left Side -->
        <div class="left-side">

            <h4>គ្រប់គ្រងស្តុប</h4>

            <h1>ដឹងជានិច្ចនូវអ្វីដែលមានក្នុងស្តុប</h1>

            <p> ស្តុកផលិតផល និងរបាយការណ៍នៅកន្លែងតែមួយ</p>

        </div>


        <!-- Right Side -->
        <div class="right-side">

            <div class="login-card">

                <h2>Sign in</h2>

                <p>Welcome.</p>

                <?php
                $error = $error ?? '';

                if ($error !== ''):
                    ?>
                <div class="alert alert-danger">
                    <?php echo htmlspecialchars($error); ?>
                </div>
                <?php endif; ?>


                <form method="POST" action="index.php?action=login">
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label for="ឈ្មោះអ្នកប្រើប្រាស់" class="form-label">
                            ឈ្មោះអ្នកប្រើប្រាស់
                        </label>

                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>


                    <div class="mb-3">
                        <label for="ពាក្យសម្ងាត់" class="form-label">
                            ពាក្យសម្ងាត់
                        </label>

                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>


                    <button type="submit" class="btn-login">
                        Login
                    </button>

                </form>

            </div>

        </div>

    </div>

</body>

</html>
