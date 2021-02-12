<?php
    //connect to the database

    $servername= "localhost";
    $username="root";
    $password="";
    $database="todoNotes";

    //connection
    $conn= mysqli_connect($servername, $username, $password, $database);


    //checking connection
    if(!$conn){
      die("Unable to connect to Database. Error: ".mysqli_connect_error());
    }else{
      echo "Connected to Database!";
    }

    //delete the note
    if(isset($_GET['delete'])){
      $sno= $_GET['delete'];

      //preapre statemnet
      $sql= "DELETE FROM `notes` WHERE `notes`.`sno` = $sno";
      
      //run stmt
      $result= mysqli_query($conn, $sql);

      //check deletion
      if ($result) {
        echo "  Deleted!";
      }else{
        echo "  Error deleting: ". mysqli_error($conn);
      }
    }


    //check request method
    if($_SERVER['REQUEST_METHOD'] == "POST"){


      //if there is already s no 
      if(isset($_POST['snoEdit'])){

        //then Update the notes
        $sno= $_POST['snoEdit'];
        $title= $_POST['title_edit'];
        $desc= $_POST['desc_edit'];

        //prepare sql statement
        $sql= "UPDATE `notes` SET `title`= '$title', `description`= '$desc' WHERE `notes`.`sno`= $sno";

        //run sql query
        $result= mysqli_query($conn, $sql);


      }else{
        //Insert new note

          //get data from form
        $title= $_POST["title"];
        $desc= $_POST["desc"];

        //prepare sql statement
          $sql= "INSERT INTO `notes` (`title`, `description`) 
          VALUES ('$title', '$desc')";

          //run sql query
          $result= mysqli_query($conn, $sql);

          //check insertion
          if ($result) {
            echo "  Inserted";
          }else{
            echo "  Error inserting: ". mysqli_error($conn);
          }
      }

    

    }
?>


<!doctype html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
    integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

  <!--Jquery Datatable CSS-->
  <link rel="stylesheet" href="//cdn.datatables.net/1.10.23/css/jquery.dataTables.min.css">

  <title>TODO List</title>
</head>

<body>


  <!-- Button trigger edit modal -->
  <!--<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#editmodal">
  Edit Modal
</button> -->

  <!-- Edit Modal -->
  <div class="modal fade" id="editmodal" tabindex="-1" role="dialog" aria-labelledby="editmodalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editmodalLabel">Edit Note</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <div class="modal-body">
          <form action="/PHP_Crud/index.php" method="POST">
            <input type="hidden" name="snoEdit" id="snoEdit">
            <div class="form-group">
              <label for="title">Note Title</label>
              <input type="text" class="form-control" id="title_edit" name="title_edit" placeholder="Enter title"
                required>
            </div>

            <div class="form-group">
              <label for="desc">Note Description</label>
              <textarea class="form-control" id="desc_edit" name="desc_edit" rows="3" placeholder="note goes here..."
                required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Update Note</button>
          </form>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary">Save changes</button>
        </div>
      </div>
    </div>
  </div>


  <!--Navigation Bar-->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="#">TODO App</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
      aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item active">
          <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">About</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Contact Us</a>
        </li>


      </ul>
    </div>
  </nav>

  <div class="container my-3">

    <h2>Add a note</h2>

    <form action="/PHP_Crud/index.php" onsubmit="return validate()" method="POST">
      <div class="form-group">
        <label for="title">Note Title</label>
        <input type="text" class="form-control" id="title" name="title" placeholder="Enter title" required>
      </div>

      <div class="form-group">
        <label for="desc">Note Description</label>
        <textarea class="form-control" id="desc" name="desc" rows="3" placeholder="note goes here..."
          required></textarea>
      </div>
      <button type="submit" class="btn btn-primary">Add Note</button>
    </form>
  </div>

  <div class="container my-3">

    <table class="table" id="table">
      <thead>
        <tr>
          <th scope="col">S.N.</th>
          <th scope="col">Title</th>
          <th scope="col">Description</th>
          <th scope="col">Actions</th>
        </tr>
      </thead>
      <tbody>

        <?php

      //Displaying notes

        $sql= "SELECT *FROM `notes`";
        $result= mysqli_query($conn, $sql);

        $sno= 0;
        while($row = mysqli_fetch_assoc($result)){
          $sno++;
          echo "<tr>" .
          "<th scope='row'>". $sno. "</th>" .
          "<td>" .$row['title']. "</td>" . 
          "<td>" .$row['description']. "</td>" .
          "<td>".
          "<button class='edit btn-sm btn btn-primary' id=".$row['sno']."> Edit </button>".
          "  ".
          "<button class='delete btn-sm btn btn-danger' id=d".$row['sno']."> Delete </button>". 
          "</td> " .
        "</tr>";


      


        }
        
        ?>

      </tbody>
    </table>


  </div>

  <!--Javascript Validation & DataTable-->
  <script>
    function validate() {
      var title = document.getElementById("title").value;
      var description = document.getElementById("desc").value;
      if (title == "" || description == "") {
        alert("Both Title and Description should be filled!");
        return false;
      }
      if (title.length > 40) {
        alert("title must be less than 50 Characters!");
        return false;
      }
    }
  </script>
  <script>
    //lunch modal on clicking edit
    edits = document.getElementsByClassName('edit');
    Array.from(edits).forEach((element) => {
      element.addEventListener("click", (e) => {
        console.log("edit",);
        tr = e.target.parentNode.parentNode;
        title = tr.getElementsByTagName("td")[0].innerText;
        desc = tr.getElementsByTagName("td")[1].innerText;
        console.log(title, desc);

        title_edit.value = title;
        desc_edit.value = desc;

        snoEdit.value = e.target.id;

        $('#editmodal').modal('toggle');
      })
    })

    //delete warning
    deletes = document.getElementsByClassName('delete');
    Array.from(deletes).forEach((element) => {
      element.addEventListener("click", (e) => {
        console.log("delete",);
        sno= e.target.id.substr(1,);
        
        if(confirm('Do you want to delete?')){
          window.location= `/PHP_Crud/index.php?delete=${sno}`;
      }else{

        }
      })
    })
  </script>




  <!-- Optional JavaScript -->
  <!-- jQuery first, then Popper.js, then Bootstrap JS -->
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
    integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
    crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
    integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"
    crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
    integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
    crossorigin="anonymous"></script>

  <!--jQuery Datatable JS-->
  <script src="//cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>

  <script>
    //data table
    $(document).ready(function () {
      $('#table').DataTable();
    });

  </script>

</body>

</html>