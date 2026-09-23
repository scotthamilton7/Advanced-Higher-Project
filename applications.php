<?php
    // Start session
    session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Applications</title>
        <link rel="stylesheet" type="text/css" href="styles.css">
        <link rel="icon" type="image/x-icon" href="images/favicon.png">
    </head>

    <body>
        <header>
            <img src="images/banner.png" alt="Banner Image">
        </header>
    
        <nav>
            <ul>
                <li><a href="userHome.php" class="left">Home</a></li>
                <li><a href="jobSearch.php" class="left">Job Search</a></li>
                <li><a href="applications.php" class="left">Applications</a></li>

                <li class="dropdown right">
                    <a href="javascript:void(0)" class="dropdownBtn">Profile ></a>
                    <div class="dropdown-content">
                        <a href="profile.php">View Profile</a>
                        <a id="dropdownBottom" href="">
                            <form method="POST">
                                <input type="submit" id="logOutButton" name="logOutBtn" value="Log Out">
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
            <section class="content">
                <h2>Your Applications</h2>
                
                <?php
                    if (!ISSET($_SESSION['accountID'])) {
                        // User has not logged in, navigate to userLogin.php
                        header("Location:userLogin.php");
                    } 
    
                    // Check if the account type is business
                    if ($_SESSION['accountType'] == "Business") {
                        // Account type is business, navigate to businessHome.php
                        header("Location:businessHome.php");
                    }

                    // User has logged in display all of their existing applications starting with most recent
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

                    // Create variables for userID from session
                    $userID = $_SESSION['accountID'];

                    // Create query to get all existing applications for the current user
                    $query = "SELECT * FROM application, job, business WHERE userID = '$userID' AND application.jobID = job.jobID AND job.businessID = business.businessID ORDER BY applicationID DESC";

                    // Execute the query using the active database
                    $result = mysqli_query($connection, $query);

                    // Check whether query has been succesful
                    if ($result) {
                        // Count number of rows, if 1 or more are returned display existing applications
                        $row_count = mysqli_num_rows($result);

                        if ($row_count > 0) {
                            // Store result in associative array
                            while ($row = mysqli_fetch_assoc($result)) {
                                // Declare variables from database
                                $jobTitle = $row['title'];
                                $busName = $row['businessName'];
                                $description = str_replace("\n", "<br>", $row['description']);
                                $coverLetter = htmlspecialchars_decode($row['coverLetter']);

                                // If the cover letter is blank add message for user
                                if ($coverLetter == "") {
                                    $coverLetter = "No cover letter was provided.";
                                }

                                // Display job card
                                echo "<section class='card'>
                                        <h3>$jobTitle at $busName</h3>
                                        <details>
                                            <summary>Job Description</summary>
                                            <blockquote>$description</blockquote>
                                        </details>
                                        <details>
                                            <summary>Your cover letter</summary>
                                            <blockquote>$coverLetter</blockquote>
                                        </details>
                                        <br>
                                    </section>";
                            }
                        }
                        else {
                            // If no records are returned then no jobs were found
                            echo "<p>You have not applied to any jobs yet. You can search for jobs <a href='jobSearch.php'>here</a>.</p>";
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
            </section>
        </main>        
    </body>
</html>
