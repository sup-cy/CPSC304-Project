<html>
    <head>
        <title>EsportGame Information Table</title>
    </head>

    <body>
        <table>
            <tr>
                <td>
                    <form method="POST" action="game_developedBy.php">
                        <p><input type="submit" value="Company" name="company"></p>
                    </form>
                </td>
                <td>
                    <form method="POST" action="gameTeam.php">
                        <p><input type="submit" value="Team" name="team"></p>
                    </form>
                </td>
                <td>
                    <form method="POST" action="venue.php">
                        <p><input type="submit" value="Venue" name="company"></p>
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
            </tr>
        </table>
        <hr />

        <h2>Insert Values into E-sport Game Table</h2>
        <form method="POST" action="esprotGame.php"> <!--refresh page when submitted-->
            <input type="hidden" id="insertQueryRequest" name="insertQueryRequest">
            Game Name: <input type="text" name="insgName"> <br /><br />
            Home Team: <input type="text" name="inshTeam"> <br /><br />
            Away Team: <input type="text" name="insaTeam"> <br /><br />
            Home Score: <input type="number" name="inshScore"> <br /><br />
            Away Score: <input type="number" name="insaScore"> <br /><br />
            Game Date(YYYY-MM-DD): <input type="text" name="insDate"> <br /><br />
            Ticket Price: <input type="number" name="insPrice" step = "0.01"> <br /><br />
            Venue Name: <input type="text" name="insvName"> <br /><br />
            Venue City: <input type="text" name="insvCity"> <br /><br />
            <input type="submit" value="Insert" name="insertSubmit"></p>
        </form>

        <hr />

        <h2>Display the Tuple in E-sport Game Table</h2>
        <table style=border-spacing:50px>
        	<tr>
        		<td align="left">
        			<form method="POST" action="esprotGame.php"> <!--refresh page when submitted-->
        			Search Game From Date(YYYY-MM-DD): <input type="text" name="dfDate"> <br /><br />
        			To Date(YYYY-MM-DD): <input type="text" name="dtDate"> <br /><br />
            		<input type="hidden" id="displayQueryRequest" name="displayQueryRequest">
            		<input type="submit" value="Display" name="displaySubmit"></p>
        			</form>
    			</td>
    			<td align="right">
        			<form method="POST" action="esprotGame.php"> <!--refresh page when submitted-->
        			</select>
                    <label for="t/a">Show game for</label>
                    <select name="ta" id="ta">
                    <option value="team">team</option>
                    <option value="all">all</option>
                    </select>
                    <br /><br />
                    Team Name(please ignore this if you choose all): <input type="text" name="dteam"> <br /><br />
                 	<input type="hidden" id="displayTQueryRequest" name="displayTQueryRequest">
            		<input type="submit" value="Display" name="displayTSubmit"></p>
        			</form>
    			</td>
			</tr>
		</table>

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
        function printResult($result) { //prints results from a select statement

            echo "<table style=border-spacing:25px>";
            echo "<tr><th>game Name</th><th>home Team</th><th>Away Team</th><th>Home Score</th><th>Away Score</th><th>Game Date</th><th>Ticket Price</th><th>Venue</th><th>City</th></tr>";

            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td> <td>" . $row[2] . "</td> <td>" . $row[3] . "</td> <td>" . $row[4] . "</td><td>" . $row[5] . "</td><td>" . $row[6] . "</td><td>" . $row[7] . "</td><td>" . $row[8] . "</td></tr>";
                //or just use "echo $row[0]"
            }

            echo "</table>";
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

        function handleInsertRequest() {
            global $db_conn;

            //Getting the values from user and insert data into the table
            $tuple = array (
                ":bind1" => $_POST['insgName'],
                ":bind2" => $_POST['inshTeam'],
                ":bind3" => $_POST['insaTeam'],
                ":bind4" => $_POST['inshScore'],
                ":bind5" => $_POST['insaScore'],
                ":bind6" => $_POST['insDate'],
                ":bind7" => $_POST['insPrice'],
                ":bind8" => $_POST['insvName'],
                ":bind9" => $_POST['insvCity']
            );

            $alltuples = array (
                $tuple
            );

            executeBoundSQL("insert into EsprotGame_host_playAt_plays values (:bind1, :bind2,:bind3,:bind4,:bind5,to_date(:bind6,'YYYY-MM-DD'),:bind7,:bind8,:bind9)", $alltuples);
            OCICommit($db_conn);
        }
        function handleDisplayRequest() {
            global $db_conn;
            $begin = $_POST['dfDate'];
            $end = $_POST['dtDate'];
            $result = executePlainSQL("SELECT * FROM EsprotGame_host_playAt_plays WHERE gamedate >= to_date('".$begin."' ,'YYYY-MM-DD') AND gamedate <= to_date('".$end."','YYYY-MM-DD')");
            printResult($result);
            
        }
        function handleTDisplayRequest() {
            global $db_conn;
            $ta = $_POST['ta'];
            $team = $_POST['dteam'];


            if($ta == "team"){
                $result = executePlainSQL("SELECT * FROM EsprotGame_host_playAt_plays WHERE homeTeam = '" . $team . "' OR awayTeam = '" . $team . "'");
            }else if($ta == "all"){
                $result = executePlainSQL("SELECT * FROM EsprotGame_host_playAt_plays");
            }
            
            printResult($result);
            
        }
        function handlePOSTRequest() {
            if (connectToDB()) {
                if (array_key_exists('insertQueryRequest', $_POST)) {
                    handleInsertRequest();
                } else if (array_key_exists('displayQueryRequest', $_POST)) {
                    handleDisplayRequest();
                }else if (array_key_exists('displayTQueryRequest', $_POST)) {
                    handleTDisplayRequest();
                }

                disconnectFromDB();
            }
        }

        if (isset($_POST['insertSubmit']) || isset($_POST['displaySubmit'])|| isset($_POST['displayTSubmit'])) {
            handlePOSTRequest();
        } 
        ?>
    </body>
</html>
