<?php ob_start();
include('config.php');
$message = '';
$alert_class = 'success';
//delete the note
if (isset($_GET['delete'])) {
  $sno = $_GET['delete'];
  //preapre statemnet
  $sql = "DELETE FROM `notes` WHERE `notes`.`sno` = $sno";

  //run stmt
  $result = mysqli_query($conn, $sql);

  //check deletion
  if ($result) {
    $message = "Todo Deleted Successfully!";
  } else {
    $message = "  Failed to Delete Todo " . mysqli_error($conn);
    $alert_class = 'danger';
  }
}


//check request method
if (!empty($_POST)) {
  //if there is already s no 
  if (isset($_POST['action']) && $_POST['action']=='updateTodo') {

    //then Update the notes
    $sno =mysqli_real_escape_string($conn,$_POST["snoEdit"]);
    $title = mysqli_real_escape_string($conn,$_POST["title_edit"]);
    $desc = mysqli_real_escape_string($conn,$_POST["desc_edit"]);

    //prepare sql statement
    $sql = "UPDATE `notes` SET `title`= '$title', `description`= '$desc' WHERE `notes`.`sno`= $sno";

    //run sql query
    $result = mysqli_query($conn, $sql);
    if ($result) {
      $message = "Todo Updated Successfully!";
    } else {
      $message = "  Failed to Update Todo: " . mysqli_error($conn);
      $alert_class = 'danger';
    }
  } /* else {
 
  } */

  else if(isset($_POST['action']) && $_POST['action']=='addTodo'){
       //Insert new note

    //get data from form
    $title = mysqli_real_escape_string($conn,$_POST["title"]);
    $desc = mysqli_real_escape_string($conn,$_POST["desc"]);

    //prepare sql statement
    $sql = "INSERT INTO `notes` (`title`, `description`) 
          VALUES ('$title', '$desc')";

    //run sql query
    $result = mysqli_query($conn, $sql);

    //check insertion
    if ($result) {
      $message = "  Todo Created Successfully";
    } else {
      $message = " Failed to Create Todo: " . mysqli_error($conn);
      $alert_class = 'danger';
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
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

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
  <div class="modal fade" id="editmodal" tabindex="-1" role="dialog" aria-labelledby="editmodalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editmodalLabel">Edit Note</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <div class="modal-body">
          <form method="POST">
            <input type="hidden" name="snoEdit" id="snoEdit">
            <div class="form-group">
              <label for="title">Note Title</label>
              <input type="text" class="form-control" id="title_edit" name="title_edit" placeholder="Enter title" required>
            </div>

            <div class="form-group">
              <label for="desc">Note Description</label>
              <textarea class="form-control" id="desc_edit" name="desc_edit" rows="3" placeholder="note goes here..." required></textarea>
            </div>
            <input type="hidden" name="action" value="updateTodo">
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
    <a class="navbar-brand" href="<?php echo $site_url; ?>">TODO App</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item active">
          <a class="nav-link" href="<?php echo $site_url; ?>">Home <span class="sr-only">(current)</span></a>
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
    <?php if ($message != '') { ?>
      <div class="row">
        <div class="col-md-12">
          <div class="alert alert-<?php echo $alert_class; ?> fade show" role="alert">
            <?php echo $message; ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        </div>
      </div>
    <?php } ?>
    <form method="POST" id="todoform">
      <div class="form-group">
        <label for="title">Note Title</label>
        <input type="text" class="form-control" id="title" name="title" placeholder="Todo title" required>
        <span id="titleerr" class="text-danger"></span>
      </div>

      <div class="form-group">
        <label for="desc">Note Description</label>
        <textarea class="form-control" id="desc" name="desc" rows="3" placeholder="Note goes here..." required></textarea>
        <span id="descerr" class="text-danger"></span>
      </div>
      <input type="hidden" name="action" value="addTodo">
      <input type="submit" class="btn btn-primary" value="Add Note">
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

        $sql = "SELECT *FROM `notes`";
        $result = mysqli_query($conn, $sql);

        $sno = 0;
        while ($row = mysqli_fetch_assoc($result)) {
          $sno++;
          echo "<tr>" .
            "<th scope='row'>" . $sno . "</th>" .
            "<td>" . $row['title'] . "</td>" .
            "<td>" . $row['description'] . "</td>" .
            "<td>" .
            "<button class='edit btn-sm btn btn-primary' id=" . $row['sno'] . "> Edit </button>" .
            "  " .
            "<button class='delete btn-sm btn btn-danger' id=d" . $row['sno'] . "> Delete </button>" .
            "</td> " .
            "</tr>";
        }

        ?>

      </tbody>
    </table>


  </div>
  <script>
    //lunch modal on clicking edit
    edits = document.getElementsByClassName('edit');
    Array.from(edits).forEach((element) => {
      element.addEventListener("click", (e) => {
        console.log("edit", );
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
        console.log("delete", );
        sno = e.target.id.substr(1, );

        if (confirm('Do you want to delete?')) {
          window.location = `index.php?delete=${sno}`;
        } else {

        }
      })
    })
  </script>




  <!-- Optional JavaScript -->
  <!-- jQuery first, then Popper.js, then Bootstrap JS -->
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js" integrity="sha512-37T7leoNS06R80c8Ulq7cdCDU5MNQBwlYoy1TX/WUsLFC2eYNqtKlV0QjH7r8JpG/S0GUMZwebnVFLPd6SU5yg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <!--jQuery Datatable JS-->
  <script src="//cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
  <script>
    /* For Validation */
    $(document).ready(function() {
      $("#todoform").validate({
        // Specify validation rules
        ignore: [],
        rules: {
          title: {
            minlength: 10,
            maxlength: 200,
          },
          desc: {
            minlength: 20,
            maxlength: 500,
          }
        },
        errorPlacement: function(error, element) {
          if (element.attr("name") == "title") {
            error.appendTo("#titleerr");
          } else if (element.attr("name") == "desc") {
            error.appendTo("#descerr");
          } else {
            error.insertAfter(element)
          }

        },
        messages: {
          title: {
            required: "Please Enter Todo Title",
          },
          desc: {
            required: "Please Enter Todo Description",
            minlength: "At least {0} Character of Your Todo Description",
            maxlength: "Only {0} Character allow of Your Todo Description",
          },
        },

      });
    });
  </script>
  <script>
    //data table
    $(document).ready(function() {
      $('#table').DataTable();
    });
  </script>

</body>

</html>