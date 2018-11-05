<!-- resources/views/layouts/appmain.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <!-- Meta, title, CSS, favicons, etc. -->
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title><?php include "../../title.php"; ?></title>

  <?php require "../engine/mysqlidbconnect.php"; ?>
  <?php require "../engine/function.php"; ?>
  <?php require "lay_csslink.php"; ?>
  <?php  
  fnCheckLoginStatus();
  ?>
</head>

<body class="nav-md footer_fixed">
  <div class="container body">
    <div class="main_container">
     <?php include "lay_sidebarfull.php"; ?>
     <!-- top navigation -->
     <?php include "lay_navbar.php"; ?>
     <!-- /top navigation -->

     <!-- page content -->
