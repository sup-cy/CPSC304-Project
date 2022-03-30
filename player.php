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
                    <form method="POST" action="gameTeam.php">
                        <p><input type="submit" value="Team" name="team"></p>
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

        <h2>Insert Values into Player Table</h2>
        <form method="POST" action="player.php"> <!--refresh page when submitted-->
            <input type="hidden" id="insertQueryRequest" name="insertQueryRequest">
            Last Name: <input type="text" name="inslName"> <br /><br />
            First Name: <input type="text" name="insfName"> <br /><br />
            ID: <input type="number" name="insId"> <br /><br />
            Age: <input type="number" name="insAge"> <br /><br />
            position: <input type="text" name="insPosition"> <br /><br />
            salary : <input type="number" name="insSalary" step = "0.01"> <br /><br />
            Total Kill : <input type="number" name="insTotalKill"> <br /><br />
            Team: <input type="text" name="insTeam"> <br /><br />
            <input type="submit" value="Insert" name="insertSubmit"></p>
        </form>

        <hr />

        <h2>Update Player Info</h2>

        <form method="POST" action="player.php"> <!--refresh page when submitted-->
            <input type="hidden" id="updateQueryRequest" name="updateQueryRequest">
            ID : <input type="number" name="uId"> <br /><br />
            <label for="att">select attribute you want change:</label>
            <select name="att" id="att">
            <option value="age">age</option>
            <option value="salary">salary</option>
            <option value="totalKill">totalKill</option>
            <option value="teamName">teamName</option>
            <option value="position">position</option>
            </select>
            <br /><br />
            new value: <input type="text" name="new"> <br /><br />
            <input type="submit" value="Update" name="updateSubmit"></p>
        </form>

        <hr />

        <h2>Delete in player Table</h2>

        <form method="POST" action="player.php"> <!--refresh page when submitted-->
            <input type="hidden" id="deleteQueryRequest" name="deleteQueryRequest">
            Id: <input type="number" name="delId"> <br /><br />
            <input type="submit" value="Delete" name="deleteSubmit"></p>
        </form>

        <hr />
        <h2>Display the Tuple in Team Table</h2>
        <table style=border-spacing:50px>
            <tr>
                <td align="left">
                    <form method="POST" action="player.php"> <!--refresh page when submitted-->
                    <label for="opeartors">Team:</label>
                    <select name="operator" id="operator">
                    <option value="max">MAX</option>
                    <option value="min">MIN</option>
                    <option value="all">ALL</option>
                    </select>
                    <label for="a/k"></label>
                    <select name="ak" id="ak">
                    <option value="age">age</option>
                    <option value="salary">salary</option>
                    <option value="totalKill">totalKill</option>
                    </select>
                    <br><br>
                    <input type="hidden" id="displayQueryRequest" name="displayQueryRequest">
                    <input type="submit" value="Display" name="displaySubmit"></p>
                    </form>
                </td>
                <td align="right">
                    <form method="POST" action="player.php"> <!--refresh page when submitted-->
                    Show all player from team: <input type="text" name="dname"> <br /><br />
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
                    echo "<table>";
                    echo "<tr><th>Lname</th><th>Fname</th><th>ID</th><th>Age</th><th>position</th><th>salary</th><th>totalKill</th><th>teamName</th></tr>";

                    while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                    echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td> <td>" . $row[2] . "</td> <td>" . $row[3] . "</td><td>" . $row[4] . "</td><td>" . $row[5] . "</td><td>" . $row[6] . "</td><td>" . $row[7] . "</td></tr>";
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
        function handleUpdateRequest() {
            global $db_conn;

            $id = $_POST['uId'];
            $attribute = $_POST['att'];
            $new_value = $_POST['new'];
            $num = 0;
            if($attribute != "teamName" && $attribute != "position" ){
                $num = $new_value + 0;
                executePlainSQL("UPDATE Player SET $attribute = '".$num."'  WHERE gameId='" . $id . "'");
            }else{
                executePlainSQL("UPDATE Player SET $attribute = '".$new_value."'  WHERE gameId='" . $id . "'");
            }
            // you need the wrap the old name and new name values with single quotations
            OCICommit($db_conn);
        }
        function handleDeleteRequest() {
            global $db_conn;
            $id = $_POST['delId'];
            executePlainSQL("DELETE FROM Player WHERE gameId= '" . $id . "'");
            OCICommit($db_conn);
        }
        function handleInsertRequest() {
            global $db_conn;

            //Getting the values from user and insert data into the table
            $tuple = array (
                ":bind1" => $_POST['inslName'],
                ":bind2" => $_POST['insfName'],
                ":bind3" => $_POST['insId'],
                ":bind4" => $_POST['insAge'],
                ":bind5" => $_POST['insPosition'],
                ":bind6" => $_POST['insSalary'],
                ":bind7" => $_POST['insTotalKill'],
                ":bind8" => $_POST['insTeam']
            );

            $alltuples = array (
                $tuple
            );

            executeBoundSQL("insert into Player values (:bind1, :bind2,:bind3,:bind4,:bind5,:bind6,:bind7,:bind8)", $alltuples);
            OCICommit($db_conn);
        }
        function handleDisplayRequest() {
            global $db_conn;

            $operator = $_POST['operator'];
            $ak = $_POST['ak'];
            if($operator == "max"){
                $result = executePlainSQL("SELECT * FROM Player WHERE $ak = (SELECT MAX($ak) FROM Player) ");
            }else if($operator == "min"){
                $result = executePlainSQL("SELECT * FROM Player WHERE $ak = (SELECT Min($ak) FROM Player) ");
            }else if($operator == "all"){
               $result = executePlainSQL("SELECT * FROM Player");
            }
            printResult($result);
            
        }
        function handleDisplayTRequest() {
            global $db_conn;
            $team = $_POST['dname'];
            
            $result = executePlainSQL("SELECT * FROM Player WHERE teamName = '".$team."' ");
            printResult($result);
            
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
                }else if (array_key_exists('displayTQueryRequest', $_POST)) {
                    handleDisplayTRequest();
                }

                disconnectFromDB();
            }
        }

        if (isset($_POST['deleteSubmit']) || isset($_POST['updateSubmit']) || isset($_POST['insertSubmit']) || isset($_POST['displaySubmit'])|| isset($_POST['displayTSubmit'])) {
            handlePOSTRequest();
        } 
        ?>
    </body>
</html>
