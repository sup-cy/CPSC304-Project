<html>
    <head>
        <title>Venue Information Table</title>
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

        <h2>Insert Values into Venue Table</h2>
        <form method="POST" action="venue.php"> <!--refresh page when submitted-->
            <input type="hidden" id="insertQueryRequest" name="insertQueryRequest">
            Name: <input type="text" name="insName"> <br /><br />
            City: <input type="text" name="insCity"> <br /><br />
            address: <input type="text" name="insAddress"> <br /><br />
            capacity: <input type="number" name="insCapacity"> <br /><br />

            <input type="submit" value="Insert" name="insertSubmit"></p>
        </form>

        <hr />

        <h2>Update Name in Venue Table</h2>

        <form method="POST" action="venue.php"> <!--refresh page when submitted-->
            <input type="hidden" id="updateQueryRequest" name="updateQueryRequest">
            Old Name: <input type="text" name="oldName"> <br /><br />
            New Name: <input type="text" name="newName"> <br /><br />

            <input type="submit" value="Update" name="updateSubmit"></p>
        </form>

        <hr />

        <h2>Delete in Venue Table</h2>

        <form method="POST" action="venue.php"> <!--refresh page when submitted-->
            <input type="hidden" id="deleteQueryRequest" name="deleteQueryRequest">
            Name: <input type="text" name="delName"> <br /><br />
            City: <input type="text" name="delCity"> <br /><br />
            <input type="submit" value="Delete" name="deleteSubmit"></p>
        </form>

        <hr />
        <h2>Display the Tuple in venue Table</h2>
        <form method="POST" action="venue.php"> <!--refresh page when submitted-->
            <label for="opeartors">Search capacity:</label>
            <select name="operator" id="operator">
            <option value="">---please select an operator---</option>
            <option value="greater">></option>
            <option value="less"><</option>
            <option value="E">=</option>
            <option value="greaterE">>=</option>
            <option value="lessE"><=</option>
            <option value="notE">!=</option>
            <option value="allTurble">all</option>
            </select>
            <input type="number" name="disCapacity" value = "0"> 
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
        function printResult($result) { //prints results from a select statement

            echo "<table>";
            echo "<tr><th>Name</th><th>City</th><th>Address</th><th>capacity</th></tr>";

            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td> <td>" . $row[2] . "</td> <td>" . $row[3] . "</td> </tr>";
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

            $old_name = $_POST['oldName'];
            $new_name = $_POST['newName'];

            // you need the wrap the old name and new name values with single quotations
            executePlainSQL("UPDATE Venue SET venueName='" . $new_name . "' WHERE venueName='" . $old_name . "'");
            OCICommit($db_conn);
        }
        function handleDeleteRequest() {
            global $db_conn;
            $name = $_POST['delName'];
            $city = $_POST['delCity'];
            executePlainSQL("DELETE FROM Venue WHERE venueName= '" . $name . "' AND venueCity = '".$city."'");
            OCICommit($db_conn);
        }
        function handleInsertRequest() {
            global $db_conn;

            //Getting the values from user and insert data into the table
            $tuple = array (
                ":bind1" => $_POST['insName'],
                ":bind2" => $_POST['insCity'],
                ":bind3" => $_POST['insAddress'],
                ":bind4" => $_POST['insCapacity']
            );

            $alltuples = array (
                $tuple
            );

            executeBoundSQL("insert into Venue values (:bind1, :bind2,:bind3,:bind4)", $alltuples);
            OCICommit($db_conn);
        }
        function handleDisplayRequest() {
            global $db_conn;
            $operator = $_POST['operator'];
            $capacity = $_POST['disCapacity'];


            if($operator == "greater"){
                $result = executePlainSQL("SELECT * FROM Venue WHERE capacity > '" . $capacity . "'");
            }else if($operator == "less"){
                $result = executePlainSQL("SELECT * FROM Venue WHERE capacity < '" . $capacity . "'");
            }else if($operator == "greaterE"){
                $result = executePlainSQL("SELECT * FROM Venue WHERE capacity >= '" . $capacity . "'");
            }else if($operator == "lessE"){
                $result = executePlainSQL("SELECT * FROM Venue WHERE capacity <= '" . $capacity . "'");
            }else if($operator == "E"){
                $result = executePlainSQL("SELECT * FROM Venue WHERE capacity = '" . $capacity . "'");
            }else if($operator == "notE"){
                $result = executePlainSQL("SELECT * FROM Venue WHERE capacity <> '" . $capacity . "'");
            }else if($operator == "allTurble"){
                $result = executePlainSQL("SELECT * FROM Venue");
            }
            
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
