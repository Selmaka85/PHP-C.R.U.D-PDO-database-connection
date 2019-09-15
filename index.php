<style>
    @import url('https://stackpath.bootstrapcdn.com/bootswatch/4.3.1/cerulean/bootstrap.min.css');
    body{
        max-width:85%;
        margin:auto;
        background:gray;
    }
    h4{
        color:white;
        padding:5px;
        border-radius:10px;
        margin-top:10px;
        border:2px chartreuse solid;
    }
   
    p{
        background:linear-gradient(to right,rgba(0,0,0,0.9),rgba(0,0,0,0.6));
        color:white;
        border-radius:10px;
        padding:10px;
    }
    h6{
        padding:5px;
        border-radius:10px;
        border:2px solid black;
        background-attachment: fixed;
        margin:auto;
    }
    small{
        color:white;
    }
    .btn{
        margin:3px;
    }
</style>
<?php
require_once('classes/Database.php');

$database= new Database();

$post=filter_input_array(INPUT_POST,FILTER_SANITIZE_STRING);

if(isset($post['submit'])){
    $title=$post['title'];
    $body=$post['body'];
    $id=$post['id'];
    $database->query("INSERT INTO posts (title,body) VALUES (:title,:body)");
    
    $database->bind(":title",$title);
    $database->bind(":body",$body);
    $database->execute();
    
    if($database->lastInsertId()){
        echo "<p>Post Added!</p>";
    }
}


if(isset($post['update'])){
    $id=$post['id'];
    $title=$post['title'];
    $body=$post['body'];
    $database->query("UPDATE posts SET title=:title, body=:body WHERE id=:id");
    
    $database->bind(":title",$title);
    $database->bind(":body",$body);
    $database->bind(":id",$id);
    $database->execute();
}


if(isset($_POST['delete'])){
    $delete_id=$_POST['delete_id'];
    $database->query("DELETE FROM posts WHERE id=:delete_id");
    $database->bind(":delete_id",$delete_id);
    $database->execute();  
}

$database-> query('SELECT * FROM posts');
$rows=$database->resultset();
?>
 
  <h4 class='text-center bg-primary'>Add Post!</h4> 
  <form class="form-group" action="<?php $_SERVER['PHP_SELF'];?>" method="post">
       <input class="form-control col-md-3" type="number" placeholder="Specify ID..." name="id"><br>
      <input class="form-control col-md-3" type="text" placeholder="Add title..." name="title"><br>
      <textarea class="form-control col-md-3" type="text" placeholder="Type your message..." name="body" rows="2" cols="30"></textarea>
      <button class="form-control col-md-3 btn btn-info" type="submit" name="submit">Submit</button><br>
      <button class="form-control col-md-3 btn btn-warning" type="submit" name="update">Update</button><br>
  </form>
  
  <h4 class='text-center bg-primary'>Posts</h4>  
<?php foreach($rows as $value): ?>
    <h6 class='text-center bg-info'><?php echo $value['title']; ?></h6>";
    <p><?php echo $value['body']."<br>Date posted on: <small>".$value['create_date']."</small>"?></p>
    <form class='form-group' action='<?php $_SERVER['PHP_SELF'];?>' method='post'>
     <input type="hidden" name="delete_id" value="<?php echo $value['id'];?>">
      <button class='form-control col-md-3 btn btn-danger' type='submit' name='delete'>Delete</button>
    </form>
    
<?php endforeach ?>