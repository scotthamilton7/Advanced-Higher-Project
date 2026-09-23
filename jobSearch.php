<?php
    // Start session
    session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Job Search</title>
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
            <section class="flex">
                <section class="content flexChild25" style="max-height: 470px;">
                    <h2>Filter</h2>
                    <form action="jobSearch.php" method="POST" id="jobSearchForm">
                        <input type="text" name="search" id="searchfield" placeholder="Search by title or location..."><br><br>

                        <label>Job type</label><br>
                        <input type="radio" id="partTime" name="jobType" value="Part-time" checked>
                        <label for="partTime">Part-time</label><br>
                        <input type="radio" id="fullTime" name="jobType" value="Full-time">
                        <label for="fullTime">Full-time</label><br>
                        <input type="radio" id="apprenticeship" name="jobType" value="Apprenticeship">
                        <label for="apprenticeship">Apprenticeship</label><br><br>

                        <label for="wkHrs">Weekly hours</label><br>
                        <select name="weeklyHours" id="wkHrs">
                            <option value="0">Any</option>
                            <option value="5">5 hours +</option>
                            <option value="10">10 hours +</option>
                            <option value="15">15 hours +</option>
                            <option value="20">20 hours +</option>
                            <option value="25">25 hours +</option>
                            <option value="30">30 hours +</option>
                        </select><br><br>

                        <label for="hrRate">Houry rate</label><br>
                        <select name="hourlyRate" id="hrRate">
                            <option value="0">Any</option>
                            <option value="5">£5/hr +</option>
                            <option value="7">£7/hr +</option>
                            <option value="9">£9/hr +</option>
                            <option value="11">£11/hr +</option>
                            <option value="13">£13/hr +</option>
                        </select><br><br>

                        <section class="centerText">
                            <input type="submit" name="searchButton" id="submitBtn" value="Search">
                        </section><br>
                    </form>
                </section>

                <section class="content flexChild75">
                    <h2>Available Jobs</h2>
                    
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

                        if (ISSET($_POST['searchButton'])) {
                            // Set error reporting to exclude warnings
                            // Only fatal errors and parse errors are displayed
                            error_reporting(E_ERROR | E_PARSE);

                            // Include the database connection file
                            require_once 'config.php';

                            // Create variables with data from html form
                            $searchString = $_POST['search'];
                            $jobType = $_POST['jobType'];
                            $wkHours = $_POST['weeklyHours'];
                            $hrRate = $_POST['hourlyRate'];

                            // Create query to get jobs matching search options
                            $query = "SELECT * FROM job, business WHERE job.businessID = business.businessID AND jobType = '$jobType' AND weeklyHours >= '$wkHours' AND hourlyRate >= '$hrRate' AND (title LIKE '%$searchString%' OR location LIKE '%$searchString%') ORDER BY jobID DESC";

                            // Execute the query using the active database
                            $result = mysqli_query($connection, $query);

                            // Check whether query has been succesful
                            if ($result) {
                                // Count number of rows, if 1 or more are returned display most recent jobs
                                $row_count = mysqli_num_rows($result);
                                echo "<p>$row_count results</p>";

                                if ($row_count > 0) {
                                    // Display most recent jobs in a section
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        // Declare job details variables from database
                                        $jobID = $row['jobID'];
                                        $jobTitle = $row['title'];
                                        $jobType = $row['jobType'];
                                        $briefDesc = substr($row['description'], 0, 100) . "...";
                                        $hourlyRate = $row['hourlyRate'];
                                        $wkHours = $row['weeklyHours'];
                                        $location = $row['location'];

                                        // Declare business details variables from database
                                        $busName = $row['businessName'];

                                        echo "<section class='flex card'>
                                                <section class='flexChild75'>
                                                    <h3>$jobTitle at $busName</h3>

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

                                                    <p>$briefDesc</p>
                                                </section>
                                                <section class='centerText flexCenter flexChild25'>
                                                    <a href='apply.php?job=$jobID'><button type='button'>Apply Now</button></a>
                                                </section>
                                            </section>";
                                    }
                                }
                                else {
                                    // If no records are returned then no jobs were found
                                    echo "<p>No jobs were found in the database. Try altering your filters.</p>";
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
                        else {
                            // No search has been made
                            echo "<p>Make a search query to start searching for jobs.</p>";
                        }                    
                    ?>
                </section>
            </section>
        </main>
    </body>
</html>
