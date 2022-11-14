<?php 
  // Connect to database
  $servername = 'localhost';
  $username = 'root';
  $password = '';
  $database = 'notes';

  // Create a connection
  $conn = mysqli_connect($servername, $username, $password, $database);

  // Die if connection was not successful
  if(!$conn){
    die("Sorry! we failed to connect: ". mysqli_connect_error());
  } 
  // else {
  //   echo "Connection was successfull<br>";
  // }

  // Insertion
  if ($_SERVER['REQUEST_METHOD'] == 'POST'){

    if(isset($_POST['snoEdit'])){
      // update 
      // echo "YES";
      // exit();
      // insert
      $sno = $_POST['snoEdit'];
      $title = $_POST['titleEdit'];
      $description = $_POST['descriptionEdit'];
      // query
      $sql = "UPDATE `notes` SET `title` = '$title', `description` = '$description' WHERE `notes`.`sno` = $sno";
      $result = mysqli_query($conn, $sql);
      if($result){
        echo "The record has been inserted successfully...<br>";
      } else {
        echo "Error ==>> " . mysqli_error($conn);
      }
    }
    else {   
      // insert
      $title = $_POST['title'];
      $description = $_POST['description'];
      // query
      $sql = "INSERT INTO `notes` (`title`, `description`) VALUES ('$title', '$description')";
      $result = mysqli_query($conn, $sql);
      
      if($result){
        echo "The record has been inserted successfully...<br>";
      } else {
        echo "Error ==>> " . mysqli_error($conn);
      }
    } 
  }

  // Delete
  if(isset($_GET['delete'])){
    $sno = $_GET['delete'];
    $sql = "DELETE FROM `notes` WHERE `notes`.`sno` = $sno";
    $result = mysqli_query($conn, $sql);
  }
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PHP CRUD</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
    <link href="//cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css" rel="stylesheet" >  <!--  for data table -->
  </head>

  <body>

    <!-- Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="editModalLabel">Edit Record</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form action="/crud/index.php" method="post" class="row g-3 w-50">
              <div class="modal-body">
                <input type="hidden" name="snoEdit" id="snoEdit">
                <div class="mb-3 form-group">
                    <label for="formGroupExampleInput" class="form-label">Note Title</label>
                    <input type="text" class="form-control" id="titleEdit" name="titleEdit" placeholder="Enter title">
                </div>
                <div class="mb-3 form-group">
                    <label for="exampleFormControlTextarea1" class="form-label">Note Description</label>
                    <textarea class="form-control" id="descriptionEdit" name="descriptionEdit" rows="3" placeholder="Enter description"></textarea>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save changes</button>
              </div>
          </form>
        </div>
      </div>
    </div>

    <div class="container my-3 d-flex flex-column justify-content-center">
        <h2>Add a Note</h2>
        <form action="/crud/index.php" method="post" class="row g-3 w-50">
            <div>
                <label for="formGroupExampleInput" class="form-label">Note Title</label>
            </div>
            <div>
                <input type="text" class="" id="title" name="title" size="50" placeholder="Enter title">
            </div>
            <div>
                <label for="exampleFormControlTextarea1" class="form-label">Note Description</label>
            </div>
            <div>
                <textarea id="description" name="description" rows="3" cols="50" placeholder="Enter description"></textarea>
            </div>
            <div>
                <button type="submit" class="btn btn-primary mb-3" >Add Note</button>
            </div> 
        </form>
    </div>

    <div>

      <table class="table">
        <thead>
          <tr>
            <th scope="col">S.no</th>
            <th scope="col">Title</th>
            <th scope="col">Description</th>
            <th scope="col">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php 
            $sql = "SELECT * FROM `notes`";
            $result = mysqli_query($conn, $sql);
            $sno = 0;
            while($row = mysqli_fetch_assoc($result)){
              // echo $var_dump($row);
              $sno += 1;
              echo "<tr>
                      <th scope='row'> ". $sno ." </th>
                      <td> ". $row['title'] ." </td>
                      <td> ". $row['description'] ." </td>
                      <td> <button class='edit btn btn-sm btn-primary' id=" . $row['sno'] . " >Edit</button> <button class='delete btn btn-sm btn-primary' id=d".$row['sno']." >Delete</button> </td>
                    </tr>";
            }
          ?>
        </tbody>
      </table>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.1.js" integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI=" crossorigin="anonymous"></script>

    <!-- for data table -->
    <script src="//cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>  
    <script>
      $(document).ready( function () {
        $('#myTable').DataTable();
      } );  
    </script>

    <script>
      edits = document.getElementsByClassName('edit');
      Array.from(edits).forEach((element) => {
        element.addEventListener("click", (e)=> {
          tr = e.target.parentNode.parentNode;
          title = tr.getElementsByTagName("td")[0].innerText;
          description = tr.getElementsByTagName("td")[1].innerText;

          titleEdit.value = title;
          descriptionEdit.value = description;

          snoEdit.value = e.target.id;

          $('#editModal').modal('toggle');
        })
      })

      deletes = document.getElementsByClassName('delete');
      Array.from(deletes).forEach((element) => {
        element.addEventListener("click", (e)=> {
          sno = e.target.id.substr(1,);

          if(confirm("Are you Sure You want to delete this note!")){
            window.location = `/crud/index.php?delete=${sno}`;
          }
          else {
            console.log("NO");
          }
        })
      })
    </script>

    
  </body>
</html>