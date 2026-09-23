<?php
    //Start session
    session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>User Sign Up</title>
        <link rel="stylesheet" type="text/css" href="styles.css">
        <link rel="icon" type="image/x-icon" href="images/favicon.png">
    </head>

    <body>
        <section class="centerBox" id="loginBox">
            <ul id="tabSelect">
                <li><a href="userSignUp.php" class="active" style="border-top-left-radius: 16px;">Personal</a></li>
                <li><a href="businessSignUp.php" style="border-top-right-radius: 16px;">Business</a></li>
            </ul>

            <section class="loginForm">
                <section class="centerText">
                        <img src="images/logo.png" alt="logo" width="200px">
                    </section>
                <h1 class="centerText">Sign Up</h1>
                <p class="centerText">PERSONAL ACCOUNT</p>

                <form id="userSignUpForm" action="userSignUp.php" method="POST">
                    <label for="uname">Name*</label><br>
                    <input type="text" id="uname" name="userName" required maxlength="70"><br><br>
                    <label for="email">Email*</label><br>
                    <input type="email" id="email" name="userEmail" required maxlength="100"><br><br>
                    <label for="pword"> Password*</labe><br>
                    <input type="password" id="pword" name="password" required minlength="8" maxlength="30"><br><br>
                    <section class="centerText">
                        <input type="submit" value="Create Account" style="width: 50%;" name="signUpBtn">
                    </section>
                </form>
            </section>
        </section>

        <?php
            if (ISSET($_SESSION['accountID'])) {
                // User has already logged in, navigate to home page
                if ($_SESSION['accountType'] == "Personal") {
                    // Account type is personal so go to userHome.php
                    header("Location:userHome.php");
                }
                elseif ($_SESSION['accountType'] == "Business") {
                    // Account type is business so go to businessHome.php
                    header("Location:businessHome.php");
                }
            }
            if (ISSET($_POST['signUpBtn'])) {
                // Set error reporting to exclude warnings
                // Only fatal errors and parse errors are displayed
                error_reporting(E_ERROR | E_PARSE);

                // Database connection variables are established
                $dbHost = "localhost";
                $dbUser = "root";
                $dbPassword = "";
                $dbName = "project";

                // Attempt to connect to database
                $connection = mysqli_connect($dbHost, $dbUser, $dbPassword, $dbName);

                // Check if the connection is successful, else display appropriate error message
                if (mysqli_connect_errno()) {
                    echo "<h2>Connection Error</h2>";
                    // Display connection error message
                    echo mysqli_connect_error();
                    die();
                }

                // Variables are created with the data input on the HTML form
                $uname = $_POST['userName'];
                $email = $_POST['userEmail'];
                $pword = $_POST['password'];

                // Create a query to check if the email address has already been used
                $query = "SELECT * FROM user WHERE user.email = '$email'";

                //Execute the query using the active database
                $result = mysqli_query($connection, $query);

                // Check whether the query has been successful and if email has been used previously
                if ($result) {
                    // Count the number rows, if 0 rows are returned then the email has not been used already
                    $row_count = mysqli_num_rows($result);

                    if ($row_count == 0) {
                        // Email has not already been used and a new account can be created
                        // Create a query to create a new account
                        $query = "INSERT INTO user(userName, email, password) VALUES('$uname', '$email', '$pword')";

                        // Execute the query using the active database
                        $result = mysqli_query($connection, $query);

                        // Check query was successful
                        if ($result) {
                            // Set newAccount session variable and navigate to userLogin.php
                            $_SESSION['newAccount'] = "True";
                            header("location:userLogin.php");
                        }
                        else {
                            // If results couldn't be returned display appropriate error message
                            echo "<p>Error creating your account</p>";
                            echo "<p>Error description: " . mysqli_error($connection) . "</p>";
                        }                            
                    }
                    else {
                        // The email has already been used, display error message to user
                        echo "<p>This email is already in use, try <a href='userLogin.php'>logging in</a> instead?</p>";
                    }
                }
                else {
                    // If results couldn't be returned display appropriate error message
                    echo "<p>Error searching database</p>";
                    echo "<p>Error description: " . mysqli_error($connection) . "</p>";
                }

                // Close the database connection
                mysqli_close($connection);
            }
        ?>
    </body>
</html>
