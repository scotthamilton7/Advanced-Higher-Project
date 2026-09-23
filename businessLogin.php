<?php
    //Start session
    session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Business Login</title>
        <link rel="stylesheet" type="text/css" href="styles.css">
        <link rel="icon" type="image/x-icon" href="images/favicon.png">
    </head>

    <body>
        <section class="centerBox" id="loginBox">            
            <ul id="tabSelect">
                <li><a href="userLogin.php" style="border-top-left-radius: 16px;">Personal</a></li>
                <li><a href="businessLogin.php" class="active" style="border-top-right-radius: 16px;">Business</a></li>
            </ul>

            <section class="loginForm">
                <section class="centerText">
                    <img src="images/logo.png" alt="logo" width="200px">
                </section>
                <h1 class="centerText">Log In</h1>
                <p class="centerText">BUSINESS ACCOUNT</p>

                <?php
                    // Set error reporting to exclude warnings
                    // Only fatal errors and parse errors are displayed
                    error_reporting(E_ERROR | E_PARSE);

                    if (ISSET($_SESSION['newAccount'])) {
                        // New account display message to tell user to log in
                        echo "<p><b>You're almost there! Please sign into your new account.</b></p>";
                        UNSET($_SESSION['newAccount']);
                    }
                ?>

                <form id="businessLoginForm" action="businessLogin.php" method="POST">
                    <label for="email">Email*</label><br>
                    <input type="text" id="email" name="businessEmail" required maxlength="100"><br><br>
                    <label for="pword"> Password*</labe><br>
                    <input type="password" id="pword" name="password" required maxlength="30"><br><br>

                    <section class="flex">
                        <a class="flexChild50" href="businessSignUp.php">Create Account</a>
                        <input class="flexChild50" value="Log In" type="submit" name="logInBtn">
                    </section>
                </form> 
                <br>
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
            if (ISSET($_POST['logInBtn'])) {
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
                $email = $_POST['businessEmail'];
                $pword = $_POST['password'];

                // Create query to search database with account details entered
                $query = "SELECT * FROM business WHERE business.email = '$email' AND business.password = '$pword'";

                // Execute the query using the active database
                $result = mysqli_query($connection, $query);

                // Check whether query has been succesful
                if ($result) {
                    // Count number of rows, if 1 row is returned the details are valid
                    $row_count = mysqli_num_rows($result);

                    if ($row_count == 1) {
                        // Assign the businessID and the account type to session variables
                        $row = mysqli_fetch_assoc($result);
                        $_SESSION['accountID'] = $row['businessID'];
                        $_SESSION['accountType'] = "Business";

                        // If a row is returned the details are correct, navigate to businessHome.php
                        header("Location:businessHome.php");
                    }
                    else {
                        // If no records are returned then details are incorrect or account type is personal
                        echo "<p>Could not find your account, try:</p>
                            <ol>
                                <li><a href='userLogin.php'>Log in as a user instead</a></li>
                                <li><a href='businessSignUp.php'>Create a new account</a></li>
                            </ol>";
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
