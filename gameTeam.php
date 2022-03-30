<html>
    <head>
        <title>Venue Information Table</title>
    </head>

    <body>
        <table>
            <tr>
            	<td>
                    <form method="POST" action="venue.php">
                        <p><input type="submit" value="Venue" name="company"></p>
                    </form>
                </td>
                <td>
                    <form method="POST" action="game_developedBy.php">
                        <p><input type="submit" value="Company" name="company"></p>
                    </form>
                </td>
                <td>
                    <form method="POST" action="esprotGame.php">
                        <p><input type="submit" value="Egame" name="egame"></p> 
                    </form>
                </td>
                <td>
                    <form method="POST" action="player.php">
                        <p><input type="submit" value="Player" name="player"></p>
                    </form>
                </td>
                <td>
                    <form method="POST" action="gameReferee.php">
                        <p><input type="submit" value="Referee" name="referee"></p>
                    </form>
                </td>
                <td>
                    <form method="POST" action="commentator.php">
                        <p><input type="submit" value="Commentator" name="commentator"></p>
                    </form>
                </td>
            </tr>
        </table>
        <hr />

        <h2>Insert Values into Team Table</h2>
        <form method="POST" action="gameTeam.php"> <!--refresh page when submitted-->
            <input type="hidden" id="insertQueryRequest" name="insertQueryRequest">
            Name: <input type="text" name="insName"> <br /><br />
            City: <input type="text" name="insCity"> <br /><br />
            Trophy : <input type="text" name="insTrophy" size = "100"> <br /><br />
            <input type="submit" value="Insert" name="insertSubmit"></p>
        </form>

        <hr />

        <h2>Add Trophy in Team Table</h2>

        <form method="POST" action="gameTeam.php"> <!--refresh page when submitted-->
            <input type="hidden" id="updateQueryRequest" name="updateQueryRequest">
            Name: <input type="text" name="uName"> <br /><br />
            City: <input type="text" name="uCity" > <br /><br />
            Add new Trophy : <input type="text" name="uTrophy" size = "100"> <br /><br />
            <input type="submit" value="Update" name="updateSubmit"></p>
        </form>

        <hr />

        <h2>Delete in Team Table</h2>

        <form method="POST" action="gameTeam.php"> <!--refresh page when submitted-->
            <input type="hidden" id="deleteQueryRequest" name="deleteQueryRequest">
            Name: <input type="text" name="delName"> <br /><br />
            City: <input type="text" name="delCity"> <br /><br />
            <input type="submit" value="Delete" name="deleteSubmit"></p>
        </form>

        <hr />
        <h2>Display the Tuple in Team Table</h2>
        <form method="POST" action="gameTeam.php"> <!--refresh page when submitted-->
            <label for="opeartors">Team:</label>
            <select name="operator" id="operator">
            <option value="ave">AVE</option>
            <option value="max">MAX</option>
            <option value="min">MIN</option>
            <option value="all">ALL</option>
            </select>
            <label for="a/k"></label>
            <select name="ak" id="ak">
            <option value="age">age</option>
            <option value="salary">salary</option>
            </select>
        <br><br>
            <input type="hidden" id="displayQueryRequest" name="displayQueryRequest">
            <input type="submit" value="Display" name="displaySubmit"></p>
        </form>

        <?php
        $success = True; //keep track of errors so it redirects the page only if there are no errors
        $db_conn = NULL; // edit the login credentials in connectToDB()
        $show_debug_alert_messages = False; // set to True if you want alerts to show you which methods are being triggered (see how it is used in debugAlertMessage())

        function debugAlertMessage($message) {
            global $show_debug_alert_messages;

            if ($show_debug_alert_messages) {
                echo "<script type='text/javascript'>alert('" . $message . "');</script>";
            }
        }

        function executePlainSQL($cmdstr) { //takes a plain (no bound variables) SQL command and executes it
            //echo "<br>running ".$cmdstr."<br>";
            global $db_conn, $success;

            $statement = OCIParse($db_conn, $cmdstr);
            //There are a set of comments at the end of the file that describe some of the OCI specific functions and how they work

            if (!$statement) {
                echo "<br>Cannot parse the following command: " . $cmdstr . "<br>";
                $e = OCI_Error($db_conn); // For OCIParse errors pass the connection handle
                echo htmlentities($e['message']);
                $success = False;
            }

            $r = OCIExecute($statement, OCI_DEFAULT);
            if (!$r) {
                echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
                $e = oci_error($statement); // For OCIExecute errors pass the statementhandle
                echo htmlentities($e['message']);
                $success = False;
            }

            return $statement;
        }

        function executeBoundSQL($cmdstr, $list) {
            /* Sometimes the same statement will be executed several times with different values for the variables involved in the query.
        In this case you don't need to create the statement several times. Bound variables cause a statement to only be
        parsed once and you can reuse the statement. This is also very useful in protecting against SQL injection.
        See the sample code below for how this function is used */

            global $db_conn, $success;
            $statement = OCIParse($db_conn, $cmdstr);

            if (!$statement) {
                echo "<br>Cannot parse the following command: " . $cmdstr . "<br>";
                $e = OCI_Error($db_conn);
                echo htmlentities($e['message']);
                $success = False;
            }

            foreach ($list as $tuple) {
                foreach ($tuple as $bind => $val) {
                    //echo $val;
                    //echo "<br>".$bind."<br>";
                    OCIBindByName($statement, $bind, $val);
                    unset ($val); //make sure you do not remove this. Otherwise $val will remain in an array object wrapper which will not be recognized by Oracle as a proper datatype
                }

                $r = OCIExecute($statement, OCI_DEFAULT);
                if (!$r) {
                    echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
                    $e = OCI_Error($statement); // For OCIExecute errors, pass the statementhandle
                    echo htmlentities($e['message']);
                    echo "<br>";
                    $success = False;
                }
            }
        }
        function printResult($result,$case) { //prints results from a select statement
        	switch ($case) {
        		case 0:
        			echo "<table>";
            		echo "<tr><th>Name</th><th>Average</th></tr>";

            		while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                	echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td> </tr>";
                	//or just use "echo $row[0]"
            		}
        			break;
        		case 1:
        			echo "<table>";
            		echo "<tr><th>Name</th><th>Lowest</th></tr>";

            		while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                	echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td> </tr>";
                	//or just use "echo $row[0]"
            		}
        			break;
        		case 2:
        			echo "<table>";
            		echo "<tr><th>Name</th><th>Highest</th></tr>";

            		while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                	echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td> </tr>";
                	//or just use "echo $row[0]"
            		}
        			break;
        		
        		default:
        			echo "<table>";
            		echo "<tr><th>Name</th><th>City</th><th>Trophy</th></tr>";

            		while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                	echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td> <td>" . $row[2] . "</td> </tr>";
                	//or just use "echo $row[0]"
            		}

            		echo "</table>";
        			break;
        	}
        }


        function connectToDB() {
            global $db_conn;

            // Your username is ora_(CWL_ID) and the password is a(student number). For example,
            // ora_platypus is the username and a12345678 is the password.
            $db_conn = OCILogon("ora_suplc", "a85579795", "dbhost.students.cs.ubc.ca:1522/stu");
            if ($db_conn) {
                debugAlertMessage("Database is Connected");
                return true;
            } else {
                debugAlertMessage("Cannot connect to Database");
                $e = OCI_Error(); // For OCILogon errors pass no handle
                echo htmlentities($e['message']);
                return false;
            }
        }
        function disconnectFromDB() {
            global $db_conn;

            debugAlertMessage("Disconnect from Database");
            OCILogoff($db_conn);
        }
        function handleUpdateRequest() {
            global $db_conn;

            $name = $_POST['uName'];
            $city = $_POST['uCity'];
            $Trophy = $_POST['uTrophy'];

            // you need the wrap the old name and new name values with single quotations
            executePlainSQL("UPDATE GameTeam SET teamTrophy = teamTrophy || ' ' || '" . $Trophy . "' WHERE teamName='" . $name . "' AND teamCity = '" . $city . "'");
            OCICommit($db_conn);
        }
        function handleDeleteRequest() {
            global $db_conn;
            $name = $_POST['delName'];
            $city = $_POST['delCity'];
            executePlainSQL("DELETE FROM GameTeam WHERE teamName= '" . $name . "' AND teamCity = '".$city."'");
            OCICommit($db_conn);
        }
        function handleInsertRequest() {
            global $db_conn;

            //Getting the values from user and insert data into the table
            $tuple = array (
                ":bind1" => $_POST['insName'],
                ":bind2" => $_POST['insCity'],
                ":bind3" => $_POST['insTrophy']
            );

            $alltuples = array (
                $tuple
            );

            executeBoundSQL("insert into GameTeam values (:bind1, :bind2,:bind3)", $alltuples);
            OCICommit($db_conn);
        }
        function handleDisplayRequest() {
            global $db_conn;
            $operator = $_POST['operator'];
            $ak = $_POST['ak'];
            $case = Null;
            if($operator == "ave"){
            	$case = 0;
                $result = executePlainSQL("SELECT GameTeam.teamName,AVG($ak) FROM GameTeam,Player WHERE player.teamName = GameTeam.teamName GROUP BY GameTeam.teamName");
            }else if($operator == "max"){
            	$case = 2;
                $result = executePlainSQL("SELECT GameTeam.teamName,MAX($ak) FROM GameTeam,Player WHERE player.teamName = GameTeam.teamName GROUP BY GameTeam.teamName");
            }else if($operator == "min"){
            	$case = 1;
                $result = executePlainSQL("SELECT GameTeam.teamName,MIN($ak) FROM GameTeam,Player WHERE player.teamName = GameTeam.teamName GROUP BY GameTeam.teamName");
            }else if($operator == "all"){
            	$case = 3;
                $result = executePlainSQL("SELECT * FROM GameTeam");
            }
            
            printResult($result,$case);
            
        }
        function handlePOSTRequest() {
            if (connectToDB()) {
                if (array_key_exists('deleteQueryRequest', $_POST)) {
                    handleDeleteRequest()();
                } else if (array_key_exists('updateQueryRequest', $_POST)) {
                    handleUpdateRequest();
                } else if (array_key_exists('insertQueryRequest', $_POST)) {
                    handleInsertRequest();
                } else if (array_key_exists('displayQueryRequest', $_POST)) {
                    handleDisplayRequest();
                }

                disconnectFromDB();
            }
        }

        if (isset($_POST['deleteSubmit']) || isset($_POST['updateSubmit']) || isset($_POST['insertSubmit']) || isset($_POST['displaySubmit'])) {
            handlePOSTRequest();
        } 
        ?>
    </body>
</html>
