<?php
	include 'includes/session.php';
	include 'includes/slugify.php';

	
        $id = $_POST['id'];
        $details_id = $_POST['details_id'];
        $order_status = $_POST['status'];
        
        $photo = $_FILES['status_doc']['name'];

        $conn = $pdo->open();
                
                if(!empty($photo)){
                    $ext = pathinfo($photo, PATHINFO_EXTENSION);
                    $new_filename = $photo;
                    move_uploaded_file($_FILES['status_doc']['tmp_name'], '../images/'.$new_filename);
                    $filename = $new_filename;	
                }else{
                        $filename = '';
                }
                
                
		try{
                    
                        if($order_status == 'Product Dispatched'){
                            $stmt = $conn->prepare("UPDATE details SET bill_document=:status WHERE id=:id");
                            $stmt->execute(['status'=>$filename, 'id'=>$details_id]);
                        
                            $stmt = $conn->prepare("UPDATE order_status_details SET status_doc=:status WHERE id=:id");
                            $stmt->execute(['status'=>$filename, 'id'=>$id]);
                            
                        }
                        else{
			$stmt = $conn->prepare("UPDATE order_status_details SET status_doc=:status WHERE id=:id");
			$stmt->execute(['status'=>$filename, 'id'=>$id]);
                        
                        
                        }
                        
                       
                      
                        
			$_SESSION['success'] = 'Document Updated Successfully';
		}
		catch(PDOException $e){
			$_SESSION['error'] = $e->getMessage();
                        
		}
		
		$pdo->close();
	

	header('location: sales.php');

?>