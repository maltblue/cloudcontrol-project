<?php
    require_once('config.php');
    require_once('includes/template.header.php');
    
    // find all staff members
    $result = $db->fetchRow('SELECT * FROM staff WHERE id = ?', $_GET['id']);

    $form = getManageForm();
    $form->setDefaults((array)$result)
         ->setAction('/edit.php?id='.$_GET['id']);
    $form->submit->setLabel('Update User');
?>

<div id="container">
  <header>
      <h1>Malt Blue / cloudControl :: Staff Manager</h1>
  </header>
  <div id="main" role="main">
      <h2>Edit Staff Member</h2>
      <?php 
        if (!empty($_POST)) {
            if ($form->isValid($_POST)) {
                $status = $db->update('staff', 
                    $form->getValues(), 
                    'id = ' . (int)$form->id->getValue()
                );
                if ($status) {
                    print 'Update was successful. <a href="/list.php">View All?</a>';
                } else {
                    print 'Update was NOT successful. <a href="/list.php">View All?</a>';
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