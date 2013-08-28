<?php

$conn = new MongoClient;
$db = $conn->mustacheTest;
$userTbl = $db->users;  // or $userTbl = $conn->mustacheTest->users;

if (!isset($_GET['action'])) {
    init($userTbl);
    $cursor = $userTbl->find();
    
    $userList = array();
    foreach ($cursor as $id => $value) {
        $userList[] = array( 'id' => $id, 'name' => $value['name'] );
    }
}
elseif ($_GET['action'] == 'details' && isset($_GET['id'])) {
    $userDetails = $userTbl->findOne(array('_id' => new MongoId($_GET['id'])));
    header('Content-type: application/json');
    echo json_encode($userDetails);
    exit;
}

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title></title>
    <style>
    #users { float:left; list-style:none; margin:0; padding:0; width:150px; }
    #details { background-color:#ccc; float:left; margin-left:-.5em; width:300px; }
    li.active { background-color:#ccc; }
    </style>
</head>
<body>
    <div>
        <ul id="users">
            <?php foreach($userList as $user) : ?>
            <li><a href="?action=details&id=<?php echo $user['id'] ?>"><?php echo $user['name'] ?></a></li>
            <?php endforeach; ?>
        </ul>
        <div id="details"></div>
    </div>
    
    <script src="mustache-js/mustache.js"></script>
    <script>
    var details = document.getElementById("details");
    var userList = document.getElementById("users");

    var req = new XMLHttpRequest();

    // get template
    var tpl = null;
    req.onload = function(){
        tpl = Mustache.compile(this.responseText);
    }
    req.open("get", "partials/partial.mustache");
    req.send();

    // set up onclick
    var userListRows = userList.getElementsByTagName("li");
    userList.addEventListener("click", function(e){
        e = e || event;
        var target = e.target || e.srcElement;
        if (target.nodeName === 'A') {

            for (var i=0; i<userListRows.length; i++) {
                userListRows[i].className = '';
            }

            req.onload = function(){
                var json = JSON.parse(this.responseText);
                details.innerHTML = tpl(json);
                target.parentNode.className = 'active';
            };
            req.open("get", target.href);
            req.send();

        }
        e.preventDefault();
    });
    </script>
</body>
</html>
<?php
function init($userTbl) {
    if ($userTbl->count() <= 0) {
        // seed
        $users = array(
            array(
                'name' => 'Frasier Crane',
                'details' => array(
                    'street-address' => '84 Beacon St.',
                    'city'           => 'Boston',
                    'state'          => 'MA',
                    'zip'            => '02108',
                    'phone'          => '555-555-5555'
                )
            ),
            array(
                'name' => 'Frank Zappa',
                'details' => array(
                    'street-address' => '7885 Woodrow Wilson Drive',
                    'city'           => 'Los Angelos',
                    'state'          => 'CA',
                    'zip'            => '90046'
                )
            ),  
        );

        foreach ($users as $user) {
            $userTbl->insert($user);
        }
    }

    return $userTbl;
}
?>