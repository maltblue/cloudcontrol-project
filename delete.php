<?php
    require_once('config.php');
    require_once('includes/template.header.php');
    
    // find all staff members
    $result = $db->fetchRow('SELECT * FROM staff WHERE id = ?', $_GET['id']);
    $form = getDeleteForm();
    $form->setDefaults(array(
            'id' => $_GET['id']
        )
    )->setAction('/delete.php?id='.$_GET['id']);
?>

<div id="container">
  <header>
      <h1>Malt Blue / cloudControl :: Staff Manager</h1>
  </header>
  <div id="main" role="main">
      <h2>Delete Staff Member</h2>
      <?php 
          if (!empty($_POST)) {
              if ($form->isValid($_POST)) {
                  $status = $db->delete('staff', 
                      'id = ' . (int)$form->id->getValue()
                  );
                  if ($status) {
                      print 'Delete was successful. <a href="/list.php">View All?</a>';
                  } else {
                      print 'Delete was NOT successful. <a href="/list.php">View All?</a>';
                  }
              } else {
                  print $form->render($view);
              }
          } else {
              print $form->render($view);
          }
        ?>
  </div>
  <footer>

  </footer>
</div> <!--! end of #container -->

<?php
    require_once('includes/template.footer.php');
?>