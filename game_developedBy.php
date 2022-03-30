<html>
    <head>
        <title>Company-Game Information Table</title>
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
            </tr>
        </table>
        <hr />
	<table>
		<tr>
			<td align="left">
        		<h2>Insert Values into Company / Game Table</h2>
        		<form method="POST" action="game_developedBy.php"> <!--refresh page when submitted-->
            	<input type="hidden" id="insertQueryRequest" name="insertQueryRequest">
            	compamy Name: <input type="text" name="inscName"> <br /><br />
            	founder First Name: <input type="text" name="insfName"> <br /><br />
            	founder Last Name: <input type="text" name="inslName"> <br /><br />
            	address: <input type="text" name="insAddress"> <br /><br />
            	City: <input type="text" name="insCity"> <br /><br />
            <input type="submit" value="Insert" name="insertSubmit"></p>
        	</form>
        </td>
        <td >
        		<br></br>
        		<form method="POST" action="game_developedBy.php"> <!--refresh page when submitted-->
            	<input type="hidden" id="insertGQueryRequest" name="insertGQueryRequest">
            	price: <input type="number" name="insPrice"> <br /><br />
            	rating: <input type="number" name="insRate" step="0.01"> <br /><br />
            	company Name: <input type="text" name="insgcName"> <br /><br />
            	release date(yyyy-mm-dd): <input type="text" name="insDate"> <br /><br />
            	game Name: <input type="text" name="insGname"> <br /><br />

            <input type="submit" value="Insert" name="insertGSubmit"></p>
        	</form>
        </td>
    </tr>
</table>


        <hr />

        <h2>Update Price/Rate in Game Table</h2>

        <form method="POST" action="game_developedBy.php"> <!--refresh page when submitted-->
            <input type="hidden" id="updateQueryRequest" name="updateQueryRequest">
            Game Name: <input type="text" name="uname"> <br /><br />
            <label for="up/r">select attribute you want change:</label>
            <select name="upr" id="upr">
            <option value="price">price</option>
            <option value="rating">rating</option>
            </select>
            <br /><br />
            new price/rating: <input type="number" name="new" step="0.01"> <br /><br />
            <input type="submit" value="Update" name="updateSubmit"></p>
        </form>

        <hr />
<table>
	<tr>
		<td align="left">
        	<h2>Delete in Company/Game Table</h2>

        	<form method="POST" action="game_developedBy.php"> <!--refresh page when submitted-->
            	<input type="hidden" id="deleteQueryRequest" name="deleteQueryRequest">
            	company Name: <input type="text" name="delcName"> <br /><br />
            	<input type="submit" value="Delete" name="deleteSubmit"></p>
        	</form>
    	</td>
    	<td align="right">
    		<br></br>
        	<form method="POST" action="game_developedBy.php"> <!--refresh page when submitted-->
            	<input type="hidden" id="deleteGQueryRequest" name="deleteGQueryRequest">
            	Game Name: <input type="text" name="delName"> <br /><br />
            	<input type="submit" value="Delete" name="deleteGSubmit"></p>
        	</form>
    	</td>
	</tr>
</table>
        <hr />
        <h2>Display the Tuple in Game Table</h2>
        <form method="POST" action="game_developedBy.php"> <!--refresh page when submitted-->
        	<label for="p/r">select:</label>
            <select name="pr" id="pr">
            <option value="price">price</option>
            <option value="rating">rating</option>
            </select>
            <label for="opeartors"></label>
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
            <input type="number" name="disCapacity" value = "0" step = "0.01">
            <br><br>
            <br>select information you want<br>
            <input type="checkbox" name="info[]" value="GameCompany.companyName" />Company Name<br />
			<input type="checkbox" name="info[]" value="gameName" />Game Name<br />
			<input type="checkbox" name="info[]" value="price" />Price<br />
			<input type="checkbox" name="info[]" value="rating" />Rating<br />
			<input type="checkbox" name="info[]" value="founderFn" />founder First Name<br />
			<input type="checkbox" name="info[]" value="founderLn" />founder Last Name<br />
			<input type="checkbox" name="info[]" value="hgeadquartersAddress" />Company Address<br />
			<input type="checkbox" name="info[]" value="headquartersCity" />Company City<br />
			<input type="checkbox" name="info[]" value="releaseDate" />Release Date<br />
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
            $infom = $_POST['info'];
            $select ="";
            $N = count($infom);
            echo "<table style=border-spacing:20px>";
            echo "<tr>";
            	for($i=0; $i < $N; $i++)
    				{
      					echo("<td><strong>".$infom[$i]."</strong></td>");
    				}
    		echo "</tr>";
            	while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
            		echo "<tr>";
            		for($y=0; $y < $N; $y++)
    				{
      					echo("<td>".$row[$y]."</td>");
    				}
    				echo "</tr>";
                //echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td> <td>" . $row[2] . "</td> <td>" . $row[3] . "</td> </tr>";
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
            $priR = $_POST['upr'];
            $game_name = $_POST['uname'];
            $new_num = $_POST['new'];


            // you need the wrap the old name and new name values with single quotations
            if($priR == "price"){
            	executePlainSQL("UPDATE Game_DevelopedBy SET price='" . $new_num . "' WHERE gameName='" . $game_name . "'");
            }else if ($priR == "rating"){
            	executePlainSQL("UPDATE Game_DevelopedBy SET rating='" . $new_num . "' WHERE gameName='" . $game_name . "'");
            }
            OCICommit($db_conn);
        }
        function handleDeleteRequest() {
            global $db_conn;
            $name = $_POST['delcName'];
            executePlainSQL("DELETE FROM GameCompany WHERE companyName= '" . $name . "'");
            OCICommit($db_conn);
        }
        function handledeleteGRequest() {
            global $db_conn;
            $name = $_POST['delName'];
            executePlainSQL("DELETE FROM Game_DevelopedBy WHERE gameName= '" . $name . "'");
            OCICommit($db_conn);
        }
        function handleInsertRequest() {
            global $db_conn;

            //Getting the values from user and insert data into the table
            $tuple = array (
                ":bind1" => $_POST['inscName'],
                ":bind2" => $_POST['insfName'],
                ":bind3" => $_POST['inslName'],
                ":bind4" => $_POST['insAddress'],
                ":bind5" => $_POST['insCity'],
            );

            $alltuples = array (
                $tuple
            );

            executeBoundSQL("insert into GameCompany values (:bind1, :bind2,:bind3,:bind4,:bind5)", $alltuples);
            OCICommit($db_conn);
        }
        function handleInsertGRequest() {
            global $db_conn;

            //Getting the values from user and insert data into the table
            $tuple = array (
                ":bind1" => $_POST['insPrice'],
                ":bind2" => $_POST['insRate'],
                ":bind3" => $_POST['insgcName'],
                ":bind4" => $_POST['insDate'],
                ":bind5" => $_POST['insGname']
            );

            $alltuples = array (
                $tuple
            );

            executeBoundSQL("insert into Game_DevelopedBy values (:bind1, :bind2,:bind3,to_date(:bind4,'YYYY-MM-DD'),:bind5)", $alltuples);
            OCICommit($db_conn);
        }
        function handleDisplayRequest() {
            global $db_conn;
            $operator = $_POST['operator'];
            $pr = $_POST['pr'];
            $num = $_POST['disCapacity'];
            $select ="";
            $infom = $_POST['info'];
            $N = count($infom);
            for($i=0; $i < $N; $i++)
    		{
      			$select .= ",$infom[$i]";
      				
    		}
    		$select = substr($select,1);

    		if($operator == "greater"){
                $result = executePlainSQL("SELECT $select FROM Game_DevelopedBy,GameCompany WHERE $pr > '" . $num . "' AND Game_DevelopedBy.companyName = GameCompany.companyName");
            }else if($operator == "less"){
                $result = executePlainSQL("SELECT $select FROM Game_DevelopedBy , GameCompany WHERE $pr < '" . $num . "' AND Game_DevelopedBy.companyName = GameCompany.companyName");
            }else if($operator == "greaterE"){
                $result = executePlainSQL("SELECT $select FROM Game_DevelopedBy ,GameCompany WHERE $pr >= '" . $num . "' AND Game_DevelopedBy.companyName = GameCompany.companyName");
            }else if($operator == "lessE"){
                $result = executePlainSQL("SELECT $select FROM Game_DevelopedBy ,GameCompany WHERE $pr <= '" . $num . "' AND Game_DevelopedBy.companyName = GameCompany.companyName");
            }else if($operator == "E"){
                $result = executePlainSQL("SELECT $select FROM Game_DevelopedBy,GameCompany WHERE 
                    $pr = '" . $num . "' AND Game_DevelopedBy.companyName = GameCompany.companyName");
            }else if($operator == "notE"){
                $result = executePlainSQL("SELECT $select FROM Game_DevelopedBy, GameCompany WHERE $pr <> '" . $num . "' AND Game_DevelopedBy.companyName = GameCompany.companyName");
            }else if($operator == "allTurble"){
                $result = executePlainSQL("SELECT $select FROM Game_DevelopedBy, GameCompany WHERE Game_DevelopedBy.companyName = GameCompany.companyName ");
            }
            printResult($result);
        }
        function handlePOSTRequest() {
            if (connectToDB()) {
                if (array_key_exists('deleteQueryRequest', $_POST)) {
                    handleDeleteRequest();
                } else if (array_key_exists('updateQueryRequest', $_POST)) {
                    handleUpdateRequest();
                } else if (array_key_exists('insertQueryRequest', $_POST)) {
                    handleInsertRequest();
                } else if (array_key_exists('displayQueryRequest', $_POST)) {
                    handleDisplayRequest();
                }else if (array_key_exists('deleteGQueryRequest', $_POST)) {
                    handleDeleteGRequest();
                }else if (array_key_exists('insertGQueryRequest', $_POST)) {
                    handleInsertGRequest();
                }
                disconnectFromDB();
            }
        }

        if (isset($_POST['deleteSubmit']) || isset($_POST['updateSubmit']) || isset($_POST['insertSubmit']) || isset($_POST['displaySubmit']) || isset($_POST['deleteGSubmit']) ||isset($_POST['insertGSubmit'])) {
            handlePOSTRequest();
        } 
        ?>
    </body>
</html>
