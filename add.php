<?php
    require_once('config.php');
    require_once('includes/template.header.php');
    
    // find all staff members
    $result = $db->fetchRow('SELECT * FROM staff');

    $form = getManageForm();
    $form->setAction('/add.php');
    $form->id->setRequired(false);
    $form->id->setIgnore(true);
?>

<div id="container">
  <header>
      <h1>Malt Blue / cloudControl :: Staff Manager</h1>
  </header>
  <div id="main" role="main">
      <h2>Add Staff Member</h2>
      <?php 
        if (!empty($_POST)) {
            if ($form->isValid($_POST)) {
                $status = $db->insert('staff', $form->getValues());
                clearCache($manager, $cacheKey);
                if ($status) {
                    print 'Creation was successful. <a href="/list.php">View All?</a>';
                } else {
                    print 'Creation was NOT successful. <a href="/list.php">View All?</a>';
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