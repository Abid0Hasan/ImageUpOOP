<?php include 'inc/header.php';?>


<?php
include 'lib/config.php';
include 'lib/Database.php';

  $db = new Database();

?>


 <div class="myform">
 <?php
   //Ready image for uploading
   if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $permited  = array('jpg', 'jpeg', 'png', 'gif');
    $file_name = $_FILES['image']['name'];
    $file_size = $_FILES['image']['size'];
    $file_temp = $_FILES['image']['tmp_name'];

    //Generate unique image name
    $div = explode('.', $file_name);  //Division
    $file_ext = strtolower(end($div)); //Extension
    $unique_image = substr(md5(time()), 0, 10).'.'.$file_ext; //Unique name generate
    $uploaded_image = "uploads/".$unique_image; //File upload

     //Image validation
    if (empty($file_name)) {
     echo "<span class='error'>Please Select any Image !</span>";
    }elseif ($file_size >1048567) {
     echo "<span class='error'>Image Size should be less then 1MB!
     </span>";
    } elseif (in_array($file_ext, $permited) === false) {
     echo "<span class='error'>You can upload only:-"
     .implode(', ', $permited)."</span>";
    } else{
      //Store image into folder.
    move_uploaded_file($file_temp, $uploaded_image);
      //Store image into database.
    $query = "INSERT INTO tbl_image(image) 
    VALUES('$uploaded_image')";
    $inserted_rows = $db->insert($query);
    if ($inserted_rows) {
     echo "<span class='success'>Image Inserted Successfully.
     </span>";
    }else {
     echo "<span class='error'>Image Not Inserted !</span>";
    }
    }
   }
  ?>


  <form action="" method="post" enctype="multipart/form-data">
   <table>
    <tr>
     <td>Select Image</td>
     <td><input type="file" name="image"/></td>
    </tr>
    <tr>
     <td></td>
     <td><input type="submit" name="submit" value="Upload"/></td>
    </tr>
   </table>
  </form>
  
  <table width="100%">
  <tr>
  <th width="30%">No.</th>
  <th width="40%">Image</th>
  <th width="30%">Action</th>
  </tr>

   
   <?php
   // Delete Functionality

      if(isset($_GET['del'])){
          $id = $_GET['del'];

       //Delete from Folder / unlink image
       $getQuery = "SELECT * FROM tbl_image WHERE id='$id'";
       $getImg = $db->select($getQuery);
       if($getImg){
         while($imgData = $getImg->fetch_assoc()){
           $delImg = $imgData['image'];
           unlink($delImg);
         }
       }
      
       //Delete From Database
        $query = "DELETE FROM tbl_image WHERE id='$id'";
        $delImage = $db->delete($query);
        if($delImage){
          echo "<span class='success'>Image Deleted Successfully.</span>";
        }else{
          echo "<span class='error'>Image Not Deleted !</span>";
        }
   
  }
   
   ?>


  <?php
  //Display Image functionality.
  $query = "SELECT * FROM tbl_image";
  $getImage = $db->select($query);
  if($getImage){
      $i = 0;
      while($result = $getImage->fetch_assoc()){
      $i++;
  ?>
 
  <tr>
    <td><?php echo $i; ?></td>
    <td><img src="<?php echo $result['image']; ?>" height="40px" 
      width="50px"/></td>
    <td><a href="?del=<?php echo $result['id']; ?>">Delete</a></td>
   </tr>
   <?php } }?>
   </table>
  
 </div>
<?php include 'inc/footer.php';?>
