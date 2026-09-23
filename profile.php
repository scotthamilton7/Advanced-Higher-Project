<?php
    // start session
    session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Profile</title>
        <link rel="stylesheet" type="text/css" href="styles.css">
        <link rel="icon" type="image/x-icon" href="images/favicon.png">
    </head>

    <body>
        <?php
            if (ISSET($_SESSION['accountID'])) {
                // Set error reporting to exclude warnings
                // Only fatal errors and parse errors are displayed
                error_reporting(E_ERROR | E_PARSE);

                if (ISSET($_POST['logOutBtn'])) {
                    // Log out button has been pressed, destory session and navigate to landing page
                    session_destroy();
                    header("Location:index.html");
                } 
                else {
                    // Log out button has not been pressed, connect to database
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

                    // Get accountID from session variable
                    $accountID = $_SESSION['accountID'];
                }
            }
            else {
                // User has not logged in, navigate to userLogin.php
                header("Location:userLogin.php");
            }
        ?>

        <header>
            <?php
                if ($_SESSION['accountType'] == "Personal") {
                    echo "<img src='images/banner.png' alt='Banner Image'>";
                } else if ($_SESSION['accountType'] == "Business") {
                    echo "<img src='images/businessBanner.png' alt='Banner Image'>";
                }
            ?>
        </header>
        
        <nav>
            <?php
                if ($_SESSION['accountType'] == "Personal") {
                    // Account type is personal, diaplay nav bar for personal accounts
                    echo "<ul>
                            <li><a href='userHome.php' class='left'>Home</a></li>
                            <li><a href='jobSearch.php' class='left'>Job Search</a></li>
                            <li><a href='applications.php' class='left'>Applications</a></li>
            
                            <li class='dropdown right'>
                                <a href='javascript:void(0)' class='dropdownBtn'>Profile ></a>
                                <div class='dropdown-content'>
                                    <a href='profile.php'>View Profile</a>
                                    <a id='dropdownBottom' href=''>
                                        <form method='POST'>
                                            <input type='submit' id='logOutButton' name='logOutBtn' value='Log Out'>
                                        </form>
                                    </a>
                                </div>
                            </li>
                        </ul>";                
                }
                elseif ($_SESSION['accountType'] == "Business") {
                    // Account type is business, display nav bar for business accounts
                    echo "<ul>
                            <li><a href='businessHome.php' class='left'>Home</a></li>
                            <li><a href='createJob.php' class='left'>Create Job</a></li>
                            <li><a href='viewListings.php' class='left'>View Listings</a></li>
                            
                            <li class='dropdown right'>
                                <a href='javascript:void(0)' class='dropdownBtn'>Profile ></a>
                                <div class='dropdown-content'>
                                    <a href='profile.php'>View Profile</a>
                                    <a id='dropdownBottom' href=''>
                                        <form method='POST'>
                                            <input type='submit' id='logOutButton' name='logOutBtn' value='Log Out'>
                                        </form>
                                    </a>
                                </div>
                            </li>
                        </ul>";        
                }
            ?>
        </nav>

        <main>
            <?php
                if ($_SESSION['accountType'] == "Personal") {
                    // Account type is personal display section containing personal account details
                    // Create query to search user table
                    $query = "SELECT * FROM user WHERE user.userID = '$accountID'";

                    // Execute the query using the active database
                    $result = mysqli_query($connection, $query);

                    // Check whether the query has been successful and display user account details
                    if ($result) {
                        // Count number of rows, if 1 row is returned the user exists and the details should be displayed
                        $row_count = mysqli_num_rows($result);

                        if ($row_count == 1) {
                            // Get account details using associative array
                            $row = mysqli_fetch_assoc($result);
                            $name = $row['userName'];
                            $email = $row['email'];

                            // Display account details
                            echo "<section class='content'>
                                    <h2>Your Profile</h2>
                                    <p>PERSONAL ACCOUNT</p>
                                    <table>
                                        <tr>
                                            <td>Name</td>
                                            <td>$name</td>
                                        </tr>
                                        <tr>
                                            <td>Email</td>
                                            <td>$email</td>                                    
                                        </tr>
                                    </table>
                                    <br>
                                    <form method='POST'>
                                        <input name='logOutBtn' type='submit' value='Log Out'>
                                    </form>
                                </section>";
                        }
                        else {
                            // No records have matched (should never happen) so display error message
                            echo "<p>Could not find user in database.</p>";
                        }
                    }
                    else {
                        // If results couldn't be returned display appropriate error message
                        echo "<p>Error searching database</p>";
                        echo "<p>Error description: " . mysqli_error($connection) . "</p>";
                    }

                }
                elseif ($_SESSION['accountType'] == "Business") {
                    // Account type is business display section containing business account details
                    // Create query to search business table
                    $query = "SELECT * FROM business WHERE business.businessID = '$accountID'";

                    // Execute the query using the active database
                    $result = mysqli_query($connection, $query);

                    // Check whether the query has been successful and display business account details
                    if ($result) {
                        // Count number of rows, if 1 row is returned the business exists and the details should be displayed
                        $row_count = mysqli_num_rows($result);

                        if ($row_count == 1) {
                            // Get account details using associative array
                            $row = mysqli_fetch_assoc($result);
                            $name = $row['businessName'];
                            $email = $row['email'];
                            $phoneNo = $row['phoneNo'];

                            // Display account details
                            echo "<section class='content'>
                                    <h2>Your Profile</h2>
                                    <p>BUSINESS ACCOUNT</p>
                                    <table>
                                        <tr>
                                            <td>Name</td>
                                            <td style='padding-left: 20px;'>$name</td>
                                        </tr>
                                        <tr>
                                            <td>Email</td>
                                            <td style='padding-left: 20px;'>$email</td>                                    
                                        </tr>
                                        <tr>
                                            <td>Phone Number</td>
                                            <td style='padding-left: 20px;'>$phoneNo</td>
                                        </tr>
                                    </table>
                                    <br>
                                    <form method='POST'>
                                        <input name='logOutBtn' type='submit' value='Log Out'>
                                    </form>
                                </section>";
                        }
                        else {
                            // No records have matched (should never happen) so display error message
                            echo "<p>Could not find business in database.</p>";
                        }
                    }
                    else {
                        // If results couldn't be returned display appropriate error message
                        echo "<p>Error searching database</p>";
                        echo "<p>Error description: " . mysqli_error($connection) . "</p>";
                    }
                }
            ?>
        </main>

        <?php
            // Close the database connection
            mysqli_close($connection);
        ?>
    </body>
</html>
