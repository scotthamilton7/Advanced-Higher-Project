<?php
    // Start session
    session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Apply for Job</title>
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

                // User has logged in, display job details
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

                // Variables are created from data sent using GET method
                $jobID = $_GET['job'];
                
                // Create query to get details for selected job
                $query = "SELECT * FROM job, business WHERE job.jobID = '$jobID' and job.businessID = business.businessID";

                // Execute the query using the active database
                $result = mysqli_query($connection, $query);

                // Check whether query has been succesful
                if ($result) {
                    // Count number of rows, if 1 or more are returned display most recent jobs
                    $row_count = mysqli_num_rows($result);

                    if ($row_count > 0) {
                        // Job has been found in database, display details for job
                        // Store job details in accosciative array
                        $row = mysqli_fetch_assoc($result);      

                        // Create variables as required
                        // Business details
                        $busName = $row['businessName'];
                        $busEmail = $row['email'];
                        $busPhone = $row['phoneNo'];

                        // Job Details
                        $jobTitle = $row['title'];
                        $jobType = $row['jobType'];
                        $jobDesc = htmlspecialchars_decode(str_replace("\n", "<br>", $row['description']));
                        $hourlyRate = $row['hourlyRate'];
                        $wkHours = $row['weeklyHours'];
                        $location = $row['location'];

                        // CODE FOR SUBMITTING AN APPLICATION
                        if (ISSET($_POST['submitCover'])) {
                            // Application submit button has been pressed

                            // Create variables for userID and cover letter (from POST)
                            $userID = $_SESSION['accountID'];
                            $coverLetter = htmlspecialchars($_POST['coverLetter']);
                            
                            // Create query to check application does not already exist
                            $query = "SELECT * FROM application WHERE userID = '$userID' AND jobID = '$jobID'";

                            // Execute the query using the active database
                            $result = mysqli_query($connection, $query);

                            // Check whether query was successful
                            if ($result) {
                                // Count number of rows, if 0 rows are returned the application has not already been made
                                $row_count = mysqli_num_rows($result);

                                if ($row_count == 0) {
                                    // Application has not already been made so should be created
                                    // Create query to insert new application into database
                                    $query = "INSERT INTO application(userID, jobID, coverLetter) VALUES('$userID', '$jobID', '$coverLetter')";

                                    // Execute the query using the active database
                                    $result = mysqli_query($connection, $query);

                                    // Check query was successful
                                    if ($result) {
                                        // Query was successful and application has been made
                                        echo "<section class='content'>
                                                <h2>Application Successful</h2>
                                                <p>You have applied for $jobTitle at $busName, good luck!</p>
                                                <p>View all of your applications <a href='applications.php'>here</a>.</p>
                                            </section>";
                                    }
                                    else {
                                        // Query was not successful and application has not been made
                                        echo "<p>Error submitting your application</p>";
                                        echo "<p>Error description: " . mysqli_error($connection) . "</p>";
                                    }
                                }
                                else {
                                    // Application has already been made, display message to user
                                    echo "<section class='content'>
                                                <h2>Already Applied</h2>
                                                <p>You have already applied for $jobTitle at $busName, you cannot apply again.</p>
                                                <p>View all of your applications <a href='applications.php'>here</a>.</p>
                                            </section>";
                                }
                            }
                            else {
                                // If results couldn't be returned display appropriate error message
                                echo "<p>Error searching database</p>";
                                echo "<p>Error description: " . mysqli_error($connection) . "</p>";
                            }
                        }                                               

                        // Display details
                        echo "<section class='content'>
                                <h2>Apply for $jobTitle</h2>

                                <table class='jobDetails'>
                                    <tr>
                                        <td class='tdShade'>Job Type</td>
                                        <td>$jobType</td>
                                        <td class='tdShade'>Hourly Rate</td>
                                        <td>$hourlyRate</td>
                                    </tr>
                                    <tr>
                                        <td class='tdShade'>Weekly Hours</td>
                                        <td>$wkHours</td>
                                        <td class='tdShade'>Location</td>
                                        <td>$location</td>
                                    </tr>
                                </table>

                                <section class='card'>
                                    <h3>Business Details</h2>
                                    <table>
                                        <tr>
                                            <td>Name</td>
                                            <td>$busName</td>
                                        </tr>
                                        <tr>
                                            <td>Email</td>
                                            <td>$busEmail</td>
                                        </tr>
                                        <tr>
                                            <td>Phone Number</td>
                                            <td>$busPhone</td>
                                        </tr>
                                    </table>

                                    <h3>Job Description</h3>
                                    <p>$jobDesc</p>
                                </section>

                                <form id='coverLetterForm' action='apply.php?job=$jobID' method='POST'>
                                    <section class='card'>
                                        <h3>Cover Letter (Recommended)</h3>
                                        <p>This is where you can tell the employer a little bit about yourself. Consider mentioning why you think you are ideal for the job.</p>
                                        
                                        <textarea name='coverLetter' id='coverTextArea' maxlength='2000'></textarea>
                                        <p id='coverCharsRemaining'>2000 Characters Remaining</p>
                                    </section>

                                    <section id='submitApplication'>
                                        <input type='submit' value='Submit' id='submitBtn' name='submitCover'>
                                    </section>
                                </form>
                            </section>";                                 
                    }
                    else {
                        // Job was not found in database, display error message
                        echo "<p>This job was not found in the database.</p>";
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

        <script>
            const textarea = document.getElementById("coverTextArea");

            textarea.addEventListener("input", event => {
                const target = event.currentTarget;
                const maxLength = target.getAttribute("maxlength");
                const currentLength = target.value.length;
                const charsRemaining = maxLength - currentLength;

                document.getElementById("coverCharsRemaining").innerText = charsRemaining + " Characters Remaining";
            });
        </script>
    </body>
</html>
