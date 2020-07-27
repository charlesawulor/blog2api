<?php
$servername="localhost";
$username="root";
$password="";
$dbname="myblog";
$conn = new mysqli($servername, $username, $password, $dbname);
// check connection
if ($conn->connect_error)
{
die("connection failed:" . $conn->connect_error);
}
?>	
<?php
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
	if (isset($_GET['postid'])){
		$postid = $conn->real_escape_string($_GET['postid']);
		$sql=$conn->query("SELECT post_title,post_content,posted_by FROM posttbl WHERE postid='$postid'");
		$data = $sql->fetch_assoc();
	}
	else
	{
		$data = array();
		$sql=$conn->query("SELECT post_title,post_content,posted_by FROM posttbl");
		while($d=$sql->fetch_assoc())$data[]=$d;
	}
	exit(json_encode($data));
	
	
	
 } else if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['catid']) && isset($_POST['post_img'])&& isset($_POST['post_title']) && isset($_POST['post_content'])&& isset($_POST['posted_by'])) {
			$catid = $conn->real_escape_string($_POST['catid']);
			$post_img = $conn->real_escape_string($_POST['post_img']);
            $post_title = $conn->real_escape_string($_POST['post_title']);
            $post_content= $conn->real_escape_string($_POST['post_content']);
			$posted_by = $conn->real_escape_string($_POST['posted_by']);

            $conn->query("INSERT INTO posttbl (catid,post_img,post_title,post_content,posted_by,posted_date) VALUES ('$catid','$post_img','$post_title','$post_content', '$posted_by',CURRENT_TIMESTAMP)");
            exit(json_encode(array("status" => 'post added')));
        } else
            exit(json_encode(array("status" => 'failed', 'reason' => 'Check Your Inputs')));
		
 	
    } else if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
        if (!isset($_GET['postid']))
            exit(json_encode(array("status" => 'failed', 'reason' => 'Check Your search Inputs')));
             $postid = $conn->real_escape_string($_GET['postid']);
 
        $data = urldecode(file_get_contents('php://input'));
if (strpos($data, '=') !== false) {
            $allPairs = array();
            $data = explode('&', $data);
            foreach($data as $pair) {
                $pair = explode('=', $pair);
                $allPairs[$pair[0]] = $pair[1];
            }

            if (isset($allPairs['catid']) && isset($allPairs['post_img']) && isset($allPairs['post_title']) && isset($allPairs['post_content']) && isset($allPairs['posted_by'])  ) {
                $conn->query("UPDATE posttbl SET catid='".$allPairs['catid']."',post_img='".$allPairs['post_img']."',post_title='".$allPairs['post_title']."',post_content='".$allPairs['post_content']."', posted_by='".$allPairs['posted_by']."' WHERE postid='$postid'");
            } else if (isset($allPairs['catid'])) {
                $conn->query("UPDATE posttbl SET catid='".$allPairs['catid']."' WHERE postid='$postid'");
            } else if (isset($allPairs['post_img'])) 	{
                $conn->query("UPDATE posttbl SET post_img='".$allPairs['post_img']."' WHERE postid='$postid'");
            } else if (isset($allPairs['post_title'])) 	{
                $conn->query("UPDATE posttbl SET post_title='".$allPairs['post_title']."' WHERE postid='$postid'");
            } else if (isset($allPairs['post_content'])) 	{
                $conn->query("UPDATE posttbl SET post_content='".$allPairs['post_content']."' WHERE postid='$postid'");
            } else if (isset($allPairs['posted_by'])) 	{
                $conn->query("UPDATE posttbl SET posted_by='".$allPairs['posted_by']."' WHERE postid='$postid'");
            } else
                exit(json_encode(array("status" => 'failed', 'reason' => 'Check Your Inputs')));

            exit(json_encode(array("status" => 'success')));
        } else
            exit(json_encode(array("status" => 'failed', 'reason' => 'Check Your Inputs')));

        
		
    } else if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
        if (!isset($_GET['postid']))
            exit(json_encode(array("status" => 'failed', 'reason' => 'Check Your Inputs')));

        $postid = $conn->real_escape_string($_GET['postid']);
        $conn->query("DELETE FROM posttbl WHERE postid='$postid'");
        exit(json_encode(array("status" => 'success')));
    }

?>