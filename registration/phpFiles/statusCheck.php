<?php
/*****************************************************************
 *This file is used to display the status info of both CNC's     *
 *This is manually made so there is definetely an option of      *
 *Making this more optomized. Even making a function could work  *
 *                                                               *
 *Created By: Elijsha Baetiong                                   *
 *Last edited:3/16/20                                            *
 *****************************************************************/

/*first we create the table that we need */
echo "<div class='machineInfo'><table><tr><th></th><th>Machine Status</th></tr><tr> <td>CNC1: </td>";
	//connect to the database
	$link = mysqli_connect("localhost","webhost","pass","Demo_OEE");
	if($link){
		//grab data from completeData
		$result = mysqli_query($link, "select status FROM completeData where machineID=1 and curdate()=date(timestamp) and extract(hour from now())=extract(hour from timestamp) and (extract(minute from now())-2)<=extract(minute from timestamp) order by timestamp desc limit 1");
		
		$answer=mysqli_fetch_array($result);
		//Now we check what status says
		//then create the proper td
		if(mysqli_num_rows($result)==0 or $answer['status']=="" or $answer['status']==null){
			echo '<td bgcolor="red" style="border:2px solid black">Not currently operational</td></tr>';
		}
		else{

			if($answer['status']=="IDLE"){
				echo '<td bgcolor="yellow" style="border:2px solid black">Machine Idle</td></tr>';
			}
			else{
				echo '<td bgcolor="green" style="border:2px solid black">In operation</td></tr>';
			}
		}
		//the rest of this is repeative of what happened above
		echo "<tr><td>CNC 2: </td>";	
	$result = mysqli_query($link, "select * FROM cncLathe where curdate()=date(timestamp) and extract(hour from now())=extract(hour from timestamp) and (extract(minute from now())-2)<=extract(minute from timestamp) order by timestamp desc limit 1");
		
		$answer=mysqli_fetch_array($result);
		if(mysqli_num_rows($result)==0 or $answer['status']=="" or $answer['status']==null){
			echo '<td bgcolor="red" style="border:2px solid black">Not currently operational</td></tr>';
		}
		else{
			if($answer['status']=="IDLE"){
				echo '<td bgcolor="yellow" style="border:2px solid black">Machine Idle</td></tr>';
			}
			else{
				echo '<td bgcolor="green" style="border:2px solid black">In operation</td></tr>';
			}
		}
	}
	else{
		echo "communication error: ".mysqli_error();
	}

echo "</table></div>";
?>
