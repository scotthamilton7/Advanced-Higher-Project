<?php
    // Start session
    session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Home</title>
        <link rel="stylesheet" type="text/css" href="styles.css">
        <link rel="icon" type="image/x-icon" href="images/favicon.png">
    </head>

    <body>
        <header>
            <img src="images/businessBanner.png" alt="Banner Image">
        </header>
    
        <nav>
            <ul>
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
            </ul>

            <?php
                if (ISSET($_POST['logOutBtn'])) {
                    // Log out button has been pressed, destory session and navigate to landing page
                    session_destroy();
                    header("Location:index.html");
                }
            ?>
        </nav>

        <main>
            <?php
                if (!ISSET($_SESSION['accountID'])) {
                    // User has not logged in, navigate to userLogin.php
                    header("Location:userLogin.php");
                } 

                // Check if the account type is personal
                if ($_SESSION['accountType'] == "Personal") {
                    // Account type is personal, navigate to userHome.php
                    header("Location:userHome.php");
                }

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

                // Assign business ID to a variable
                $businessID = $_SESSION['accountID'];

                // Create query to select the 3 most recent job listings for the business
                $query = "SELECT * FROM job WHERE businessID = '$businessID' ORDER BY jobID DESC LIMIT 3";

                // Execute the query using the active database
                $result = mysqli_query($connection, $query);

                // Check whether query has been succesful
                if ($result) {
                    // Count number of rows, if 1 or more are returned display most recent jobs
                    $row_count = mysqli_num_rows($result);

                    if ($row_count > 0) {
                        // Create section for recent jobs
                        echo "<section class='content'>
                                <h3>Your Recent Listings</h3>";
                        
                        // Display most recent jobs in a section
                        while ($row = mysqli_fetch_assoc($result)) {
                            // Declare variables from database
                            $jobID = $row['jobID'];
                            $jobTitle = $row['title'];
                            $briefDesc = htmlspecialchars_decode(substr($row['description'], 0, 100) . "...");

                            echo "<section class='flex card'>
                                    <section class='flexChild75'>
                                        <h3>$jobTitle</h3>
                                        <p>$briefDesc</p>
                                    </section>
                                    <section class='centerText flexCenter flexChild25'>
                                        <a href='viewApplicants.php?job=$jobID'><button type='button'>View Applicants</button></a>
                                    </section>
                                </section>";
                        }

                        // Close recent jobs section
                        echo "</section>";
                    }
                    else {
                        // If no records are returned then no jobs were found
                        echo "<section class='content'>
                                <h3>No recent job listings</h3>
                                <p>You haven't made any job listings yet, try creating your first one <a href='createJob.php'>here</a>.</p>
                            </section>";
                    }
                }
                else {
                    // If results couldn't be returned display appropriate error message
                    echo "<p>Error searching database</p>";
                    echo "<p>Error description: " . mysqli_error($connection) . "</p>";
                }

                // Close the database connection
                mysqli_close($connection);                    
            ?>
        </main>
    </body>
</html>
