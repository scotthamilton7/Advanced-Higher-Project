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
                <section class="content flexChild75">
                    <h2>Recent Job Postings</h2>

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

                        // User has logged in, create query to get 3 most recent job listings
                        // Set error reporting to exclude warnings
                        // Only fatal errors and parse errors are displayed
                        error_reporting(E_ERROR | E_PARSE);

                        // Include the database connection file
                        require_once 'config.php';

                        // Create query to get 3 most recent jobs
                        $query = "SELECT * FROM job ORDER BY jobID DESC LIMIT 3";

                        // Execute the query using the active database
                        $result = mysqli_query($connection, $query);

                        // Check whether query has been succesful
                        if ($result) {
                            // Count number of rows, if 1 or more are returned display most recent jobs
                            $row_count = mysqli_num_rows($result);

                            if ($row_count > 0) {
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
                                            <section class='flexchild25 centerText flexCenter'>
                                                <a href='apply.php?job=$jobID'><button type='button'>Apply Now</button></a>
                                            </section>
                                        </section>";
                                }
                            }
                            else {
                                // If no records are returned then no jobs were found
                                echo "<p>No jobs were found in the database. Try refreshing this page.</p>";
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
                <section class="content flexChild25">
                    <h2>CV Guide</h2>
                    <section class="centerText">
                        <img src="images/CV.png" alt="CV Image" width="250px">
                        <p>View our full guide on how to write a professional CV to maximise the chances of being noticed by employers.</p>
                        <button type="button" onclick="window.location.href='cvguide.php'">View Now</button>
                    </section>
                </section>
            </section>
        </main>
    </body>
</html>
