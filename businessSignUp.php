<?php
    // Start session
    session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Business Sign Up</title>
        <link rel="stylesheet" type="text/css" href="styles.css">
        <link rel="icon" type="image/x-icon" href="images/favicon.png">
    </head>

    <body>
        <section class="centerBox" id="loginBox">
            <ul id="tabSelect">
                <li><a href="userSignUp.php" style="border-top-left-radius: 16px;">Personal</a></li>
                <li><a href="businessSignUp.php" class="active" style="border-top-right-radius: 16px;">Business</a></li>
            </ul>

            <section class="loginForm">
                <section class="centerText">
                    <img src="images/logo.png" alt="logo" width="200px">
                </section>
                <h1 class="centerText">Sign Up</h1>
                <p class="centerText">BUSINESS ACCOUNT</p>

                <form id="businessSignUpForm" action="businessSignUp.php" method="POST">
                    <label for="bname">Business Name*</label><br>
                    <input type="text" id="bname" name="businessName" required maxlength="70"><br><br>
                    <label for="email">Email*</label><br>
                    <input type="email" id="email" name="businessEmail" required maxlength="100"><br><br>
                    <label for="phoneNo">Phone Number*</label><br>
                    <input type="text" id="phoneNo" name="phoneNumber" required maxlength="15"><br><br>
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

                // Include the database connection file
                require_once 'config.php';

                // Variables are created with the data input on the HTML form
                $bname = $_POST['businessName'];
                $email = $_POST['businessEmail'];
                $phoneNo = $_POST['phoneNumber'];
                $pword = $_POST['password'];

                // Create a query to check if the email address has already been used
                $query = "SELECT * FROM business WHERE business.email = '$email'";

                //Execute the query using the active database
                $result = mysqli_query($connection, $query);

                // Check whether the query has been successful and if email has been used previously
                if ($result) {
                    // Count the number rows, if 0 rows are returned then the email has not been used already
                    $row_count = mysqli_num_rows($result);

                    if ($row_count == 0) {
                        // Email has not already been used and a new account can be created
                        // Create a query to create a new account
                        $query = "INSERT INTO business(businessName, email, phoneNo, password) VALUES('$bname', '$email', '$phoneNo', '$pword')";

                        // Execute the query using the active database
                        $result = mysqli_query($connection, $query);

                        // Check query was successful
                        if ($result) {
                            // Set newAccount session variable and navigate to businessLogin.php
                            $_SESSION['newAccount'] = "True";
                            header("location:businessLogin.php");
                        }
                        else {
                            // If results couldn't be returned display appropriate error message
                            echo "<p>Error creating your account</p>";
                            echo "<p>Error description: " . mysqli_error($connection) . "</p>";
                        }                            
                    }
                    else {
                        // The email has already been used, display error message to user
                        echo "<p>This email is already in use, try <a href='businessLogin.php'>logging in</a> instead?</p>";
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
