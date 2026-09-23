<?php
    // Start session
    session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>View Applicants</title>
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

                    // Assign data to PHP variables
                    $jobID = $_GET['job'];
                    $businessID = $_SESSION['accountID'];

                    // Create query to display job details
                    $query = "SELECT * FROM job WHERE job.jobID = '$jobID'";

                    // Execute the query using the active database
                    $result = mysqli_query($connection, $query);

                    // Check whether query has been succesful
                    if ($result) {
                        // Store job details in accosciative array
                        $row = mysqli_fetch_assoc($result);                           
                        
                        // Job details variables
                        $jobTitle = $row['title'];
                        $jobType = $row['jobType'];
                        $jobDesc = htmlspecialchars_decode(str_replace("\n", "<br>", $row['description']));
                        $hourlyRate = $row['hourlyRate'];
                        $wkHours = $row['weeklyHours'];
                        $location = $row['location'];

                        echo "<section class='content'>
                                <h2>$jobTitle</h2>
                                <details>
                                    <summary>View job description</summary>
                                    <blockquote>$jobDesc</blockquote>
                                </details>
                                <br>
                            </section>";

                        // Open flex section
                        echo "<section class='flex' style='margin-top: -10px;'>";

                        echo "<section class='content flexChild25'>";

                        // Display job title
                        echo "<h3>Job details</h3>";

                        // Display job details
                        echo "<table class='jobDetails'>
                                <tr>
                                    <td class='tdShade'>Job Type</td>
                                    <td>$jobType</td>
                                </tr>
                                <tr>
                                    <td class='tdShade'>Hourly Rate</td>
                                    <td>$hourlyRate</td>
                                </tr>
                                <tr>
                                    <td class='tdShade'>Weekly Hours</td>
                                    <td>$wkHours</td>                                        
                                </tr>
                                <tr>
                                    <td class='tdShade'>Location</td>
                                    <td>$location</td>
                                </tr>
                            </table>";

                        echo "</section>";
                    }
                    else {
                        // If results couldn't be returned display appropriate error message
                        echo "<p>Error searching database</p>";
                        echo "<p>Error description: " . mysqli_error($connection) . "</p>";
                    }

                    // Create query to display applicant details
                    $query = "SELECT * FROM user, application WHERE application.jobID = '$jobID' AND user.userID = application.userID";

                    // Execute the query using the active database
                    $result = mysqli_query($connection, $query);

                    // Check whether query has been succesful
                    if ($result) {
                        // Count number of rows, if 1 or more are returned display applicants
                        $row_count = mysqli_num_rows($result);

                        if ($row_count > 0) {
                            // Create section for recent jobs
                            echo "<section class='content flexChild75'>
                                    <h3>Applicants</h3>";
                            
                            // Display applicants in a section
                            while ($row = mysqli_fetch_assoc($result)) {
                                // Declare variables from database
                                $userName = $row['userName'];
                                $userEmail = $row['email'];
                                $coverLetter = htmlspecialchars_decode($row['coverLetter']);

                                echo "<section class='card'>
                                    <h3>$userName</h3>
                                    <p>$userEmail</p>
                                    <details>
                                        <summary>View cover letter</summary>
                                        <blockquote>$coverLetter</blockquote>
                                    </details>
                                    <br>
                                </section>";
                            }

                            // Close flex section
                            echo "</section>";
                        }
                        else {
                            // If no records are returned then no jobs were found
                            echo "<section class='content flexChild75'>
                                    <h3>No Applicants Yet</h3>
                                    <p>No one has applied to this job yet but as soon as they do, they will show up here!</p>
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
