<?php include 'inc/header.php'; ?>
<?php include 'inc/admin-sidebar.php'; ?>
        <div class="grid_10">
            <div class="box round first grid">
                <h2>Edit Post</h2>
                <?php
                 $id = $_GET['editId']; 
                ?>    


                <?php

            if(isset($_POST['submit'])){ 
                if(isset($_FILES['image'])){
                    $error = array();
                    $file_name = $_FILES['image']['name'];
                    $file_size = $_FILES['image']['size'];
                    $file_tmp = $_FILES['image']['tmp_name'];
                    $file_type = $_FILES['image']['type'];
                    $file_ext = explode('.',$file_name);
                    $fileExtension = end($file_ext);
                    $extensions = array("jpeg","jpg","png");
                    if(in_array($fileExtension,$extensions) === false)
                    {
                    $errors[] = "This extension file not allowed, Please choose a JPG or PNG file.";
                    }
                
                    if($file_size > 2097152){
                    $errors[] = "File size must be 2mb or lower.";
                    }
                    $newName = time()."-".basename($file_name);
                    $target = "upload/".$newName;
                    
                
                }

                $title = mysqli_real_escape_string($db->link,$_POST['title']);
                
                if(isset($_POST['category_name'])){
                    $category = mysqli_real_escape_string($db->link,$_POST['category_name']);
                }
                if(isset($_POST['image'])){
                    $image = mysqli_real_escape_string($db->link,$_POST['image']);
                }
                if(isset($_POST['body'])){
                    $body = mysqli_real_escape_string($db->link,$_POST['body']);
                }
                if(isset($_POST['tags'])){
                    $tags = mysqli_real_escape_string($db->link,$_POST['tags']);
                }              
                

                if($title == "" || $category == "" ||  $body == "" || $tags == ""){
                    echo "<span style='color:red;'>Field must not be empty</span>";
                }else{

                    if(!empty($file_name)){
                        $query = "UPDATE tbl_post   
                                  SET
                                  title = '$title',
                                  cat = '$category',
                                  body = '$body',
                                  image = '$newName',
                                  author = '$author',
                                  tags = '$tags'
                                  WHERE id = '$id';
                                  ";

                        $db->insert($query);
                        if(empty($errors) == true){
                            move_uploaded_file($file_tmp,$target);
                        }else{
                            print_r($errors);
                            die();
                        }
                        echo "<span style='color:green;'>Post Update Successfully</span>";
                    }else{
                        $query = "UPDATE tbl_post
                                  SET
                                  title = '$title',
                                  cat = '$category',
                                  body = '$body',
                                  author = '$author',
                                  tags = '$tags'
                                  WHERE id = '$id';
                                  ";
                    }
                }
            }
                
                
                ?>

                <div class="block">               
                 <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST" enctype="multipart/form-data">
                 <?php 
                 echo $query = "SELECT * FROM tbl_post WHERE id ='$id' ";
                 $post = $db->select($query);
                 if($post){
                     while($postResult = $post->fetch_assoc()){
                 ?>
                    <table class="form">
                       
                        <tr>
                            <td>
                                <label>Title</label>
                            </td>
                            <td>
                                <input type="text" name="title" value="<?php echo $postResult['title']; ?>" class="medium" />
                            </td>
                        </tr>
                     
                        <tr>
                            <td>
                                <label>Category</label>
                            </td>
                            <td>
                                <select id="select" name="category_name">
                                <option  disabled selected value>SELECT CATEGORY</option>
                                <?php 
                                $query = "SELECT * FROM tbl_category";
                                $category_name = $db->select($query);
                                if($category_name){
                                    while($result = $category_name->fetch_assoc()){
                                        if($postResult['cat'] == $result['cid']){
                                            $selected = "selected";
                                        }else{
                                            $selected = "";
                                        }
                                        echo '<option '.$selected.' value="'.$result['cid'].'">'.$result['name'].'</option>';
                                    }
                                }
                                ?>
                                    
                                </select>
                            </td>
                        </tr>
                   
                    
                        <!-- <tr>
                            <td>
                                <label>Date Picker</label>
                            </td>
                            <td>
                                <input type="text" id="date-picker" />
                            </td>
                        </tr> -->
                        <tr>
                            <td>
                                <label>Upload Image</label>
                            </td>
                            <td>
                                <img style="width:90px" src="upload/<?php echo $postResult['image']; ?>" alt=""><br>
                                <input type="file"  name="image"/>
                            </td>
                        </tr>
                        <tr>
                            <td style="vertical-align: top; padding-top: 9px;">
                                <label>Content</label>
                            </td>
                            <td>
                                <textarea class="tinymce" name="body"><?php echo $postResult['body']; ?></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label>Tags</label>
                            </td>
                            <td>
                                <input type="text" name="tags" value="<?php echo $postResult['tags']; ?>" class="medium" />
                            </td>
                        </tr>
                        <!-- <tr>
                            <td>
                                <label>Author</label>
                            </td>
                            <td>
                                <input type="text" name="author" placeholder="Enter author name" class="medium" />
                            </td>
                        </tr> -->
						<tr>
                            <td></td>
                            <td>
                                <input type="submit" name="submit" Value="Save" />
                            </td>
                        </tr>
                    </table>
                    <?php
                        }
                    }
                    ?>
                    </form>
                </div>
            </div>
        </div>
        <div class="clear">
        </div>
    </div>
    <!-- Load TinyMCE -->
    <script src="js/tiny-mce/jquery.tinymce.js" type="text/javascript"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            setupTinyMCE();
            setDatePicker('date-picker');
            $('input[type="checkbox"]').fancybutton();
            $('input[type="radio"]').fancybutton();
        });
    </script>
    <?php include 'inc/footer.php'; ?>
