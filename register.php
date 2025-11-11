<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Register - Smart Parking</title>

    <!-- Custom fonts for this template-->
    <link href="./includes/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="./includes/css/sb-admin-2.min.css" rel="stylesheet">

    <style>
        .valid-feedback,
        .invalid-feedback {
            display: none;
            font-size: 0.8rem;
        }

        .is-valid~.valid-feedback,
        .is-invalid~.invalid-feedback {
            display: block;
        }

        .form-control:focus {
            box-shadow: none;
        }

        .password-strength {
            height: 5px;
            margin-top: 5px;
            margin-bottom: 10px;
            background: #e9ecef;
            border-radius: 3px;
        }

        .password-strength-bar {
            height: 100%;
            border-radius: 3px;
            transition: width 0.3s;
        }

        .password-requirements {
            font-size: 0.8rem;
            color: #6c757d;
            margin-top: 5px;
        }

        .requirement {
            margin-bottom: 2px;
        }

        .requirement.met {
            color: #28a745;
        }

        .requirement.unmet {
            color: #dc3545;
        }
    </style>
</head>

<body class="bg-gradient-dark">
    <script src="./includes/sweetalert.js"></script>
    <div class="container">

        <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
                <!-- Nested Row within Card Body -->
                <div class="row">
                    <div class="col-lg-5 d-none d-lg-block bg-register-image">
                        <img src="https://cdn.pixabay.com/photo/2021/11/13/19/28/cars-6792173_640.jpg" alt="">
                    </div>
                    <div class="col-lg-7">
                        <div class="p-5">
                            <div class="text-center">
                                <h1 class="h4 text-dark mb-4">
                                    <i class="fas fa-car"></i>
                                    Smart Parking
                                </h1>
                                <h1 class="h4 text-gray-900 mb-4">Create an Account!</h1>
                            </div>
                            <form class="user" action="./x.php" method="post" id="registerForm">
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <input type="text" class="form-control form-control-user" id="firstName"
                                            placeholder="First Name" name="fname" required>
                                        <div class="invalid-feedback">Please enter your first name</div>
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control form-control-user" id="lastName"
                                            placeholder="Last Name" name="lname" required>
                                        <div class="invalid-feedback">Please enter your last name</div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <input type="text" class="form-control form-control-user" id="nic"
                                            placeholder="NIC" name="nic" required>
                                        <div class="invalid-feedback">Please enter your NIC</div>
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="tel" class="form-control form-control-user" id="phone"
                                            placeholder="Contact Number" name="phone" required
                                            pattern="[0-9]{10}" maxlength="10">
                                        <div class="valid-feedback">Looks good!</div>
                                        <div class="invalid-feedback">Please enter a valid 10-digit phone number</div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <input type="email" class="form-control form-control-user" id="email"
                                        placeholder="Email Address" name="email" required>
                                    <div class="valid-feedback">Looks good!</div>
                                    <div class="invalid-feedback">Please enter a valid email address</div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <input type="password" class="form-control form-control-user"
                                            id="password" placeholder="Password" name="pwd" required
                                            minlength="8">
                                        <div class="password-strength">
                                            <div class="password-strength-bar" id="passwordStrengthBar"></div>
                                        </div>
                                        <div class="password-requirements">
                                            <div class="requirement unmet" id="lengthReq">• At least 8 characters</div>
                                            <div class="requirement unmet" id="upperReq">• At least 1 uppercase letter</div>
                                            <div class="requirement unmet" id="lowerReq">• At least 1 lowercase letter</div>
                                            <div class="requirement unmet" id="numberReq">• At least 1 number</div>
                                            <div class="requirement unmet" id="specialReq">• At least 1 special character</div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="password" class="form-control form-control-user"
                                            id="repeatPassword" placeholder="Repeat Password" name="rpwd" required>
                                        <div class="valid-feedback">Passwords match!</div>
                                        <div class="invalid-feedback">Passwords do not match</div>
                                    </div>
                                </div>
                                <button type="submit" name="register" class="btn btn-dark btn-user btn-block" id="submitBtn">
                                    Register Account
                                </button>
                            </form>
                            <div class="text-center">
                                <a class="small" href="./login.php">Already have an account? Login!</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php
    if (isset($_SESSION['already_email'])) {
        echo "
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Email or NIC already exists!',
                    }).then(() => {
                        //window.history.back(); // Navigate back to the previous page
                    });
                </script>";
        unset($_SESSION["already_email"]);
    }

    if (isset($_SESSION['password_not_match'])) {
        echo "
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Password does not match!',
                    }).then(() => {
                        //window.history.back(); // Navigate back to the previous page
                    });
                </script>";
        unset($_SESSION["password_not_match"]);
    }
    ?>

    <!-- Bootstrap core JavaScript-->
    <script src="./includes/vendor/jquery/jquery.min.js"></script>
    <script src="./includes/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="./includes/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="./includes/js/sb-admin-2.min.js"></script>

    <script>
        $(document).ready(function() {
            // Email validation
            $('#email').on('input', function() {
                const email = $(this).val();
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

                if (emailRegex.test(email)) {
                    $(this).removeClass('is-invalid').addClass('is-valid');
                } else {
                    $(this).removeClass('is-valid').addClass('is-invalid');
                }
            });

            // Phone number validation
            $('#phone').on('input', function() {
                const phone = $(this).val();
                const phoneRegex = /^\d{10}$/;

                if (phoneRegex.test(phone)) {
                    $(this).removeClass('is-invalid').addClass('is-valid');
                } else {
                    $(this).removeClass('is-valid').addClass('is-invalid');
                }
            });

            // Password validation
            $('#password').on('input', function() {
                const password = $(this).val();
                const repeatPassword = $('#repeatPassword').val();

                // Check password strength
                const hasMinLength = password.length >= 8;
                const hasUpperCase = /[A-Z]/.test(password);
                const hasLowerCase = /[a-z]/.test(password);
                const hasNumbers = /\d/.test(password);
                const hasSpecialChars = /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password);

                // Update requirement indicators
                updateRequirement('lengthReq', hasMinLength);
                updateRequirement('upperReq', hasUpperCase);
                updateRequirement('lowerReq', hasLowerCase);
                updateRequirement('numberReq', hasNumbers);
                updateRequirement('specialReq', hasSpecialChars);

                // Calculate password strength (0-100)
                let strength = 0;
                if (hasMinLength) strength += 20;
                if (hasUpperCase) strength += 20;
                if (hasLowerCase) strength += 20;
                if (hasNumbers) strength += 20;
                if (hasSpecialChars) strength += 20;

                // Update strength bar
                updateStrengthBar(strength);

                // Validate password
                if (hasMinLength && hasUpperCase && hasLowerCase && hasNumbers && hasSpecialChars) {
                    $(this).removeClass('is-invalid').addClass('is-valid');
                } else {
                    $(this).removeClass('is-valid').addClass('is-invalid');
                }

                // Check password match
                checkPasswordMatch(password, repeatPassword);
            });

            // Repeat password validation
            $('#repeatPassword').on('input', function() {
                const password = $('#password').val();
                const repeatPassword = $(this).val();
                checkPasswordMatch(password, repeatPassword);
            });

            function updateRequirement(elementId, condition) {
                const element = $('#' + elementId);
                if (condition) {
                    element.removeClass('unmet').addClass('met');
                } else {
                    element.removeClass('met').addClass('unmet');
                }
            }

            function updateStrengthBar(strength) {
                const bar = $('#passwordStrengthBar');
                bar.css('width', strength + '%');

                // Change color based on strength
                if (strength < 40) {
                    bar.css('background-color', '#dc3545'); // Red
                } else if (strength < 80) {
                    bar.css('background-color', '#ffc107'); // Yellow
                } else {
                    bar.css('background-color', '#28a745'); // Green
                }
            }

            function checkPasswordMatch(password, repeatPassword) {
                if (repeatPassword === '' || password === '') {
                    $('#repeatPassword').removeClass('is-valid is-invalid');
                    return;
                }

                if (repeatPassword === password && password.length >= 8) {
                    $('#repeatPassword').removeClass('is-invalid').addClass('is-valid');
                } else {
                    $('#repeatPassword').removeClass('is-valid').addClass('is-invalid');
                }
            }

            // Form submission validation
            $('#registerForm').on('submit', function(e) {
                let isValid = true;

                // Check required fields
                $(this).find('[required]').each(function() {
                    if (!$(this).val()) {
                        $(this).addClass('is-invalid');
                        isValid = false;
                    }
                });

                // Check password requirements
                const password = $('#password').val();
                const hasMinLength = password.length >= 8;
                const hasUpperCase = /[A-Z]/.test(password);
                const hasLowerCase = /[a-z]/.test(password);
                const hasNumbers = /\d/.test(password);
                const hasSpecialChars = /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password);

                if (!(hasMinLength && hasUpperCase && hasLowerCase && hasNumbers && hasSpecialChars)) {
                    $('#password').addClass('is-invalid');
                    isValid = false;
                }

                // Check password match
                if ($('#password').val() !== $('#repeatPassword').val()) {
                    $('#repeatPassword').addClass('is-invalid');
                    isValid = false;
                }

                // Check email format
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test($('#email').val())) {
                    $('#email').addClass('is-invalid');
                    isValid = false;
                }

                // Check phone format
                const phoneRegex = /^\d{10}$/;
                if (!phoneRegex.test($('#phone').val())) {
                    $('#phone').addClass('is-invalid');
                    isValid = false;
                }

                if (!isValid) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Validation Error',
                        text: 'Please fix the errors in the form before submitting.',
                    });
                }
            });
        });
    </script>
</body>

</html>