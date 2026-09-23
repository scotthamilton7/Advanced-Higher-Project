<?php 
    // Start session
    session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Create a Job</title>
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

                // Check if form has been submitted
                if (ISSET($_POST['createJob'])) {
                    // Set error reporting to exclude warnings
                    // Only fatal errors and parse errors are displayed
                    error_reporting(E_ERROR | E_PARSE);

                    // Include the database connection file
                    require_once 'config.php';

                    // Create variable for businessID
                    $businessID = $_SESSION['accountID'];

                    // Store data from html form in php variables
                    $jobTitle = $_POST['jobTitle'];
                    $jobType = $_POST['jobType'];
                    $hourlyRate = $_POST['hourlyRate'];
                    $weeklyHours = $_POST['weeklyHours'];
                    $jobLocation = $_POST['jobLocation'];
                    $description = htmlspecialchars($_POST['description']);

                    // Create query to insert job into database
                    $query = "INSERT INTO job(title, jobType, businessID, description, weeklyHours, hourlyRate, location) VALUES('$jobTitle', '$jobType', '$businessID', '$description', '$weeklyHours', '$hourlyRate', '$jobLocation')";
                
                    // Execute the query using the active database
                    $result = mysqli_query($connection, $query);

                    // Check whether query was successful
                    if ($result) {
                        // Query was successful and application has been made
                        echo "<section class='content'>
                            <h2>Job Created</h2>
                            <p>You have created the position $jobTitle!</p>
                            <p>View all of your listings <a href='listings.php'>here</a>.</p>
                        </section>";
                    }
                    else {
                        // If query was unsuccessful display error message
                        echo "<p>Error creating job</p>";
                        echo "<p>Error description: " . mysqli_error($connection) . "</p>";
                    }

                    // Close the database connection
                    mysqli_close($connection);
                }       
            ?>
            
            <section class="content">
                <h2>Create a Job</h2>

                <form action="createJob.php" method="POST">
                    <section class="flex">
                        <section id="jobDetails" class="flexChild50" style="margin-right: 30px;">
                            <label for="title">Title</label><br>
                            <input type="text" name="jobTitle" id="title" required><br><br>

                            <label for="type">Job Type</label><br>
                            <select name="jobType" id="type">
                                <option value="Part-time">Part-time</option>
                                <option value="Full-time">Full-time</option>
                                <option value="Apprenticeship">Apprenticeship</option>
                            </select><br><br>

                            <label for="hrRate">Hourly Rate</label><br>
                            <input type="text" name="hourlyRate" id="hrRate" required><br><br>

                            <label for="wkHours">Weekly Hours</label><br>
                            <input type="text" name="weeklyHours" id="wkHours" required><br><br>

                            <label for="location">Location</label><br>
                            <input type="text" name="jobLocation" id="location" required><br><br>
                        </section>

                        <section class="flexChild50">
                            <label for="descriptionTextArea">Description</label>
                            <textarea name='description' id='descriptionTextArea' maxlength='4000' required></textarea>
                            <p id='descriptionCharsRemaining'>4000 Characters Remaining</p>

                            <section id="createJob">
                                <input type="submit" name="createJob" value="Create" id="createJobBtn">
                            </section>
                        </section>
                    </section>
                </form>
            </section>  
        </main>

        <script>
            const textarea = document.getElementById("descriptionTextArea");

            textarea.addEventListener("input", event => {
                const target = event.currentTarget;
                const maxLength = target.getAttribute("maxlength");
                const currentLength = target.value.length;
                const charsRemaining = maxLength - currentLength;

                document.getElementById("descriptionCharsRemaining").innerText = charsRemaining + " Characters Remaining";
            });
        </script>
    </body>
</html>
