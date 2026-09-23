<?php
    // Start session
    session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>CV Guide</title>
        <link rel="stylesheet" type="text/css" href="styles.css">
        <link rel="icon" type="image/x-icon" href="images/favicon.png">
    </head>

    <body>
        <header>
            <img src="images/banner.png" alt="Banner Image">
        </header>
        
        <nav>
            <ul>
                <?php
                    if (ISSET($_SESSION['accountID'])) {
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
                    else {
                        echo "<li><a href='userLogin.php' class='right'>Login / Sign Up</a></li>
                            <li><a href='index.html' class='right'>Landing Page</a></li>";
                    }
                ?>
            </ul>
        </nav>

        <main>
            <section class="content">
                <h2>What makes a good CV?</h2>
                <p>Your CV should present your qualifications, skills and experience to potential employers. Some key things to include are listed below.</p>
                <p>Remember your CV should be formatted so that it is easy to read and pleasant to look at. It should be tailored for each job and should be error free.</p>
                <h3>1. Contact Information</h3>
                <p>Include your full name, phone number and email adress. You could also include a LinkedIn profile if you have one.</p>
                <h3>2. Education</h3>
                <p>What qualifications have you achieved? Are you working towards any further qualifications? Organise these in a list starting with the most recent.</p>
                <h3>3. Experience</h3>
                <p>What previous job roles have you had? Describe your major accomplishments and achievements in these roles. Organise these in a list starting with the most recent.</p>
                <h3>4. Skills</h3>
                <p>What skills do you have that are relevant to the position you are applying for? You should provide examples of how you can apply these skills.</p>
                <h3>5. Achievements</h3>
                <p>Have you recieved any significant achievements throughout your academic and professional careers?</p>
                <h3>6. References</h3>
                <p>Include some references or indicate that they are available upon request. Make sure you inform your references in advance.</p>
                <p>Some people you could use as referecnes are, but not limited to, <b>teachers and lecturers</b>, <b>managers and supervisors</b>, and <b>colleagues or team members</b>.</p>
                <h3>7. Other</h3>
                <p>You may also wish to include <b>certificates and training</b> which are relavant to the role, <b>languages</b> that you speak and your level of fluency, <b>projects</b> you have worked on including your role within the project, and <b>publications and presentations</b> you have contributed to.</p>            
            </section>

            <section class="content">
                <h2>Want some more help?</h2>
                <p>Here are some useful external resources:</p>
                <section class="links">
                    <a href="https://nationalcareers.service.gov.uk/careers-advice/cv-sections" target="_blank"><button type="button">UK Government</button></a>
                    <a href="https://www.indeed.com/career-advice/resumes-cover-letters/how-to-write-a-cv" target="_blank"><button type="button">Indeed</button></a>
                </section>
            </section>

            <br>
        </main>
    </body>
</html>
