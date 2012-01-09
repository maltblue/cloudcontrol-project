<?php
    require_once('config.php');
    require_once('includes/template.header.php');
    
    $sqlFindAll = 'SELECT * FROM staff ORDER BY lastName DESC LIMIT 10';
    
    // find all staff members
    if ($m) {
        if (($result = $m->get($cacheKey)) === FALSE) {
            $result = $db->fetchAll($sqlFindAll);
            echo "fetched result from database<br />";
            $m->set($result, $cacheKey);
        } else {
            echo "fetched result from cache<br />";
        }
    } else {
        $result = $db->fetchAll($sqlFindAll);
        echo "fetched result from database<br />";
    }
?>
	<div id="container">
      <header>
          <h1>Malt Blue / cloudControl :: Staff Manager</h1>
      </header>
      <div id="main" role="main">
          <ul>
              <li><a href="/add.php" 
                  title="add staff member">add staff member</a></li>
          </ul>
          <h2>List Staff</h2>
          <table>
              <thead>
                  <tr>
                      <th>First Name</th>
                      <th>Last Name</th>
                      <th>Email Address</th>
                      <th>Occupation</th>
                      <th></th>
                  </tr>
              </thead>
              <tbody>
              <?php foreach ($result as $key => $staffMember) : ?>
                  <tr>
                      <td><?php print $staffMember->firstName; ?></td>
                      <td><?php print $staffMember->lastName; ?></td>
                      <td><?php print $staffMember->emailAddress; ?></td>
                      <td><?php print $staffMember->occupation; ?></td>
                      <td>
                          <a href="/edit.php?id=<?php print $staffMember->id; ?>" 
                              title="edit this staff member">edit</a> | 
                          <a href="/delete.php?id=<?php print $staffMember->id; ?>" 
                                title="delete this staff member">delete</a>
                      </td>
                  </tr>
              <?php endforeach;?>
              </tbody>
              <tfoot>
                  <tr>
                      <td colspan="5">
                          record count: <?php print count($result); ?>
                      </td>
                  </tr>
              </tfoot>
          </table>
      </div>
      <footer>
          copyright &copy; <a href="http://www.maltblue.com" 
          title="Malt Blue Limited">Malt Blue Limited</a> 2001, all rights reserved.
      </footer>
    </div> <!--! end of #container -->

<?php require_once('includes/template.footer.php'); ?>