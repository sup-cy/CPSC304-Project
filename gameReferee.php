<html>
    <head>
        <title>Referee Information Table</title>
    </head>

    <body>
        <table>
            <tr>
            	<td>
					<form method="POST" action="gameTeam.php">
                        <p><input type="submit" value="Team" name="team"></p>
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


        <table>
		<tr>
			<td align="left">
        		<h2>Insert Values into Referee / Referee_Records Table</h2>
        		<form method="POST" action="gameReferee.php"> <!--refresh page when submitted-->
            	<input type="hidden" id="insertQueryRequest" name="insertQueryRequest">
                refereeID: <input type="text" name="insrefereeID"> <br /><br />
                firstName: <input type="text" name="insfirstName"> <br /><br />
                lastName : <input type="text" name="inslastName"> <br /><br />
                workYears : <input type="number" name="insworkYears" step="1"> <br /><br />
                salary : <input type="number" name="inssalary" size = "1"> <br /><br />
            <input type="submit" value="Insert" name="insertSubmit"></p>
        	</form>
        </td>
        <td >
        		<br></br>
        		<form method="POST" action="gameReferee.php"> <!--refresh page when submitted-->
            	<input type="hidden" id="insertRecordsQueryRequest" name="insertRecordsQueryRequest">
            	refereeID: <input type="text" name="insrefereeID"> <br /><br />
            	homeTeam: <input type="text" name="inshomeTeam"> <br /><br />
            	awayTeam: <input type="text" name="insawayTeam"> <br /><br />
            	gamedate(yyyy-mm-dd): <input type="text" name="insDate"> <br /><br />

            <input type="submit" value="Insert" name="insertQuerySubmit"></p>
        	</form>
        </td>
        </tr>
    </table>

        <hr />
        <h2>Update workYears/salary in Referee Table</h2>

        <form method="POST" action="gameReferee.php"> <!--refresh page when submitted-->
            <input type="hidden" id="updateQueryRequest" name="updateQueryRequest">
            refereeID: <input type="text" name="uid"> <br /><br />
            <label for="up/r">select attribute you want change:</label>
            <select name="upr" id="upr">
            <option value="workYears">workYears</option>
            <option value="salary">salary</option>
            </select>
            <br /><br />
            new workYears/salary: <input type="number" name="new" step="1"> <br /><br />
            <input type="submit" value="Update" name="updateSubmit"></p>
        </form>



        <hr />

        <h2>Delete in Referee Table</h2>

        <form method="POST" action="gameReferee.php"> <!--refresh page when submitted-->
            <input type="hidden" id="deleteQueryRequest" name="deleteQueryRequest">
            refereeID: <input type="text" name="delID"> <br /><br />
            <input type="submit" value="Delete" name="deleteSubmit"></p>
        </form>

        <hr />
        <h2>Display the Tuple in Referee Table</h2>
        <form method="POST" action="gameReferee.php"> <!--refresh page when submitted-->
            <label for="opeartors">Search salary/workYears:</label>
            <select name="operator" id="operator">
            <option value="ave">AVE</option>
            <option value="max">MAX</option>
            <option value="min">MIN</option>
            <option value="all">ALL</option>
            </select>
            <label for="a/k"></label>
            <select name="ak" id="ak">
            <option value="workYears">workYears</option>
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

            $id = $_POST['uid'];
            $upr = $_POST['upr'];
            $new = $_POST['new'];
            if ($upr == "salary") {
                executePlainSQL("UPDATE GameReferee SET salary = '" . $new . "' WHERE refereeID='" . $id . "' ");
            }else if ($upr == "workYears") {
                executePlainSQL("UPDATE GameReferee SET workYears ='" . $new . "' WHERE refereeID='" . $id . "' ");
            }
            OCICommit($db_conn);
        }
        function handleDeleteRequest() {
            global $db_conn;
            $id = $_POST['delID'];
            executePlainSQL("DELETE FROM GameReferee WHERE refereeID= '" . $id . "' ");
            OCICommit($db_conn);
        }
        function handleInsertRequest() {
            global $db_conn;

            //Getting the values from user and insert data into the table
            $tuple = array (
                ":bind1" => $_POST['insrefereeID'],
                ":bind2" => $_POST['insfirstName'],
                ":bind3" => $_POST['inslastName'],
                ":bind4" => $_POST['insworkYears'],
                ":bind5" => $_POST['inssalary'],
            );

            $alltuples = array (
                $tuple
            );

            executeBoundSQL("insert into GameReferee values (:bind1, :bind2, :bind3, :bind4, :bind5)", $alltuples);
            OCICommit($db_conn);
        }
        function handleDisplayRequest() {
            global $db_conn;
            $operator = $_POST['operator'];
            $ak = $_POST['ak'];
            $case = Null;
            if($operator == "ave"){
            	$case = 0;
                $result = executePlainSQL("SELECT * FROM GameReferee WHERE $ak = (SELECT AVG($ak) FROM GameReferee)");
            }else if($operator == "max"){
            	$case = 2;
                $result = executePlainSQL("SELECT * FROM GameReferee WHERE $ak = (SELECT MAX($ak) FROM GameReferee)");
            }else if($operator == "min"){
            	$case = 1;
                $result = executePlainSQL("SELECT * FROM GameReferee WHERE $ak = (SELECT MIN($ak) FROM GameReferee)");
            }else if($operator == "all"){
            	$case = 3;
                $result = executePlainSQL("SELECT * FROM GameReferee");
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
                }else if (array_key_exists('insertRecordsQueryRequest', $_POST)) {
                    handleInsertGRequest();
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
