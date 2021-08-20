<?php
session_start();
session_destroy();
header('location: ' . $router->generate_url('login'));
exit();