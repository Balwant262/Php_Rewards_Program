<?php
	include 'includes/session.php';
	include 'includes/slugify.php';

	if(isset($_POST['edit'])){
		$id = $_POST['id'];
                $points = $_POST['points'];
                $user_id = $_POST['user_id'];
		
		$conn = $pdo->open();
                
		try{
			$stmt = $conn->prepare("UPDATE users SET points_redeemed=points_redeemed-:points, points_balance=points_balance+:points2 WHERE id=:id");
                        $stmt->execute(['points'=>$points,'points2'=>$points, 'id'=>$user_id]);
                        $stmt = $conn->prepare("UPDATE details SET status=:status WHERE id=:id");
			$stmt->execute(['status'=>"Order Cancelled", 'id'=>$id]);
                        
			$_SESSION['success'] = 'Order Cancelled Successfully';
		}
		catch(PDOException $e){
			$_SESSION['error'] = $e->getMessage();
		}
		
		$pdo->close();
	}
	else{
		$_SESSION['error'] = 'Fill up form first';
	}

	header('location: sales.php');

?>