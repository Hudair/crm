<?php
echo $this->db->last_query(); exit();
$res = $this->db->query("");
echo '<pre>'; print_r(); exit();
$res = $this->db->query("");

$res = $this->db->query("SELECT * FROM   WHERE ")->result();